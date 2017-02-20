<?php

namespace Unified\Devices\Build;

use DB;
use ReflectionFunction;
use stdClass;
use Unified\Devices\Build\BuildInfo;
use Unified\Models\DevicePortDef;
use Unified\Models\DeviceType;
use Unified\Models\DevicePort;
use Unified\Models\NetworkTree;
use Unified\Models\NetworkTreeMap;

/**
 * Call cswapi to build a Device
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class DeviceBuilder
{

    private $buildInfo;

    public function __construct(BuildInfo $buildInfo)
    {
        $this->buildInfo = $buildInfo;
    }

    public function Build()
    {
        $cswapiRoot = env('CSWAPI_ROOT');

        $deviceType = DeviceType::find($this->buildInfo->getTypeId());

        if ($deviceType === null) {
            throw new BuildDeviceException("Device type {$this->buildInfo->getTypeId()} not found.");
        }

        // try to get the build file from the device type table.
        $buildFile = $deviceType->build_file;

        if (empty($buildFile)) {
            // build file not found so try the cswapi way. It has a lot of hard coded stuff.
            require_once($cswapiRoot . '/common/class/CssUtil.php');

            $buildFile = \CssUtil::getBuildFile($deviceType->model, $deviceType->vendor);
        }

        if (empty($buildFile)) {
            throw new BuildDeviceException("No build file for {$this->buildInfo->getTypeId()}.");
        }

        $buildFile = $cswapiRoot . '/networking/builders/' . $buildFile;

        if (!file_exists($buildFile)) {
            throw new BuildDeviceException("Build file: {$buildFile} does not exist.");
        }

        $devInfo = new stdClass();

        $devInfo->name = $this->buildInfo->getName();
        $devInfo->ipAddress = $this->buildInfo->getIpAddress();
        $devInfo->ipAddressSecondary = $this->buildInfo->getIpAddress2();
        $devInfo->readCommunity = $this->buildInfo->getReadCommunity();
        $devInfo->writeCommunity = $this->buildInfo->getWriteCommunity();
        $devInfo->snmpVersion = $this->buildInfo->getSnmpVersion();
        $devInfo->webUiUsername = $this->buildInfo->getWebUiUserName();
        $devInfo->webUiPassword = $this->buildInfo->getWebUiPassword();

        $devInfo->privEncryption = $this->buildInfo->getSnmpPrivEncryption();
        $devInfo->privPassword = $this->buildInfo->getSnmpPrivPassword();
        $devInfo->userName = $this->buildInfo->getSnmpUserName();
        $devInfo->authEncryption = $this->buildInfo->getSnmpAuthEncryption();
        $devInfo->authType = $this->buildInfo->getSnmpAuthType();
        $devInfo->authPassword = $this->buildInfo->getSnmpAuthPassword();

        $devInfo->vendor = $deviceType->vendor;
        $devInfo->model = $deviceType->model;
        $devInfo->scanInterval = $this->buildInfo->getScanInterval();
        $devInfo->scanOnAdd = false;
        $devInfo->typeId = $this->buildInfo->getTypeId();

        // build array with port info as the builder expects
        $portsInfo = [];

        foreach ($this->buildInfo->getPorts() as $name => $value) {
            $portInfo = new stdClass();
            $portInfo->portVarName = $name;
            $portInfo->newPort = $value;
            $portsInfo[] = $portInfo;
        }

        $portsInfo['length'] = count($this->buildInfo->getPorts());
        $devInfo->ports = $portsInfo;

        $devInfo->deviceToken = $this->buildInfo->getDeviceToken();

        $devInfo->formvars_string = 'Unified Device build';

        require_once($cswapiRoot . '/common/doctrine.php');
        require_once($cswapiRoot . '/networking/class/Build.php');
        require_once($cswapiRoot . '/networking/class/BuildTable.php');

        set_include_path(get_include_path() . PATH_SEPARATOR . '/var/www/');
        require_once $buildFile;

        // some builders expect a logger, some don't.
        // only create the logger if we need it.
        $function = new ReflectionFunction('buildDevice');

        global $buildLogger;

        require_once($cswapiRoot . '/networking/util/LoggerHelper.php');
        $buildLogger = new \LoggerHelper(\LogType::BUILD, \LogLevel::DEBUG);

        if ($function->getNumberOfParameters() > 2) {
            $buildResult = buildDevice($this->buildInfo->getParentNodeId(), $devInfo, $buildLogger);
        } else {
            $buildResult = buildDevice($this->buildInfo->getParentNodeId(), $devInfo);
        }

//        $portDefs = DevicePortDef::where('device_type_id', $this->buildInfo->getTypeId())->get();

        $success = isset($buildResult['success']) && $buildResult['success'] == 1;

        if ($success) {

            $portDefs = DB::table('css_networking_device_port_def')
                ->select('id', 'variable_name', 'default_port')
                ->where('device_type_id', $this->buildInfo->getTypeId())
                ->get();

            foreach($portDefs as $portDef) {

                $port = $this->buildInfo->getPort($portDef->variable_name);

                if ($port === null) {
                    $port = $portDef->default_port;
                }

                $dp = new DevicePort([
                    'port_def_id' => $portDef->id,
                    'device_id'   => $buildResult['deviceId'],
                    'port'        => $port,
                ]);
                $dp->save();
/*                DB::table('css_networking_device_port')
                        ->insert([
                            'port_def_id' => $portDef->id,
                            'device_id' => $buildResult['deviceId'],
                            'port' => $port
                        ]);
*/
            }
        }

        if (isset($buildResult['nodeId']) && is_numeric($buildResult['nodeId'])) {

            $nodeId = $buildResult['nodeId'];

            NetworkTreeMap::where('node_map', 'like', "%$nodeId%")
                ->update([
                    'build_in_progress' => 0,
                    'deleted' => 0,
                ]);
/*
            DB::table('css_networking_network_tree_map')
                ->where('node_map', 'like', '%.' . $nodeId . '.%')
                ->update([
                    'build_in_progress' => 0,
                    'deleted' => 0
                ]);
*/
// TODO Anthony does not work
//             NetworkTree::find($nodeId)
//                 ->update([
//                     'scan_interval' => $this->buildInfo->getScanInterval(),
//                     'scan_alarms_interval' => $this->buildInfo->getScanInterval(),
//                 ]);


            DB::table('css_networking_network_tree')
                    ->where('id', $nodeId)
                    ->update([
                        'scan_interval' => $this->buildInfo->getScanInterval(),
                        'scan_alarms_interval' => $this->buildInfo->getScanInterval()
                    ]);

            // save new node_id. The UI uses this to redirect to device info page.
            if ($success) {
// TODO Anthony Does not work
//                 DeviceBuild::where('device_token', '=', $this->buildInfo->getDeviceToken())
//                             ->update(['node_id' => $nodeId]);
                DB::table('css_networking_build')
                        ->where('device_token', $this->buildInfo->getDeviceToken())
                        ->update([
                            'node_id' => $nodeId
                        ]);
            }
        }

        // The result is an array from the cswapi builder.
        // It is probably ignored because we're running this asynchronously through a queue.
        // The build record in the database tells us the result.
        return $buildResult;
    }
}
