<?php

// brings cswapi database from 7.3.0 to 7.3.1
// (step 3 of SiteGate 2.5 to SiteGate 2.6)

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportSiteportal731Changes extends Migration
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
            // R7.3.1 - B1508
            DB::unprepared(<<<RAWSQL
update css_networking_device_type
set `rebuilder_file` = null
where `vendor` like '%asentria%';
RAWSQL
            );
            // R7.3.1 - B2364
            DB::unprepared(<<<RAWSQL
REPLACE INTO def_status_groups (group_var_name, group_breadCrumb) VALUES ('Frequency', 'Frequency');
REPLACE INTO def_status_groups (group_var_name, group_breadCrumb) VALUES ('Downlink', 'Downlink');
REPLACE INTO def_status_groups (group_var_name, group_breadCrumb) VALUES ('Uplink', 'Uplink');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice1CurrentInputNoise','1493', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice2CurrentInputNoise','1493', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice3CurrentInputNoise','1493', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice4CurrentInputNoise','1493', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice5CurrentInputNoise','1493', 'Frequency');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice1CurrentInputNoise','1500', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice2CurrentInputNoise','1500', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice3CurrentInputNoise','1500', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice4CurrentInputNoise','1500', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice5CurrentInputNoise','1500', 'Frequency');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice1CurrentInputNoise','1501', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice2CurrentInputNoise','1501', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice3CurrentInputNoise','1501', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice4CurrentInputNoise','1501', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice5CurrentInputNoise','1501', 'Frequency');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice1CurrentInputNoise','1502', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice2CurrentInputNoise','1502', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice3CurrentInputNoise','1502', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice4CurrentInputNoise','1502', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice5CurrentInputNoise','1502', 'Frequency');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice1CurrentInputNoise','1503', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice2CurrentInputNoise','1503', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice3CurrentInputNoise','1503', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice4CurrentInputNoise','1503', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice5CurrentInputNoise','1503', 'Frequency');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice1CurrentInputNoise','1504', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice2CurrentInputNoise','1504', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice3CurrentInputNoise','1504', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice4CurrentInputNoise','1504', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice5CurrentInputNoise','1504', 'Frequency');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice1CurrentOutputNoise','1493', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice2CurrentOutputNoise','1493', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice3CurrentOutputNoise','1493', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice4CurrentOutputNoise','1493', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice5CurrentOutputNoise','1493', 'Frequency');


REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice1CurrentOutputNoise','1500', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice2CurrentOutputNoise','1500', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice3CurrentOutputNoise','1500', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice4CurrentOutputNoise','1500', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice5CurrentOutputNoise','1500', 'Frequency');


REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice1CurrentOutputNoise','1501', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice2CurrentOutputNoise','1501', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice3CurrentOutputNoise','1501', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice4CurrentOutputNoise','1501', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice5CurrentOutputNoise','1501', 'Frequency');


REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice1CurrentOutputNoise','1502', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice2CurrentOutputNoise','1502', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice3CurrentOutputNoise','1502', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice4CurrentOutputNoise','1502', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice5CurrentOutputNoise','1502', 'Frequency');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice1CurrentOutputNoise','1503', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice2CurrentOutputNoise','1503', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice3CurrentOutputNoise','1503', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice4CurrentOutputNoise','1503', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice5CurrentOutputNoise','1503', 'Frequency');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice1CurrentOutputNoise','1504', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice2CurrentOutputNoise','1504', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice3CurrentOutputNoise','1504', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice4CurrentOutputNoise','1504', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice5CurrentOutputNoise','1504', 'Frequency');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice1Freq','1502', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice2Freq','1502', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice3Freq','1502', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice4Freq','1502', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlSubBandSlice5Freq','1502', 'Frequency');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlHighEdgeFreqOfSubband','1502', 'Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlLowEdgeFreqOfSubband','1502', 'Frequency');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiDlCurrentIpPower','1492', 'Downlink');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiDlCurrentOpPower','1492', 'Downlink');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiDlModuleTemperature','1492', 'Downlink');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiDlPOIStatus','1492', 'Downlink');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlAlarmPersistencyTime','1492', 'Uplink');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlConfiguredGain','1492', 'Uplink');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlModuleTemperature','1492', 'Uplink');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlOperatingBandMaxFreq','1492', 'Uplink');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlOperatingBandMinFreq','1492', 'Uplink');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlPOIStatus','1492', 'Uplink');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiUlThresholdHysteresis','1492', 'Uplink');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiChassisPartNo','1491', 'UnitInformation\\Hardware');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiChassisRevision','1491', 'UnitInformation\\Hardware');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiChassisSerialNumber','1491', 'UnitInformation\\Hardware');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiControlModuleHwRevision','1491', 'UnitInformation\\Hardware');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiControlModulePartNo','1491', 'UnitInformation\\Hardware');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiControlModuleSerialNumber','1491', 'UnitInformation\\Hardware');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiControlModuleTemperature','1491', 'UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiDHCPClientEnable','1491', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiDHCPRangeEnd','1491', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiDHCPRangeStart','1491', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiDHCPServerEnable','1491', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiFANModuleStatus','1491', 'UnitInformation\\Hardware');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiFANStatus','1491', 'UnitInformation\\Hardware');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiInterfaceAddress','1491', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiInterfaceBroadCast','1491', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiInterfaceName','1491', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiInterfaceNetMask','1491', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiLedStatus','1491', 'UnitInformation\\Hardware');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiName','1491', 'UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiNWOperatingMode','1491', 'UnitInformation\\Hardware');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiSystemUptime','1491', 'UnitInformation');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiDefaultGateway','1490', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiHeartbeatInterval','1490', 'UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiId','1490', 'UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiLocation','1490', 'UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiNTPServerOne','1490', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiNTPServerThree','1490', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiNTPServerTwo','1490', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiPrimaryDNSServer','1490', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiSecondaryDNSServer','1490', 'LANInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hpbsmiSiteId','1490', 'UnitInformation');




RAWSQL
            );
            // R7.3.1 - B2443
            DB::unprepared(<<<RAWSQL
-- Removing unused and unpopulated date_updated column in prop history table
SET @s = (SELECT IF(
    ( SELECT COUNT(*)
      FROM INFORMATION_SCHEMA.COLUMNS
      WHERE table_name = 'css_networking_device_prop_history'
      AND table_schema = DATABASE()
      AND column_name = 'date_updated'
    ) = 0,
    'SELECT 1',
    "ALTER TABLE css_networking_device_prop_history DROP COLUMN date_updated;"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

RAWSQL
            );
            DB::unprepared(<<<RAWSQL
-- Adding trigger for automatically setting date_changed value on new prop history row.
DROP TRIGGER IF EXISTS css_networking_device_prop_history_insert_trigger;
CREATE TRIGGER css_networking_device_prop_history_insert_trigger BEFORE INSERT ON css_networking_device_prop_history FOR EACH ROW BEGIN
    SET NEW.date_changed = now();
END
RAWSQL
            );
            // R7.3.1 - B4903
            DB::unprepared(<<<RAWSQL
-- device notification table
SET @s = (SELECT IF(
    ( SELECT COUNT(*)
      FROM INFORMATION_SCHEMA.COLUMNS
      WHERE table_name = 'css_networking_alarm_notification'
      AND table_schema = DATABASE()
      AND column_name = 'send_outage'
    ) > 0,
    'SELECT 1',
		"ALTER TABLE css_networking_alarm_notification ADD COLUMN send_outage int(11) NOT NULL DEFAULT 0;"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


SET @s = (SELECT IF(
    ( SELECT COUNT(*)
      FROM INFORMATION_SCHEMA.COLUMNS
      WHERE table_name = 'css_networking_alarm_notification'
      AND table_schema = DATABASE()
      AND column_name = 'send_degradation'
    ) > 0,
    'SELECT 1',
		"ALTER TABLE css_networking_alarm_notification ADD COLUMN send_degradation int(11) NOT NULL DEFAULT 0;"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


-- device snmp notification
SET @s = (SELECT IF(
    ( SELECT COUNT(*)
      FROM INFORMATION_SCHEMA.COLUMNS
      WHERE table_name = 'css_snmp_notification'
      AND table_schema = DATABASE()
      AND column_name = 'send_outage'
    ) > 0,
    'SELECT 1',
		"ALTER TABLE css_snmp_notification ADD COLUMN send_outage TINYINT(4) NOT NULL DEFAULT 0;"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


SET @s = (SELECT IF(
    ( SELECT COUNT(*)
      FROM INFORMATION_SCHEMA.COLUMNS
      WHERE table_name = 'css_snmp_notification'
      AND table_schema = DATABASE()
      AND column_name = 'send_degradation'
    ) > 0,
    'SELECT 1',
		"ALTER TABLE css_snmp_notification ADD COLUMN send_degradation TINYINT(4) NOT NULL DEFAULT 0;"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


RAWSQL
            );
            // R7.3.1 - B4965
            DB::unprepared(<<<RAWSQL
-- BEGIN set the tooltips for Bard MC4000 device statuses - Wayne 20160115

UPDATE css_networking_device_prop_def SET tooltip = 'Alarm Board Status' WHERE name LIKE 'Alarm Board' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Average Temperature' WHERE name LIKE 'Average Deg. F' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Backup Generator Status' WHERE name LIKE 'Backup Generator' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Blower System 1 Status' WHERE name LIKE 'Blower (G) System 1' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Blower System 2 Status' WHERE name LIKE 'Blower (G) System 2' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Comfort Mode Status' WHERE name LIKE 'Comfort Mode' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Controller Failure Alarm' WHERE name LIKE 'Controller Failure' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = '1st Stage Cooling Control' WHERE name LIKE 'Cool Stage 1' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = '2nd Stage Cooling Control' WHERE name LIKE 'Cool Stage 2' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = '3rd Stage Cooling Control' WHERE name LIKE 'Cool Stage 3' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = '4th Stage Cooling Control' WHERE name LIKE 'Cool Stage 4' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Lead Unit 1 - 1st Stage Cooling System' WHERE name LIKE 'Cool Stg 1 (Y1) S1' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Lead Unit 2 - 1st Stage Cooling System' WHERE name LIKE 'Cool Stg 1 (Y1) S2' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Lead Unit 1 - 2nd Stage Cooling System' WHERE name LIKE 'Cool Stg 2 (Y2) S1' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Lead Unit 2 - 2nd Stage Cooling System' WHERE name LIKE 'Cool Stg 2 (Y2) S2' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'DC Fan Status' WHERE name LIKE 'DC Fan' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Dehumidification Circuit Status' WHERE name LIKE 'Dehumidification' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Dehumidifier Status' WHERE name LIKE 'Dehumidifier' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Economizer System 1 Status' WHERE name LIKE 'Economizer System 1' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Economizer System 2 Status' WHERE name LIKE 'Economizer System 2' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Fire/Smoke Alarm' WHERE name LIKE 'Fire/Smoke' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = '1st Stage Heating Control' WHERE name LIKE 'Heat Stage 1' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = '2nd Stage Heating Control' WHERE name LIKE 'Heat Stage 2' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = '3rd Stage Heating Control' WHERE name LIKE 'Heat Stage 3' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = '4th Stage Heating Control' WHERE name LIKE 'Heat Stage 4' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Heater System 1 Status' WHERE name LIKE 'Heater (W) System 1' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Heater System 2 Status' WHERE name LIKE 'Heater (W) System 2' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'High Temperature Alarm 1' WHERE name LIKE 'High Temp 1' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'High Temperature Alarm 2' WHERE name LIKE 'High Temp 2' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Lead System 1 Status' WHERE name LIKE 'Lead System 1' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Lead System 2 Status' WHERE name LIKE 'Lead System 2' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Local Temperature' WHERE name LIKE 'Local Deg. F' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Lock/Unlock Status' WHERE name LIKE 'Lock Status' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Low Temperature Alarm' WHERE name LIKE 'Low Temp' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Power Loss System 1 Status' WHERE name LIKE 'Power Loss System 1' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Power Loss System 2 Status' WHERE name LIKE 'Power Loss System 2' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Temperature at Remote Sensor 1 Location' WHERE name LIKE 'Remote 1' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Temperature at Remote Sensor 2 Location' WHERE name LIKE 'Remote 2' AND device_type_id = 1428;
UPDATE css_networking_device_prop_def SET tooltip = 'Cooling Set Point Temperature' WHERE name LIKE 'Set Point Deg. F' AND device_type_id = 1428;

-- END set the tooltips for Bard MC4000 device statuses - Wayne 20160115

RAWSQL
            );
            // R7.3.1 - B5508
            DB::unprepared(<<<RAWSQL
-- -----------------------------------------------------------------------------------------------------------------------------------------------------------
-- Bug 5508: This script is fixing a typo for one prop def 'Total On-line Rectifier Capacity'
-- -----------------------------------------------------------------------------------------------------------------------------------------------------------
UPDATE css_networking_device_prop_def SET name='Total On-line Rectifier Capacity (Amp):' WHERE (variable_name='Total On-line Rectifier Capacity' AND device_type_id = 2052);
-- -----------------------------------------------------------------------------------------------------------------------------------------------------------
-- End of script
-- -----------------------------------------------------------------------------------------------------------------------------------------------------------

RAWSQL
            );
            // R7.3.1 - B5779
            DB::unprepared(<<<RAWSQL
UPDATE css_authentication_user_pref SET value = 'PolledAlarms,Traps,Critical,Major,Minor,Warning,Information,Ignored,Delayed,Chronic,SLA,AllAlarmType,AllAlarmLevel,AllMisc'
WHERE variable_name = 'alarmGridFilter';


RAWSQL
            );
            // R7.3.1 - B5915
            DB::unprepared(<<<RAWSQL
-- Bug 5915: Adding missing statuses and properties for ADC Spectrum

INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt) VALUES (1, NULL, 1142, 1, 0, NULL, 'host_dart_reverse_gain_mode', 'host_dart_reverse_gain_mode', 'STRING', 0, 1, 0, 0, NULL, 0, NULL, 4, 4, 0, 0, 1, NULL, NULL, 0);

INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt) VALUES (1, NULL, 1142, 1, 0, NULL, 'host_dart_additive_gain', 'host_dart_additive_gain', 'STRING', 0, 1, 0, 0, NULL, 0, NULL, 4, 4, 0, 0, 1, NULL, NULL, 0);

INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt) VALUES (1, NULL, 1142, 1, 0, NULL, 'host_dart_gain_mode', 'host_dart_gain_mode', 'STRING', 0, 1, 0, 0, NULL, 0, NULL, 4, 4, 0, 0, 1, NULL, NULL, 0);


RAWSQL
            );
            // R7.3.1 - B5981
            DB::unprepared(<<<RAWSQL
-- Insert (if they do not exist) and update Cisco properties
-- Existence checks use regular expressions,  because some variable_name values in the database have a space in front

DROP PROCEDURE IF EXISTS upgrade_css_networking_device_prop_def_cisco
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
CREATE PROCEDURE upgrade_css_networking_device_prop_def_cisco()

BEGIN
DECLARE CheckExists int;
SET CheckExists = 0;

SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotal5sec$" AND device_type_id = 1466;

IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1466,'cpmCPUTotal5sec','cpmCPUTotal5sec');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'CPU Total 5sec', data_type = 'INTEGER', tooltip = 'The overall CPU busy percentage in the last 5 second period.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotal5sec$" AND device_type_id = 1466;

-- ====================================================================
SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotal1min$" AND device_type_id = 1466;

IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1466,'cpmCPUTotal1min','cpmCPUTotal1min');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'CPU Total 1min', data_type = 'INTEGER', tooltip = 'The overall CPU busy percentage in the last 1 minute period.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotal1min$" AND device_type_id = 1466;

-- =========================================================
SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotal5min$" AND device_type_id = 1466;

IF (CheckExists =0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1466,'cpmCPUTotal5min','cpmCPUTotal5min');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'CPU Total 5min', data_type = 'INTEGER', tooltip = 'The overall CPU busy percentage in the last 5 minute period.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotal5min$" and device_type_id = 1466;

-- ===========================================================
SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotalMonIntervalValue$" and device_type_id = 1466;

IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1466,'cpmCPUTotalMonIntervalValue','cpmCPUTotalMonIntervalValue');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'CPU Total Over Last Monitor Interval', data_type = 'INTEGER',
tooltip = 'The overall CPU busy percentage in the last monitor interval. '
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotalMonIntervalValue$" and device_type_id = 1466;

-- ================================================================
SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUMonInterval$" and device_type_id = 1466;

IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1466,'cpmCPUMonInterval','cpmCPUMonInterval');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 1, name = 'CPU Monitoring Interval (sec)', data_type = 'INTEGER', tooltip = 'CPU usage monitoring interval.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUMonInterval$" and device_type_id = 1466;

-- ====================================
SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUInterruptMonIntervalValue$" and device_type_id = 1466;

IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1466,'cpmCPUInterruptMonIntervalValue','cpmCPUInterruptMonIntervalValue');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'CPU Interrupt Over Last Monitor Period ', data_type = 'INTEGER', tooltip = 'The overall CPU busy percentage in the interrupt context in the last Monitor Interval.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUInterruptMonIntervalValue$" and device_type_id = 1466;


END
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
CALL upgrade_css_networking_device_prop_def_cisco;
DROP PROCEDURE IF EXISTS upgrade_css_networking_device_prop_def_cisco;

RAWSQL
            );
            // R7.3.1 - B6065
            DB::unprepared(<<<RAWSQL
-- Bug 6065 -- add the ICMP Report type to css_networking_report_types
INSERT IGNORE INTO css_networking_report_types (id, report_type, alarm_settings, coord_inherit, blank_values, type_id, generator_settings, help_text)
VALUES ('46', 'ICMP Report', '0', '0', '0', '0', '0', 'This report will produce an Excel spreadsheet containing information about the online status of selected devices.  This report will cause a significant amount of ICMP network traffic, as it will ping each device.  Note that only main devices are being pinged, so the status may differ from the ping results due to port forwarding.');

RAWSQL
            );
            // R7.3.1 - B6210
            DB::unprepared(<<<RAWSQL
-- update the Properties name
-- remove the (mA) from properties name after we convert the output current into Ampere to match the values presented in device web interface

INSERT INTO css_networking_device_prop_def
(variable_name, device_type_id, name)
SELECT * FROM
(SELECT 'pbtBatStringFloatCurrent.analogAlarmHI', 1268, 'Float Current - HI Alarm Threshold (mA)') as tmp
WHERE NOT EXISTS
(
SELECT * FROM css_networking_device_prop_def
WHERE variable_name = 'pbtBatStringFloatCurrent.analogAlarmHI' AND device_type_id = 1268
)
;
UPDATE css_networking_device_prop_def SET name = 'Float Current - HI Alarm Threshold'
WHERE variable_name = 'pbtBatStringFloatCurrent.analogAlarmHI' AND device_type_id = 1268;

INSERT INTO css_networking_device_prop_def
(variable_name, device_type_id, name)
SELECT * FROM
(SELECT 'pbtBatStringFloatCurrent.analogAlarmHIHI', 1268, 'Float Current - HIHI Alarm Threshold (mA)') as tmp
WHERE NOT EXISTS
(
SELECT * FROM css_networking_device_prop_def
WHERE variable_name = 'pbtBatStringFloatCurrent.analogAlarmHIHI' AND device_type_id = 1268
)
;
UPDATE css_networking_device_prop_def SET name = 'Float Current - HIHI Alarm Threshold'
WHERE variable_name = 'pbtBatStringFloatCurrent.analogAlarmHIHI' AND device_type_id = 1268;

INSERT INTO css_networking_device_prop_def
(variable_name, device_type_id, name)
SELECT * FROM
(SELECT 'pbtBatStringFloatCurrent.analogAlarmLO', 1268, 'Float Current - LO Alarm Threshold (mA)') as tmp
WHERE NOT EXISTS
(
SELECT * FROM css_networking_device_prop_def
WHERE variable_name = 'pbtBatStringFloatCurrent.analogAlarmLO' AND device_type_id = 1268
)
;
UPDATE css_networking_device_prop_def SET name = 'Float Current - LO Alarm Threshold'
WHERE variable_name = 'pbtBatStringFloatCurrent.analogAlarmLO' AND device_type_id = 1268;

INSERT INTO css_networking_device_prop_def
(variable_name, device_type_id, name)
SELECT * FROM
(SELECT 'pbtBatStringFloatCurrent.analogAlarmLOLO', 1268, 'Float Current - LOLO Alarm Threshold (mA)') as tmp
WHERE NOT EXISTS
(
SELECT * FROM css_networking_device_prop_def
WHERE variable_name = 'pbtBatStringFloatCurrent.analogAlarmLOLO' AND device_type_id = 1268
)
;
UPDATE css_networking_device_prop_def SET name = 'Float Current - LOLO Alarm Threshold'
WHERE variable_name = 'pbtBatStringFloatCurrent.analogAlarmLOLO' AND device_type_id = 1268;
RAWSQL
            );
            // R7.3.1 - B6224
            DB::unprepared(<<<RAWSQL
-- add the index
SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE table_schema=DATABASE()
		AND table_name='data_alarm_event_history'
		AND index_name='alarm_id_indx'
    ) > 0,
    "SELECT 1",
    "CREATE INDEX alarm_id_indx ON data_alarm_event_history(alarm_id) USING BTREE;"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
RAWSQL
            );
            // R7.3.1 - B6628
            DB::unprepared(<<<RAWSQL
UPDATE css_networking_device_prop_def SET prop_type_id=1
WHERE variable_name IN
('Fire/Smoke','High Temp 1','High Temp 2','High Temp 3',
'Low Temp','Controller Failure','Alarm Board',
'Backup Generator','Comfort Mode','Dehumidification',
'Lock Status','Lead System Sysetm 1','Lead System Sysetm 2',
'Power Loss Sysetm 1','Power Loss Sysetm 2','Ref. Lockout ALM S1',
'Ref. Lockout Alm S2','Remote 1','Remote 2','Primary DNS','Secondry DNS')
 AND device_type_id=1428;
RAWSQL
            );
            // R7.3.1 - B6476
            DB::unprepared(<<<RAWSQL
-- multitech modem device type
INSERT IGNORE INTO `css_networking_device_type` VALUES (90, 5, 'MultiTech', 'rCell 100', 1, 0, 0, 1, 0, NULL, 'admin', '9b710787e1b06ddfb97ec58d35730977', '2c', 'eea478269a2bbc0498ac382ed500822c', '31cfdf3e44c7ed4a34262041fc664d8c', NOW(), 1, 0, 4, 1, 'multitech_rcell_100_builder.php', 'multitech_rcell_100_launcher.php', NULL, NULL, NULL, NULL, 'SHA', NULL, 'AES', 'authPriv', NULL, 0, 1, '', '', 0, 0, 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Location and Access Info,Log,Map,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', 0, 0, 0);
-- multitech modem band device type
INSERT IGNORE INTO `css_networking_device_type` VALUES (91, 5, 'MultiTech', 'rCell 100 Band', 0, 0, 0, 1, 1, NULL, 'admin', '9b710787e1b06ddfb97ec58d35730977', '2c', 'eea478269a2bbc0498ac382ed500822c', '31cfdf3e44c7ed4a34262041fc664d8c', NOW(), 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'SHA', NULL, 'AES', 'authPriv', NULL, 0, 1, '', '', 0, 0, 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Location and Access Info,Log,Map,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', 0, 0, 0);
-- multitech rcell 100 port defs
INSERT IGNORE INTO `css_networking_device_port_def` VALUES (null, 90, 'https', 'HTTPS', 443, NOW());
INSERT IGNORE INTO `css_networking_device_port_def` VALUES (null, 90, 'telnet', 'TELNET', 5000, NOW());
-- multitech rcell 100 modem props
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 1, 0, '644', 'carrier', 'Carrier', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 2, 0, '25', 'modemserialnumber', 'Modem Serial Number', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 1, 0, '8', 'firmRelease', 'Firmware Release', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 2, 0, '17', 'mdn', 'Phone Number (MDN)', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 1, 0, NULL, 'original_name', 'Original Name', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 2, 0, '17', 'iccid', 'ICCID', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 2, 0, '17', 'imei', 'IMEI', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 2, 0, '17', 'imsi', 'IMSI', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 2, 0, '17', 'manufacturer', 'Manufacturer', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 2, 0, '17', 'network', 'Network', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 1, 0, '643', 'ip', 'IP Address', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 1, 0, '643', 'lanip', 'Local IP Address', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 1, 0, '643', 'operatingband', 'Operating Band', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 1, 0, '643', 'apn', 'APN', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 1, 0, '643', 'mcc', 'Mobile Country Code (MCC)', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 1, 0, '643', 'mnc', 'Mobile Network Code (MNC)', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 90, 1, 0, '643', 'plmnid', 'Public Land Mobile Network ID (PLMN ID)', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 90, 1, 0, '643', 'modem_uptime', 'Modem Uptime (minutes)', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 90, 1, 0, '643', 'cellular_connection_time', 'Cellular Connection Time (minutes)', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, 1, NULL, 0);

-- multitech rcell 100 band props
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 91, 1, 0, '643', 'test_url', 'Test URL', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 91, 1, 0, '643', 'test_url_result', 'Test URL Result', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 91, 1, 0, '643', 'arfcn', 'ARFCN', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 91, 1, 0, '643', 'earfcn', 'EARFCN', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 91, 1, 0, '643', 'uarfcn', 'UARFCN', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 91, 1, 0, '644', 'original_name', 'Original Name', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 1, NULL, 91, 1, 0, '643', 'tower', 'Tower', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 0, NULL, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '261', 'rssi', 'RSSI (dBm)', 'INTEGER', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '260', 'channel', 'Channel', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '260', 'cid', 'CID', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '643', 'txpower', 'TX Power (dBm)', 'DECIMAL', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '643', 'roaming', 'Roaming', 'STRING', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '643', 'avg', 'Ping Average (ms)', 'DECIMAL', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '643', 'loss', 'Ping Loss (%)', 'DECIMAL', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '643', 'max', 'Ping Max (ms)', 'DECIMAL', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '643', 'min', 'Ping Min (ms)', 'DECIMAL', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '643', 'time', 'Ping Time (ms)', 'DECIMAL', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '643', 'ecio', 'Ec/Io', 'DECIMAL', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '643', 'rscp', 'RSCP (dBm)', 'DECIMAL', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '643', 'rsrp', 'RSRP (dBm)', 'DECIMAL', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);
INSERT IGNORE INTO `css_networking_device_prop_def` VALUES (null, 2, NULL, 91, 1, 0, '643', 'rsrq', 'RSRQ (dB)', 'DECIMAL', 0, 1, 0, 0, '0', 0, '0', 4, 4, 0, '2015-2-26 17:22:26', 0, 1, 1, NULL, 0);

-- create `band-switching` flag in the config table
INSERT INTO `css_general_config` VALUES ('enable_band_switching', '0', NULL, NULL, NULL, 'enables band switch functionality on applicable modems');

-- ----------------------------
-- Table structure for css_networking_band_switches
-- ----------------------------
DROP TABLE IF EXISTS `css_networking_band_switches`;
CREATE TABLE `css_networking_band_switches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `device_ip_address` varchar(255) NOT NULL,
  `band` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- add new table for connectivity test urls
DROP TABLE IF EXISTS `css_networking_connectivity_url`;
CREATE TABLE `css_networking_connectivity_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `device_ip_address` varchar(255) NOT NULL,
  `target_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

RAWSQL
            );
            // R7.3.1 - B6478
            DB::unprepared(<<<RAWSQL
-- add the index
SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE table_schema=DATABASE()
                              AND table_name='css_networking_alarm_severity '
                              AND index_name='idx_css_networking_alarm_severity_id'
    ) > 0,
    "SELECT 1",
    "CREATE INDEX idx_css_networking_alarm_severity_id ON css_networking_alarm_severity (id) USING BTREE;"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

RAWSQL
            );
            // R7.3.1 - B6517
            DB::unprepared(<<<RAWSQL
-- START ASCO Automatic Transfer Switch - Does not populate SNMP credentials and default UID or password. SET THE ENCRYPTED VALUE FOR THE VARIABLES IN THE TABLE. TAREQ 12/16/15
 UPDATE css_networking_device_type SET defaultSNMPRead='eea478269a2bbc0498ac382ed500822c', defaultSNMPWrite='31cfdf3e44c7ed4a34262041fc664d8c', defaultWebUiPw='ec6d52fc1bf7473b8b3df185d48d57cb' WHERE id=1599;
 -- END

RAWSQL
            );
            // R7.3.1 - B6529
            DB::unprepared(<<<RAWSQL
-- B6529 As part of this bug, we are deprecating the original Generator Compliance Report and changing the working and functional Generator Sitewide Compliance Report into the Generator Compliance Report.
-- We are also making it clear in the report help text that the report can be run on one or more generators
DELETE rtemp.*
FROM css_networking_report_template rtemp
INNER JOIN css_networking_report_types rtype ON rtemp.type_id = rtype.id AND rtype.report_type = 'Generator Sitewide Compliance Report';

DELETE FROM css_networking_report_types WHERE report_type = 'Generator Sitewide Compliance Report';

UPDATE css_networking_report_types SET help_text = 'This report will produce a zip file containing one or more Excel spreadsheet reports for any (single or multiple) generators under the selected node:\r\n-             Monthly Operations Log\r\n-             Rolling 12-Month Operations\r\n-             NOx Emissions\r\n-             CO Emissions\r\n-             SOx Emissions\r\n-             PM Emissions\r\n-             VOC Emissions\r\n-             Emisson Factors' WHERE id=38;


RAWSQL
            );
            // R7.3.1 - B6577
            DB::unprepared(<<<RAWSQL

-- ----------------------------------------------------------------------------------------------------------------------------------------------
-- The Generator lockout feature is being removed from the UI.  Cleaning up supporting database components.
-- ----------------------------------------------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS css_generator_lockouts;

-- ----------------------------------------------------------------------------------------------------------------------------------------------
-- End
-- ----------------------------------------------------------------------------------------------------------------------------------------------

RAWSQL
            );
            // R7.3.1 - B6586
            DB::unprepared(<<<RAWSQL
-- Add flag to css_snmp_incoming_trap for MIBS that come in to Kreiger, that we aren't sure what to do with yet.

SET @s = (SELECT IF(
    ( SELECT COUNT(*)
      FROM INFORMATION_SCHEMA.COLUMNS
      WHERE table_name = 'css_snmp_incoming_trap'
      AND table_schema = DATABASE()
      AND column_name = 'unknown_mib'
    ) > 0,
    'SELECT 1',
    "ALTER TABLE css_snmp_incoming_trap ADD COLUMN unknown_mib bit(1) DEFAULT b'0';"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


RAWSQL
            );
            // R7.3.1 - B6615
            DB::unprepared(<<<RAWSQL
-- Add flags for Wifi Aggregation SLA requirement code enabling.

INSERT IGNORE INTO css_general_config (setting_name, var1, var2, var3, var4, description) VALUES ('enable_snmp_trap_logging', '0', NULL, NULL, NULL, 'Enable PHP facilities for logging traps to MySQL.');
INSERT IGNORE INTO css_general_config (setting_name, var1, var2, var3, var4, description) VALUES ('enable_snmp_trap_failover', '0', NULL, NULL, NULL, 'Enable PHP facilities for failover trap handling.');


RAWSQL
            );
            // R7.3.1 - B6661
            DB::unprepared(<<<RAWSQL
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'Fixed Transmitter Attenuation';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'first_unreachable_timestamp';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'FLI Network 1 Address';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'FLI Network 1 Name';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'FLI Network 1 Physical ID';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'Maximum Receiver Alarm Level ';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'Maximum Receiver Attenuation ';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'Minimum Receiver Attenuation ';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'Minimum Receiver Alarm Level ';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'Minimum Transmitter Attenuation';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'Receiver Diode Type';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'Transmitter Laser Type';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 1 Address';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 1 Name';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 1 Physical ID';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 2 Address';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 2 Name';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 2 Physical ID';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 3 Address';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 3 Name';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 3 Physical ID';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 4 Address';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 4 Name';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 4 Physical ID';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 5 Address';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 5 Name';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 5 Physical ID';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 6 Address';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 6 Name';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 6 Physical ID';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 7 Address';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 7 Name';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 7 Physical ID';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 8 Address';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 8 Name';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 8 Physical ID';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 9 Address';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 9 Name';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1369 AND variable_name = 'WLI Network 9 Physical ID';

UPDATE css_networking_device_prop_def SET prop_type_id = 2 WHERE device_type_id = 1369 AND variable_name = '\$repeater->unreachable';

UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'first_unreachable_timestamp';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WBA 1 DL max gain';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WBA 1 UL max gain';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WBA 1 DL min gain';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WBA 1 UL min gain';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WBA 2 DL max gain';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WBA 2 UL max gain';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WBA 2 DL min gain';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WBA 2 UL min gain';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WBA1 BA Addr';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WBA1 Type';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WBA2 BA Addr';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WBA2 Type';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WBA2 WBA Addr';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WLI Network 1 Address';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WLI Network 1 Name';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WLI Network 1 Physical ID';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WLI Network 2 Address';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WLI Network 2 Name';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WLI Network 2 Physical ID';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WLI Network 3 Name';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WLI Network 3 Address';
UPDATE css_networking_device_prop_def SET prop_type_id = 1 WHERE device_type_id = 1370 AND variable_name = 'WLI Network 3 Physical ID';

UPDATE css_networking_device_prop_def SET prop_type_id = 2 WHERE device_type_id = 1370 AND variable_name = 'Gain Trim Cal Active DOWNLINK 0';
UPDATE css_networking_device_prop_def SET prop_type_id = 2 WHERE device_type_id = 1370 AND variable_name = 'Max Gain Limit Active  DOWNLINK 0';
UPDATE css_networking_device_prop_def SET prop_type_id = 2 WHERE device_type_id = 1370 AND variable_name = 'Gain Control Valid DOWNLINK 0';
UPDATE css_networking_device_prop_def SET prop_type_id = 2 WHERE device_type_id = 1370 AND variable_name = 'Gain Trim Cal Active DOWNLINK 1';
UPDATE css_networking_device_prop_def SET prop_type_id = 2 WHERE device_type_id = 1370 AND variable_name = 'Max Gain Limit Active  DOWNLINK 1';
UPDATE css_networking_device_prop_def SET prop_type_id = 2 WHERE device_type_id = 1370 AND variable_name = 'Gain Control Valid DOWNLINK 1';
UPDATE css_networking_device_prop_def SET prop_type_id = 2 WHERE device_type_id = 1370 AND variable_name = 'Gain Trim Cal Active DOWNLINK 2';
UPDATE css_networking_device_prop_def SET prop_type_id = 2 WHERE device_type_id = 1370 AND variable_name = 'Max Gain Limit Active  DOWNLINK 2';
UPDATE css_networking_device_prop_def SET prop_type_id = 2 WHERE device_type_id = 1370 AND variable_name = 'Gain Control Valid DOWNLINK 2';
UPDATE css_networking_device_prop_def SET prop_type_id = 2 WHERE device_type_id = 1370 AND variable_name = 'Gain Trim Cal Active DOWNLINK 3';
UPDATE css_networking_device_prop_def SET prop_type_id = 2 WHERE device_type_id = 1370 AND variable_name = 'Max Gain Limit Active  DOWNLINK 3';
UPDATE css_networking_device_prop_def SET prop_type_id = 2 WHERE device_type_id = 1370 AND variable_name = 'Gain Control Valid DOWNLINK 3';

RAWSQL
            );
            // R7.3.1 - B6662
            DB::unprepared(<<<RAWSQL
/*----------Grouping properties----------*/

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Ip Address',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Gateway',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Mask',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'original_name',
		'1370',
		'General'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repeater Name',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Hardware ID',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLMRX Information',
		'1370',
		'General'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI information',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'FLI information',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Current Application',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Boot-Software Version',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'CU Application 1 Version',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'CU Application 2 Version',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'CU Software Cuboard Version',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'CU Software Serial Number',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'CU Software Year',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'CU Software Week',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'CU Software Type',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'CU Software Flash Size',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Modem Param Speed',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Network ID',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Node Config Current Role',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Node Configuration Network ID',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Alarm Intensity',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Modem Init String',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Dialing Method',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Physical ID',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	('Active', '1370', 'General');

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Wire Line Interface Address',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'FON Interface Address',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Wire Line Interface Net-Mask Address',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'FON Interface Gateway Address',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Name Service Units Address Primary',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Modem Address',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repeater Network ID',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Type-1',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Address-1',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Gain Downlink-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Gain Downlink-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Frequency Downlink-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Frequency Downlink-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Frequency Uplink1-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Frequency Uplink1-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum AGC Downlink-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum AGC Downlink-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum AGC Uplink1-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum AGC Uplink1-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain for Amplifier Strip-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Computed Minimum Gain-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Computed Maximum Gain-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Attenuation-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Attenuation-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode-1',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Software AGC Det Limit-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Gain Limit-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain for Amplifier Strip-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Computed Minimum Gain-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Computed Maximum Gain-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Attenuation-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Attenuation-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode-5',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop Time Constant-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Factor High-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Factor Low-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Factor High-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Factor Low-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Hardware AGC Det Limit-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Software AGC Det Limit-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Max Gain-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Calibration-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Calibration Str-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Calibration-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Calibration Str-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Frequency Uplink2-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Frequency Uplink2-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum AGC Uplink2-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum AGC Uplink2-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain for Amplifier Strip-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Computed Minimum Gain-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Computed Maximum Gain-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Attenuation-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Attenuation-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode-9',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop Time Constant-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Factor High-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Factor Low-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Factor High-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Factor Low-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Hardware AGC Det Limit-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Software AGC Det Limit-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Max Gain-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Calibration Str-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Calibration-9',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Receiver Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Receiver Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Transmitter Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Transmitter Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Hardware Receiver Type',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Hardware Transmitter Type',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Hardware Sub Carrier Frequency',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Transmitter Wavelength',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Fixed Transmission Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Fiber Optical Enable',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Fiber Optical Powersaver',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Suppress RX Alarms',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Sub Carrier Frequency',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Receiver Optical Detector Diode Type',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Transmitter Optical Detector Diode Type',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'RF Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Transmitter RF Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Transmission Part Adjustable RF Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Hardware Flags',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Gain Limit-5',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Hardware Minimum Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Hardware Maximum Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Frequency ID',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Frequency ID',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Bandwidth',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Bandwidth',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bsel Hardware min freaquency uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bsel Hardware max freaquency uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bsel Hardware Duplex Distance',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bsel Hardware Duplex Dist attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BSC Gain Control Recovery Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Mounted-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Mounted-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Diversity Mounted-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink2 Mounted-1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkEnable WRH',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkSoftware AGC',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkHardware AGC',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkGain for Amplifier Strip',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkComputed Minimum Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkComputed Maximum Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkInput Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkOutput Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkDown Reg Alarm',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkThreshold Value',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkMode',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkDefault Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkLoop Time Constant',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkAttac Factor High',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkAttac Factor Low',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkDecay Factor High',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkDecay Factor Low',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkHardware AGC Det Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkSoftware AGC Det Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkLNA Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkLNA Max Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkMaximum Gain Linit Active',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkGain Trim Calibration Active',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkMaximum Gain Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkGain Trim Calibration',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkBoard Number',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkGain Trim Calibration Str',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkEnable WRH',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkSoftware AGC',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkHardware AGC',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkGain for Amplifier Strip',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkComputed Minimum Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkComputed Maximum Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkInput Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkOutput Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkDown Reg Alarm',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkThreshold Value',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkMode',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkDefault Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkLoop Time Constant',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkAttac Factor High',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkAttac Factor Low',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkDecay Factor High',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkDecay Factor Low',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkHardware AGC Det Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkSoftware AGC Det Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkLNA Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkLNA Max Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkMaximum Gain Linit Active',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkGain Trim Calibration Active',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkMaximum Gain Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkGain Trim Calibration',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkBoard Number',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkGain Trim Calibration Str',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Type',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Address',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Gain Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Gain Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Frequency Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Frequency Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Frequency Uplink1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Frequency Uplink1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Frequency Uplink2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Frequency Uplink2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum AGC Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum AGC Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum AGC Uplink1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum AGC Uplink1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum AGC Uplink2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum AGC Uplink2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Mounted',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Mounted',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Diversity Mounted',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink2 Mounted',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Enable WRH',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Software AGC',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Hardware AGC',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Gain for Amplifier Strip',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Computed Minimum Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Computed Maximum Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Input Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Output Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Down Reg Alarm',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Threshold Value',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Mode',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Default Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Attac Factor High',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Attac Factor Low',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Decay Factor High',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Decay Factor Low',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Hardware AGC Det Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Software AGC Det Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink LNA Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink LNA Max Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Maximum Gain Linit Active',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Gain Trim Calibration Active',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Maximum Gain Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Gain Trim Calibration',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Board Number',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Gain Trim Calibration Str',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Enable WRH',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Software AGC',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Hardware AGC',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Gain for Amplifier Strip',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Computed Minimum Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Computed Maximum Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Input Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Output Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Down Reg Alarm',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Threshold Value',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Mode',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Default Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Attac Factor High',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Attac Factor Low',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Decay Factor High',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Decay Factor Low',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Hardware AGC Det Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Software AGC Det Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink LNA Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink LNA Max Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Maximum Gain Linit Active',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Gain Trim Calibration Active',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Maximum Gain Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Gain Trim Calibration',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Board Number',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Gain Trim Calibration Str',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Computed  Minimum Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Maximum Gain Limit Active',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Computed  Minimum Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Maximum Gain Limit Active',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Low Frequency Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Low Frequency Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Bsel Repeater Bandwidth',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bsel Repeater Uplink Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bsel Repeater Downlink Gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BSC Gain Control Recovery Gain.',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'The High Frequency value of Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'The High Frequency value of Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink AGC Active Alarm On',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink AGC Active Alarm On',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Supress RX Alarms',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'ISG IP Address',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repeater Port',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repeater Mac',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repeater Ip',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Type',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Base Cover Info',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'MrxType',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Modem Type',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'OSI Link',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'F2F Link',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Modem2 Type',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Capabilities',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW Capabilities',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Gateway',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Mask',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Primary NS',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Secondary NS',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'unreachable_Threshold',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'FLI Ip Address',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'FLI Gateway',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'FLI Mask',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'OMS Primary',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'OMS Secondary',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repater Type',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repater Base Cover Info',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repater Mrx Type',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repater Modem Type',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repater OSI Link',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repater F2F Link',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repater Modem2 Type',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repater Accessory',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repater Capabilities',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repater SW Capabilities',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Type',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 BA Addr ',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Max LNA Att UL1 ',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Max LNA Att UL2 ',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Type',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 WBA Addr ',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 BA Addr ',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Max LNA Att UL1 ',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Max LNA Att UL2 ',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA On Off Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Temp Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA RSSI Det V Uplink',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Output Power Detc Bm Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Set Gain Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Control To Gain Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Used Gain Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Threshold Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC State Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC Alarm Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Attenuation Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Attenuation Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Max Gain Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Attenuation Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Detected System Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Nom Gain Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Out Det V Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Det Lim SW Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Det Lim HW Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Running Mode Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Alarm Flag Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Bit Status Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC Mode Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC Num State Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop Time Constant Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Constant Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Constant Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'first_unreachable_timestamp',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	('2', '1370', 'Repeater Info');

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	('1', '1370', 'Repeater Info');

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Att UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Att UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Threshold UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Down Reg Alarm UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Use Set Lim UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac High UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Low UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay High UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Low UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC Det Lim UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Det Lim UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Att UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Nom Gain UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control ValidUPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Flags UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal Active UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit Active  UPLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Att UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Att UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Threshold UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Down Reg Alarm UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Use Set Lim UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac High UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Low UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay High UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Low UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC Det Lim UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Det Lim UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Att UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Nom Gain UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Flags UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal Active UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit Active  UPLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Att UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Att UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Threshold UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Down Reg Alarm UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Active UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Use Set Lim UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac High UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Low UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay High UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Low UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC Det Lim UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Det Lim UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Att UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Nom Gain UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control ValidUPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Flags UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal Active UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit Active  UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Valid UPLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Att UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Att UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Threshold UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Down Reg Alarm UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Active UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Use Set Lim UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac High UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Low UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay High UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Low UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC Det Lim UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Det Lim UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Att UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Nom Gain UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control ValidUPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Flags UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal Active UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit Active  UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Valid UPLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA On Off Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA PA On Off Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	('WBA BA', '1370', 'RFconfig');

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BA Output Power Detc Bm Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Set Gain Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Control To Gain Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Used Gain Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Threshold Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC Alarm Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Attenuation Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Attenuation Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Detected System Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Nom Gain Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Det Lim SW Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Det Lim HW Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Running Mode Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Alarm Flag Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Bit Status Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'PA Gain Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'PA Det V Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'PA Det Lim Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BA Nom Gain Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BA Det V Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BA Det Lim Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC Mode Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop Time Constant Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Constant',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Constant',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Transmitter Wavelength Centi',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Reciever Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Reciever Alarm Level',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Receiver Diode Type',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Transmitter Laser Type',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Fixed Transmitter Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Laser Transmitter Power',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	('Flags', '1370', 'RFconfig');

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Fiber Optical Powersave',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Receiver Warning Level',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Receiver Error Level',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Receiver Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Low Power Transmitter',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Transmitter Attenuation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Transmitter Disable Afc',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Xemics Flag Freq',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Version',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Transmitter Status',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Reciever Status',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Reciever Level',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Transmitter Level',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'General Status',
		'1370',
		'General'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Att UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Att UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Threshold UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Down Reg Alarm UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Active UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Use Set Lim UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac High UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Low UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay High UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Low UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC Det Lim UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Det Lim UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Att UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Nom Gain UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control ValidUPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Flags UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal Active UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit Active  UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Valid UPLINK DIV 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Att UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Att UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Threshold UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Down Reg Alarm UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Active UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Use Set Lim UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac High UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Low UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay High UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Low UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC Det Lim UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Det Lim UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Att UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Nom Gain UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control ValidUPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Flags UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal Active UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit Active  UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Valid UPLINK DIV 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Att UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Att UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Threshold UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Down Reg Alarm UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Active UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Use Set Lim UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac High UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Low UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay High UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Low UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC Det Lim UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Det Lim UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Att UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Nom Gain UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control ValidUPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Flags UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal Active UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit Active  UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Valid UPLINK DIV 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Att UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Att UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Threshold UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Down Reg Alarm UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Active UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Use Set Lim UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac High UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Low UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay High UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Low UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC Det Lim UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Det Lim UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Att UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Nom Gain UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control ValidUPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Flags UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal Active UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit Active  UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Valid UPLINK DIV 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 1 Name',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 1 Address',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 1 Physical ID',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 2 Name',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 2 Address',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 2 Physical ID',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 3 Name',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 3 Address',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 3 Physical ID',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Att DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Att DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Threshold DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Down Reg Alarm DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Use Set Lim DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac High DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Low DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay High DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Low DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC Det Lim DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Det Lim DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Att DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Nom Gain DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control ValidDOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Flags DOWNLINK 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Att DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Att DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Threshold DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Down Reg Alarm DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Use Set Lim DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac High DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Low DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay High DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Low DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC Det Lim DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Det Lim DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Att DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Nom Gain DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control ValidDOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Flags DOWNLINK 1',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Att DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Att DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Threshold DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Down Reg Alarm DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Active DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Use Set Lim DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac High DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Low DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay High DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Low DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC Det Lim DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Det Lim DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Att DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Nom Gain DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control ValidDOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Att DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Att DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Threshold DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Down Reg Alarm DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Active DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Mode DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Use Set Lim DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac High DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Low DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay High DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Low DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'HW AGC Det Lim DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Det Lim DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Att DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Nom Gain DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control ValidDOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Flags DOWNLINK 3',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA On Off Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Temp Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA RSSI Det V Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Output Power Detc Bm Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BA Output Power Detc Bm Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Control To Gain Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Threshold Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Attenuation Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Attenuation Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Det Output Power Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Detected System Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Nom Gain Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Det Lim SW Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Det Lim HW Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Running Mode Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Alarm Flag Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Bit Status Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'PA Gain Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'PA Det V Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'PA Det Lim Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BA Nom Gain Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BA Det V Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BA Det Lim Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC Mode Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC Num State Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop Time Constant Downlink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Constant 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Constant 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA On Off Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Temp Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA RSSI Det V Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Output Power Detc Bm Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Control To Gain Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'SW AGC Threshold Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC State Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Input Attenuation Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Output Attenuation Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Max Gain Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'LNA Attenuation Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Detected System Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Nom Gain Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Det Lim SW Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Det Lim HW Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Running Mode Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Alarm Flag Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Bit Status Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC Mode Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC Num State Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Loop Time Constant Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Attac Constant Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Decay Constant Uplink 0',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA 1 DL min gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA 1 DL max gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA 1 UL min gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA 1 UL max gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA 2 DL min gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA 2 DL max gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA 2 UL min gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA 2 UL max gain',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Detected System',
		'1370',
		'General'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'VersionId',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'MinGain (Db)',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'MaxGain (Db)',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Min Freq Dl',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Freq Dl',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Min Freq Ul',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Freq Ul',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Duplex Dist',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Duplex Dist Alt',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Frequencey Step',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BandWidth Step',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Min BandWidth',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max BandWidth',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Booster',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Variable BW',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Has On Off',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Center Frequency Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Center Frequency Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'High Frequency Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'High Frequency Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bandwidth',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bsel On',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Alarm Active',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Set To Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Set To Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Control To Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Control To Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Agc Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Agc Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'PA Uplink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'PA Downlink',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BandWidth Settable',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BandWidth Info',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Intermodulation',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Alc Type',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trail',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Alc Test Limit',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Swap If Filter',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Recovery Time',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Recovery Gain Margin',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Repeater Type',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'\$repeater->unreachable',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'\$hourdiff',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'ModemAddress',
		'1370',
		'Network'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Flags DOWNLINK 2',
		'1370',
		'RFconfig'
	);

REPLACE INTO def_prop_groups_map (
	prop_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control ValidUPLINK 1',
		'1370',
		'RFconfig'
	);



/*----------Adding status groups----------*/
REPLACE INTO def_status_groups (
	group_var_name,
	group_breadCrumb
)
VALUES
	('Network', 'Network');

REPLACE INTO def_status_groups (
	group_var_name,
	group_breadCrumb
)
VALUES
	(
		'RF Parameters',
		'RF Parameters'
	);

REPLACE INTO def_status_groups (
	group_var_name,
	group_breadCrumb
)
VALUES
	(
		'Repeater Info',
		'Repeater Info'
	);

REPLACE INTO def_status_groups (
	group_var_name,
	group_breadCrumb
)
VALUES
	('General', 'General');

/*----------Grouping statuses----------*/
REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Control Station Capability Enabled',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Alarm Output Level',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	('Date', '1370', 'General');

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	('Time', '1370', 'General');

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Statistic Collection Time Started',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Local Time',
		'1370',
		'General'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Transmitted Messages',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Received Messages',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Retransmitted Messges',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Lost Messages',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bytes Transmitted',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bytes Received',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bytes Retransmitted',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Error Rate of Bytes',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Minimum Receiver Alarm Level',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Maximum Receiver Alarm Level',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Optical Signal Strength Warning Level',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Optical Signal Strength Error Level',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Low Power Level',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Transmitter Power',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bsel Hardware Frequency Step(change)',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bsel Hardware Bandwidth Step(change)',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'DownlinkParameter Status',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'UplinkParameter Status',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Parameter Status',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Loop Time Constant',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Parameter Status',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Loop Time Constant',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'BSC Gain Control Recovery Time',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Bsel Bandwidth',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Set Gain',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Used Gain',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Detector',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink AGC State',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink AGC Alarm ONOFF',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Set Gain',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Used Gain',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Detector',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink AGC State',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink AGC Alarm ONOFF',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink WBA ONOFF',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink WBA Output Power',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink PA ONOFF',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink BA ONOFF',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink WBA ONOFF',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink WBA Output Power',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink PA ONOFF',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink BA ONOFF',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink WBA',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink PA',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink BA',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink WBA',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink PA',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink BA',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink RH Output Power',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Low Power Alarm On',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Low Power Signal Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Low Power Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Min Low Power Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Max Low Power Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Minor Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Critical Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Min Minor Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Max Minor Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Min Critical Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Downlink Max Critical Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Low Power Alarm On',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Low Power Signal Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Low Power Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Min Low Power Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Max Low Power Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Minor Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Critical Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Min Minor Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Max Minor Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Min Critical Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Uplink Max Critical Time Threshold',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Min Gain DL ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Max Gain DL ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Min Gain UL1 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Max Gain UL1 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Min Gain UL2 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Max Gain UL2 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Max Freq DL ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Min Freq DL ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Min Freq UL1 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Max Freq UL1 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Min Freq UL2 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Max Freq UL2 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 WBA Addr ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Max AGC DL ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Min AGC DL ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Min AGC UL1 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Max AGC UL1 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Min AGC UL2 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA1 Max AGC UL2 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Min Gain DL ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Max Gain DL ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Min Gain UL1 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Max Gain UL1 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Min Gain UL2 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Max Gain UL2 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Max Freq DL ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Min Freq DL ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Min Freq UL1 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Max Freq UL1 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Min Freq UL2 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Max Freq UL2 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Max AGC DL ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Min AGC DL ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Min AGC UL1 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Max AGC UL1 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Min AGC UL2 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA2 Max AGC UL2 ',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'last_unreachable_timestamp',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Active UPLINK 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Active UPLINK 1',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Temp Downlink',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA RSSI Det V Downlink',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Output Power Detc Bm Downlink',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'PA Output Power Detc B, Downlink',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Detector Downlink',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC State Downlink',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Det Output Power Downlink',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Out Det V Downlink',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC Num State Downlink',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 1 Active',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 1 PCS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 1 CS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 2 Active',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 2 PCS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 2 CS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 3 Active',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 3 PCS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 3 CS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Active DOWNLINK 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal Active DOWNLINK 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit Active  DOWNLINK 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Valid DOWNLINK 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Active DOWNLINK 1',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal Active DOWNLINK 1',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit Active  DOWNLINK 1',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Valid DOWNLINK 1',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal Active DOWNLINK 2',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit Active  DOWNLINK 2',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Valid DOWNLINK 2',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Trim Cal Active DOWNLINK 3',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Max Gain Limit Active  DOWNLINK 3',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Gain Control Valid DOWNLINK 3',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA PA On Off Downlink 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'PA Output Power Detc B, Downlink 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA BA 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Set Gain Downlink 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Used Gain Downlink 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Detector Downlink 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC State Downlink 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC Alarm Downlink 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Out Det V Downlink 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Set Gain Uplink 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'Used Gain Uplink 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'AGC Alarm Uplink 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WBA Out Det V Uplink 0',
		'1370',
		'RF Parameters'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 4 Name',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 4 Address',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 4 Physical ID',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 4 Active',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 4 PCS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 4 CS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 5 Name',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 5 Address',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 5 Physical ID',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 5 Active',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 5 PCS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 5 CS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 6 Name',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 6 Address',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 6 Physical ID',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 6 Active',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 6 PCS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 6 CS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 7 Name',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 7 Address',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 7 Physical ID',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 7 Active',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 7 PCS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 7 CS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 8 Name',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 8 Address',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 8 Physical ID',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 8 Active',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 8 PCS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 8 CS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 9 Name',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 9 Address',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 9 Physical ID',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 9 Active',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 9 PCS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 9 CS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 10 Name',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 10 Address',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 10 Physical ID',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 10 Active',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 10 PCS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 10 CS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 11 Name',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 11 Address',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 11 Physical ID',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 11 Active',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 11 PCS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 11 CS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 12 Name',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 12 Address',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 12 Physical ID',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 12 Active',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 12 PCS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'WLI Network 12 CS',
		'1370',
		'Network'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'previous_outage',
		'1370',
		'Repeater Info'
	);

REPLACE INTO def_status_groups_map (
	status_def_variable_name,
	device_type_id,
	group_var_name
)
VALUES
	(
		'current_outage',
		'1370',
		'Repeater Info'
	);


RAWSQL
            );
            // R7.3.1 - B6710
            DB::unprepared(<<<RAWSQL
-- add Main Device Name to data_microwave Table

SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = 'data_microwave'
        AND table_schema = DATABASE()
        AND column_name = 'main_device_name'
    ) > 0,
    "SELECT 1",
    "ALTER TABLE data_microwave ADD COLUMN main_device_name VARCHAR( 255 ) NULL"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

RAWSQL
            );
            // R7.3.1 - B6725
            DB::unprepared(<<<RAWSQL
-- Phoenix Writable properties with fixed value options should be drop-down not text box

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringControl') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringControl') AND device_type_id IN (1268);


-- --------------


REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringEqualizationControl') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringEqualizationControl') AND device_type_id IN (1268);


-- --------------


REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enable' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarSensorControl') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','Measure Now' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarSensorControl') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3','Disable' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarSensorControl') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '4','Reset Admittance' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarSensorControl') AND device_type_id IN (1269);

-- ----------------


REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Normal' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarSensorLedControl') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','Led Green' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarSensorLedControl') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3','Led Red' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarSensorLedControl') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '4','Flash Green' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarSensorLedControl') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '5','Flash Red' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarSensorLedControl') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '6','Green Red' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarSensorLedControl') AND device_type_id IN (1269);


-- -----------------------

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '01','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeStatus.discharge') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '02','Major' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeStatus.discharge') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '03','Minor' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeStatus.discharge') AND device_type_id IN (1268);


-- --------------------

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '01','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeStatus.normal') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '02','Major' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeStatus.normal') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '03','Minor' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeStatus.normal') AND device_type_id IN (1268);


-- --------------------

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '01','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringSensorComStatus.faultedSensor') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '02','Major' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringSensorComStatus.faultedSensor') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '03','Minor' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringSensorComStatus.faultedSensor') AND device_type_id IN (1268);

-- --------------------

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '01','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringSensorComStatus.normal') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '02','Major' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringSensorComStatus.normal') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '03','Minor' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringSensorComStatus.normal') AND device_type_id IN (1268);


-- --------------------

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarElectrolyteLevel.low') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','Major' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarElectrolyteLevel.low') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3','Minor' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarElectrolyteLevel.low') AND device_type_id IN (1269);

-- -------------

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarElectrolyteLevel.normal') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','Major' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarElectrolyteLevel.normal') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3','Minor' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarElectrolyteLevel.normal') AND device_type_id IN (1269);

-- -------------

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarElectrolyteLevel.notAvailable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','Major' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarElectrolyteLevel.notAvailable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3','Minor' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarElectrolyteLevel.notAvailable') AND device_type_id IN (1269);

-- -------------

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarElectrolyteLevel.warning') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','Major' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarElectrolyteLevel.warning') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3','Minor' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarElectrolyteLevel.warning') AND device_type_id IN (1269);


--        -----------*******************------------------

-- --------------************ END ************---------------------


RAWSQL
            );
            // R7.3.1 - B6729
            DB::unprepared(<<<RAWSQL
-- START REMOVE THE PROP SCANNER UNTILL COMMSCOPE FIX THE PROPERTIES
 UPDATE css_networking_device_type SET prop_scan_file=null WHERE id=1548;
-- END
RAWSQL
            );
            // R7.3.1 - B6730
            DB::unprepared(<<<RAWSQL
-- remove ASCO 5140QEM from Siteportal until finalized
UPDATE css_networking_device_type SET  auto_build_enabled=0 WHERE (id='1599' and class_id='26');
RAWSQL
            );
            // R7.3.1 - B6747
            DB::unprepared(<<<RAWSQL
INSERT INTO css_networking_device_class (id, description, is_license) VALUES ('5001', 'Remote Agent', '0')
	ON DUPLICATE KEY UPDATE description = 'Remote Agent', is_license = '0', id = '5001';

UPDATE css_networking_device_type SET class_id = 5001 WHERE id in (5032, 5033, 5034);


RAWSQL
            );
            // R7.3.1 - B6748
            DB::unprepared(<<<RAWSQL
UPDATE css_networking_device_type SET class_id = 1091 WHERE id = 1376;
UPDATE css_networking_device_type SET class_id = 11 WHERE id IN (1381, 1384, 1385, 1399);
RAWSQL
            );
            // R7.3.1 - B6757
            DB::unprepared(<<<RAWSQL

-- ----------------------------------------------------------------------------------------------------------------------------------------------
-- Making Generic trap alarms acknowledgeable, since they are not self-clearing.
-- ----------------------------------------------------------------------------------------------------------------------------------------------

UPDATE css_alarms_dictionary SET can_acknowledge='1' WHERE alarm_description='Link Down' AND severity_id='6';
UPDATE css_alarms_dictionary SET can_acknowledge='1' WHERE alarm_description='Link Up'  AND severity_id='6';
UPDATE css_alarms_dictionary SET can_acknowledge='1' WHERE alarm_description='Cold Start'  AND severity_id='6';
UPDATE css_alarms_dictionary SET can_acknowledge='1' WHERE alarm_description='Warm Start'  AND severity_id='6';
UPDATE css_alarms_dictionary SET can_acknowledge='1' WHERE alarm_description='EGP NeithbourLoss'  AND severity_id='2';

-- ----------------------------------------------------------------------------------------------------------------------------------------------
-- End
-- ----------------------------------------------------------------------------------------------------------------------------------------------

RAWSQL
            );
            // R7.3.1 - B6788
            DB::unprepared(<<<RAWSQL
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.2',name='Ru LP5b Type',data_type='STRING',editable='0',prop_type_id='1',tooltip='Ru LP5b Type'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bType';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.3',name='Ru LP5b Model',data_type='STRING',editable='0',prop_type_id='1',tooltip='Ru LP5b Model'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bModel';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.4',name='Ru LP5b Code',data_type='STRING',editable='0',prop_type_id='1',tooltip='Ru LP5b Code'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bCommercialCode';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.5',name='Ru LP5b Description',data_type='STRING',editable='0',prop_type_id='1',tooltip='Ru LP5b Description'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bCommercialDescription';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.6',name='Ru LP5b Version',data_type='STRING',editable='0',prop_type_id='1',tooltip='Ru LP5b Version'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bVersion';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.7',name='Ru LP5b Name',data_type='STRING',editable='1',prop_type_id='1',tooltip='Ru LP5b Name'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bName';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.8',name='Ru LP5b Description',data_type='STRING',editable='1',prop_type_id='1',tooltip='Ru LP5b Description'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bDescription';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.11',name='Ru LP5b Serial Number',data_type='STRING',editable='0',prop_type_id='1',tooltip='Ru LP5b Serial Number'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bSerialNumber';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.12',name='Ru LP5b ProductionDate',data_type='STRING',editable='0',prop_type_id='1',tooltip='Ru LP5b ProductionDate'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bProductionDate';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.13',name='Ru LP5b Commission Date',data_type='STRING',editable='1',prop_type_id='1',tooltip='Ru LP5b Commission Date'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bCommissionDate';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.14',name='Ru LP5b Temperature',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='Ru LP5b Temperature'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bTemperature';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.15',name='RU LP5b PsuVout',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='RU LP5b PsuVout'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bPsuVout';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.16',name='RU LP5b Transmitted Power',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='RU LP5b Transmitted Power'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bPwrTx';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.17',name='RU LP5b Received Power',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='RU LP5b Received Power'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bPwrRx';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.18',name='Ru LP5b TypeName',data_type='STRING',editable='0',prop_type_id='1',tooltip='Ru LP5b TypeName'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bTypeName';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.19',name='Ru LP5b VendorId',data_type='INTEGER',editable='0',prop_type_id='1',tooltip='Ru LP5b VendorId'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bVendorId';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.20',name='Ru LP5b Fiber Loss',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='Ru LP5b Fiber Loss'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bFiberLoss';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.21',name='Ru LP5b Data Timestamp',data_type='STRING',editable='0',prop_type_id='1',tooltip='Ru LP5b Data Timestamp'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bDataTimestamp';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.22',name='Ru LP5b Data Source',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='Ru LP5b Data Source'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bDataSource';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.16.1.1.23',name='Ru LP5b Data Restore',data_type='STRING',editable='1',prop_type_id='1',tooltip='Ru LP5b Data Restore'WHERE  device_type_id='1204'AND variable_name='spvRuLp5bDataRestore';

UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.2',name='MORTX Type',data_type='STRING',editable='0',prop_type_id='1',tooltip='Motrx Type' WHERE  device_type_id='1167' AND variable_name='spvMotrxType';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.3',name='MORTX Model',data_type='STRING',editable='0',prop_type_id='1',tooltip='Motrx Model' WHERE  device_type_id='1167' AND variable_name='spvMotrxModel';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.4',name='MORTX Code',data_type='STRING',editable='0',prop_type_id='1',tooltip='Motrx Code' WHERE  device_type_id='1167' AND variable_name='spvMotrxCommercialCode';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.5',name='MORTX Description',data_type='STRING',editable='0',prop_type_id='1',tooltip='Motrx Description' WHERE  device_type_id='1167' AND variable_name='spvMotrxCommercialDescription';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.6',name='MORTX Version',data_type='STRING',editable='0',prop_type_id='1',tooltip='Motrx Version' WHERE  device_type_id='1167' AND variable_name='spvMotrxVersion';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.7',name='MORTX Serial Number',data_type='STRING',editable='0',prop_type_id='1',tooltip='Motrx Serial Number' WHERE  device_type_id='1167' AND variable_name='spvMotrxSerialNumber';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.8',name='MORTX Production Date',data_type='STRING',editable='0',prop_type_id='1',tooltip='Motrx Production Date' WHERE  device_type_id='1167' AND variable_name='spvMotrxProductionDate';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.9',name='MORTX Commission Date',data_type='STRING',editable='1',prop_type_id='1',tooltip='Motrx Commission Date' WHERE  device_type_id='1167' AND variable_name='spvMotrxCommissionDate';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.10',name='MOTRX Temperature in C',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='MOTRX Temperature in C' WHERE  device_type_id='1167' AND variable_name='spvMotrxTemperature';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.11',name='MOTRX Tx Power dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='MOTRX Tx Power dBm' WHERE  device_type_id='1167' AND variable_name='spvMotrxTxPwr';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.12',name='MOTRX Tx Enable',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='MOTRX Tx Enable' WHERE  device_type_id='1167' AND variable_name='spvMotrxTxEnable';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.13',name='MORTX Tx Att AllUser',data_type='STRING',editable='1',prop_type_id='1',tooltip=' MOTRX Tx common user attenuation dB' WHERE  device_type_id='1167' AND variable_name='spvMotrxTxAttAllUser';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.14',name='MORTX Rx Att AllUser',data_type='STRING',editable='1',prop_type_id='1',tooltip=' MOTRX Rx common user attenuation dB' WHERE  device_type_id='1167' AND variable_name='spvMotrxRxAttAllUser';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.15',name='MORTX Type Name',data_type='STRING',editable='0',prop_type_id='1',tooltip='Motrx Type Name' WHERE  device_type_id='1167' AND variable_name='spvMotrxTypeName';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.16',name='MORTX VendorId',data_type='INTEGER',editable='0',prop_type_id='1',tooltip='Motrx VendorId' WHERE  device_type_id='1167' AND variable_name='spvMotrxVendorId';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.17',name='MORTX Data Timestamp',data_type='STRING',editable='0',prop_type_id='1',tooltip='Motrx Data Timestamp' WHERE  device_type_id='1167' AND variable_name='spvMotrxDataTimestamp';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.18',name='MORTX Data Source',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='Motrx Data Source' WHERE  device_type_id='1167' AND variable_name='spvMotrxDataSource';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.1.1.19',name='MORTX Data Restore',data_type='STRING',editable='1',prop_type_id='1',tooltip='Motrx Data Restore' WHERE  device_type_id='1167' AND variable_name='spvMotrxDataRestore';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.2.1.2',name='MOTRX Receiver Power dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip=' MOTRX receiver power dBm' WHERE  device_type_id='1988' AND variable_name='spvMotrxRxPwrCh';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.2.1.3',name='MOTRX Receiver Enable',data_type='INTEGER',editable='1',prop_type_id='1',tooltip=' MOTRX Receiver Enable' WHERE  device_type_id='1988' AND variable_name='spvMotrxRxEnableCh';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.2.1.4',name='MOTRX Receiver Att User',data_type='STRING',editable='1',prop_type_id='1',tooltip='MotrxRx Att User' WHERE  device_type_id='1988' AND variable_name='spvMotrxRxAttUserCh';
UPDATE css_networking_device_prop_def SET snmp_oid='.1.3.6.1.4.1.6626.6000.1.6.2.1.5',name='MOTRX Receiver Fiber Loss',data_type='INTEGER',editable='0',prop_type_id='1',tooltip='MotrxRx Fiber Loss' WHERE  device_type_id='1988' AND variable_name='spvMotrxRxFiberLossCh';


UPDATE css_networking_device_prop_def SET snmp_oid='',name='Type',data_type='STRING',editable='0',prop_type_id='1',tooltip='Type' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayType';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='Model',data_type='STRING',editable='0',prop_type_id='1',tooltip='Model' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayModel';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='Version',data_type='STRING',editable='0',prop_type_id='1',tooltip='Version' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayVersion';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='SerialNumber',data_type='STRING',editable='0',prop_type_id='1',tooltip='SerialNumber' WHERE  device_type_id=' 2009' AND variable_name='spvDasTraySerialNumber';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='Name',data_type='STRING',editable='1',prop_type_id='1',tooltip='Name' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayName';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='Temperature',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='Temperature' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayTemperature';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Mec Bts Dl Ch1 attenuation dB',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='DasTray Mec Bts Dl Ch1 attenuation dB' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayAttMecBtsDl1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Mec Bts Dl Ch2attenuation dB',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='DasTray Mec Bts Dl Ch2attenuation dB' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayAttMecBtsDl2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dig Bts In Dl Ch1 attenuation dB',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='DasTray Dig Bts In Dl Ch1 attenuation dB' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayAttDigBtsInDl1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dig Bts In Dl Ch2 attenuation dB',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='DasTray Dig Bts In Dl Ch2attenuation dB' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayAttDigBtsInDl2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dig Das Out Dl Ch1 attenuation dB',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='DasTray Dig Das Out Dl Ch1 attenuation dB' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayAttDigDasOutDl1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dig Das Out Dl Ch2 attenuation dB',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='DasTray Dig Das Out Dl Ch2attenuation dB' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayAttDigDasOutDl2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dig Das In Ul Ch1 attenuation dB',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='DasTray Dig Das In Ul Ch1 attenuation dB' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayAttDigDasInUl1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dig Das In Ul Ch2 attenuation dB',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='DasTray Dig Das In Ul Ch2attenuation dB' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayAttDigDasInUl2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dig Bts Main Ul Ch1 attenuation dB',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='DasTray Dig Bts Main Ul Ch1 attenuation dB' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayAttDigBtsMainUl1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dig Bts Main Ul Ch2attenuation dB',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='DasTray Dig Bts Main Ul Ch2attenuation dB' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayAttDigBtsMainUl2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dig Bts Div Ul Ch1 attenuation dB',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='DasTray Dig Bts Div Ul Ch1 attenuation dB' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayAttDigBtsDivUl1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dig Bts Div Ul Ch2 attenuation dB',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='DasTray Dig Bts Div Ul Ch2attenuation dB' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayAttDigBtsDivUl2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dl RMS power Ch1 dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='DasTray Dl RMS power Ch1 dBm' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayRmsPwrDl1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dl RMS power Ch2 dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='DasTray Dl RMS power Ch2 dBm' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayRmsPwrDl2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dl peak power Ch1 dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='DasTray Dl peak power Ch1 dBm' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayPeakPwrDl1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Dl peak power Ch2 dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='DasTray Dl peak power Ch2 dBm' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayPeakPwrDl2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Ul RMS power Ch1 dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='DasTray Ul RMS power Ch1 dBm' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayRmsPwrUl1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Ul RMS power Ch2 dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='DasTray Ul RMS power Ch2 dBm' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayRmsPwrUl2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Ul RMS power div Ch1 dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='DasTray Ul RMS power div Ch1 dBm' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayRmsPwrDivUl1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Ul RMS power div Ch2 dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='DasTray Ul RMS power div Ch2 dBm' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayRmsPwrDivUl2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Ul peak power Ch1 dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='DasTray Ul peak power Ch1 dBm' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayPeakPwrUl1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Ul peak power Ch2 dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='DasTray Ul peak power Ch2 dBm' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayPeakPwrUl2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Ul RMS power div Ch1 dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='DasTray Ul RMS power div Ch1 dBm' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayPeakPwrDivUl1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Ul RMS power div Ch2 dBm',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='DasTray Ul RMS power div Ch2 dBm' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayPeakPwrDivUl2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Power Hysteresis',data_type='INTEGER',editable='1',prop_type_id='1',tooltip='DasTray power hysteresis' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayPwrAlarmHysteresis';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Preset menu',data_type='STRING',editable='1',prop_type_id='1',tooltip='DasTray preset menu' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayPresetMenu';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='LNA Ch1 On/Off',data_type='INTEGER',editable='1',prop_type_id='1',tooltip=' switch LNA enable Ch1' WHERE  device_type_id=' 2009' AND variable_name='spvDasTraySwitchLna1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='LNA Ch2 On/Off',data_type='INTEGER',editable='1',prop_type_id='1',tooltip=' switch LNA enable Ch2' WHERE  device_type_id=' 2009' AND variable_name='spvDasTraySwitchLna2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Pwr Limiter',data_type='STRING',editable='1',prop_type_id='1',tooltip='spvDasTrayPwrLimiter' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayPwrLimiter';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='DasTray Power Limiter Mode menu',data_type='STRING',editable='1',prop_type_id='1',tooltip='DasTray power limiter mode menu' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayPwrLimiterMode';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='THR RMS PWR MIN DL Ch1',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='THR RMS PWR MIN DL Ch1' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayMinRmsPwrDlThr1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='THR RMS PWR MIN DL Ch2',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='THR RMS PWR MIN DL Ch2' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayMinRmsPwrDlThr2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='THR RMS PWR MAX DL Ch1',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='THR RMS PWR MAX DL Ch1' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayMaxRmsPwrDlThr1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='THR RMS PWR MAX DL Ch2',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='THR RMS PWR MAX DL Ch2' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayMaxRmsPwrDlThr2';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='THR PEAK PWR MAX DL Ch1',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='THR PEAK PWR MAX DL Ch1' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayMaxPeakPwrDlThr1';
UPDATE css_networking_device_prop_def SET snmp_oid='',name='THR PEAK PWR MAX DL Ch2 ',data_type='INTEGER',editable='0',prop_type_id='2',tooltip='THR PEAK PWR MAX DL Ch2 ' WHERE  device_type_id=' 2009' AND variable_name='spvDasTrayMaxPeakPwrDlThr2';


UPDATE css_networking_device_prop_def SET data_type='INTEGER' WHERE  device_type_id='2013' AND variable_name like '%PwrDlCh%';
UPDATE css_networking_device_prop_def SET name='Channel 1 UL Att',editable='1',prop_type_id='1',tooltip='Channel 1 UL Att'WHERE  device_type_id='2013'AND variable_name='spvRuLp5bAttUlCh1';
UPDATE css_networking_device_prop_def SET prop_type_id='1' WHERE  device_type_id='2013'AND variable_name like '%spvRuLp5bAttUlCh%';
UPDATE css_networking_device_prop_def SET prop_type_id='1' WHERE  device_type_id='2013'AND variable_name like '%spvRuLp5bAttDlCh%';
UPDATE css_networking_device_prop_def SET name='Channel1 Rf Enable',editable='1',prop_type_id='1',tooltip='Channel1 Rf Enable' WHERE  device_type_id='2013'AND variable_name='spvRuLp5bRfEnableCh1';
UPDATE css_networking_device_prop_def SET name='Channel2 Rf Enable',editable='1',prop_type_id='1',tooltip='Channel2 Rf Enable' WHERE  device_type_id='2013'AND variable_name='spvRuLp5bRfEnableCh2';
UPDATE css_networking_device_prop_def SET name='Channel3 Rf Enable',editable='1',prop_type_id='1',tooltip='Channel3 Rf Enable' WHERE  device_type_id='2013'AND variable_name='spvRuLp5bRfEnableCh3';
UPDATE css_networking_device_prop_def SET name='Channel4 Rf Enable',editable='1',prop_type_id='1',tooltip='Channel4 Rf Enable' WHERE  device_type_id='2013'AND variable_name='spvRuLp5bRfEnableCh4';
UPDATE css_networking_device_prop_def SET name='Channel5 Rf Enable',editable='1',prop_type_id='1',tooltip='Channel5 Rf Enable' WHERE  device_type_id='2013'AND variable_name='spvRuLp5bRfEnableCh5';


RAWSQL
            );
            // R7.3.1 - B6789
            DB::unprepared(<<<RAWSQL
/***********Create status groups***********/
REPLACE INTO def_status_groups (group_var_name, group_breadcrumb) VALUES (
'Network', 'Network');

REPLACE INTO def_status_groups (group_var_name, group_breadcrumb) VALUES (
'RF Parameters', 'RF Parameters');

REPLACE INTO def_status_groups (group_var_name, group_breadcrumb) VALUES (
'Repeater Info', 'Repeater Info');

REPLACE INTO def_status_groups (group_var_name, group_breadcrumb) VALUES (
'General', 'General');

/***********FON prop grouping***********/
REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('original_name', '1369', 'General');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repeater Name', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware ID', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Modem Param Speed', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Network ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Node Configutration Network ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Alarm Intensity', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Modem Init String', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Dialing Method', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Wire Line Interface Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Wire Line Interface Net-Mask Address', '1369',
'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FON Interface Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FON Interface Gateway Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Name Service Units Address Primary', '1369', 'Network')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Modem Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repeater Network ID', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Transmitter Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Receiver Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Receiver Alarm Level', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Fiber Optical Enable', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Optical Signal Strength Warning Level', '1369',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Optical Signal Strength Error Level', '1369',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('RF Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter RF Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmission Part Adjustable RF Attenuation', '1369',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware Flags', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Type-1', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Address-1', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Gain Downlink-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Gain Downlink-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency Downlink-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency Downlink-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency Uplink1-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency Uplink1-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum AGC Downlink-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum AGC Downlink-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum AGC Uplink1-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum AGC Uplink1-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain for Amplifier Strip-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Computed Minimum Gain-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Computed Maximum Gain-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Attenuation-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Attenuation-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Software AGC Det Limit-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Gain Limit-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain for Amplifier Strip-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Computed Minimum Gain-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Computed Maximum Gain-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Attenuation-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Attenuation-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop Time Constant-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Factor High-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Factor Low-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Factor High-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Factor Low-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware AGC Det Limit-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Software AGC Det Limit-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Max Gain-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Calibration-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Calibration Str-5', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Calibration-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Calibration Str-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware Minimum Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware Maximum Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency ID', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency ID', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Bandwidth', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Bandwidth', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Low Frequency Downlink', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Low Frequency Uplink', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Bsel Repeater Bandwidth', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Repeater Uplink Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Repeater Downlink Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BSC Gain Control Recovery Time', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BSC Gain Control Recovery Gain.', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Hardware min freaquency uplink', '1369',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Hardware max freaquency uplink', '1369',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Hardware Duplex Distance', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Hardware Duplex Dist attenuation', '1369',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BSC Gain Control Recovery Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('The High Frequency value of Downlink', '1369',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('The High Frequency value of Uplink', '1369', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency Uplink2-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency Uplink2-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum AGC Uplink2-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum AGC Uplink2-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain for Amplifier Strip-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Computed Minimum Gain-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Computed Maximum Gain-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Attenuation-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Attenuation-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop Time Constant-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Factor High-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Factor Low-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Factor High-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Factor Low-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware AGC Det Limit-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Software AGC Det Limit-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Max Gain-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Calibration Str-9', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Mounted-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Mounted-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Diversity Mounted-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink2 Mounted-1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkEnable WRH', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkSoftware AGC', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkHardware AGC', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkGain for Amplifier Strip', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkComputed Minimum Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkComputed Maximum Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkInput Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkOutput Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkDown Reg Alarm', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkThreshold Value', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkMode', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkDefault Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkLoop Time Constant', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkAttac Factor High', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkAttac Factor Low', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkDecay Factor High', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkDecay Factor Low', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkHardware AGC Det Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkSoftware AGC Det Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkLNA Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkLNA Max Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkMaximum Gain Linit Active', '1369', 'RFconfig')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkGain Trim Calibration Active', '1369',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkMaximum Gain Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkGain Trim Calibration', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkBoard Number', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkGain Trim Calibration Str', '1369', 'RFconfig')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkEnable WRH', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkSoftware AGC', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkHardware AGC', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkGain for Amplifier Strip', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkComputed Minimum Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkComputed Maximum Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkInput Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkOutput Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkDown Reg Alarm', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkThreshold Value', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkMode', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkDefault Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkLoop Time Constant', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkAttac Factor High', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkAttac Factor Low', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkDecay Factor High', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkDecay Factor Low', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkHardware AGC Det Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkSoftware AGC Det Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkLNA Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkLNA Max Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkMaximum Gain Linit Active', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkGain Trim Calibration Active', '1369', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkMaximum Gain Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkGain Trim Calibration', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkBoard Number', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkGain Trim Calibration Str', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Type', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Address', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Gain Downlink', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Gain Downlink', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency Downlink', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency Downlink', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency Uplink1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency Uplink1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency Uplink2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency Uplink2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum AGC Downlink', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum AGC Downlink', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum AGC Uplink1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum AGC Uplink1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum AGC Uplink2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum AGC Uplink2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Mounted', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Mounted', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Diversity Mounted', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink2 Mounted', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Enable WRH', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Software AGC', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Hardware AGC', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Gain for Amplifier Strip', '1369', 'RFconfig')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Computed Minimum Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Computed Maximum Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Input Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Output Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Down Reg Alarm', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Threshold Value', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Mode', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Default Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Loop Time Constant', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Attac Factor High', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Attac Factor Low', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Decay Factor High', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Decay Factor Low', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Hardware AGC Det Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Software AGC Det Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink LNA Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink LNA Max Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Maximum Gain Linit Active', '1369', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Gain Trim Calibration Active', '1369',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Maximum Gain Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Gain Trim Calibration', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Board Number', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Gain Trim Calibration Str', '1369', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Enable WRH', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Software AGC', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Hardware AGC', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Gain for Amplifier Strip', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Computed Minimum Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Computed Maximum Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Input Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Output Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Down Reg Alarm', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Threshold Value', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Mode', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Default Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Loop Time Constant', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Attac Factor High', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Attac Factor Low', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Decay Factor High', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Decay Factor Low', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Hardware AGC Det Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Software AGC Det Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink LNA Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink LNA Max Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Maximum Gain Linit Active', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Gain Trim Calibration Active', '1369',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Maximum Gain Limit', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Gain Trim Calibration', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Board Number', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Gain Trim Calibration Str', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Computed  Minimum Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Maximum Gain Limit Active', '1369', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Computed  Minimum Gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Maximum Gain Limit Active', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink WBA', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink PA', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink BA', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink WBA', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink PA', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink BA', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Disable AFC', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Suppress RX Alarms', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Supress RX Alarms', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('ISG IP Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repeater Port', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repeater Mac', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repeater Ip', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Type', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Base Cover Info', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('MrxType', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Modem Type', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('OSI Link', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('F2F Link', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Modem2 Type', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Capabilities', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW Capabilities', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Wavelength Centi', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Receiver Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Receiver Alarm Level', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiver Diode Type', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Laser Type', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Fixed Transmitter Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Flags', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Fiber Optical Powersave', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiver Warning Level', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiver Error Level', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiver Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Attenuation', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Disable Afc', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Xemics Flag Freq', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Is MIMO Enabled', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mimo Primary Flag', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mimo ID', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Synch', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Version', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Reciever Level', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Ip Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Gateway', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Mask', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Ip Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Gateway', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Mask', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Primary NS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Secondary NS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('first_unreachable_timestamp', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('unreachable_Threshold', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('OMS Primary', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('OMS Secondary', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Type', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Base Cover Info', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Mrx Type', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Modem Type', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater OSI Link', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater F2F Link', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Modem2 Type', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Accessory', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Capabilities', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater SW Capabilities', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Type', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min Gain DL ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max Gain DL ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min Gain UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max Gain UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min Gain UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max Gain UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max Freq DL ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min Freq DL ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min Freq UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max Freq UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min Freq UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max Freq UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 WBA Addr ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 BA Addr ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max AGC DL ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min AGC DL ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min AGC UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max AGC UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min AGC UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max AGC UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max LNA Att UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max LNA Att UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Type', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min Gain DL ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max Gain DL ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min Gain UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max Gain UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min Gain UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max Gain UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max Freq DL ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min Freq DL ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min Freq UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max Freq UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min Freq UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max Freq UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 WBA Addr ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 BA Addr ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max AGC DL ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min AGC DL ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min AGC UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max AGC UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min AGC UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max AGC UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max LNA Att UL1 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max LNA Att UL2 ', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 0 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 0 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 0 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 0 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 0 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 0 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidDOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  DOWNLINK 0', '1369', 'RFconfig')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid DOWNLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidDOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  DOWNLINK 1', '1369', 'RFconfig')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid DOWNLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidDOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  DOWNLINK 2', '1369', 'RFconfig')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid DOWNLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidDOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  DOWNLINK 3', '1369', 'RFconfig')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid DOWNLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA On Off Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Temp Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA RSSI Det V Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Output Power Detc Bm Downlink 0', '1369',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA PA On Off Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('PA Output Power Detc B, Downlink 0', '1369', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA BA 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BA Output Power Detc Bm Downlink 0', '1369', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Set Gain Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Control To Gain Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Used Gain Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Detector Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Threshold Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC State Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC Alarm Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Attenuation Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Attenuation Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Det Output Power Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Detected System Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Nom Gain Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Out Det V Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Det Lim SW Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Det Lim HW Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Running Mode Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Alarm Flag Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Bit Status Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('PA Gain Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('PA Det V Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('PA Det Lim Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BA Nom Gain Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BA Det V Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BA Det Lim Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC Mode Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC Num State Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop Time Constant Downlink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Constant 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Constant 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidUPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid UPLINK 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidUPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid UPLINK 1', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidUPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid UPLINK 2', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidUPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid UPLINK 3', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA On Off Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Temp Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA RSSI Det V Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Output Power Detc Bm Uplink 0', '1369', 'RFconfig')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Set Gain Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Control To Gain Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Used Gain Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Threshold Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC State Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC Alarm Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Attenuation Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Attenuation Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Max Gain Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Attenuation Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Detected System Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Nom Gain Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Out Det V Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Det Lim SW Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Det Lim HW Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Running Mode Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Alarm Flag Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Bit Status Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC Mode Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC Num State Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop Time Constant Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Constant Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Constant Uplink 0', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 1 DL min gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 1 DL max gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 1 UL min gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 1 UL max gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 2 DL min gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 2 DL max gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 2 UL min gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 2 UL max gain', '1369', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 Name', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 Address', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 Physical ID', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 Active', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 PCS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 CS', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('\$repeater->unreachable', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('\$hourdiff', '1369', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('ModemAddress', '1369', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('smnp_access', '1369', 'Network');

/***********OCM prop grouping***********/
REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('original_name', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repeater Name', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware ID', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Modem Param Speed', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Network ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Node Configutration Network ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Alarm Intensity', '1368', 'General');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Modem Init String', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Dialing Method', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Wire Line Interface Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Wire Line Interface Net-Mask Address', '1368',
'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FON Interface Address', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FON Interface Gateway Address', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Name Service Units Address Primary', '1368', 'Network')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Modem Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repeater Network ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Fiber Optical Enable', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Optical Signal Strength Warning Level', '1368',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Optical Signal Strength Error Level', '1368',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('RF Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter RF Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmission Part Adjustable RF Attenuation', '1368',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware Flags', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Type-1', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Address-1', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Gain Downlink-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Gain Downlink-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency Downlink-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency Downlink-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency Uplink1-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency Uplink1-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum AGC Downlink-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum AGC Downlink-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum AGC Uplink1-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum AGC Uplink1-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain for Amplifier Strip-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Computed Minimum Gain-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Computed Maximum Gain-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Attenuation-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Attenuation-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Software AGC Det Limit-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Gain Limit-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain for Amplifier Strip-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Computed Minimum Gain-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Computed Maximum Gain-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Attenuation-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Attenuation-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop Time Constant-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Factor High-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Factor Low-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Factor High-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Factor Low-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware AGC Det Limit-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Software AGC Det Limit-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Max Gain-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Calibration-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Calibration Str-5', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Calibration-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Calibration Str-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware Minimum Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware Maximum Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency ID', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency ID', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Bandwidth', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Bandwidth', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Low Frequency Downlink', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Low Frequency Uplink', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Bsel Repeater Bandwidth', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Repeater Uplink Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Repeater Downlink Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BSC Gain Control Recovery Time', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BSC Gain Control Recovery Gain.', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Hardware min freaquency uplink', '1368',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Hardware max freaquency uplink', '1368',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Hardware Duplex Distance', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Hardware Duplex Dist attenuation', '1368',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BSC Gain Control Recovery Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('The High Frequency value of Downlink', '1368',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('The High Frequency value of Uplink', '1368', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency Uplink2-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency Uplink2-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum AGC Uplink2-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum AGC Uplink2-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain for Amplifier Strip-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Computed Minimum Gain-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Computed Maximum Gain-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Attenuation-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Attenuation-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop Time Constant-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Factor High-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Factor Low-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Factor High-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Factor Low-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware AGC Det Limit-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Software AGC Det Limit-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Max Gain-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Calibration Str-9', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Mounted-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Mounted-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Diversity Mounted-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink2 Mounted-1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkEnable WRH', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkSoftware AGC', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkHardware AGC', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkGain for Amplifier Strip', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkComputed Minimum Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkComputed Maximum Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkInput Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkOutput Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkDown Reg Alarm', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkThreshold Value', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkMode', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkDefault Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkLoop Time Constant', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkAttac Factor High', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkAttac Factor Low', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkDecay Factor High', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkDecay Factor Low', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkHardware AGC Det Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkSoftware AGC Det Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkLNA Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkLNA Max Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkMaximum Gain Linit Active', '1368', 'RFconfig')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkGain Trim Calibration Active', '1368',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkMaximum Gain Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkGain Trim Calibration', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkBoard Number', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkGain Trim Calibration Str', '1368', 'RFconfig')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkEnable WRH', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkSoftware AGC', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkHardware AGC', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkGain for Amplifier Strip', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkComputed Minimum Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkComputed Maximum Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkInput Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkOutput Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkDown Reg Alarm', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkThreshold Value', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkMode', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkDefault Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkLoop Time Constant', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkAttac Factor High', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkAttac Factor Low', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkDecay Factor High', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkDecay Factor Low', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkHardware AGC Det Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkSoftware AGC Det Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkLNA Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkLNA Max Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkMaximum Gain Linit Active', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkGain Trim Calibration Active', '1368', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkMaximum Gain Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkGain Trim Calibration', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkBoard Number', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkGain Trim Calibration Str', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Type', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Address', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Gain Downlink', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Gain Downlink', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency Downlink', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency Downlink', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency Uplink1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency Uplink1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Frequency Uplink2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Frequency Uplink2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum AGC Downlink', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum AGC Downlink', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum AGC Uplink1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum AGC Uplink1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum AGC Uplink2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum AGC Uplink2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Mounted', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Mounted', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Diversity Mounted', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink2 Mounted', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Enable WRH', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Software AGC', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Hardware AGC', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Gain for Amplifier Strip', '1368', 'RFconfig')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Computed Minimum Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Computed Maximum Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Input Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Output Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Down Reg Alarm', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Threshold Value', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Mode', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Default Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Loop Time Constant', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Attac Factor High', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Attac Factor Low', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Decay Factor High', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Decay Factor Low', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Hardware AGC Det Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Software AGC Det Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink LNA Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink LNA Max Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Maximum Gain Linit Active', '1368', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Gain Trim Calibration Active', '1368',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Maximum Gain Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Gain Trim Calibration', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Board Number', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Gain Trim Calibration Str', '1368', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Enable WRH', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Software AGC', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Hardware AGC', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Gain for Amplifier Strip', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Computed Minimum Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Computed Maximum Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Input Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Output Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Down Reg Alarm', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Threshold Value', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Mode', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Default Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Loop Time Constant', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Attac Factor High', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Attac Factor Low', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Decay Factor High', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Decay Factor Low', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Hardware AGC Det Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Software AGC Det Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink LNA Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink LNA Max Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Maximum Gain Linit Active', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Gain Trim Calibration Active', '1368',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Maximum Gain Limit', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Gain Trim Calibration', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Board Number', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Gain Trim Calibration Str', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Computed  Minimum Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Maximum Gain Limit Active', '1368', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Computed  Minimum Gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Maximum Gain Limit Active', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink WBA', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink PA', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink BA', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink WBA', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink PA', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink BA', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Disable AFC', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Suppress RX Alarms', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Supress RX Alarms', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('ISG IP Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repeater Port', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repeater Mac', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repeater Ip', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Type', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Base Cover Info', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('MrxType', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Modem Type', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('OSI Link', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('F2F Link', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Modem2 Type', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Capabilities', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW Capabilities', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Wavelength Centi', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Reciever Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Reciever Alarm Level', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiver Diode Type', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Laser Type', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Fixed Transmitter Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Flags', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Fiber Optical Powersave', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiver Warning Level', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiver Error Level', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiver Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Attenuation', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Disable Afc', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Xemics Flag Freq', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Is MIMO Enabled', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mimo Primary Flag', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mimo ID', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Synch', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Version', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Reciever Level', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Ip Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Gateway', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Mask', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Ip Address', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Gateway', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Mask', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Primary NS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Secondary NS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('unreachable_Threshold', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('OMS Primary', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('OMS Secondary', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Type', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Base Cover Info', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Mrx Type', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Modem Type', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater OSI Link', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater F2F Link', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Modem2 Type', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Accessory', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater Capabilities', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Repater SW Capabilities', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Type', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min Gain DL', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max Gain DL', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min Gain UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max Gain UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min Gain UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max Gain UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max Freq DL', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min Freq DL', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min Freq UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max Freq UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min Freq UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max Freq UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 WBA Addr', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 BA Addr', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max AGC DL', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min AGC DL', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min AGC UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max AGC UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Min AGC UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max AGC UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max LNA Att UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA1 Max LNA Att UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Type', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min Gain DL', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max Gain DL', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min Gain UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max Gain UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min Gain UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max Gain UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max Freq DL', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min Freq DL', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min Freq UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max Freq UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min Freq UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max Freq UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 WBA Addr', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 BA Addr', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max AGC DL', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min AGC DL', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min AGC UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max AGC UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Min AGC UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max AGC UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max LNA Att UL1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA2 Max LNA Att UL2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 0 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 0 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 0 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 0 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 0 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 0 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidDOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  DOWNLINK 0', '1368', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid DOWNLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidDOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  DOWNLINK 1', '1368', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid DOWNLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidDOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  DOWNLINK 2', '1368', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid DOWNLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidDOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  DOWNLINK 3', '1368', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid DOWNLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA On Off Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Temp Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA RSSI Det V Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Output Power Detc Bm Downlink 0', '1368',
'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA PA On Off Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('PA Output Power Detc B, Downlink 0', '1368', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA BA 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BA Output Power Detc Bm Downlink 0', '1368', 'RFconfig'
);

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Set Gain Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Control To Gain Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Used Gain Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Detector Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Threshold Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC State Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC Alarm Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Attenuation Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Attenuation Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Det Output Power Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Detected System Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Nom Gain Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Out Det V Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Det Lim SW Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Det Lim HW Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Running Mode Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Alarm Flag Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Bit Status Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('PA Gain Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('PA Det V Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('PA Det Lim Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BA Nom Gain Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BA Det V Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('BA Det Lim Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC Mode Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC Num State Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop Time Constant Downlink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Constant 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Constant 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidUPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid UPLINK 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidUPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid UPLINK 1', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidUPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid UPLINK 2', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Att UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Att UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Threshold UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Down Reg Alarm UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Active UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Mode UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Use Set Lim UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac High UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Low UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay High UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Low UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('HW AGC Det Lim UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Det Lim UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Att UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Nom Gain UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control ValidUPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Flags UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Trim Cal Active UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Max Gain Limit Active  UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Gain Control Valid UPLINK 3', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA On Off Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Temp Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA RSSI Det V Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Output Power Detc Bm Uplink 0', '1368', 'RFconfig')
;

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Set Gain Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Control To Gain Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Used Gain Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('SW AGC Threshold Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC State Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC Alarm Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Input Attenuation Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Output Attenuation Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Max Gain Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('LNA Attenuation Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Detected System Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Nom Gain Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Out Det V Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Det Lim SW Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Det Lim HW Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Running Mode Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Alarm Flag Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA Bit Status Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC Mode Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('AGC Num State Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Loop Time Constant Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Attac Constant Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('Decay Constant Uplink 0', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 1 DL min gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 1 DL max gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 1 UL min gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 1 UL max gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 2 DL min gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 2 DL max gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 2 UL min gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WBA 2 UL max gain', '1368', 'RFconfig');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 Name', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 Address', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 Physical ID', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 Active', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 PCS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 CS', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('\$repeater->unreachable', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('\$hourdiff', '1368', 'Repeater Info');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('ModemAddress', '1368', 'Network');

REPLACE INTO def_prop_groups_map (prop_def_variable_name, device_type_id,
group_var_name) VALUES ('smnp_access', '1368', 'Network');

/***********FON status grouping***********/
REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Control Station Capability Enabled', '1369',
'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Alarm Output Level', '1369', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Date', '1369', 'General');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Time', '1369', 'General');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Statistic Collection Time Started', '1369',
'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Local Time', '1369', 'General');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitted Messages', '1369', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Received Messages', '1369', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Retransmitted Messges', '1369', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Lost Messages', '1369', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bytes Transmitted', '1369', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bytes Received', '1369', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bytes Retransmitted', '1369', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Error Rate of Bytes', '1369', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Transmitter Attenuation', '1369',
'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Wavelength', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Fiber Optical Powersaver', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Sub Carrier Frequency', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Optical Signal Strength Warning Level', '1369',
'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Optical Signal Strength Error Level', '1369',
'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Low Power Level', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Power', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Bandwidth', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Hardware Frequency Step(change)', '1369',
'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Hardware Bandwidth Step(change)', '1369',
'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkParameter Status', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkParameter Status', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Parameter Status', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Parameter Status', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink WBA ONOFF', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink PA ONOFF', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink BA ONOFF', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink WBA ONOFF', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink PA ONOFF', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink BA ONOFF', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Optical Power', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('General Status', '1369', 'General');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Fon Receiver Status', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiever Level', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Status', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Level', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiver Status', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiver Level', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Frequency Step(change)', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bandwidth Step(change)', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Reciever Attenuation', '1369', 'RF Parameters')
;

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Reciever Alarm Level', '1369', 'RF Parameters')
;

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Laser Transmitter Power', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Reciever Status', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Reciever Level', '1369', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('last_unreachable_timestamp', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 1 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 2 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 3 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 4 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 5 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 6 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 7 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 8 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 9 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 1 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 2 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 10 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 11 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 12 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 3 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 0 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 0 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 0 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 0 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 0 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 0 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 15 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 15 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 15 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 15 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 15 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 15 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI Network 13 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 4 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 5 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 6 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 7 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 8 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 9 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 10 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 11 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 Name', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 Address', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 Physical ID', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 Active', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 PCS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI Network 12 CS', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('previous_outage', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('current_outage', '1369', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('\$repeater->unreachable', '1369', 'Network');

/***********OCM status grouping***********/
REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLMRX Information', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Accessory', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('WLI information', '1368', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('FLI information', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Current Application', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Boot-Software Verison', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('CU Application 1 Version', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('CU Application 2 Version', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('CU Software Cuboard Version', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('CU Software Serial Number', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('CU Software Year', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('CU Software Week', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('CU Software Type', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('CU Software Flash Size', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Node Config Current Role', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Control Station Capability Enabled', '1368',
'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Alarm Output Level', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Physical ID', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Date', '1368', 'General');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Time', '1368', 'General');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Statistic Collection Time Started', '1368',
'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Local Time', '1368', 'General');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitted Messages', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Received Messages', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Retransmitted Messges', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Lost Messages', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bytes Transmitted', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bytes Received', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bytes Retransmitted', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Error Rate of Bytes', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Receiver Attenuation', '1368', 'RF Parameters')
;

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Receiver Attenuation', '1368', 'RF Parameters')
;

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Transmitter Attenuation', '1368',
'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Transmitter Attenuation', '1368',
'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Minimum Receiver Alarm Level', '1368', 'Repeater Info')
;

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Maximum Receiver Alarm Level', '1368', 'Repeater Info')
;

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware Receiver Type', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware Transmitter Type', '1368', 'Repeater Info');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Hardware Sub Carrier Frequency', '1368',
'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Wavelength', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Fixed Transmission Attenuation', '1368',
'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Fiber Optical Powersaver', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Sub Carrier Frequency', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiver Optical Detector Diode Type', '1368',
'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Optical Detector Diode Type', '1368',
'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Low Power Level', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Power', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Bandwidth', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Hardware Frequency Step(change)', '1368',
'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bsel Hardware Bandwidth Step(change)', '1368',
'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('DownlinkParameter Status', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('UplinkParameter Status', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink Parameter Status', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink Parameter Status', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink WBA ONOFF', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink PA ONOFF', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Downlink BA ONOFF', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink WBA ONOFF', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink PA ONOFF', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Uplink BA ONOFF', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Optical Power', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('General Status', '1368', 'General');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiver Status', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiver Level', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Status', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Transmitter Level', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Receiever Level', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Fon Receiver Status', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Frequency Step(change)', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Bandwidth Step(change)', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Laser Transmitter Power', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Low Power Transmitter', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('Reciever Status', '1368', 'RF Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('first_unreachable_timestamp', '1368', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('last_unreachable_timestamp', '1368', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('previous_outage', '1368', 'Network');

REPLACE INTO def_status_groups_map (status_def_variable_name, device_type_id,
group_var_name) VALUES ('current_outage', '1368', 'Network');
RAWSQL
            );
            // R7.3.1 - B6795
            DB::unprepared(<<<RAWSQL

-- add new STATUS groups :

REPLACE INTO def_status_groups VALUES ('String Info', 'String Info');
REPLACE INTO def_status_groups VALUES ('Jar Info', 'Jar Info');
REPLACE INTO def_status_groups VALUES ('BC Info', 'BC Info');
REPLACE INTO def_status_groups VALUES ('Device Info', 'Device Info');

-- add new PROPERTIES groups :

REPLACE INTO def_prop_groups VALUES ('Jar Voltage Delta', 'Jar Voltage Delta');
REPLACE INTO def_prop_groups VALUES ('String Voltage', 'String Voltage');
REPLACE INTO def_prop_groups VALUES ('Device Info', 'Device Info');
REPLACE INTO def_prop_groups VALUES ('String Info', 'String Info');




-- Main Device Props :
REPLACE INTO def_prop_groups_map  VALUES ('Firmware', 5030, 'Device Info');
REPLACE INTO def_prop_groups_map  VALUES ('original_name', 5030, 'Device Info');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatFirmVer', 5030, 'Device Info');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatHardVer', 5030, 'Device Info');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatLocation', 5030, 'Device Info');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatMacAddress', 5030, 'Device Info');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatManufName', 5030, 'Device Info');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatMeasurementInterval', 5030, 'Device Info');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatModelName', 5030, 'Device Info');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatSiteName', 5030, 'Device Info');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatSoftVer', 5030, 'Device Info');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatStrings', 5030, 'Device Info');

-- Main Device Status :
REPLACE INTO def_status_groups_map  VALUES ('pbtBatSystemUptime', 5030, 'Device Info');
REPLACE INTO def_status_groups_map  VALUES ('pbtCellMetrixSystemStatus', 5030, 'Device Info');


-- PROPS goes under Others:

REPLACE INTO def_prop_groups_map  VALUES ('pbtBatStringJarVoltageDelta.alarmEnable', 1268, 'Jar Voltage Delta');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatStringJarVoltageDelta.analogAlarmDeadband', 1268, 'Jar Voltage Delta');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatStringJarVoltageDelta.analogAlarmHI', 1268, 'Jar Voltage Delta');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatStringJarVoltageDelta.analogAlarmHIHI', 1268, 'Jar Voltage Delta');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatStringJarVoltageDelta.analogAlarmLO', 1268, 'Jar Voltage Delta');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatStringJarVoltageDelta.analogAlarmLOLO', 1268, 'Jar Voltage Delta');

REPLACE INTO def_prop_groups_map  VALUES ('pbtBatStringVoltage.alarmEnable', 1268, 'String Voltage');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatStringVoltage.analogAlarmDeadband', 1268, 'String Voltage');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatStringVoltage.analogAlarmHI', 1268, 'String Voltage');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatStringVoltage.analogAlarmHIHI', 1268, 'String Voltage');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatStringVoltage.analogAlarmLO', 1268, 'String Voltage');
REPLACE INTO def_prop_groups_map  VALUES ('pbtBatStringVoltage.analogAlarmLOLO', 1268, 'String Voltage');

-- --------1

INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringJarVoltageDelta.alarmEnable', 1268, 'Other\Jar Voltage Delta') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringJarVoltageDelta.alarmEnable' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'Jar Voltage Delta'
WHERE prop_def_variable_name = 'pbtBatStringJarVoltageDelta.alarmEnable' AND device_type_id = 1268;

-- ----------2
INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)


SELECT * FROM
(SELECT 'pbtBatStringVoltage.analogAlarmDeadband', 1268, 'Other\Jar Voltage Delta') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringVoltage.analogAlarmDeadband' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'Jar Voltage Delta'
WHERE prop_def_variable_name = 'pbtBatStringVoltage.analogAlarmDeadband' AND device_type_id = 1268;

-- -----------3
INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)


SELECT * FROM
(SELECT 'pbtBatStringJarVoltageDelta.analogAlarmHI', 1268, 'Other\Jar Voltage Delta') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringJarVoltageDelta.analogAlarmHI' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'Jar Voltage Delta'
WHERE prop_def_variable_name = 'pbtBatStringJarVoltageDelta.analogAlarmHI' AND device_type_id = 1268;

-- ------4
INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)


SELECT * FROM
(SELECT 'pbtBatStringJarVoltageDelta.analogAlarmHIHI', 1268, 'Other\Jar Voltage Delta') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringJarVoltageDelta.analogAlarmHIHI' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'Jar Voltage Delta'
WHERE prop_def_variable_name = 'pbtBatStringJarVoltageDelta.analogAlarmHIHI' AND device_type_id = 1268;

-- ------5
INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)


SELECT * FROM
(SELECT 'pbtBatStringJarVoltageDelta.analogAlarmLO', 1268, 'Other\Jar Voltage Delta') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringJarVoltageDelta.analogAlarmLO' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'Jar Voltage Delta'
WHERE prop_def_variable_name = 'pbtBatStringJarVoltageDelta.analogAlarmLO' AND device_type_id = 1268;

-- ------6
INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)


SELECT * FROM
(SELECT 'pbtBatStringJarVoltageDelta.analogAlarmLOLO', 1268, 'Other\Jar Voltage Delta') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringJarVoltageDelta.analogAlarmLOLO' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'Jar Voltage Delta'
WHERE prop_def_variable_name = 'pbtBatStringJarVoltageDelta.analogAlarmLOLO' AND device_type_id = 1268;

-- ------1!
INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)


SELECT * FROM
(SELECT 'pbtBatStringVoltage.alarmEnable', 1268, 'Other\String Voltage') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringVoltage.alarmEnable' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Voltage'
WHERE prop_def_variable_name = 'pbtBatStringVoltage.alarmEnable' AND device_type_id = 1268;

-- -------2
INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringVoltage.analogAlarmDeadband', 1268, 'Other\String Voltage') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringVoltage.analogAlarmDeadband' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Voltage'
WHERE prop_def_variable_name = 'pbtBatStringVoltage.analogAlarmDeadband' AND device_type_id = 1268;

-- -----3
INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringVoltage.analogAlarmHI', 1268, 'Other\String Voltage') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringVoltage.analogAlarmHI' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Voltage'
WHERE prop_def_variable_name = 'pbtBatStringVoltage.analogAlarmHI' AND device_type_id = 1268;

-- ------4
INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringVoltage.analogAlarmHIHI', 1268, 'Other\String Voltage') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringVoltage.analogAlarmHIHI' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Voltage'
WHERE prop_def_variable_name = 'pbtBatStringVoltage.analogAlarmHIHI' AND device_type_id = 1268;

-- ------5
INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringVoltage.analogAlarmLO', 1268, 'Other\String Voltage') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringVoltage.analogAlarmLO' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Voltage'
WHERE prop_def_variable_name = 'pbtBatStringVoltage.analogAlarmLO' AND device_type_id = 1268;

-- ------6
INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringVoltage.analogAlarmLOLO', 1268, 'Other\String Voltage') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringVoltage.analogAlarmLOLO' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Voltage'
WHERE prop_def_variable_name = 'pbtBatStringVoltage.analogAlarmLOLO' AND device_type_id = 1268;

-- String - props

INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringConnectionAdmittance', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringConnectionAdmittance' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Info'
WHERE prop_def_variable_name = 'pbtBatStringConnectionAdmittance' AND device_type_id = 1268;


-- ---

INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringEqualizationControl', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringEqualizationControl' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Info'
WHERE prop_def_variable_name = 'pbtBatStringEqualizationControl' AND device_type_id = 1268;


-- ---

INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringJars', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringJars' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Info'
WHERE prop_def_variable_name = 'pbtBatStringJars' AND device_type_id = 1268;


-- ---

INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringName', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringName' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Info'
WHERE prop_def_variable_name = 'pbtBatStringName' AND device_type_id = 1268;


-- ---

INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringSensorComStatu', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringSensorComStatu' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Info'
WHERE prop_def_variable_name = 'pbtBatStringSensorComStatu' AND device_type_id = 1268;

-- ---

INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringDischargeStatus.discharge', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringDischargeStatus.discharge' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Info'
WHERE prop_def_variable_name = 'pbtBatStringDischargeStatus.discharge' AND device_type_id = 1268;

-- ----------

INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringDischargeStatus.normal', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringDischargeStatus.normal' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Info'
WHERE prop_def_variable_name = 'pbtBatStringDischargeStatus.normal' AND device_type_id = 1268;

-- ----------

INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringSensorComStatus.faultedSensor', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringSensorComStatus.faultedSensor' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Info'
WHERE prop_def_variable_name = 'pbtBatStringSensorComStatus.faultedSensor' AND device_type_id = 1268;

-- ----------

INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringSensorComStatus.normal', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringSensorComStatus.normal' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Info'
WHERE prop_def_variable_name = 'pbtBatStringSensorComStatus.normal' AND device_type_id = 1268;

-- ----------

INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtCellMetrixGroupTimeSinceDischarge', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtCellMetrixGroupTimeSinceDischarge' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Info'
WHERE prop_def_variable_name = 'pbtCellMetrixGroupTimeSinceDischarge' AND device_type_id = 1268;

-- ----------

INSERT INTO def_prop_groups_map
(prop_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringSensorComStatus', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_prop_groups_map
WHERE prop_def_variable_name = 'pbtBatStringSensorComStatus' AND device_type_id = 1268
)
;
UPDATE def_prop_groups_map SET group_var_name = 'String Info'
WHERE prop_def_variable_name = 'pbtBatStringSensorComStatus' AND device_type_id = 1268;


-- String Info - STATUS

INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'Battery Voltage - Very Low', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'Battery Voltage - Very Low' AND device_type_id = 1268
)
;
UPDATE def_status_groups_map SET group_var_name = 'String Info'
WHERE status_def_variable_name = 'Battery Voltage - Very Low' AND device_type_id = 1268;

-- ----------

INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringDischargeEvents', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatStringDischargeEvents' AND device_type_id = 1268
)
;
UPDATE def_status_groups_map SET group_var_name = 'String Info'
WHERE status_def_variable_name = 'pbtBatStringDischargeEvents' AND device_type_id = 1268;


INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

-- ----------

SELECT * FROM
(SELECT 'pbtBatStringDischargeSeconds', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatStringDischargeSeconds' AND device_type_id = 1268
)
;
UPDATE def_status_groups_map SET group_var_name = 'String Info'
WHERE status_def_variable_name = 'pbtBatStringDischargeSeconds' AND device_type_id = 1268;

-- ----------

INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringDischargeSecondsTotal', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatStringDischargeSecondsTotal' AND device_type_id = 1268
)
;
UPDATE def_status_groups_map SET group_var_name = 'String Info'
WHERE status_def_variable_name = 'pbtBatStringDischargeSecondsTotal' AND device_type_id = 1268;

-- ----------

INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringDischargeStatus', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatStringDischargeStatus' AND device_type_id = 1268
)
;
UPDATE def_status_groups_map SET group_var_name = 'String Info'
WHERE status_def_variable_name = 'pbtBatStringDischargeStatus' AND device_type_id = 1268;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringJarVoltageDelta', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatStringJarVoltageDelta' AND device_type_id = 1268
)
;
UPDATE def_status_groups_map SET group_var_name = 'String Info'
WHERE status_def_variable_name = 'pbtBatStringJarVoltageDelta' AND device_type_id = 1268;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringSensorComStatus', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatStringSensorComStatus' AND device_type_id = 1268
)
;
UPDATE def_status_groups_map SET group_var_name = 'String Info'
WHERE status_def_variable_name = 'pbtBatStringSensorComStatus' AND device_type_id = 1268;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringStatus', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatStringStatus' AND device_type_id = 1268
)
;
UPDATE def_status_groups_map SET group_var_name = 'String Info'
WHERE status_def_variable_name = 'pbtBatStringStatus' AND device_type_id = 1268;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatStringVoltage', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatStringVoltage' AND device_type_id = 1268
)
;
UPDATE def_status_groups_map SET group_var_name = 'String Info'
WHERE status_def_variable_name = 'pbtBatStringVoltage' AND device_type_id = 1268;

-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtCellMetrixGroupLastDischargeTime', 1268, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtCellMetrixGroupLastDischargeTime' AND device_type_id = 1268
)
;
UPDATE def_status_groups_map SET group_var_name = 'String Info'
WHERE status_def_variable_name = 'pbtCellMetrixGroupLastDischargeTime' AND device_type_id = 1268;


-- ---------------------------------------------------------------------------

-- Jar Info - status :
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'alarm_state', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'alarm_state' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'alarm_state' AND device_type_id = 1269;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarAdmittanceTime', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarAdmittanceTime' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarAdmittanceTime' AND device_type_id = 1269;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarCellAdmittance', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarCellAdmittance' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarCellAdmittance' AND device_type_id = 1269;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarCellAdmittanceChange', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarCellAdmittanceChange' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarCellAdmittanceChange' AND device_type_id = 1269;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarCellInitialAdmittance', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarCellInitialAdmittance' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarCellInitialAdmittance' AND device_type_id = 1269;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarElectrolyteLevel', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarElectrolyteLevel' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarElectrolyteLevel' AND device_type_id = 1269;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarInitialAdmittanceTime', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarInitialAdmittanceTime' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarInitialAdmittanceTime' AND device_type_id = 1269;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarName', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarName' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarName' AND device_type_id = 1269;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarSensorControl', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarSensorControl' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarSensorControl' AND device_type_id = 1269;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarSensorStatus', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarSensorStatus' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarSensorStatus' AND device_type_id = 1269;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarTemperature', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarTemperature' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarTemperature' AND device_type_id = 1269;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarTemperature.alarmEnable', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarTemperature.alarmEnable' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarTemperature.alarmEnable' AND device_type_id = 1269;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarTemperature.analogAlarmLOLO', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarTemperature.analogAlarmLOLO' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarTemperature.analogAlarmLOLO' AND device_type_id = 1269;
-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarVoltage', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarVoltage' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarVoltage' AND device_type_id = 1269;

-- ----------
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatJarVoltageHiRes', 1269, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatJarVoltageHiRes' AND device_type_id = 1269
)
;
UPDATE def_status_groups_map SET group_var_name = 'Jar Info'
WHERE status_def_variable_name = 'pbtBatJarVoltageHiRes' AND device_type_id = 1269;


-- BC Info - status :
INSERT INTO def_status_groups_map
(status_def_variable_name, device_type_id, group_var_name)

SELECT * FROM
(SELECT 'pbtBatSystemUptime', 1267, 'Other') as tmp
WHERE NOT EXISTS
(
SELECT * FROM def_status_groups_map
WHERE status_def_variable_name = 'pbtBatSystemUptime' AND device_type_id = 1267
)
;
UPDATE def_status_groups_map SET group_var_name = 'BC Info'
WHERE status_def_variable_name = 'pbtBatSystemUptime' AND device_type_id = 1267;

-- --------------

-- remove threshold for Discharge Current (A)
UPDATE css_networking_device_prop_def SET thresh_enable = 0
WHERE variable_name = 'pbtBatStringDischargeCurrent' AND device_type_id = 1268;

-- remove threshold for System Uptime
UPDATE css_networking_device_prop_def SET thresh_enable = 0
WHERE variable_name = 'pbtBatSystemUptime' AND device_type_id = 1268;

-- remove threshold for Discharge Status
UPDATE css_networking_device_prop_def SET thresh_enable = 0
WHERE variable_name = 'pbtBatStringDischargeStatus' AND device_type_id = 1268;

-- remove threshold for String Status
UPDATE css_networking_device_prop_def SET thresh_enable = 0
WHERE variable_name = 'pbtBatStringStatus' AND device_type_id = 1268;



RAWSQL
            );
            // R7.3.1 - B6796
            DB::unprepared(<<<RAWSQL
-- Jar Voltage - Alarm Enable
-- ----pbtBatJarVoltage.alarmLoLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1269','SCTE-HMS-PROPERTY-MIB::alarmEnable.16.1.3.6.1.4.1.20433.1.1.1.4.4.1.7','pbtBatJarVoltage.alarmLoLoEnable','Jar Voltage - Alarm Enable LoLo','HEX','1','1','Enable/Disable Jar Voltage - Alarm Enable LoLo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarVoltage.alarmLoLoEnable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarVoltage.alarmLoLoEnable') AND device_type_id IN (1269);

-- ----pbtBatJarVoltage.alarmLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1269','SCTE-HMS-PROPERTY-MIB::alarmEnable.16.1.3.6.1.4.1.20433.1.1.1.4.4.1.7','pbtBatJarVoltage.alarmLoEnable','Jar Voltage - Alarm Enable Lo','HEX','1','1','Enable/Disable Jar Voltage - Alarm Enable Lo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarVoltage.alarmLoEnable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarVoltage.alarmLoEnable') AND device_type_id IN (1269);

-- ----pbtBatJarVoltage.alarmHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1269','SCTE-HMS-PROPERTY-MIB::alarmEnable.16.1.3.6.1.4.1.20433.1.1.1.4.4.1.7','pbtBatJarVoltage.alarmHiEnable','Jar Voltage - Alarm Enable Hi','HEX','1','1','Enable/Disable Jar Voltage - Alarm Enable Hi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarVoltage.alarmHiEnable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarVoltage.alarmHiEnable') AND device_type_id IN (1269);

-- ----pbtBatJarVoltage.alarmHiHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1269','SCTE-HMS-PROPERTY-MIB::alarmEnable.16.1.3.6.1.4.1.20433.1.1.1.4.4.1.7','pbtBatJarVoltage.alarmHiHiEnable','Jar Voltage - Alarm Enable HiHi','HEX','1','1','Enable/Disable Jar Voltage - Alarm Enable HiHi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarVoltage.alarmHiHiEnable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarVoltage.alarmHiHiEnable') AND device_type_id IN (1269);


-- -----------------////*************/////------------------


-- Temperature - Alarm Enable
-- ----pbtBatJarTemperature.alarmLoLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1269','SCTE-HMS-PROPERTY-MIB::alarmEnable.16.1.3.6.1.4.1.20433.1.1.1.4.4.1.8','pbtBatJarTemperature.alarmLoLoEnable','Temperature - Alarm Enable LoLo','HEX','1','1','Enable/Disable Temperature - Alarm Enable LoLo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarTemperature.alarmLoLoEnable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarTemperature.alarmLoLoEnable') AND device_type_id IN (1269);

-- ----pbtBatJarTemperature.alarmLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1269','SCTE-HMS-PROPERTY-MIB::alarmEnable.16.1.3.6.1.4.1.20433.1.1.1.4.4.1.8','pbtBatJarTemperature.alarmLoEnable','Temperature - Alarm Enable Lo','HEX','1','1','Enable/Disable Temperature - Alarm Enable Lo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarTemperature.alarmLoEnable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarTemperature.alarmLoEnable') AND device_type_id IN (1269);

-- ----pbtBatJarTemperature.alarmHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1269','SCTE-HMS-PROPERTY-MIB::alarmEnable.16.1.3.6.1.4.1.20433.1.1.1.4.4.1.8','pbtBatJarTemperature.alarmHiEnable','Temperature - Alarm Enable Hi','HEX','1','1','Enable/Disable Temperature - Alarm Enable Hi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarTemperature.alarmHiEnable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarTemperature.alarmHiEnable') AND device_type_id IN (1269);

-- ----pbtBatJarTemperature.alarmHiHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1269','SCTE-HMS-PROPERTY-MIB::alarmEnable.16.1.3.6.1.4.1.20433.1.1.1.4.4.1.8','pbtBatJarTemperature.alarmHiHiEnable','Temperature - Alarm Enable HiHi','HEX','1','1','Enable/Disable Temperature - Alarm Enable HiHi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarTemperature.alarmHiHiEnable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarTemperature.alarmHiHiEnable') AND device_type_id IN (1269);


-- -----------------////*************/////------------------


-- Cell Admittance Change - Alarm Enable
-- ----pbtBatJarCellAdmittanceChange.alarmLoLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1269','SCTE-HMS-PROPERTY-MIB::alarmEnable.16.1.3.6.1.4.1.20433.1.1.1.4.4.1.13','pbtBatJarCellAdmittanceChange.alarmLoLoEnable','Cell Admittance Change - Alarm Enable LoLo','HEX','1','1','Enable/Disable Cell Admittance Change - Alarm Enable LoLo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarCellAdmittanceChange.alarmLoLoEnable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarCellAdmittanceChange.alarmLoLoEnable') AND device_type_id IN (1269);

-- ----pbtBatJarCellAdmittanceChange.alarmLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1269','SCTE-HMS-PROPERTY-MIB::alarmEnable.16.1.3.6.1.4.1.20433.1.1.1.4.4.1.13','pbtBatJarCellAdmittanceChange.alarmLoEnable','Cell Admittance Change - Alarm Enable Lo','HEX','1','1','Enable/Disable Cell Admittance Change - Alarm Enable Lo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarCellAdmittanceChange.alarmLoEnable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarCellAdmittanceChange.alarmLoEnable') AND device_type_id IN (1269);

-- ----pbtBatJarCellAdmittanceChange.alarmHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1269','SCTE-HMS-PROPERTY-MIB::alarmEnable.16.1.3.6.1.4.1.20433.1.1.1.4.4.1.13','pbtBatJarCellAdmittanceChange.alarmHiEnable','Cell Admittance Change - Alarm Enable Hi','HEX','1','1','Enable/Disable Cell Admittance Change - Alarm Enable Hi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarCellAdmittanceChange.alarmHiEnable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarCellAdmittanceChange.alarmHiEnable') AND device_type_id IN (1269);

-- ----pbtBatJarCellAdmittanceChange.alarmHiHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1269','SCTE-HMS-PROPERTY-MIB::alarmEnable.16.1.3.6.1.4.1.20433.1.1.1.4.4.1.13','pbtBatJarCellAdmittanceChange.alarmHiHiEnable','Cell Admittance Change - Alarm Enable HiHi','HEX','1','1','Enable/Disable Cell Admittance Change - Alarm Enable HiHi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarCellAdmittanceChange.alarmHiHiEnable') AND device_type_id IN (1269);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatJarCellAdmittanceChange.alarmHiHiEnable') AND device_type_id IN (1269);


-- -----------------////*************/////------------------


-- Ambient Temperature (C) - Alarm Enable
-- ----pbtCellMetrixGroupAmbientTemperature.alarmLoLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.3.2.1.3.1.4.1','pbtCellMetrixGroupAmbientTemperature.alarmLoLoEnable','Ambient Temperature (C) - Alarm Enable LoLo','HEX','1','1','Enable/Disable Ambient Temperature (C) - Alarm Enable LoLo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtCellMetrixGroupAmbientTemperature.alarmLoLoEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtCellMetrixGroupAmbientTemperature.alarmLoLoEnable') AND device_type_id IN (1268);

-- ----pbtCellMetrixGroupAmbientTemperature.alarmLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.3.2.1.3.1.4.1','pbtCellMetrixGroupAmbientTemperature.alarmLoEnable','Ambient Temperature (C) - Alarm Enable Lo','HEX','1','1','Enable/Disable Ambient Temperature (C) - Alarm Enable Lo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtCellMetrixGroupAmbientTemperature.alarmLoEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtCellMetrixGroupAmbientTemperature.alarmLoEnable') AND device_type_id IN (1268);

-- ----pbtCellMetrixGroupAmbientTemperature.alarmHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.3.2.1.3.1.4.1','pbtCellMetrixGroupAmbientTemperature.alarmHiEnable','Ambient Temperature (C) - Alarm Enable Hi','HEX','1','1','Enable/Disable Ambient Temperature (C) - Alarm Enable Hi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtCellMetrixGroupAmbientTemperature.alarmHiEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtCellMetrixGroupAmbientTemperature.alarmHiEnable') AND device_type_id IN (1268);

-- ----pbtCellMetrixGroupAmbientTemperature.alarmHiHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.3.2.1.3.1.4.1','pbtCellMetrixGroupAmbientTemperature.alarmHiHiEnable','Ambient Temperature (C) - Alarm Enable HiHi','HEX','1','1','Enable/Disable Ambient Temperature (C) - Alarm Enable HiHi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtCellMetrixGroupAmbientTemperature.alarmHiHiEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtCellMetrixGroupAmbientTemperature.alarmHiHiEnable') AND device_type_id IN (1268);


-- -----------------////*************/////------------------


-- String Voltage - Alarm Enable
-- ----pbtBatStringVoltage.alarmLoLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.6','pbtBatStringVoltage.alarmLoLoEnable','String Voltage - Alarm Enable LoLo','HEX','1','1','Enable/Disable String Voltage - Alarm Enable LoLo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringVoltage.alarmLoLoEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringVoltage.alarmLoLoEnable') AND device_type_id IN (1268);

-- ----pbtBatStringVoltage.alarmLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.6','pbtBatStringVoltage.alarmLoEnable','String Voltage - Alarm Enable Lo','HEX','1','1','Enable/Disable String Voltage - Alarm Enable Lo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringVoltage.alarmLoEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringVoltage.alarmLoEnable') AND device_type_id IN (1268);

-- ----pbtBatStringVoltage.alarmHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.6','pbtBatStringVoltage.alarmHiEnable','String Voltage - Alarm Enable Hi','HEX','1','1','Enable/Disable String Voltage - Alarm Enable Hi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringVoltage.alarmHiEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringVoltage.alarmHiEnable') AND device_type_id IN (1268);

-- ----pbtBatStringVoltage.alarmHiHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.6','pbtBatStringVoltage.alarmHiHiEnable','String Voltage - Alarm Enable HiHi','HEX','1','1','Enable/Disable String Voltage - Alarm Enable HiHi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringVoltage.alarmHiHiEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringVoltage.alarmHiHiEnable') AND device_type_id IN (1268);


-- -----------------////*************/////------------------


-- Ripple Current - Alarm Enable
-- ----pbtBatStringRippleCurrent.alarmLoLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.13','pbtBatStringRippleCurrent.alarmLoLoEnable','Ripple Current - Alarm Enable LoLo','HEX','1','1','Enable/Disable Ripple Current - Alarm Enable LoLo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringRippleCurrent.alarmLoLoEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringRippleCurrent.alarmLoLoEnable') AND device_type_id IN (1268);

-- ----pbtBatStringRippleCurrent.alarmLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.13','pbtBatStringRippleCurrent.alarmLoEnable','Ripple Current - Alarm Enable Lo','HEX','1','1','Enable/Disable Ripple Current - Alarm Enable Lo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringRippleCurrent.alarmLoEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringRippleCurrent.alarmLoEnable') AND device_type_id IN (1268);

-- ----pbtBatStringRippleCurrent.alarmHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.13','pbtBatStringRippleCurrent.alarmHiEnable','Ripple Current - Alarm Enable Hi','HEX','1','1','Enable/Disable Ripple Current - Alarm Enable Hi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringRippleCurrent.alarmHiEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringRippleCurrent.alarmHiEnable') AND device_type_id IN (1268);

-- ----pbtBatStringRippleCurrent.alarmHiHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.13','pbtBatStringRippleCurrent.alarmHiHiEnable','Ripple Current - Alarm Enable HiHi','HEX','1','1','Enable/Disable Ripple Current - Alarm Enable HiHi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringRippleCurrent.alarmHiHiEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringRippleCurrent.alarmHiHiEnable') AND device_type_id IN (1268);


-- -----------------////*************/////------------------



-- Voltage Delta - Alarm Enable
-- ----pbtBatStringJarVoltageDelta.alarmLoLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.10','pbtBatStringJarVoltageDelta.alarmLoLoEnable','Voltage Delta - Alarm Enable LoLo','HEX','1','1','Enable/Disable Voltage Delta - Alarm Enable LoLo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringJarVoltageDelta.alarmLoLoEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringJarVoltageDelta.alarmLoLoEnable') AND device_type_id IN (1268);

-- ----pbtBatStringJarVoltageDelta.alarmLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.10','pbtBatStringJarVoltageDelta.alarmLoEnable','Voltage Delta - Alarm Enable Lo','HEX','1','1','Enable/Disable Voltage Delta - Alarm Enable Lo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringJarVoltageDelta.alarmLoEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringJarVoltageDelta.alarmLoEnable') AND device_type_id IN (1268);

-- ----pbtBatStringJarVoltageDelta.alarmHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.10','pbtBatStringJarVoltageDelta.alarmHiEnable','Voltage Delta - Alarm Enable Hi','HEX','1','1','Enable/Disable Voltage Delta - Alarm Enable Hi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringJarVoltageDelta.alarmHiEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringJarVoltageDelta.alarmHiEnable') AND device_type_id IN (1268);

-- ----pbtBatStringJarVoltageDelta.alarmHiHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.10','pbtBatStringJarVoltageDelta.alarmHiHiEnable','Voltage Delta - Alarm Enable HiHi','HEX','1','1','Enable/Disable Voltage Delta - Alarm Enable HiHi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringJarVoltageDelta.alarmHiHiEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringJarVoltageDelta.alarmHiHiEnable') AND device_type_id IN (1268);


-- -----------------////*************/////------------------



-- Float Current - Alarm Enable
-- ----pbtBatStringFloatCurrent.alarmLoLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.7','pbtBatStringFloatCurrent.alarmLoLoEnable','Float Current - Alarm Enable LoLo','HEX','1','1','Enable/Disable Float Current - Alarm Enable LoLo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringFloatCurrent.alarmLoLoEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringFloatCurrent.alarmLoLoEnable') AND device_type_id IN (1268);

-- ----pbtBatStringFloatCurrent.alarmLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.7','pbtBatStringFloatCurrent.alarmLoEnable','Float Current - Alarm Enable Lo','HEX','1','1','Enable/Disable Float Current - Alarm Enable Lo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringFloatCurrent.alarmLoEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringFloatCurrent.alarmLoEnable') AND device_type_id IN (1268);

-- ----pbtBatStringFloatCurrent.alarmHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.7','pbtBatStringFloatCurrent.alarmHiEnable','Float Current - Alarm Enable Hi','HEX','1','1','Enable/Disable Float Current - Alarm Enable Hi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringFloatCurrent.alarmHiEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringFloatCurrent.alarmHiEnable') AND device_type_id IN (1268);

-- ----pbtBatStringFloatCurrent.alarmHiHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.7','pbtBatStringFloatCurrent.alarmHiHiEnable','Float Current - Alarm Enable HiHi','HEX','1','1','Enable/Disable Float Current - Alarm Enable HiHi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringFloatCurrent.alarmHiHiEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringFloatCurrent.alarmHiHiEnable') AND device_type_id IN (1268);


-- -----------------////*************/////------------------



-- Discharge Current - Alarm Enable
-- ----pbtBatStringDischargeCurrent.alarmLoLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.11','pbtBatStringDischargeCurrent.alarmLoLoEnable','Discharge Current - Alarm Enable LoLo','HEX','1','1','Enable/Disable Discharge Current - Alarm Enable LoLo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeCurrent.alarmLoLoEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeCurrent.alarmLoLoEnable') AND device_type_id IN (1268);

-- ----pbtBatStringDischargeCurrent.alarmLoEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.11','pbtBatStringDischargeCurrent.alarmLoEnable','Discharge Current - Alarm Enable Lo','HEX','1','1','Enable/Disable Discharge Current - Alarm Enable Lo');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeCurrent.alarmLoEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeCurrent.alarmLoEnable') AND device_type_id IN (1268);

-- ----pbtBatStringDischargeCurrent.alarmHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.11','pbtBatStringDischargeCurrent.alarmHiEnable','Discharge Current - Alarm Enable Hi','HEX','1','1','Enable/Disable Discharge Current - Alarm Enable Hi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeCurrent.alarmHiEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeCurrent.alarmHiEnable') AND device_type_id IN (1268);

-- ----pbtBatStringDischargeCurrent.alarmHiHiEnable
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1268','SCTE-HMS-PROPERTY-MIB::alarmEnable.15.1.3.6.1.4.1.20433.1.1.1.4.3.1.11','pbtBatStringDischargeCurrent.alarmHiHiEnable','Discharge Current - Alarm Enable HiHi','HEX','1','1','Enable/Disable Discharge Current - Alarm Enable HiHi');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeCurrent.alarmHiHiEnable') AND device_type_id IN (1268);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('pbtBatStringDischargeCurrent.alarmHiHiEnable') AND device_type_id IN (1268);


-- -----------------////*************/////------------------



RAWSQL
            );
            // R7.3.1 - B6819
            DB::unprepared(<<<RAWSQL
-- B6819, replacing/inserting alarm_dictionary entries for all TEKO current absorption out of range alarms
-- to have an appropriate cause, impact, remedy, and alarm details.

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1191, 'Channel AWS2100 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1191, 'Channel SMR700 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1191, 'Channel CH-3 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1194, 'Channel AWS2100 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1.	Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1194, 'Channel SMR700 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1194, 'Channel CH-3 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1198, 'Channel AWS2100 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1198, 'Channel SMR700 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1198, 'Channel CH-3 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1193, 'Channel AWS2100 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1.	Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1193, 'Channel SMR700 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1193, 'Channel CH-3 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1204, 'Channel SMR700 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1204, 'Channel AMPS850 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1204, 'Channel OFF current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1.	Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1204, 'Channel PCS1900 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1.	Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1204, 'Channel AWS2100 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1.	Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1995, 'Channel SMR700 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1995, 'Channel AMPS850 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1.	Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1995, 'Channel OFF current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1.	Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1995, 'Channel PCS1900 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1.	Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1995, 'Channel AWS2100 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1.	Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1182, 'Channel1 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1182, 'Channel2 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1182, 'Channel3 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1185, 'Channel1 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1185, 'Channel2 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1185, 'Channel3 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1186, 'Channel1 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1186, 'Channel2 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1186, 'Channel3 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1991, 'Channel1 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1991, 'Channel2 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy, alarm_details)
VALUES (1991, 'Channel3 current absorption is out of range.', 2, 'Uplink or Downlink overdrive of the amplifier', 'Service degradation', '1. Check DL output power of each remote via GUI and confirm with spec sheet appropriate value. The output is dependent on remote type. A site visit may be required to properly set power using a spectrum analyzer.\n2.	Check antenna positioning and set appropriate Remote Unit Uplink attenuation, Check for uncoordinated mobiles or nearby transmitters.\n3.	If alarm persists after verifying the above, replace remote unit.', 'Channel Current Absorption out of Range');

RAWSQL
            );
            // R7.3.1 - B6836
            DB::unprepared(<<<RAWSQL
-- ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Bug 6836 - GE Infinity NE - Non clearing alarms not acknowledgeable:
-- Removing invalid alarm dictionary records that have severity of zero.
-- Fixing acknowledge flags for records in alarm dictionary and alarm tables.
-- ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Removing invalid records
DELETE FROM css_alarms_dictionary WHERE (device_type_id = '2050' AND severity_id = '0');
-- Fixing acknowledge flags for records in the alarm dictionary table
UPDATE css_alarms_dictionary SET can_acknowledge = '1' WHERE (device_type_id = '2050' AND alarm_description = 'Password At Default');
UPDATE css_alarms_dictionary SET can_acknowledge = '1' WHERE (device_type_id = '2050' AND alarm_description = 'External Password Reset');
UPDATE css_alarms_dictionary SET can_acknowledge = '1' WHERE (device_type_id = '2050' AND alarm_description = 'Excessive Login Attempts');
UPDATE css_alarms_dictionary SET can_acknowledge = '1' WHERE (device_type_id = '2050' AND alarm_description = 'Clock Changed');
UPDATE css_alarms_dictionary SET can_acknowledge = '1' WHERE (device_type_id = '2050' AND alarm_description = 'Configuration Changed');
UPDATE css_alarms_dictionary SET can_acknowledge = '1' WHERE (device_type_id = '2050' AND alarm_description = 'History Cleared');
UPDATE css_alarms_dictionary SET can_acknowledge = '1' WHERE (device_type_id = '2050' AND alarm_description = 'Alarm Test Aborted');
-- Fixing acknowledge flags for records in the alarm table
UPDATE css_networking_device_alarm a INNER JOIN css_networking_device d ON (a.device_id = d.id) SET a.can_acknowledge = '1' WHERE (d.type_id = '2050' AND a.description = 'Password At Default');
UPDATE css_networking_device_alarm a INNER JOIN css_networking_device d ON (a.device_id = d.id) SET a.can_acknowledge = '1' WHERE (d.type_id = '2050' AND a.description = 'External Password Reset');
UPDATE css_networking_device_alarm a INNER JOIN css_networking_device d ON (a.device_id = d.id) SET a.can_acknowledge = '1' WHERE (d.type_id = '2050' AND a.description = 'Excessive Login Attempts');
UPDATE css_networking_device_alarm a INNER JOIN css_networking_device d ON (a.device_id = d.id) SET a.can_acknowledge = '1' WHERE (d.type_id = '2050' AND a.description = 'Clock Changed');
UPDATE css_networking_device_alarm a INNER JOIN css_networking_device d ON (a.device_id = d.id) SET a.can_acknowledge = '1' WHERE (d.type_id = '2050' AND a.description = 'Configuration Changed');
UPDATE css_networking_device_alarm a INNER JOIN css_networking_device d ON (a.device_id = d.id) SET a.can_acknowledge = '1' WHERE (d.type_id = '2050' AND a.description = 'History Cleared');
UPDATE css_networking_device_alarm a INNER JOIN css_networking_device d ON (a.device_id = d.id) SET a.can_acknowledge = '1' WHERE (d.type_id = '2050' AND a.description = 'Alarm Test Aborted');
-- -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- End of script
-- -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
RAWSQL
            );
            // R7.3.1 - B6852
            DB::unprepared(<<<RAWSQL
-- modify device type model in css_networking_device_type table to accommodate Sierra Raven GX450
update css_networking_device_type set model = "Raven GX 440/450" where model = "Raven GX 440";



RAWSQL
            );
            // R7.3.1 - B6932
            DB::unprepared(<<<RAWSQL


REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','model','model','Model','STRING','0','1','Model');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','serialnumber','serialnumber','Serial Number','STRING','0','1',' Serial Number');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','swver','swver','Software Version','STRING','0','1','Software Version');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','wandhcp','wandhcp','DHCP Clinet On/Off','INTEGER','1','1','DHCP Clinet On/Off');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','wanip','wanip','IP Address ','STRING','1','1','IP Address of the WAN port ');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','subnetmask','subnetmask','Subnet Mask ','STRING','1','1','Subnet Mask of the WAN port');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','defaultgateway','defaultgateway','Default Gateway','STRING','1','1','Default Gateway Address of the WAN Port');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','dnsprimaryserver','dnsprimaryserver','Primary DNS Server Address','STRING','1','1','Primary DNS Server Address');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','dnssecondaryserver','dnssecondaryserver','Secondary DNS Server Address','STRING','1','1','Secondary DNS Server Address');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','readcommunitySNMP','readcommunitySNMP','SNMP Read Community','STRING','1','1','SNMP Read Community');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','writecommunity','writecommunity','SNMP Write Community','STRING','1','1','SNMP Write Community');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','user','user','SNMPv3 User','STRING','1','1',' SNMPv3 User');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','securitylevel','securitylevel','SNMPv3 Security Level','INTEGER','1','1',' SNMPv3 Security Level');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','authprotocol','authprotocol','SNMPv3 Authentication Protocol','INTEGER','1','1','SNMPv3 Authentication Protocol');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','authpasscode','authpasscode','SNMPv3 Authentication Passcode','INTEGER','1','1',' SNMPv3 Authentication Passcode');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','privprotocol','privprotocol','SNMPv3 Privacy Protocol','INTEGER','1','1',' SNMPv3 Privacy Protocol');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','privpasscode','privpasscode','SNMPv3 Privacy Passcode','INTEGER','1','1','SNMPv3 Privacy Passcode');
REPLACE css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1530','siteid','siteid',' Site ID','STRING','1','1',' Site ID');
REPLACE css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1530','interval','interval','Heartbeat Interval','INTEGER','1','1',' Heartbeat Interval');



REPLACE css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('2013','portenable','portenable','Port Enable','INTEGER','0','1','Port Enable');
REPLACE css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('2013','physicalport','physicalport',' Physical Port','INTEGER','0','1',' Physical Port');
REPLACE css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('2013','channel','channel','Channel Number','INTEGER','0','2','Channel Number');
REPLACE css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('2013','freqband','freqband','Band Name','STRING','0','1','Band Name');
REPLACE css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('2013','customprofilename','customprofilename','Custom Profile Name','STRING','0','1','Custom Profile Name');
REPLACE css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('2013','startfreq','startfreq','Start Frequency','INTEGER','0','1','Start Frequency');
REPLACE css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('2013','stopfreq','stopfreq','Stop Frequency','INTEGER','0','1','Stop Frequency');
REPLACE css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('2013','btsnumber','btsnumber','BTS Number','INTEGER','0','2',' BTS Number');
REPLACE css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('2013','sector','sector','Sector','INTEGER','0','2','Sector');
REPLACE css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('2013','offset','offset','Offset','INTEGER','0','2',' Offset');
REPLACE css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('2013','note','note','Note','STRING','0','1',' Note');



REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('model','1530','UnitInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('serialnumber','1530','UnitInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('swver','1530','UnitInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('wandhcp','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('wanip','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('subnetmask','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('defaultgateway','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dnsprimaryserver','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dnssecondaryserver','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('readcommunity','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('writecommunity','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('user','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('securitylevel','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('authprotocol','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('authpasscode','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('privprotocol','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('privpasscode','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('siteid','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('interval','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('trapinformdestination','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('trapinformport','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('trapinformsnmpversion','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('trapinformv2ccommunity','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('trapinformv3user','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('trapinformv3securitylevel','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('trapinformv3authprotocol','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('trapinformv3authpasscode','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('trapinformv3privprotocol','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('trapinformv3privpasscode','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('emaildestaddress','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('emailname','1530','LANInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('firmware','1530','UnitInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1530','UnitInformation');

REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('RF Port Config','RF Port Config');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('RF Port Config','RF Port Config');

REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('portenable','2013','UnitInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('physicalport','2013','UnitInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('channel','2013','RF Port Config');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('freqband','2013','RF Port Config');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('customprofilename','2013','UnitInformation');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('startfreq','2013','RF Port Config');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('stopfreq','2013','RF Port Config');
REPLACE def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('note','2013','UnitInformation');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('btsnumber','2013','RF Port Config');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('offset','2013','RF Port Config');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('sector','2013','RF Port Config');


REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'0','Off' FROM css_networking_device_prop_def WHERE variable_name in ('wandhcp') AND device_type_id IN (1530);
REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'1','On' FROM css_networking_device_prop_def WHERE variable_name in ('wandhcp') AND device_type_id IN (1530);

REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'1','noAuthnoPriv' FROM css_networking_device_prop_def WHERE variable_name in ('securitylevel') AND device_type_id IN (1530);
REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'2','authnoPriv' FROM css_networking_device_prop_def WHERE variable_name in ('securitylevel') AND device_type_id IN (1530);
REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'3','authPriv' FROM css_networking_device_prop_def WHERE variable_name in ('securitylevel') AND device_type_id IN (1530);

REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'1','MD5' FROM css_networking_device_prop_def WHERE variable_name in ('authprotocol') AND device_type_id IN (1530);
REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'2','SHA1' FROM css_networking_device_prop_def WHERE variable_name in ('authprotocol') AND device_type_id IN (1530);

REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'1','DES' FROM css_networking_device_prop_def WHERE variable_name in ('privprotocol') AND device_type_id IN (1530);
REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'2','AES' FROM css_networking_device_prop_def WHERE variable_name in ('privprotocol') AND device_type_id IN (1530);

REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'0','OFF' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'1','01' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'2','02' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'3','03' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'4','04' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'5','05' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'6','06' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'7','07' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'8','08' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'9','09' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'10','10' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'11','11' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'12','12' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'13','13' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'14','14' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'15','15' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'16','16' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'17','17' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'18','18' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'19','19' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'20','20' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'21','21' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'22','22' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'23','23' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'24','24' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'25','25' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'26','26' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'27','27' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'28','28' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'29','29' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'30','30' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'31','31' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'32','32' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'33','33' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'34','34' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'35','35' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'36','36' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'37','37' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'38','38' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'39','39' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'40','40' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'41','41' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'42','42' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'43','43' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'44','44' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'45','45' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'46','46' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'47','47' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'48','48' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'49','49' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'50','50' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'51','51' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'52','52' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'53','53' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'54','54' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'55','55' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'56','56' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'57','57' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'58','58' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'59','59' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);
 REPLACE css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'60','60' FROM css_networking_device_prop_def WHERE variable_name in ('interval') AND device_type_id IN (1530);

RAWSQL
            );
            // R7.3.1 - B7001
            DB::unprepared(<<<RAWSQL
-- Insert (if they do not exist) and update Cisco properties
-- Existence checks use regular expressions,  because some variable_name values in the database have a space in front

DROP PROCEDURE IF EXISTS upgrade_css_networking_device_prop_def_cisco;
RAWSQL
            );
            DB::unprepared(<<<RAWSQL

CREATE PROCEDURE upgrade_css_networking_device_prop_def_cisco()

BEGIN
DECLARE CheckExists int;
SET CheckExists = 0;

SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotal5sec$" AND device_type_id = 1440;

IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1440,'cpmCPUTotal5sec','cpmCPUTotal5sec');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'CPU Total 5sec', data_type = 'INTEGER', tooltip = 'The overall CPU busy percentage in the last 5 second period.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotal5sec$" AND device_type_id = 1440;

-- ====================================================================
SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotal1min$" AND device_type_id = 1440;

IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1440,'cpmCPUTotal1min','cpmCPUTotal1min');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'CPU Total 1min', data_type = 'INTEGER', tooltip = 'The overall CPU busy percentage in the last 1 minute period.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotal1min$" AND device_type_id = 1440;

-- =========================================================
SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotal5min$" AND device_type_id = 1440;

IF (CheckExists =0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1440,'cpmCPUTotal5min','cpmCPUTotal5min');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'CPU Total 5min', data_type = 'INTEGER', tooltip = 'The overall CPU busy percentage in the last 5 minute period.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotal5min$" and device_type_id = 1440;

-- ===========================================================
SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotalMonIntervalValue$" and device_type_id = 1440;

IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1440,'cpmCPUTotalMonIntervalValue','cpmCPUTotalMonIntervalValue');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'CPU Total Over Last Monitor Interval', data_type = 'INTEGER',
tooltip = 'The overall CPU busy percentage in the last monitor interval. '
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUTotalMonIntervalValue$" and device_type_id = 1440;

-- ================================================================
SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUMonInterval$" and device_type_id = 1440;

IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1440,'cpmCPUMonInterval','cpmCPUMonInterval');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 1, name = 'CPU Monitoring Interval (sec)', data_type = 'INTEGER', tooltip = 'CPU usage monitoring interval.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUMonInterval$" and device_type_id = 1440;

-- ====================================
SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUInterruptMonIntervalValue$" and device_type_id = 1440;

IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1440,'cpmCPUInterruptMonIntervalValue','cpmCPUInterruptMonIntervalValue');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'CPU Interrupt Over Last Monitor Period ', data_type = 'INTEGER', tooltip = 'The overall CPU busy percentage in the interrupt context in the last Monitor Interval.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUInterruptMonIntervalValue$" and device_type_id = 1440;

-- =======================================================
SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUMemoryUsed$" 'cpmCPUMemoryUsed' and device_type_id = 1440;

IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1440,'cpmCPUMemoryUsed','cpmCPUMemoryUsed');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'CPU Memory Used (kilobytes) ', data_type = 'INTEGER',
tooltip = 'The overall CPU wide system memory which is currently under use.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUMemoryUsed$" and device_type_id = 1440;

-- =======================================================

SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUMemoryFree" and device_type_id = 1440;


IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1440,'cpmCPUMemoryFree','cpmCPUMemoryFree');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'Memory free (kilobytes)', data_type = 'INTEGER',
tooltip = 'The overall CPU wide system memory which is currently free.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUMemoryFree" and device_type_id = 1440;

-- =========================================================================
SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUMemoryKernelReserved$" and device_type_id = 1440;

IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1440,'cpmCPUMemoryKernelReserved','cpmCPUMemoryKernelReserved');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'Kernel memory reserved (kilobytes)', data_type = 'INTEGER',
tooltip = 'The overall CPU wide system memory which is reserved for kernel usage.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUMemoryKernelReserved$" and device_type_id = 1440;


-- =========================================================================
SELECT COUNT(*)  INTO CheckExists
FROM css_networking_device_prop_def
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUMemoryLowest$" and device_type_id = 1440;


IF (CheckExists = 0) THEN
	INSERT IGNORE INTO css_networking_device_prop_def (device_class_id,device_type_id,variable_name,name)
	VALUES (1129,1440,'cpumCPUMemoryLowest','cpumCPUMemoryLowest');
END IF;

UPDATE css_networking_device_prop_def SET prop_type_id = 2, name = 'Kernel memory reserved (bytes)', data_type = 'INTEGER',
tooltip = 'The lowest free memory that has been recorded since
device has booted.'
WHERE variable_name REGEXP "^[[:space:]]*cpmCPUMemoryLowest$" and device_type_id = 1440;

END
RAWSQL
            );
            DB::unprepared(<<<RAWSQL

CALL upgrade_css_networking_device_prop_def_cisco;
DROP PROCEDURE IF EXISTS upgrade_css_networking_device_prop_def_cisco;

RAWSQL
            );
            // R7.3.1 - B7064
            DB::unprepared(<<<RAWSQL
-- update thresholding prop defs for SiteBoss sensors

update css_networking_device_prop_def
set name = 'Temperature Value (Very Low/Very High)', alarm_exempt = 1
where device_type_id = 1087 and variable_name = 'value';

update css_networking_device_prop_def
set name = 'Temperature Value (Low/High)', alarm_exempt = 1
where device_type_id = 1087 and variable_name = 'value_two';

update css_networking_device_prop_def
set name = 'Analog Value (Very Low/Very High)', alarm_exempt = 1
where device_type_id = 1089 and variable_name = 'value';

update css_networking_device_prop_def
set name = 'Analog Value (Low/High)', alarm_exempt = 1
where device_type_id = 1089 and variable_name = 'value_two';

update css_networking_device_prop_def
set name = 'Humidity Value (Very Low/Very High)', alarm_exempt = 1
where device_type_id = 1091 and variable_name = 'value';

update css_networking_device_prop_def
set name = 'Humidity Value (Low/High)', alarm_exempt = 1
where device_type_id = 1091 and variable_name = 'value_two';

update css_networking_device_prop_def
set name = 'Volume Fuel Level (Very Low/Very High)', alarm_exempt = 1
where device_type_id = 1094 and variable_name = 'volume_value';

update css_networking_device_prop_def
set name = 'Volume Fuel Level (Low/High)', alarm_exempt = 1
where device_type_id = 1094 and variable_name = 'volume_value_two';

update css_networking_device_prop_def
set name = 'Average Current Value (Very Low/Very High)', alarm_exempt = 1
where device_type_id = 1320 and variable_name = 'current_average_value';

update css_networking_device_prop_def
set name = 'Average Current Value (Low/High)', alarm_exempt = 1
where device_type_id = 1320 and variable_name = 'current_average_value_two';

update css_networking_device_prop_def
set name = 'Frequency Value (Very Low/Very High)', alarm_exempt = 1
where device_type_id = 1320 and variable_name = 'frequency_value';

update css_networking_device_prop_def
set name = 'Frequency Value (Low/High)', alarm_exempt = 1
where device_type_id = 1320 and variable_name = 'frequency_value_two';

update css_networking_device_prop_def
set name = 'Power Apparent Total Value (Very Low/Very High)', alarm_exempt = 1
where device_type_id = 1320 and variable_name = 'power_apparent_total_value';

update css_networking_device_prop_def
set name = 'Power Reactive Total Value (Very Low/Very High)', alarm_exempt = 1
where device_type_id = 1320 and variable_name = 'power_reactive_total_value';

update css_networking_device_prop_def
set name = 'Power Factor Value (Very Low/Very High)', alarm_exempt = 1
where device_type_id = 1320 and variable_name = 'power_factor_total_value';

update css_networking_device_prop_def
set name = 'Power Factor Total Value (Low/High)', alarm_exempt = 1
where device_type_id = 1320 and variable_name = 'power_factor_total_value_two';

update css_networking_device_prop_def
set name = 'Real Power Value (Very Low/Very High)', alarm_exempt = 1
where device_type_id = 1320 and variable_name = 'power_real_total_value';

update css_networking_device_prop_def
set name = 'Real Power Total Value (Low/High)', alarm_exempt = 1
where device_type_id = 1320 and variable_name = 'power_real_total_value_two';

update css_networking_device_prop_def
set name = 'Average Voltage Value (Very Low/Very High)', alarm_exempt = 1
where device_type_id = 1320 and variable_name = 'voltage_average_value';

update css_networking_device_prop_def
set name = 'Average Voltage Value (Low/High)', alarm_exempt = 1
where device_type_id = 1320 and variable_name = 'voltage_average_value_two';

RAWSQL
            );
            // R7.3.1 - B7159
            DB::unprepared(<<<RAWSQL
-- B7519, replacing/inserting alarm_dictionary entries for all MA SC450 device disconnected alarms
-- to have an appropriate cause, impact, remedy, and alarm details.

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy)
VALUES (108, 'OPTM Disconnected', 2, 'Baseline not completed / Device lost power / Device disconnected from system', 'This is based on the device that is disconnected, but generally would result in a loss of coverage.', '1. Baseline not completed – verify that all components are connected to the system and are showing up properly in the GUI, then perform the Baseline\n2. Device lost power – locate device showing as disconnected.  Verify the unit is powered on.  Check power levels from source.\n3. Device disconnected from system – locate device showing as disconnected.  Verify connectivity to system – replace connecting cables if necessary.\n4. If device is connected and powered on, and is still showing as disconnected, try power cycling the device to restore communication.');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy)
VALUES (110, 'BTSC Disconnected', 2, 'Baseline not completed / Device lost power / Device disconnected from system', 'This is based on the device that is disconnected, but generally would result in a loss of coverage.', '1. Baseline not completed – verify that all components are connected to the system and are showing up properly in the GUI, then perform the Baseline\n2. Device lost power – locate device showing as disconnected.  Verify the unit is powered on.  Check power levels from source.\n3. Device disconnected from system – locate device showing as disconnected.  Verify connectivity to system – replace connecting cables if necessary.\n4. If device is connected and powered on, and is still showing as disconnected, try power cycling the device to restore communication.');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy)
VALUES (109, 'RHU Disconnected', 2, 'Baseline not completed / Device lost power / Device disconnected from system', 'This is based on the device that is disconnected, but generally would result in a loss of coverage.', '1. Baseline not completed – verify that all components are connected to the system and are showing up properly in the GUI, then perform the Baseline\n2. Device lost power – locate device showing as disconnected.  Verify the unit is powered on.  Check power levels from source.\n3. Device disconnected from system – locate device showing as disconnected.  Verify connectivity to system – replace connecting cables if necessary.\n4. If device is connected and powered on, and is still showing as disconnected, try power cycling the device to restore communication.');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy)
VALUES (1085, 'ADDON Disconnected', 2, 'Baseline not completed / Device lost power / Device disconnected from system', 'This is based on the device that is disconnected, but generally would result in a loss of coverage.', '1. Baseline not completed – verify that all components are connected to the system and are showing up properly in the GUI, then perform the Baseline\n2. Device lost power – locate device showing as disconnected.  Verify the unit is powered on.  Check power levels from source.\n3. Device disconnected from system – locate device showing as disconnected.  Verify connectivity to system – replace connecting cables if necessary.\n4. If device is connected and powered on, and is still showing as disconnected, try power cycling the device to restore communication.');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy)
VALUES (1261, 'OCH Disconnected', 2, 'Baseline not completed / Device lost power / Device disconnected from system', 'This is based on the device that is disconnected, but generally would result in a loss of coverage.', '1. Baseline not completed – verify that all components are connected to the system and are showing up properly in the GUI, then perform the Baseline\n2. Device lost power – locate device showing as disconnected.  Verify the unit is powered on.  Check power levels from source.\n3. Device disconnected from system – locate device showing as disconnected.  Verify connectivity to system – replace connecting cables if necessary.\n4. If device is connected and powered on, and is still showing as disconnected, try power cycling the device to restore communication.');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy)
VALUES (1327, 'ORU Disconnected', 2, 'Baseline not completed / Device lost power / Device disconnected from system', 'This is based on the device that is disconnected, but generally would result in a loss of coverage.', '1. Baseline not completed – verify that all components are connected to the system and are showing up properly in the GUI, then perform the Baseline\n2. Device lost power – locate device showing as disconnected.  Verify the unit is powered on.  Check power levels from source.\n3. Device disconnected from system – locate device showing as disconnected.  Verify connectivity to system – replace connecting cables if necessary.\n4. If device is connected and powered on, and is still showing as disconnected, try power cycling the device to restore communication.');

REPLACE INTO css_alarms_dictionary (device_type_id, alarm_description, severity_id, cause, impact, remedy)
VALUES (1314, 'HX Disconnected', 2, 'Baseline not completed / Device lost power / Device disconnected from system', 'This is based on the device that is disconnected, but generally would result in a loss of coverage.', '1. Baseline not completed – verify that all components are connected to the system and are showing up properly in the GUI, then perform the Baseline\n2. Device lost power – locate device showing as disconnected.  Verify the unit is powered on.  Check power levels from source.\n3. Device disconnected from system – locate device showing as disconnected.  Verify connectivity to system – replace connecting cables if necessary.\n4. If device is connected and powered on, and is still showing as disconnected, try power cycling the device to restore communication.');


RAWSQL
            );
            // R7.3.1 - B7162
            DB::unprepared(<<<RAWSQL
-- -------------------------------------------------------------
-- Bug 7162 - Reset table contents & new structures for Wifi Agg
-- -------------------------------------------------------------
DROP TABLE IF EXISTS css_snmp_alarm_map;
CREATE TABLE css_snmp_alarm_map (
  id int(11) NOT NULL AUTO_INCREMENT,
  alarm_id int(10) DEFAULT NULL,
  clear_bit bit(1) DEFAULT NULL,
  original_inc_payload text,
  formatted_inc_payload text,
  out_payload text,
  sent bit(1) DEFAULT b'0',
  sent_time datetime(3) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
DROP TRIGGER IF EXISTS format_incoming__css_snmp_alarm_map;
DELIMITER ;;
CREATE TRIGGER format_incoming__css_snmp_alarm_map BEFORE INSERT ON css_snmp_alarm_map FOR EACH ROW BEGIN
    IF NEW.formatted_inc_payload IS NULL AND NEW.original_inc_payload IS NOT NULL
    THEN
      SET NEW.formatted_inc_payload = format_trap_payload(NEW.original_inc_payload);
    END IF;
END
;;
DELIMITER ;

-- ----------------------------
-- Table structure for css_snmp_incoming_trap
-- ----------------------------
DROP TABLE IF EXISTS css_snmp_incoming_trap;
CREATE TABLE css_snmp_incoming_trap (
  id int(11) NOT NULL AUTO_INCREMENT,
  program_name varchar(60) DEFAULT NULL,
  from_host varchar(60) DEFAULT NULL,
  ip_address char(15) DEFAULT NULL,
  device_id int(10) DEFAULT NULL,
  device_reported_time datetime(3) DEFAULT NULL,
  received_at datetime(3) DEFAULT NULL,
  original_payload text,
  formatted_payload text,
  invalid_duplicate bit(1) DEFAULT b'0',
  redundant_inc_trap_id int(11) DEFAULT NULL,
  unknown_mib bit(1) DEFAULT b'0',
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=64719 DEFAULT CHARSET=latin1;
DROP TRIGGER IF EXISTS format_incoming__css_snmp_incoming_trap;
DELIMITER ;;
CREATE TRIGGER format_incoming__css_snmp_incoming_trap BEFORE INSERT ON css_snmp_incoming_trap FOR EACH ROW BEGIN
    DECLARE ip char(15) DEFAULT NEW.ip_address;

    IF NEW.device_id IS NULL AND ip IS NOT NULL
    THEN
      SET NEW.device_id =
      (SELECT dv.id AS 'device_id'
       FROM css_networking_device dv
       INNER JOIN css_networking_network_tree nt ON dv.id = nt.device_id
       INNER JOIN css_networking_network_tree_map ntm ON nt.id = ntm.node_id AND ntm.deleted = 0 AND ntm.build_in_progress = 0 AND ntm.visible = 1
       WHERE dv.ip_address = ip
       ORDER BY dv.id DESC
       LIMIT 1);
    END IF;

    IF NEW.formatted_payload IS NULL AND NEW.original_payload IS NOT NULL
    THEN
      SET NEW.formatted_payload = format_trap_payload(NEW.original_payload);
    END IF;
END
;;
DELIMITER ;


RAWSQL
            );
            // R7.3.1 - B7231
            DB::unprepared(<<<RAWSQL
-- START
REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children, main_device,build_file,scan_file,controller_file)
VALUES
(9,1077,"GENERAC","H Panel",1,1,0,1,1,"generacHpanel_builder_launcher.php","generacHpanel_scanner_launcher.php","generacHpanelController.php");
-- END





-- START
INSERT IGNORE INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(9,'telnet','MODBUS',502);
INSERT IGNORE INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(9,'snmp','SNMP',161);
-- END


-- START
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','oil_temperature','Oil Temperature (F)','DECIMAL','0','2','Lube Oil Temperature');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','coolant_temperature','Coolant Temperature (F)','DECIMAL','0','2','Coolant Temperature');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','oil_pressure','Oil Pressure (PSI)','DECIMAL','0','2','Oil Pressure (psi)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','coolant_level','Coolant Level (%)','DECIMAL','0','2','Coolant Level (%)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','volume_percentlevel','Fuel Level %','DECIMAL','0','2','USER CFG 05');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','fuelPressure','Fuel Pressure (PSI)','DECIMAL','0','2','USER CFG 06');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','','throt_pos','Throttle Position (Steps)','DECIMAL','0','2','Throttle Position (Steps)',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','o2_sensor','Oxygen Sensor','DECIMAL','0','2','Throt Pos');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','user_cfg09','USER CFG 09','DECIMAL','0','2','USER CFG 09');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','battery_voltage','Battery Voltage (VDC)','DECIMAL','0','2','ECM Battery Voltage (VDC)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','current_phsa','Current Phase A (Amps)','DECIMAL','0','2','Current Phase A');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','current_phsb','Current Phase B (Amps)','DECIMAL','0','2','Current Phase B');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','current_phsc','Current Phase N (Amps)','DECIMAL','0','2','Current Phase C');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','avrg_current','Average Current (Amps)','DECIMAL','0','2','Current Phase C');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','volt_phsa','Voltage Phase A-N (Volts)','DECIMAL','0','2','Voltage Phase A');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','volt_phsb','Voltage Phase B-N (Volts)','DECIMAL','0','2','Voltage Phase B');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','volt_phsc','Voltage Phase A-B (Volts)','DECIMAL','0','2','Voltage Phase C');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','avrg_voltage','Average Voltage (Volts)','DECIMAL','0','2','Average Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','total_power','Total Power (KW)','DECIMAL','0','2','Total Power (KW)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','total_pf','Total Power Factor','DECIMAL','0','2','Total PF');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','gen_frequency','Generator Frequency (Hz)','DECIMAL','0','2','Generator Frequency (Hz)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','engine_rpm','Engine Speed (RPM)','DECIMAL','0','2','Engine RPM');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','af_duty_cycle','A/F Duty Cycle','DECIMAL','0','2','A/F Duty Cycle');

REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','1077','volume_tankvolume','Fuel Tank Capacity','INTEGER','1','1','The Fuel Tank Capacity Used by the Generator');
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','1077','gen_fuel_type','Fuel Type','INTEGER','1','1','The Fuel Type Used by the Generator');
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','1077','setRunTime','Generator Run Time (minutes)','INTEGER','1','1','Generator Run Time in minutes when start the generator');
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','1077','Generator Status','Generator Status','INTEGER','1','1','Generator Status Running/Not Running');
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','generator_running_state','Generator Running State','INTEGER','0','2','Generator Running State',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','1077','generator_running','Generator Running','INTEGER','0','2','Generator Running');
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','generator_report_request_time','generator_report_request_time','INTEGER','1','1','generator_report_request_time',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','generator_run_end_time','generator_run_end_time','INTEGER','1','1','generator_run_end_time',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','generator_run_start_time','generator_run_start_time','INTEGER','1','1','generator_run_start_time',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','relayState','relayState','INTEGER','1','1','relayState',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','start_time','start_time','INTEGER','1','1','start_time',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','generator_running_time','generator_running_time','INTEGER','1','1','generator_running_time',0);

REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','auto_switch','Auto Switch','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','manual_switch','Manual Switch','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','emergency_stop','Emergency Stop','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','remote_start','Remote Start','INTEGER','1','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','di1user_Cfg05','DI1/USR CFG 5','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','di2fuel_pressure','DI2/Fuel Pressure','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','di3line_power','DI3/Line Power','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','di4gen_pwer','DI4/GEN Power','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','modem_dcd','Modem DCD','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','modem_enabled','Modem Enabled','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','gen_overspeed','GEN Overspeed','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','huio1_cfg12','HUIO 1 CFG 12','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','huio1_cfg13','HUIO 1 CFG 13','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','huio1_cfg14','HUIO 1 CFG 14','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','huio1_cfg15','HUIO 1 CFG 15','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','huio2_cfg16','HUIO 2 CFG 16','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','common_alarm','Common Alarm','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','common_warning','Common Warning','INTEGER','0','1','',0);
-- REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','generator_running','Generator Running','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','alarm_enabled','Alarm Enabled','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','ready_for_load','Ready For Load','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','gen_ready_to_run','GEN Ready to Run','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','gen_stopped_alarm','GEN Stopped Alarm','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','gen_stopped','Gen Stopped','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','gen_in_maunal','Gen In Manual','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','gen_in_auto','GEN In Auto','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','gen_in_off','GEN In OFF','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','overcrank_alarm','OverCrank Alarm','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','oil_inhibit_alarm','Oil Inhibit Alarm','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','annunc_spr_light','Annunc SPR Light','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','oil_temp_hi_alarm','Oil Temperature Hi Alarm','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1077','oil_temp_low_alarm','Oil Temperature Low Alarm','INTEGER','0','1','',0);






-- END


-- START
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'0','Stop'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'Generator Status'
 )
 AND device_type_id in ('9');
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'1','Start'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'Generator Status'
 )
 AND device_type_id in ('9');
-- END

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'0','Propane'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'gen_fuel_type'
 )
 AND device_type_id in ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'1','Natural Gas'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'gen_fuel_type'
 )
 AND device_type_id in ('9');

 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'2','Diesel'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'gen_fuel_type'
 )
 AND device_type_id in ('9');


-- start
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','01' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','02' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3','03' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '4','04' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '5','05' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '6','06' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '7','07' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '8','08' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '9','09' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '10','10' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '11','11' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '12','12' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '13','13' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '14','14' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '15','15' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '16','16' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '17','17' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '18','18' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '19','19' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '20','20' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '21','21' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '22','22' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '23','23' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '24','24' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '25','25' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '26','26' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '27','27' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '28','28' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '29','29' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '30','30' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '31','31' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '32','32' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '33','33' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '34','34' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '35','35' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '36','36' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '37','37' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '38','38' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '39','39' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '40','40' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '41','41' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '42','42' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '43','43' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '44','44' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '45','45' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '46','46' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '47','47' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '48','48' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '49','49' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '50','50' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '51','51' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '52','52' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '53','53' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '54','54' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '55','55' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '56','56' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '57','57' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '58','58' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '59','59' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '60','60' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');
-- end

-- start









 REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Control','Control');
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('setRunTime','9','Control');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Generator Status','9','Control');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_fuel_type','9','Control');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('remote_start','9','Control');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('volume_tankvolume','9','Control');

     REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Device Parameters','Device Parameters');
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_in_off','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_in_auto','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_in_maunal','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_stopped','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_ready_to_run','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ready_for_load','9','Device Parameters');
      REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('auto_switch','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('manual_switch','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_stop','9','Device Parameters');
   REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('huio1_cfg12','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('huio1_cfg13','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('huio1_cfg14','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('huio1_cfg15','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('huio2_cfg16','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('user_cfg05','9','Device Parameters');
      REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('user_cfg06','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('user_cfg09','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('modem_enabled','9','Device Parameters');
    REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('di1user_Cfg05','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('di2fuel_pressure','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('di3line_power','9','Device Parameters');
      REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('di4gen_pwer','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_overspeed','9','Device Parameters');
       REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('annunc_spr_light','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('modem_dcd','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','9','Device Parameters');


  REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Parameters','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('oil_temperature','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('oil_pressure','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('coolant_temperature','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('coolant_level','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('volume_percentlevel','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('fuelPressure','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('throt_pos','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('o2_sensor','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('user_cfg09','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('battery_voltage','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('current_phsa','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('current_phsb','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('current_phsc','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('avrg_current','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('volt_phsa','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('volt_phsb','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('volt_phsc','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('avrg_voltage','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_power','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_pf','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('gen_frequency','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('engine_rpm','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('af_duty_cycle','9','Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('generator_running','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Generator Status','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('generator_running_state','9','Parameters');
-- end

 UPDATE css_networking_device_type SET uses_default_value=0 WHERE id=9;
RAWSQL
            );
            // R7.3.1 - B7266
            DB::unprepared(<<<RAWSQL
UPDATE def_generator_type
SET vendor = 'Generac - Standard 60kW Diesel' WHERE vendor = 'Generac - Standard 60KW Diesel';

UPDATE def_generator_type
SET vendor = 'Kohler - Standard 40kW Diesel' WHERE vendor = 'Kohler - Standard 40KW Diesel';

UPDATE def_generator_type
SET vendor = 'Kohler - Standard 60kW Diesel' WHERE vendor = 'Kohler - Standard 60KW Diesel';

UPDATE def_generator_type
SET vendor = 'MTU Onsite Energy - Standard 30kW Propane' WHERE vendor = 'MTU Onsite Energy - Standard 30KW Propane';

UPDATE def_generator_type
SET vendor = 'MTU Onsite Energy - Standard 30kW Diesel' WHERE vendor = 'MTU Onsite Energy - Standard 30KW Diesel';

UPDATE def_generator_type
SET vendor = 'MTU Onsite Energy - Standard 30kW Nat Gas' WHERE vendor = 'MTU Onsite Energy - Standard 30KW Nat Gas';

UPDATE def_generator_type
SET vendor = 'MTU Onsite Energy - Standard 40kW Propane' WHERE vendor = 'MTU Onsite Energy - Standard 40KW Propane';

UPDATE def_generator_type
SET vendor = 'MTU Onsite Energy - Standard 50kW Diesel' WHERE vendor = 'MTU Onsite Energy - Standard 50KW Diesel';

UPDATE def_generator_type
SET vendor = 'MTU Onsite Energy - Standard 50kW Propane' WHERE vendor = 'MTU Onsite Energy - Standard 50KW Propane';

UPDATE def_generator_type
SET vendor = 'MTU Onsite Energy - Standard 60kW Diesel' WHERE vendor = 'MTU Onsite Energy - Standard 60KW Diesel';

UPDATE def_generator_type
SET vendor = 'Katolight 60kW - Standard 60kW Diesel' WHERE vendor = 'Katolight 60KW - Standard 60KW Diesel';

RAWSQL
            );
            // R7.3.1 - B7289
            DB::unprepared(<<<RAWSQL
-- Add Status & Props For Remote Agent Units and its su-bdevices

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','PBT-ENTITYSTATUS-MIB::pbtEntityComStatus','pbtEntityComStatus','Entity Communication Status','INTEGER','0','2','This object describes the status of the communications with the specified entity.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','PBT-ENTITYSTATUS-MIB::pbtEntityComEntity','pbtEntityComEntity','Communication Entity Indix','INTEGER','0','1','This object provides a value equal to the entPhysicalIndex associated with this row.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','PBT-ENTITYSTATUS-MIB::pbtEntityCompositeStatus','pbtEntityCompositeStatus','Entity Composite Status','INTEGER','0','2','This object describes the status of the composite alarm of the specified entity.');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','PBT-REMOTE-AGENT-MIB::pbtRemoteUnitStatus','pbtRemoteUnitStatus','Remote Unit Status','INTEGER','0','2',' The status of the Module.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','PBT-REMOTE-AGENT-MIB::pbtRemoteUnitControl','pbtRemoteUnitControl','Remote Unit Control','INTEGER','1','1',' This variable controls the operation of the Module.For future use, Default value is zero.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','PBT-REMOTE-AGENT-MIB::pbtRaUnitTemperature','pbtRaUnitTemperature','Remote Unit Temperature','INTEGER','0','1',' The air temperature measured at the Module.Units:Degrees C');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','PBT-REMOTE-AGENT-MIB::pbtRaUnitHumidity','pbtRaUnitHumidity','Remote Unit Humidity','INTEGER','0','1',' The relative humidity measured at the Module.Units:%');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','PBT-REMOTE-AGENT-MIB::pbtRaUnitAcVoltage','pbtRaUnitAcVoltage','Remote Unit Ac Voltage','INTEGER','0','1',' The AC Line voltage measured by a plugin transformer at the Module.  Units:VAC');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','PBT-REMOTE-AGENT-MIB::pbtRaUnitBusVoltage','pbtRaUnitBusVoltage','Remote Unit Bus Voltage','INTEGER','0','1','The P-Bus supply voltage measured by the remote Module.  Units:0.01 VDC');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputMode','pbtRaInputMode','Remote Agent Input Mode','INTEGER','1','1','Input Mode.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputDigitalThreshold','pbtRaInputDigitalThreshold','Remote Agent Input Digital Threshold','INTEGER','1','1',' The InputDigitalThreshold controls the InputDigitalState.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputDigitalState','pbtRaInputDigitalState','Remote Agent Input Digital State','INTEGER','0','1',' State of the digital input.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputText','pbtRaInputText','Remote Agent Input Text','STRING','1','1',' String describing the Input.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputLowText','pbtRaInputLowText','Remote Agent Input LowText','STRING','1','1',' String describing the Input Low State.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputHighText','pbtRaInputHighText','Remote Agent Input High Text','STRING','1','1',' String describing the Input High State.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputAnalogRawValue','pbtRaInputAnalogRawValue','Remote Agent Input Analog Raw Value','INTEGER','0','1',' Raw analog input voltage in Volts DC * 100.Example:for 5.12 VDC input voltage the value is 512.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputAnalogValue','pbtRaInputAnalogValue','Remote Agent Input Analog Value','INTEGER','0','1',' Scaled Analog value =(RawValue * Slope) + Offset');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputAnalogSlopeNumerator','pbtRaInputAnalogSlopeNumerator','Remote Agent Input Analog Slope Numerator','INTEGER','1','1',' Used to calculate the slope used in the analog scaling.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputAnalogSlopeDenominator','pbtRaInputAnalogSlopeDenominator','Remote Agent Input Analog Slope Denominator','INTEGER','1','1',' Used to calculate the slope used in the analog scaling.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputAnalogOffsetNumerator','pbtRaInputAnalogOffsetNumerator','Remote Agent Input Analog Offset Numerator','INTEGER','1','1',' Used to calculate the Offset used in the analog scaling.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputAnalogOffsetDenominator','pbtRaInputAnalogOffsetDenominator','Remote Agent Input Analog Offset Denominator','INTEGER','1','1',' Used to calculate the Offset used in the analog scaling.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputAnalogUnits','pbtRaInputAnalogUnits','Remote Agent Input Analog Units','STRING','1','1','String describing the unit of measure.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5034','PBT-REMOTE-AGENT-MIB::pbtRaInputAnalogDecimalPlaces','pbtRaInputAnalogDecimalPlaces','Remote Agent Input Analog Decimal Places','INTEGER','1','1',' Number of Decimal places in the decocded Analog Value pbtRaInputAnalogValue.');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalDescr','entPhysicalDescr','entity Physical Description','STRING','0','2',' A textual description of physical entity. ');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalVendorType','entPhysicalVendorType','entity Physical Vendor Type','INTEGER','0','1','An indication of the vendor-specific hardware type of the physical entity.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalContainedIn','entPhysicalContainedIn','entity Physical Contained In','INTEGER','0','1',' The value of entPhysicalIndex for the physical entity which contains this physical entity.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalClass','entPhysicalClass','entity Physical Class','INTEGER','0','1','An indication of the general hardware type of the physical entity.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalParentRelPos','entPhysicalParentRelPos','entity Physical Parent Relive Position','INTEGER','0','1','An indication of the relative position of this child component among all its sibling components.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalName','entPhysicalName','entity Physical Name','STRING','0','1',' The textual name of the physical entity. ');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalHardwareRev','entPhysicalHardwareRev','entity Physical Hardware Revision','STRING','0','1','The vendor-specific hardware revision string for the physical entity.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalFirmwareRev','entPhysicalFirmwareRev','entity Physical Firmware Revision','STRING','0','1','The vendor-specific firmware revision string for the physical entity.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalSoftwareRev','entPhysicalSoftwareRev','entity Physical Software Revision','STRING','0','1','The vendor-specific software revision string for the physical entity.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalSerialNum','entPhysicalSerialNum','entity Physical Serial Number','STRING','1','1','The vendor-specific serial number string for the physical entity. ');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalMfgName','entPhysicalMfgName','entity Physical Manufacturer Name','STRING','0','1','The name of the manufacturer of this physical component.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalModelName','entPhysicalModelName','entity Physical Model Name','INTEGER','0','1','The vendor-specific model name identifier string associated with this physical component.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalAlias','entPhysicalAlias','entity Physical Alias Name','STRING','1','1','This object is an alias name for the physical entity as specified by a network manager, and provides a non-volatile handle for the physical entity.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalAssetID','entPhysicalAssetID','entity Physical Asset ID','INTEGER','1','1','This object is a user-assigned asset tracking identifier for the physical entity as specified by a network manager, and provides non-volatile storage of this information.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('5033','ENTITY-MIB::entPhysicalIsFRU','entPhysicalIsFRU','Field Replaceable Unit','INTEGER','0','1','This object indicates whether or not this physical entity is considered a field replaceable unit by the vendor.');


-- Define Status & Props GROUPS

REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Set Input Name','Set Input Name');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Set Input Mode','Set Input Mode');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Setup Digital Input','Setup Digital Input');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Setup Analog Input','Setup Analog Input');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('General Info','General Info');

REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Remote Agent Info','Remote Agent Info');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Remote Agent Info\\Environmental','Remote Agent Info\\Environmental');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('General Info','General Info');

REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Set Input Name','Set Input Name');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Set Input Mode','Set Input Mode');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Setup Digital Input','Setup Digital Input');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Setup Analog Input','Setup Analog Input');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('General Info','General Info');

REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Remote Agent Info','Remote Agent Info');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Remote Agent Info\\Environmental','Remote Agent Info\\Environmental');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('General Info','General Info');

-- Define Status & Props Group Map

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('pbtEntityComStatus','5033','Remote Agent Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtEntityComEntity','5033','Remote Agent Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('pbtEntityCompositeStatus','5033','Remote Agent Info');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRemoteUnitStatus','5033','Remote Agent Info\\Environmental');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRemoteUnitControl','5033','Remote Agent Info\\Environmental');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaUnitTemperature','5033','Remote Agent Info\\Environmental');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaUnitHumidity','5033','Remote Agent Info\\Environmental');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaUnitAcVoltage','5033','Remote Agent Info\\Environmental');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaUnitBusVoltage','5033','Remote Agent Info\\Environmental');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputMode','5034','Set Input Mode');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputDigitalThreshold','5034','Setup Digital Input');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputDigitalState','5034','Setup Digital Input');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputText','5034','Set Input Name');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputLowText','5034','Setup Digital Input');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputHighText','5034','Setup Digital Input');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputAnalogRawValue','5034','Setup Analog Input');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputAnalogValue','5034','Setup Analog Input');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputAnalogSlopeNumerator','5034','Setup Analog Input');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputAnalogSlopeDenominator','5034','Setup Analog Input');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputAnalogOffsetNumerator','5034','Setup Analog Input');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputAnalogOffsetDenominator','5034','Setup Analog Input');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputAnalogUnits','5034','Setup Analog Input');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('pbtRaInputAnalogDecimalPlaces','5034','Setup Analog Input');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalVendorType','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalContainedIn','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalClass','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalParentRelPos','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalMfgName','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalModelName','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalAlias','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalAssetID','5033','General Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalIsFRU','5033','General Info');

RAWSQL
            );
            // R7.3.1 - B7316
            DB::unprepared(<<<RAWSQL
UPDATE css_networking_device_type SET build_file='SiteGateBuilderLauncher.php' WHERE id=5000;
RAWSQL
            );
            // R7.3.1 - B7346
            DB::unprepared(<<<RAWSQL
UPDATE css_networking_device_prop_def
SET name = 'Blower (G) System 1'
WHERE device_type_id = 1428 and  variable_name = 'Blower (G) Sysetem 1';

UPDATE css_networking_device_prop_def
SET name = 'Blower (G) System 2'
WHERE device_type_id = 1428 and  variable_name = 'Blower (G) Sysetm 2';

UPDATE css_networking_device_prop_def
SET name = 'Heater (W) System 1'
WHERE device_type_id = 1428 and  variable_name = 'Heater (W) Sysetm 1';

UPDATE css_networking_device_prop_def
SET name = 'Heater (W) System 2'
WHERE device_type_id = 1428 and  variable_name = 'Heater (W) Sysetm 2';

UPDATE css_networking_device_prop_def
SET name = 'Lead System System 1'
WHERE device_type_id = 1428 and  variable_name = 'Lead System Sysetm 1';

UPDATE css_networking_device_prop_def
SET name = 'Lead System System 2'
WHERE device_type_id = 1428 and  variable_name = 'Lead System Sysetm 2';

UPDATE css_networking_device_prop_def
SET name = 'Power Loss System 1'
WHERE device_type_id = 1428 and  variable_name = 'Power Loss Sysetm 1';

UPDATE css_networking_device_prop_def
SET name = 'Power Loss System 2'
WHERE device_type_id = 1428 AND variable_name = 'Power Loss Sysetm 2';

UPDATE css_networking_device_prop_def
SET name = 'Economizer System 1'
WHERE device_type_id = 1428 and  variable_name = 'Economizer Sysetm 1';

UPDATE css_networking_device_prop_def
SET name = 'Economizer System 2'
WHERE device_type_id = 1428 and  variable_name = 'Economizer Sysetm 2';

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
