<?php

namespace Unified\Services\SitePortalAPI;

use DB;

/**
 * Returns the data for an for SitePortal to build a SiteGate
 *
 * @author ross.keatinge
 */
class BuildServer
{

    public function handle($nodeId)
    {
        if ($nodeId === null) {
            return null;
        }

        // Inject the device encryption key into the database.
        // Use include rather than require so that it doesn't completely fail if we don't have a working cswapi installation.
        // This might be useful for testing.
        @include_once ENV('CSWAPI_ROOT') . '/common/class/cssEncryption.php';

        if (class_exists('\cssEncryption')) {
            DB::statement('SET @css_encryption_key = :key', ['key' => \cssEncryption::getInstance()->getKey()]);
        }

        // We need to support SitePortal clients that do and don't support encryption.
        // The builder on SitePortal uses the Device object which will encrypt if it is a newer version. That means we need to avoid
        // double encryption on new SitePortals as well as make the response intelligible to older SitePortals.
        // The easiest way to achieve compatibility is to decrypt here and send the credentials in plain text.
        // Communications is via SSL so this is safe.
        // We might also need to support the situation where this code is critically applied to an older SiteGate
        // which has an older cswapi which does not do encryprtion.
        // We therefore only attempt to decrypt here if we have that ability.
        // The SQL unfortunately includes all columns of the device table SELECTing d.*. Rather than expand that to every column, excluding
        // those that we want to decrypt, we add the decrypted columns later. When MySQL / PDO sees multiple columns with the same name in
        // a result set, the last one "wins" and replaces earlier columns.
        // cssEncryption is required by doctrine.php on cswapi >= 7.2
        // $decryptSQL is our own hard coded safe text so it's safe to insert into a SQL statement like this.

        $sql = 'SELECT d.*, dt.id, dt.class_id, nt.id AS node_id, nt.parent_node_id,
            coalesce(parent_group.name, parent_dev.name) AS parent_node_name, ntm.visible, @@system_time_zone AS timeZone, \'device\' AS classification,
            css_decrypt(d.read_community) AS read_community, css_decrypt(d.write_community) AS write_community,
            css_decrypt(d.password) AS password, css_decrypt(d.SNMPauthPassword) AS SNMPauthPassword, css_decrypt(d.SNMPprivPassword) AS SNMPprivPassword
            FROM css_networking_device AS d
            INNER JOIN css_networking_device_type AS dt ON dt.id = d.type_id
            INNER JOIN css_networking_network_tree AS nt ON nt.device_id = d.id
            INNER JOIN css_networking_network_tree_map AS ntm ON ntm.node_id = nt.id
            INNER JOIN css_networking_network_tree AS parent ON parent.id = nt.parent_node_id
            LEFT JOIN css_networking_device parent_dev ON parent_dev.id = parent.device_id
            LEFT JOIN css_networking_group parent_group ON parent_group.id = parent.group_id
            WHERE ntm.node_map LIKE :nodeIdLike AND ntm.deleted = 0 AND ntm.build_in_progress = 0
            ORDER BY nt.id';

        $nodeIdLike = '%.' . $nodeId . '.%';
        $devices = DB::select($sql, ['nodeIdLike' => $nodeIdLike]);

        $sql = 'SELECT nt.id as node_id, dp.id, dpd.default_port AS defaultPort, dp.port AS newPort,
            dp.port_def_id AS portDefId, dpd.name as portName, dpd.variable_name as portVarName, @@system_time_zone as timeZone
            FROM css_networking_device_port dp
            INNER JOIN css_networking_device_port_def dpd ON dp.port_def_id = dpd.id
            INNER JOIN css_networking_network_tree AS nt ON nt.device_id = dp.device_id
            INNER JOIN css_networking_network_tree_map AS ntm ON ntm.node_id = nt.id
            WHERE ntm.node_map LIKE :nodeIdLike AND ntm.deleted = 0 AND ntm.build_in_progress = 0';

        $ports = DB::select($sql, ['nodeIdLike' => $nodeIdLike]);

        $sql = 'SELECT max(da.id) AS max_id FROM css_networking_device_alarm da
            INNER JOIN css_networking_network_tree t ON t.device_id = da.device_id
            INNER JOIN css_networking_network_tree_map tm ON tm.node_id = t.id
            WHERE tm.node_map LIKE :nodeIdLike AND tm.deleted = 0 AND tm.build_in_progress = 0';

        $alarmInfo = DB::select($sql, ['nodeIdLike' => $nodeIdLike]);

        return [
            'timestamp' => time(),
            'inventory' => $devices,
            'ports' => $ports,
            'max_cleared_alarm_id' => $alarmInfo[0]->max_id
        ];
    }

}
