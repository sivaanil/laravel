<?php

namespace Unified\Devices\Rebuild;

use DB;
use \Carbon\Carbon;
use Unified\Models\DeviceRebuild;

/**
 * Use this for information and control after a build has been started by DeviceBuilder.
 *
 * @author Franz Honer <franz.honer@csquaredsystems.com>
 */
class RebuildManager
{

    /**
     * Call this frequency during a rebuild
     * @param string $deviceToken
     * @return \Unified\Devices\Build\RebuildProgress
     */
    public function getRebuildProgress($rebuildId)
    {
        $qResult = DeviceRebuild::where('id', $rebuildId)
                ->select('message', 'success')
                ->first();

        if ($qResult !== null) {

            if ($qResult->success === '1') {
                $status = RebuildProgress::STATUS_COMPLETE;
            } else {
                $status = RebuildProgress::STATUS_REBUILDING;
            }

            $message = $qResult->message;
            
            $progress = new RebuildProgress($status, $message);
        } else {
            $progress = new RebuildProgress(RebuildProgress::STATUS_NOT_FOUND);
        }

        return $progress;
    }

}
