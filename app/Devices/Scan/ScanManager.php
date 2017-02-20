<?php

namespace Unified\Devices\Scan;

use DB;
use Unified\Models\DeviceScan;

/**
 * Description of ScanManager
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class ScanManager
{

    public function getScanProgress($scanId)
    {
        $scan = new DeviceScan();
        $scan->id = $scanId;
        $qResult = $scan->getProgress($scanId);

     /*   $qResult = DB::table('css_networking_scan')
                ->select('scanning', 'progress', 'success', 'message', 'process_id')
                ->where('id', $scanId)
                ->first();
*/
        if ($qResult !== null) {
            $message = $qResult->message;

            if ($qResult->scanning === '1') {
                $status = ScanProgress::STATUS_SCANNING;
            } else {

                // remove log=<big long file name> from the final message.
                $logIdx = strpos($message, 'log=');
                if ($logIdx !== false) {
                    $message = trim(substr($message, 0, $logIdx));
                }

                if ($message === ScanStarter::STARTING_MESSAGE) {
                    $status = ScanProgress::STATUS_STARTING;
                } else if ($qResult->success === '1') {
                    $status = ScanProgress::STATUS_COMPLETE;
                } else {
                    $status = ScanProgress::STATUS_FAILED;
                }
            }

            $progress = new ScanProgress($status, $qResult->progress, $message, $qResult->process_id);
        } else {
            $progress = new ScanProgress(ScanProgress::STATUS_NOT_FOUND);
        }

        return $progress;
    }

    /**
     * This will cancel the scan next time the scanner calls the ScanProgress()function.
     * More accurately, this really "fails" the scan rather can cancels it because the scan table doesn't have
     * a user_cancel column like the build table.
     *
     * @param int $deviceId
     */
    public function CancelScan($scanId)
    {
        DeviceScan::where('id', $scanId)
                ->update([
                    'scanning' => 0
        ]);
    }

}
