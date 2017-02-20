<?php

namespace Unified\Services\SitePortalAPI;

use DB;

/**
 * Returns the data for a prop scan from SitePortal
 *
 * @author ross.keatinge
 */
class PropScan
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

        $sql = 'SELECT nt.id AS node_id, dpd.name, dpd.variable_name, dpd.device_type_id, dp.*, @@system_time_zone as timeZone,
                unix_timestamp(dp.date_updated) AS unix_timestamp
                FROM css_networking_network_tree_map ntm
                INNER JOIN css_networking_network_tree nt ON nt.id = ntm.node_id
                INNER JOIN css_networking_device d ON d.id = nt.device_id
                INNER JOIN css_networking_device_prop dp ON dp.device_id = d.id
                INNER JOIN css_networking_device_prop_def dpd ON dpd.id = dp.prop_def_id
                WHERE ntm.node_map LIKE :nodeIdLike AND ntm.deleted = 0 AND ntm.build_in_progress = 0
                AND dp.date_updated >= FROM_UNIXTIME(:fromTimestamp)
                ORDER BY dp.date_updated, dp.id LIMIT :limit';

        $props = DB::select($sql, [
                    'nodeIdLike' => '%.' . $nodeId . '.%',
                    'fromTimestamp' => $fromTimestamp,
                    'limit' => $limit
        ]);

        // get timestamp of last prop
        if (count($props) > 0) {
            $newTimestamp = $props[count($props) - 1]->unix_timestamp;
        } else {
            $newTimestamp = $fromTimestamp;
        }

        return [
            'timestamp' => $newTimestamp,
            'props' => $props
        ];
    }

}
