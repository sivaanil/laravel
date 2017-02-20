<?php namespace Unified\Http\Controllers;

use Auth;
use DB;
use Unified\Http\Helpers\GeneralHelper;
use Unified\Http\Helpers\nodes\NodeHelper;
use Unified\Models\NetworkTree;

    /**
     * Description of Tree
     *
     * @author akhand.singh
     */
    class Tree
    {

        private $root = null;
        private $nodeList = array();
        public $homeNodeId = '321';
        private $selectedNode = null;

        function __construct() {
            $this->homeNodeId = Auth::user()->home_node_id;
        }

        public static function load()
        {
            return 'Tree';
        }

        /**
         * invoke the tree
         *
         * @param void
         *
         * @return view tree
         */
        public function showTree()
        {
            return View::make("tree::tree");
        }

        /**
         * return the private property of the class
         *
         * @param mixed $property
         *
         * @return mixed $property
         */
        public function __get($property)
        {
            if (property_exists($this, $property)) {
                return $this->$property;
            }
        }

        /**
         * return the instance
         *
         * @param mixed $property
         * @param mixed $value
         *
         * @return $this
         */
        public function __set($property, $value)
        {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }

            return $this;
        }

        /**
         * return the selectedNode
         *
         * @return $this->selectedNode
         */
        public function getSelectedNode()
        {
            return $this->selectedNode;
        }

        /**
         * set the selectedNode
         *
         * @param int $nodeId
         *
         * @return void
         */
        public function setSelectedNode($nodeId)
        {
            $this->selectedNode = $this->getNodeInfoByNodeId($nodeId);
        }

        /**
         * return the standard information of a node
         *
         * @param int $nodeId
         *
         * @return array $response
         */
        public function getNodeInfoByNodeId($nodeId)
        {
            if (! empty($this->nodeList[$nodeId])) {
                return $this->nodeList[$nodeId];
            } else {
                $nodeData = $this->getAsyncNodeInfo($nodeId);
                $this->setNodeInfoByNodeId($nodeId, $nodeData);

                return $this->getNodeInfoByNodeId($nodeId);
            }

            return null;
        }

        /**
         * update the standard information of a node
         *
         * @param int $nodeId
         * @param int $nodeData
         *
         * @return void
         */
        public function setNodeInfoByNodeId($nodeId, $nodeData)
        {
            $this->nodeList[$nodeId] = $nodeData;
        }

        /**
         * get Node Information from DB
         *
         * @param int $nodeId
         *
         * @return array $data
         */
        public function getAsyncNodeInfo($nodeId)
        {

            $data = array();

            $deviceNode = NetworkTree::getDeviceNode($nodeId);
            $i = 0;
            if (! empty($deviceNode) && count($deviceNode) > 0) {
                if ($deviceNode[$i]->dName != null || $deviceNode[$i]->dName != '') {
                    $data['nodeId'] = $deviceNode[$i]->id;
                    $data['label'] = $deviceNode[$i]->dName;
                    $data['type'] = 'device';
                    $data['typeId'] = $deviceNode[$i]->type_id;
                    $data['ipAddress'] = $deviceNode[$i]->ip_address;
                    $data['deviceClass'] = $deviceNode[$i]->description;
                    if (strlen($deviceNode[$i]->prop_scan_file) > 0) {
                        $data['hasPropScan'] = true;
                    }
                    $data['classId'] = $deviceNode[$i]->class_id;
                    $data['parentNodeId'] = $deviceNode[$i]->parent_node_id;
                    $data['parentDeviceId'] = $deviceNode[$i]->parent_device_id;
                    $data['parentGroupId'] = $deviceNode[$i]->parent_group_id;
                    $data['mainDevice'] = $deviceNode[$i]->main_device;
                    $data['hasWeb'] = $deviceNode[$i]->has_web_interface;
                    $data['hasRebuilder'] = ($deviceNode[$i]->rebuilder_file) ? true : false;
                    $data['inheritsIpAddress'] = true;
                    if ($deviceNode[$i]->main_device === '1') {
                        $data['inheritsIpAddress'] = false;
                    }
                }
            }

            $groupNode = NetworkTree::getGroupByNodeId($nodeId);

            $i = 0;
            if (! empty($groupNode) && count($groupNode) > 0) {
                if ($groupNode[$i]->gName != null || $groupNode[$i]->gName != '') {
                    $data['nodeId'] = $groupNode[$i]->id;
                    $data['label'] = $groupNode[$i]->gName;
                    $data['type'] = 'group';
                    $data['typeId'] = null;
                    $data['ipAddress'] = null;
                    $data['deviceClass'] = null;
                    $data['classId'] = null;
                    $data['parentNodeId'] = $groupNode[$i]->parent_node_id;
                    $data['parentDeviceId'] = $groupNode[$i]->parent_device_id;
                    $data['parentGroupId'] = $groupNode[$i]->parent_group_id;
                    $data['mainDevice'] = null;
                    $data['inheritsIpAddress'] = false;
                }
            }

            $parentDeviceNode = NetworkTree::getByNodeId($data['parentNodeId']);

            $i = 0;
            if (! empty($parentDeviceNode) && count($parentDeviceNode) > 0) {
                if ($parentDeviceNode[$i]->dName != null || $parentDeviceNode[$i]->dName != '') {
                    $data['parentType'] = 'device';
                    $data['parentTypeId'] = $parentDeviceNode[$i]->type_id;
                    $data['parentClassId'] = $parentDeviceNode[$i]->class_id;
                }
            } else {
                $data['parentType'] = 'group';
                $data['parentTypeId'] = null;
                $data['parentClassId'] = null;
            }

            return $data;
        }

        /**
         * get Level Node from DB
         *
         * @param int $nodeId
         *
         * @return array $data
         */
        public function getLevelByNodeId($nodeId)
        {
            $res = array();
            $node = NetworkTree::getLevelByNodeId($nodeId);

            $i = 0;
            $res['nodeId'] = null;
            $res['sevNum'] = $node[0]->sev == null ? 100 :$node[0]->sev; // -1 is clered
            $res['sev'] = GeneralHelper::getNameFromSeverity($res['sevNum']);

            $res['status_description'] = $node[$i]->status_description;
            $res['label'] = "Invalid Node Id";
            $res['type'] = null;

            if (count($node) > 0) {
                if ($node[$i]->dName == null || $node[$i]->dName == '') {
                    $res['nodeId'] = $node[$i]->id;
                    $res['label'] = $node[$i]->gName;
                    $res['type'] = 'group';
                } else {
                    $res['nodeId'] = $node[$i]->id;
                    $res['label'] = $node[$i]->dName;
                    $res['type'] = 'device';
                }
            }

            return $res;
        }

        /**
         * get Level Children Node from DB
         *
         * @param int $nodeId
         *
         * @return array $data
         */
        public function getLevelChildrenByNodeId($nodeId)
        {
            $children = NetworkTree::getLevelChildrenByNodeId($nodeId);
            $child = array();
            //echo $children->toSql();
            //die;
            // natural sort
            usort($children, function ($a, $b) {
                $aLabel = $a->gName ? $a->gName : $a->dName;
                $bLabel = $b->gName ? $b->gName : $b->dName;
                return strnatcasecmp($aLabel, $bLabel);
            });

            $count = count($children);
            for ($i = 0; $i < $count; $i ++) {
                $child[$i]['sevNum'] = $children[$i]->sev == null ? 100 : $children[$i]->sev; // -1 is clered
                $child[$i]['sev'] = GeneralHelper::getNameFromSeverity($child[$i]['sevNum']);
                $child[$i]['status_description'] = $children[$i]->status_description;
                $child[$i]['nodeId'] = $children[$i]->id;
                $child[$i]['numChildren'] = $children[$i]->numChildren;
                $child[$i]['label'] = $children[$i]->dName == null ? $children[$i]->gName : $children[$i]->dName;
                $child[$i]['type'] = $children[$i]->dName == null ? 'group' : 'device';
                $child[$i]['device_type'] = $children[$i]->device_type;
            }

            return $child;
        }

        /**
         * get All Node Levels from DB
         *
         * NOTE: This function always throws an index not found error on line 302. Unused in the code
         *   unless the route is hit in a controller, which it never is.
         * @param int $nodeId
         *
         * @return json $data
         */
        public function loadAllLevelsByNodeId($nodeId)
        {

            $nodeArray = array();

            $groupList = NetworkTree::getGroupList();
            $groupInfo = array();
            $count = count($groupList);

            for ($i = 0; $i < $count; $i ++) {
                $groupInfo['value'] = $groupList[$i]->id;
                $groupInfo['sev'] = 'critical';
                $groupInfo['label'] = $groupList[$i]->gName;
                $groupInfo['items'] = array();
                $groupInfo['dataset'] = array();

                $groupInfo['icon'] = $this->getNodeIcon($groupInfo['dataset']['type'], $groupInfo['sev']);

                array_push($nodeArray, (object) $groupInfo);
            }

            $deviceList = NetworkTree::getDeviceList();

            $deviceInfo = array();
            $count = count($deviceList);
            for ($i = 0; $i < $count; $i ++) {

                $deviceInfo['value'] = $deviceList[$i]->id;
                $deviceInfo['sev'] = 'critical';
                $deviceInfo['label'] = $deviceList[$i]->dName;
                $deviceInfo['items'] = array();
                $deviceInfo['dataset'] = array();

                array_push($nodeArray, (object) $deviceInfo);
            }

            $root = $this->build_tree($nodeArray);

            return json_encode(array('nodes'        => array(0 => $root),
                                     'lastsynctime' => $this->lastSyncTime(),
                                     'length'       => count($nodeArray)
            ));

        }

        /**
         * UN-783 Filter list of nodes by device type
         * Filters the nodes passed so that the list includes only the ones
         * that are of the device type passed in
         */
         public function filterByDeviceType($deviceTypeFilter, $nodes) {

            $output = [];

            foreach ($nodes as $node) {
                if ($node['type'] != 'device') {
                    $output[] = $node;
                    continue;
                }

                if ($node['device_type'] == $deviceTypeFilter) {
                    $output[] = $node;
                }
            }
            return $output;
         }

        /**
         * get Last Sync Time from DB
         *
         * @return dateTime $data
         */
        public function lastSyncTime()
        {
            $lastsyncTimeRecord = DB::select(DB::raw("SELECT NOW() as lastResponseTime;"));
            $count = count($lastsyncTimeRecord);
            for ($i = 0; $i < $count; $i ++) {
                $lastsyncTime = $lastsyncTimeRecord[$i]->lastResponseTime;
            }

            return $lastsyncTime;
        }

        /**
         * get All Node Levels Since Time from DB
         *
         * @param int $lastsynctime
         *
         * @return json $data
         */
/*        public function getAllLevelNodesSinceTime($lastsynctime)
        {
            //dd($lastsynctime);
            $lastsynctime = 0;
            $updateNodes = DB::table('css_networking_network_tree as nt')
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
                ->where('nt.date_updated', '>=', $lastsynctime)
                ->where('ntm.deleted', '=', '0')
                ->groupBy('ntm.node_id')
                ->orderBy(DB::raw('isnull(gName)'), 'asc')//isnull puts groups before devices
                ->orderBy('gName', 'asc')
                ->orderBy('dName', 'asc')
                ->get();

            $updated = array();

            $count = count($updateNodes);

            for ($i = 0; $i < $count; $i ++) {
                $updated[$i]['nodeId'] = $updateNodes[$i]->id;
                $updated[$i]['sevNum'] = $updateNodes[$i]->sev == null ? 100 : $updateNodes[$i]->sev; // -1 is clered

                $updated[$i]['sev'] = GeneralHelper::getNameFromSeverity($updated[$i]['sevNum']);
                if ($updateNodes[$i]->dName == null || $updateNodes[$i]->dName == '') {
                    $updated[$i]['name'] = $updateNodes[$i]->gName;
                    $updated[$i]['type'] = 'group';
                } else {
                    $updated[$i]['name'] = $updateNodes[$i]->dName;
                    $updated[$i]['type'] = 'device';
                }
            }

            $deletedNodes = NetworkTree::getDeletedNodes($lastsynctime);
            $deleted = array();

            $count = count($deletedNodes);

            for ($i = 0; $i < $count; $i ++) {
                $deleted[$i]['nodeId'] = $updateNodes[$i]->id;
                $deleted[$i]['sevNum'] = $updateNodes[$i]->sev == null ? 100 : $updateNodes[$i]->sev; // -1 is clered

                $deleted[$i]['sev'] = GeneralHelper::getNameFromSeverity($deleted[$i]['sevNum']);
                if ($updateNodes[$i]->dName == null || $updateNodes[$i]->dName == '') {
                    $deleted[$i]['name'] = $updateNodes[$i]->gName;
                    $deleted[$i]['type'] = 'group';
                } else {
                    $deleted[$i]['name'] = $updateNodes[$i]->dName;
                    $deleted[$i]['type'] = 'device';
                }
            }

            $data = array();
            $data["updated"] = $updated;
            $data["deleted"] = $deleted;

            return $data;
        }
*/
        /**
         * Create a standard class Object hierarchy.
         *
         * @param $list (array)
         *
         * @return json response
         */
        public function build_tree($list)
        {
            $arr = array();
            $count = count($list);
            for ($i = 0; $i < $count; $i ++) {
                $arr[$list[$i]->dataset['nodeId']] = $list[$i];
            }
            for ($indi = 0; $indi < $count; $indi ++) {
                if ($list[$indi]->dataset['nodeId'] == $this->homeNodeId) {
                    $this->root = $list[$indi];
                    $this->root->items = array();

                } else {
                    if (! empty($arr[$list[$indi]->dataset['parentNodeId']])) {
                        $parent = $arr[$list[$indi]->dataset['parentNodeId']];
                    } else {
                        $parent = null;
                    }
                    if ($parent != null) {
                        if (! isset($parent->items)) {
                            $parent->items = array();
                        }
                        array_push($parent->items, $list[$indi]);
                    }
                }
            }

            return $this->root;
        }

        /**
         * format nodes in tree specific format.
         *
         * @param mixed   $parent
         * @param mixed   $children
         * @param boolean $flag
         *
         * @return json response
         */
        public function formatNodes($parent, $children, $flag = 1)
        {
            $formattedList = array();
            $offSet = 0;

            if ($parent != null) {
                $min = 100;
                // check parent severity
                if ($parent['sevNum'] < $min) {
                    $min = $parent['sevNum'];
                }
                // check children severity
                foreach ($children as $child) {
                    if (is_numeric($min) && $child['sevNum'] < $min) {
                        $min = $child['sevNum'];
                    }
                }
                if (count($parent) > 0 && $parent != "") {
                    if ($parent['label'] != "Invalid Node Id") {
                        $curSev = GeneralHelper::getNameFromSeverity($min);

                        $formattedList[$offSet]['value'] = $parent['nodeId'];
                        $formattedList[$offSet]['sev'] = $curSev['severity'];
                        $formattedList[$offSet]['label'] = '<span class=\'tree-status-' . $parent['status_description'] . '\'>' . $parent['label'] . '</span>';
                        $formattedList[$offSet]['items'] = array();
                        $formattedList[$offSet]['dataset'] = array();

                        $formattedList[$offSet]['status_description'] = $parent['status_description'];
                        $formattedList[$offSet]['icon'] = $this->getNodeIcon($parent['type'], $curSev['severity']);
                        $formattedList[$offSet]['iconsize'] = '20px';
                        $formattedList[$offSet]['menuIcon'] = url() . "/img/icons/text_list_bullets.png";
                        $formattedList[$offSet]['menuIconSize'] = '15px';

                        $itemList = array();

                        $count = count($children);

                        if ($count > 0) {

                            if ($flag == 1) {

                                for ($i = 0; $i < $count; $i ++) {
                                    $itemList['value'] = $children[$i]['nodeId'];
                                    $itemList['sev'] = $children[$i]['sev']['severity'];
                                    if ($children[$i]['numChildren']) {
                                        $itemList['items'] = array(0 => array('label' => "Loading..."));
                                    } else {
                                        $itemList['items'] = array();
                                    }
                                    $itemList['label'] = '<span class=\'tree-status-' . $children[$i]['status_description'] . '\'>' . $children[$i]['label'] . '</span>';
                                    $itemList['dataset'] = array();

                                    $itemList['status_description'] = $children[$i]['status_description'];
                                    $itemList['icon'] = $this->getNodeIcon($children[$i]['type'], $itemList['sev']);
                                    $itemList['iconsize'] = '20px';
                                    $itemList['menuIcon'] = url() . "/img/icons/text_list_bullets.png";
                                    $itemList['menuIconSize'] = '15px';

                                    array_push($formattedList[$offSet]["items"], $itemList);
                                }
                            } else {
                                for ($i = 0; $i < $count; $i ++) {
                                    $offSet1 = $i;
                                    $itemList[$offSet1]['value'] = $children[$i]['nodeId'];
                                    $itemList[$offSet1]['sev'] = $children[$i]['sev']['severity'];
                                    if ($children[$i]['numChildren']) {
                                        $itemList[$offSet1]['items'] = array(0 => array('label' => "Loading..."));
                                    }
                                    $itemList[$offSet1]['label'] = '<span class=\'tree-status-' . $children[$i]['status_description'] . '\'>' . $children[$i]['label'] . '</span>';
                                    $itemList[$offSet1]['dataset'] = array();

                                    $itemList[$offSet1]['status_description'] = $children[$i]['status_description'];
                                    $itemList[$offSet1]['icon'] = $this->getNodeIcon($children[$i]['type'],
                                        $itemList[$offSet1]['sev']);
                                    $itemList[$offSet1]['iconsize'] = '20px';
                                    $itemList[$offSet1]['menuIcon'] = url() . "/img/icons/text_list_bullets.png";
                                    $itemList[$offSet1]['menuIconSize'] = '15px';
                                }

                                return $itemList;
                            }
                        }
                    }
                }
            } else {
                for ($i = 0; $i < $count; $i ++) {
                    $offSet = $i;
                    $formattedList[$offSet]['value'] = $children[$i]['nodeId'];
                    $formattedList[$offSet]['sev'] = $children[$i]['sev']['severity'];
                    if ($children[$i]['numChildren']) {
                        $formattedList[$offSet]['items'] = array(0 => array('label' => "Loading..."));
                    }
                    $formattedList[$offSet]['label'] = $children[$i]['label'];
                    $formattedList[$offSet]['dataset'] = array();

                    $formattedList[$offSet1]['status_description'] = $children[$i]['status_description'];
                    $formattedList[$offSet]['icon'] = $this->getNodeIcon($children[$i]['type'],
                        $formattedList[$offSet]['sev']);
                    $formattedList[$offSet]['iconsize'] = '20px';
                    $formattedList[$offSet]['menuIcon'] = "./img/icons/text_list_bullets.png";
                    $formattedList[$offSet]['menuIconSize'] = '15px';
                }
            }

            return $formattedList;
        }

        /**
         * return Node Label
         *
         * @param int    $type
         * @param string $sev
         * @param string $label
         *
         * @return string response
         */
        public function getNodeLabel($type, $sev, $label)
        {
            if ($type == 0) {
                if ($sev != '') {
                    return "<span class='nodelabel'><img src='./img/icons/alarm_device.png'/>" . $label . "</span/>";
                } else {
                    return "<span class='nodelabel'><img src='./img/icons/generic_device.png'/>" . $label . "</span>";
                }
            } else {

                switch ($sev) {
                    case 'critical':
                        return "<span class='nodelabel'><img src='./img/icons/critical_folder.png'/>" . $label . "</span>";

                    case 'major':
                        return "<span class='nodelabel'><img src='./img/icons/major_folder.png'/>" . $label . "</span>";

                    case 'minor':
                        return "<span class='nodelabel'><img src='./img/icons/minor_folder.png'/>" . $label . "</span>";

                    case 'warning':
                        return "<span class='nodelabel'><img src='./img/icons/warning_folder.png'/>" . $label . "</span>";

                    default:
                        return "<span class='nodelabel'><img src='./img/icons/clear_folder.png'/>" . $label . "</span>";

                }

            }
        }

        /**
         * return Node icon
         *
         * @param int    $type
         * @param string $sev
         *
         * @return string response
         */
        public function getNodeIcon($type, $sev)
        {
            $icon_path = url() . '/img/icons/';
            if ($type == 'device') {
                switch ($sev) {
                    case 'critical':
                        return $icon_path . "critical_leaf_device.png";

                    case 'major':
                        return $icon_path . "major_leaf_device.png";

                    case 'minor':
                        return $icon_path . "minor_leaf_device.png";

                    case 'warning':
                        return $icon_path . "warning_leaf_device.png";

                    default:
                        return $icon_path . "clear_leaf_device.png";
                        //return $icon_path . "undetermined_leaf_device.png";

                }
            } else {

                switch ($sev) {
                    case 'critical':
                        return $icon_path . "critical_folder.png";

                    case 'major':
                        return $icon_path . "major_folder.png";

                    case 'minor':
                        return $icon_path . "minor_folder.png";

                    case 'warning':
                        return $icon_path . "warning_folder.png";

                    default:
                        return $icon_path . "clear_folder.png";
                        //return $icon_path . "undetermined_leaf_folder.png";

                }

            }
        }


    }

?>

