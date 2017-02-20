<?php

namespace Unified\Models;

use Auth;
use DB;
use Unified\Models\NetworkTreeMap;
use Unified\Http\Helpers\QueryParameters;
/**
 * Property log model.
 */
final class DevicePropLog {
    use QueryTrait;
    /**
     * Returns list of property logs matching provided query parameters
     * @param QueryParameters $config
     */
    public static function getPropertyLogs(QueryParameters $config) {
        $filters = $config->getFilters();
        $fields = $config->getFields();
        $control = $config->getControl();
        // Get more control parameters (page, etc.) if necessary
        $updated = $config->getControlParam ( 'updated');
        $parentNode = $config->getControlParam ( 'root');
        
        if (!isset($parentNode) || ($parentNode == 0)) {
            // Get parent node from user information
            $parentNode = Auth::user()->home_node_id;
        }
        
        $node = NetworkTreeMap::where('node_id', '=', $parentNode)->first();
        if (empty($node)) {
            $retVal = [];
            if ($config->isCount ()) {
                $retVal ['count'] = 0;
            }
        
            if (! is_null ( $updated )) {
                $retVal ['updated'] = $updated;
            }
            $retVal ['properties'] = [];
        
            return $retVal;    
        }
        $nodeMap = $node->node_map;
        $query = DB::table('css_networking_device_prop_log as ndpl')
            ->join('css_networking_device_prop as ndp', 'ndpl.prop_id', '=', 'ndp.id')
            ->join('css_networking_network_tree as nnt', 'nnt.device_id', '=', 'ndp.device_id')
            ->join('css_networking_network_tree_map as nntm', function ($join) use ($nodeMap) {
                        $join->on('nntm.node_id', '=', 'nnt.id')
                        ->where('nntm.node_map', 'like', "$nodeMap%")->where('nntm.deleted','=',0);
                    });
        
        
        if (! is_null ( $updated )) {
           array_unshift( $fields,DB::raw ( 'unix_timestamp(ndpl.date_created)*1000000000+ndpl.prop_id as sequence'));
        }
        
        // set fields
        $query = self::setFields($query, $fields, $config->isCount());
        // Apply filters if present
        if (! is_null ( $updated )) {
            $query->having('sequence', '>', $updated);
            $query->orderBy ( 'sequence' );
        }
        // Apply filters
        $query = self::setFilters ( $query, $filters );
        // Apply sortby
        $query = self::setSortby ( $query, $config->getSortby() );
        
        // Set pagination parameters
        $query = self::setPagination ( $query, $config->getOffset (), $config->getLimit () );

        // Execute query
        $retVal = self::getResults ( $query, $config->isCount (), 'propertyLogs' );
        if (! is_null ( $updated )) {
            
            if (count ( $retVal['propertyLogs'] ) > 0) {
                $newSequence = $retVal['propertyLogs'] [count ( $retVal['propertyLogs'] ) - 1]->sequence;
            } else {
                $newSequence = $updated;
            }
            $retVal ['updated'] = $newSequence;
            
            // Remove sequence field helping to respond on query with parameter "updated"
            foreach ( $retVal['propertyLogs']  as &$record ) {
                unset ( $record->sequence );
            }
        }
        
        return $retVal;    
    }
    
    /**
     * Return property with specified ID
     */
    public static function getPropertyById($config) {
        // Utilize getProperties call. Incoming filters should already have filter by property id.
        return self::getProperties ($config);
    }        
 }
