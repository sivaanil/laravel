<?php

// brings cswapi database from 7.3.1.1 to 7.3.2
// (SiteGate 2.6 to SiteGate 2.7 part 2)

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportSiteportal732Changes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // SiteGate only!
        if (env('C2_SERVER_TYPE') == 'sitegate') {
            // R7.3.2 - B3028
            DB::unprepared(<<<RAWSQL

-- Start add device type for spiderCloud Tareq 
REPLACE INTO css_networking_device_type (id,class_id,vendor,model,auto_build_enabled, uses_snmp, can_add_children, main_device,uses_default_value,support_traps,has_web_interface,build_file,scan_file) 
VALUES
(1609,2,'SpiderCloud','SpiderCloud Receiver',1,0,0,1,0,1,0,'trapReceiver_builder.php','trap_receiver_launcher.php');
-- End add device type for spiderCloud Tareq  


-- Start add SNMP port for spiderCloud Tareq  
REPLACE INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1609,'snmp','SNMP',162);
-- End add SNMP port for spiderCloud Tareq  
RAWSQL
            );
            // R7.3.2 - B6462
            DB::unprepared(<<<RAWSQL
ALTER TABLE css_networking_chronic_alarms MODIFY description VARCHAR(256);
RAWSQL
            );
            // R7.3.2 - B7493
            DB::unprepared(<<<RAWSQL
-- DB Changes for Bugs 7493 and 7498. Adding and changing prop defs for Digi Transport devices. 
-- Getting more cellular signal properties, and the system and mobile temperature.

REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt) 
VALUES (2, NULL, 1129, 1, '0', NULL, 'rsrp', 'RSRP (dBm)', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '1', NULL, NULL, '0');
REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt) 
VALUES (2, NULL, 1129, 1, '0', NULL, 'rsrq', 'RSRQ (dB)', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '1', NULL, NULL, '0');
REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt) 
VALUES (2, NULL, 1129, 1, '0', NULL, 'sinr', 'SINR (dB)', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '1', NULL, NULL, '0');

DELETE
FROM css_networking_device_prop_def WHERE device_type_id = 1129 AND variable_name = "temp" AND name = "Temperature (C)";
DELETE
FROM css_networking_device_prop_def WHERE device_type_id = 1129 AND variable_name = "rssi";
DELETE
FROM css_networking_device_prop_def WHERE device_type_id = 1129 AND variable_name = "rssiEVDO" AND name = "RSSI";

REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt) 
VALUES (2, NULL, 1129, 1, '0', '', 'coreTemp', 'System Temperature (C)', 'DECIMAL', '0', '1', '0', '0', '0', '0', '0', '4', '4', '0', '0', '1', '', NULL, '0');
REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt) 
VALUES (2, NULL, 1129, 1, '0', '', 'moTemp', 'Mobile Temperature (C)', 'DECIMAL', '0', '1', '0', '0', '0', '0', '0', '4', '4', '0', '0', '1', '', NULL, '0');


UPDATE css_networking_device_prop_def SET variable_name = "rssi", name = "RSSI (dBm)" WHERE device_type_id = 1129 AND variable_name = "rssiLTE" AND name = "RSSI LTE";
UPDATE css_networking_device_prop_def SET name = "Signal Strength EVDO (dBm)" WHERE device_type_id = 1129 AND variable_name = "rssi1EVDO" AND name = "Signal Strength EVDO";
UPDATE css_networking_device_prop_def SET name = "Signal Strength xRTT (dBm)" WHERE device_type_id = 1129 AND variable_name = "rssi1xRTT" AND name = "Signal Strength xRTT";
UPDATE css_networking_device_prop_def SET name = "Signal Strength 1xRtt (dBm)" WHERE device_type_id = 1129 AND variable_name = "rssiXRtt" AND name = "Signal Strength 1xRtt";


RAWSQL
            );
            // R7.3.2 - B7572
            DB::unprepared(<<<RAWSQL
SET foreign_key_checks = 0;
REPLACE INTO css_networking_device_class (id,description,is_license) VALUES (1153,'Small Cell',1);
REPLACE INTO css_networking_device_type (id,class_id,vendor,model,auto_build_enabled,uses_snmp,snmp_only,main_device,uses_default_value,has_web_interface,build_file,scan_file) VALUES (1608,1153,'Samsung','Femto',1,0,1,1,0,0,'femto_builder.php','femto_scanner.php');
SET foreign_key_checks = 1;
RAWSQL
            );
            // R7.3.2 - B7612
            DB::unprepared(<<<RAWSQL
-- Update device type id for Cisco ASA 5500 series trap handler
UPDATE css_networking_device_port_def 
SET device_type_id = '2325'
WHERE device_type_id = '5052'AND variable_name = 'snmp'AND name = 'SNMP'AND default_port = 161;

-- Update device type id for Cisco ASA 5500 series trap handler
UPDATE css_networking_device_type 
SET id = 2325
WHERE id = 5052 AND class_id = 1129 AND vendor = 'Cisco' AND model = 'ASA 5500 Series Trap Handler' AND 
auto_build_enabled = 1 AND uses_snmp = 1 AND snmp_only = 1 AND can_add_children = 0 AND can_disable = 0 AND defaultSNMPVer = '2c' AND defaultSNMPRead = 'public' AND 
defaultSNMPWrite = 'public' AND main_device = 1 AND general_device_id = 0 AND uses_default_value = 1 AND build_file = 'Cisco_ASA_Trap_Handler_Builder_Launcher.php' AND 
scan_file = 'trap_receiver_launcher.php' AND SNMPauthEncryption = '' AND SNMPprivEncryption = '' AND SNMPauthType = '' AND support_traps = 1 AND has_web_interface = 0;

RAWSQL
            );

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // imported from SitePortal - not currently implemented
    }
}
