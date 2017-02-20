<?php

namespace Unified\Devices\Rebuild;

use Carbon\Carbon;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Unified\Jobs\RebuildDeviceJob;
use Unified\Models\DeviceRebuild;
use Unified\Models\NetworkTree;

/**
 * Description of ScanStarter
 *
 * @author Franz Honer <franz.honer@csquaredsystems.com>
 */
class RebuildStarter
{

    use DispatchesJobs;

    // this text is used as a marker by RebuildManager
    const STARTING_MESSAGE = 'Starting rebuild';

    /**
     * Start a new rebuild or return RebuildInfo for existing rebuild.
     *
     * @param int $nodeId
     * @return RebuildInfo
     * @throws RebuildDeviceException
     */
    public function StartRebuild($nodeId)
    {
        $treeResult = NetworkTree::getScanStatus($nodeId);

        if ($treeResult === null) {
            throw new RebuildDeviceException("Device not found for node {$nodeId}");
        }

        $rebuildId = null;

        // TODO: are we already rebuilding? 
        // not checking this currently because there is currently no mechanism to clear stuck rebuilds

        if ($rebuildId === null) {

            $rebuild = new DeviceRebuild();
            $rebuild->device_id = $treeResult->device_id;
            $rebuild->start_timestamp = Carbon::now();
            $rebuild->message = self::STARTING_MESSAGE;
            $rebuild->save();

            $rebuildInfo = new RebuildInfo($nodeId, $rebuild->device_id, $rebuild->id);

            $this->dispatch(new RebuildDeviceJob($rebuildInfo));
        }
        else {
            // device was already rebuilding
            // return this so we can show progress for current rebuild
        }

        return $rebuildInfo;
    }

}
