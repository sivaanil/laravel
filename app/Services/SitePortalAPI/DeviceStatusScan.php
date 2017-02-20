<?php

namespace Unified\Services\SitePortalAPI;

use DB;
use Unified\Models\NetworkTreeMap;

/**
 * Returns device status data to SitePortal. Called after an alarm or prop scans.
 *
 * @author ross.keatinge
 */
class DeviceStatusScan
{

    public function handle($nodeId)
    {
        if ($nodeId === null) {
            return null;
        }
        return NetworkTreeMap::scanDevices($nodeId);
    }

}
