<?php namespace Unified\Models;

use Auth;
use DB;
use Eloquent;
use Unified\Http\Helpers\QueryParameters;

class DeviceAlarm extends Eloquent
{
    use QueryTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_device_alarm';
    public $timestamps = false;
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //we want to show the device's password
    //protected $hidden = array('password');

    public static function clearAlarms($nodeId) {
        $searchRes = DB::table('css_networking_device_alarm as da')
            ->select('da.*')
            ->join('css_networking_device as d', 'd.id', '=', 'da.device_id')
            ->join('css_networking_network_tree as nt', 'nt.device_id', '=', 'd.id')
            ->join(DB::raw("(SELECT ntm.breadcrumb, ntm.node_id as nid from css_networking_network_tree nt
			Inner join css_networking_network_tree_map ntm on ntm.node_map like Concat(\"%.\",nt.id,\".%\") and ntm.deleted = 0 and ntm.build_in_progress = 0 and ntm.visible=1
			where nt.id = ?) as tmp"), 'tmp.nid', '=', 'nt.id')
            ->whereRaw('da.cleared is null')
            ->setBindings([$nodeId])
            ->get();
        return $searchRes;
    }

    public static function filtered($selectString, $groupByString, $nodeMap ) {

        $alarmQueue = DB::table('css_networking_device_alarm')
            ->selectRaw($selectString)
            ->groupBy(DB::raw($groupByString))
            ->join(DB::raw("(SELECT nt.device_id from css_networking_network_tree nt
         join css_networking_network_tree_map ntm on nt.id = ntm.node_id
        where ntm.node_map LIKE ? and ntm.deleted = 0 and ntm.build_in_progress = 0 and ntm.visible=1 and nt.device_id <> 0) as device"),
                'device.device_id', '=', 'css_networking_device_alarm.device_id')
            ->setBindings(array($nodeMap . '%'));
        //echo $alarmQue->toSql();
        //die;
        return $alarmQueue->get();
    }

    public static function getNode($alarmId) {
         $res = DB::table('css_networking_device_alarm as a')
            //->select(array(DB::raw('main_node_id(nt.id)')))
            ->select(array('nt.id'))
            ->join('css_networking_device as d', 'd.id', "=", "a.device_id")
            ->join('css_networking_network_tree as nt', 'nt.device_id', "=", "d.id")
            ->where("a.id", "=", $alarmId)
            ->take(1)
            ->get();
        return $res;
    }

    /**
     * Returns list of alarms matching provided query parameters
     * @param QueryParameters $config
     * @return \Illuminate\Routing\Route
     */
    public static function getAlarms(QueryParameters $config) {
        $filters = $config->getFilters();
        $fields = $config->getFields();
        $control = $config->getControl();
        
        // Get more control parameters (page, etc.) if necessary
        $updated = $config->getControlParam ( 'updated');
        $parentNode = $config->getControlParam ( 'root');
    
        if (!isset($parentNode) || ($parentNode == 0)) {
            // Get parent node from user information
            // TODO FIXME remove AUTH from models
            $parentNode = Auth::user()->home_node_id;
        }
    
        if (isset($parentNode)) {
            $nodes = NetworkTreeMap::where('node_id', '=', $parentNode)->first();
            if (empty($nodes)) {
                return new ServiceResponse ( ServiceResponse::FAIL, ['error' => 'Node ' . $parentNode . ' does not exist.'] );
            }
            $nodeMap = $nodes->node_map;
            $query = DB::table('css_networking_device_alarm as nda')
            ->join('css_networking_network_tree as nnt', 'nnt.device_id', '=', 'nda.device_id')
            ->join('css_networking_network_tree_map as nntm', function ($join) use ($nodeMap) {
                $join->on('nntm.node_id', '=', 'nnt.id')
                ->where('nntm.node_map', 'like', $nodeMap.'%')->where('nntm.deleted','=',0);
            });
        } else {
            // Simple alarm request
            $query = DB::table ( 'css_networking_device_alarm as nda' );
            $query = $query->leftjoin ( 'css_networking_network_tree as nnt', 'nnt.device_id', '=', 'nda.device_id' );
        }
    
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
    
    /**
     * Modify alarm attributes
     *
     * @param unknown $content
     *            Attributes to be modified
     */
    public function modifyAttributes(&$content) {
        try {
            DB::beginTransaction ();
            
            QueryTrait::setEntityAttributes ( $this, $content );
            $this->save();
            
            $retVal = self::checkFinalContent ( $content );
            if ($retVal ['status']) {
                DB::commit ();
            } else {
                DB::rollback ();
            }
        } catch ( Exception $e ) {
            $retVal = QueryTrait::error ( 'Unable to modify alarm: ' . $e->getMessage );
            DB::rollback ();
        }
        
        return $retVal;
    }
    
}
