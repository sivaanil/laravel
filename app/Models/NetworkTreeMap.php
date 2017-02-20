<?php namespace Unified\Models;

use Auth;
use Eloquent;
use DB;
use Unified\Http\Helpers\QueryParameters;

class NetworkTreeMap extends BaseModel
{
    const HOME_NODE_ACCESS_LIST_CACHE_PREFIX = 'home_node_access_list_';
    const CACHE_TTL = 1; // can reduce to floating point value in Laravel 5.3

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_network_tree_map';

    public $timestamps = false;

    /**
     * getNodesAccessibleByHomeNodeIds
     */
    public function getNodesAccessibleByHomeNodeIds($whitelistNodeIds, $blacklistNodeIds = [])
    {
        // check cache
        $jsonResults = null;
        if ($this->isCacheRedis()) {
            // ensure lists are always in the same order
            asort($whitelistNodeIds);
            asort($blacklistNodeIds);
            $cacheKey = self::HOME_NODE_ACCESS_LIST_CACHE_PREFIX . implode('.', $whitelistNodeIds) . ':bl:' . implode('.', $blacklistNodeIds);
            $cache = $this->getCache();
            $jsonResults = $cache->get($cacheKey);
        }
         
        if ($jsonResults == null) {
            	
            // check database
            $inList = [];
            foreach ($whitelistNodeIds as $nodeId) {
                $inList[] = "%.{$nodeId}.%";
            }
            	
            $queryResults = $this->getDb()
                ->table($this->table)
                ->select('node_id')
                ->where('visible', 1)
                ->where('deleted', 0)
                ->where(function ($query) use ($whitelistNodeIds) {
                    foreach ($whitelistNodeIds as $nodeId) {
                        $query->orWhere('node_map', 'LIKE', "%.{$nodeId}.%");
                    }
                })
                ->where(function ($query) use ($blacklistNodeIds) {
                    foreach ($blacklistNodeIds as $nodeId) {
                        $query->where('node_map', 'NOT LIKE', "%.{$nodeId}.%");
                    }
                })
                ->get();
    
            $results = [];
            foreach ($queryResults as $row) {
                $results[] = $row->node_id;
            }
            	
            $results = array_unique($results);
            asort($results);
            	
            // allow redis cache array
            $jsonResults = json_encode($results);
            	
            if ($this->isCacheRedis()) {
                $cache->put($cacheKey, $jsonResults, self::CACHE_TTL);
            }
        }
    
        return json_decode($jsonResults, true);
    }
    
    /**
     * areNodesAccessibleByHomeNodeIds
     *
     * check to see if given nodes are all accessible by the given home node ids
     */
    public function areNodesAccessibleByHomeNodeIds($nodeIds, $homeNodeIds, $blacklistNodeIds = [])
    {
        $accessibleNodeIds = $this->getNodesAccessibleByHomeNodeIds($homeNodeIds, $blacklistNodeIds);
    
        return (array_diff($nodeIds, $accessibleNodeIds) === []);
    }
    
    
    

    public static function scanDevices($nodeId) {
        $sql = 'SELECT nt.id AS node_id, d.id AS device_id, @@system_time_zone AS timeZone,
                d.last_scan, d.last_alarms_scan, d.last_properties_scan,
                d.failed_scan_count, d.failed_alarms_scan_count, d.failed_properties_scan_count,
                nt.fail_count, nt.fail_alarms_count, nt.fail_properties_count,
                d.last_failed_scan, d.last_failed_alarms_scan, d.last_failed_properties_scan,
                d.failed_properties_scan_count,
                d.scan_interval, d.alarms_scan_interval, d.properties_scan_interval,
                nt.scan_interval as tree_scan_interval, nt.scan_alarms_interval, nt.scan_properties_interval,
                nt.retry_interval, nt.fail_threshold, d.current_status_id
                FROM css_networking_network_tree_map ntm
                INNER JOIN css_networking_network_tree nt ON nt.id = ntm.node_id
                INNER JOIN css_networking_device d ON d.id = nt.device_id
                WHERE ntm.node_map LIKE :nodeIdLike AND ntm.deleted = 0
                AND ntm.build_in_progress = 0';

        $devData = DB::select($sql, [
                    'nodeIdLike' => '%.' . $nodeId . '.%'
        ]);

        return [
            'devices' => $devData
        ];
    }

    public static function getAlarmsFromTimestamp($nodeId, $fromTimestamp, $limit) {
        $sql = 'SELECT nt.id AS node_id, da.*, @@system_time_zone AS timeZone
                FROM css_networking_network_tree_map ntm
                INNER JOIN css_networking_network_tree nt ON nt.id = ntm.node_id
                INNER JOIN css_networking_device d ON d.id = nt.device_id
                INNER JOIN css_networking_device_alarm as da ON d.id = da.device_id
                WHERE ntm.node_map LIKE :nodeIdLike AND ntm.deleted = 0 AND ntm.build_in_progress = 0
                AND da.sequence > :sequenceId
                ORDER BY da.sequence, da.id LIMIT :limit';

        $alarms = DB::select($sql, [
                    'nodeIdLike' => '%.' . $nodeId . '.%',
                    'sequenceId' => $fromTimestamp,
                    'limit' => $limit
        ]);
        return $alarms;

    }

    public static function getNodeNames() {
        $sql = "select nt.id,
            case
                    when d.id is null
                    then g.name
                    else d.name
            END as name
            from css_networking_network_tree_map ntm
            inner join css_networking_network_tree nt on ntm.node_id=nt.id
            left join css_networking_device d on nt.device_id=d.id
            left join css_networking_group g on nt.group_id=g.id
            where ntm.deleted = 0 and ntm.build_in_progress = 0
            group by nt.id;";
        $stmt = DB::raw($sql);
        $dbNodes = DB::select($stmt);
        //dd($dbNodes);

        $nodes = array();
        foreach ($dbNodes as $theNode) {
            //dd($theNode);
            $nodes[$theNode->id] = $theNode->name;
        }

        return $nodes;
    }

    public static function countNodeSubtree($nodeMap) {
        $children = DB::table('css_networking_network_tree_map as ntm')
            ->select(DB::raw('(count(*)) as numChildren'))
            ->where('ntm.node_map', 'LIKE', $nodeMap . '%')
            ->get();

        return $children[0]->numChildren;
    }

    public static function getPortInfo($nodeId, $ipAddress) {
         $ports = DB::table('css_networking_network_tree_map AS tm')
            ->select('d.name as deviceName', DB::raw("ucase(dprtd.name) as portType"), "dprt.port as modemPort",
                "dprtd.default_port as devicePort")//, "dprt.id as portId"
            ->join('css_networking_network_tree AS t ', 'tm.node_id', '=', 't.id')
            ->join('css_networking_device AS d ', 't.device_id', '=', 'd.id')
            ->join('css_networking_device_port AS dprt ', 'd.id', '=', 'dprt.device_id')
            ->join('css_networking_device_port_def AS dprtd ', 'dprt.port_def_id', '=', 'dprtd.id')
            ->where('tm.deleted', '=', "0")
            ->where('tm.node_map', 'like', '%.' . $nodeId . '.%')
            ->where('d.ip_address', '=', "$ipAddress")
            ->orderBy('d.name')
            ->get();

        return $ports;

    }

    public static function getWebUrl($nodeId) {

        $qResult = DB::table('css_networking_network_tree as t')
            ->select('d.ip_address', 'pd.variable_name as protocol', 'p.port')
            ->join('css_networking_device as d', 'd.id', '=', 't.device_id')
            ->join('css_networking_device_port as p', 'p.device_id', '=', 't.device_id')
            ->join('css_networking_device_port_def as pd', 'pd.id', '=', 'p.port_def_id')
            ->where('t.device_id', '=', DB::raw("(select Main_Device_Id(?))"))
            ->setBindings([$nodeId])
            ->get();

        if (empty($qResult)) {
            return null;
        }

        $ipAddress = $qResult[0]->ip_address;

        $httpPort = 80;
        $httpsPort = null;

        foreach($qResult as $info) {
            switch($info->protocol) {
                case 'http':
                    $httpPort = $info->port;
                    break;

                case 'https':
                    $httpsPort = $info->port;
                    break;
            }
        }

        $url = null;

        // prefer https
        // add port only if non-standard
        if ($httpsPort !== null) {
            $url = 'https://' . $ipAddress;

            if ($httpsPort != 443) {
                $url .= ':' . $httpsPort;
            }
        }
        else {
            $url = 'http://' . $ipAddress;

            if ($httpPort != 80) {
                $url .= ':' . $httpPort;
            }
        }

        return $url;

    }
    
    /**
     * Set the deleted flags for target node and all device children.
     * @param unknown $nodeId Traget Node ID
     */
    public static function deleteByNodeId($nodeId) {

        DB::table ( 'css_networking_network_tree_map' )->where ( 'node_map', 'like', '%.' . $nodeId . '.%' )->update ( 
                [ 
                        'deleted' => '1' 
                ] );
    }
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //we want to show the device's password
    //protected $hidden = array('password');

    public function networkTree()
    {
        return $this->belongsTo('NetworkTree', 'node_id');
    }
}
