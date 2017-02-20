<?php

namespace Modules\Enterprise\Models;

use Auth;
use DB;
use Unified\Http\Helpers\QueryParameters;
use Unified\Models\QueryTrait;
use Modules\Enterprise\Models\VesQueryTrait;

/**
 * Radio Node model.
 */
final class VesRadioNode {
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
        
        // get all radio node properties that is requred to be present
        $rnFields = QueryParameters::popElementsStartingWith ( $fields, 'rn.' );
        $rnFilters = QueryParameters::popFiltersStartingWith ( $filters, 'rn.' );
        // get all band properties that is requred to be present
        $bandFields = QueryParameters::popElementsStartingWith ( $fields, 'b.' );
        $bandFilters = QueryParameters::popFiltersStartingWith ( $filters, 'b.' );
        
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
        // add filter allowing to receive only RadioNode devices 5056
        $query->where ( 'nd.type_id', '=', 5057 );
        
        // TODO verify that node is allowed to be accesed by particular user.
        if (! is_null ( $rootNode )) {
            $query->where ( 'nntm.node_map', 'LIKE', '%.' . $rootNode . '.%' );
        }
        
        // Apply filters
        $query = self::setFilters ( $query, $filters );
        
        // Execute query
        $dataName = 'radioNodes';
        $retVal = self::getResults ( $query, $config->isCount (), $dataName );
   
        // Add service node details
        foreach ( $retVal ['radioNodes'] as &$node ) {
            $node->__deleted = 0;
            // Add radio node properties if necessary
            if (! empty ( $rnFields ) || ! empty ( $rnFilters )) {
                $props = self::getProperties ( $node->__device_id__ );
                if (! self::checkFilters ( $rnFilters, $props )) {
                    $node->__deleted = 1;
                    continue;
                }
                foreach ( $rnFields as $prop ) {
                    $fieldName = self::getFieldName ( $prop );
                    $node->$fieldName = self::getPropValue ( $prop, $props );
                }
            }
            
            // Add/verify bands
            if (! empty ( $bandFields ) || ! empty ( $bandFilters )) {
                $bandMatch = false;
                    $bandFilterMatch = true;
                    $node->bands = self::getSubDevices ( $node->__device_id__,
                            $node->__node_map__,
                            5058,
                            $bandFields,
                            $bandFilters,
                            $bandFilterMatch );
                    if (! $bandFilterMatch) {
                        // No bands matching the requested band filter is present
                        $node->__deleted = 1;
                        continue;
                    }
            }
            
        }
        
        // FIXME Code below is the same code as in VesServiceNode pagination parser. REFACTOR IT.
        // Prepare object to be returned
        $offset = $config->getOffset ();
        $limit = $config->getLimit ();
        $skipped = 0;
        $returned = 0;
        $count = 0;
        // Remove support fields and apply pgination
        foreach ( $retVal [$dataName] as $key => &$node ) {
            // Delete node not matching filters
            if ($node->__deleted) {
                unset ( $retVal [$dataName] [$key] );
                continue;
            }
            // Count matching nodes
            $count ++;
            // Delete nodes if offset is present
            if ($offset > 0 && $skipped < $offset) {
                $skipped ++;
                unset ( $retVal [$dataName] [$key] );
                continue;
            }
            // Enforce limit if present
            if ($limit > 0 && $returned >= $limit) {
                unset ( $retVal [$dataName] [$key] );
                continue;
            }
            
            // Prepare returned object
            $returned ++;
            unset ( $node->__deleted );
            unset ( $node->__device_id__ );
            unset ( $node->__node_map__ );
        }
        $retVal [$dataName] = array_values ( $retVal [$dataName]);
        if ($config->isCount ()) {
            $retVal ['count'] = $count;
        }
        
        return $retVal;
    }
}
