<?php namespace Unified\Models;

use Eloquent;
use Unified\Services\FixPropTimes;
use DB;

class NetworkTree extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_network_tree';

    public $timestamps = false;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //we want to show the device's password
    //protected $hidden = array('password');

    public static function getRecordsToFix() {
        $output = DB::table('css_networking_network_tree as t')
                ->join('css_networking_network_tree_map as tm', 'tm.node_id', '=', 't.id')
                ->where('tm.deleted', '0')
                ->join('css_networking_device_prop as p', 'p.device_id', '=', 't.device_id')
                ->where('node_map', 'like', '%5000%')
                ->groupBy('p.date_updated')
                ->having(DB::raw('count(*)'), '>', FixPropTimes::MAX_TS_COUNT)
                ->orderBy('p.date_updated')
                ->select('p.date_updated')
                ->selectRaw('count(*) as prop_count')
                ->get();

        return $output;

    }

    public static function getDevicesUpdatedOn($date) {
        $output = DB::table('css_networking_network_tree as t')
                    ->join('css_networking_network_tree_map as tm', 'tm.node_id', '=', 't.id')
                    ->where('tm.deleted', '0')
                    ->join('css_networking_device_prop as p', 'p.device_id', '=', 't.device_id')
                    ->where('node_map', 'like', '%5000%')
                    ->where('p.date_updated', $date)
                    ->select('t.device_id')
                    ->get();
        return $output;

    }

    public static function getPropCountForDate($date) {
        // Gets the count of props for a specific date
        $dateCount = DB::table('css_networking_network_tree as t')
                ->join('css_networking_network_tree_map as tm', 'tm.node_id', '=', 't.id')
                ->join('css_networking_device_prop as p', 'p.device_id', '=', 't.device_id')
                ->where('node_map', 'like', '%5000%')
                ->where('p.date_updated', $date)
                ->selectRaw('count(*) as prop_count')
                ->get();
        if(is_object($dateCount)) {
            Log::info(__CLASS__." found {$dateCount->prop_count} properties for $date");
            return $dateCount->prop_count;
        }
        return 0;

    }

    public static function getDeviceNode($nodeId) {
        $deviceNode = DB::table('css_networking_network_tree as nt')
            ->select('nt.id', 'nt.parent_node_id', 'nt.parent_device_id', 'nt.parent_group_id', 'd.name as dName',
                'd.type_id', 'd.ip_address', 'dc.description', 'dc.id as class_id', 'dt.main_device', 'dt.has_web_interface',
                'dt.scan_file', 'dt.prop_scan_file', 'dt.rebuilder_file', 'dt.controller_file' )
            ->join('css_networking_device as d', 'nt.device_id', '=', 'd.id')
            ->join('css_networking_device_type as dt', 'd.type_id', '=', 'dt.id')
            ->join('css_networking_device_class as dc', 'dt.class_id', '=', 'dc.id')
            ->where('nt.id', '=', $nodeId)
            ->get();
        return $deviceNode;
    }

    public static function getGroupByNodeId($nodeId) {
        $groupNode = DB::table('css_networking_network_tree as nt')
            ->select('nt.id', 'g.name as gName', 'nt.parent_node_id', 'nt.parent_device_id', 'nt.parent_group_id')
            ->leftJoin('css_networking_group as g', 'nt.group_id', '=', 'g.id')
            ->where('nt.id', '=', $nodeId)
            ->get();
        return $groupNode;
    }

    public static function getByNodeId($nodeId) {
        $output = DB::table('css_networking_network_tree as nt')
            ->select('nt.id', 'nt.parent_node_id', 'nt.parent_device_id', 'nt.parent_group_id', 'd.name as dName',
                'd.type_id', 'd.ip_address', 'dc.description', 'dc.id as class_id')
            ->join('css_networking_device as d', 'nt.device_id', '=', 'd.id')
            ->join('css_networking_device_type as dt', 'd.type_id', '=', 'dt.id')
            ->join('css_networking_device_class as dc', 'dt.class_id', '=', 'dc.id')
            ->where('nt.id', '=', $nodeId)
            ->get();
        return $output;
    }

    public static function getNodeNameAndType($nodeId) {
        $node = DB::table('css_networking_network_tree as nt')
            ->select('nt.id', 'g.name as gName', 'd.name as dName')
            ->leftJoin('css_networking_device as d', 'nt.device_id', '=', 'd.id')
            ->leftJoin('css_networking_group as g', 'nt.group_id', '=', 'g.id')
            ->where('nt.id', '=', $nodeId)
            ->get();
        return $node;
    }

    public static function getAlarmSeverityForNode($nodeId) {

        return DB::table('css_networking_network_tree as nt')
            ->select('nt.id', DB::raw('min(da.severity_id) as sev'))
            ->join('css_networking_network_tree_map as ntm', function ($join) use ($nodeId) {
                $join->on('nt.id', '=', 'node_id')->where('deleted', '=', '0')->where('visible', '=',
                    '1')->where('build_in_progress', '=', '0')->where('ntm.node_id', '=', $nodeId);
            })
            ->join('css_networking_network_tree_map as children', function ($join) {
                $join->on('children.node_map', 'like', DB::Raw("concat(ntm.node_map, '%')"))->where('children.deleted',
                    '=', '0')->where('children.visible', '=', '1')->where('children.build_in_progress', '=', '0');
            })
            ->join('css_networking_network_tree as chnt', 'chnt.id', '=', 'children.node_id')
            ->leftJoin('css_networking_device_alarm as da', function ($join) {
                $join->on('da.device_id', '=', "chnt.device_id")->where('da.cleared_bit', '=', '0')->where('da.ignored',
                    '=', '0')->where('da.severity_id', '<=', '4')->where('da.severity_id', '>=', '1');
            })
            ->groupBy('ntm.node_id')
            ->get();

    }

    // TODO - The two queries here are almost identical, and need to be done better than this.
    // Seriously, this is an emotionally unsettling function. ~A! 3/15/2016
    /**
     * @deprecated
     */
    public static function getNodeChildren($nodeId, $includeSeverity) {
        if ($includeSeverity) {
            return DB::table('css_networking_network_tree as nt')
                ->select('nt.id', 'g.name as gName', 'd.name as dName', DB::raw('min(da.severity_id) as sev'),
                    DB::raw('(count(nt.id) - 1) as numChildren'))
                ->join('css_networking_network_tree_map as ntm', function ($join) {
                    $join->on('nt.id', '=', 'node_id')->where('deleted', '=', '0')->where('visible', '=',
                        '1')->where('build_in_progress', '=', '0');
                })
                ->join('css_networking_network_tree_map as children', function ($join) {
                    $join->on('children.node_map', 'like',
                        DB::Raw("concat(ntm.node_map, '%')"))->where('children.deleted', '=',
                        '0')->where('children.visible', '=', '1')->where('children.build_in_progress', '=', '0');
                })
                ->join('css_networking_network_tree as chnt', 'chnt.id', '=', 'children.node_id')
                ->leftJoin('css_networking_device as d', 'nt.device_id', '=', 'd.id')
                ->leftJoin('css_networking_group as g', 'nt.group_id', '=', 'g.id')
                ->leftJoin('css_networking_device_alarm as da', function ($join) {
                    $join->on('da.device_id', '=', "chnt.device_id")->where('da.cleared_bit', '=',
                        '0')->where('da.ignored', '=', '0')->where('da.severity_id', '<=', '4')->where('da.severity_id',
                        '>=', '1');
                })
                ->where('nt.parent_node_id', '=', $nodeId)
                ->groupBy('ntm.node_id')
                ->orderBy(DB::raw('isnull(gName)'), 'asc')//isnull puts groups before devices
                ->orderBy('gName', 'asc')
                ->orderBy('dName', 'asc')
                ->get();
        } else {
            return DB::table('css_networking_network_tree as nt')
                ->select('nt.id', 'g.name as gName', 'd.name as dName', DB::raw('-2 as sev'),
                    DB::raw('(count(nt.id) - 1) as numChildren'))
                ->join('css_networking_network_tree_map as ntm', function ($join) {
                    $join->on('nt.id', '=', 'node_id')->where('deleted', '=', '0')->where('visible', '=',
                        '1')->where('build_in_progress', '=', '0');
                })
                ->join('css_networking_network_tree_map as children', function ($join) {
                    $join->on('children.node_map', 'like',
                        DB::Raw("concat(ntm.node_map, '%')"))->where('children.deleted', '=',
                        '0')->where('children.visible', '=', '1')->where('children.build_in_progress', '=', '0');
                })
                ->join('css_networking_network_tree as chnt', 'chnt.id', '=', 'children.node_id')
                ->leftJoin('css_networking_device as d', 'nt.device_id', '=', 'd.id')
                ->leftJoin('css_networking_group as g', 'nt.group_id', '=', 'g.id')
                ->where('nt.parent_node_id', '=', $nodeId)
                ->groupBy('ntm.node_id')
                ->orderBy(DB::raw('isnull(gName)'), 'asc')//isnull puts groups before devices
                ->orderBy('gName', 'asc')
                ->orderBy('dName', 'asc')
                ->get();
        }


    }

    public static function breadcrumbMap($nodeIds) {
        $nodes = DB::table('css_networking_network_tree_map as tm')
            ->select(DB::Raw('coalesce(d.name,g.name) as name'), 'tm.node_id')
            ->join('css_networking_network_tree as nt', 'nt.id', '=', 'tm.node_id')
            ->leftJoin('css_networking_device as d', 'd.id', '=', 'nt.device_id')
            ->leftJoin("css_networking_group as g", 'g.id', '=', 'nt.group_id')
            ->whereIn('node_id', $nodeIds)
            ->orderByRaw('length(tm.node_map)')
            ->get();
        return $nodes;
    }

    public static function getLevelByNodeId($nodeId) {
        $node = DB::table('css_networking_network_tree as nt')
            ->select('nt.id', 'g.name as gName', 'd.name as dName', 's.description as status_description', DB::raw('min(da.severity_id) as sev'))
            ->leftJoin('css_networking_device as d', 'nt.device_id', '=', 'd.id')
            ->leftJoin('css_networking_group as g', 'nt.group_id', '=', 'g.id')
            ->leftJoin('css_networking_device_status as s', 'd.current_status_id', '=', 's.id')
            ->leftJoin('css_networking_device_alarm as da', function ($join) {
                $join->on('da.device_id', '=', "nt.device_id")->where('da.cleared_bit', '=',
                    '0')->where('da.ignored', '=', '0')->where('da.severity_id', '<=', '4')->where('da.severity_id',
                    '>=', '1');
            })
            ->where('nt.id', '=', $nodeId)
            ->get();
        return $node;
    }

    public static function getLevelChildrenByNodeId($nodeId) {
        $children = DB::table('css_networking_network_tree as nt')
            ->select('nt.id', 'g.name as gName', 'd.name as dName', 's.description as status_description', DB::raw('min(da.severity_id) as sev'),
                DB::raw('(count(distinct children.id) - 1) as numChildren'), 'd.type_id as device_type')
            ->join('css_networking_network_tree_map as ntm', function ($join) {
                $join->on('nt.id', '=', 'node_id')->where('deleted', '=', '0')->where('visible', '=',
                    '1')->where('build_in_progress', '=', '0');
            })
            ->join('css_networking_network_tree_map as children', function ($join) {
                $join->on('children.node_map', 'like',
                    DB::Raw("concat(ntm.node_map, '%')"))->where('children.deleted', '=',
                    '0')->where('children.visible', '=', '1')->where('children.build_in_progress', '=', '0');
            })
            ->join('css_networking_network_tree as chnt', 'chnt.id', '=', 'children.node_id')
            ->leftJoin('css_networking_device as d', 'nt.device_id', '=', 'd.id')
            ->leftJoin('css_networking_group as g', 'nt.group_id', '=', 'g.id')
            ->leftJoin('css_networking_device_status as s', 'd.current_status_id', '=', 's.id')
            ->leftJoin('css_networking_device_alarm as da', function ($join) {
                $join->on('da.device_id', '=', "chnt.device_id")->where('da.cleared_bit', '=',
                    '0')->where('da.ignored', '=', '0')->where('da.severity_id', '<=', '4')->where('da.severity_id',
                    '>=', '1');
            })
            ->where('nt.parent_node_id', '=', $nodeId)
            ->groupBy('ntm.node_id')
            ->get();
        return $children;

    }

    public static function getGroupList() {
        $groupList = DB::select(DB::raw("SELECT
            t.id as id,
            t.parent_node_id,
            t.parent_device_id,
            t.parent_group_id,
            g.name as gName
            FROM css_networking_network_tree t
            INNER JOIN css_networking_network_tree_map tm ON(
                                                                tm.node_id = t.id
                                                                AND tm.node_map like '%.321.%'
                                                                AND tm.deleted = 0
                                                                AND tm.build_in_progress = 0
                                                                AND tm.visible = 1
                                                            )
            INNER JOIN css_networking_group g ON(
                                                    t.group_id = g.id
                                                    AND t.group_id != 0
                                                    )


        "));

        return $groupList;
    }

    public static function getDeviceList() {
        $deviceList = DB::select(DB::raw("SELECT
            t.id as id,
            t.parent_node_id,
            t.parent_device_id,
            t.parent_group_id,
            d.name as dName,
            d.type_id,
            d.ip_address,
            dc.description,
            dc.id as class_id,
            dt.main_device
            FROM css_networking_network_tree t
            INNER JOIN css_networking_network_tree_map tm ON(
                                                                tm.node_id = t.id
                                                                AND tm.node_map like '%.321.%'
                                                                AND tm.deleted = 0
                                                                AND tm.build_in_progress = 0
                                                                AND tm.visible = 1
                                                            )
            INNER JOIN css_networking_device d ON(
                                                    t.device_id = d.id
                                                    AND t.device_id != 0
                                                    )
            INNER JOIN css_networking_device_type dt ON(
                                                            d.type_id = dt.id
                                                        )
            INNER JOIN css_networking_device_class dc ON(
                                                            dt.class_id = dc.id
                                                        )

        "));
        return $deviceList;

    }

    public static function getDeletedNodes($lastSyncTime) {
        $deletedNodes = DB::table('css_networking_network_tree as nt')
            ->select('nt.id', 'g.name as gName', 'd.name as dName', DB::raw('min(da.severity_id) as sev'))
            ->join('css_networking_network_tree_map as ntm', function ($join) {
                $join->on('nt.id', '=', 'node_id')->where('deleted', '=', '0')->where('visible', '=',
                    '1')->where('build_in_progress', '=', '0');
            })
            ->join('css_networking_network_tree_map as children', function ($join) {
                $join->on('children.node_map', 'like',
                    DB::Raw("concat(ntm.node_map, '%')"))->where('children.deleted', '=',
                    '0')->where('children.visible', '=', '1')->where('children.build_in_progress', '=', '0');
            })
            ->join('css_networking_network_tree as chnt', 'chnt.id', '=', 'children.node_id')
            ->leftJoin('css_networking_device as d', 'nt.device_id', '=', 'd.id')
            ->leftJoin('css_networking_group as g', 'nt.group_id', '=', 'g.id')
            ->leftJoin('css_networking_device_alarm as da', function ($join) {
                $join->on('da.device_id', '=', "chnt.device_id")->where('da.cleared_bit', '=',
                    '0')->where('da.ignored', '=', '0')->where('da.severity_id', '<=', '4')->where('da.severity_id',
                    '>=', '1');
            })
            ->where('nt.date_updated', '>=', $lastSyncTime)
            ->where('ntm.deleted', '=', '1')
            ->groupBy('ntm.node_id')
            ->orderBy(DB::raw('isnull(gName)'), 'asc')//isnull puts groups before devices
            ->orderBy('gName', 'asc')
            ->orderBy('dName', 'asc')
            ->get();
        return $deletedNodes;

    }

    public static function fromNodeList($nodeIdList) {
        $res = DB::table('css_networking_network_tree')
            ->select(array(
                'css_networking_network_tree.id as node_id',
                'css_networking_device_type.has_web_interface as wi',
                'css_networking_device_type.prop_scan_file as psf'
            ))
            ->join('css_networking_device', 'css_networking_device.id', "=",
                DB::raw("main_device_id(css_networking_network_tree.id)"))
            ->join('css_networking_device_type', 'css_networking_device_type.id', "=",
                "css_networking_device.type_id")
            ->whereIn("css_networking_network_tree.id", $nodeIdList)
            ->get();
        return $res;
    }

    public static function search($getMains, $str, $nodeId, $pageLimit, $page, $selectedNodeSearch) {
        $_searchRes = DB::table('css_networking_network_tree as nt')
            ->select(DB::raw('SQL_CALC_FOUND_ROWS COALESCE(d.name,g.name)as name, IFNULL(IFNULL(d.ip_address, d.ip_address_2),Node_Main_Name(nt.id)) as display'),
                'nt.id as node_id', 'd.id as devId')
            ->join('css_networking_network_tree_map as ntm', function ($join) {
                $join->on('nt.id', '=', 'node_id')->where('deleted', '=', '0')->where('visible', '=', '1')
                    ->where('build_in_progress', '=', '0');
            })
            ->leftJoin('css_networking_group as g', 'nt.group_id', '=', 'g.id')
            ->leftJoin('css_networking_device as d', 'nt.device_id', '=', 'd.id')
            ->leftJoin('css_networking_device_type as dt', 'dt.id', '=', 'd.type_id')
            ->whereRaw('(dt.main_device in ' . "($getMains)" . ' or dt.id is null)');//Need to use whereRaw so that the query sections can be surrounded in ()

            $_searchRes->whereRaw("(d.name LIKE concat('%',?,'%') or g.name LIKE concat('%',?,'%') or d.ip_address LIKE concat('%',?,'%') or d.ip_address_2 LIKE concat('%',?,'%'))",
                array($str, $str, $str, $str));
                    
                    
            if($selectedNodeSearch == "true") {
                //echo "asdf";
            } else {
                
            }       
                    
                    
        $searchRes = $_searchRes->whereRaw("ntm.node_map like concat('%.',?,'.%')", array($nodeId))
                            ->take($pageLimit)->skip($page * $pageLimit)->get();
        $count = DB::select(DB::raw('select FOUND_ROWS() as count;'));

        $output = new \StdClass();
        $output->result = $searchRes;
        $output->num = $count;
        return $output;

    }


    public static function getScanStatus($nodeId) {
        $treeResult = DB::table('css_networking_network_tree as nt')
            ->select('nt.device_id', 'd.scanning')
            ->join('css_networking_device as d', 'nt.device_id', '=', 'd.id')
            ->where('nt.id', $nodeId)
            ->first();
        return $treeResult;
    }

    public static function getInheritedScanInterval($nodeId) {
        $scanIntervals = DB::table('css_networking_network_tree as nt')
            ->select('ntm.breadcrumb AS NodeName',
                    'nt.scan_interval',
                    'nt.retry_interval',
                    'nt.scan_alarms_interval',
                    'nt.scan_properties_interval',
                    'nt.fail_threshold',
                    'nt.heartbeat_threshold',
                    'nt.fail_count',
                    'nt.fail_alarms_count',
                    'nt.fail_properties_count')
            ->join('css_networking_network_tree_map as ntm', 'ntm.node_id', '=', 'nt.id')
            ->leftJoin('css_networking_device as d', 'd.id', '=', 'nt.device_id')
            ->leftJoin('css_networking_group as g', 'g.id', '=', 'nt.group_id')
            ->whereRaw("(nt.scan_interval is not null OR
                    nt.retry_interval is not null OR
                    nt.scan_alarms_interval is not null OR
                    nt.scan_properties_interval is not null OR
                    nt.fail_threshold is not null OR
                    ntm.node_id = ? ) AND
                    (
                    SELECT
                            tm.node_map
                    FROM
                            css_networking_network_tree_map tm
                    WHERE
                            tm.node_id = ?
                    )LIKE concat('%.', ntm.node_id, '.%')", array($nodeId, $nodeId))
            ->orderByRaw("length(ntm.node_map) desc");

        $results = $scanIntervals->get();
        return $results;

    }

    public static function getNodeInfo($nodeId) {
        $node = DB::table('css_networking_network_tree')
            ->select('css_networking_network_tree.id', 'ntm.breadcrumb')
            ->join('css_networking_network_tree_map as ntm', 'css_networking_network_tree.id', '=', 'node_id')
            ->where('device_id', '=', $nodeId)
            ->get();

        return $node;
    }

    public static function getScanData($nodeId) {
        $qResult = DB::table('css_networking_network_tree as nt')
                ->select('dt.id as device_type_id', 'dt.vendor', 'dt.model', 'dt.scan_file', 'dt.prop_scan_file')
                ->join('css_networking_device as d', 'nt.device_id', '=', 'd.id')
                ->join('css_networking_device_type as dt', 'd.type_id', '=', 'dt.id')
                ->where('nt.id', $nodeId)
                ->first();

        return $qResult;
    }

    //returns list of NetworkTree items which are child nodes of the current instance.
    //This function's implementation is not fast, so use it sparingly!
    public function getChildren() {
        $children = NetworkTree::where('parent_node_id', $this->id)->get();
        $descendants = [];
        foreach($children as $child) {
            $descendants[] = $child;
            $gChildren = $child->getChildren();
            foreach($gChildren as $grandChild) {
                $descendants[] = $grandChild;
            }
        }
        return $descendants;
    }

    public function networkTreeMap()
    {
        return $this->hasOne('NetworkTreeMap', 'node_id');
    }

    public function device()
    {
        //return $this->hasOne('Unified\Models\Device', 'device_id');
        return $this->belongsTo('Unified\Models\Device');
    }
}
