<?php

// brings cswapi database from 7.3.2 to 7.3.3
// (SiteGate 2.7 to SiteGate 2.8)

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportSiteportal733Changes extends Migration
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
            // R7.3.2.1 - B7385
            DB::unprepared(<<<RAWSQL
            -- --------------------------------------------------------------------------------------
-- Bug 7385 - Devices - set device SNMP traps enabled by default
-- --------------------------------------------------------------------------------------
            alter table css_networking_device change trap_enabled trap_enabled int(11) default 1;
-- --------------------------------------------------------------------------------------
-- End of script
            -- --------------------------------------------------------------------------------------
RAWSQL
            );
                // R7.3.3 - 7809
            DB::unprepared(<<<RAWSQL
-- Bug 7809. Mike Zhukovskiy
-- Add a dummy alarm scanner to devices that need to have one

UPDATE css_networking_device_type
SET scan_file = "cisco_dummy_alarm_scanner_launcher.php",
    prop_scan_file = "cisco_catalyst_6500_scanner_launcher.php"
WHERE id = 1440;

UPDATE css_networking_device_type
SET scan_file = "cisco_dummy_alarm_scanner_launcher.php",
    prop_scan_file = "cisco_ASR_1000_scanner_launcher.php"
WHERE id = 1451;

UPDATE css_networking_device_type
SET scan_file = "cisco_dummy_alarm_scanner_launcher.php",
    prop_scan_file = "cisco_ASA_scanner_launcher.php"
WHERE id  = 1466;
    
UPDATE css_networking_device_type
SET scan_file = "cisco_dummy_alarm_scanner_launcher.php",
prop_scan_file = "cisco_wlan_controller_scanner_launcher.php"
WHERE id = 1468;

UPDATE css_networking_device_type
SET scan_file = "cisco_dummy_alarm_scanner_launcher.php",
prop_scan_file = "cisco_UCS_scanner_launcher.php"
WHERE id = 1470;

UPDATE css_networking_device_type
SET scan_file = "cisco_dummy_alarm_scanner_launcher.php",
prop_scan_file = "cisco_CNR_scanner_launcher.php"
WHERE id = 1471;

-- Ericsson SE600 full driver
UPDATE css_networking_device_type
SET scan_file = "trap_receiver_launcher.php",
prop_scan_file = "ericsson_se600_prop_launcher.php"
WHERE id = 5025;

-- Ericsson SE600 trap receiver 
UPDATE css_networking_device_type
SET scan_file = "trap_receiver_launcher.php",
prop_scan_file = NULL
WHERE id = 5035;
-- Bug 7809. Mike Zhukovskiy

RAWSQL
            );
            // R7.3.3 - B3411
            DB::unprepared(<<<RAWSQL
-- -----------------------------------------------------------------------------------------------------------------------------------------------------
-- Bug 3411 - Queued Devices - alert for queuing a device should be acknowledgeable
-- -----------------------------------------------------------------------------------------------------------------------------------------------------
UPDATE css_alarms_dictionary SET can_acknowledge='1' WHERE (alarm_description = 'The Device is queued for build' AND severity_id = '6');
-- -----------------------------------------------------------------------------------------------------------------------------------------------------
-- End of Script
-- -----------------------------------------------------------------------------------------------------------------------------------------------------
RAWSQL
            );
            // R7.3.3 - B5226
            DB::unprepared(<<<RAWSQL
UPDATE css_authentication_user
SET role_id = 2, role = 'Administrator'
WHERE role_id = 1 AND role = 'System Administrator' AND id <> 5;

INSERT INTO css_authentication_user_role
(inherit, node_id, user_id, role_id, date_updated)
SELECT '0' AS inherit, u.home_node_id AS node_id, u.id AS user_id, u.role_id, NOW() AS date_updated
FROM css_authentication_user u
LEFT JOIN css_authentication_user_role ur ON (u.id = ur.user_id)
WHERE ur.user_id IS NULL;
RAWSQL
            );
            // R7.3.3 - B5561
            DB::unprepared(<<<RAWSQL

  -- START update prop_def for ON/OFF statuses in Bard MC4000 Tareq Alwrekat 03/16/16
     UPDATE css_networking_device_prop_def SET data_type='INTEGER', graph_type=2  WHERE (variable_name IN ('Blower (G) Sysetm 1','Blower (G) Sysetm 2','Cool Stg 1 (Y1) S1','Cool Stg 1 (Y1) S2','Cool Stg 2 (Y2) S1','Cool Stg 2 (Y2) S2','Heater (W) Sysetm 1','Heater (W) Sysetm 2','Economizer Sysetm 1','Economizer Sysetm 2','Cool Stage 1','Cool Stage 2','Cool Stage 3','Cool Stage 4','Heat Stage 1','Heat Stage 2','Heat Stage 3','Heat Stage 4','DC Fan','Dehumidifier')) AND (device_type_id IN (1428));
  -- END update prop_def for ON/OFF statuses in Bard MC4000 Tareq Alwrekat 03/16/16
 
 -- START add prop_ops for ON/OFF Bard MC4000 Tareq Alwrekat 03/16/16
     REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text,graph_value) SELECT id,'0','OFF','0' FROM css_networking_device_prop_def WHERE (variable_name IN ('Blower (G) Sysetm 1','Blower (G) Sysetm 2','Cool Stg 1 (Y1) S1','Cool Stg 1 (Y1) S2','Cool Stg 2 (Y2) S1','Cool Stg 2 (Y2) S2','Heater (W) Sysetm 1','Heater (W) Sysetm 2','Economizer Sysetm 1','Economizer Sysetm 2','Cool Stage 1','Cool Stage 2','Cool Stage 3','Cool Stage 4','Heat Stage 1','Heat Stage 2','Heat Stage 3','Heat Stage 4','DC Fan','Dehumidifier')) AND (device_type_id IN (1428));
     REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text,graph_value) SELECT id,'1','ON','1' FROM css_networking_device_prop_def WHERE (variable_name IN ('Blower (G) Sysetm 1','Blower (G) Sysetm 2','Cool Stg 1 (Y1) S1','Cool Stg 1 (Y1) S2','Cool Stg 2 (Y2) S1','Cool Stg 2 (Y2) S2','Heater (W) Sysetm 1','Heater (W) Sysetm 2','Economizer Sysetm 1','Economizer Sysetm 2','Cool Stage 1','Cool Stage 2','Cool Stage 3','Cool Stage 4','Heat Stage 1','Heat Stage 2','Heat Stage 3','Heat Stage 4','DC Fan','Dehumidifier')) AND (device_type_id IN (1428));
-- END add prop_ops for ON/OFF Bard MC4000 Tareq Alwrekat 03/16/16

RAWSQL
            );
            // R7.3.3 - B5857
            DB::unprepared(<<<RAWSQL
-- Bug 5023, Refactor Power Report data collection

-- Two different kinds of devices write to this table: RTUs and power plants. 
-- Business logic dictates that sometimes they write to the same row.  For this case, we are keeping separate timestamps.
-- Sometimes a row will only be written by one, but not both devices. Allowing NULL values for that possibility.

-- Drop the date_changed field if it exists
SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = 'data_power_rtu'
        AND table_schema = DATABASE()
        AND column_name = 'date_changed'
    ) > 0,
    "ALTER TABLE data_power_rtu DROP date_changed;",    
    "SELECT 1"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


-- Add the rtu timestamp field
SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = 'data_power_rtu'
        AND table_schema = DATABASE()
        AND column_name = 'rtu_timestamp'
    ) > 0,
    "SELECT 1",
    "ALTER TABLE data_power_rtu ADD COLUMN rtu_timestamp TIMESTAMP NULL;"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add the power plant timestamp field
SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = 'data_power_rtu'
        AND table_schema = DATABASE()
        AND column_name = 'power_plant_timestamp'
    ) > 0,
    "SELECT 1",
    "ALTER TABLE data_power_rtu ADD COLUMN power_plant_timestamp TIMESTAMP NULL;"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

RAWSQL
            );
            // R7.3.3 - B6306
            DB::unprepared(<<<RAWSQL
-- START
REPLACE INTO css_networking_device_type (`id`, `class_id`, `vendor`, `model`, `auto_build_enabled`, `uses_snmp`, `snmp_only`, `can_add_children`, `can_disable`, `defaultWebUi`, `defaultWebUiUser`, `defaultWebUiPw`, `defaultSNMPVer`, `defaultSNMPRead`, `defaultSNMPWrite`, `date_updated`, `main_device`, `general_device_id`, `node_type`, `uses_default_value`, `build_file`, `scan_file`, `prop_scan_file`, `controller_file`, `SNMPuserName`, `SNMPauthPassword`, `SNMPauthEncryption`, `SNMPprivPassword`, `SNMPprivEncryption`, `SNMPauthType`, `rebuilder_file`, `support_traps`, `has_web_interface`, `canvas_pref_top`, `canvas_pref_bottom`, `canvas_default_top`, `canvas_default_bottom`, `canvas_list`, `development_flag`, `auto_detect_flag`, `heartbeat_threshold_enabled`)
VALUES ('2400', '10', 'SYM', 'SPOI', '1', '1', '0', '1', '0', NULL, NULL, NULL, '2c', '8bf79860eef9f6beea23994dcfa890aa3c6ade7fe5d8ab4bafb295ccb918b43b', 'e8142df3c5713e83cb98ddda28f174ea0dbc0c586962ebb913bf6ce158b26c0c', '2016-04-13 14:56:06', '1', '0', NULL, '1', 'genericDeviceBuilder.php', 'genericScannerLauncher.php', NULL, NULL, NULL, NULL, 'SHA', NULL, 'AES', 'authPriv', NULL, '0', '1', NULL, NULL, '0', '0', 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Location and Access Info,Log,Map,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', '0', '0', '0');

REPLACE INTO css_networking_device_type (`id`, `class_id`, `vendor`, `model`, `auto_build_enabled`, `uses_snmp`, `snmp_only`, `can_add_children`, `can_disable`, `defaultWebUi`, `defaultWebUiUser`, `defaultWebUiPw`, `defaultSNMPVer`, `defaultSNMPRead`, `defaultSNMPWrite`, `date_updated`, `main_device`, `general_device_id`, `node_type`, `uses_default_value`, `build_file`, `scan_file`, `prop_scan_file`, `controller_file`, `SNMPuserName`, `SNMPauthPassword`, `SNMPauthEncryption`, `SNMPprivPassword`, `SNMPprivEncryption`, `SNMPauthType`, `rebuilder_file`, `support_traps`, `has_web_interface`, `canvas_pref_top`, `canvas_pref_bottom`, `canvas_default_top`, `canvas_default_bottom`, `canvas_list`, `development_flag`, `auto_detect_flag`, `heartbeat_threshold_enabled`) 
VALUES ('2401', '11', 'SYM', 'Note', '0', '1', '0', '0', '0', NULL, NULL, NULL, '2c', '8bf79860eef9f6beea23994dcfa890aa3c6ade7fe5d8ab4bafb295ccb918b43b', 'e8142df3c5713e83cb98ddda28f174ea0dbc0c586962ebb913bf6ce158b26c0c', '2016-04-13 14:56:16', '0', '0', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, 'SHA', NULL, 'AES', 'authPriv', NULL, '0', '1', NULL, NULL, '0', '0', 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Location and Access Info,Log,Map,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', '0', '0', '0');

REPLACE INTO css_networking_device_type (`id`, `class_id`, `vendor`, `model`, `auto_build_enabled`, `uses_snmp`, `snmp_only`, `can_add_children`, `can_disable`, `defaultWebUi`, `defaultWebUiUser`, `defaultWebUiPw`, `defaultSNMPVer`, `defaultSNMPRead`, `defaultSNMPWrite`, `date_updated`, `main_device`, `general_device_id`, `node_type`, `uses_default_value`, `build_file`, `scan_file`, `prop_scan_file`, `controller_file`, `SNMPuserName`, `SNMPauthPassword`, `SNMPauthEncryption`, `SNMPprivPassword`, `SNMPprivEncryption`, `SNMPauthType`, `rebuilder_file`, `support_traps`, `has_web_interface`, `canvas_pref_top`, `canvas_pref_bottom`, `canvas_default_top`, `canvas_default_bottom`, `canvas_list`, `development_flag`, `auto_detect_flag`, `heartbeat_threshold_enabled`) 
VALUES ('2402', '1004', 'SYM', 'Slot', '0', '1', '0', '0', '0', NULL, NULL, NULL, '2c', '8bf79860eef9f6beea23994dcfa890aa3c6ade7fe5d8ab4bafb295ccb918b43b', 'e8142df3c5713e83cb98ddda28f174ea0dbc0c586962ebb913bf6ce158b26c0c', '2016-04-14 11:07:23', '0', '0', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, 'SHA', NULL, 'AES', 'authPriv', NULL, '0', '1', NULL, NULL, '0', '0', 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Location and Access Info,Log,Map,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', '0', '0', '0');
-- END


-- START
DELIMITER $$
DROP PROCEDURE IF EXISTS insert_css_networking_device_port_def $$
CREATE PROCEDURE insert_css_networking_device_port_def()
BEGIN

IF NOT EXISTS( (SELECT * 
	FROM css_networking_device_port_def
	where device_type_id = 2400) )
THEN
	REPLACE INTO css_networking_device_port_def (device_type_id,variable_name,name,default_port) VALUES (2400,'http','HTTP',8888);
	REPLACE INTO css_networking_device_port_def (device_type_id,variable_name,name,default_port) VALUES (2400,'https','HTTPS',443);
	REPLACE INTO css_networking_device_port_def (device_type_id,variable_name,name,default_port) VALUES (2400,'snmp','SNMP',8161);
	
END IF;
END $$
CALL insert_css_networking_device_port_def $$
DROP PROCEDURE IF EXISTS insert_css_networking_device_port_def $$
DELIMITER ;
-- END

RAWSQL
            );
            // R7.3.3 - B7006
            DB::unprepared(<<<RAWSQL
-- APC Power Distribution Unit device integration Wayne 20160311

-- Classify the device in the device_class table

SET FOREIGN_KEY_CHECKS=0; -- to disable them
REPLACE INTO css_networking_device_class SET id = 1153, description = 'Power Bank', is_license = 0;
SET FOREIGN_KEY_CHECKS=1; -- to re-enable them

-- Inserting device INTO device_type table

REPLACE INTO css_networking_device_type SET id = 2301, class_id = 1145, vendor = 'APC', model = 'PDU', auto_build_enabled = 1, uses_snmp = 1, defaultSNMPVer = '2c', 
defaultSNMPRead = 'eea478269a2bbc0498ac382ed500822c', defaultSNMPWrite = '31cfdf3e44c7ed4a34262041fc664d8c', main_device = 1,
uses_default_value = 1, build_file = 'apc_pdu_builder.php', scan_file = 'apc_pdu_alarm_scanner_launcher.php', prop_scan_file = 'apc_pdu_prop_scanner_launcher.php', SNMPauthEncryption = 'SHA', 
SNMPprivEncryption = 'AES', SNMPauthType = 'authPriv', support_traps = 1, has_web_interface = 1, 
canvas_list = 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Location and Access Info,Log,Map,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera';
REPLACE INTO css_networking_device_type SET id = 2303, class_id = 25, vendor = 'APC', model = 'Outlet', auto_build_enabled = 0, uses_snmp = 1, defaultSNMPVer = '2c', 
defaultSNMPRead = 'eea478269a2bbc0498ac382ed500822c', defaultSNMPWrite = '31cfdf3e44c7ed4a34262041fc664d8c', main_device = 0,
uses_default_value = 1, SNMPauthEncryption = 'SHA', SNMPprivEncryption = 'AES', SNMPauthType = 'authPriv', 
canvas_list = 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Location and Access Info,Log,Map,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera';
REPLACE INTO css_networking_device_type SET id = 2302, class_id = 1153, vendor = 'APC', model = 'Bank', auto_build_enabled = 0, uses_snmp = 1, defaultSNMPVer = '2c', 
defaultSNMPRead = 'eea478269a2bbc0498ac382ed500822c', defaultSNMPWrite = '31cfdf3e44c7ed4a34262041fc664d8c', main_device = 0,
uses_default_value = 1, SNMPauthEncryption = 'SHA', SNMPprivEncryption = 'AES', SNMPauthType = 'authPriv', 
canvas_list = 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Location and Access Info,Log,Map,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera';

-- Define the ports for for the device

DELETE FROM css_networking_device_port_def WHERE device_type_id = 2301;
REPLACE INTO css_networking_device_port_def SET device_type_id = 2301, variable_name = 'http', name = 'HTTP', default_port = 80;
REPLACE INTO css_networking_device_port_def SET device_type_id = 2301, variable_name = 'snmp', name = 'SNMP', default_port = 161;

-- Define the props and statuses for the main device 2301

DELETE FROM css_networking_device_prop WHERE device_id BETWEEN 231537 AND 231563;
DELETE FROM css_networking_device_prop_def WHERE device_type_id IN (2301, 2302, 2303);
DELETE FROM def_status_groups WHERE group_var_name LIKE '%Hardware Factory%' AND group_breadCrumb LIKE '%Hardware Factory%';
DELETE FROM def_status_groups WHERE group_var_name LIKE '%Device Status%' AND group_breadCrumb LIKE '%Device Status%';
DELETE FROM def_status_groups WHERE group_var_name LIKE '%Current TCP/IP Settings%' AND group_breadCrumb LIKE '%Current TCP/IP Settings%';
DELETE FROM def_status_groups WHERE group_var_name LIKE '%Device Configuration%' AND group_breadCrumb LIKE '%Device Configuration%';
DELETE FROM def_status_groups WHERE group_var_name LIKE '%Device Thresholds%' AND group_breadCrumb LIKE '%Device Thresholds%';
DELETE FROM def_prop_groups WHERE group_var_name LIKE '%Device Thresholds%' AND group_breadCrumb LIKE '%Device Thresholds%';
DELETE FROM def_prop_groups WHERE group_var_name LIKE '%Current TCP/IP Settings%' AND group_breadCrumb LIKE '%Current TCP/IP Settings%';
DELETE FROM def_prop_groups WHERE group_var_name LIKE '%Device Configuration%' AND group_breadCrumb LIKE '%Device Configuration%';
DELETE FROM def_prop_groups WHERE group_var_name LIKE '%Device Status%' AND group_breadCrumb LIKE '%Device Status%';
DELETE FROM def_prop_groups WHERE group_var_name LIKE '%Hardware Factory%' AND group_breadCrumb LIKE '%Hardware Factory%';
DELETE FROM css_networking_device_prop_group WHERE name IN ('Device Status', 'Device Configuration', 'Device Thresholds', 'Hardware Factory', 'Current TCP/IP Settings');
DELETE FROM def_prop_groups_map WHERE device_type_id IN (2301, 2302, 2303);
DELETE FROM def_status_groups_map WHERE device_type_id IN (2301, 2302, 2303);

REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "rPDUOutletDevColdstartDelay", 
name = "Cold Start Delay", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Delay when performing a cold start";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2301, variable_name = "rPDUIdentDeviceLinetoLineVoltage", 
name = "Line to Line Voltage", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, thresh_enable = 1, 
tooltip = "Line to Line Voltage";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2301, variable_name = "rPDUIdentDevicePowerFactor", 
name = "Power Factor", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, thresh_enable = 1, 
tooltip = "Power Factor";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "rPDULoadBankConfigNearOverloadThreshold", 
name = "Total Near Overload Warning Threshold", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, 
thresh_enable = 1, tooltip = "Total Near Overload Warning Threshold";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "rPDULoadBankConfigOverloadThreshold", 
name = "Total Overload Alarm Threshold", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, 
thresh_enable = 1, tooltip = "Total Overload Alarm Threshold";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "rPDULoadBankConfigLowLoadThreshold", 
name = "Total Low Load Warning Threshold", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, 
thresh_enable = 1, tooltip = "Total Low Load Warning Threshold";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "rPDUOutletBankOverloadRestriction", 
name = "Total Overload Outlet Restriction", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, 
thresh_enable = 0, tooltip = "Total Overload Outlet Restriction";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "rPDUIdentDeviceRating", 
name = "Device Rating", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Device Rating";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "ipAdEntAddr", 
name = "System IP Address", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "System IP Address";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2301, variable_name = "sysUpTimeInstance", 
name = "Management Uptime", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "System Management Uptime";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "ipAdEntNetMask", 
name = "System Subnet Mask", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "System Subnet Mask";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "sPDUIdentHardwareRev", 
name = "Hardware Revision", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "System Hardware Revision";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "firmware", 
name = "Firmware Version", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "System Firmware Version";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "sPDUIdentDateOfManufacture", 
name = "Date of Manufacture", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "System Date of Manufacture";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "sPDUIdentModelNumber", 
name = "Hardware Model Number", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "System Hardware Number";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "sPDUIdentSerialNumber", 
name = "Hardware Serial Number", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "System Hardware Serial Number";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "sysName", 
name = "System Name", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "System Name";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2301, variable_name = "rPDULoadStatusLoad", 
name = "Total Status Load (Amps)", data_type = "DECIMAL", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, thresh_enable = 1, 
tooltip = "Total System Load in Amps";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2301, variable_name = "rPDULoadStatusLoadState", 
name = "Load State", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "System Load State";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "ifPhysAddress", 
name = "MAC Address", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "System MAC Address";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "native_id", 
name = "Native ID", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Native ID";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2301, variable_name = "original_name", 
name = "Original Name", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Original name from the first time the device was built";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2301, variable_name = "ifInOctets", 
name = "Octets Received", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, thresh_enable = 1, 
tooltip = "The total number of octets received on the interface, including framing characters.";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2301, variable_name = "ifOutOctets", 
name = "Octets Sent", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, thresh_enable = 1, 
tooltip = "The total number of octets transmitted out of the interface, including framing characters.";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2301, variable_name = "currentInThroughput", 
name = "Current Inbound Throughput", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, thresh_enable = 1, 
tooltip = "The total number of octets received on the interface, including framing characters.";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2301, variable_name = "currentOutThroughput", 
name = "Current Outbound Throughput", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, thresh_enable = 1, 
tooltip = "The total number of octets transmitted out of the interface, including framing characters.";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2301, variable_name = "rPDUPowerSupply1Status", 
name = "Power Supply 1 Status", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Status of the first power supply.";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2301, variable_name = "rPDUPowerSupply2Status", 
name = "Power Supply 2 Status", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Status of the second power supply.";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2301, variable_name = "rPDUIdentDevicePowerWatts", 
name = "Power Watts", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, thresh_enable = 1, 
tooltip = "Device Power in watts";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2301, variable_name = "rPDUIdentDevicePowerVA", 
name = "Power VA", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, thresh_enable = 1, 
tooltip = "Device Power in VA";

-- Define the props and statuses for the subdevice 2302

REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2302, variable_name = "rPDULoadBankConfigNearOverloadThreshold", 
name = "Near Overload Warning Threshold", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, 
thresh_enable = 1, tooltip = "Near Overload Warning Threshold";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2302, variable_name = "rPDULoadBankConfigOverloadThreshold", 
name = "Overload Alarm Threshold", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, 
thresh_enable = 1, tooltip = "Overload Alarm Threshold";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2302, variable_name = "rPDULoadBankConfigLowLoadThreshold", 
name = "Low Load Warning Threshold", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, 
thresh_enable = 1, tooltip = "Low Load Warning Threshold";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2302, variable_name = "rPDUOutletBankOverloadRestriction", 
name = "Overload Outlet Restriction", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, 
thresh_enable = 0, tooltip = "Overload Outlet Restriction";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2302, variable_name = "rPDUStatusBankIndex", 
name = "Bank Index", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Bank Index";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2302, variable_name = "rPDUStatusBankState", 
name = "Bank State", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Bank State";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2302, variable_name = "rPDULoadStatusLoad", 
name = "Bank Status Load (Amps)", data_type = "DECIMAL", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 1, thresh_enable = 1, 
tooltip = "Bank Status Load in Amps";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2302, variable_name = "native_id", 
name = "Native ID", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Native ID";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2302, variable_name = "original_name", 
name = "Original Name", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Original name from the first time the device was built";

-- Define the props and statuses for the subdevice 2303

REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2303, variable_name = "rPDUOutletStatusIndex", 
name = "Outlet Index", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Outlet Index";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2303, variable_name = "rPDUOutletStatusOutletName", 
name = "Outlet Name", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Name of Outlet";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2303, variable_name = "rPDUOutletStatusOutletState", 
name = "Outlet State", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Outlet State";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2303, variable_name = "rPDUOutletStatusOutletBank", 
name = "Bank Name", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Name of bank the outlet is running on";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2303, variable_name = "rPDUOutletConfigPowerOnTime", 
name = "Power On Delay (Seconds)", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0,
tooltip = "Power On Delay in Seconds";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2303, variable_name = "rPDUOutletConfigPowerOffTime", 
name = "Power Off Delay (Seconds)", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Power Off Delay in Seconds";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2303, variable_name = "rPDUOutletConfigRebootDuration", 
name = "Outlet Reboot Duration (Seconds)", data_type = "INTEGER", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Outlet Reboot Duration in Seconds";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2303, variable_name = "native_id", 
name = "Native ID", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Native ID";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 1, device_type_id = 2303, variable_name = "original_name", 
name = "Original Name", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Original name from the first time the device was built";
REPLACE INTO css_networking_device_prop_def SET prop_type_id = 2, device_type_id = 2303, variable_name = "rPDUStatusOutletState", 
name = "Outlet Load Status", data_type = "STRING", editable = 0, visible = 1, severity_id = 4, severity_id_two = 4, graph_type = 0, thresh_enable = 0, 
tooltip = "Outlet Load Status";

-- REPLACE INTO def_prop_group

INSERT INTO def_prop_groups SET group_var_name = 'Device Configuration', group_breadCrumb = 'Device Configuration' ON DUPLICATE KEY UPDATE group_breadCrumb = 'Device Configuration';
INSERT INTO def_prop_groups SET group_var_name = 'Hardware Factory', group_breadCrumb = 'Hardware Factory' ON DUPLICATE KEY UPDATE group_breadCrumb = 'Hardware Factory';
INSERT INTO def_prop_groups SET group_var_name = 'Current TCP/IP Settings', group_breadCrumb = 'Current TCP/IP Settings' ON DUPLICATE KEY UPDATE group_breadCrumb = 'Current TCP/IP Settings';
INSERT INTO def_prop_groups SET group_var_name = 'Device Thresholds', group_breadCrumb = 'Device Thresholds' ON DUPLICATE KEY UPDATE group_breadCrumb = 'Device Thresholds';

INSERT INTO def_status_groups SET group_var_name = 'Device Status', group_breadCrumb = 'Device Status' ON DUPLICATE KEY UPDATE group_breadCrumb = 'Device Status';

-- Group the props

REPLACE INTO css_networking_device_prop_group SET name = 'Device Status';
REPLACE INTO css_networking_device_prop_group SET name = 'Device Configuration';
REPLACE INTO css_networking_device_prop_group SET name = 'Device Thresholds';
REPLACE INTO css_networking_device_prop_group SET name = 'Hardware Factory';
REPLACE INTO css_networking_device_prop_group SET name = 'Current TCP/IP Settings';

-- Prop: Device Configuration

INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDUOutletStatusOutletName', device_type_id = 2303, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDUOutletConfigPowerOnTime', device_type_id = 2303, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDUOutletConfigPowerOffTime', device_type_id = 2303, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDUOutletConfigRebootDuration', device_type_id = 2303, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDUOutletDevColdstartDelay', device_type_id = 2301, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDUOutletBankOverloadRestriction', device_type_id = 2301, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDUOutletBankOverloadRestriction', device_type_id = 2302, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'original_name', device_type_id = 2301, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'original_name', device_type_id = 2302, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'original_name', device_type_id = 2303, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'native_id', device_type_id = 2301, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'native_id', device_type_id = 2302, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'native_id', device_type_id = 2303, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDUIdentDeviceRating', device_type_id = 2301, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'sysName', device_type_id = 2301, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDUOutletStatusIndex', device_type_id = 2303, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDUOutletStatusOutletBank', device_type_id = 2303, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDUStatusBankIndex', device_type_id = 2302, group_var_name = 'Device Configuration' ON DUPLICATE KEY UPDATE group_var_name = 'Device Configuration';

-- Status: Device Thresholds

INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDULoadBankConfigNearOverloadThreshold', device_type_id = 2301, group_var_name = 'Device Thresholds' ON DUPLICATE KEY UPDATE group_var_name = 'Device Thresholds';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDULoadBankConfigOverloadThreshold', device_type_id = 2301, group_var_name = 'Device Thresholds' ON DUPLICATE KEY UPDATE group_var_name = 'Device Thresholds';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDULoadBankConfigLowLoadThreshold', device_type_id = 2301, group_var_name = 'Device Thresholds' ON DUPLICATE KEY UPDATE group_var_name = 'Device Thresholds';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDULoadBankConfigNearOverloadThreshold', device_type_id = 2302, group_var_name = 'Device Thresholds' ON DUPLICATE KEY UPDATE group_var_name = 'Device Thresholds';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDULoadBankConfigOverloadThreshold', device_type_id = 2302, group_var_name = 'Device Thresholds' ON DUPLICATE KEY UPDATE group_var_name = 'Device Thresholds';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'rPDULoadBankConfigLowLoadThreshold', device_type_id = 2302, group_var_name = 'Device Thresholds' ON DUPLICATE KEY UPDATE group_var_name = 'Device Thresholds';

-- Status: Device Status

INSERT INTO def_status_groups_map SET status_def_variable_name = 'rPDULoadStatusLoad', device_type_id = 2301, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'rPDUIdentDeviceLinetoLineVoltage', device_type_id = 2301, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'rPDUIdentDevicePowerFactor', device_type_id = 2301, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'rPDUIdentDevicePowerWatts', device_type_id = 2301, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'rPDUIdentDevicePowerVA', device_type_id = 2301, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'ifInOctets', device_type_id = 2301, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'ifOutOctets', device_type_id = 2301, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'currentInThroughput', device_type_id = 2301, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'currentOutThroughput', device_type_id = 2301, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'rPDULoadStatusLoad', device_type_id = 2302, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'rPDUStatusBankState', device_type_id = 2302, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'rPDUOutletStatusOutletState', device_type_id = 2303, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'rPDUOutletStatusOutletName', device_type_id = 2303, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'sysUpTimeInstance', device_type_id = 2301, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'rPDUStatusOutletState', device_type_id = 2303, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'rPDUPowerSupply1Status', device_type_id = 2301, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'rPDUPowerSupply2Status', device_type_id = 2301, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';
INSERT INTO def_status_groups_map SET status_def_variable_name = 'rPDULoadStatusLoadState', device_type_id = 2301, group_var_name = 'Device Status' ON DUPLICATE KEY UPDATE group_var_name = 'Device Status';

-- Props: Hardware Factory

INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'sPDUIdentHardwareRev', device_type_id = 2301, group_var_name = 'Hardware Factory' ON DUPLICATE KEY UPDATE group_var_name = 'Hardware Factory';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'firmware', device_type_id = 2301, group_var_name = 'Hardware Factory' ON DUPLICATE KEY UPDATE group_var_name = 'Hardware Factory';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'sPDUIdentDateOfManufacture', device_type_id = 2301, group_var_name = 'Hardware Factory' ON DUPLICATE KEY UPDATE group_var_name = 'Hardware Factory';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'sPDUIdentModelNumber', device_type_id = 2301, group_var_name = 'Hardware Factory' ON DUPLICATE KEY UPDATE group_var_name = 'Hardware Factory';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'sPDUIdentSerialNumber', device_type_id = 2301, group_var_name = 'Hardware Factory' ON DUPLICATE KEY UPDATE group_var_name = 'Hardware Factory';

-- Status: TCP/IP Settings

INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'ifPhysAddress', device_type_id = 2301, group_var_name = 'Current TCP/IP Settings' ON DUPLICATE KEY UPDATE group_var_name = 'Current TCP/IP Settings';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'ipAdEntNetMask', device_type_id = 2301, group_var_name = 'Current TCP/IP Settings' ON DUPLICATE KEY UPDATE group_var_name = 'Current TCP/IP Settings';
INSERT INTO def_prop_groups_map SET prop_def_variable_name = 'ipAdEntAddr', device_type_id = 2301, group_var_name = 'Current TCP/IP Settings' ON DUPLICATE KEY UPDATE group_var_name = 'Current TCP/IP Settings';

-- Update the prop_group_id

update css_networking_device_prop_def SET prop_group_id = (select id from css_networking_device_prop_group where name = 'Device Configuration') where variable_name in ('rPDUOutletStatusOutletName', 'rPDUOutletConfigPowerOnTime', 
'rPDUOutletConfigPowerOffTime', 'rPDUOutletConfigRebootDuration', 'rPDUOutletDevColdstartDelay', 'rPDUOutletBankOverloadRestriction', 'original_name', 'native_id', 'sysName', 'rPDUStatusOutletState', 'rPDUOutletStatusIndex', 
'rPDUOutletStatusOutletBank', 'rPDUStatusBankIndex', 'rPDUStatusBankIndex');
update css_networking_device_prop_def SET prop_group_id = (select id from css_networking_device_prop_group where name = 'Device Thresholds') where variable_name in ('rPDUIdentDevicePowerFactor', 'rPDULoadBankConfigOverloadThreshold', 
'rPDULoadBankConfigLowLoadThreshold', 'rPDULoadBankConfigNearOverloadThreshold', 'rPDULoadBankConfigOverloadThreshold', 'rPDULoadBankConfigLowLoadThreshold');
update css_networking_device_prop_def SET prop_group_id = (select id from css_networking_device_prop_group where name = 'Device Status') where variable_name in ('rPDUIdentDeviceRating', 'rPDUOutletStatusIndex',
'rPDUOutletStatusOutletState', 'rPDUOutletStatusOutletName', 'rPDULoadStatusLoad', 'rPDUStatusBankState', 'rPDULoadStatusLoadState', 'rPDULoadStatusLoad', 'rPDUIdentDeviceLinetoLineVoltage', 'currentInThroughput', 
'rPDUIdentDevicePowerFactor', 'rPDUIdentDevicePowerWatts', 'rPDUIdentDevicePowerVA', 'ifInOctets', 'ifOutOctets', 'rPDUStatusOutletState', 'rPDUPowerSupply1Status', 'rPDUPowerSupply2Status', 'currentOutThroughput', 
'rPDULoadStatusLoadState');
update css_networking_device_prop_def SET prop_group_id = (select id from css_networking_device_prop_group where name = 'Hardware Factory') where variable_name in ('sPDUIdentHardwareRev', 'firmware', 
'sPDUIdentDateOfManufacture', 'sPDUIdentModelNumber', 'sPDUIdentSerialNumber');
update css_networking_device_prop_def SET prop_group_id = (select id from css_networking_device_prop_group where name = 'Current TCP/IP Settings') where variable_name in ('ifPhysAddress', 'ipAdEntNetMask', 
'sysUpTimeInstance', 'ipAdEntAddr');

-- Double checking to make sure all the group names are formatted correctly

UPDATE def_status_groups SET group_var_name = 'Current TCP/IP Settings', group_breadCrumb = 'Current TCP/IP Settings' WHERE group_var_name = 'Current TCP/IP SETtings';
UPDATE css_networking_device_prop_group SET name = 'Current TCP/IP Settings' WHERE name = 'Current TCP/IP SETtings';
UPDATE def_status_groups_map SET group_var_name = 'Current TCP/IP Settings' WHERE group_var_name = 'Current TCP/IP SETtings';

-- APC Power Distribution Unit device integration Wayne 20160311

RAWSQL
            );
            // R7.3.3 - B7109
            DB::unprepared(<<<RAWSQL
INSERT IGNORE INTO css_general_config (setting_name, var1) VALUES ('generator_default_scheduling', 'false');
RAWSQL
            );
            // R7.3.3 - B7442
            DB::unprepared(<<<RAWSQL
-- Allow for longer prop opts values
ALTER TABLE css_networking_device_prop_opts MODIFY COLUMN value varchar(50) NOT NULL;

-- Add multitech controller link
UPDATE css_networking_device_type SET controller_file='multitech_rcell100_controller.php' WHERE (id = 90);

-- Add prop defs for band switching
REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt) VALUES ('1', NULL, '90', '1', '0', NULL, 'foGTargetBands', '4G Target Bands', 'STRING', '1', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '1', NULL, NULL, '0');
REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt) VALUES ('1', NULL, '90', '1', '0', NULL, 'bandSwitchingStatus', 'Band Switching Status', 'INTEGER', '1', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '1', NULL, NULL, '0');
REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt) VALUES ('1', NULL, '90', '1', '0', NULL, 'commStandard', 'Communication Standard', 'STRING', '1', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '1', NULL, NULL, '0');
REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt) VALUES ('1', NULL, '90', '1', '0', NULL, 'thrGTargetBands', '3G Target Bands', 'STRING', '1', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '1', NULL, NULL, '0');
REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt) VALUES ('1', NULL, '90', '1', '0', NULL, 'test_url', 'Test URL', 'STRING', '1', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '1', NULL, NULL, '0');
DELETE FROM css_networking_device_prop_def WHERE (variable_name='test_url' AND device_type_id = 91);
UPDATE css_networking_device_prop_def SET editable = 1 WHERE (variable_name='test_url' AND device_type_id = 90);

-- Add prop opts for band switching
REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'4G', '4G'
FROM css_networking_device_prop_def
WHERE variable_name='commStandard' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'3G', '3G'
FROM css_networking_device_prop_def
WHERE variable_name='commStandard' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'0', 'Disabled'
FROM css_networking_device_prop_def
WHERE variable_name='bandSwitchingStatus' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'1', 'Enabled'
FROM css_networking_device_prop_def
WHERE variable_name='bandSwitchingStatus' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'None', 'None'
FROM css_networking_device_prop_def
WHERE variable_name='foGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'4G', '4G'
FROM css_networking_device_prop_def
WHERE variable_name='foGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'None', 'None'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'UMTS 1900MHz,UMTS 850MHz,GSM 1900MHz,GSM 850MHz', 'UMTS 1900MHz,UMTS 850MHz,GSM 1900MHz,GSM 850MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'UMTS 850MHz,GSM 1900MHz,GSM 850MHz', 'UMTS 850MHz,GSM 1900MHz,GSM 850MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'UMTS 1900MHz,GSM 1900MHz,GSM 850MHz', 'UMTS 1900MHz,GSM 1900MHz,GSM 850MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'UMTS 1900MHz,UMTS 850MHz,GSM 850MHz', 'UMTS 1900MHz,UMTS 850MHz,GSM 850MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'UMTS 1900MHz,UMTS 850MHz,GSM 1900MHz', 'UMTS 1900MHz,UMTS 850MHz,GSM 1900MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'UMTS 1900MHz,UMTS 850MHz', 'UMTS 1900MHz,UMTS 850MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'UMTS 1900MHz,GSM 1900MHz', 'UMTS 1900MHz,GSM 1900MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'UMTS 850MHz,GSM 1900MHz', 'UMTS 850MHz,GSM 1900MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'UMTS 850MHz,GSM 850MHz', 'UMTS 850MHz,GSM 850MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'GSM 1900MHz,GSM 850MHz', 'GSM 1900MHz,GSM 850MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'UMTS 1900MHz', 'UMTS 1900MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'UMTS 850MHz', 'UMTS 850MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'GSM 1900MHz', 'GSM 1900MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);

REPLACE INTO  css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id,'GSM 850MHz', 'GSM 850MHz'
FROM css_networking_device_prop_def
WHERE variable_name='thrGTargetBands' AND (device_type_id = 90);
 
DROP TABLE IF EXISTS css_networking_band_switches;
DELETE FROM css_networking_device_prop_def 
WHERE variable_name = 'firmRelease' AND device_type_id = 90;


RAWSQL
            );
            // R7.3.3 - B7446
            DB::unprepared(<<<RAWSQL
-- Define new Device Type TDTX500/25
INSERT IGNORE INTO css_networking_device_type(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device) VALUES('5060', '1029', 'Andrew', 'TDTX500/25', '0', '1', '0', '0', '1','0');

RAWSQL
            );
            // R7.3.3 - B7457
            DB::unprepared(<<<RAWSQL
-- ---------------------------------------------------------------------------------------------
-- Bug 7457 Rename the entry for GE "Infinity NE" to "Infinity/Pulsar"
-- ---------------------------------------------------------------------------------------------
UPDATE css_networking_device_type SET model='Infinity/Pulsar' WHERE (id='2050');
-- ---------------------------------------------------------------------------------------------
-- End of script
-- ---------------------------------------------------------------------------------------------
RAWSQL
            );
            // R7.3.3 - B7567
            DB::unprepared(<<<RAWSQL
-- Create Generic Throughput Test Device Type --
REPLACE INTO css_networking_device_type (id, class_id, vendor, model, auto_build_enabled, uses_snmp, snmp_only, can_add_children, can_disable, defaultWebUi, defaultWebUiUser, defaultWebUiPw, defaultSNMPVer, defaultSNMPRead, defaultSNMPWrite, main_device, general_device_id, node_type, uses_default_value, build_file, scan_file, prop_scan_file, controller_file, SNMPuserName, SNMPauthPassword, SNMPauthEncryption, SNMPprivPassword, SNMPprivEncryption, SNMPauthType, rebuilder_file, support_traps, has_web_interface, canvas_pref_top, canvas_pref_bottom, canvas_default_top, canvas_default_bottom, canvas_list, development_flag, auto_detect_flag, heartbeat_threshold_enabled)
VALUES (5055, 2, 'Generic', 'Throughput Test', '1', '0', '0', '0', '0', NULL, NULL, NULL, '2c', 'eea478269a2bbc0498ac382ed500822c', '31cfdf3e44c7ed4a34262041fc664d8c', '1', '0', NULL, '1', 'generic_throughput_builder_launcher.php', 'generic_throughput_scanner_launcher.php', NULL, NULL, NULL, NULL, 'SHA', NULL, 'AES', 'authPriv', NULL, '0', '0', NULL, NULL, '0', '0', 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Ignored Alarms,Location and Access Info,Log,Map,Notes,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', '0', '0', '0');

-- Create Generic Throughput Test Device Type Prop Defs --
REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
VALUES (2, NULL, 5055, '1', '0', NULL, 'throughput_data_size', 'Throughput Data Size (MB)', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '127','0', '0', '1', '4', '0');
REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
VALUES (2, NULL, 5055, '1', '0', NULL, 'throughput_rate', 'Throughput Rate (Mbps)', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '127', '0', '0', '1', '4', '0');
REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
VALUES (2, NULL, 5055, '1', '0', NULL, 'throughput_test_duration', 'Throughput Test Duration (seconds)', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '127', '0', '0', '1', '4', '0');
REPLACE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
VALUES (1, NULL, 5055, '1', '0', NULL, 'throughput_test_last_run', 'Throughput Test Last Run', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '127', '0', '0', '1', '4', '0');

-- Create Generic Throughput Test Device Type Port Defs --
REPLACE INTO css_networking_device_port_def (device_type_id, variable_name, name, default_port) VALUES (5055, 'http', 'HTTP', '80');
REPLACE INTO css_networking_device_port_def (device_type_id, variable_name, name, default_port) VALUES (5055, 'https', 'HTTPS', '443');

RAWSQL
            );
            // R7.3.3 - B7581
            DB::unprepared(<<<RAWSQL
-- START
REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children, main_device,build_file,scan_file)
VALUES
(1610,10,"Solid","DMS-2000",1,1,1,1,1,"genericDeviceBuilder.php","genericScannerLauncher.php");
-- END


-- START
INSERT IGNORE INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1610,'http','HTTP',80);
INSERT IGNORE INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1610,'https','HTTPS',443);
INSERT IGNORE INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1610,'snmp','SNMP',161);
-- END

RAWSQL
            );
            // R7.3.3 - B7631
            DB::unprepared(<<<RAWSQL
-- ----------------------------
-- Function structure for format_trap_payload
-- ----------------------------
DROP FUNCTION IF EXISTS format_trap_payload;
DELIMITER ;;
CREATE DEFINER=root@localhost FUNCTION format_trap_payload(original_payload text) RETURNS text CHARSET latin1
BEGIN
  DECLARE formatted_payload text DEFAULT original_payload;
  SET formatted_payload = REPLACE(formatted_payload, ' ', '');
  SET formatted_payload = REPLACE(formatted_payload, '\\', '');
  SET formatted_payload = REPLACE(formatted_payload, '\"', '');
  SET formatted_payload = REPLACE(formatted_payload, '[', '');
  SET formatted_payload = REPLACE(formatted_payload, ']', '');
  SET formatted_payload = REPLACE(formatted_payload, ':', '');
  SET formatted_payload = REPLACE(formatted_payload, '#011', '');
  SET formatted_payload = REPLACE(formatted_payload, '#012', '');
  SET formatted_payload = REPLACE(formatted_payload, '#015', '');
  SET formatted_payload = REPLACE(formatted_payload, '\n', '');
  SET formatted_payload = REPLACE(formatted_payload, '\r', '');
  SET formatted_payload = REPLACE(formatted_payload, '\t', '');
  RETURN formatted_payload;
END
;;
DELIMITER ;


RAWSQL
            );
            // R7.3.3 - B7700
            DB::unprepared(<<<RAWSQL
SET foreign_key_checks = 0;
REPLACE INTO css_networking_device_class (id,description,is_license) VALUES (1156, 'Network Element',1);
UPDATE css_networking_device_type SET class_id=1156 WHERE id IN (9,10);
UPDATE css_networking_device_prop_def SET device_class_id = 1156 WHERE device_type_id IN (9,10) AND device_class_id = 1077;
SET foreign_key_checks = 1;

RAWSQL
            );
            // R7.3.3 - B7798
            DB::unprepared(<<<RAWSQL
-- Bug 7798. Mike Zhukovskiy
-- Correctly set the Catalyst 3750 scan files

UPDATE css_networking_device_type
SET scan_file = "cisco_dummy_alarm_scanner_launcher.php",
    prop_scan_file = "cisco_catalyst_3750_scanner_launcher.php"
WHERE id=1452;

RAWSQL
            );
            // R7.3.3 - B7804
            DB::unprepared(<<<RAWSQL
-- Bug 7804. Mike Zhukovskiy
-- Correctly set the Broadhop QNS scan files

UPDATE css_networking_device_type
SET scan_file = "cisco_dummy_alarm_scanner_launcher.php",
    prop_scan_file = "broadhop_prop_scanner_launcher.php"
WHERE id=1479;
-- End 7804
RAWSQL
            );
            // R7.3.3 - B7857
            DB::unprepared(<<<RAWSQL
-- Bug 7857 Add flag field to css_snmp_queue

SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = 'css_snmp_queue'
        AND table_schema = DATABASE()
        AND column_name = 'flag'
    ) > 0,
    "SELECT 1",
    "ALTER TABLE css_snmp_queue ADD COLUMN flag tinyint(4) NOT NULL DEFAULT 0;"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


RAWSQL
            );
            // R7.3.3 - B7878
            DB::unprepared(<<<RAWSQL
-- Bug 7878 Change SpiderCloud model name

UPDATE css_networking_device_type
SET model = "Trap Receiver"
WHERE vendor = "SpiderCloud" AND model = "SpiderCloud Receiver";


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
