<?php

namespace Unified\Services\SitePortalAPI;

use DB;

/**
 * Returns the data for a prop def scan from SitePortal
 *
 * @author ross.keatinge
 */
class PropDefScan
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

        // This is a somewhat weird query to match what all versions of SitePortal expects
        // It includes only one row for each unique prop_def that has changed
        // It includes node_id which we don't really need but SitePortal expects

        $sql = 'SELECT tmp.node_id, dpd2.*, @@system_time_zone as timeZone,
                unix_timestamp(dpd2.date_updated) AS unix_timestamp
                FROM
                (
                SELECT dpd.id, MIN(nt.id) as node_id
                FROM css_networking_network_tree_map ntm
                INNER JOIN css_networking_network_tree nt on nt.id = ntm.node_id
                INNER JOIN css_networking_device d on d.id = nt.device_id
                INNER JOIN css_networking_device_prop_def dpd on dpd.device_type_id = d.type_id
                WHERE ntm.node_map LIKE :nodeIdLike
                AND ntm.deleted = 0 AND ntm.build_in_progress = 0
                AND dpd.date_updated >= FROM_UNIXTIME(:fromTimestamp)
                AND dpd.name != \'SHOULD NOT\' AND dpd.name != \'ATTENTION\'
                GROUP BY dpd.id
                ) as tmp
                INNER JOIN css_networking_device_prop_def dpd2 ON dpd2.id = tmp.id
                ORDER BY dpd2.date_updated, dpd2.id LIMIT :limit';

        /* This is the old query which returns way to much duplicated data
          $sql = 'SELECT nt.id AS node_id, dpd.*, @@system_time_zone as timeZone,
          unix_timestamp(dpd.date_updated) AS unix_timestamp
          FROM css_networking_network_tree_map ntm
          INNER JOIN css_networking_network_tree nt on nt.id = ntm.node_id
          INNER JOIN css_networking_device d on d.id = nt.device_id
          INNER JOIN css_networking_device_prop_def dpd on dpd.device_type_id = d.type_id
          WHERE ntm.node_map LIKE :nodeIdLike
          AND ntm.deleted = 0 AND ntm.build_in_progress = 0
          AND dpd.date_updated >= FROM_UNIXTIME(:fromTimestamp)
          AND dpd.name != \'SHOULD NOT\' AND dpd.name != \'ATTENTION\'
          ORDER BY dpd.date_updated, dpd.id LIMIT :limit';
         */

        $propDefs = DB::select($sql, [
                    'nodeIdLike' => '%.' . $nodeId . '.%',
                    'fromTimestamp' => $fromTimestamp,
                    'limit' => $limit
        ]);

        // get timestamp of last prop
        if (count($propDefs) > 0) {
            $newTimestamp = $propDefs[count($propDefs) - 1]->unix_timestamp;
        } else {
            $newTimestamp = $fromTimestamp;
        }

        return [
            'timestamp' => $newTimestamp,
            'propDefs' => $propDefs
        ];
    }

}
