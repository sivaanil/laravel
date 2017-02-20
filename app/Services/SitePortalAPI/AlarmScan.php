<?php

namespace Unified\Services\SitePortalAPI;
use Unified\Models\NetworkTreeMap;

use DB;

/**
 * Returns the data for an Alarm scan from SitePortal
 *
 * @author ross.keatinge
 */
class AlarmScan
{

    const MAX_LIMIT = 1000;

    public function handle($nodeId, $fromTimestamp, $limit)
    {
        if ($nodeId === null) {
            return null;
        }

        if (!is_numeric($fromTimestamp)) {
            return null;
        }

        if (!is_numeric($limit) || ((int) $limit) > self::MAX_LIMIT) {
            $limit = self::MAX_LIMIT;
        }

        $alarms = NetworkTreeMap::getAlarmsFromTimestamp($nodeId, $fromTimestamp, $limit);
        // get timestamp of last alarm
        if (count($alarms) > 0) {
            $newTimestamp = $alarms[count($alarms) - 1]->sequence;
        } else {
            $newTimestamp = $fromTimestamp;
        }

        return [
            'timestamp' => $newTimestamp,
            'alarms' => $alarms,
            'acknowledged' => [],
            'ignored' => [],
        ];
    }

}
