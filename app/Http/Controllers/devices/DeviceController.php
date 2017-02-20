<?php namespace Unified\Http\Controllers\devices;

use DB;
use Input;
use Excel;
use PHPExcel_Shared_Date;
use PHPExcel_Style_Alignment;
use Session;
use Unified\Models\NetworkTreeMap;
use Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;
use Unified\Browser\GuacamoleUrlBuilder;
use Unified\Devices\Build\BuildInfo;
use Unified\Devices\Build\BuildManager;
use Unified\Devices\Build\BuildProgress;
use Unified\Devices\Build\DeviceBuilder;
use Unified\Devices\Rebuild\DeviceRebuilder;
use Unified\Devices\Rebuild\RebuildManager;
use Unified\Devices\Rebuild\RebuildStarter;
use Unified\Devices\Scan\ScanStarter;
use Unified\Devices\Scan\ScanManager;
use Unified\Devices\Scan\ScanProgress;
use Unified\Jobs\BuildDeviceJob;
use Unified\Models\NetworkTree;
use Unified\Models\Device;
use Unified\Models\DeviceType;
use Unified\Models\DeviceClass;
use Unified\Models\DeviceProp;
use Unified\Http\Helpers\devices\DeviceHelper;
use Unified\Http\Helpers\nodes\NodeHelper;
use Unified\Http\Helpers\GeneralHelper;
use Unified\Browser\BrowserManager;
use Unified\VirtualDeviceManager\VirtualDeviceType\ExternalRTU;

class DeviceController extends \BaseController
{
    use DispatchesJobs;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = [];
        $data['scanInterval'] = '15';

        return [
            'data' => $data
        ];
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
        // validate the posted form data
        $result = DeviceController::validateForm();

        if ($result['success']) { // validation successful - store the record
            $buildInfo = new BuildInfo();
            $deviceToken = $buildInfo->getDeviceToken();
            $buildInfo->setParentNodeId(Input::get('parentNodeId'));
            $buildInfo->setName(Input::get('deviceName'));
            $buildInfo->setTypeId(Input::get('deviceType'));
            $buildInfo->setIpAddress(Input::get('primaryIpAddress'));
            $buildInfo->setIpAddress2(Input::get('secondaryIpAddress'));
            $buildInfo->setReadCommunity(Input::get('snmpRead'));
            $buildInfo->setWriteCommunity(Input::get('snmpWrite'));
            $buildInfo->setWebUiUserName(Input::get('webUsername'));
            $buildInfo->setWebUiPassword(Input::get('webPassword'));
            $buildInfo->setSnmpVersion(Input::get('snmpVer'));
            $buildInfo->setSnmpAuthType(Input::get('snmpAuthType'));
            $buildInfo->setSnmpUserName(Input::get('snmpUsername'));
            $buildInfo->setSnmpAuthPassword(Input::get('snmpAuthPassword'));
            $buildInfo->setSnmpAuthEncryption(Input::get('snmpAuthEncryption'));
            $buildInfo->getSnmpAuthPassword(Input::get('snmpAuthPassword'));
            $buildInfo->getSnmpPrivEncryption(Input::get('snmpPrivacyEncryption'));
            $buildInfo->setSnmpPrivPassword(Input::get('snmpPrivPassword'));
            $buildInfo->setScanInterval(Input::get('scanInterval'));

            if(is_array(Input::get('devicePorts'))) {
                // build array with port info as the builder expects
                $portsInfo = [];
                foreach (Input::get('devicePorts') as $port) {
                    $portsInfo[$port['variable_name']] = $port['port'];
                }
                $buildInfo->setPorts($portsInfo);
            }

            try {
                $this->dispatch(new BuildDeviceJob($buildInfo));
                return ['success' => true, 'token' => $deviceToken];
            } catch (\Unified\Devices\Build\BuildDeviceException $e) {
                return [
                    'success' => false,
                    'errors' => ['formError' => $e->getMessage()]
                ];
            }
        } else { // validation failed
            return $result;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $nodeId
     *
     * @return Response
     */
    public function show($id)
    {
        $node = NetworkTree::find($id);
        $device = Device::find($node->device_id);
        if ($device == null) {
            //placehold for a 'device not found' view

            return [
                'data' => null
            ];
        } else {
            //TODO, make this part of the model's definition
            $type = DeviceType::find($device->type_id);
            $class = DeviceClass::find($type->class_id);
            $device->hasTwoScanners = ($type->prop_scan_file) ? true : false;
            $device->typeName = $type->vendor . " " . $type->model;
            $device->className = $class->description;
            $device->nodeId = $id;
            $device->timeZone = GeneralHelper::getSystemTimeZone();

            $portInfo = $this->getPortInfo($node);
            $device->ports = $portInfo;

            // camera events
            // TODO: refactor properly
            if ($device->type_id == '1623') {
                // hacky hack
                // todo: use props
                $eventInfo = $this->getEventInfo($device->mac_address);
                $device->events = $eventInfo;
            }

            //$device->ports = [[]]
            $view = "devices/DeviceInfo";
            $name = "devInfo";
            $data = $device->toArray();

            $scanIntervals = DeviceHelper::GetInheritedScanInterval($id);

            $data = array_merge($data, $scanIntervals);

            return [
                'data' => $data
            ];
        }
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

    public function getPortInfo($node)
    {
        /*      SELECT d.name deviceName,
                                            ucase(dprtd.name) portType,
                                            dprt.port modemPort,
                                            dprtd.default_port devicePort, dprt.id portId
                                            FROM
                                            css_networking_network_tree_map AS tm
                                            Inner Join css_networking_network_tree AS t ON tm.node_id = t.id
                                            Inner Join css_networking_device AS d ON t.device_id = d.id
                                            Inner Join css_networking_device_port AS dprt ON d.id = dprt.device_id
                                            Inner Join css_networking_device_port_def AS dprtd ON dprt.port_def_id = dprtd.id
                                            where
                                            tm.deleted = 0 AND
                                            tm.node_map like CONCAT('%.217317.%') AND -- main node id yo...
                                            d.ip_address = '166.158.44.227'
                                            order by d.name*/


        $ipAddr = DeviceHelper::getMainIp($node);
        $mainNode = NodeHelper::getMainDevNodeFromNodeId($node->id);


        $ports = NetworkTreeMap::getPortInfo($mainNode->id, $ipAddr);
        $portArr = [];
        for ($i = 0; $i < count($ports); $i ++) {
            //var_dump($props[$i]);
            $portArr[$i]['Device Name'] = $ports[$i]->deviceName;
            $portArr[$i]['Port Type'] = $ports[$i]->portType;
            $portArr[$i]['From'] = $ports[$i]->modemPort;
            $portArr[$i]['To'] = $ports[$i]->devicePort;
        }

        return $portArr;
    }

    public function getEventInfo($rec_mon_id) {
        // Get cURL resource
                $curl = curl_init();
        // ignore certificate
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'https://127.0.0.2/zm/api/events/index/MonitorId:' . $rec_mon_id . '.json'
                ));
        // Send the request & save response to $resp
                $resp = curl_exec($curl);
        // Close request to clear up some resources
                curl_close($curl);
        $events = json_decode($resp);
        return $events->events;
    }

    public function getPorts()
    {

        $nodeId = (string) Input::get('nodeId');
        $node = NetworkTree::find($nodeId);
        $portData = $this->getPortInfo($node);

        return $portData;
    }

    public function startScan($id, $type) {
        $scanStarter = new ScanStarter();
        $scanInfo = $scanStarter->StartScan($id, $type);
        $scanId = $scanInfo->getScanId();
        $result = ['scanId' => $scanId];
        return $result;
    }

    public function startRebuild($nodeId) {
        $rebuildStarter = new RebuildStarter();
        $rebuildInfo = $rebuildStarter->StartRebuild($nodeId);
        $rebuildId = $rebuildInfo->getRebuildId();
        $result = ['rebuildId' => $rebuildId];
        return $result;
    }

    public function getBuildProgress($deviceToken) {
        $bm = new BuildManager();
        $progress = $bm->getBuildProgress($deviceToken);
        $percentage = $progress->getPercentage();
        $status = $progress->getStatus();
        $message = $progress->getMessage();
        $nodeId = $progress->getNodeId();
        $result = ['percentage'=>$percentage, 'status'=>$status, 'message'=>$message, 'node_id'=>$nodeId];
        return $result;
    }

    public function cancelBuild($deviceToken) {
        $bm = new BuildManager();
        $bm->cancelBuild($deviceToken);
    }

    public function getRebuildProgress($deviceId) {
        $rm = new RebuildManager();
        $progress = $rm->getRebuildProgress($deviceId);
        $status = $progress->getStatus();
        $message = $progress->getMessage();
        $nodeId = $progress->getNodeId();
        $result = ['status'=>$status, 'message'=>$message, 'node_id'=>$nodeId];
        return $result;
    }

    public function getScanProgress($scanId) {
        $sm = new ScanManager();
        $progress = $sm->getScanProgress($scanId);
        $percentage = $progress->getPercentage();
        $status = $progress->getStatus();
        $message = $progress->getMessage();
        $result = ['percentage'=>$percentage, 'status'=>$status, 'message'=>$message];
        return $result;
    }

    public function getWebLink($nodeId)
    {
        // if it's a SiteGate environment, return Guacamole URL
        if (env('C2_SERVER_TYPE') == 'sitegate') {
            $browserManager = new BrowserManager();
            $url = $browserManager->GetGuacUrlForNode($nodeId);
        } else { // if it's a Unified environment, return the actual device URL
            $url = DeviceHelper::getDeviceWebLink($nodeId);
        }
        return $url;
    }

    public function removeDevice($nodeId) {
        // safeguards: if it's node 321, or a SiteGate and this is a request to delete node 5000, do nothing
        if ($nodeId == '321' || (env('C2_SERVER_TYPE') == 'sitegate' && $nodeId == '5000')) {
            return;
        }

        //TODO: (later) Delete nodes from custom trees

        // Set the deleted flags for the devices/groups
        NetworkTreeMap::where('node_map', 'LIKE', '%.'.$nodeId.'.%')->update(['deleted' => '1']);

        $nodeIds = NetworkTreeMap::where('node_map', 'LIKE', '%.'.$nodeId.'.%')->get(array('node_id'));

        foreach ($nodeIds as $nodes) {
            $node = NetworkTree::where('id', '=', $nodeId)->get(array('device_id', 'group_id'));
            // log entries
            //\CssUtil::AddDeviceEntry($nodes->nodeId, "delete");
        }
        
        // Check if removed node included a virtual device, and if so reset the properties
        foreach ($nodeIds as $nodes) {
            $node = NetworkTree::where('id', '=', $nodes->node_id)->first(array('device_id'));
            $device = Device::where('id', '=', $node->device_id)->first(array('type_id'));
            if ($device->type_id == '5063') {
                $class = DeviceType::where('id', '=', $device->type_id)->first(array('id'));
                $virtualDevice = new ExternalRTU($class);
                $virtualDevice->deconfigureSensors($nodes);
            }
        }
  
    }

    public function validateForm()
    {
        // Setup the validator
        $rules = [
            'primaryIpAddress' => 'required|ip',
            'secondaryIpAddress' => 'sometimes|ip',
            'scanInterval' => 'required|integer|min:5',
        ];
        $validator = Validator::make(Input::all(), $rules);

        $validator->sometimes(['snmpRead', 'snmpWrite'], 'required', function($input) {
            return ($input->uses_snmp == '1' && ($input->snmpVer == '1' || $input->snmpVer == '2c'));
        });

        $validator->sometimes(['snmpAuthType'], 'required', function($input) {
            return $input->snmpVer == '3';
        });

        $validator->sometimes(['snmpUserName', 'snmpAuthEncryption', 'snmpAuthPassword'], 'required', function($input) {
            return ($input->snmpVer == '3' && ($input->snmpAuthType == 'authNoPriv' || $input->snmpAuthType == 'authPriv'));
        });

        $validator->sometimes(['snmpPrivacyEncryption', 'snmpPrivacyPassword'], 'required', function($input) {
            return ($input->snmpVer == '3' && $input->snmpAuthType == 'authPriv');
        });

        // Validate the input and return correct response
        if ($validator->fails())
        {
            return [
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }
        return ['success' => true];
    }

    public function validatePreForm()
    {
        $deviceClass = (string) Input::get('deviceClass');
        $deviceType = (array) Input::get('deviceType');
        $checkType = DeviceType::verifyTypeAgainstClass($deviceClass);
        $checkOptions = \Unified\Models\VirtualDevice\VirtualDeviceWizardTemplates::verifyTemplateAgainstType($deviceType);

        // Setup the validator -- Note that double quotes are required for the variable to evaluate
        $rules = [
            'deviceName' => 'required|string|max:64',
            'deviceClass' => 'required',
            'deviceType' => "required|in:$checkType",
            'deviceOptions' => "required_if:deviceType,$checkOptions"
        ];
        $validator = Validator::make(Input::all(), $rules);

        // Validate the input and return correct response
        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ];
        }
        return ['success' => true];
    }

    public function dataExport($nodeId='5000')
    {
        $headers = [];
        $headers['data'] = Device::getDeviceInventory($nodeId);
        //dd($headers);
        Excel::create('DeviceInventory', function ($excel) use ($headers) {

            // Set the title
            $excel->setTitle('Device Inventory');

            // Chain the setters
            $excel->setCreator("C Squared Systems, LLC.")
                ->setCompany("C Squared Systems, LLC.");

            // Call them separately
            $excel->sheet('Devices', function ($sheet) use ($headers) {
                foreach ($headers['data'] as &$row) {
                    $row = (array) $row;
                    // clean up dates and convert to Excel format
                    $row['lastAlarmsScan'] = ($row['lastAlarmsScan'] == '2000-01-01 00:00:00') ? 'n/a' : PHPExcel_Shared_Date::PHPToExcel(strtotime($row['lastAlarmsScan']));
                    $row['lastPropertiesScan'] = ($row['lastPropertiesScan'] == '2000-01-01 00:00:00') ? 'n/a' :PHPExcel_Shared_Date::PHPToExcel(strtotime($row['lastPropertiesScan']));
                    $row['path'] = preg_replace('/All Clients >> /', '', $row['path']);
                }
                $sheet->fromArray($headers['data'], null, 'A1', false, false);
                $sheet->setWidth('A', 60);
                $sheet->setWidth('B', 16);
                $sheet->setWidth('C', 14);
                $sheet->setWidth('D', 22);
                $sheet->setWidth('E', 22);
                $sheet->setWidth('F', 18);
                $sheet->getDefaultStyle()->getAlignment()->setWrapText(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                // set format on date columns
                $sheet->getStyle("D1:D".count($headers['data']))->getNumberFormat()->setFormatCode('m/d/yyyy hh:mm');
                $sheet->getStyle("E1:E".count($headers['data']))->getNumberFormat()->setFormatCode('m/d/yyyy hh:mm');

                // add headers
                $sheet->prependRow(1, array(
                    'Device Name', 'IP Address', 'Ports', 'Last Alarms Scan', 'Last Properties Scan', 'Alarm Count'
                ));

                // format header row
                $sheet->row(1, function($row) {
                    $row->setBackground('#4F81BD');
                    $row->setAlignment('center');
                    $row->setFontColor('#FFFFFF');
                });
                $sheet->setAutoFilter();


            });
        })->export('xls');
    }
}
