<?php 
namespace Modules\Enterprise\Models;

use Auth;
use DB;
use Unified\Http\Helpers\QueryParameters;
use Unified\Models\QueryTrait;
use Unified\Models\NetworkTreeMap;

class VesAlarm
{
    use QueryTrait;

    /**
     * Returns list of alarms matching provided query parameters
     * @param QueryParameters $config
     * @return \Illuminate\Routing\Route
     */
    public static function getAlarms(QueryParameters $config) {
        $filters = $config->getFilters();
        $fields = $config->getFields();
        $control = $config->getControl();

        // check if field sequence is requested
        $sequenceField = QueryParameters::popElementsStartingWith ( $fields, 'sequence' );
        
        // Get more control parameters (page, etc.) if necessary
        $updated = $config->getControlParam ( 'updated');
        $parentNode = $config->getControlParam ( 'root');
    
        if (!isset($parentNode) || ($parentNode == 0)) {
            // Get parent node from user information
            // TODO FIXME remove AUTH from models
            $parentNode = Auth::user()->home_node_id;
        }
    
        $nodes = NetworkTreeMap::where('node_id', '=', $parentNode)->first();
        if (empty($nodes)) {
            return new ServiceResponse ( ServiceResponse::FAIL, ['error' => 'Node ' . $parentNode . ' does not exist.'] );
        }
        $nodeMap = $nodes->node_map;
        $query = DB::table('css_networking_device_alarm as nda')
        ->join('css_networking_network_tree as nnt', 'nnt.device_id', '=', 'nda.device_id')
        ->join('css_networking_device as nd', function ($join) {
            $join->on('nd.id', '=', 'nda.device_id')
            ->whereIn('nd.type_id',[2065,5056,5057,5058,5059,5061,5065]);
        })
        ->join('css_networking_network_tree_map as nntm', function ($join) use ($nodeMap) {
            $join->on('nntm.node_id', '=', 'nnt.id')
            ->where('nntm.node_map', 'like', $nodeMap.'%')->where('nntm.deleted','=',0);
        });
        
        // set fields
        $query = self::setFields($query, $fields, $config->isCount());

        // Apply sequence filters if present
        if (! is_null ( $updated )) {
            // add sequence filter
            $query->where ( 'nda.sequence', '>', $updated );
            // add order by sequence
            $query->orderBy ( 'nda.sequence' );
        }
        
        // Apply filters
        $query = self::setFilters ( $query, $filters );
        
        // Apply sortby
        $query = self::setSortby ( $query, $config->getSortby() );
        
        // Set pagination parameters
        $query = self::setPagination ( $query, $config->getOffset (), $config->getLimit () );

        // Execute query
        $retVal = self::getResults ( $query, $config->isCount (), 'alarms' );

        if (! is_null ( $updated )) {
            if (count ( $retVal['alarms'] ) > 0) {
                $newSequence = $retVal['alarms'] [count ( $retVal['alarms'] ) - 1]->sequence;
            } else {
                $newSequence = $updated;
            }
            $retVal['updated'] = $newSequence;
        }

        return $retVal;
    }
    
}
