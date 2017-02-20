<?php namespace Unified\Http\Helpers\nodes;

use Auth;
use DB;
use Unified\Http\Helpers\GeneralHelper;
use Unified\Http\Controllers\devices\DeviceController;
use Unified\Http\Helpers\devices\DeviceHelper;
use Unified\Models\Device;
use Unified\Models\DeviceType;
use Unified\Models\NetworkTree;
use Unified\Models\NetworkTreeMap;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Fetch this nodes children.
 *
 * @param  int $nodeId
 *
 * @return array child(nodeId, name, isGroup)
 */
class NodeHelper
{
    public static function getNodeChildren($nodeId, $includeSeverity = true)
    {
        $children = NetworkTree::getNodeChildren($nodeId, $includeSeverity);
        $child = array();

        for ($i = 0; $i < count($children); $i ++) {
            $child[$i]['nodeId'] = $children[$i]->id;
            $child[$i]['sevNum'] = $children[$i]->sev == null ? 100 : $children[$i]->sev; // -1 is clered
            $child[$i]['numChildren'] = $children[$i]->numChildren;

            $child[$i]['sev'] = GeneralHelper::getNameFromSeverity($child[$i]['sevNum']);
            if ($children[$i]->dName == null || $children[$i]->dName == '') {
                $child[$i]['name'] = $children[$i]->gName;
                $child[$i]['isGroup'] = true;
            } else {
                $child[$i]['name'] = $children[$i]->dName;
                $child[$i]['isGroup'] = false;
            }
        }
        //var_dump($child);
        //die;
        return $child;
    }

    /**
     * Count this nodes children.
     *
     * @param  int $nodeId
     *
     * @return array child(nodeId, name, isGroup)
     */
    public static function countNodeSubtree($nodeMap)
    {
        return NetworkTreeMap::countNodeSubtree($nodeMap);
    }


    /**
     * Fetch this node.
     *
     * @param  int $nodeId
     *
     * @return array(name, isGroup, nodeId)
     */
    public static function getNodeById($nodeId)
    {
        $res = array();
        $node = NetworkTree::find($nodeId);
        $name = NodeHelper::getNodeNameAndType($nodeId);
        $res['name'] = $name['name'];
        $res['isGroup'] = $name['isGroup'];
        $res['nodeId'] = $node->id;

        return $res;
    }

    /**
     * Fetch this nodes parent.
     *
     * @param  int $nodeId
     *
     * @return Parent node
     */
    public static function getNodeParent($nodeId, $includeSeverity=true)
    {
        if (Auth::user()->home_node_id == $nodeId) {
            //We are at the users home node there is no parent
            return "";
        }
        $thisNode = NetworkTree::find($nodeId);
        $parent = NetworkTree::find($thisNode->parent_node_id);
        $parentName = NodeHelper::getNodeNameAndType($parent->id);
        if ($includeSeverity) {
            $nodeSev = NodeHelper::getAlarmSeverityForNode($parent->id);
            $res['sev'] = $nodeSev['sev'];
            $res['sevNum'] = $nodeSev['sevNum'];
        } else {
            $res['sev'] = 'unknown';
            $res['sevNum'] = - 2;
        }
        $res['name'] = $parentName['name'];
        $res['isGroup'] = $parentName['isGroup'];
        $res['nodeId'] = $parent->id;

        return $res;
    }

    /**
     * Get this nodes device or group name depending on what kind of node it is
     *
     * @param  int $nodeId
     *
     * @return array name
     */
    public static function getNodeNameAndType($nodeId) {
        $res = array();
        $node = NetworkTree::getNodeNameAndType($nodeId);

        $i = 0;
        if (count($node) > 0) {
            if ($node[$i]->dName == null || $node[$i]->dName == '') {
                $res['name'] = $node[$i]->gName;
                $res['isGroup'] = true;
            } else {
                $res['name'] = $node[$i]->dName;
                $res['isGroup'] = false;
            }
        } else {
            $res['name'] = "Invalid Node Id";
            $res['isGroup'] = null;
        }

        return $res;
    }

    /**
     * Take all parents and children and turn them into an array that can be used to
     * render the NodeChange & Home pages
     *
     * @param  array  $parent    - the parent of the selected node
     * @param  array  $children  - the children of the selected node
     * @param boolean $usingHome - true if on the home page after logging in
     *
     * @return array $formattedList - The list to be displayed on Node selection
     */
    public static function formatNodes($parent, $current, $children, $usingHome, $includeSeverity = true)
    {
        $formattedList = array();
        $offSet = 0;
        if (count($parent) > 0 && $parent != "") {
            if ($parent['name'] != "Invalid Node Id") {
                $formattedList[$offSet]['nodeId'] = $usingHome ? "NodeChange/" . $parent['nodeId'] : $parent['nodeId'];
                $formattedList[$offSet]['nodeIdNum'] = $parent['nodeId'];
                $formattedList[$offSet]['isGroup'] = $parent['isGroup'];
                $formattedList[$offSet]['name'] = "[Parent] ";
                $formattedList[$offSet]['name'] .= $parent['name'];
                if ($includeSeverity && isset($parent['sev']['severity'])) {
                    $formattedList[$offSet]['sev'] = $parent['sev']['severity'];
                } else {
                    $formattedList[$offSet]['sev'] = - 2;
                }
                $offSet ++;
            }
        }
        if (count($current) > 0 && $current != "") {
            if ($current['name'] != "Invalid Node Id") {
                $formattedList[$offSet]['nodeId'] = $usingHome ? "NodeChange/" . $current['nodeId'] : $current['nodeId'];
                $formattedList[$offSet]['nodeIdNum'] = $current['nodeId'];
                $formattedList[$offSet]['isGroup'] = $current['isGroup'];
                $formattedList[$offSet]['name'] = "[Current] ";
                $formattedList[$offSet]['name'] .= $current['name'];
                $formattedList[$offSet]['isCurrent'] = "true";
                $formattedList[$offSet]['hasChildren'] = (count($children) > 0) ? 'hasChildren' : 'noChildren';
                if ($includeSeverity) {
                    $curSev = NodeHelper::getAlarmSeverityForNode($current['nodeId']);
                    $formattedList[$offSet]['sev'] = $curSev['sev']['severity'];
                } else {
                    $formattedList[$offSet]['sev'] = - 2;
                }
                $offSet ++;
            }
        }

        if (count($children) > 0) {
            for ($i = 0; $i < count($children); $i ++) {
                $index = $i + $offSet;
                $formattedList[$index]['nodeId'] = $children[$i]['nodeId'];
                $formattedList[$index]['nodeIdNum'] = $children[$i]['nodeId'];
                $formattedList[$index]['isGroup'] = $children[$i]['isGroup'];
                //$formattedList[$index]['name'] = $children[$i]['isGroup']==1?$children[$i]['name']." [Folder]":$children[$i]['name']." [Device]";
                $formattedList[$index]['name'] = $children[$i]['name'];
                $formattedList[$index]['hasChildren'] = ($children[$i]['numChildren'] > 0) ? 'hasChildren' : 'noChildren';
                $formattedList[$index]['sev'] = $children[$i]['sev']['severity'];
            }
        }

        return $formattedList;
    }

    public static function getMainDevNodeFromNodeId($nodeId)
    {
        /*	SELECT ntm.breadcrumb from css_networking_network_tree nt
        Inner join css_networking_network_tree_map ntm on ntm.node_map like Concat("%.",nt.id,".%")
        where nt.device_id = node_main_device_id(240948);*/
        $idAsANumber;
        if (is_int($nodeId) || is_string($nodeId)) {
            $idAsANumber = $nodeId;
        } else if (isset($nodeId->id)) {
            $idAsANumber = $nodeId->id;
        } else {
            //a valid id wasn't found
            return;
        }
        $main = DB::select("SELECT Main_Device_Id($idAsANumber) as node_main_device_id");
        if ($main[0]->node_main_device_id) {
            $node_main_device_id = $main[0]->node_main_device_id;
            $node = DB::table('css_networking_network_tree as nt')
                ->where('nt.device_id', '=', $node_main_device_id)
                ->get();
            if (count($node) > 0) {
                return $node[0];
            }
        }

        return null;
    }


    public static function getAlarmSeverityForNode($nodeId)
    {

        $nodeSeverity = NetworkTree::getAlarmSeverityForNode($nodeId);

        if (count($nodeSeverity) > 0) {
            $node['sevNum'] = $nodeSeverity[0]->sev == null ? 100 : $nodeSeverity[0]->sev;
            $node['sev'] = GeneralHelper::getNameFromSeverity($nodeSeverity[0]->sev == null ? 100 : $nodeSeverity[0]->sev);
        }

        return $node;
    }

    public static function getAvailableActions($nodeId)
    {
        /*SELECT d.stop_scan_until, d.stop_alarm_until, d.stop_property_until, d.web_enabled, dt.scan_file, dt.prop_scan_file, dt.has_webInterface-- dt.prop_scan_file, dt.scan_file-- , dt.id
        from css_networking_network_tree as nt
        inner join css_networking_device as d on d.id = nt.device_id
        inner join css_networking_device_type as dt on dt.id = d.type_id
        where nt.id = $id;*/
        //echo $nodeId;
        $node = NetworkTree::find($nodeId);
        $mainDev = NodeHelper::getMainDevNodeFromNodeId($node->id);
        if ($mainDev == null) {
            return null;
        }
        $device = Device::find($mainDev->device_id);
        $devType = DeviceType::find($device->type_id);
        $buttonList = array();
        if ($devType->has_webInterface == 1) {
            $httpLink = DeviceController::getWebLink($node);
            if ($httpLink !== false) {
                $buttonList[] = array(
                    'dispText' => trans('menuArea.web'),
                    'command'  => "$httpLink",
                    'target'   => "_blank",
                    'icon'     => "tmp.png",
                    'enabled'  => true
                );
            } else {
                echo "There was an error getting the link for the Launch Web Interface";
            }
        }
        if ($devType->prop_scan_file == null || $devType->prop_scan_file == "") {
            //TODO Get Alert confirmation before turning scanning back on
            //echo $device->id."<br/> ". $mainDev->id."<br/>".$devType->scan_file."<br/> ";
            $activeScanDetails = DeviceHelper::getActiveDeviceScanTime($device->id, $mainDev->id, $devType->scan_file);
            $buttonList[] = array(
                'name'        => "scan",
                'dispText'    => trans('menuArea.scandev'),
                'command'     => "/launchScan/$nodeId/scan",
                'description' => "$activeScanDetails",
                'icon'        => "tmp.png",
                'enabled'     => true
            );

            if ($device->stop_scan_until < date("Y-m-d H:i:s")) {
                $buttonList[] = array(
                    'dispText' => trans('menuArea.disablescan'),
                    'command'  => "/DeviceActions/$nodeId/stopscan/scan",
                    'target'   => "_self",
                    'icon'     => "tmp.png",
                    'enabled'  => true
                );
            } else {
                $startTime = NodeHelper::getStartTime($device->stop_scan_until);
                if ($device->stop_scan_notes) {
                    $description = "Scanning has been stopped $startTime with given reason: {$device->stop_scan_notes}";
                } else {
                    $description = "Scanning has been stopped $startTime with no given reason.";
                }
                $buttonList[] = array(
                    'dispText'    => trans('menuArea.enablescan'),
                    'command'     => "/DeviceActions/$nodeId/startScan/scan",
                    'description' => "$description",
                    'icon'        => "tmp.png",
                    'enabled'     => true
                );
            }
        } else {
            //echo $device->id."<br/> ". $mainDev->id."<br/>".$devType->scan_file."<br/> ";
            $activeScanDetailsAlarm = DeviceHelper::getActiveDeviceScanTime($device->id, $mainDev->id,
                $devType->scan_file);
            $buttonList[] = array(
                'name'        => "alarm",
                'dispText'    => trans('menuArea.scanalarm'),
                'command'     => "/launchScan/$nodeId/alarm",
                'description' => "$activeScanDetailsAlarm",
                'icon'        => "tmp.png",
                'enabled'     => true
            );
            $activeScanDetailsProp = DeviceHelper::getActiveDeviceScanTime($device->id, $mainDev->id,
                $devType->prop_scan_file);
            $buttonList[] = array(
                'name'        => "prop",
                'dispText'    => trans('menuArea.scanprop'),
                'command'     => "/launchScan/$nodeId/prop",
                'description' => "$activeScanDetailsProp",
                'icon'        => "tmp.png",
                'enabled'     => true
            );

            if ($device->stop_alarm_until < date("Y-m-d H:i:s")) {
                $buttonList[] = array(
                    'dispText' => trans('menuArea.disablealarm'),
                    'command'  => "/DeviceActions/$nodeId/stopscan/alarm",
                    'target'   => "_self",
                    'icon'     => "tmp.png",
                    'enabled'  => true
                );
            } else {
                $startTime = NodeHelper::getStartTime($device->stop_alarm_until);
                if ($device->stop_scan_notes) {
                    $description = "Scanning has been stopped $startTime with given reason: {$device->stop_scan_notes}";
                } else {
                    $description = "Scanning has been stopped $startTime with no given reason.";
                }
                $buttonList[] = array(
                    'dispText'    => trans('menuArea.enablealarm'),
                    'command'     => "/DeviceActions/$nodeId/startScan/alarm",
                    'description' => "$description",
                    'icon'        => "tmp.png",
                    'enabled'     => true
                );
            }
            if ($device->stop_property_until < date("Y-m-d H:i:s")) {
                $buttonList[] = array(
                    'dispText' => trans('menuArea.disableprop'),
                    'command'  => "/DeviceActions/$nodeId/stopscan/prop",
                    'target'   => "_self",
                    'icon'     => "tmp.png",
                    'enabled'  => true
                );
            } else {
                $startTime = NodeHelper::getStartTime($device->stop_property_until);
                if ($device->stop_scan_notes) {
                    $description = "Scanning has been stopped $startTime with given reason: {$device->stop_scan_notes}";
                } else {
                    $description = "Scanning has been stopped $startTime with no given reason.";
                }
                $buttonList[] = array(
                    'dispText'    => trans('menuArea.enableprop'),
                    'command'     => "/DeviceActions/$nodeId/startScan/prop",
                    'description' => "$description",
                    'icon'        => "tmp.png",
                    'enabled'     => true
                );
            }

        }
        //var_dump($buttonList);
        $res['buttonList'] = $buttonList;

        //$name = "actions";
        return $res;
    }

    public static function launchScan($id, $type)
    {
        //echo "Ready to launch $id $type<br/>";
        $node = NodeHelper::getMainDevNodeFromNodeId($id);
        if ($node != null && is_numeric($node->device_id) && $node->device_id > 0) {
            $filePath = NodeHelper::getScanFile($node, $type);
            if ($filePath === false) {
                //no file was found
                echo "TODO invalid scan type";
                // $this is not accessible in a static function     return $this->show($id);
            }
            //$_ENV variables are defined in /SitePortalMobile/.env.php if that file is missing it needs to be populated.
            $environment = $_ENV['cswapi_path'];
            $filePath = "$environment/networking/scanners/" . $filePath;
            //echo $filePath." ". $node->id;

            $pid = GeneralHelper::ExecBackground($_ENV['php_path'], $_ENV['php_command'], $filePath, $node->id);
        } else {
            echo "TODO Make this a notice";

            return Redirect::to("/home")->with('flash_error', 'Invalid Node Id');
        }

        return $id;
    }

    public static function getScanFile($node, $type)
    {
        $device = Device::find($node->device_id);
        $deviceType = DeviceType::find($device->type_id);
        if ($type == "scan" || $type == "alarm") {
            $file = $deviceType->scan_file;
        } else if ($type == "prop") {
            $file = $deviceType->prop_scan_file;
        } else {
            return false;
        }

        return $file;
    }

    public static function getStartTime($time)
    {
        if ($time >= "2028-05-14 00:00:00") {
            $startTime = "indefinatly";
        } else {
            $startTime = "until " . $time;
        }

        return $startTime;
    }

    public static function SetBreadcrumbs()
    {
        // this function can be run manually to fix broken or missing breadcrumbs in the database
        /*

        $sql = "SELECT id,node_map FROM css_networking_network_tree_map where deleted = 0 and build_in_progress = 0";
        $stmt = DB::raw($sql);
        $nodesToUpdate = DB::select($stmt);

        $sqlUpdateString = "";
        $nodeDictionary = NodeHelper::GetNodeNames();

        foreach ($nodesToUpdate as $targetNode) {
            $crumb = addslashes(NodeHelper::BuildBreadcrumb($targetNode->node_map, $nodeDictionary));
            //$sqlUpdateString .= "UPDATE css_networking_network_tree_map set breadcrumb = '" . $crumb . "' WHERE "
            //        ."id = '{$targetNode->id}' AND breadcrumb <> '$crumb';\n";
            $sqlUpdateString .= "UPDATE css_networking_network_tree_map set breadcrumb = '" . $crumb . "' WHERE "
                    ."id = '{$targetNode->id}';\n";
        }

        $sql = "$sqlUpdateString";
        $stmt = DB::raw($sql);
        DB::statement($sql);
       */
    }

    public static function GetNodeNames()
    {
        return NetworkTreeMap::getNodeNames();
    }

    public static function BuildBreadcrumb($nodeMap, $dictionary, $NMdelimiter = ".", $breadcrumbDelimiter = " >> ")
    {

        $rawNodeMap = explode($NMdelimiter, $nodeMap);
        $breadcrumbList = array();
        $start = 1;
        $limit = count($rawNodeMap) - 1; //we must ignore the first and last elements, because nodemap looks like .a.b.c.
        for ($i = $start; $i < $limit; $i ++) {
            if (! array_key_exists($rawNodeMap[$i], $dictionary)) {
                //error
                $breadcrumbList[] = 'NULL';
            } else {
                $breadcrumbList[] = $dictionary[$rawNodeMap[$i]];
            }
        }

        $fullBC = implode($breadcrumbDelimiter, $breadcrumbList);

        return $fullBC;
    }
}
