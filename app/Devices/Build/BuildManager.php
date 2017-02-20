<?php

namespace Unified\Devices\Build;

use DB;
use \Carbon\Carbon;
use Unified\Models\DeviceBuild;

/**
 * Use this for information and control after a build has been started by DeviceBuilder.
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class BuildManager
{

    /**
     * Call this frequency during a build
     * @param string $deviceToken
     * @return \Unified\Devices\Build\BuildProgress
     */
    public function getBuildProgress($deviceToken)
    {
        $qResult = DeviceBuild::where('device_token', $deviceToken)
                ->select('building', 'progress', 'user_cancel', 'message', 'process_id', 'node_id')
                ->first();

        if ($qResult !== null) {

            if ($qResult->building === '1') {
                $status = BuildProgress::STATUS_BUILDING;
            } else {
                if ($qResult->user_cancel === 'YES') {
                    $status = BuildProgress::STATUS_CANCELED;
                } else if ($qResult->node_id !== null) {
                    // DeviceBuilder sets node_id on successful build.
                    $status = BuildProgress::STATUS_COMPLETE;
                } else {
                    $status = BuildProgress::STATUS_FAILED;
                }
            }

            $message = $qResult->message;

            // remove log=<big long file name> from the final message.
            $logIdx = strpos($message, 'log=');
            if ($logIdx !== false) {
                $message = trim(substr($message, 0, $logIdx));
            }

            $progress = new BuildProgress($status, $qResult->progress, $message, $qResult->process_id, $qResult->node_id);
        } else {
            $progress = new BuildProgress(BuildProgress::STATUS_NOT_FOUND);
        }

        return $progress;
    }

    /**
     * This will cancel the build next time the builder calls the BuildProgress()function.
     * @param type $deviceToken
     */
    public function CancelBuild($deviceToken)
    {
        DeviceBuild::where('device_token', $deviceToken)
                ->update([
                    'building' => 0,
                    'user_cancel' => 'YES',
                    'end_timestamp' => Carbon::now()
        ]);
    }

}
