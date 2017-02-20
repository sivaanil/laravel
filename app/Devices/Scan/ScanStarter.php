<?php

namespace Unified\Devices\Scan;

use Carbon\Carbon;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Unified\Jobs\ScanDeviceJob;
use Unified\Models\DeviceScan;
use Unified\Models\NetworkTree;

/**
 * Description of ScanStarter
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class ScanStarter
{

    use DispatchesJobs;

    // this text is used as a marker by ScanManager
    const STARTING_MESSAGE = 'Starting scan';

    /**
     * Start a new scan or return ScanInfo for existing scan.
     *
     * @param int $nodeId
     * @param string $scanType
     * @return ScanInfo
     * @throws ScanDeviceException
     */
    public function StartScan($nodeId, $scanType)
    {
        $treeResult = NetworkTree::getScanStatus($nodeId);
/*        $treeResult = DB::table('css_networking_network_tree as nt')
                ->select('nt.device_id', 'd.scanning')
                ->join('css_networking_device as d', 'nt.device_id', '=', 'd.id')
                ->where('nt.id', $nodeId)
                ->first();
*/

        if ($treeResult === null) {
            throw new ScanDeviceException("Device not found for node {$nodeId}");
        }

        $scanId = null;

        // are we already scanning? (probably from a cron job)
        if ($treeResult->scanning == 1) {

            $scanResult = DeviceScan::isScanning($treeResult->device_id);

/*            $scanResult = DB::table('css_networking_scan')
                    ->select('id')
                    ->where('device_id', '=', $treeResult->device_id)
                    ->where('scanning', '=', 1)
                    ->whereNull('end_timestamp')
                    ->orderBy('id', 'DESC')
                    ->first();
*/
            // if we don't find this then it suggests that the device is not really scanning, despite what the device.scanning flag says.
            if ($scanResult !== null) {
                $scanId = $scanResult->id;
            }
        }

        if ($scanId === null) {

            $scan = new DeviceScan();
            $scan->user_id = 1;
            $scan->device_id = $treeResult->device_id;
            $scan->start_timestamp = Carbon::now();
            $scan->progress = 0;
            $scan->scanning = 0;
            $scan->process_id = getmypid();
            $scan->message = self::STARTING_MESSAGE;
            $scan->save();

            $scanInfo = new ScanInfo($nodeId, $treeResult->device_id, $scan->id, $scanType, false);

            $this->dispatch(new ScanDeviceJob($scanInfo));
        }
        else {
            // device was already scanning.
            // return this so we can show progress for the current scan.
            // this might not be the same scan type (alarm or prop) that was requested.
            $scanInfo = new ScanInfo($nodeId, $treeResult->device_id, $scanId, $scanType, true);
        }

        return $scanInfo;
    }

}
