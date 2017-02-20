<?php

namespace Unified\Devices\Scan;

use DB;
use Unified\Models\Device;
use Unified\Models\DeviceScan;
use Unified\Models\NetworkTree;

/* * ,
 * Description of DeviceScanner
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */

class DeviceScanner
{

    public function __construct(ScanInfo $scanInfo)
    {
        $this->scanInfo = $scanInfo;
    }

    private $scanInfo;

    public function Scan()
    {
        $cswapiRoot = ENV('CSWAPI_ROOT');
//        set_include_path(get_include_path() . PATH_SEPARATOR . '/var/www/');
        // Get scan data for this node
        $qResult = NetworkTree::getScanData($this->scanInfo->getNodeId());

/*        $qResult = DB::table('css_networking_network_tree as nt')
                ->select('dt.id as device_type_id', 'dt.vendor', 'dt.model', 'dt.scan_file', 'dt.prop_scan_file')
                ->join('css_networking_device as d', 'nt.device_id', '=', 'd.id')
                ->join('css_networking_device_type as dt', 'd.type_id', '=', 'dt.id')
                ->where('nt.id', $this->scanInfo->getNodeId())
                ->first();
*/
        $success = true;
        $message = null;

        if ($qResult === null) {
            $success = false;
            $message = "Device type info not found for node {$this->scanInfo->getNodeId()}";
        }

        if ($success) {

            // try to get the scan file from our query here. The file should be set in the device_type table
            $scanFile = $this->scanInfo->getScanType() === ScanInfo::SCAN_TYPE_ALARM ? $qResult->scan_file : $qResult->prop_scan_file;

            if (empty($scanFile)) {
                // scan file not found so try the cswapi way. It has a lot of hard coded stuff.
                require_once($cswapiRoot . '/common/class/CssUtil.php');

                $deviceType = new \stdClass();
                $deviceType->vendor = $qResult->vendor;
                $deviceType->model = $qResult->model;

                if ($this->scanInfo->getScanType() === ScanInfo::SCAN_TYPE_ALARM) {
                    $scanFile = \CssUtil::GetScanFileFromType($deviceType);
                } else {
                    $scanFile = \CssUtil::GetPropScanFileFromType($deviceType);
                }
            }

            if (empty($scanFile)) {
                $success = false;
                $message = "{$this->scanInfo->getScanTypeName()} scan file not found for device type {$qResult->device_type_id}";
            }
        }

        if ($success) {
            $scanFile = $cswapiRoot . '/networking/scanners/' . $scanFile;

            if (!file_exists($scanFile)) {
                $success = false;
                $message = "{$this->scanInfo->getScanTypeName()} scan file {$scanFile} does not exist.";
            }
        }

        if ($success) {

            $device = Device::find($this->scanInfo->getDeviceId());
            $device->scanning = 1;
            $device->save();
            unset($device);

            require_once($cswapiRoot . '/networking/class/ScanTable.php');
            require_once($cswapiRoot . '/networking/class/Scan.php');
            require_once($cswapiRoot . '/networking/class/DeviceAlarmTable.php');

            \DeviceAlarmTable::isAManualScan();

            global $theScan;

            $theScan = \ScanTable::GetScanById($this->scanInfo->getScanId());
            $theScan->scanning = 1;
            $theScan->save();

            $_REQUEST['id'] = $this->scanInfo->getNodeId();

            $argvSave = null;

            // adjust the args to give a reasonable file name in /noc_logs

            if (isset($_SERVER['argv'])) {
                $argvSave = $_SERVER['argv'];
            }

            $_SERVER['argv'] = [
                'node-' . $this->scanInfo->getNodeId(),
                'device-' . $this->scanInfo->getDeviceId(),
                'scan-' . $this->scanInfo->getScanId(),
            ];

            // this echos out progress info for the Flash UI.
            // we ignore this and get progress from the database.
            // require_once $scanFile;
            require $scanFile;

            if ($argvSave !== null) {
                $_SERVER['argv'] = $argvSave;
            }
        }

        $scan = DeviceScan::find($this->scanInfo->getScanId());

        // we need to update the table if we found a problem or if it somehow thinks it is still scanning.
        if (!$success || $scan->scanning != 0) {
            $scan->scanning = 0;

            if (!$success) {
                // we found a problem
                $scan->success = 0;
                $scan->message = $message;
                $scan->end_timestamp = new \DateTime();
            }

            $scan->save();
        }

        $device = Device::find($this->scanInfo->getDeviceId());

        if ($device->scanning != 0) {
            $device->scanning = 0;
            $device->save();
        }
    }
}
