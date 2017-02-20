<?php namespace Unified\Http\Controllers\nodes;

use Unified\Http\Controllers\devices\DeviceController;

class ActionController extends \BaseController
{
    //put your code here
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {

        /*SELECT d.stop_scan_until, d.stop_alarm_until, d.stop_property_until, d.web_enabled, dt.scan_file, dt.prop_scan_file, dt.has_webInterface-- dt.prop_scan_file, dt.scan_file-- , dt.id
        from css_networking_network_tree as nt
        inner join css_networking_device as d on d.id = nt.device_id
        inner join css_networking_device_type as dt on dt.id = d.type_id
        where nt.id = $id;*/

        $node = NetworkTree::find($id);
        $mainDev = NodeHelper::getMainDevNodeFromNodeId($node->id);
        $device = Device::find($mainDev->device_id);
        $devType = DeviceType::find($device->type_id);
        $buttonList = array();
        if ($devType->has_webInterface == 1) {
            $httpLink = DeviceController::getWebLink($mainDev->id);
            if ($httpLink !== false) {
                $buttonList[] = array(
                    'dispText' => "Launch Web Interface",
                    'command'  => "target='_blank' href='$httpLink'"
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
                'dispText'    => "Scan Device",
                'command'     => "href='/DeviceActions/$id/launchScan/scan'",
                'description' => "$activeScanDetails"
            );

            if ($device->stop_scan_until < date("Y-m-d H:i:s")) {
                $buttonList[] = array(
                    'dispText' => "Stop Scanning",
                    'command'  => "href='/DeviceActions/$id/stopscan/scan'"
                );
            } else {
                $startTime = NodeHelper::getStartTime($device->stop_scan_until);
                if ($device->stop_scan_notes) {
                    $description = "Scanning has been stopped $startTime with given reason: {$device->stop_scan_notes}";
                } else {
                    $description = "Scanning has been stopped $startTime with no given reason.";
                }
                $buttonList[] = array(
                    'dispText'    => "Start Scanning",
                    'command'     => "href='/DeviceActions/$id/startScan/scan'",
                    'description' => "$description"
                );
            }
        } else {
            //echo $device->id."<br/> ". $mainDev->id."<br/>".$devType->scan_file."<br/> ";
            $activeScanDetailsAlarm = DeviceHelper::getActiveDeviceScanTime($device->id, $mainDev->id,
                $devType->scan_file);
            $buttonList[] = array(
                'name'        => "alarm",
                'dispText'    => "Scan Device Alarms",
                'command'     => "href='/DeviceActions/$id/launchScan/alarm'",
                'description' => "$activeScanDetailsAlarm"
            );
            //var_dump($activeScanDetailsAlarm);
            //echo ;
            $activeScanDetailsProp = DeviceHelper::getActiveDeviceScanTime($device->id, $mainDev->id,
                $devType->prop_scan_file);
            //var_dump($activeScanDetailsProp);
            $buttonList[] = array(
                'name'        => "prop",
                'dispText'    => "Scan Device Properties",
                'command'     => "href='/DeviceActions/$id/launchScan/prop'",
                'description' => "$activeScanDetailsProp"
            );

            if ($device->stop_alarm_until < date("Y-m-d H:i:s")) {
                $buttonList[] = array(
                    'dispText' => "Disable Alarm Scanning",
                    'command'  => "href='/DeviceActions/$id/stopscan/alarm'"
                );
            } else {
                $startTime = NodeHelper::getStartTime($device->stop_alarm_until);
                if ($device->stop_scan_notes) {
                    $description = "Scanning has been stopped $startTime with given reason: {$device->stop_scan_notes}";
                } else {
                    $description = "Scanning has been stopped $startTime with no given reason.";
                }
                $buttonList[] = array(
                    'dispText'    => "Enable Alarm Scanning",
                    'command'     => "href='/DeviceActions/$id/startScan/alarm'",
                    'description' => "$description"
                );
            }
            if ($device->stop_property_until < date("Y-m-d H:i:s")) {
                $buttonList[] = array(
                    'dispText' => "Disable Property Scanning",
                    'command'  => "href='/DeviceActions/$id/stopscan/prop'"
                );
            } else {
                $startTime = NodeHelper::getStartTime($device->stop_property_until);
                if ($device->stop_scan_notes) {
                    $description = "Scanning has been stopped $startTime with given reason: {$device->stop_scan_notes}";
                } else {
                    $description = "Scanning has been stopped $startTime with no given reason.";
                }
                $buttonList[] = array(
                    'dispText'    => "Enable Property Scanning",
                    'command'     => "href='/DeviceActions/$id/startScan/prop'",
                    'description' => "$description"
                );
            }

        }
        //var_dump($buttonList);
        $res['buttonList'] = $buttonList;
        $name = "actions";

        return GeneralHelper::makeWithExtras('nodes/ActionsMenu', $res, $id, $name);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function update($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function startScanPost($id, $type)
    {
        $mainDevNode = NodeHelper::getMainDevNodeFromNodeId($id);
        $node = NetworkTree::find($mainDevNode->id);
        $device = Device::find($node->device_id);

        $date = new DateTime("2000-01-01 00:00:00");

        if ($type == "scan") {
            $device->stop_scan_until = $date->format('Y-m-d H:i:s');
        } else if ($type == "alarm") {
            $device->stop_alarm_until = $date->format('Y-m-d H:i:s');
        } else if ($type == "prop") {
            $device->stop_property_until = $date->format('Y-m-d H:i:s');
        }
        $device->save();

        return $this->show($id);
    }

    public function launchScan($id, $type)
    {
        return $this->show(NodeHelper::launchScan($id, $type));
        //echo "Ready to launch $id $type<br/>";
        /*$node =  NodeHelper::getMainDevNodeFromNodeId($id);
        if($node !=null && is_numeric($node->device_id) && $node->device_id > 0){
            $filePath = $this->getScanFile($node, $type);
            if($filePath === false){
                //no file was found
                echo "TODO invalid scan type";
                return $this->show($id);
            }
            //$_ENV variables are defined in /SitePortalMobile/.env.php if that file is missing it needs to be populated.
            $environment = $_ENV['cswapi_path'];
            $filePath = "$environment/networking/scanners/".$filePath;
            //echo $filePath." ". $node->id;

            $pid = GeneralHelper::ExecBackground($_ENV['php_path'], $_ENV['php_command'], $filePath, $node->id);
        }else{
            echo "TODO Make this a notice";
            return Redirect::to("/home")->with('flash_error', 'Invalid Node Id');
        }
        return $this->show($id);*/
    }


    public function updateScanInfo()
    {

        $str = (string) Input::get('nodeId');
        $node = NetworkTree::find($str);
        $mainDev = NodeHelper::getMainDevNodeFromNodeId($node->id);
        $device = Device::find($mainDev->device_id);
        $devType = DeviceType::find($device->type_id);
        $ret = array();
        if ($devType->prop_scan_file == null || $devType->prop_scan_file == "") {
            $ret['scan'] = $activeScanDetails = DeviceHelper::getActiveDeviceScanTime($device->id, $mainDev->id,
                $devType->scan_file);
        } else {
            $ret['alarm'] = $activeScanDetailsAlarm = DeviceHelper::getActiveDeviceScanTime($device->id, $mainDev->id,
                $devType->scan_file);
            $ret['prop'] = $activeScanDetailsProp = DeviceHelper::getActiveDeviceScanTime($device->id, $mainDev->id,
                $devType->prop_scan_file);
        }
        $res = json_encode($ret);

        return $res;
    }


}