<?php

namespace Unified\Models;

use Auth;
use DB;
use Unified\Http\Helpers\QueryParameters;
/**
 * Node 'model'.
 */
final class Node {
    use QueryTrait;
    
    /**
     * Return list of nodes matching provided filters
     */
    public static function getNodes(QueryParameters $config) {
        // Inject the device encryption key into the database.
        // Use include rather than require so that it doesn't completely fail if we don't have a working cswapi installation.
        // This might be useful for testing.
        @include_once ENV('CSWAPI_ROOT') . '/common/class/cssEncryption.php';
        
        if (class_exists('\cssEncryption')) {
            DB::statement('SET @css_encryption_key = :key', ['key' => \cssEncryption::getInstance()->getKey()]);
        }
        
        $filters = $config->getFilters();
        $sortby = $config->getSortby();
        $fields = $config->getFields();
        $control = $config->getControl();
        
        $updated = $config->getControlParam ( 'updated');
        $rootNode = $config->getControlParam ( 'root');
        $hasChildren = $config->getControlParam (  'hasChildren' );
        $children = $config->getControlParam (  'children' );
        
        if (!isset($rootNode) || ($rootNode == 0)) {
            // Get root node from user information
            $rootNode = Auth::user()->home_node_id;
        }
        
        // check if field sequence is requested
        $sequenceField = QueryParameters::popElementsStartingWith ( $fields, 'sequence' );
        
        // Check if port definition join is required
        $portDefJoinIsNecessary = 
            QueryParameters::isPresentInFilters($filters, 'ndpd.') ||
            QueryParameters::isPresentInFilters($sortby, 'ndpd.');
        
        // Check if device_port definition join is required
        $portJoinIsNecessary =
            $portDefJoinIsNecessary ||
            (! empty ( $sequenceField )) ||
            (! is_null ( $updated ));
            
        // Remove port related fields from field's list.
        // We will add ports information later
        $portDefsFields = QueryParameters::popElementsStartingWith ( $fields, 'ndpd.' );
        $portFields = QueryParameters::popElementsStartingWith ( $fields, 'ndp.' );
        if (! empty ( $portFields ) || ! empty ( $portDefsFields )) {
            // Add device ID to be latter used to search for port information
            // Field will be removed during port search
            $fields [] = 'nd.id as __device_id__';
        }
        
        if (! is_null ( $hasChildren ) || ! is_null ( $children )) {
            // Add node ID field to search for children information
            $fields [] = 'nntm.node_id as id';
        }
        
        if (! empty ( $sequenceField ) || ! is_null ( $updated )) {
            // Find largest sequence number related to the particular node
            $fields [] = DB::raw ( 
                'greatest(COALESCE(nntm.sequence,0), COALESCE(nnt.sequence,0), COALESCE(nd.sequence,0), COALESCE(ng.sequence,0), max(COALESCE(ndp.sequence,0))) as sequence' );
        }
        
        // Start query construction
        $query = DB::table ( 'css_networking_network_tree_map as nntm' );
        $query = self::setFields($query, $fields, $config->isCount());
        $query = $query->leftjoin ( 'css_networking_network_tree as nnt', 'nnt.id', '=', 'nntm.node_id' );
        $query = $query->leftjoin ( 'css_networking_device as nd', 'nd.id', '=', 'nnt.device_id' );
        $query = $query->leftjoin ( 'css_networking_group as ng', 'ng.id', '=', 'nnt.group_id' );
        if ($portJoinIsNecessary) {
            $query = $query->leftjoin ( 'css_networking_device_port as ndp', 'ndp.device_id', '=', 'nnt.device_id' );
        }
        if ($portDefJoinIsNecessary) {

            $query = $query->leftjoin ( 'css_networking_device_port_def as ndpd', 'ndpd.id', '=', 'ndp.port_def_id' );
        }
        
        $query = $query->leftjoin ( 'css_networking_device_type as ndt', 'ndt.id', '=', 'nd.type_id' );
        $query = $query->leftjoin ( 'css_networking_device_class as ndc', 'ndc.id', '=', 'ndt.class_id' );

        // add filter restricting acces to nodes marked as "build in progress"
        $query->where('nntm.build_in_progress', '=', 0);

        // TODO verify that node is allowed to be accesed by particular user.
        if (! is_null ( $rootNode )) {
            $query->where('nntm.node_map', 'LIKE', '%.'.$rootNode.'.%');
        }
        
        // Gorup by node id to avoid multiple node entries caused by multiple ports
        if ($portJoinIsNecessary) {
            $query = $query->groupBy('nnt.id');
        }

        if (! is_null ( $updated )) {
            $query = $query->having('sequence', '>', $updated);
            $query->orderBy ( 'sequence' );
        }

        // Apply filters
        $query = self::setFilters ( $query, $filters );
        
        // Apply sortby
        $query = self::setSortby ( $query, $config->getSortby() );
        
        // Set pagination parameters
        $query = self::setPagination ( $query, $config->getOffset (), $config->getLimit () );
        
        // Execute query
        $retVal = self::getResults ( $query, $config->isCount (), 'nodes' );
        
        // Get port information if necessary
        if (! empty ( $portFields ) || ! empty ( $portDefsFields )) {
            $fields = $portFields + $portDefsFields;
            foreach ( $retVal ['nodes'] as &$node ) {
                $query = DB::table ( 'css_networking_device_port as ndp' )->select ( $fields );
                $query = $query->leftjoin ( 'css_networking_device_port_def as ndpd', 'ndpd.id', '=', 'ndp.port_def_id' );
                $query = $query->where ('ndp.device_id',  $node->__device_id__ );
                $ports = $query->get();
                 // Add port info to deviceType object
                $node->ports = $ports;
                
                // Remove index used to get port information
                unset ( $node->__device_id__ );
            }
        }
        
        // Set hasChildren if requested
        if (! is_null ( $hasChildren ) || ! is_null ( $children )) {
            foreach ( $retVal ['nodes'] as &$node ) {
                $query = DB::table ( 'css_networking_network_tree_map as nntm' );
                $query = $query->leftjoin ( 'css_networking_network_tree as nnt', 'nnt.id', '=', 'nntm.node_id' );
                $data = $query->select ( 'nntm.node_id as id' )->where('nntm.deleted',0)
                ->where ( 'nnt.parent_node_id', $node->id )->get ();
                if (! is_null ( $hasChildren )) {
                    $node->hasChildren = ! empty ( $data );
                }
                if (! is_null ( $children)) {
                    $node->children = $data;
                }
            }
        }

        // Return latest sequence if parameter "updated" is present in a query
        if (! is_null ( $updated )) {
            $newSequence = $updated;
            if ( count (  $retVal['nodes'] ) > 0) {
                $newSequence = $retVal['nodes'] [count ( $retVal['nodes'] ) - 1]->sequence;
            }
            $retVal['updated'] = $newSequence;
        }
        
        return $retVal;
    }
    
    /**
     * Delete node
     *
     * @param unknown $content
     *            parameters
     */
    public static function deleteNode($content) {
        // Verify node ID
        $nodeId = QueryTrait::popByKey ( $content, 'id' );
        if (is_null ( $nodeId )) {
            return QueryTrait::error ( 'Node ID is empty.' );
        }
        $curNode = NetworkTree::find ( $nodeId );
        if ($curNode == false || count ( $curNode ) == 0) {
            return QueryTrait::error ( 'Node does not exists.' );
        }
        
        // Delete node and subnodes
        NetworkTreeMap::deleteByNodeId ( $nodeId );
        
        return self::checkFinalContent ( $content );
    }
    
    /**
     * Modify node attributes
     * 
     * @param unknown $content Attributes to be modified
     */
    public static function modifyNode($content) {
        try {
            DB::beginTransaction ();
            // Verify node ID
            $nodeId = QueryTrait::popByKey ( $content, 'id' );
            if (is_null ( $nodeId )) {
                return QueryTrait::error ( 'Node ID is empty.' );
            }
            $curNode = NetworkTree::find ( $nodeId );
            if ($curNode == false || count ( $curNode ) == 0) {
                return QueryTrait::error ( 'Node does not exists.' );
            }
            
            // Verify parent if it is present in the list of modified parameters
            $parentId = QueryTrait::popByKey ( $content, 'parent_node_id' );
            if (!empty ( $parentId ) && $curNode->parent_node_id !== $parentId) {
                $parentNtm = NetworkTreeMap::where ( 'node_id', '=', $parentId )->first ();
                if ($parentNtm == false || count ( $parentNtm ) == 0) {
                    return QueryTrait::error ( 'Parent ID node is missing in network tree map.' );
                }
                $parentNt = NetworkTree::getByNodeId ( $parentId );
                if ($parentNt == false || count ( $parentNt ) == 0) {
                    return QueryTrait::error ( 'Parent ID node is missing in network tree.' );
                }
                // Node has been moved
                // TODO modify node_map, breadcrumb, etc.
            }
            
            // Create Device or Group
            if ($curNode->group_id != 0) {
                $group = Group::find ( $curNode->group_id );
                self::updateGroup ( $group, $content );
            } else {
                $device = Device::find ( $curNode->device_id );
                self::updateDevice ( $device, $content );
                // if ports are present - replace current ports with new data
                self::updatePorts ( $device, $content );
            }
            
            self::updateNodeTree ( $curNode, $content );
            
            $nodeMap = NetworkTreeMap::where ( 'node_id', '=', $curNode->id )->first ();
            self::updateNodeMap ( $nodeMap, $content );
            
            $retVal = self::checkFinalContent ( $content );
            if ($retVal ['status']) {
                DB::commit ();
            } else {
                DB::rollback ();
            }
        } catch ( Exception $e ) {
            $retVal = QueryTrait::error ( 'Unable to modify node: ' . $e->getMessage );
            DB::rollback ();
        }
        
        return $retVal;
    }
    
    /**
     * Add node
     *
     * @param unknown $content
     *            Node content
     * @return Service response object with the following status codes:
     */
    public static function addNode($content) {
        try {
            DB::beginTransaction ();
            // Verify node type ID
            $nodeTypeId = QueryTrait::popByKey ( $content, 'type_id' );
            if (is_null ( $nodeTypeId )) {
                return QueryTrait::error ( 'Node type ID is empty.' );
            }
            $nodeType = DeviceType::find ( $nodeTypeId );
            if ($nodeTypeId != 0 && ($nodeType == false || count ( $nodeType ) == 0)) {
                return QueryTrait::error ( 'Invalid node type ID.' );
            }
            
            // Verify parent
            $parentId = QueryTrait::popByKey ( $content, 'parent_node_id' );
            if (empty ( $parentId )) {
                return QueryTrait::error ( 'Parent ID is empty.' );
            }
            $parentNtm = NetworkTreeMap::where ( 'node_id', '=', $parentId )->first ();
            if ($parentNtm == false || count ( $parentNtm ) == 0) {
                return QueryTrait::error ( 'Parent ID node is missing in network tree map.' );
            }
            $parentNt = NetworkTree::find ( $parentId );
            if ($parentNt == false || count ( $parentNt ) == 0) {
                return QueryTrait::error ( 'Parent ID node is missing in network tree.' );
            }
            
            // Create Device or Group
            if ($nodeTypeId == 0) {
                $groupOrDevice = new Group ();
                self::updateGroup ( $groupOrDevice, $content );
            } else {
                $groupOrDevice = new Device ();
                $groupOrDevice->setAttribute ( 'type_id', $nodeTypeId );
                self::updateDevice ( $groupOrDevice, $content );
                // if ports are present - replace current ports with new data
                self::updatePorts ( $groupOrDevice, $content );
            }
            
            // Create networkTree record
            $node = new NetworkTree ();
            $node->setAttribute ( 'parent_node_id', $parentNt->id );
            $node->setAttribute ( 'parent_group_id', $parentNt->group_id );
            $node->setAttribute ( 'parent_device_id', $parentNt->device_id );
            $node->setAttribute ( 'group_id', ($nodeTypeId == 0) ? $groupOrDevice->id : 0 );
            $node->setAttribute ( 'device_id', ($nodeTypeId == 0) ? 0 : $groupOrDevice->id );
            self::updateNodeTree ( $node, $content );
            
            $nodeMap = new NetworkTreeMap ();
            $nodeMap->setAttribute ( 'node_id', $node->id );
            $nodeMap->setAttribute ( 'node_map', $parentNtm->node_map . $node->id . '.' );
            $nodeMap->setAttribute ( 'breadcrumb', $parentNtm->breadcrumb . ' >> ' . $groupOrDevice->name );
            self::updateNodeMap ( $nodeMap, $content );
            
            $retVal = self::checkFinalContent ( $content );
            if ($retVal ['status']) {
                $retVal ['nodeId'] = $node->id;
                DB::commit ();
            } else {
                DB::rollback ();
            }
        } catch ( Exception $e ) {
            $retVal = QueryTrait::error ( 'Unable to add node: ' . $e->getMessage );
            DB::rollback ();
        }
        
        return $retVal;
    }
    
    private static function updateDevice(&$device, &$content) {
        // Set device attributes (prefix "d.")
        QueryTrait::setEntityAttributesWithPrefix($device, $content, "d.");
        // Set common device/group attributes (prefix "dg.")
        QueryTrait::setEntityAttributesWithPrefix($device, $content, "dg.");
        $device->save ();
    }
    
    private static function updatePorts(&$device, &$content) {
        if (! isset ( $content ['ports'] ) || ! array_key_exists ( 'ports', $content )) {
            // Ports info is not present.
            return;
        }
        // delete old port info ports with refreshed data
        foreach ( DevicePort::getPortsByDeviceId ( $device->id ) as &$port ) {
            $port->delete ();
        }
        // add new port info
        foreach ( $content ['ports'] as $key => &$portContent ) {
            $localPort = new DevicePort ();
            // Set port attributes (prefix "ndp.")
            QueryTrait::setEntityAttributesWithPrefix($device, $portContent, "ndp.");
            $localPort->setAttribute ( 'device_id', $device->id );
            $localPort->save ();
            if (empty($portContent)) {
                unset($content ['ports'][$key]);
            }
        }
        if (empty($content ['ports'])) {
            unset($content ['ports']);
        }
    }

    private static function updateGroup(&$group, &$content) {
        // Set common device/group attributes (prefix "dg.")
        QueryTrait::setEntityAttributesWithPrefix($group, $content, "dg.");
        $group->save ();
    }
    
    private static function updateNodeMap(&$nodeMap, &$content) {
        // Set networking_tree_map attributes (prefix "ntm.")
        QueryTrait::setEntityAttributesWithPrefix($nodeMap, $content, "ntm.");
        $nodeMap->save ();
    }
    
    private static function updateNodeTree(&$node, &$content) {
        // Set networking_tree attributes (prefix "nt.")
        QueryTrait::setEntityAttributesWithPrefix($node, $content, "nt.");
        $node->save ();
    }
}
