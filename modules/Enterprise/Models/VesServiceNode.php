<?php

namespace Modules\Enterprise\Models;

use Auth;
use DB;
use Unified\Http\Helpers\QueryParameters;
use Unified\Models\QueryTrait;
use Modules\Enterprise\Models\VesQueryTrait;

/**
 * Service Node 'model'.
 */
final class VesServiceNode {
    use QueryTrait;
    use VesQueryTrait;
    
    /**
     * Return list of nodes matching provided filters
     */
    public static function getNodes(QueryParameters $config) {
        $filters = $config->getFilters ();
        $fields = $config->getFields ();
        $control = $config->getControl ();
        
        if (! isset ( $rootNode ) || ($rootNode == 0)) {
            // Get root node from user information
            $rootNode = Auth::user ()->home_node_id;
        }
        
        // get all service node properties that is requred to be present
        $serviceNodeProps = QueryParameters::popElementsStartingWith ( $fields, 'sn.' );
        $snFilters = QueryParameters::popFiltersStartingWith ( $filters, 'sn.' );
        // get all radio node properties that is requred to be present
        $rnFields = QueryParameters::popElementsStartingWith ( $fields, 'rn.' );
        $rnFilters = QueryParameters::popFiltersStartingWith ( $filters, 'rn.' );
        // get all band properties that is requred to be present
        $bandFields = QueryParameters::popElementsStartingWith ( $fields, 'b.' );
        $bandFilters = QueryParameters::popFiltersStartingWith ( $filters, 'b.' );
        // get all management device properties that is requred to be present
        $mdFields = QueryParameters::popElementsStartingWith ( $fields, 'md.' );
        $mdFilters = QueryParameters::popFiltersStartingWith ( $filters, 'md.' );
        // get all LAN device properties that is requred to be present
        $lanFields = QueryParameters::popElementsStartingWith ( $fields, 'ld.' );
        $lanFilters = QueryParameters::popFiltersStartingWith ( $filters, 'ld.' );
        
        // All other queries will be based on device ID and/or node map, so add them to the output objects.
        // They will be removed before return
        $fields [] = 'nd.id as __device_id__';
        $fields [] = 'nntm.node_map as __node_map__';
        
        // Start query construction
        $query = DB::table ( 'css_networking_network_tree_map as nntm' );
        $query = self::setFields ( $query, $fields, $config->isCount () );
        $query = $query->leftjoin ( 'css_networking_network_tree as nnt', 'nnt.id', '=', 'nntm.node_id' );
        $query = $query->leftjoin ( 'css_networking_device as nd', 'nd.id', '=', 'nnt.device_id' );
        
        // add filter restricting acces to nodes marked as "build in progress"
        $query->where ( 'nntm.build_in_progress', '=', 0 );
        // add filter restricting acces to nodes marked as deleted
        $query->where ( 'nntm.deleted', '=', 0 );
        // add filter allowing to receive only ServiceNode devices 5056
        $query->where ( 'nd.type_id', '=', 5056 );
        
        // TODO verify that node is allowed to be accesed by particular user.
        if (! is_null ( $rootNode )) {
            $query->where ( 'nntm.node_map', 'LIKE', '%.' . $rootNode . '.%' );
        }
        
        // Apply filters
        $query = self::setFilters ( $query, $filters );
        
        // Execute query
        $retVal = self::getResults ( $query, $config->isCount (), 'nodes' );
        
        // Add nativeId to the list of radioNode parameters if band fields or filters is present in the query
        if (! empty ( $bandFields ) || ! empty ( $bandFilters )) {
            if (! QueryParameters::isPresent ( $rnFields, "nativeId" )) {
                    $rnFields [] = "rn.native_id as nativeId";
            }
        }
        
        // Add service node details
        foreach ( $retVal ['nodes'] as &$node ) {
            $node->__deleted = 0;
            // Add service node properties if necessary
            if (! empty ( $serviceNodeProps ) || ! empty ( $snFilters )) {
                $props = self::getProperties ( $node->__device_id__ );
                if (! self::checkFilters ( $snFilters, $props )) {
                    $node->__deleted = 1;
                    continue;
                }
                foreach ( $serviceNodeProps as $prop ) {
                    $fieldName = self::getFieldName ( $prop );
                    $node->$fieldName = self::getPropValue ( $prop, $props );
                }
            }
            
            // Add/verify radio nodes
            if (! self::addSubDevices ( $node, "radioNodes", $rnFields, $rnFilters, 5057 )) {
                continue;
            }
            
            // Add/verify bands
            if (! empty ( $bandFields ) || ! empty ( $bandFilters )) {
                $bandMatch = false;
                foreach ( $node->radioNodes as &$rn ) {
                    $bandFilterMatch = true;
                    $rn->bands = self::getSubDevices ( $rn->__device_id__, 
                            $rn->__node_map__, 
                            5058, 
                            $bandFields, 
                            $bandFilters, 
                            $bandFilterMatch );
                    $bandMatch |= $bandFilterMatch;
                    if (! $bandMatch) {
                        // No bands matching the requested band filter is present
                        $node->__deleted = 1;
                        continue;
                    }
                }
            }
            
            // Add/verify management devices
            if (! self::addSubDevices ( $node, "mgmtDevices", $mdFields, $mdFilters, 5059 )) {
                continue;
            }
            
            if (! self::addSubDevices ( $node, "lanDevices", $lanFields, $lanFilters, 5061 )) {
                continue;
            }
        }
        
        // Prepare object to be returned
        $offset = $config->getOffset ();
        $limit = $config->getLimit ();
        $skipped = 0;
        $returned = 0;
        $count = 0;
        // Remove support fields
        foreach ( $retVal ['nodes'] as $key => &$node ) {
            // Delete node not matching filters
            if ($node->__deleted) {
                unset ( $retVal ['nodes'] [$key] );
                continue;
            }
            // Count matching nodes
            $count ++;
            // Delete nodes if offset is present
            if ($offset > 0 && $skipped < $offset) {
                $skipped ++;
                unset ( $retVal ['nodes'] [$key] );
                continue;
            }
            // Enforce limit if present
            if ($limit > 0 && $returned >= $limit) {
                unset ( $retVal ['nodes'] [$key] );
                continue;
            }
            
            // Prepare returned object
            $returned ++;
            unset ( $node->__deleted );
            unset ( $node->__device_id__ );
            unset ( $node->__node_map__ );
            if (isset ( $node->radioNodes )) {
                foreach ( $node->radioNodes as &$rn ) {
                    unset ( $rn->__device_id__ );
                    unset ( $rn->__node_map__ );
                }
            }
        }
        // We are removed some array elements, so reindex array
        $retVal ['nodes'] = array_values ( $retVal ['nodes']);
        
        if ($config->isCount ()) {
            $retVal ['count'] = $count;
        }
        
        return $retVal;
    }
    /**
     * Add data for each subdevice with type $subDeviceType to target object $node.
     * 
     * @param unknown $node
     *            target object
     * @param unknown $name
     *            name of the subobject containing list of the subdevices
     * @param unknown $fields
     *            List of requested fields
     * @param unknown $filters
     *            List of filters to match each device against
     * @param unknown $subDeviceType            
     * @return boolean true - filters are matched successfully, false otherwise
     */
    private static function addSubDevices(&$node, $name, $fields, $filters, $subDeviceType) {
        $matchFilters = true;
        
        if (! empty ( $fields ) || ! empty ( $filters )) {
            $matchFilters = true;
            $node->$name = self::getSubDevices ( $node->__device_id__, 
                    $node->__node_map__, 
                    $subDeviceType, 
                    $fields, 
                    $filters, 
                    $matchFilters );
            // REmove element if no fields is requested
            if (empty($fields)) {
                unset($node->$name);
            }
            // Mark node as deleted if no records matching filters is found
            if (! $matchFilters) {
                $node->__deleted = 1;
            }
        }
        return $matchFilters;
    }
    
}
