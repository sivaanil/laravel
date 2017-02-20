<?php

// brings cswapi database from 7.2.3 to 7.3.0
// (step 2 of SiteGate 2.5 to SiteGate 2.6)

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportSiteportal73Changes extends Migration
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
            // R7.3 - B148
            DB::unprepared(<<<RAWSQL
-- START Change the uses_snmp from 1 to 0. Wayne 09042015
UPDATE css_networking_device_type SET uses_snmp = '0' WHERE id = '1321';
UPDATE css_networking_device_type SET uses_snmp = '0' WHERE id = '1322';
UPDATE css_networking_device_type SET uses_snmp = '0' WHERE id = '1323';
-- END Change the uses_snmp from 1 to 0. Wayne 09042015
RAWSQL
            );
            // R7.3 - B1990
            DB::unprepared(<<<RAWSQL
-- Update Specific RMX3200 Alarms
UPDATE css_networking_device_alarm da
join css_networking_device d on (d.id= da.device_id)
join css_networking_device_type dt on (dt.id= d.type_id)
SET da.description = ( CASE 
	WHEN locate('is high', da.notes) THEN left(da.notes, locate('is high', da.notes) + 6)
	WHEN locate('is low', da.notes) THEN left(da.notes, locate('is low', da.notes) + 5)
	WHEN locate('is normal', da.notes) THEN left(da.notes, locate('is normal', da.notes) + 8)
	ELSE da.description
END )
WHERE dt.vendor = 'Westell' and (da.notes like ('%is low%') or da.notes like ('%is high%') or da.notes like ('%is normal%'));

RAWSQL
            );
            // R7.3 - B2074
            DB::unprepared(<<<RAWSQL
INSERT INTO `css_networking_report_types` (`id`, `report_type`, `alarm_settings`, `coord_inherit`, `blank_values`, `type_id`, `generator_settings`, `help_text`)
 VALUES ('44', 'MicroWave Report', '0', '0', '0', '0', '0', 'This report will produce an Excel spreadsheet containing information about MicroWave usage on the selected node for the selected time frame.')
ON DUPLICATE KEY UPDATE id = '44', report_type = 'MicroWave Report', alarm_settings = '0', coord_inherit = '0', 
blank_values = '0' , type_id = '0', generator_settings = '0', help_text = 'This report will produce an Excel spreadsheet containing information about MicroWave usage on the selected node for the selected time frame.'
;


CREATE TABLE IF NOT EXISTS `data_microwave` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `group_node_id` int(11) DEFAULT NULL,
  `group_name` varchar(64) DEFAULT NULL,
  `device_id` int(11) DEFAULT NULL,
  `device_name` varchar(64) DEFAULT NULL,
  `rsl` varchar(64) DEFAULT NULL,
  `rslMax` varchar(64) DEFAULT NULL,
  `rslMin` varchar(64) DEFAULT NULL,
  `currentThru` varchar(64) DEFAULT NULL,
  `maxThru` varchar(64) DEFAULT NULL,
  `minThru` varchar(64) DEFAULT NULL,
  `txStartFreq` varchar(64) DEFAULT NULL,
  `txEndFreq` varchar(64) DEFAULT NULL,
  `txPower` varchar(64) DEFAULT NULL,
  `rxStartFreq` varchar(64) DEFAULT NULL,
  `rxEndFreq` varchar(64) DEFAULT NULL,
  `devicePath` varchar(255) DEFAULT NULL,
  `rxThru` varchar(64) DEFAULT NULL,
  `txThru` varchar(64) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `BER` varchar(64) DEFAULT NULL,
  `Model` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1025 DEFAULT CHARSET=latin1;
RAWSQL
            );
            // R7.3 - B4403
            DB::unprepared(<<<RAWSQL
DROP PROCEDURE IF EXISTS GetPoorServiceTimeForNodeProcedure;
DROP PROCEDURE IF EXISTS GetPoorServiceTimeForNodeProceedure;

CREATE DEFINER = 'root'@'%' PROCEDURE GetPoorServiceTimeForNodeProcedure(IN DeviceId INT, IN ClearedBit INT, IN TimeStart VARCHAR(100), 
					IN TimeStop VARCHAR(100), IN SeverityList VARCHAR(25), IN IgnoredString VARCHAR(25))
BEGIN


		SET @deviceId = DeviceId;
		SET @clearedBit = ClearedBit;
		SET @timeStart = TimeStart;
		SET @timeStop = TimeStop;
		SET @severityList = SeverityList;
		SET @ignoredString = IgnoredString;
		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_ServiceAffectingAlarmsEnd;
		CREATE TEMPORARY TABLE tmp_ServiceAffectingAlarmsEnd AS (
		SELECT b.ts, -1 as type, @row_number:=@row_number+1 AS e, NULL AS s
		FROM( SELECT IFNULL(a.cleared,@timeStop) AS ts
					FROM css_networking_device_alarm a
					INNER JOIN css_networking_device d ON (d.id = a.device_id)
					INNER JOIN css_alarms_dictionary ad ON ( a.description = ad.alarm_description AND d.type_id = ad.device_type_id AND ad.severity_id IN (@severityList))
					WHERE (((a.cleared_bit = @clearedBit) OR (a.cleared > @timeStart)) AND (a.raised < @timeStop)) 
						AND ad.rf_effect = 1 AND a.device_id = @deviceId AND a.ignored IN (0, @ignoredString)
					)b
		ORDER BY b.ts);

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_ServiceAffectingAlarmsStart;
		CREATE TEMPORARY TABLE tmp_ServiceAffectingAlarmsStart AS(
		SELECT b.ts, 1 as type, NULL AS e, @row_number:=@row_number+1 AS s
		FROM( SELECT CASE WHEN ( a.raised <= @timeStart )
											THEN @timeStart ELSE a.raised 
											END AS ts
					FROM css_networking_device_alarm a
					INNER JOIN css_networking_device d ON (d.id = a.device_id)
					INNER JOIN css_alarms_dictionary ad ON ( a.description = ad.alarm_description AND d.type_id = ad.device_type_id AND ad.severity_id IN (@severityList))
					WHERE (((a.cleared_bit = @clearedBit) OR (a.cleared > @timeStart)) AND (a.raised < @timeStop)) 
						AND ad.rf_effect = 1 AND a.device_id = @deviceId AND a.ignored IN (0, @ignoredString)
					)b
		ORDER BY b.ts);
		
		DROP TABLE IF EXISTS tmp_mergeAlarmResults;
		CREATE TEMPORARY TABLE tmp_mergeAlarmResults AS(
		SELECT a.*
		FROM ( SELECT * FROM tmp_ServiceAffectingAlarmsEnd
						UNION ALL
						SELECT * FROM tmp_ServiceAffectingAlarmsStart) a );

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_mergeAlarmResultsOrdered;
		CREATE TEMPORARY TABLE tmp_mergeAlarmResultsOrdered AS(
		SELECT mar.*, @row_number:=@row_number+1 AS se  
		FROM tmp_mergeAlarmResults mar
		ORDER BY mar.ts, mar.type DESC);

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_mergeOverlapsRemoved;
		CREATE TEMPORARY TABLE tmp_mergeOverlapsRemoved AS(
		SELECT ts, @row_number:=@row_number+1 AS row, CEILING(@row_number/2) AS pair
		FROM tmp_mergeAlarmResultsOrdered
		WHERE COALESCE(s-(se-s)-1, (se-e)-e) = 0);

		SELECT IFNULL(SUM(TIME_TO_SEC(TIMEDIFF(a.end_time,a.start_time))), 0) AS poor_service_time_sec
		FROM ( SELECT MIN(ts) AS start_time, MAX(ts) AS end_time
					FROM tmp_mergeOverlapsRemoved
					GROUP BY pair)a;

END

RAWSQL
            );
            DB::unprepared(<<<RAWSQL

DROP PROCEDURE IF EXISTS GetServiceOutageTimeForNodeProcedure;
DROP PROCEDURE IF EXISTS GetServiceOutageTimeForNodeProceedure;

CREATE DEFINER = 'root'@'%' PROCEDURE GetServiceOutageTimeForNodeProcedure(IN DeviceId INT, IN ClearedBit INT, IN TimeStart VARCHAR(100), IN TimeStop VARCHAR(100), IN SeverityList VARCHAR(25), IN IgnoredString VARCHAR(25))
BEGIN

		SET @deviceId = DeviceId;
		SET @clearedBit = ClearedBit;
		SET @timeStart = TimeStart;
		SET @timeStop = TimeStop;
		SET @severityList = SeverityList;
		SET @ignoredString = IgnoredString;
		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_ServiceAffectingAlarmsEnd;
		CREATE TEMPORARY TABLE tmp_ServiceAffectingAlarmsEnd AS (
		SELECT b.ts, -1 as type, @row_number:=@row_number+1 AS e, NULL AS s
		FROM( SELECT IFNULL(a.cleared,@timeStop) AS ts
					FROM css_networking_device_alarm a
					INNER JOIN css_networking_device d ON (d.id = a.device_id)
					INNER JOIN css_alarms_dictionary ad ON ( a.description = ad.alarm_description AND d.type_id = ad.device_type_id AND ad.severity_id IN (@severityList))
					WHERE (((a.cleared_bit = @clearedBit) OR (a.cleared > @timeStart)) AND (a.raised < @timeStop)) 
						AND ad.rf_effect = 2 AND a.device_id = @deviceId AND a.ignored IN (0, @ignoredString)
					)b
		ORDER BY b.ts);

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_ServiceAffectingAlarmsStart;
		CREATE TEMPORARY TABLE tmp_ServiceAffectingAlarmsStart AS(
		SELECT b.ts, 1 as type, NULL AS e, @row_number:=@row_number+1 AS s
		FROM( SELECT CASE WHEN ( a.raised < @timeStart )
											THEN @timeStart ELSE a.raised 
											END AS ts
					FROM css_networking_device_alarm a
					INNER JOIN css_networking_device d ON (d.id = a.device_id)
					INNER JOIN css_alarms_dictionary ad ON ( a.description = ad.alarm_description AND d.type_id = ad.device_type_id AND ad.severity_id IN (@severityList))
					WHERE (((a.cleared_bit = @clearedBit) OR (a.cleared > @timeStart)) AND (a.raised < @timeStop)) 
						AND ad.rf_effect = 2 AND a.device_id = @deviceId AND a.ignored IN (0, @ignoredString)
					)b
		ORDER BY b.ts);

		DROP TABLE IF EXISTS tmp_mergeAlarmResults;
		CREATE TEMPORARY TABLE tmp_mergeAlarmResults AS(
		SELECT a.*
		FROM ( SELECT * FROM tmp_ServiceAffectingAlarmsEnd
						UNION ALL
						SELECT * FROM tmp_ServiceAffectingAlarmsStart) a );

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_mergeAlarmResultsOrdered;
		CREATE TEMPORARY TABLE tmp_mergeAlarmResultsOrdered AS(
		SELECT mar.*, @row_number:=@row_number+1 AS se  
		FROM tmp_mergeAlarmResults mar
		ORDER BY mar.ts, mar.type DESC);

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_mergeOverlapsRemoved;
		CREATE TEMPORARY TABLE tmp_mergeOverlapsRemoved AS(
		SELECT ts, @row_number:=@row_number+1 AS row, CEILING(@row_number/2) AS pair
		FROM tmp_mergeAlarmResultsOrdered
		WHERE COALESCE(s-(se-s)-1, (se-e)-e) = 0);

		SELECT IFNULL(SUM(TIME_TO_SEC(TIMEDIFF(a.end_time,a.start_time))), 0) AS no_service_time_sec
		FROM ( SELECT MIN(ts) AS start_time, MAX(ts) AS end_time
					FROM tmp_mergeOverlapsRemoved
					GROUP BY pair)a;

END
RAWSQL
            );
            // R7.3 - B4587
            DB::unprepared(<<<RAWSQL
-- START Change the class_id to 11. Wayne 10092015
UPDATE css_networking_device_type SET class_id='11' WHERE id='1327';
UPDATE css_networking_device_type SET class_id='11' WHERE id='1314';
-- END Change the class_id to 11. Wayne 10092015
RAWSQL
            );
            // R7.3 - 4643
            DB::unprepared(<<<RAWSQL


-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Bug 4643 - Report Template Table - PHP Notice: "Undefined property: stdClass::\$reporting_abnormal_fuel_consumption" is getting generated, but this variable seems to be unused by any report in the code reporting_abnormal_fuel_consumption, I will be removing it.
-- --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = 'css_networking_report_template'
        AND table_schema = DATABASE()
        AND column_name = 'reporting_abnormal_fuel_consumption'
    ) = 0,
    'SELECT 1',
    'ALTER TABLE css_networking_report_template DROP COLUMN reporting_abnormal_fuel_consumption'
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- -------------------------------------------------------------------------------------------
-- End of script
-- -------------------------------------------------------------------------------------------

RAWSQL
            );
            // R7.3 - B4849
            DB::unprepared(<<<RAWSQL
DROP PROCEDURE IF EXISTS `GetPoorServiceTimeForNodeProceedure`
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
CREATE DEFINER = `root`@`%` PROCEDURE `GetPoorServiceTimeForNodeProceedure`(IN DeviceId INT, IN ClearedBit INT, IN TimeStart VARCHAR(100), IN TimeStop VARCHAR(100), IN IgnoredString VARCHAR(25))
		COMMENT 'This stored procedure for the Raw RF Uptime Report, to get poor service time in seconds for a device id for a time period based on alarm data.'
BEGIN


		SET @deviceId = DeviceId;
		SET @clearedBit = ClearedBit;
		SET @timeStart = TimeStart;
		SET @timeStop = TimeStop;
		SET @ignoredString = IgnoredString;

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_ServiceAffectingAlarmsEnd;
		CREATE TEMPORARY TABLE tmp_ServiceAffectingAlarmsEnd AS (
		SELECT b.ts, -1 AS type, @row_number:=@row_number+1 AS e, NULL AS s
		FROM( SELECT IFNULL(a.cleared,@timeStop) AS ts
					FROM css_networking_device_alarm a
					INNER JOIN css_networking_device d ON (d.id = a.device_id)
					INNER JOIN css_alarms_dictionary ad ON ( a.description = ad.alarm_description AND d.type_id = ad.device_type_id AND a.severity_id IN (1,2))
					WHERE (((a.cleared_bit = @clearedBit) OR (a.cleared > @timeStart)) AND (a.raised < @timeStop)) 
						AND ad.rf_effect = 1 AND a.device_id = @deviceId AND a.ignored IN (0, @ignoredString)
					)b
		ORDER BY b.ts);

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_ServiceAffectingAlarmsStart;
		CREATE TEMPORARY TABLE tmp_ServiceAffectingAlarmsStart AS(
		SELECT b.ts, 1 AS type, NULL AS e, @row_number:=@row_number+1 AS s
		FROM( SELECT CASE WHEN ( a.raised <= @timeStart )
											THEN @timeStart ELSE a.raised 
											END AS ts
					FROM css_networking_device_alarm a
					INNER JOIN css_networking_device d ON (d.id = a.device_id)
					INNER JOIN css_alarms_dictionary ad ON ( a.description = ad.alarm_description AND d.type_id = ad.device_type_id AND a.severity_id IN (1,2))
					WHERE (((a.cleared_bit = @clearedBit) OR (a.cleared > @timeStart)) AND (a.raised < @timeStop)) 
						AND ad.rf_effect = 1 AND a.device_id = @deviceId AND a.ignored IN (0, @ignoredString)
					)b
		ORDER BY b.ts);
		
		DROP TABLE IF EXISTS tmp_mergeAlarmResults;
		CREATE TEMPORARY TABLE tmp_mergeAlarmResults AS(
		SELECT a.*
		FROM ( SELECT * FROM tmp_ServiceAffectingAlarmsEnd
						UNION ALL
						SELECT * FROM tmp_ServiceAffectingAlarmsStart) a );

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_mergeAlarmResultsOrdered;
		CREATE TEMPORARY TABLE tmp_mergeAlarmResultsOrdered AS(
		SELECT mar.*, @row_number:=@row_number+1 AS se  
		FROM tmp_mergeAlarmResults mar
		ORDER BY mar.ts, mar.type DESC);

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_mergeOverlapsRemoved;
		CREATE TEMPORARY TABLE tmp_mergeOverlapsRemoved AS(
		SELECT ts, @row_number:=@row_number+1 AS row, CEILING(@row_number/2) AS pair
		FROM tmp_mergeAlarmResultsOrdered
		WHERE COALESCE(s-(se-s)-1, (se-e)-e) = 0);

		SELECT IFNULL(SUM(TIME_TO_SEC(TIMEDIFF(a.end_time,a.start_time))), 0) AS poor_service_time_sec
		FROM ( SELECT MIN(ts) AS start_time, MAX(ts) AS end_time
					FROM tmp_mergeOverlapsRemoved
					GROUP BY pair)a;

END
RAWSQL
            );
            DB::unprepared(<<<RAWSQL

DROP PROCEDURE IF EXISTS `GetServiceOutageTimeForNodeProceedure`;
RAWSQL
            );
            DB::unprepared(<<<RAWSQL

CREATE DEFINER = `root`@`%` PROCEDURE `GetServiceOutageTimeForNodeProceedure`(IN DeviceId INT, IN ClearedBit INT, IN TimeStart VARCHAR(100), IN TimeStop VARCHAR(100), IN IgnoredString VARCHAR(25))
		COMMENT 'This stored procedure for the Raw RF Uptime Report, to get service outage time in seconds for a device id for a time period based on alarm data.'
BEGIN

		SET @deviceId = DeviceId;
		SET @clearedBit = ClearedBit;
		SET @timeStart = TimeStart;
		SET @timeStop = TimeStop;
		SET @ignoredString = IgnoredString;

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_ServiceAffectingAlarmsEnd;
		CREATE TEMPORARY TABLE tmp_ServiceAffectingAlarmsEnd AS (
		SELECT b.ts, -1 AS type, @row_number:=@row_number+1 AS e, NULL AS s
		FROM( SELECT IFNULL(a.cleared,@timeStop) AS ts
					FROM css_networking_device_alarm a
					INNER JOIN css_networking_device d ON (d.id = a.device_id)
					INNER JOIN css_alarms_dictionary ad ON ( a.description = ad.alarm_description AND d.type_id = ad.device_type_id AND a.severity_id IN (1,2))
					WHERE (((a.cleared_bit = @clearedBit) OR (a.cleared > @timeStart)) AND (a.raised < @timeStop)) 
						AND ad.rf_effect = 2 AND a.device_id = @deviceId AND a.ignored IN (0, @ignoredString)
					)b
		ORDER BY b.ts);

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_ServiceAffectingAlarmsStart;
		CREATE TEMPORARY TABLE tmp_ServiceAffectingAlarmsStart AS(
		SELECT b.ts, 1 AS type, NULL AS e, @row_number:=@row_number+1 AS s
		FROM( SELECT CASE WHEN ( a.raised < @timeStart )
											THEN @timeStart ELSE a.raised 
											END AS ts
					FROM css_networking_device_alarm a
					INNER JOIN css_networking_device d ON (d.id = a.device_id)
					INNER JOIN css_alarms_dictionary ad ON ( a.description = ad.alarm_description AND d.type_id = ad.device_type_id AND a.severity_id IN (1,2))
					WHERE (((a.cleared_bit = @clearedBit) OR (a.cleared > @timeStart)) AND (a.raised < @timeStop)) 
						AND ad.rf_effect = 2 AND a.device_id = @deviceId AND a.ignored IN (0, @ignoredString)
					)b
		ORDER BY b.ts);

		DROP TABLE IF EXISTS tmp_mergeAlarmResults;
		CREATE TEMPORARY TABLE tmp_mergeAlarmResults AS(
		SELECT a.*
		FROM ( SELECT * FROM tmp_ServiceAffectingAlarmsEnd
						UNION ALL
						SELECT * FROM tmp_ServiceAffectingAlarmsStart) a );

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_mergeAlarmResultsOrdered;
		CREATE TEMPORARY TABLE tmp_mergeAlarmResultsOrdered AS(
		SELECT mar.*, @row_number:=@row_number+1 AS se  
		FROM tmp_mergeAlarmResults mar
		ORDER BY mar.ts, mar.type DESC);

		SET @row_number:=0;

		DROP TABLE IF EXISTS tmp_mergeOverlapsRemoved;
		CREATE TEMPORARY TABLE tmp_mergeOverlapsRemoved AS(
		SELECT ts, @row_number:=@row_number+1 AS row, CEILING(@row_number/2) AS pair
		FROM tmp_mergeAlarmResultsOrdered
		WHERE COALESCE(s-(se-s)-1, (se-e)-e) = 0);

		SELECT IFNULL(SUM(TIME_TO_SEC(TIMEDIFF(a.end_time,a.start_time))), 0) AS no_service_time_sec
		FROM ( SELECT MIN(ts) AS start_time, MAX(ts) AS end_time
					FROM tmp_mergeOverlapsRemoved
					GROUP BY pair)a;

END
RAWSQL
            );
            // R7.3 - B4864
            DB::unprepared(<<<RAWSQL
DROP FUNCTION IF EXISTS `format_trap_payload`;
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
CREATE DEFINER=`root`@`localhost` FUNCTION `format_trap_payload`(original_payload text) RETURNS text CHARSET latin1
BEGIN
  DECLARE formatted_payload text DEFAULT original_payload;
  SET formatted_payload = REPLACE(formatted_payload, ' ', '');
  SET formatted_payload = REPLACE(formatted_payload, '\\\\', '');
  SET formatted_payload = REPLACE(formatted_payload, '[', '');
  SET formatted_payload = REPLACE(formatted_payload, ']', '');
  SET formatted_payload = REPLACE(formatted_payload, ':', '');
  SET formatted_payload = REPLACE(formatted_payload, '#011', '');
  SET formatted_payload = REPLACE(formatted_payload, '#012', '');
  SET formatted_payload = REPLACE(formatted_payload, '#015', '');
  SET formatted_payload = REPLACE(formatted_payload, '\\n', '');
  SET formatted_payload = REPLACE(formatted_payload, '\\t', '');
  RETURN formatted_payload;
END
RAWSQL
            ); 
            DB::unprepared(<<<RAWSQL
-- ----------------------------
-- Change report type 41 to Wifi Aggregation SLA Report
-- ----------------------------
UPDATE css_networking_report_types SET report_type = 'Wifi Aggregation SLA Report' WHERE id = 41;

-- ----------------------------
-- Table structure for css_snmp_incoming_trap
-- ----------------------------
DROP TABLE IF EXISTS `css_snmp_incoming_trap`;
CREATE TABLE `css_snmp_incoming_trap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_name` varchar(60) DEFAULT NULL,
  `from_host` varchar(60) DEFAULT NULL,
  `ip_address` char(15) DEFAULT NULL,
  `device_id` int(10) DEFAULT NULL,
  `device_reported_time` datetime DEFAULT NULL,
  `received_at` datetime DEFAULT NULL,
  `original_payload` text,
  `formatted_payload` text,
  `invalid_duplicate` bit(1) DEFAULT b'0',
  `redundant_inc_trap_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
DROP TRIGGER IF EXISTS `format_incoming__css_snmp_incoming_trap`;
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
CREATE TRIGGER `format_incoming__css_snmp_incoming_trap` BEFORE INSERT ON `css_snmp_incoming_trap` FOR EACH ROW BEGIN
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
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
DROP FUNCTION IF EXISTS `format_trap_payload`;
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
CREATE DEFINER=`root`@`localhost` FUNCTION `format_trap_payload`(original_payload text) RETURNS text CHARSET latin1
BEGIN
  DECLARE formatted_payload text DEFAULT original_payload;
  SET formatted_payload = REPLACE(formatted_payload, ' ', '');
  SET formatted_payload = REPLACE(formatted_payload, '\\\\', '');
  SET formatted_payload = REPLACE(formatted_payload, '[', '');
  SET formatted_payload = REPLACE(formatted_payload, ']', '');
  SET formatted_payload = REPLACE(formatted_payload, ':', '');
  SET formatted_payload = REPLACE(formatted_payload, '#011', '');
  SET formatted_payload = REPLACE(formatted_payload, '#012', '');
  SET formatted_payload = REPLACE(formatted_payload, '#015', '');
  SET formatted_payload = REPLACE(formatted_payload, '\\n', '');
  SET formatted_payload = REPLACE(formatted_payload, '\\t', '');
  RETURN formatted_payload;
END
RAWSQL
            );
            // R7.3 - B5215
            DB::unprepared(<<<RAWSQL


-- Start add Class for Transfer Switch Tareq 073115
SET FOREIGN_KEY_CHECKS = 0;
REPLACE into css_networking_device_class (id,description, is_license) VALUES (1152,"Transfer Switch",1);
-- End add Class for Transfer Switch Tareq 073115

-- ----------------------------
-- Start add device type for ASCO Tareq 073115
-- ----------------------------
REPLACE INTO css_networking_device_type(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,defaultWebUiUser,defaultWebUiPw, main_device,build_file,scan_file,prop_scan_file,controller_file) VALUES (1599,26,"ASCO","5140QEM",0,1,1,1,"monitor","ASCO",1,"asco_builder.php","asco_alarms_launcher.php","asco_props_launcher.php","asco_controller.php");
-- ----------------------------
-- End add device type for ASCO Tareq  073115
-- ----------------------------

-- ----------------------------
-- Start add device type for ASCO Automatic Transfer Switch Tareq 073115
-- ----------------------------
REPLACE INTO css_networking_device_type (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device,controller_file) VALUES (1597,1152,"ASCO","Automatic Transfer Switch",0,1,0,0,1,0,"asco_controller.php");
-- ----------------------------
-- End add device type for ASCO Automatic Transfer Switch Tareq 073115
-- ----------------------------

-- ----------------------------
-- Start add device type for ASCO Power Meter Tareq 073115
-- ----------------------------
REPLACE INTO css_networking_device_type (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device,controller_file) VALUES (1598,1116,"ASCO","Power Meter",0,1,0,0,1,0,"asco_controller.php");
-- ----------------------------
-- End add device type for ASCO Power Meter Tareq  073115
-- ----------------------------

-- ----------------------------
-- Start add device type for ASCO Normal DPM Tareq 073115
-- ----------------------------
REPLACE INTO css_networking_device_type (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device,controller_file) VALUES (1593,1116,"ASCO","Normal DPM",0,1,0,0,1,0,"asco_controller.php");
-- ----------------------------
-- End add device type for ASCO Normal DPM Tareq  073115
-- ----------------------------

-- ----------------------------
-- Start add device type for ASCO Load DPM Tareq 073115
-- ----------------------------
REPLACE INTO css_networking_device_type (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device,controller_file) VALUES (1594,1116,"ASCO","Load DPM",0,1,0,0,1,0,"asco_controller.php");
-- ----------------------------
-- End add device type for ASCO Load DPM Tareq  073115
-- ----------------------------

-- ----------------------------
-- Start add device type for ASCO Emergency DPM Tareq 073115
-- ----------------------------
REPLACE INTO css_networking_device_type (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device,controller_file) VALUES (1595,1116,"ASCO","Emergency DPM",0,1,0,0,1,0,"asco_controller.php");
-- ----------------------------
-- End add device type for ASCO Emergency DPM Tareq  073115
-- ----------------------------

-- ----------------------------
-- Start add device type for ASCO Standalone DPM Tareq 073115
-- ----------------------------
REPLACE INTO css_networking_device_type (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device,controller_file) VALUES (1596,1116,"ASCO","Standalone DPM",0,1,0,0,1,0,"asco_controller.php");
-- ----------------------------
-- End add device type for ASCO Standalone DPM Tareq  073115
-- ----------------------------


-- Start add SNMP port for ASCO Tareq 073115
REPLACE INTO css_networking_device_port_def (device_type_id,variable_name,name,default_port) VALUES (1599,'snmp','SNMP',161);
-- End add SNMP port for ASCO Tareq 073115

-- Start add HTTP port for ASCO Tareq 073115
REPLACE INTO css_networking_device_port_def (device_type_id,variable_name,name,default_port) VALUES (1599,'http','HTTP',80);
-- End add HTTP port for  ASCO 073115

RAWSQL
            );
            // R7.3 - B5230
            DB::unprepared(<<<RAWSQL

SET foreign_key_checks = 0;

-- START change editable to 1 to enable new value option for some properties for MobileAccess ONE. Tareq 091115
UPDATE css_networking_device_prop_def SET editable=1 WHERE variable_name IN ('hcmModuleName','acmModuleName','rimModuleName','rimULGain','rimULGainMode','rimALC','rimMaxPower','rauModuleName','rauAntenna','rxuModuleName','oimModuleName') AND device_type_id IN(1506,1509,1507,1510,1511,1514);
-- END change editable to 1 to enable new value option for some properties for MobileAccess ONE. Tareq 091115

-- START change prop_type_id for some properties for MobileAccess ONE. Tareq 091115
UPDATE css_networking_device_prop_def SET prop_type_id=1 WHERE variable_name IN ('rimULGain','rimMaxPower') AND device_type_id=1507;
-- END change prop_type_id for some properties for MobileAccess ONE. Tareq 091115

-- START update name and tooltip for some properties for MobileAccess ONE. Tareq 091115
UPDATE css_networking_device_prop_def SET name='Band1 DL Power', tooltip='RAU Power per band ' WHERE variable_name='rauDlPowerBand1' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name='Band2 DL Power', tooltip='RAU Power per band ' WHERE variable_name='rauDlPowerBand2' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name='Band3 DL Power', tooltip='RAU Power per band ' WHERE variable_name='rauDlPowerBand3' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name='Band4 DL Power', tooltip='RAU Power per band ' WHERE variable_name='rauDlPowerBand4' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name='Antenna State', tooltip='Antenna State' WHERE variable_name='rxuAntenna' AND device_type_id=1511;
UPDATE css_networking_device_prop_def SET name='Band1 DL Power', tooltip='RAU Power per band ' WHERE variable_name='rxuDlPowerBand1' AND device_type_id=1511;
UPDATE css_networking_device_prop_def SET name='Band2 DL Power', tooltip='RAU Power per band ' WHERE variable_name='rxuDlPowerBand2' AND device_type_id=1511;
-- END update name and tooltip for some properties for MobileAccess ONE. Tareq 091115

-- START Add new properties\\statuses for MobileAccess ONE. Tareq 091115
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1506','hcmOverallStatus','hcmOverallStatus','Overall Status','INTEGER','0','1',' HCM Overall Status');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1506','hcmSetLocalDHCP','hcmGetLocalDHCP','Local IP DHCP','INTEGER','1','1',' Get Local IP DHCP');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1506','hcmSetLanDHCP','hcmGetLanDHCP','LAN DHCP','STRING','1','1','LAN DHCP');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1506','hcmSetLanIPAddress','hcmGetLanIPAddress','LAN IP Address','STRING','1','1','LAN IP Address');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1506','hcmSetLocalIPAddress','hcmGetLocalIPAddress','Local IP Address','STRING','1','1','Local IP Address');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1506','hcmSetLocalSubMask','hcmGetLocalSubMask','Local Subnet Mask','STRING','1','1','Local Subnet Mask');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1506','hcmSetLanSubMask','hcmGetLanSubMask','LAN Subnet Mask','STRING','1','1','LAN Subnet Mask');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1506','hcmSetLanDefaultGateway','hcmGetLanDefaultGateway','LAN Default Gateway ','STRING','1','1','LAN Default Gateway ');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1509','acmOverallStatus','acmOverallStatus','Overall Status','INTEGER','0','1','Overall Status');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1509','acmChassisType','acmChassisType',' Chassis Type','INTEGER','0','1',' Chassis Type');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1509','acmNumberOfResets','acmNumberOfResets',' ACM Number Of Resets','INTEGER','0','1',' ACM Number Of Resets');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1509','acmUpTime','acmUpTime',' ACM Up time','INTEGER','0','2',' ACM Up time');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1507','rimServiceGroup1','rimServiceGroup1','RF group 1 state','INTEGER','0','1','RF group 1 state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1507','rimServiceGroup2','rimServiceGroup2','RF group 2 state','INTEGER','0','1','RF group 2 state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1507','rimServiceGroup3','rimServiceGroup3','RF group 3 state','INTEGER','0','1','RF group 3 state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1507','rimULPowerDetector','rimULPowerDetector','Detected UL Power','INTEGER','0','2','RIM Detected UL Power');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1507','rimNumberOfResets','rimNumberOfResets','Number Of Resets','INTEGER','0','2','Number Of Resets');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1507','rimUpTime','rimUpTime','Up time','INTEGER','0','2','Up time');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1507','rimServiceState','rimServiceState','Service State','INTEGER','1','1','RIM service state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauOverallStatus','rauOverallStatus','Overall Status','INTEGER','0','1','Overall Status');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauUlLimiterBand1','rauUlLimiterBand1','Band1  UL Limiter state','INTEGER','0','1','Band1  UL Limiter state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauUlLimiterBand2','rauUlLimiterBand2','Band2  UL Limiter state','INTEGER','0','1','Band2  UL Limiter state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauUlLimiterBand3','rauUlLimiterBand3','Band3  UL Limiter state','INTEGER','0','1','Band3  UL Limiter state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauUlLimiterBand4','rauUlLimiterBand4','Band4  UL Limiter state','INTEGER','0','1','Band4  UL Limiter state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauType','rauType','Type','INTEGER','0','1',' RAU type');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauAntennaMode','rauAntennaMode',' Antenna mode','INTEGER','1','1',' RAU Antenna mode - (Open ANT sense OFF) or (Short\\ Resist ANT sense ON) or internal (internal is read only) ');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauServiceStateServ1','rauServiceStateServ1','Service 1 state','INTEGER','1','1','Service 1 state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauServiceStateServ2','rauServiceStateServ2','Service 2 state','INTEGER','1','1','Service 2 state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauServiceStateServ3','rauServiceStateServ3','Service 3 state','INTEGER','1','1','Service 3 state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauServiceStateServ4','rauServiceStateServ4','Service 4 state','INTEGER','1','1','Service 4 state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauOutPowerServ1','rauOutPowerServ1','Service 1 output power','INTEGER','1','1','Service 1 output power');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauOutPowerServ2','rauOutPowerServ2','Service 2 output power','INTEGER','1','1','Service 2 output power');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauOutPowerServ3','rauOutPowerServ3','Service 3 output power','INTEGER','1','1','Service 3 output power');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauOutPowerServ4','rauOutPowerServ4','Service 4 output power','INTEGER','1','1','Service 4 output power');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauServ1ExtFilter','rauServ1ExtFilter','Service 1 Exteranl Filter','INTEGER','1','1','Service 1 Exteranl Filter');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauServ2ExtFilter','rauServ2ExtFilter','Service 2 Exteranl Filter','INTEGER','1','1','Service 2 Exteranl Filter');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauULPowerDetectorBand1','rauULPowerDetectorBand1','Uplink Link Power Detector Band 1','INTEGER','0','2','Uplink Link Power Detector Band 1');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauULPowerDetectorBand2','rauULPowerDetectorBand2','Uplink Link Power Detector Band 2','INTEGER','0','2','Uplink Link Power Detector Band 2');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauULPowerDetectorBand3','rauULPowerDetectorBand3','Uplink Link Power Detector Band 3','INTEGER','0','2','Uplink Link Power Detector Band 3');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauULPowerDetectorBand4','rauULPowerDetectorBand4','Uplink Link Power Detector Band 4','INTEGER','0','2','Uplink Link Power Detector Band 4');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauNumberOfResets','rauNumberOfResets','Number Of Resets','INTEGER','0','2','Number Of Resets');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauUpTime','rauUpTime','Up time','INTEGER','0','2','Up time');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuOverallStatus','rxuOverallStatus','Overall Status','INTEGER','0','1','Overall Status');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuUlLimiterBand1','rxuUlLimiterBand1','Band1 UL Limiter state','INTEGER','0','1','Band1 UL Limiter state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuUlLimiterBand2','rxuUlLimiterBand2','Band2 UL Limiter state','INTEGER','0','1','Band2 UL Limiter state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuAntennaMode','rxuAntennaMode','Antenna mode','INTEGER','0','1',' RXU Antenna mode - (Open ANT sense OFF) or (Short\\ Resist ANT sense ON) or internal ');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuServiceStateServ1','rxuServiceStateServ1','Service 1 state','INTEGER','1','1','Service 1 state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuServiceStateServ2','rxuServiceStateServ2','Service 2 state','INTEGER','1','1','Service 2 state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuServ1ExtFilter','rxuServ1ExtFilter','Exteranl Filter','INTEGER','1','1','Exteranl Filter');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuULPowerDetectorBand1','rxuULPowerDetectorBand1','Uplink Link Power Detector Band 1','INTEGER','0','2','Uplink Link Power Detector Band 1');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuULPowerDetectorBand2','rxuULPowerDetectorBand2','Uplink Link Power Detector Band 2','INTEGER','0','2','Uplink Link Power Detector Band 2');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuNumberOfResets','rxuNumberOfResets','Number Of Resets','INTEGER','0','2','Number Of Resets');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuUpTime','rxuUpTime','Up time','INTEGER','0','2','Up time');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1514','oimOverallStatus','oimOverallStatus','Overall Status','INTEGER','0','1','Overall Status');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1514','oimServiceGroup1','oimServiceGroup1','RF group 1 state','INTEGER','0','1','RF group 1 state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1514','oimServiceGroup2','oimServiceGroup2','RF group 2 state','INTEGER','0','1','RF group 2 state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1514','oimServiceGroup3','oimServiceGroup3','RF group 3 state','INTEGER','0','1','RF group 3 state');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1514','oimNumberOfResets','oimNumberOfResets','Number Of Resets','INTEGER','0','2','Number Of Resets');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1514','oimUpTime','oimUpTime',' Up time','INTEGER','0','2',' Up time');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1514','oimDLOpticalLossPort1','oimDLOpticalLossPort1','Down Link Optical Loss Link 1','INTEGER','0','2','Down Link Optical Loss Link 1');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1514','oimDLOpticalLossPort2','oimDLOpticalLossPort2','Down Link Optical Loss Link 2','INTEGER','0','2','Down Link Optical Loss Link 2');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1514','oimDLOpticalLossPort3','oimDLOpticalLossPort3','Down Link Optical Loss Link 3','INTEGER','0','2','Down Link Optical Loss Link 3');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1514','oimULOpticalLossPort1','oimULOpticalLossPort1','Up Link Optical Loss Link 1','INTEGER','0','2','Up Link Optical Loss Link 1');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1514','oimULOpticalLossPort2','oimULOpticalLossPort2','Up Link Optical Loss Link 2','INTEGER','0','2','Up Link Optical Loss Link 2');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1514','oimULOpticalLossPort3','oimULOpticalLossPort3','Up Link Optical Loss Link 3','INTEGER','0','2','Up Link Optical Loss Link 3');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauChassisIndex','rauChassisIndex','Chassis Index','INTEGER','1','0','Chassis Index');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauSlotIndex','rauSlotIndex','Slot Index','INTEGER','1','0','Slot Index');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1510','rauPortIndex','rauPortIndex','Port Index','INTEGER','1','0','Port Index');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuChassisIndex','rxuChassisIndex','Chassis Index','INTEGER','1','0','Chassis Index');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuSlotIndex','rxuSlotIndex','Slot Index','INTEGER','1','0','Slot Index');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuPortIndex','rxuPortIndex','Port Index','INTEGER','1','0','Port Index');
REPLACE INTO css_networking_device_prop_def (`device_type_id`,`snmp_oid`,`variable_name`, `name`, `data_type`,`editable`,`prop_type_id`,`tooltip`) VALUES ('1511','rxuRemoteIndex','rxuRemoteIndex','Remote Index','INTEGER','1','0','Remote Index');
-- END Add new properties\\statuses for MobileAccess ONE. Tareq 091115

-- START add the proporties that can be changed to prop_opts table for MobileAccess ONE. Tareq 091115
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'0','ON' FROM css_networking_device_prop_def WHERE variable_name in ('hcmAGCsw','rimServiceGroup1','rimServiceGroup2','rimServiceGroup3','rimServiceState','rauServ1ExtFilter','rauServ2ExtFilter','rauServiceStateServ1','rauServiceStateServ2','rauServiceStateServ3','rauServiceStateServ4','rauUlLimiterBand1','rauUlLimiterBand2','rauUlLimiterBand3','rauUlLimiterBand4','rxuServ1ExtFilter','rxuServiceStateServ1','rxuServiceStateServ2','rxuUlLimiterBand1','rxuUlLimiterBand2','oimServiceGroup1','oimServiceGroup2','oimServiceGroup3','rimALC'
,'mruServiceStateServ1','mruServiceStateServ2','mruServiceStateServ3','mruServiceStateServ4','mruServiceStateServ5') AND device_type_id IN (1506,1507,1509,1510,1511,1514,1592);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'1','OFF' FROM css_networking_device_prop_def WHERE variable_name in ('hcmAGCsw','rimServiceGroup1','rimServiceGroup2','rimServiceGroup3','rimServiceState','rauServ1ExtFilter','rauServ2ExtFilter','rauServiceStateServ1','rauServiceStateServ2','rauServiceStateServ3','rauServiceStateServ4','rauUlLimiterBand1','rauUlLimiterBand2','rauUlLimiterBand3','rauUlLimiterBand4','rxuServ1ExtFilter','rxuServiceStateServ1','rxuServiceStateServ2','rxuUlLimiterBand1','rxuUlLimiterBand2','oimServiceGroup1','oimServiceGroup2','oimServiceGroup3','rimALC'
,'mruServiceStateServ1','mruServiceStateServ2','mruServiceStateServ3','mruServiceStateServ4','mruServiceStateServ5') AND device_type_id IN (1506,1507,1509,1510,1511,1514,1592);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'0','None' FROM css_networking_device_prop_def WHERE variable_name in ('hcmGetLanDHCP') AND device_type_id IN (1506);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'1','Client' FROM css_networking_device_prop_def WHERE variable_name in ('hcmGetLanDHCP') AND device_type_id IN (1506);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'0','None' FROM css_networking_device_prop_def WHERE variable_name in ('hcmGetLocalDHCP') AND device_type_id IN (1506);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'1','Server' FROM css_networking_device_prop_def WHERE variable_name in ('hcmGetLocalDHCP') AND device_type_id IN (1506);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'0','Unknown' FROM css_networking_device_prop_def WHERE variable_name in ('acmChassisType') AND device_type_id IN (1509);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'1','HEU' FROM css_networking_device_prop_def WHERE variable_name in ('acmChassisType') AND device_type_id IN (1509);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'2','OIU' FROM css_networking_device_prop_def WHERE variable_name in ('acmChassisType') AND device_type_id IN (1509);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'3','IHU' FROM css_networking_device_prop_def WHERE variable_name in ('acmChassisType') AND device_type_id IN (1509);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'0','Internal' FROM css_networking_device_prop_def WHERE variable_name in ('rauAntenna','rxuAntenna') AND device_type_id IN (1510,1511);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'1','External' FROM css_networking_device_prop_def WHERE variable_name in ('rauAntenna','rxuAntenna') AND device_type_id IN (1510,1511);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'0','Open' FROM css_networking_device_prop_def WHERE variable_name in ('rauAntennaMode','rxuAntennaMode') AND device_type_id IN (1510,1511);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'1','ShortOrResist' FROM css_networking_device_prop_def WHERE variable_name in ('rauAntennaMode','rxuAntennaMode') AND device_type_id IN (1510,1511);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'2','Internal' FROM css_networking_device_prop_def WHERE variable_name in ('rauAntennaMode','rxuAntennaMode') AND device_type_id IN (1510,1511);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'0','Unknown' FROM css_networking_device_prop_def WHERE variable_name in ('rauType') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'1','RAU' FROM css_networking_device_prop_def WHERE variable_name in ('rauType') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'2','RAU5' FROM css_networking_device_prop_def WHERE variable_name in ('rauType') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'0','Manual' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGainMode') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,'1','Autosymetrical' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGainMode') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,0,'00' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,1,'01' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,2,'02' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,3,'03' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,4,'04' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,5,'05' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,6,'06' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,7,'07' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,8,'08' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,9,'09' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,10,'10' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,11,'11' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,12,'12' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,13,'13' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,14,'14' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,15,'15' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,16,'16' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,17,'17' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,18,'18' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,19,'19' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,20,'20' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,21,'21' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,22,'22' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,23,'23' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,24,'24' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,25,'25' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,26,'26' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,27,'27' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,28,'28' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,29,'29' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,30,'30' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,31,'31' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,32,'32' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,33,'33' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,34,'34' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,35,'35' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,36,'36' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,37,'37' FROM css_networking_device_prop_def WHERE variable_name in ('rimMaxPower') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-19,'-19' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-18,'-18' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-17,'-17' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-16,'-16' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-15,'-15' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-14,'-14' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-13,'-13' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-12,'-12' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-11,'-11' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-10,'-10' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-9,'-09' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-8,'-08' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-7,'-07' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-6,'-06' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-5,'-05' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-4,'-04' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-3,'-03' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-2,'-02' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,-1,'-01' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,0,'00' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,1,'01' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,2,'02' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,3,'03' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,4,'04' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,5,'05' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,6,'06' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,7,'07' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,8,'08' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,9,'09' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,10,'10' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,11,'11' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,12,'12' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,13,'13' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,14,'14' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,15,'15' FROM css_networking_device_prop_def WHERE variable_name in ('rimULGain') AND device_type_id IN (1507);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,25,'25' FROM css_networking_device_prop_def WHERE variable_name in ('mruOutPowerServ1','mruOutPowerServ2') AND device_type_id IN (1592);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,26,'26' FROM css_networking_device_prop_def WHERE variable_name in ('mruOutPowerServ1','mruOutPowerServ2') AND device_type_id IN (1592);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,27,'27' FROM css_networking_device_prop_def WHERE variable_name in ('mruOutPowerServ1','mruOutPowerServ2') AND device_type_id IN (1592);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,28,'28' FROM css_networking_device_prop_def WHERE variable_name in ('mruOutPowerServ1','mruOutPowerServ2') AND device_type_id IN (1592);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,29,'29' FROM css_networking_device_prop_def WHERE variable_name in ('mruOutPowerServ1','mruOutPowerServ2') AND device_type_id IN (1592);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,30,'30' FROM css_networking_device_prop_def WHERE variable_name in ('mruOutPowerServ1','mruOutPowerServ2') AND device_type_id IN (1592);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,28,'28' FROM css_networking_device_prop_def WHERE variable_name in ('mruOutPowerServ3','mruOutPowerServ4','mruOutPowerServ5') AND device_type_id IN (1592);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,29,'29' FROM css_networking_device_prop_def WHERE variable_name in ('mruOutPowerServ3','mruOutPowerServ4','mruOutPowerServ5') AND device_type_id IN (1592);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,30,'30' FROM css_networking_device_prop_def WHERE variable_name in ('mruOutPowerServ3','mruOutPowerServ4','mruOutPowerServ5') AND device_type_id IN (1592);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,31,'31' FROM css_networking_device_prop_def WHERE variable_name in ('mruOutPowerServ3','mruOutPowerServ4','mruOutPowerServ5') AND device_type_id IN (1592);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,32,'32' FROM css_networking_device_prop_def WHERE variable_name in ('mruOutPowerServ3','mruOutPowerServ4','mruOutPowerServ5') AND device_type_id IN (1592);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,33,'33' FROM css_networking_device_prop_def WHERE variable_name in ('mruOutPowerServ3','mruOutPowerServ4','mruOutPowerServ5') AND device_type_id IN (1592);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,10,'10' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ1','rauOutPowerServ2') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,11,'11' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ1','rauOutPowerServ2') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,12,'12' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ1','rauOutPowerServ2') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,13,'13' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ1','rauOutPowerServ2') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,14,'14' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ1','rauOutPowerServ2') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,15,'15' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ1','rauOutPowerServ2') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,12,'12' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ3') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,13,'13' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ3') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,14,'14' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ3') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,15,'15' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ3') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,16,'16' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ3') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,17,'17' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ3') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,18,'18' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ3') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,19,'19' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ3') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,20,'20' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ3') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,13,'13' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ4') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,14,'14' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ4') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,15,'15' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ4') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,16,'16' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ4') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,17,'17' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ4') AND device_type_id IN (1510);
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id,18,'18' FROM css_networking_device_prop_def WHERE variable_name in ('rauOutPowerServ4') AND device_type_id IN (1510);
-- END add the proporties that can be changed to prop_opts table for MobileAccess ONE. Tareq 091115

-- START add the proporties groups table for MobileAccess ONE. Tareq 091415
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management','Management');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Module Info','Management\\Module Info');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Power Alarms','Management\\Power Alarms');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management\\RF Parameters','Management\\RF Parameters');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Optical Info','Management\\Optical Info');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Site Info','Management\\Site Info');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Admin','Admin');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Admin\\Firmware','Admin\\Firmware');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Admin\\Security','Admin\\Security');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Admin\\SNMP Config','Admin\\SNMP Config');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Admin\\IP Settings','Admin\\IP Settings');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management','Management');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Module Info','Management\\Module Info');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Power Alarms','Management\\Power Alarms');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\RF Parameters','Management\\RF Parameters');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Optical Info','Management\\Optical Info');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Site Info','Management\\Site Info');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Admin','Admin');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Admin\\Firmware','Admin\\Firmware');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Admin\\Security','Admin\\Security');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Admin\\SNMP Config','Admin\\SNMP Config');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Admin\\IP Settings','Admin\\IP Settings');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Alarm mask','Alarm mask');
-- END add the proporties groups table for MobileAccess ONE. Tareq 091415

-- START add the proporties groups map table for MobileAccess ONE. Tareq 091515
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmAGCsw','1506','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmChassisSerialNumber','1506','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmDefaultGateway','1506','Admin\\IP Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmGetLanDefaultGateway','1506','Admin\\IP Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmGetLanDHCP','1506','Admin\\IP Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmGetLanIPAddress','1506','Admin\\IP Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmGetLanSubMask','1506','Admin\\IP Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmGetLocalDHCP','1506','Admin\\IP Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmGetLocalIPAddress','1506','Admin\\IP Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmGetLocalSubMask','1506','Admin\\IP Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmIPAddress','1506','Admin\\IP Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmModuleName','1506','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmOverallStatus','1506','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmOwner','1506','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmSerialNumber','1506','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmSlotIndex','1506','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmSWVersionActive','1506','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hcmZoneInfo','1506','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1506','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('uptime','1506','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: HW Failure','1506','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Adjustment Fault','1506','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Faulty installation','1506','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Modules have been detected with mismatched versions','1506','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: There are Disconnected modules','1506','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('acmChassisSerialNumber','1509','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('acmChassisType','1509','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('acmIPAddress','1509','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('acmModuleName','1509','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('acmNumberOfResets','1509','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('acmOverallStatus','1509','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('acmOwner','1509','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('acmSerialNumber','1509','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('acmSWVersionActive','1509','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('acmZoneInfo','1509','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1509','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('OverAll Status','1509','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('acmInsideTemperature','1509','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('acmUpTime','1509','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Over Temperature','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: HW Failure','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Adjustment Fault','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Inconsistent Version','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: ACM Power Supply A over Temperature','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: ACM Power Supply B over Temperature','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: ACM Power Supply A Output Under Voltage','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: ACM Power Supply B Output Under Voltage','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: ACM Ext1 Clock down','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: ACM Ext2 Clock down','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: ACM Pilot Clock Down','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: ACM Power Supply A Input Under Voltage','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: ACM Power Supply B Input Under Voltage','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: PSM Failure','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: FAM Failure','1509','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1507','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('OverAll Status','1507','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimALC','1507','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimBand','1507','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimChassisSerialNumber','1507','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimIPAddress','1507','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimMaxPower','1507','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimModuleName','1507','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimOwner','1507','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimSerialNumber','1507','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimServiceGroup1','1507','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimServiceGroup2','1507','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimServiceGroup3','1507','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimSlotIndex','1507','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimSWVersionActive','1507','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimULGain','1507','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimULGainMode','1507','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimZoneInfo','1507','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimServiceState','1507','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rimUpTime','1507','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rimDetectedInPower','1507','Management\\RF Parameters');                                                                                                
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rimInsideTemperature','1507','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rimNumberOfResets','1507','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rimULPowerDetector','1507','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: RIM DL RF Low Input Power','1507','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: RIM DL Over Power','1507','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: RIM Service Off','1507','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: RIM Output Power','1507','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: dlout_pwr_h','1507','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: RIM Over Temperature','1507','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Inconsistent Version','1507','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Adjustment Fault','1507','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: HW Failure','1507','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: RIM UL Synthesizer Unlocked','1507','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: RIM DL Synthesizer Unlocked','1507','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: RIM Reference Clock Unlocked','1507','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: dlout_pwr_h','1507','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('oimChassisSerialNumber','1514','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('oimIPAddress','1514','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('oimModuleName','1514','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('oimOverallStatus','1514','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('oimOwner','1514','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('oimSerialNumber','1514','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('oimServiceGroup1','1514','Management\\Optical Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('oimServiceGroup2','1514','Management\\Optical Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('oimServiceGroup3','1514','Management\\Optical Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('oimSlotIndex','1514','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('oimSWVersionActive','1514','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('oimZoneInfo','1514','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1514','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('OverAll Status','1514','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('oimDLOpticalLossPort1','1514','Management\\Optical Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('oimDLOpticalLossPort2','1514','Management\\Optical Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('oimDLOpticalLossPort3','1514','Management\\Optical Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('oimInsideTemperature','1514','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('oimNumberOfResets','1514','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('oimULOpticalLossPort1','1514','Management\\Optical Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('oimULOpticalLossPort2','1514','Management\\Optical Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('oimULOpticalLossPort3','1514','Management\\Optical Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('oimUpTime','1514','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Optical power low port 1','1514','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Optical power low port 2','1514','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Optical power low port 3','1514','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: OIM Over Temperature','1514','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Inconsistent Version','1514','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Adjustment Fault','1514','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: HW Failure','1514','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Band1 info','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Band2 info','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Band3 info','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Band4 info','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1510','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('OverAll Status','1510','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauAntenna','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauAntennaMode','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauBands','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauChassisSerialNumber','1510','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauIPAddress','1510','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauModuleName','1510','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauOutPowerServ1','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauOutPowerServ2','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauOutPowerServ3','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauOutPowerServ4','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauOverallStatus','1510','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauOwner','1510','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauSerialNumber','1510','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauServ1ExtFilter','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauServ2ExtFilter','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauServiceStateServ1','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauServiceStateServ2','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauServiceStateServ3','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauServiceStateServ4','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauSWVersionActive','1510','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauType','1510','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauUlLimiterBand1','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauUlLimiterBand2','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauUlLimiterBand3','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauUlLimiterBand4','1510','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauZoneInfo','1510','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauDlPowerBand1','1510','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauDlPowerBand2','1510','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauDlPowerBand3','1510','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauDlPowerBand4','1510','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauInsideTemperature','1510','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauNumberOfResets','1510','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauULPowerDetectorBand1','1510','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauULPowerDetectorBand2','1510','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauULPowerDetectorBand3','1510','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauULPowerDetectorBand4','1510','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauUpTime','1510','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: RAU Over Temperature','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: RAU Antenna Disconnect','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Incorrect Version','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Adjustment Fault','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: HW Failure','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 1 Mask','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 2 Mask','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 3 Mask','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 4 Mask','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RAU RF Low Power','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RAU UL Over Power','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RAU Service Off','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RAU RF Low Power','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RAU UL Over Power','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RAU Service Off','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: RAU RF Low Power','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: RAU UL Over Power','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: RAU Service Off','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: RAU RF Low Power','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: RAU UL Over Power','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: RAU Service Off','1510','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1511','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('OverAll Status','1511','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuAntenna','1511','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuAntennaMode','1511','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuBands','1511','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuChassisSerialNumber','1511','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuIPAddress','1511','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuModuleName','1511','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuOverallStatus','1511','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuOwner','1511','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuSerialNumber','1511','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuServ1ExtFilter','1511','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuServiceStateServ1','1511','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuServiceStateServ2','1511','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuSWVersionActive','1511','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuUlLimiterBand1','1511','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuUlLimiterBand2','1511','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuZoneInfo','1511','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rxuDlPowerBand1','1511','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rxuDlPowerBand2','1511','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rxuInsideTemperature','1511','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rxuNumberOfResets','1511','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rxuULPowerDetectorBand1','1511','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rxuULPowerDetectorBand2','1511','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rxuUpTime','1511','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: RXU Over Temperature','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: RXU Antenna Disconnect','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Inconsistent Version','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Adjustment Fault','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: HW Failure','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: RXU Synthesizer Lock','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RXU RF Low','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RXU UL Over Power','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RXU Service Off','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RXU DL Synthesizer Lock','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RXU UL Synthesizer Lock','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RXU RF Low','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RXU UL Over Power','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RXU Service Off','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RXU DL Synthesizer Lock','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RXU UL Synthesizer Lock','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 1 Mask','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 2 Mask','1511','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Band1 info','1511','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Band2 info','1511','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruModuleName','1592','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruZoneInfo','1592','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruIPAddress','1592','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSerialNumber','1592','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruChassisSerialNumber','1592','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSWVersionActive','1592','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruInsideTemperature','1592','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruChassisIndex','1592','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSlotIndex','1592','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruPortIndex','1592','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruOverallStatus','1592','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruOwner','1592','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand1','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand2','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand3','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand4','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand5','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand1SN','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand2SN','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand3SN','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand4SN','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand5SN','1592','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruDlPowerBand1','1592','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruDlPowerBand2','1592','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruDlPowerBand3','1592','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruDlPowerBand4','1592','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruDlPowerBand5','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruUlLimiterBand1','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruUlLimiterBand2','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruUlLimiterBand3','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruUlLimiterBand4','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruUlLimiterBand5','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruServiceStateServ1','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruServiceStateServ2','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruServiceStateServ3','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruServiceStateServ4','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruServiceStateServ5','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruOutPowerServ1','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruOutPowerServ2','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruOutPowerServ3','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruOutPowerServ4','1592','Management\\RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruOutPowerServ5','1592','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruULPowerDetectorBand1','1592','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruULPowerDetectorBand2','1592','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruULPowerDetectorBand3','1592','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruULPowerDetectorBand4','1592','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruULPowerDetectorBand5','1592','Management\\RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruNumberOfResets','1592','Management\\Module Info');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruUpTime','1592','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: MRU Over Temperature','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: MRU Incorrect Version','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: MRU Adjustment Fault','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: MRU HW_Failure','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: MRU Door Open','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: MRU FAM Velocity','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: MRU Over Temperature When Door is Open','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: MRU Power Supply Alarm','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: MRU Optic Low','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: MRU OPTM-S Over Temperature','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: MRU Heat Exchanger Failure','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU RF Low Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU UL Over Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU Service off','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU VSWR','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU Module Shut Down','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU Module Permanent Shut Down','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU Module Over Temperature','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU Module Out Of Slot','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU Module Over Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU RF Low Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU UL Over Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU Service off','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU VSWR','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU Module Shut Down','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU Module Permanent Shut Down','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU Module Over Temperature','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU Module Out Of Slot','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU Module Over Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU RF Low Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU UL Over Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU Service off','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU VSWR','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU Module Shut Down','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU Module Permanent Shut Down','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU Module Over Temperature','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU Module Out Of Slot','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU Module Over Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU RF Low Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU UL Over Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU Service off','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU VSWR','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU Module Shut Down','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU Module Permanent Shut Down','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU Module Over Temperature','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU Module Out Of Slot','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU Module Over Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU RF Low Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU UL Over Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU Service off','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU VSWR','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU Module Shut Down','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU Module Permanent Shut Down','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU Module Over Temperature','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU Module Out Of Slot','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU Module Over Power','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 1 Mask','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 2 Mask','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 3 Mask','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 4 Mask','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 5 Mask','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: MRU Cabinet Door Alarm','1592','Alarm mask');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('OverAll Status','1592','Management\\Module Info');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1592','Management\\Module Info');
-- END add the proporties groups map table for MobileAccess ONE. Tareq 091515

-- START add Controller file for MobileAccess ONE Tareq 091515
UPDATE css_networking_device_type SET controller_file='mobileAccess_one_controller.php' WHERE id IN (1506,1509,1507,1514,1510,1511,1590,1591);
-- END add Controller file for MobileAccess ONE Tareq 091515


-- START add device type for MRU, RFM and FMM for MobileAccess ONE Tareq 091515
REPLACE INTO css_networking_device_type(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device)VALUES(1591,2,"MobileAccess","FRM",0,1,0,0,1,0);
REPLACE INTO css_networking_device_type(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device)VALUES(1590,2,"MobileAccess","FMM",0,1,0,0,1,0);
REPLACE INTO css_networking_device_type(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device)VALUES(1592,11,"MobileAccess","MRU",0,1,0,0,1,0);
-- END add device type for MRU, RFM and FMM for MobileAccess ONE Tareq 091515

-- START add props for the MRU MA ONE. Tareq 091515
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruModuleName','mruModuleName',' NAME','STRING','1','1',' NAME');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruZoneInfo','mruZoneInfo','Zone Info','STRING','0','1','Zone Info');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruIPAddress','mruIPAddress','IP Address','STRING','0','1','IP Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruSerialNumber','mruSerialNumber','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruChassisSerialNumber','mruChassisSerialNumber','Chassis Serial Number','STRING','0','1','Chassis Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruSWVersionActive','mruSWVersionActive','Software Version','STRING','0','1','Software Version');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruInsideTemperature','mruInsideTemperature','Temperature','INTEGER','0','2','Temperature');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruChassisIndex','mruChassisIndex','Chassis Index','INTEGER','0','1','Chassis Index');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruSlotIndex','mruSlotIndex','Slot Index','INTEGER','0','1','Slot Index');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruPortIndex','mruPortIndex','Port Index','INTEGER','0','1','Port Index');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruOverallStatus','mruOverallStatus','Overall Status','INTEGER','0','1','Overall Status');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruOwner','mruOwner','Owner','INTEGER','0','1','Owner');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruSupportedBand1','mruSupportedBand1','Supported band on slot 1','STRING','0','1','Supported band on slot 1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruSupportedBand2','mruSupportedBand2','Supported band on slot 2','STRING','0','1','Supported band on slot 2');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruSupportedBand3','mruSupportedBand3','Supported band on slot 3','STRING','0','1','Supported band on slot 3');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruSupportedBand4','mruSupportedBand4','Supported band on slot 4','STRING','0','1','Supported band on slot 4');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruSupportedBand5','mruSupportedBand5','Supported band on slot 5','STRING','0','1','Supported band on slot 5');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruSupportedBand1SN','mruSupportedBand1SN','SN on slot 1','STRING','0','1','SN on slot 1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruSupportedBand2SN','mruSupportedBand2SN','SN on slot 2','STRING','0','1','SN on slot 2');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruSupportedBand3SN','mruSupportedBand3SN','SN on slot 3','STRING','0','1','SN on slot 3');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruSupportedBand4SN','mruSupportedBand4SN','SN on slot 4','STRING','0','1','SN on slot 4');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruSupportedBand5SN','mruSupportedBand5SN','SN on slot 5','STRING','0','1','SN on slot 5');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruDlPowerBand1','mruDlPowerBand1','Band1 DL Power','INTEGER','0','2','Band1 DL Power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruDlPowerBand2','mruDlPowerBand2','Band2 DL Power','INTEGER','0','2','Band2 DL Power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruDlPowerBand3','mruDlPowerBand3','Band3 DL Power','INTEGER','0','2','Band3 DL Power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruDlPowerBand4','mruDlPowerBand4','Band4 DL Power','INTEGER','0','2','Band4 DL Power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruDlPowerBand5','mruDlPowerBand5','Band5 DL Power','INTEGER','0','2','Band5 DL Power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruUlLimiterBand1','mruUlLimiterBand1','Band1  UL Limiter state','INTEGER','0','1','Band1  UL Limiter state');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruUlLimiterBand2','mruUlLimiterBand2','Band2  UL Limiter state','INTEGER','0','1','Band2  UL Limiter state');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruUlLimiterBand3','mruUlLimiterBand3','Band3  UL Limiter state','INTEGER','0','1','Band3  UL Limiter state');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruUlLimiterBand4','mruUlLimiterBand4','Band4  UL Limiter state','INTEGER','0','1','Band4  UL Limiter state');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruUlLimiterBand5','mruUlLimiterBand5','Band5  UL Limiter state','INTEGER','0','1','Band5  UL Limiter state');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruServiceStateServ1','mruServiceStateServ1','service 1 state','INTEGER','1','1','service 1 state');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruServiceStateServ2','mruServiceStateServ2','service 2 state','INTEGER','1','1','service 2 state');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruServiceStateServ3','mruServiceStateServ3','service 3 state','INTEGER','1','1','service 3 state');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruServiceStateServ4','mruServiceStateServ4','service 4 state','INTEGER','1','1','service 4 state');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruServiceStateServ5','mruServiceStateServ5','service 5 state','INTEGER','1','1','service 5 state');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruOutPowerServ1','mruOutPowerServ1','service 1 output power','INTEGER','1','1','service 1 output power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruOutPowerServ2','mruOutPowerServ2','service 2 output power','INTEGER','1','1','service 2 output power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruOutPowerServ3','mruOutPowerServ3','service 3 output power','INTEGER','1','1','service 3 output power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruOutPowerServ4','mruOutPowerServ4','service 4 output power','INTEGER','1','1','service 4 output power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruOutPowerServ5','mruOutPowerServ5','service 5 output power','INTEGER','1','1','service 5 output power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruULPowerDetectorBand1','mruULPowerDetectorBand1','Band1 UL Power','INTEGER','0','2','Band1 UL Power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruULPowerDetectorBand2','mruULPowerDetectorBand2','Band2 UL Power','INTEGER','0','2','Band2 UL Power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruULPowerDetectorBand3','mruULPowerDetectorBand3','Band3 UL Power','INTEGER','0','2','Band3 UL Power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruULPowerDetectorBand4','mruULPowerDetectorBand4','Band4 UL Power','INTEGER','0','2','Band4 UL Power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruULPowerDetectorBand5','mruULPowerDetectorBand5','Band5 UL Power','INTEGER','0','2','Band5 UL Power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruNumberOfResets','mruNumberOfResets','Number Of Resets','INTEGER','0','2','Number Of Resets');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1592','mruUpTime','mruUpTime','Up time','INTEGER','0','2','Up time');
-- END add props for the MRU MA ONE. Tareq 091515

-- START Disable threshold for uptime status for MobileAccess one. Tareq 091715
UPDATE css_networking_device_prop_def SET thresh_enable=0 WHERE device_type_id IN (1509,1507,1510,1511,1592) AND variable_name IN ('acmUpTime','rimUpTime','rauUpTime','rxuUpTime','mruUpTime');
-- END Disable threshold for uptime status for MobileAccess one. Tareq 091715

-- START add rebuilder file to MobileAccess One. Tareq 092115
UPDATE css_networking_device_type SET rebuilder_file='mobile_access_one_rebuilder.php' WHERE id=1506;
-- END  add rebuilder file to MobileAccess One. Tareq 092115

-- START clean up some properties. Tareq 092215
UPDATE css_networking_device_prop_def SET name='ACM Number Of Resets', prop_type_id=2 WHERE variable_name='acmNumberOfResets' AND device_type_id=1509;
UPDATE css_networking_device_prop_def SET name='ACM Up time',prop_type_id=2 WHERE variable_name='acmUpTime' AND device_type_id=1509;
UPDATE css_networking_device_prop_def SET name='Antenna mode' WHERE variable_name='rauAntennaMode' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name='Chassis Type' WHERE variable_name='acmChassisType' AND device_type_id=1509;
UPDATE css_networking_device_prop_def SET name='MA RIM Owner' WHERE variable_name='rimOwner' AND device_type_id=1507;
UPDATE css_networking_device_prop_def SET name='NAME' WHERE variable_name='mruModuleName' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name='RF group 1 state' WHERE variable_name='oimServiceGroup1' AND device_type_id=1514;
UPDATE css_networking_device_prop_def SET name='RF group 2 state' WHERE variable_name='oimServiceGroup2' AND device_type_id=1514;
UPDATE css_networking_device_prop_def SET name='RF group 3 state' WHERE variable_name='oimServiceGroup3' AND device_type_id=1514;
UPDATE css_networking_device_prop_def SET name='Up time' WHERE variable_name='oimUpTime' AND device_type_id=1514;
UPDATE css_networking_device_prop_def SET name=	'Band1 DL Power (dBm)' WHERE variable_name='rauDlPowerBand1' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name=	'Band1 DL Power (dBm)' WHERE variable_name='rxuDlPowerBand1' AND device_type_id=1511;
UPDATE css_networking_device_prop_def SET name=	'Band1 DL Power (dBm)' WHERE variable_name='mruDlPowerBand1' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'Band1 UL Power (dBm)' WHERE variable_name='mruULPowerDetectorBand1' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'Band2 DL Power (dBm)' WHERE variable_name='rauDlPowerBand2' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name=	'Band2 DL Power (dBm)' WHERE variable_name='rxuDlPowerBand2' AND device_type_id=1511;
UPDATE css_networking_device_prop_def SET name=	'Band2 DL Power (dBm)' WHERE variable_name='mruDlPowerBand2' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'Band2 UL Power (dBm)' WHERE variable_name='mruULPowerDetectorBand2' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'Band3 DL Power (dBm)' WHERE variable_name='rauDlPowerBand3' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name=	'Band3 DL Power (dBm)' WHERE variable_name='mruDlPowerBand3' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'Band3 UL Power (dBm)' WHERE variable_name='mruULPowerDetectorBand3' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'Band4 DL Power (dBm)' WHERE variable_name='rauDlPowerBand4' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name=	'Band4 DL Power (dBm)' WHERE variable_name='mruDlPowerBand4' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'Band4 UL Power (dBm)' WHERE variable_name='mruULPowerDetectorBand4' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'Band5 DL Power (dBm)' WHERE variable_name='mruDlPowerBand5' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'Band5 UL Power (dBm)' WHERE variable_name='mruULPowerDetectorBand5' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'Detected Input Power (dBm)' WHERE variable_name='rimDetectedInPower' AND device_type_id=1507;
UPDATE css_networking_device_prop_def SET name=	'Detected UL Power (dBm)' WHERE variable_name='rimULPowerDetector' AND device_type_id=1507;
UPDATE css_networking_device_prop_def SET name=	'Max Power (dBm)' WHERE variable_name='rimMaxPower' AND device_type_id=1507;
UPDATE css_networking_device_prop_def SET name=	'Uplink Link Power Detector Band 1  (dBm)' WHERE variable_name='rauULPowerDetectorBand1' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name=	'Uplink Link Power Detector Band 1  (dBm)' WHERE variable_name='rxuULPowerDetectorBand1' AND device_type_id=1511;
UPDATE css_networking_device_prop_def SET name=	'Uplink Link Power Detector Band 2  (dBm)' WHERE variable_name='rauULPowerDetectorBand2' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name=	'Uplink Link Power Detector Band 2  (dBm)' WHERE variable_name='rxuULPowerDetectorBand2' AND device_type_id=1511;
UPDATE css_networking_device_prop_def SET name=	'Uplink Link Power Detector Band 3  (dBm)' WHERE variable_name='rauULPowerDetectorBand3' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name=	'Uplink Link Power Detector Band 4  (dBm)' WHERE variable_name='rauULPowerDetectorBand4' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name=	'service 1 output power  (dBm)' WHERE variable_name='mruOutPowerServ1' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'service 2 output power  (dBm)' WHERE variable_name='mruOutPowerServ2' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'service 3 output power  (dBm)' WHERE variable_name='mruOutPowerServ3' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'service 4 output power  (dBm)' WHERE variable_name='mruOutPowerServ4' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'service 5 output power  (dBm)' WHERE variable_name='mruOutPowerServ5' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'Temperature (C)' WHERE variable_name='rimInsideTemperature' AND device_type_id=1507;
UPDATE css_networking_device_prop_def SET name=	'Temperature (C)' WHERE variable_name='acmInsideTemperature' AND device_type_id=1509;
UPDATE css_networking_device_prop_def SET name=	'Temperature (C)' WHERE variable_name='rauInsideTemperature' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name=	'Temperature (C)' WHERE variable_name='rxuInsideTemperature' AND device_type_id=1511;
UPDATE css_networking_device_prop_def SET name=	'Temperature (C)' WHERE variable_name='oimInsideTemperature' AND device_type_id=1514;
UPDATE css_networking_device_prop_def SET name=	'Temperature (C)' WHERE variable_name='mruInsideTemperature' AND device_type_id=1592;
UPDATE css_networking_device_prop_def SET name=	'Up Link Optical Loss Link 1 (dB)' WHERE variable_name='oimULOpticalLossPort1' AND device_type_id=1514;
UPDATE css_networking_device_prop_def SET name=	'Up Link Optical Loss Link 2 (dB)' WHERE variable_name='oimULOpticalLossPort2' AND device_type_id=1514;
UPDATE css_networking_device_prop_def SET name=	'Up Link Optical Loss Link 3 (dB)' WHERE variable_name='oimULOpticalLossPort3' AND device_type_id=1514;
UPDATE css_networking_device_prop_def SET name=	'Down Link Optical Loss Link 3 (dB)' WHERE variable_name='oimDLOpticalLossPort3' AND device_type_id=1514;
UPDATE css_networking_device_prop_def SET name=	'Down Link Optical Loss Link 2 (dB)' WHERE variable_name='oimDLOpticalLossPort2' AND device_type_id=1514;
UPDATE css_networking_device_prop_def SET name=	'Down Link Optical Loss Link 1 (dB)' WHERE variable_name='oimDLOpticalLossPort1' AND device_type_id=1514;
UPDATE css_networking_device_prop_def SET name=	'Up-Link Gain (dB)' WHERE variable_name='rimULGain' AND device_type_id=1507;
UPDATE css_networking_device_prop_def SET name=	'Band1 UL Power (dBm)' WHERE variable_name='rauULPowerDetectorBand1' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name=	'Band2 UL Power (dBm)' WHERE variable_name='rauULPowerDetectorBand2' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name=	'Band3 UL Power (dBm)' WHERE variable_name='rauULPowerDetectorBand3' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name=	'Band4 UL Power (dBm)' WHERE variable_name='rauULPowerDetectorBand4' AND device_type_id=1510;
UPDATE css_networking_device_prop_def SET name=	'Band1 UL Power (dBm)' WHERE variable_name='rxuULPowerDetectorBand1' AND device_type_id=1511;
UPDATE css_networking_device_prop_def SET name=	'Band2 UL Power (dBm)' WHERE variable_name='rxuULPowerDetectorBand2' AND device_type_id=1511;
UPDATE css_networking_device_prop_def SET name=	'Up Time (Seconds)' WHERE variable_name IN ('rimUpTime','acmUpTime','rauUpTime','rxuUpTime','oimUpTime') AND device_type_id IN (1507,1509,1510,1511,1514);
-- END clean up some properties. Tareq 092215

-- START add separate groups for bands. Tareq 092215
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\RF Parameters\\Band 1','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\RF Parameters\\Band 2','Management\\RF Parameters\\Band 2');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\RF Parameters\\Band 3','Management\\RF Parameters\\Band 3');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\RF Parameters\\Band 4','Management\\RF Parameters\\Band 4');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\RF Parameters\\Band 5','Management\\RF Parameters\\Band 5');

REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management\\RF Parameters\\Band 1','Management\\RF Parameters\\Band 1');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management\\RF Parameters\\Band 2','Management\\RF Parameters\\Band 2');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management\\RF Parameters\\Band 3','Management\\RF Parameters\\Band 3');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management\\RF Parameters\\Band 4','Management\\RF Parameters\\Band 4');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management\\RF Parameters\\Band 5','Management\\RF Parameters\\Band 5');

REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Optical Info\\Band 1','Management\\Optical Info\\Band 1');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Optical Info\\Band 2','Management\\Optical Info\\Band 2');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Optical Info\\Band 3','Management\\Optical Info\\Band 3');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Optical Info\\Band 4','Management\\Optical Info\\Band 4');

REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Optical Info\\Band 1','Management\\Optical Info\\Band 1');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Optical Info\\Band 2','Management\\Optical Info\\Band 2');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Management\\Optical Info\\Band 3','Management\\Optical Info\\Band 3');

REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Alarm mask\\Band 1','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Alarm mask\\Band 2','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Alarm mask\\Band 3','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Alarm mask\\Band 4','Alarm mask\\Band 4');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Alarm mask\\Band 5','Alarm mask\\Band 5');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimServiceGroup1','1507','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimServiceGroup2','1507','Management\\RF Parameters\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rimServiceGroup3','1507','Management\\RF Parameters\\Band 3');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Optical power low port 1','1514','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Optical power low port 2','1514','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Optical power low port 3','1514','Alarm mask\\Band 3');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Band1 info','1510','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Band2 info','1510','Management\\RF Parameters\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Band3 info','1510','Management\\RF Parameters\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Band4 info','1510','Management\\RF Parameters\\Band 4');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauOutPowerServ1','1510','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauOutPowerServ2','1510','Management\\RF Parameters\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauOutPowerServ3','1510','Management\\RF Parameters\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauOutPowerServ4','1510','Management\\RF Parameters\\Band 4');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauServiceStateServ1','1510','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauServiceStateServ2','1510','Management\\RF Parameters\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauServiceStateServ3','1510','Management\\RF Parameters\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauServiceStateServ4','1510','Management\\RF Parameters\\Band 4');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauUlLimiterBand1','1510','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauUlLimiterBand2','1510','Management\\RF Parameters\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauUlLimiterBand3','1510','Management\\RF Parameters\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rauUlLimiterBand4','1510','Management\\RF Parameters\\Band 4');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauDlPowerBand1','1510','Management\\RF Parameters\\Band 1');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauDlPowerBand2','1510','Management\\RF Parameters\\Band 2');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauDlPowerBand3','1510','Management\\RF Parameters\\Band 3');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauDlPowerBand4','1510','Management\\RF Parameters\\Band 4');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauULPowerDetectorBand1','1510','Management\\RF Parameters\\Band 1');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauULPowerDetectorBand2','1510','Management\\RF Parameters\\Band 2');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauULPowerDetectorBand3','1510','Management\\RF Parameters\\Band 3');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rauULPowerDetectorBand4','1510','Management\\RF Parameters\\Band 4');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 1 Mask','1510','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 2 Mask','1510','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 3 Mask','1510','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 4 Mask','1510','Alarm mask\\Band 4');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RAU RF Low Power','1510','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RAU UL Over Power','1510','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RAU Service Off','1510','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RAU RF Low Power','1510','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RAU UL Over Power','1510','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RAU Service Off','1510','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: RAU RF Low Power','1510','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: RAU UL Over Power','1510','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: RAU Service Off','1510','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: RAU RF Low Power','1510','Alarm mask\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: RAU UL Over Power','1510','Alarm mask\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: RAU Service Off','1510','Alarm mask\\Band 4');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuServ1ExtFilter','1511','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuServiceStateServ1','1511','Management\\RF Parameters\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuServiceStateServ2','1511','Management\\RF Parameters\\Band 3');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuUlLimiterBand1','1511','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rxuUlLimiterBand2','1511','Management\\RF Parameters\\Band 2');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rxuDlPowerBand1','1511','Management\\RF Parameters\\Band 1');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rxuDlPowerBand2','1511','Management\\RF Parameters\\Band 2');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rxuULPowerDetectorBand1','1511','Management\\RF Parameters\\Band 1');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rxuULPowerDetectorBand2','1511','Management\\RF Parameters\\Band 2');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RXU RF Low','1511','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RXU UL Over Power','1511','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RXU Service Off','1511','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RXU DL Synthesizer Lock','1511','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: RXU UL Synthesizer Lock','1511','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RXU RF Low','1511','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RXU UL Over Power','1511','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RXU Service Off','1511','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RXU DL Synthesizer Lock','1511','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: RXU UL Synthesizer Lock','1511','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 1 Mask','1511','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 2 Mask','1511','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Band1 info','1511','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Band2 info','1511','Management\\RF Parameters\\Band 2');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand1','1592','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand2','1592','Management\\RF Parameters\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand3','1592','Management\\RF Parameters\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand4','1592','Management\\RF Parameters\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand5','1592','Management\\RF Parameters\\Band 5');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand1SN','1592','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand2SN','1592','Management\\RF Parameters\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand3SN','1592','Management\\RF Parameters\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand4SN','1592','Management\\RF Parameters\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruSupportedBand5SN','1592','Management\\RF Parameters\\Band 5');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruDlPowerBand1','1592','Management\\RF Parameters\\Band 1');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruDlPowerBand2','1592','Management\\RF Parameters\\Band 2');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruDlPowerBand3','1592','Management\\RF Parameters\\Band 3');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruDlPowerBand4','1592','Management\\RF Parameters\\Band 4');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruDlPowerBand5','1592','Management\\RF Parameters\\Band 5');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruUlLimiterBand1','1592','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruUlLimiterBand2','1592','Management\\RF Parameters\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruUlLimiterBand3','1592','Management\\RF Parameters\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruUlLimiterBand4','1592','Management\\RF Parameters\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruUlLimiterBand5','1592','Management\\RF Parameters\\Band 5');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruServiceStateServ1','1592','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruServiceStateServ2','1592','Management\\RF Parameters\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruServiceStateServ3','1592','Management\\RF Parameters\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruServiceStateServ4','1592','Management\\RF Parameters\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruServiceStateServ5','1592','Management\\RF Parameters\\Band 5');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruOutPowerServ1','1592','Management\\RF Parameters\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruOutPowerServ2','1592','Management\\RF Parameters\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruOutPowerServ3','1592','Management\\RF Parameters\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruOutPowerServ4','1592','Management\\RF Parameters\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('mruOutPowerServ5','1592','Management\\RF Parameters\\Band 5');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruULPowerDetectorBand1','1592','Management\\RF Parameters\\Band 1');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruULPowerDetectorBand2','1592','Management\\RF Parameters\\Band 2');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruULPowerDetectorBand3','1592','Management\\RF Parameters\\Band 3');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruULPowerDetectorBand4','1592','Management\\RF Parameters\\Band 4');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('mruULPowerDetectorBand5','1592','Management\\RF Parameters\\Band 5');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU RF Low Power','1592','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU UL Over Power','1592','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU Service off','1592','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU VSWR','1592','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU Module Shut Down','1592','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU Module Permanent Shut Down','1592','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU Module Over Temperature','1592','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU Module Out Of Slot','1592','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service1: MRU Module Over Power','1592','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU RF Low Power','1592','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU UL Over Power','1592','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU Service off','1592','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU VSWR','1592','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU Module Shut Down','1592','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU Module Permanent Shut Down','1592','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU Module Over Temperature','1592','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU Module Out Of Slot','1592','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service2: MRU Module Over Power','1592','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU RF Low Power','1592','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU UL Over Power','1592','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU Service off','1592','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU VSWR','1592','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU Module Shut Down','1592','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU Module Permanent Shut Down','1592','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU Module Over Temperature','1592','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU Module Out Of Slot','1592','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service3: MRU Module Over Power','1592','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU RF Low Power','1592','Alarm mask\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU UL Over Power','1592','Alarm mask\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU Service off','1592','Alarm mask\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU VSWR','1592','Alarm mask\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU Module Shut Down','1592','Alarm mask\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU Module Permanent Shut Down','1592','Alarm mask\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU Module Over Temperature','1592','Alarm mask\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU Module Out Of Slot','1592','Alarm mask\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service4: MRU Module Over Power','1592','Alarm mask\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU RF Low Power','1592','Alarm mask\\Band 5');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU UL Over Power','1592','Alarm mask\\Band 5');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU Service off','1592','Alarm mask\\Band 5');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU VSWR','1592','Alarm mask\\Band 5');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU Module Shut Down','1592','Alarm mask\\Band 5');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU Module Permanent Shut Down','1592','Alarm mask\\Band 5');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU Module Over Temperature','1592','Alarm mask\\Band 5');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU Module Out Of Slot','1592','Alarm mask\\Band 5');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask Service5: MRU Module Over Power','1592','Alarm mask\\Band 5');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 1 Mask','1592','Alarm mask\\Band 1');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 2 Mask','1592','Alarm mask\\Band 2');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 3 Mask','1592','Alarm mask\\Band 3');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 4 Mask','1592','Alarm mask\\Band 4');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Alarm mask: Service 5 Mask','1592','Alarm mask\\Band 5');
-- END add separate groups for bands. Tareq 092215
SET foreign_key_checks = 1;
RAWSQL
            );
            // R7.3 - B5254
            DB::unprepared(<<<RAWSQL

-- Add new groups to def_prop_groups
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Settings','Settings');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Voltage & Frequency','Voltage & Frequency');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Switch Status','Switch Status');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Identification','Identification');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Current','Current');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Engine Exerciser Program','Engine Exerciser Program');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Pickup & Dropout','Pickup & Dropout');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Voltage','Voltage');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Energy Consumption','Energy Consumption');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Power Quality','Power Quality');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Voltage Unbalance & Frequency','Voltage Unbalance & Frequency');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Power','Power');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Statistics','Statistics');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Max Demand Information','Max Demand Information');
-- End Add new groups to def_prop_groups

-- Start Add new groups to def_status_groups
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Settings','Settings');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Voltage & Frequency','Voltage & Frequency');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Switch Status','Switch Status');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Identification','Identification');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Current','Current');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Engine Exerciser Program','Engine Exerciser Program');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Pickup & Dropout','Pickup & Dropout');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Voltage','Voltage');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Energy Consumption','Energy Consumption');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Power Quality','Power Quality');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Voltage Unbalance & Frequency','Voltage Unbalance & Frequency');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Power','Power');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Statistics','Statistics');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Max Demand Information','Max Demand Information');
-- End Add new groups to def_status_groups


-- Start add prop_defs for ASCO devices 
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.1.0','phase_shift_between_normal_and_emergency','Phase shift between normal & emergency','INTEGER','0','1',' Phase shift between normal & emergency');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.2.0','normal_frequency','Normal frequency (Hz)','INTEGER','0','2',' Normal frequency');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.3.0','emergency_frequency','Emergency frequency (Hz)','INTEGER','0','2','Emergency frequency');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.4.0','main_on_normal','Main on normal','INTEGER','0','1',' Main on normal');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.5.0','main_on_emergency','Main on emergency','INTEGER','0','1','Main on emergency');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.6.0','auxiliary_on_normal','Auxiliary on normal','INTEGER','0','1','Auxiliary on normal');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.7.0','auxiliary_on_emergency','Auxiliary on emergency','INTEGER','0','1','Auxiliary on emergency');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.8.0','normal_source_available','Normal source','INTEGER','0','1',' Normal source available');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.9.0','emergency_source_available','Emergency source','INTEGER','0','1',' Emergency source available');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.10.0','engine_exerciser_with_load_active','Engine exerciser with load','INTEGER','0','1',' Engine exerciser with load active');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.11.0','external_f17_is_active','External  Feature 17 ','INTEGER','0','1',' External F17 is active');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.12.0','normal_voltage_phase_AB','Normal voltage phase AB (V)','INTEGER','0','2','Normal voltage phase AB');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.13.0','normal_voltage_phase_BC','Normal voltage phase BC (V)','INTEGER','0','2',' Normal voltage phase BC');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.14.0','normal_voltage_phase_CA','Normal voltage phase CA (V)','INTEGER','0','2','Normal voltage phase CA');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.15.0','normal_voltage_unbalance','Normal voltage unbalance (V)','INTEGER','0','2',' Normal voltage unbalance');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.18.0','emergency_voltage_phase_CA','Emergency voltage phase CA (V)','INTEGER','0','2','Emergency voltage phase CA');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.19.0','nominal_voltage','Nominal voltage (V)','INTEGER','0','2','Nominal voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.20.0','nominal_frequency','Nominal frequency (Hz)','INTEGER','0','2',' Nominal frequency');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.21.0','normal_source_3phase_sensing','Normal source 3 phase sensing','INTEGER','0','1',' Normal source 3 phase sensing');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.22.0','transfer_switch_type','Transfer switch type','INTEGER','0','1',' Transfer switch type (OTTS,DTTS)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.23.0','transfer_switch_ampere_rating','Transfer switch ampere rating','INTEGER','0','1',' Transfer switch ampere rating');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.24.0','controller_software_version','Controller software version','STRING','0','1',' Controller software version');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.25.0','controller_software_date','Controller software date','STRING','0','1','Controller software date');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.26.0','external_parameter_lock_is_active','External parameter lock','INTEGER','0','1',' External parameter lock is active');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.27.0','engine_running','Engine running','INTEGER','0','1',' Engine running');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.28.0','controller_state','Controller state','INTEGER','0','1',' Controller state');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.29.0','controller_status_state_data','Controller status state data','INTEGER','0','1','Controller status state data');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.30.0','timer_bypass_feature_6B_status','Timer bypass (Feature 6B) status','INTEGER','0','1','Timer bypass (Feature 6B) status');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.31.0','remote_transfer_feature_17_status','Remote transfer (Feature 17) status','INTEGER','0','1',' Remote transfer (Feature 17) status');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.32.0','load_shed_feature_30_status','Load shed (Feature 30) status','INTEGER','0','1','Load shed (Feature 30) status');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.33.0','transfer_inhibit_to_emergency_feature_34B_status','Featreu 34B Inhibit Transfer to S2','INTEGER','0','1',' Transfer inhibit to emergency (Feature 34B) status');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.34.0','local_test_feature_5_status','Local test (Feature 5) status','INTEGER','0','1',' Local test (Feature 5) status');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.35.0','manual_transfer_to_emergency_feature_6ZE_status','Manual transfer to emergency (Feature 6ZE) status','INTEGER','0','1','Manual transfer to emergency (Feature 6ZE) status');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.36.0','current_sensing','Current sensing','INTEGER','0','1','Current sensing');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.37.0','phase_A_current','Phase A current (A)','INTEGER','0','2','With 1 or 2 CT selected this is labeled as I1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.38.0','phase_B_current','Phase B current (A)','INTEGER','0','2',' Phase B current (A)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.39.0','phase_C_current','Phase C current (A)','INTEGER','0','2','With 2 CT selected this is labeled as I2');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.40.0','failure_to_accept_timer','Failure to accept timer','INTEGER','0','2',' Failure to accept timer');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.41.0','normal_to_emergency_pretransfer_time_delay_feature_31_bypass_enabled','Normal to emergency pretransfer time delay (Feature 31) bypass','INTEGER','0','1',' Normal to emergency pretransfer time delay (Feature 31) bypass enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.42.0','emergency_to_normal_pretransfer_time_delay_feature_31_bypass_enabled','Emergency to normal pretransfer time delay (Feature 31) bypass ','INTEGER','0','1',' Emergency to normal pretransfer time delay (Feature 31) bypass enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.43.0','commit_to_transfer_enabled','Commit to transfer ','INTEGER','0','1',' Commit to transfer enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.44.0','bypass_DTTS_source_failure_enabled','Bypass DTTS source failure','INTEGER','0','1','Data for DTTS and Group1 emulation is NOT enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.45.0','inphase_transfer_enabled','Inphase transfer','INTEGER','0','1',' Inphase transfer enabled - only for OTTS');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.46.0','inphase_monitor_for_load_shed_feature_30_enabled','Inphase monitor for load shed (Feature 30)','INTEGER','0','1',' Inphase monitor for load shed (Feature 30) enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.47.0','fail_accept_timer_enabled','Fail accept timer','INTEGER','0','1',' Fail accept timer enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.48.0','transfer_retransfer_time_delay_bypass_feature_6B_input_enabled','Transfer/retransfer time delay bypass (Feature 6B) input ','INTEGER','0','1',' Transfer/retransfer time delay bypass (Feature 6B) input enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.49.0','remote_transfer_feature_17_input_enabled','Remote transfer (Feature 17) input ','INTEGER','0','1','Data for transfer switch in auto mode only');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.50.0','retransfer_to_normal_mode_selector_feature_6DL_input_enabled','Retransfer to normal mode selector (Feature 6DL) ','INTEGER','0','1',' Retransfer to normal mode selector (Feature 6DL) enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.51.0','serial_remote_transfer_feature_17_enabled','Serial remote transfer (Feature 17) ','INTEGER','0','1',' Serial remote transfer (Feature 17) enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.52.0','voltage_unbalance_enabled','Voltage unbalance','INTEGER','0','1',' Voltage unbalance enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.53.0','current_sensing_module_present','Current sensing module','INTEGER','0','1',' Current sensing module present');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.54.0','alert_LED_active','Alert LED','INTEGER','0','1',' Alert LED active');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.55.0','not_in_auto_LED_active','Not in Auto LED','INTEGER','0','1',' Not in Auto LED active');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.58.0','daylight_savings','Daylight savings','INTEGER','0','1',' Daylight savings');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.59.0','loss_of_emergency_when_connected_to_emergency_alarm_enabled','Loss of emergency when connected to emergency alarm','INTEGER','0','1',' Loss of emergency when connected to emergency alarm enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.60.0','expiration_of_failure_to_accept_timer_alarm_enabled','Expiration of failure to accept timer alarm','INTEGER','0','1',' Expiration of failure to accept timer alarm enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.61.0','load_disconnect_enabled_DTTS_only','Load disconnect (DTTS only)','INTEGER','0','1',' Load disconnect enabled (DTTS only)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.62.0','op1_is_feature_31','OP1 is Feature 31','INTEGER','0','1',' OP1 is Feature 31');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.63.0','op1_is_common_alarm','OP1 is common alarm','INTEGER','0','1',' OP1 is common alarm');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.64.0','op1_is_NR2','OP1 is NR2','INTEGER','0','1',' OP1 is NR2');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.65.0','op1_is_not_in_auto','OP1 is not in auto','INTEGER','0','1',' OP1 is not in auto');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.66.0','op2_is_feature_31','OP2 is Feature 31','INTEGER','0','1',' OP2 is Feature 31');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.67.0','op2_is_common_alarm','OP2 is common alarm','INTEGER','0','1',' OP2 is common alarm');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.68.0','op2_is_NR2','OP2 is NR2','INTEGER','0','1',' OP2 is NR2');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.69.0','op2_is_not_in_auto','OP2 is not in auto','INTEGER','0','1',' OP2 is not in auto');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.70.0','op2_1G_enabled','OP2 1G enabled','INTEGER','0','1',' OP2 1G enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.71.0','op3_is_feature_31','OP3 is Feature 31','INTEGER','0','1',' OP3 is Feature 31');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.72.0','op3_is_common_alarm','OP3 is common alarm','INTEGER','0','1',' OP3 is common alarm');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.73.0','op3_is_NR2','OP2 is NR2','INTEGER','0','1',' OP2 is NR2');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.74.0','op3_is_not_in_auto','OP3 is not in auto','INTEGER','0','1',' OP3 is not in auto');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.75.0','controller_serial_number','Controller serial number','STRING','0','1','Controller serial number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.76.0','engine_exerciser_F11C_advanced_program_number','Engine exerciser (F11C) advanced program number','INTEGER','0','1',' Engine exerciser (F11C) advanced program number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.77.0','engine_exerciser_F11C_enabled','Engine exerciser (F11C) enabled','INTEGER','0','1',' Engine exerciser (F11C) enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.78.0','engine_exerciser_F11C_test_with_load_transfer_enabled','Engine exerciser (F11C) test with load transfer','INTEGER','0','1',' Engine exerciser (F11C) test with load transfer enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.79.0','engine_exerciser_F11C_start_week','Engine exerciser (F11C) start week','INTEGER','0','2',' Engine exerciser (F11C) start week');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.80.0','engine_exerciser_F11C_day_of_week','Engine exerciser (F11C) day of week','INTEGER','0','2',' Engine exerciser (F11C) day of week');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.82.0','engine_exerciser_F11C_start_time_hours','Engine exerciser (F11C) start time hours','INTEGER','0','2',' Engine exerciser (F11C) start time hours');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.83.0','engine_exerciser_F11C_start_time_minutes','Engine exerciser (F11C) start time minutes','INTEGER','0','2',' Engine exerciser (F11C) start time minutes');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.84.0','engine_exerciser_F11C_run_time_hours','Engine exerciser (F11C) run time hours','INTEGER','0','2',' Engine exerciser (F11C) run time hours');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.85.0','engine_exerciser_F11C_run_time_minutes','Engine exerciser (F11C) schedule run time minutes','INTEGER','0','2',' Engine exerciser (F11C) schedule run time minutes');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.86.0','normal_voltage_dropout','Normal voltage dropout (V)','INTEGER','0','2',' Normal voltage dropout');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.87.0','normal_voltage_pickup','Normal voltage pickup (V)','INTEGER','0','2',' Normal voltage pickup');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.88.0','normal_over_voltage_trip','Normal over voltage trip (V)','INTEGER','0','2',' Normal over voltage trip');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.89.0','normal_frequency_dropout','Normal frequency dropout (Hz)','INTEGER','0','2',' Normal frequency dropout');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.90.0','normal_frequency_pickup','Normal frequency pickup (Hz)','INTEGER','0','2','Normal frequency pickup');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.91.0','normal_over_frequency_trip','Normal over frequency trip (Hz)','INTEGER','0','2',' Normal over frequency trip');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.92.0','emergency_voltage_dropout','Emergency voltage dropout (V)','INTEGER','0','2','Emergency voltage dropout');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.93.0','emergency_voltage_pickup','Emergency voltage pickup (V)','INTEGER','0','2',' Emergency voltage pickup');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.94.0','emergency_over_voltage_trip','Emergency over voltage trip (V)','INTEGER','0','2',' Emergency over voltage trip');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.95.0','emergency_frequency_dropout','Emergency frequency dropout (Hz)','INTEGER','0','2',' Emergency frequency dropout');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.96.0','emergency_frequency_pickup','Emergency frequency pickup (Hz)','INTEGER','0','2',' Emergency frequency pickup');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.97.0','emergency_over_frequency_trip','Emergency over frequency trip (Hz)','INTEGER','0','2',' Emergency over frequency trip');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.98.0','timer_1C_engine_start_time_delay','Normal Override Timer (1C) (SEC)','INTEGER','0','2',' Timer 1C engine start time delay');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.99.0','timer_1F_engine_fail_time_delay','Emergency Override Timer  (1F) (SEC)','INTEGER','0','2',' Timer 1F engine fail time delay');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.100.0','timer_2B_transfer_N_to_E_time_delay','N TO E On N Fail Timer (2B)(SEC)','INTEGER','0','2',' Timer 2B transfer N to E time delay');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.101.0','timer_2E_engine_cool_down_time_delay','Engine Cooldown Timer (2E)(SEC)','INTEGER','0','2',' Timer 2E engine cool down time delay');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.102.0','timer_3AF_transfer_E_to_N_on_source_fail_time_delay','E To N On N Fail Timer (3A)(SEC)','INTEGER','0','2',' Timer 3AF transfer E to N on source fail time delay');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.103.0','timer_3A_T_transfer_E_to_N_on_test_time_delay','E To N Test Timer (3A)(SEC)','INTEGER','0','2',' Timer 3A T transfer E to N on test time delay');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.104.0','timer_31F_N_to_E_pre_transfer_signal_time_delay','N To E PRE-Transfer Timer (31F)(SEC)','INTEGER','0','2',' Timer 31F N to E pre transfer signal time delay');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.105.0','timer_31M_N_to_E_post_transfer_signal_time_delay','N To E POST-Transfer Timer (31M)(SEC)','INTEGER','0','2',' Timer 31M N to E post transfer signal time delay');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.106.0','timer_31G_E_to_N_pre_transfer_signal_time_delay','E To N PRE-Transfer Timer (31G)(SEC)','INTEGER','0','2',' Timer 31G E to N pre transfer signal time delay');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.107.0','timer_31N_E_to_N_post_transfer_signal_time_delay','E To N POST-Transfer Timer (31N)(SEC)','INTEGER','0','2',' Timer 31N  E to N post transfer signal time delay');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.108.0','dtts_load_disconnect_time_delay','DTTS load disconnect time delay','INTEGER','0','2',' DTTS load disconnect time delay');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.109.0','commit_to_transfer_after_engine_start','Commit to transfer after engine start','INTEGER','0','2',' Commit to transfer after engine start');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.110.0','real_time_hour','Real time hour','INTEGER','0','2',' Real time hour');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.111.0','real_time_minute','Real time minute','INTEGER','0','2',' Real time minute');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.112.0','real_time_second','Real time second','INTEGER','0','2',' Real time second');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.113.0','calendar_year','Calendar year','INTEGER','0','2',' Calendar year');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.114.0','calendar_month','Calendar month','INTEGER','0','2',' Calendar month');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.115.0','calendar_day_of_month','Calendar day of month','INTEGER','0','2',' Calendar day of month');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.116.0','calendar_day_of_week','Calendar day of week','INTEGER','0','2',' Calendar day of week');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.117.0','total_time_E_has_been_acceptable_high_word','Total time E has been acceptable(high word)','INTEGER','0','2',' Total time E has been acceptable(high word)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.118.0','total_time_E_has_been_acceptable_low_word','Total time E has been acceptable(low word)','INTEGER','0','2',' Total time E has been acceptable(low word)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.119.0','total_time_N_has_been_acceptable_high_word','Total time N has been acceptable(high word)','INTEGER','0','2',' Total time N has been acceptable(high word)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.120.0','total_time_N_has_been_acceptable_low_word','Total time N has been acceptable(low word)','INTEGER','0','2',' Total time N has been acceptable(low word)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.121.0','total_number_of_days_CP_has_been_energized','Total number of days CP has been energized','INTEGER','0','2',' Total number of days CP has been energized');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.122.0','total_number_of_TS_transfers','Total number of TS transfers','INTEGER','0','2',' Total number of TS transfers');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.123.0','total_number_of_TS_transfers_due_to_source_failures','Total number of TS transfers due to source failures','INTEGER','0','2',' Total number of TS transfers due to source failures');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.124.0','controller_location','Controller location','STRING','0','1',' Controller location');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.125.0','external_feature_5_enabled','External Feature 5','INTEGER','0','1',' External Feature 5 enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.126.0','external_feature_34_enabled','External Feature 34 ','INTEGER','0','1',' External Feature 34 enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.127.0','inphase_filter_enabled','In-phase filter','INTEGER','0','1',' In-phase filter enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.128.0','current_sensing_option_installed','Current sensing option','INTEGER','0','1',' Current sensing option installed');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.129.0','advanced_11C_option_installed','Advanced 11C option','INTEGER','0','1',' Advanced 11C option Installed');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.130.0','event_log_option_installed','Event log option','INTEGER','0','1',' Event log option Installed');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.131.0','rs485_communication_option_installed','RS485 communication option','INTEGER','0','1',' RS485 communication option Installed');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.132.0','op1_common_alarm_option_installed','OP1 common alarm option ','INTEGER','0','1',' OP1 common alarm option Installed');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.133.0','total_time_load_connected_to_normal_high_word','Total time load connected to normal (high word)','INTEGER','0','2',' Total time load connected to normal (high word)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.134.0','total_time_load_connected_to_normal_low_word','Total time load connected to normal (low word)','INTEGER','0','2',' Total time load connected to normal (low word)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.135.0','total_time_load_connected_to_emergency_high_word','Total time load connected to emergency (high word)','INTEGER','0','2',' Total time load connected to emergency (high word)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.136.0','total_time_load_connected_to_emergency_low_word','Total time load connected to emergency (low word)','INTEGER','0','2',' Total time load connected to emergency (low word)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.137.0','rs485_port_address','RS-485 port address','INTEGER','0','1',' RS-485 port address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.138.0','rs485_port_baud_rate','RS-485 port baud rate','INTEGER','0','2',' RS-485 port baud rate');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.139.0','rs485_port_protocol','RS-485 port protocol','INTEGER','0','1',' RS-485 port protocol');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.140.0','emulate_group_1','Emulate Group 1','INTEGER','0','1',' Emulate Group 1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.141.0','dtts_load_disconnect_recovery_mode_manual_or_auto','DTTS load disconnect recovery mode','INTEGER','0','1',' DTTS load disconnect recovery mode');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.142.0','timer_2B_transfer_N_to_E_on_test_time_delay','N To E Test Timer (2B)(SEC)','INTEGER','0','2',' Timer 2B transfer N to E on test time delay');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.143.0','inphase_monitor_time_delay','IN-PHASE Timer (SEC)','INTEGER','0','2',' Inphase monitor time delay');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.144.0','ats_total_transfer_time_in_10th_seconds','Last Switch Transfer Duration (N to E) (SEC)','INTEGER','0','2',' ATS total transfer time in 10th seconds');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.145.0','dongle_installed','Dongle installed','INTEGER','0','1',' Dongle installed');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.146.0','language_selection_setting','Language selection setting','INTEGER','0','1',' Language selection setting');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.147.0','lcd_contrast_setting','LCD contrast setting','INTEGER','0','1',' LCD contrast setting');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.148.0','controller_name','Controller name','STRING','0','1',' Controller name');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.149.0','boot_loader_version','Boot loader version','STRING','0','1',' Boot loader version');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.150.0','voltage_display_phase_label_selection','Voltage display phase label selection','INTEGER','0','1',' Voltage display phase label selection');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.151.0','source_1_or_source_2_selection_for_different_english_format','Source 1 or source 2 selection for different English format','INTEGER','0','1',' Source 1 or source 2 selection for different English format');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.152.0','external_battery_enabled','External battery enabled','INTEGER','0','1',' External battery enabled');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.153.0','ts_data_gen_start_date','TS data gen start date','INTEGER','0','2',' TS data gen start date');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.154.0','ts_data_gen_start_month','TS data gen start month','INTEGER','0','2',' TS data gen start month');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.155.0','ts_data_gen_start_year','TS data gen start year','INTEGER','0','2',' TS data gen start year');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.156.0','ts_data_gen_start_hour','TS data gen start hour','INTEGER','0','2',' TS data gen start hour');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.157.0','ts_data_gen_start_minutes','TS data gen start minutes','INTEGER','0','2',' TS data gen start minutes');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.158.0','ts_data_gen_start_seconds','TS data gen start seconds','INTEGER','0','2',' TS data gen start seconds');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.159.0','ts_data_gen_start_10th_of_seconds','TS data gen start 10th of seconds','INTEGER','0','2',' TS data gen start 10th of seconds');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.160.0','ts_data_gen_start_elapsed_time_in_seconds','TS data gen start elapsed time in seconds','INTEGER','0','2',' TS data gen start elapsed time in seconds');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.161.0','total_active_alarm_number','Total active alarm number','INTEGER','0','1',' Total active alarm number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.162.0','load_shed_direction','Load shed direction','INTEGER','0','1',' Load shed direction');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.163.0','dtts_only_load_shed_on_source_failure','DTTS only - load shed on source failure','INTEGER','0','1',' DTTS only - load shed on source failure');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.164.0','dtts_only_load_shed_on_request_f17','DTTS only - load shed on request F17','INTEGER','0','1',' DTTS only - load shed on request F17');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.165.0','normal_voltage_unbalance_dropout','Normal voltage unbalance dropout (V)','INTEGER','0','2','Parameter settings display for 3 phase only');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.166.0','normal_voltage_unbalance_pickup','Normal voltage unbalance pickup (V)','INTEGER','0','2','Parameter settings display for 3 phase only');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.167.0','emergency_voltage_unbalance_dropout','Emergency voltage unbalance dropout (V)','INTEGER','0','2','Parameter settings display for 3 phase only');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1597',' .1.3.6.1.4.1.7777.1.1.168.0','emergency_voltage_unbalance_pickup','Emergency voltage unbalance pickup (V)','INTEGER','0','2','Parameter settings display for 3 phase only');




REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.1.0','dpmDPMName','Name','STRING','0','1','Name');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.2.0','dpmDPMLoca','Location','STRING','0','1','Location');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.3.0','dpmNominalVoltage','Nominal Voltage (V)','INTEGER','0','2','Nominal Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.4.0','dpmNominalCurrent','Nominal Current (Amps)','INTEGER','0','2','Nominal Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.5.0','dpmInstantaneousDemandKwMW','Instantaneous Demand (kW)','INTEGER','0','2','Instantaneous Demand kW MW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.6.0','dpmInstantaneousDemandDate','Instantaneous Demand Date','STRING','0','2','Instantaneous Demand Date');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.7.0','dpmInstantaneousDemandTime','Instantaneous Demand Time','STRING','0','2','Instantaneous Demand Time');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.8.0','dpmBlockDemandforFullHouInkW','Instantaneous Block Demand for Full Hour (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Hour In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.9.0','dpmBlockDemandforFullDayInkW','Instantaneous Block Demand for Full Day (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Day In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.10.0','dpmBlockDemandforFullMonthInKW','Instantaneous Block Demand for Full Month (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Month In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.11.0','dpmBlockDemandforFullYear','Instantaneous Block Demand for Full Year (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Year In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.12.0','dpmCurrentThdA','Phase A THD Current (Amps)','INTEGER','0','2','Phase A THD Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.13.0','dpmCurrentThdB','Phase B THD Current (Amps)','INTEGER','0','2','Phase B THD Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.14.0','dpmCurrentThdC','Phase C THD Current (Amps)','INTEGER','0','2','Phase C THD Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.15.0','dpmVoltageThdA','Phase A THD Voltage (V)','INTEGER','0','2','Phase A THD Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.16.0','dpmVoltageThdB','Phase B THD Voltage (V)','INTEGER','0','2','Phase B THD Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.17.0','dpmVoltageThdC','Phase C THD Voltage (V)','INTEGER','0','2','Phase C THD Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.18.0','dpmVab','Vab Volatage (V)','INTEGER','0','2','Vab Volatage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.19.0','dpmVbc','Vbc Volatage (V)','INTEGER','0','2','Vbc Volatage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.20.0','dpmVca','Vca Volatage (V)','INTEGER','0','2','Vca Volatage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.21.0','dpmVolAverage','Vol Average','INTEGER','0','2','Vol Average');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.22.0','dpmVolUnbal','Vol Unbal','INTEGER','0','2','Vol Unbal');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.23.0','dpmVan','Vol Van','INTEGER','0','2','Vol Van');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.24.0','dpmVbn','VBN','INTEGER','0','2','VBN');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.25.0','dpmVcn','VCN','INTEGER','0','2','VCN');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.26.0','dpmVavgneutral','Avg neutral Volatagae','INTEGER','0','2','Avg neutral Volatagae');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.27.0','dpmFrequency','Frequency (Hz)','INTEGER','0','2','Frequency ');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.28.0','dpmPowerFactor','Power factor','INTEGER','0','2','Power factor');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.29.0','dpmCurPhaseA','Phase A Current (Amps)','INTEGER','0','2','Phase A Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.30.0','dpmCurPhaseB','Phase B Current (Amps)','INTEGER','0','2','Phase B Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.31.0','dpmCurPhaseC','Phase C Current (Amps)','INTEGER','0','2','Phase C Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.32.0','dpmPhaseCurAvg','Phase Current Average (Amps)','INTEGER','0','2','Phase Current Average');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.33.0','dpmPhaseCurUnbalancecePercentage','Phase Current Unbalance Percentage (Amps)','INTEGER','0','2','Phase Current Unbalance Percentage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.34.0','dpmRealTotalPower','Real Total power factor','INTEGER','0','2','Real Total power factor');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.35.0','dpmReactiveTotalPower','Reactive Total power (kW)','INTEGER','0','2','Reactive Total power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.36.0','dpmApparentTotalPower','Apparent Total power (kW)','INTEGER','0','2','Apparent Total power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.37.0','dpmNetActiveEnergy','Net Active Energy','INTEGER','0','2','Net Active Energy');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1593','.1.3.6.1.4.1.7777.1.2.38.0','dpmFirmwareVersion','Firmware Version','STRING','0','1','Firmware Version');



REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.1.0','dpmDPMName','Name','STRING','0','1','Name');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.2.0','dpmDPMLoca','Location','STRING','0','1','Location');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.3.0','dpmNominalVoltage','Nominal Voltage (V)','INTEGER','0','2','Nominal Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.4.0','dpmNominalCurrent','Nominal Current (Amps)','INTEGER','0','2','Nominal Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.5.0','dpmInstantaneousDemandKwMW','Instantaneous Demand (kW)','INTEGER','0','2','Instantaneous Demand kW MW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.6.0','dpmInstantaneousDemandDate','Instantaneous Demand Date','STRING','0','2','Instantaneous Demand Date');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.7.0','dpmInstantaneousDemandTime','Instantaneous Demand Time','STRING','0','2','Instantaneous Demand Time');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.8.0','dpmBlockDemandforFullHouInkW','Instantaneous Block Demand for Full Hour (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Hour In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.9.0','dpmBlockDemandforFullDayInkW','Instantaneous Block Demand for Full Day (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Day In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.10.0','dpmBlockDemandforFullMonthInKW','Instantaneous Block Demand for Full Month (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Month In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.11.0','dpmBlockDemandforFullYear','Instantaneous Block Demand for Full Year (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Year In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.12.0','dpmCurrentThdA','Phase A THD Current (Amps)','INTEGER','0','2','Phase A THD Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.13.0','dpmCurrentThdB','Phase B THD Current (Amps)','INTEGER','0','2','Phase B THD Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.14.0','dpmCurrentThdC','Phase C THD Current (Amps)','INTEGER','0','2','Phase C THD Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.15.0','dpmVoltageThdA','Phase A THD Voltage (V)','INTEGER','0','2','Phase A THD Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.16.0','dpmVoltageThdB','Phase B THD Voltage (V)','INTEGER','0','2','Phase B THD Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.17.0','dpmVoltageThdC','Phase C THD Voltage (V)','INTEGER','0','2','Phase C THD Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.18.0','dpmVab','Vab Volatage (V)','INTEGER','0','2','Vab Volatage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.19.0','dpmVbc','Vbc Volatage (V)','INTEGER','0','2','Vbc Volatage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.20.0','dpmVca','Vca Volatage (V)','INTEGER','0','2','Vca Volatage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.21.0','dpmVolAverage','Vol Average','INTEGER','0','2','Vol Average');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.22.0','dpmVolUnbal','Vol Unbal','INTEGER','0','2','Vol Unbal');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.23.0','dpmVan','Vol Van','INTEGER','0','2','Vol Van');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.24.0','dpmVbn','VBN','INTEGER','0','2','VBN');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.25.0','dpmVcn','VCN','INTEGER','0','2','VCN');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.26.0','dpmVavgneutral','Avg neutral Volatagae','INTEGER','0','2','Avg neutral Volatagae');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.27.0','dpmFrequency','Frequency (Hz)','INTEGER','0','2','Frequency');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.28.0','dpmPowerFactor','Power factor','INTEGER','0','2','Power factor');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.29.0','dpmCurPhaseA','Phase A Current (Amps)','INTEGER','0','2','Phase A Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.30.0','dpmCurPhaseB','Phase B Current (Amps)','INTEGER','0','2','Phase B Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.31.0','dpmCurPhaseC','Phase C Current (Amps)','INTEGER','0','2','Phase C Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.32.0','dpmPhaseCurAvg','Phase Current Average (Amps)','INTEGER','0','2','Phase Current Average');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.33.0','dpmPhaseCurUnbalancecePercentage','Phase Current Unbalance Percentage (Amps)','INTEGER','0','2','Phase Current Unbalance Percentage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.34.0','dpmRealTotalPower','Real Total power factor','INTEGER','0','2','Real Total power factor');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.35.0','dpmReactiveTotalPower','Reactive Total power (kW)','INTEGER','0','2','Reactive Total power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.36.0','dpmApparentTotalPower','Apparent Total power (kW)','INTEGER','0','2','Apparent Total power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.37.0','dpmNetActiveEnergy','Net Active Energy','INTEGER','0','2','Net Active Energy');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1595','.1.3.6.1.4.1.7777.1.3.38.0','dpmFirmwareVersion','Firmware Version','STRING','0','1','Firmware Version');



REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.1.0','dpmDPMName','Name','STRING','0','1','Name');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.2.0','dpmDPMLoca','Location','STRING','0','1','Location');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.3.0','dpmNominalVoltage','Nominal Voltage (V)','INTEGER','0','2','Nominal Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.4.0','dpmNominalCurrent','Nominal Current (Amps)','INTEGER','0','2','Nominal Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.5.0','dpmInstantaneousDemandKwMW','Instantaneous Demand (kW)','INTEGER','0','2','Instantaneous Demand kW MW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.3.6.0','dpmInstantaneousDemandDate','Instantaneous Demand Date','STRING','0','2','Instantaneous Demand Date');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.7.0','dpmInstantaneousDemandTime','Instantaneous Demand Time','STRING','0','2','Instantaneous Demand Time');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.8.0','dpmBlockDemandforFullHouInkW','Instantaneous Block Demand for Full Hour (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Hour In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.9.0','dpmBlockDemandforFullDayInkW','Instantaneous Block Demand for Full Day (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Day In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.10.0','dpmBlockDemandforFullMonthInKW','Instantaneous Block Demand for Full Month (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Month In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.11.0','dpmBlockDemandforFullYear','Instantaneous Block Demand for Full Year (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Year In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.12.0','dpmCurrentThdA','Phase A THD Current (Amps)','INTEGER','0','2','Phase A THD Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.13.0','dpmCurrentThdB','Phase B THD Current (Amps)','INTEGER','0','2','Phase B THD Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.14.0','dpmCurrentThdC','Phase C THD Current (Amps)','INTEGER','0','2','Phase C THD Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.15.0','dpmVoltageThdA','Phase A THD Voltage (V)','INTEGER','0','2','Phase A THD Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.16.0','dpmVoltageThdB','Phase B THD Voltage (V)','INTEGER','0','2','Phase B THD Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.17.0','dpmVoltageThdC','Phase C THD Voltage (V)','INTEGER','0','2','Phase C THD Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.18.0','dpmVab','Vab Volatage (V)','INTEGER','0','2','Vab Volatage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.19.0','dpmVbc','Vbc Volatage (V)','INTEGER','0','2','Vbc Volatage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.20.0','dpmVca','Vca Volatage (V)','INTEGER','0','2','Vca Volatage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.21.0','dpmVolAverage','Vol Average','INTEGER','0','2','Vol Average');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.22.0','dpmVolUnbal','Vol Unbal','INTEGER','0','2','Vol Unbal');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.23.0','dpmVan','Vol Van','INTEGER','0','2','Vol Van');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.24.0','dpmVbn','VBN','INTEGER','0','2','VBN');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.25.0','dpmVcn','VCN','INTEGER','0','2','VCN');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.26.0','dpmVavgneutral','Avg neutral Volatagae','INTEGER','0','2','Avg neutral Volatagae');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.27.0','dpmFrequency','Frequency (Hz)','INTEGER','0','2','Frequency');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.28.0','dpmPowerFactor','Power factor','INTEGER','0','2','Power factor');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.29.0','dpmCurPhaseA','Phase A Current (Amps)','INTEGER','0','2','Phase A Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.30.0','dpmCurPhaseB','Phase B Current (Amps)','INTEGER','0','2','Phase B Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.31.0','dpmCurPhaseC','Phase C Current (Amps)','INTEGER','0','2','Phase C Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.32.0','dpmPhaseCurAvg','Phase Current Average (Amps)','INTEGER','0','2','Phase Current Average');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.33.0','dpmPhaseCurUnbalancecePercentage','Phase Current Unbalance Percentage (Amps)','INTEGER','0','2','Phase Current Unbalance Percentage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.34.0','dpmRealTotalPower','Real Total power factor','INTEGER','0','2','Real Total power factor');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.35.0','dpmReactiveTotalPower','Reactive Total power (kW)','INTEGER','0','2','Reactive Total power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.36.0','dpmApparentTotalPower','Apparent Total power (kW)','INTEGER','0','2','Apparent Total power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.37.0','dpmNetActiveEnergy','Net Active Energy','INTEGER','0','2','Net Active Energy');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1594','.1.3.6.1.4.1.7777.1.4.38.0','dpmFirmwareVersion','Firmware Version','STRING','0','1','Firmware Version');



REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.1.0','dpmDPMName','Name','STRING','0','1','Name');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.2.0','dpmDPMLoca','Location','STRING','0','1','Location');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.3.0','dpmNominalVoltage','Nominal Voltage (V)','INTEGER','0','2','Nominal Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.4.0','dpmNominalCurrent','Nominal Current (Amps)','INTEGER','0','2','Nominal Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.5.0','dpmInstantaneousDemandKwMW','Instantaneous Demand (kW)','INTEGER','0','2','Instantaneous Demand kW MW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.3.6.0','dpmInstantaneousDemandDate','Instantaneous Demand Date','STRING','0','2','Instantaneous Demand Date');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.7.0','dpmInstantaneousDemandTime','Instantaneous Demand Time','STRING','0','2','Instantaneous Demand Time');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.8.0','dpmBlockDemandforFullHouInkW','Instantaneous Block Demand for Full Hour (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Hour In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.9.0','dpmBlockDemandforFullDayInkW','Instantaneous Block Demand for Full Day (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Day In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.10.0','dpmBlockDemandforFullMonthInKW','Instantaneous Block Demand for Full Month (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Month In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.11.0','dpmBlockDemandforFullYear','Instantaneous Block Demand for Full Year (kW)','INTEGER','0','2','Instantaneous Block Demand for Full Year In kW');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.12.0','dpmCurrentThdA','Phase A THD Current (Amps)','INTEGER','0','2','Phase A THD Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.13.0','dpmCurrentThdB','Phase B THD Current (Amps)','INTEGER','0','2','Phase B THD Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.14.0','dpmCurrentThdC','Phase C THD Current (Amps)','INTEGER','0','2','Phase C THD Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.15.0','dpmVoltageThdA','Phase A THD Voltage (V)','INTEGER','0','2','Phase A THD Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.16.0','dpmVoltageThdB','Phase B THD Voltage (V)','INTEGER','0','2','Phase B THD Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.17.0','dpmVoltageThdC','Phase C THD Voltage (V)','INTEGER','0','2','Phase C THD Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.18.0','dpmVab','Vab Volatage (V)','INTEGER','0','2','Vab Volatage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.19.0','dpmVbc','Vbc Volatage (V)','INTEGER','0','2','Vbc Volatage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.20.0','dpmVca','Vca Volatage (V)','INTEGER','0','2','Vca Volatage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.21.0','dpmVolAverage','Vol Average','INTEGER','0','2','Vol Average');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.22.0','dpmVolUnbal','Vol Unbal','INTEGER','0','2','Vol Unbal');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.23.0','dpmVan','Vol Van','INTEGER','0','2','Vol Van');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.24.0','dpmVbn','VBN','INTEGER','0','2','VBN');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.25.0','dpmVcn','VCN','INTEGER','0','2','VCN');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.26.0','dpmVavgneutral','Avg neutral Volatagae','INTEGER','0','2','Avg neutral Volatagae');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.27.0','dpmFrequency','Frequency (Hz)','INTEGER','0','2','Frequency');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.28.0','dpmPowerFactor','Power factor','INTEGER','0','2','Power factor');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.29.0','dpmCurPhaseA','Phase A Current (Amps)','INTEGER','0','2','Phase A Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.30.0','dpmCurPhaseB','Phase B Current (Amps)','INTEGER','0','2','Phase B Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.31.0','dpmCurPhaseC','Phase C Current (Amps)','INTEGER','0','2','Phase C Current');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.32.0','dpmPhaseCurAvg','Phase Current Average (Amps)','INTEGER','0','2','Phase Current Average');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.33.0','dpmPhaseCurUnbalancecePercentage','Phase Current Unbalance Percentage (Amps)','INTEGER','0','2','Phase Current Unbalance Percentage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.34.0','dpmRealTotalPower','Real Total power factor','INTEGER','0','2','Real Total power factor');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.35.0','dpmReactiveTotalPower','Reactive Total power (kW)','INTEGER','0','2','Reactive Total power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.36.0','dpmApparentTotalPower','Apparent Total power (kW)','INTEGER','0','2','Apparent Total power');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.37.0','dpmNetActiveEnergy','Net Active Energy','INTEGER','0','2','Net Active Energy');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1596','.1.3.6.1.4.1.7777.1.5.38.0','dpmFirmwareVersion','Firmware Version','STRING','0','1','Firmware Version');

-- End Add prop_defs to ASCO devices


-- Start Add def_prop_groups_map and def_status_groups_map to ASCO devices
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('phase_shift_between_normal_and_emergency','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('normal_frequency','1597','Voltage & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_frequency','1597','Voltage & Frequency');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('main_on_normal','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('main_on_emergency','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('auxiliary_on_normal','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('auxiliary_on_emergency','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('normal_source_available','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_source_available','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('engine_exerciser_with_load_active','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('external_f17_is_active','1597','Switch Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('normal_voltage_phase_AB','1597','Voltage & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('normal_voltage_phase_BC','1597','Voltage & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('normal_voltage_phase_CA','1597','Voltage & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('normal_voltage_unbalance','1597','Identification');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_voltage_phase_CA','1597','Voltage & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('nominal_voltage','1597','Voltage & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('nominal_frequency','1597','Voltage & Frequency');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('normal_source_3phase_sensing','1597','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('transfer_switch_type','1597','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('transfer_switch_ampere_rating','1597','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('controller_software_version','1597','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('controller_software_date','1597','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('external_parameter_lock_is_active','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('engine_running','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('controller_state','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('controller_status_state_data','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('timer_bypass_feature_6B_status','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('remote_transfer_feature_17_status','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('load_shed_feature_30_status','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('transfer_inhibit_to_emergency_feature_34B_status','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('local_test_feature_5_status','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('manual_transfer_to_emergency_feature_6ZE_status','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('current_sensing','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('phase_A_current','1597','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('phase_B_current','1597','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('phase_C_current','1597','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('failure_to_accept_timer','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('normal_to_emergency_pretransfer_time_delay_feature_31_bypass_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_to_normal_pretransfer_time_delay_feature_31_bypass_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('commit_to_transfer_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('bypass_DTTS_source_failure_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('inphase_transfer_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('inphase_monitor_for_load_shed_feature_30_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('fail_accept_timer_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('transfer_retransfer_time_delay_bypass_feature_6B_input_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('remote_transfer_feature_17_input_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('retransfer_to_normal_mode_selector_feature_6DL_input_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('serial_remote_transfer_feature_17_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('voltage_unbalance_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('current_sensing_module_present','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('alert_LED_active','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('not_in_auto_LED_active','1597','Switch Status');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('daylight_savings','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('loss_of_emergency_when_connected_to_emergency_alarm_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('expiration_of_failure_to_accept_timer_alarm_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('load_disconnect_enabled_DTTS_only','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op1_is_feature_31','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op1_is_common_alarm','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op1_is_NR2','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op1_is_not_in_auto','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op2_is_feature_31','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op2_is_common_alarm','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op2_is_NR2','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op2_is_not_in_auto','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op2_1G_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op3_is_feature_31','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op3_is_common_alarm','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op3_is_NR2','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op3_is_not_in_auto','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('controller_serial_number','1597','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('engine_exerciser_F11C_advanced_program_number','1597','Engine Exerciser Program');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('engine_exerciser_F11C_enabled','1597','Engine Exerciser Program');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('engine_exerciser_F11C_test_with_load_transfer_enabled','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('engine_exerciser_F11C_start_week','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('engine_exerciser_F11C_day_of_week','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('engine_exerciser_F11C_start_time_hours','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('engine_exerciser_F11C_start_time_minutes','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('engine_exerciser_F11C_run_time_hours','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('engine_exerciser_F11C_run_time_minutes','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('normal_voltage_dropout','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('normal_voltage_pickup','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('normal_over_voltage_trip','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('normal_frequency_dropout','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('normal_frequency_pickup','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('normal_over_frequency_trip','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_voltage_dropout','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_voltage_pickup','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_over_voltage_trip','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_frequency_dropout','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_frequency_pickup','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_over_frequency_trip','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('timer_1C_engine_start_time_delay','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('timer_1F_engine_fail_time_delay','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('timer_2B_transfer_N_to_E_time_delay','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('timer_2E_engine_cool_down_time_delay','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('timer_3AF_transfer_E_to_N_on_source_fail_time_delay','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('timer_3A_T_transfer_E_to_N_on_test_time_delay','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('timer_31F_N_to_E_pre_transfer_signal_time_delay','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('timer_31M_N_to_E_post_transfer_signal_time_delay','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('timer_31G_E_to_N_pre_transfer_signal_time_delay','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('timer_31N_E_to_N_post_transfer_signal_time_delay','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dtts_load_disconnect_time_delay','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('commit_to_transfer_after_engine_start','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('real_time_hour','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('real_time_minute','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('real_time_second','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('calendar_year','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('calendar_month','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('calendar_day_of_month','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('calendar_day_of_week','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_time_E_has_been_acceptable_high_word','1597','Statistics');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_time_E_has_been_acceptable_low_word','1597','Statistics');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_time_N_has_been_acceptable_high_word','1597','Statistics');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_time_N_has_been_acceptable_low_word','1597','Statistics');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_number_of_days_CP_has_been_energized','1597','Statistics');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('LAST GENERATOR START SIGNAL','1597','Statistics');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_number_of_TS_transfers','1597','Statistics');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_number_of_TS_transfers_due_to_source_failures','1597','Statistics');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('controller_location','1597','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('external_feature_5_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('external_feature_34_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('inphase_filter_enabled','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('current_sensing_option_installed','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('advanced_11C_option_installed','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event_log_option_installed','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rs485_communication_option_installed','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('op1_common_alarm_option_installed','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_time_load_connected_to_normal_high_word','1597','Statistics');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_time_load_connected_to_normal_low_word','1597','Statistics');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_time_load_connected_to_emergency_high_word','1597','Statistics');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_time_load_connected_to_emergency_low_word','1597','Statistics');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rs485_port_address','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('rs485_port_baud_rate','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rs485_port_protocol','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('emulate_group_1','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dtts_load_disconnect_recovery_mode_manual_or_auto','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('timer_2B_transfer_N_to_E_on_test_time_delay','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('inphase_monitor_time_delay','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ats_total_transfer_time_in_10th_seconds','1597','Statistics');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dongle_installed','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('language_selection_setting','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('lcd_contrast_setting','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('controller_name','1597','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('boot_loader_version','1597','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('voltage_display_phase_label_selection','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('source_1_or_source_2_selection_for_different_english_format','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('external_battery_enabled','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ts_data_gen_start_date','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ts_data_gen_start_month','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ts_data_gen_start_year','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ts_data_gen_start_hour','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ts_data_gen_start_minutes','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ts_data_gen_start_seconds','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ts_data_gen_start_10th_of_seconds','1597','Engine Exerciser Program');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ts_data_gen_start_elapsed_time_in_seconds','1597','Engine Exerciser Program');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('total_active_alarm_number','1597','Statistics');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('load_shed_direction','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dtts_only_load_shed_on_source_failure','1597','Settings');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dtts_only_load_shed_on_request_f17','1597','Settings');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('normal_voltage_unbalance_dropout','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('normal_voltage_unbalance_pickup','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_voltage_unbalance_dropout','1597','Pickup & Dropout');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_voltage_unbalance_pickup','1597','Pickup & Dropout');



REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dpmDPMName','1593','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dpmDPMLoca','1593','Identification');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmNominalVoltage','1593','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmNominalCurrent','1593','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmInstantaneousDemandKwMW','1593','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmInstantaneousDemandDate','1593','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmInstantaneousDemandTime','1593','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullHouInkW','1593','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullDayInkW','1593','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullMonthInKW','1593','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullYear','1593','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurrentThdA','1593','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurrentThdB','1593','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurrentThdC','1593','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVoltageThdA','1593','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVoltageThdB','1593','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVoltageThdC','1593','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVab','1593','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVbc','1593','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVca','1593','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVolAverage','1593','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVolUnbal','1593','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVan','1593','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVbn','1593','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVcn','1593','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVavgneutral','1593','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmFrequency','1593','Voltage Unbalance & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmPowerFactor','1593','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurPhaseA','1593','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurPhaseB','1593','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurPhaseC','1593','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmPhaseCurAvg','1593','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmPhaseCurUnbalancecePercentage','1593','Voltage Unbalance & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmRealTotalPower','1593','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmReactiveTotalPower','1593','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmApparentTotalPower','1593','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmNetActiveEnergy','1593','Power');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dpmFirmwareVersion','1593','Identification');



REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dpmDPMName','1595','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dpmDPMLoca','1595','Identification');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmNominalVoltage','1595','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmNominalCurrent','1595','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmInstantaneousDemandKwMW','1595','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmInstantaneousDemandDate','1595','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmInstantaneousDemandTime','1595','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullHouInkW','1595','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullDayInkW','1595','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullMonthInKW','1595','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullYear','1595','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurrentThdA','1595','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurrentThdB','1595','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurrentThdC','1595','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVoltageThdA','1595','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVoltageThdB','1595','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVoltageThdC','1595','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVab','1595','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVbc','1595','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVca','1595','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVolAverage','1595','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVolUnbal','1595','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVan','1595','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVbn','1595','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVcn','1595','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVavgneutral','1595','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmFrequency','1595','Voltage Unbalance & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmPowerFactor','1595','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurPhaseA','1595','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurPhaseB','1595','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurPhaseC','1595','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmPhaseCurAvg','1595','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmPhaseCurUnbalancecePercentage','1595','Voltage Unbalance & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmRealTotalPower','1595','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmReactiveTotalPower','1595','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmApparentTotalPower','1595','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmNetActiveEnergy','1595','Power');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dpmFirmwareVersion','1595','Identification');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Contact Address','1599','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Contact Email','1599','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Contact Website','1599','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Firmware','1599','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1599','Identification');








REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dpmDPMName','1594','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dpmDPMLoca','1594','Identification');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmNominalVoltage','1594','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmNominalCurrent','1594','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmInstantaneousDemandKwMW','1594','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmInstantaneousDemandDate','1594','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmInstantaneousDemandTime','1594','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullHouInkW','1594','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullDayInkW','1594','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullMonthInKW','1594','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullYear','1594','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurrentThdA','1594','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurrentThdB','1594','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurrentThdC','1594','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVoltageThdA','1594','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVoltageThdB','1594','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVoltageThdC','1594','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVab','1594','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVbc','1594','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVca','1594','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVolAverage','1594','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVolUnbal','1594','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVan','1594','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVbn','1594','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVcn','1594','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVavgneutral','1594','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmFrequency','1594','Voltage Unbalance & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmPowerFactor','1594','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurPhaseA','1594','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurPhaseB','1594','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurPhaseC','1594','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmPhaseCurAvg','1594','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmPhaseCurUnbalancecePercentage','1594','Voltage Unbalance & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmRealTotalPower','1594','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmReactiveTotalPower','1594','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmApparentTotalPower','1594','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmNetActiveEnergy','1594','Power');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dpmFirmwareVersion','1594','Identification');





REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dpmDPMName','1596','Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dpmDPMLoca','1596','Identification');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmNominalVoltage','1596','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmNominalCurrent','1596','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmInstantaneousDemandKwMW','1596','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmInstantaneousDemandDate','1596','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmInstantaneousDemandTime','1596','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullHouInkW','1596','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullDayInkW','1596','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullMonthInKW','1596','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmBlockDemandforFullYear','1596','Max Demand Information');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurrentThdA','1596','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurrentThdB','1596','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurrentThdC','1596','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVoltageThdA','1596','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVoltageThdB','1596','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVoltageThdC','1596','Power Quality');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVab','1596','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVbc','1596','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVca','1596','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVolAverage','1596','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVolUnbal','1596','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVan','1596','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVbn','1596','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVcn','1596','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmVavgneutral','1596','Voltage');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmFrequency','1596','Voltage Unbalance & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmPowerFactor','1596','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurPhaseA','1596','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurPhaseB','1596','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmCurPhaseC','1596','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmPhaseCurAvg','1596','Current');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmPhaseCurUnbalancecePercentage','1596','Voltage Unbalance & Frequency');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmRealTotalPower','1596','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmReactiveTotalPower','1596','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmApparentTotalPower','1596','Power');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('dpmNetActiveEnergy','1596','Power');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('dpmFirmwareVersion','1596','Identification');

-- End Add def_prop_groups_map and def_status_groups_map to ASCO devices

UPDATE css_networking_device_prop_def SET visible=0 WHERE variable_name IN ('ts_data_gen_start_year','ts_data_gen_start_month','ts_data_gen_start_date','ts_data_gen_start_hour','ts_data_gen_start_minutes','ts_data_gen_start_seconds');
RAWSQL
            );
            // R7.3 - B5297
            DB::unprepared(<<<RAWSQL
-- add the Sensor Report type to css_networking_report_types
INSERT IGNORE INTO css_networking_report_types (id, report_type, alarm_settings, coord_inherit, blank_values, type_id, generator_settings, help_text) VALUES ('45', 'Sensor Report', '0', '0', '0', '0', '0', 'This report will produce an Excel spreadsheet with 1 device per Sheet and 1 column per selected property in 15 minute time intervals. All properties are rounded down to the nearest 15 minute interval.');

RAWSQL
            );
            // R7.3 - B5377
            DB::unprepared(<<<RAWSQL
UPDATE css_networking_device_type SET
defaultWebUiUser = 'G8Keeper',
defaultWebUiPw = '65b5bdb2322772a76c92d6fc1ec26e8b'
WHERE id = 5000

RAWSQL
            );
            // R7.3 - B5443
            DB::unprepared(<<<RAWSQL
-- Inserts an invisible prop_def to be used by scanners to avoid re-processing existing cleared alarms when rebuilding a SiteGate.

REPLACE INTO css_networking_device_prop_def
(
device_type_id,
variable_name,
name,
data_type,
visible
)
VALUES
(
5000,
'max_remote_cleared_alarm_id',
'Maximum remote cleared alarm id when built',
'INTEGER',
0
);

RAWSQL
            );
            // R7.3 - B5720
            DB::unprepared(<<<RAWSQL

-- START add prop_defs for main device for ION-U. Tareq 100215
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','basicAgentVersion','basicAgentVersion','Software Version','STRING','0','1','This is the software version of the agent and is determined accordingto the software version control rules of the particular device.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','basicCurrentMibVersion','basicCurrentMibVersion','Current MIB version','STRING','0','1','Current MIBs version');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','basicWirelessInnovationsMibVersion','basicWirelessInnovationsMibVersion','WirelessInnovations MIB Version','STRING','0','1','The version of the WIRELESS-INNOVATIONS-PRODUCTS-MIB');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','basicCurrentTime','basicCurrentTime','Current time','STRING','0','1','Current time in Coordinated Universal Time (UTC) format.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connLanGateway','connLanGateway','Default Gateway','STRING','0','1','Default Gateway');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','.1.3.6.1.2.1.4.21.1.11.10.140.0.0','.1.3.6.1.2.1.4.21.1.11.10.140.0.0','Subnet Mask','STRING','0','1','Subnet Mask');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connAgentIPAddressLAN','connAgentIPAddressLAN','IP Address','STRING','0','1','IP Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','alarmRecipient','alarmRecipient','Alarm Recipient','STRING','0','1','The recipient of all notifications');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','alarmForwardingEnabled','alarmForwardingEnabled','Enable/Disable alarm forwarding','INTEGER','0','1','Enable/Disable alarm forwarding');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','alarmHeartbeatInterval','alarmHeartbeatInterval','Heartbeat Interval (Minutes)','INTEGER','1','1','Heartbeat Interval. Allowed range is 0 to 6000 minutes (0 to 100 hours).');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','alarmSynchronize','alarmSynchronize','Alarm Synchronize','INTEGER','0','1','This object must be set to true(1) in order to force the agent to resend all active alarms to the current manager. Setting this object to false(2) has no effect. On GET requests always false(2) is returned.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','alarmTrapCounter','alarmTrapCounter','Trap Counter','INTEGER','0','2','This is the counter indicating how many total notifications have been sent from the device since last bootup (or reboot).');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','alarmSummary','alarmSummary','System summary alarm status','INTEGER','0','1','System summary alarm status.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connAgentUID','connAgentUID','Agent ID','STRING','0','1','the agent unique identifier.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connAgentNotificationType','connAgentNotificationType','Notification type','INTEGER','1','1','Notification type the agent uses for any component specific event.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connAgentUID2','connAgentUID2','Alternative Agent ID','STRING','0','1','The alternative unique identifier of the Agent.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connAgentWebCommunicationProtocol','connAgentWebCommunicationProtocol','Web Communication Protocol','INTEGER','1','1','Web Communication Protocol');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connAgentInformTimeout','connAgentInformTimeout','Inform Timeout (Seconds)','INTEGER','1','1','This object shows/controls the timeout (in seconds) used by the Agent waiting for inform responses before resending.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connAgentInformRetries','connAgentInformRetries','Inform Retries','INTEGER','1','1','This object shows/controls the number of retries used by the Agent in case inform resending. Allowed range is 1 to 20 times or -1. The value of -1 indicates retries forever until the inform resonse gets received.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connAgntOrigIdleTimeoutPSODreqBySMS','connAgntOrigIdleTimeoutPSODreqBySMS','Communication idle timeout for PSOD (Minutes)','INTEGER','1','1','Communication idle timeout in minutes for agent side "On Demand" packet switched network connection.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connAttachPSODbySMSenabled','connAttachPSODbySMSenabled','Enable/Disable PSOD','INTEGER','1','1','Shows/sets whether SMS initiated packet switched network connection in the "On demand" mode is enabled or disabled.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connAgentSMSserviceCenter','connAgentSMSserviceCenter','SMS service center number','STRING','1','1','SMS service center number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connAgentDisconnectTrigger','connAgentDisconnectTrigger','Disconnect  connection','INTEGER','1','1','Force a disconnect of an active agent originated network connection.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connAgntOrigAlwaysOnInactivityTimer','connAgntOrigAlwaysOnInactivityTimer','Communication idle timeout','INTEGER','1','1','Communication idle timeout (related to application oriented incoming data requests) in minutes for agent outgoing always on connections. Allowed range is 0 for infinite time or 30 to 1440 minutes (24 hours).');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','.1.3.6.1.2.1.1.3','Up Time','Up Time (Seconds)','STRING','0','2','SYSTEM UP TIME');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','.1.3.6.1.2.1.1.6','Location','Location','STRING','0','1','SYSTEM LOCATION');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','.1.3.6.1.2.1.1.4','System contact','System contact','STRING','0','1','SYSTEM CONTACT');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','.1.3.6.1.2.1.1.5','System name','System name','STRING','0','1','SYSTEM NAME');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','sysCtrlApplication','sysCtrlApplication','Reboot','INTEGER','1','1','Reboot causes a complete reboot of the operating system running at the Agent.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connLanSubnetPrefixSize','connLanSubnetPrefixSize','LAN Subnet Prefix Size','INTEGER','1','1','Agent LAN routing prefix size of subnet (similar CIDR notation), equivalent to the number of leading 1 bits in the routing prefix mask.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connLanAddressType','connLanAddressType','LAN IP Address Type','INTEGER','1','1','Agent LAN IP address type (IPv4 or IPv6).');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connLanDhcpEnabled','connLanDhcpEnabled','DHCP Enabled','INTEGER','1','1','Object that shows / controls whether automatic DHCP LAN IP address assignment is enabled or not.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('1548','connAgentSnmpSecurityModel','connAgentSnmpSecurityModel','SNMP Security Model','INTEGER','1','1','Security model for SNMP communication.');
-- END add prop_defs for main device for ION-U. Tareq 100215



-- START add prop_groups/status_groups for main device for ION-U. Tareq 100215
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Unit Identification','Unit Identification');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Setup','Setup');
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Setup\\Connectivity','Setup\\Connectivity');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Unit Identification','Unit Identification');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Setup','Setup');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Setup\\Connectivity','Setup\\Connectivity');
-- END add prop_groups/status_groups for main device for ION-U. Tareq 100215

-- START add prop_groups_map/status_groups_map for main device for ION-U. Tareq 100215
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('basicAgentVersion','1548','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('basicCurrentMibVersion','1548','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('basicWirelessInnovationsMibVersion','1548','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('basicCurrentTime','1548','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connLanGateway','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('.1.3.6.1.2.1.4.21.1.11.10.140.0.0','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connAgentIPAddressLAN','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('alarmRecipient','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('alarmForwardingEnabled','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('alarmHeartbeatInterval','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('alarmSynchronize','1548','Setup\\Connectivity');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('alarmTrapCounter','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('alarmSummary','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connAgentUID','1548','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connAgentNotificationType','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connAgentUID2','1548','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connAgentWebCommunicationProtocol','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connAgentInformTimeout','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connAgentInformRetries','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connAgntOrigIdleTimeoutPSODreqBySMS','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connAttachPSODbySMSenabled','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connAgentSMSserviceCenter','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connAgentDisconnectTrigger','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connAgntOrigAlwaysOnInactivityTimer','1548','Setup\\Connectivity');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Up Time','1548','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Location','1548','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('System contact','1548','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('System name','1548','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('sysCtrlApplication','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connLanSubnetPrefixSize','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connLanAddressType','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connLanDhcpEnabled','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('connAgentSnmpSecurityModel','1548','Setup\\Connectivity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1548','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('firmware','1548','Unit Identification');
-- END add prop_groups_map/status_groups_map for main device for ION-U. Tareq 100215



-- START
REPLACE INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1548,'https','HTTPS',443);
-- END


-- START
REPLACE INTO css_networking_device_type(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device)
VALUES(1601,15,"Andrew","RU Band",0,1,0,0,1,0);
-- END

-- START
REPLACE INTO css_networking_device_type(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device)
VALUES(1602,2,"Andrew","RU Service",0,1,0,0,1,0);
-- END

-- START
REPLACE INTO css_networking_device_type(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device)
VALUES(1603,2,"Andrew","POI Service",0,1,0,0,1,0);
-- END

-- START
REPLACE INTO css_networking_device_type(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,can_disable, main_device)
VALUES(1604,2,"Andrew","POI Path",0,1,0,0,1,0);
-- END


-- START
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('RF Parameters','RF Parameters');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('RF Parameters','RF Parameters');
-- END

-- START
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiPathDasPortLabel','1604','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiPathIsActive','1604','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiAttenuationCurrentValue','1604','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiAttenuationDirection','1604','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiPathBtsPortLabel','1604','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiAttenuationMinValue','1604','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiAttenuationMaxValue','1604','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiAttenuationResolutionValue','1604','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiAttenuationRefValue','1604','RF Parameters');
-- END

-- START
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1604','poiPathDasPortLabel','poiPathDasPortLabel','POI DAS label','STRING','0','1','notAvailable:  may be responded in case the value is temporary not available (try later).
notSupported: indicates that the object is not supported by the referenced POI type resp.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1604','poiPathIsActive','poiPathIsActive','Path status','INTEGER','1','1','notAvailable:  may be responded in case the value is temporary not available (try later).
notSupported: indicates that the object is not supported by the referenced POI type resp.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1604','poiAttenuationCurrentValue','poiAttenuationCurrentValue','Attenuation (dB)','INTEGER','1','1','notAvailable:  may be responded in case the value is temporary not available (try later).
notSupported: indicates that the object is not supported by the referenced POI type resp.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1604','poiAttenuationDirection','poiAttenuationDirection','Direction of signal','STRING','0','1','notAvailable:  may be responded in case the value is temporary not available (try later).
notSupported: indicates that the object is not supported by the referenced POI type resp.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1604','poiPathBtsPortLabel','poiPathBtsPortLabel','Internal POI BTS label','STRING','0','1','notAvailable:  may be responded in case the value is temporary not available (try later).
notSupported: indicates that the object is not supported by the referenced POI type resp.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1604','poiAttenuationMinValue','poiAttenuationMinValue','Minimum attenuation  (dB)','STRING','0','1','notAvailable:  may be responded in case the value is temporary not available (try later).
notSupported: indicates that the object is not supported by the referenced POI type resp.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1604','poiAttenuationResolutionValue','poiAttenuationResolutionValue','Attenuation Resolution  (dB)','STRING','0','1','notAvailable:  may be responded in case the value is temporary not available (try later).
notSupported: indicates that the object is not supported by the referenced POI type resp.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1604','poiAttenuationMaxValue','poiAttenuationMaxValue','Maximum attenuation  (dB)','STRING','0','1','notAvailable:  may be responded in case the value is temporary not available (try later).
notSupported: indicates that the object is not supported by the referenced POI type resp.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1604','poiAttenuationRefValue','poiAttenuationRefValue','Referent attenuation  (dB)','STRING','0','1','notAvailable:  may be responded in case the value is temporary not available (try later).
notSupported: indicates that the object is not supported by the referenced POI type resp.');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2048','entPhysicalFirmwareRev','entPhysicalFirmwareRev','Firmware Rev','STRING','0','1','Firmware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2048','entPhysicalHardwareRev','entPhysicalHardwareRev','Hardware Rev','STRING','0','1','Hardware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2048','entPhysicalSerialNum','entPhysicalSerialNum','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2048','entPhysicalName','entPhysicalName','Physical Address','STRING','0','1','Physical Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2048','entPhysicalDescr','entPhysicalDescr','Description','STRING','0','1','Description');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2048','','ID','ID','STRING','0','1','ID');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2048','','Manufacture','Manufacture','STRING','0','1','Manufacture');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2048','','Module Type','Module Type','STRING','0','1','Module Type');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2048','','Produced','Produced','STRING','0','1','Produced');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1551','ruReboot','ruReboot','Reboot','INTEGER','1','1',' Allows to reboot the current remote unit.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1551','ruOpticalLossUL','ruOpticalLossUL','Optical Loss UL','INTEGER','0','2',' Optical loss (uplink channel) of the fiber connection to the remote unit.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1551','ruOpticalLossDL','ruOpticalLossDL','Optical Loss DL','INTEGER','0','2',' Optical loss (downlink channel) of the fiber connection to the remote unit.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1551','ruVswrAntennaPort','ruVswrAntennaPort','VSWR Antenna Port','STRING','0','1','VSWR Antenna Port.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1551','ruVswrAlarmEnabled','ruVswrAlarmEnabled','VSWR Alarm Enable','INTEGER','1','1','Enable/Disable VSWR Alarms.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1551','ruVswrAlarmLatency','ruVswrAlarmLatency','VSWR Alarm Latency (Minutes)','INTEGER','1','1','VSWR Alarm Latency (Minutes).');


REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1601','ruBandMimoPath','ruBandMimoPath','Mimo Path','STRING','0','1','Mimo Path');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1601','ruBandActive','ruBandActive','Band Status','INTEGER','1','1','Indicates whether the power amplifier is active.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1601','ruBandDlAttenuator','ruBandDlAttenuator','DL Attenuator (dB)','INTEGER','1','1','DL Attenuator (dB)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1601','ruBandDlAttenuatorMinValue','ruBandDlAttenuatorMinValue','DL Attenuator Min (dB)','INTEGER','0','1','DL Attenuator Min Value');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1601','ruBandDlAttenuatorMaxValue','ruBandDlAttenuatorMaxValue','DL Attenuator Max (dB)','INTEGER','0','1','DL Attenuator Max Value');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1601','ruBandDlAttenuatorRefValue','ruBandDlAttenuatorRefValue','DL AttenuatorReferent (dB)','INTEGER','0','1','DL Attenuator Ref Value');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1601','ruBandDlAttenuatorResolutionValue','ruBandDlAttenuatorResolutionValue','Dl Attenuator Resolution (dB)','INTEGER','0','1','Dl Attenuator Resolution Value');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1601','ruBandId','ruBandId','Band ID','INTEGER','0','1','The band associated with the current power amplifier.');
-- END

-- START
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ruOpticalLossUL','1551','RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ruOpticalLossDL','1551','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruReboot','1551','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruVswrAntennaPort','1551','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruVswrAlarmEnabled','1551','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruVswrAlarmLatency','1551','RF Parameters');



REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruBandMimoPath','1601','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruBandActive','1601','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruBandDlAttenuator','1601','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruBandDlAttenuatorMinValue','1601','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruBandDlAttenuatorMaxValue','1601','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruBandDlAttenuatorRefValue','1601','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruBandDlAttenuatorResolutionValue','1601','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruBandId','1601','RF Parameters');
-- END


-- START
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','poiModuleServiceMapCTA','poiModuleServiceMapCTA','IPOI CTA','STRING','0','1','CTA for the current IPOI.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','poiModuleServiceMapDlPort','poiModuleServiceMapDlPort','DL port','STRING','0','1','DL port lable associated for the current iPOI port.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','poiModuleServiceMapUlPort','poiModuleServiceMapUlPort','UL port','STRING','0','1','UL port lable associated for the current iPOI port.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','poiModuleServiceMapInputPower','poiModuleServiceMapInputPower','Input Power (dBm)','INTEGER','0','2','The input power for the given iPOI port.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','poiModuleServiceMapAlcThreshold','poiModuleServiceMapAlcThreshold','ALC threshold','INTEGER','1','1','The ALC threshold for the given iPOI port.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','poiModuleServiceMapSectorGroupInstanceId','poiModuleServiceMapSectorGroupInstanceId','Sector Group Instance Id','INTEGER','0','1','A reference to a sector group instance.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','poiModuleServiceMapAlarmThresholdLow','poiModuleServiceMapAlarmThresholdLow','Alarm Threshold Low','INTEGER','1','1','Alarm Threshold Low');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','poiModuleServiceMapAlarmThresholdLowActive','poiModuleServiceMapAlarmThresholdLowActive','Enable Alarm Threshold Low','INTEGER','1','1','Enable Alarm Threshold Low');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','poiModuleServiceMapAlarmThresholdHigh','poiModuleServiceMapAlarmThresholdHigh','Alarm Threshold High','INTEGER','1','1','Alarm Threshold High');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','poiModuleServiceMapAlarmThresholdHighActive','poiModuleServiceMapAlarmThresholdHighActive','Enable Alarm Threshold High','INTEGER','1','1','Enable Alarm Threshold High');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlarmThresholdAlc','Alarm Threshold ALC','INTEGER','1','1','Alarm Threshold ALC');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','poiModuleServiceMapAlarmThresholdAlcActive','poiModuleServiceMapAlarmThresholdAlcActive','Enable Alarm Threshold ALC','INTEGER','1','1','Enable Alarm Threshold ALC');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','poiModuleServiceMapAlarmLatency','poiModuleServiceMapAlarmLatency','Alarm Latency (Minutes)','INTEGER','1','1','Alarm Latency');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorGroupInstanceId','ionuSectorGroupInstanceId','Sector Group Instance Id','INTEGER','0','1','Sector Group Instance Id');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorGroupInstanceSectorId','ionuSectorGroupInstanceSectorId','Sector Group Instance Sector Id','INTEGER','0','1','Sector Group Instance Sector Id');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorGroupInstanceName','ionuSectorGroupInstanceName','Sector Group Instance Name','STRING','0','1','Specifies the name of the current sector group instance row.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorGroupInstanceUserDefinedId','ionuSectorGroupInstanceUserDefinedId','User defined ID of the sector group instance','STRING','0','1','The user defined ID of the sector group instance. It may be identical to the name.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorGroupInstanceSectorGroupId','ionuSectorGroupInstanceSectorGroupId','Sector Group Instance Sector GroupId','INTEGER','0','1','The reference to the sector group');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorProfileId','ionuSectorProfileId','Sector Profile Id','INTEGER','0','1','Sector Profile Id');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorProfileOperator','ionuSectorProfileOperator','Operator Name','STRING','0','1','Operator Name');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorProfileTechnology','ionuSectorProfileTechnology','Technology','STRING','0','1','Technology being e.g. UMTS, LTE, GSM, ..');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorProfilePilotRatio','ionuSectorProfilePilotRatio','Pilot to total power ratio','INTEGER','0','2','pilot to total power ratio');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorProfileNumChannels','ionuSectorProfileNumChannels','Number of channels','INTEGER','0','2','Number of channels within the current sector profile.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorProfileBandId','ionuSectorProfileBandId','Sector Profile Band Id','INTEGER','0','1','Sector Profile Band Id');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorProfileBandwidthValue','ionuSectorProfileBandwidthValue','Bandwidth','INTEGER','0','1','The bandwidth of the current sector profile.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorProfileBandwidthUnit','ionuSectorProfileBandwidthUnit','Bandwidth Unit','STRING','0','1','Bandwidth Unit');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorProfileSectorGroupId','ionuSectorProfileSectorGroupId','Sector group ID ','STRING','0','1',' A sector group ID links sector profiles with sector group instances.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1603','ionuSectorProfileCarrierFrequency','ionuSectorProfileCarrierFrequency','Frequency  [MHz]','STRING','0','1','Carrier frequency in [MHz].');


REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ruServiceCTA','ruServiceCTA','Service CTA','INTEGER','0','1','Service CTA');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ruServiceBandId','ruServiceBandId','Band ID','INTEGER','0','1','Band ID');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ruServiceSectorProfileId','ruServiceSectorProfileId','Profile ID','INTEGER','0','1','Profile ID');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ruServiceMimoPath','ruServiceMimoPath','Mimo Path','INTEGER','0','1','Mimo Path');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ruServiceOutputPower','ruServiceOutputPower','Output power (dBm)','INTEGER','0','2','Output power for the given service.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ruServiceAlarmThresholdLow','ruServiceAlarmThresholdLow','Alarm Threshold Low','INTEGER','1','1','Alarm Threshold Low');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ruServiceAlarmThresholdLowActive','ruServiceAlarmThresholdLowActive','Enable Alarm Threshold Low','INTEGER','1','1','Enable Alarm Threshold Low');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ruServiceAlarmThresholdHigh','ruServiceAlarmThresholdHigh','Alarm Threshold High','INTEGER','1','1','Alarm Threshold High');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ruServiceAlarmThresholdHighActive','ruServiceAlarmThresholdHighActive','Enable Alarm Threshold High','INTEGER','1','1','Enable Alarm Threshold High');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ruServiceAlarmLatency','ruServiceAlarmLatency','Alarm Latency (Minutes)','INTEGER','1','1','Alarm Latency');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ionuSectorProfileId','ionuSectorProfileId','Sector Profile Id','INTEGER','0','1','Sector Profile Id');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ionuSectorProfileOperator','ionuSectorProfileOperator','Operator Name','STRING','0','1','Operator Name');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ionuSectorProfileTechnology','ionuSectorProfileTechnology','Technology','STRING','0','1','Technology being e.g. UMTS, LTE, GSM, ..');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ionuSectorProfilePilotRatio','ionuSectorProfilePilotRatio','Pilot to total power ratio','INTEGER','0','2','pilot to total power ratio');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ionuSectorProfileNumChannels','ionuSectorProfileNumChannels','Number of channels','INTEGER','0','2','Number of channels within the current sector profile.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ionuSectorProfileBandId','ionuSectorProfileBandId','Sector Profile Band Id','INTEGER','0','1','Sector Profile Band Id');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ionuSectorProfileBandwidthValue','ionuSectorProfileBandwidthValue','Bandwidth','INTEGER','0','1','The bandwidth of the current sector profile.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ionuSectorProfileBandwidthUnit','ionuSectorProfileBandwidthUnit','Bandwidth Unit','STRING','0','1','Bandwidth Unit');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ionuSectorProfileSectorGroupId','ionuSectorProfileSectorGroupId','Sector group ID ','STRING','0','1',' A sector group ID links sector profiles with sector group instances.');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1602','ionuSectorProfileCarrierFrequency','ionuSectorProfileCarrierFrequency','Frequency  [MHz]','STRING','0','1','Carrier frequency in [MHz].');
-- END

-- START
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1556','entPhysicalFirmwareRev','entPhysicalFirmwareRev','Firmware Rev','STRING','0','1','Firmware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1556','entPhysicalHardwareRev','entPhysicalHardwareRev','Hardware Rev','STRING','0','1','Hardware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1556','entPhysicalSerialNum','entPhysicalSerialNum','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1556','entPhysicalName','entPhysicalName','Physical Address','STRING','0','1','Physical Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1556','entPhysicalDescr','entPhysicalDescr','Description','STRING','0','1','Description');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1556','entPhysicalSoftwareRev','entPhysicalSoftwareRev','Software Version','STRING','0','1','Software Version');


REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1551','entPhysicalFirmwareRev','entPhysicalFirmwareRev','Firmware Rev','STRING','0','1','Firmware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1551','entPhysicalHardwareRev','entPhysicalHardwareRev','Hardware Rev','STRING','0','1','Hardware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1551','entPhysicalSerialNum','entPhysicalSerialNum','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1551','entPhysicalName','entPhysicalName','Physical Address','STRING','0','1','Physical Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1551','entPhysicalDescr','entPhysicalDescr','Description','STRING','0','1','Description');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1551','entPhysicalSoftwareRev','entPhysicalSoftwareRev','Software Version','STRING','0','1','Software Version');


REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1552','entPhysicalFirmwareRev','entPhysicalFirmwareRev','Firmware Rev','STRING','0','1','Firmware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1552','entPhysicalHardwareRev','entPhysicalHardwareRev','Hardware Rev','STRING','0','1','Hardware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1552','entPhysicalSerialNum','entPhysicalSerialNum','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1552','entPhysicalName','entPhysicalName','Physical Address','STRING','0','1','Physical Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1552','entPhysicalDescr','entPhysicalDescr','Description','STRING','0','1','Description');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1552','entPhysicalSoftwareRev','entPhysicalSoftwareRev','Software Version','STRING','0','1','Software Version');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1553','entPhysicalFirmwareRev','entPhysicalFirmwareRev','Firmware Rev','STRING','0','1','Firmware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1553','entPhysicalHardwareRev','entPhysicalHardwareRev','Hardware Rev','STRING','0','1','Hardware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1553','entPhysicalSerialNum','entPhysicalSerialNum','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1553','entPhysicalName','entPhysicalName','Physical Address','STRING','0','1','Physical Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1553','entPhysicalDescr','entPhysicalDescr','Description','STRING','0','1','Description');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1553','entPhysicalSoftwareRev','entPhysicalSoftwareRev','Software Version','STRING','0','1','Software Version');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1554','entPhysicalFirmwareRev','entPhysicalFirmwareRev','Firmware Rev','STRING','0','1','Firmware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1554','entPhysicalHardwareRev','entPhysicalHardwareRev','Hardware Rev','STRING','0','1','Hardware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1554','entPhysicalSerialNum','entPhysicalSerialNum','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1554','entPhysicalName','entPhysicalName','Physical Address','STRING','0','1','Physical Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1554','entPhysicalDescr','entPhysicalDescr','Description','STRING','0','1','Description');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1554','entPhysicalSoftwareRev','entPhysicalSoftwareRev','Software Version','STRING','0','1','Software Version');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1555','entPhysicalFirmwareRev','entPhysicalFirmwareRev','Firmware Rev','STRING','0','1','Firmware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1555','entPhysicalHardwareRev','entPhysicalHardwareRev','Hardware Rev','STRING','0','1','Hardware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1555','entPhysicalSerialNum','entPhysicalSerialNum','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1555','entPhysicalName','entPhysicalName','Physical Address','STRING','0','1','Physical Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1555','entPhysicalDescr','entPhysicalDescr','Description','STRING','0','1','Description');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1555','entPhysicalSoftwareRev','entPhysicalSoftwareRev','Software Version','STRING','0','1','Software Version');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1984','entPhysicalFirmwareRev','entPhysicalFirmwareRev','Firmware Rev','STRING','0','1','Firmware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1984','entPhysicalHardwareRev','entPhysicalHardwareRev','Hardware Rev','STRING','0','1','Hardware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1984','entPhysicalSerialNum','entPhysicalSerialNum','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1984','entPhysicalName','entPhysicalName','Physical Address','STRING','0','1','Physical Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1984','entPhysicalDescr','entPhysicalDescr','Description','STRING','0','1','Description');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('1984','entPhysicalSoftwareRev','entPhysicalSoftwareRev','Software Version','STRING','0','1','Software Version');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2023','entPhysicalFirmwareRev','entPhysicalFirmwareRev','Firmware Rev','STRING','0','1','Firmware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2023','entPhysicalHardwareRev','entPhysicalHardwareRev','Hardware Rev','STRING','0','1','Hardware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2023','entPhysicalSerialNum','entPhysicalSerialNum','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2023','entPhysicalName','entPhysicalName','Physical Address','STRING','0','1','Physical Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2023','entPhysicalDescr','entPhysicalDescr','Description','STRING','0','1','Description');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2023','entPhysicalSoftwareRev','entPhysicalSoftwareRev','Software Version','STRING','0','1','Software Version');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2041','entPhysicalFirmwareRev','entPhysicalFirmwareRev','Firmware Rev','STRING','0','1','Firmware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2041','entPhysicalHardwareRev','entPhysicalHardwareRev','Hardware Rev','STRING','0','1','Hardware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2041','entPhysicalSerialNum','entPhysicalSerialNum','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2041','entPhysicalName','entPhysicalName','Physical Address','STRING','0','1','Physical Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2041','entPhysicalDescr','entPhysicalDescr','Description','STRING','0','1','Description');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2041','entPhysicalSoftwareRev','entPhysicalSoftwareRev','Software Version','STRING','0','1','Software Version');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2045','entPhysicalFirmwareRev','entPhysicalFirmwareRev','Firmware Rev','STRING','0','1','Firmware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2045','entPhysicalHardwareRev','entPhysicalHardwareRev','Hardware Rev','STRING','0','1','Hardware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2045','entPhysicalSerialNum','entPhysicalSerialNum','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2045','entPhysicalName','entPhysicalName','Physical Address','STRING','0','1','Physical Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2045','entPhysicalDescr','entPhysicalDescr','Description','STRING','0','1','Description');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2045','entPhysicalSoftwareRev','entPhysicalSoftwareRev','Software Version','STRING','0','1','Software Version');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2046','entPhysicalFirmwareRev','entPhysicalFirmwareRev','Firmware Rev','STRING','0','1','Firmware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2046','entPhysicalHardwareRev','entPhysicalHardwareRev','Hardware Rev','STRING','0','1','Hardware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2046','entPhysicalSerialNum','entPhysicalSerialNum','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2046','entPhysicalName','entPhysicalName','Physical Address','STRING','0','1','Physical Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2046','entPhysicalDescr','entPhysicalDescr','Description','STRING','0','1','Description');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2046','entPhysicalSoftwareRev','entPhysicalSoftwareRev','Software Version','STRING','0','1','Software Version');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2047','entPhysicalFirmwareRev','entPhysicalFirmwareRev','Firmware Rev','STRING','0','1','Firmware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2047','entPhysicalHardwareRev','entPhysicalHardwareRev','Hardware Rev','STRING','0','1','Hardware Rev');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2047','entPhysicalSerialNum','entPhysicalSerialNum','Serial Number','STRING','0','1','Serial Number');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2047','entPhysicalName','entPhysicalName','Physical Address','STRING','0','1','Physical Address');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2047','entPhysicalDescr','entPhysicalDescr','Description','STRING','0','1','Description');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2047','entPhysicalSoftwareRev','entPhysicalSoftwareRev','Software Version','STRING','0','1','Software Version');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2048','entPhysicalSoftwareRev','entPhysicalSoftwareRev','Software Version','STRING','0','1','Software Version');
-- END

-- START
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','1556','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','1556','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','1556','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ID','1556','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Module Type','1556','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','1556','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Manufacture','1556','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Produced','1556','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1556','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','1556','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','1556','Unit Identification');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','1551','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','1551','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','1551','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ID','1551','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Module Type','1551','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','1551','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Manufacture','1551','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Produced','1551','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1551','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','1551','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','1551','Unit Identification');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','1552','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','1552','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','1552','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ID','1552','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Module Type','1552','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','1552','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Manufacture','1552','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Produced','1552','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1552','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','1552','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','1552','Unit Identification');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','1553','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','1553','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','1553','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ID','1553','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Module Type','1553','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','1553','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Manufacture','1553','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Produced','1553','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1553','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','1553','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','1553','Unit Identification');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','1554','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','1554','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','1554','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ID','1554','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Module Type','1554','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','1554','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Manufacture','1554','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Produced','1554','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','1554','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','1554','Unit Identification');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1554','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','1555','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','1555','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','1555','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ID','1555','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Module Type','1555','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','1555','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Manufacture','1555','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Produced','1555','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1555','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','1555','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','1555','Unit Identification');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','1984','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','1984','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','1984','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ID','1984','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Module Type','1984','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','1984','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Manufacture','1984','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Produced','1984','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1984','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','1984','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','1984','Unit Identification');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','2023','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','2023','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','2023','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ID','2023','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Module Type','2023','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','2023','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Manufacture','2023','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Produced','2023','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','2023','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','2023','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','2023','Unit Identification');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','2041','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','2041','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','2041','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ID','2041','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Module Type','2041','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','2041','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Manufacture','2041','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Produced','2041','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','2041','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','2041','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','2041','Unit Identification');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','2045','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','2045','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','2045','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ID','2045','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Module Type','2045','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','2045','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Manufacture','2045','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Produced','2045','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','2045','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','2045','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','2045','Unit Identification');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','2046','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','2046','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','2046','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ID','2046','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Module Type','2046','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','2046','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Manufacture','2046','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Produced','2046','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','2046','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','2046','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','2046','Unit Identification');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','2047','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','2047','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','2047','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ID','2047','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Module Type','2047','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','2047','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Manufacture','2047','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Produced','2047','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','2047','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','2047','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','2047','Unit Identification');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalDescr','2048','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalFirmwareRev','2048','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalHardwareRev','2048','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ID','2048','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Module Type','2048','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSerialNum','2048','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Manufacture','2048','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Produced','2048','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','2048','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalName','2048','Unit Identification');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('entPhysicalSoftwareRev','2048','Unit Identification');
-- END

-- START
UPDATE css_networking_device_type SET controller_file='andrew_controller.php' WHERE id IN (1601,1602,1603,1604,1551,1556,1552,1555,1554,1553,1984,2023,2041,2045,2046,2047,2048);
-- END

-- START
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','Disabled' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandActive',
            'poiPathIsActive',
            'poiModuleServiceMapAlarmThresholdAlcActive',
            'poiModuleServiceMapAlarmThresholdHighActive',
            'poiModuleServiceMapAlarmThresholdLowActive',
            'ruServiceAlarmThresholdLowActive',
            'ruServiceAlarmThresholdHighActive',
			'alarmForwardingEnabled',
			'connAttachPSODbySMSenabled',
			'connLanDhcpEnabled','ruVswrAlarmEnabled') AND device_type_id IN (1548,1601,1604,1603,1602,1551);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Enabled' FROM css_networking_device_prop_def
WHERE variable_name in ('ruBandActive',
            'poiPathIsActive',
            'poiModuleServiceMapAlarmThresholdAlcActive',
            'poiModuleServiceMapAlarmThresholdHighActive',
            'poiModuleServiceMapAlarmThresholdLowActive',
            'ruServiceAlarmThresholdLowActive',
            'ruServiceAlarmThresholdHighActive',
			'alarmForwardingEnabled',
			'connAttachPSODbySMSenabled',
			'connLanDhcpEnabled') AND device_type_id IN (1548,1601,1604,1603,1602,1551);
			
			

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','V2c' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentSnmpSecurityModel') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','V3USM' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentSnmpSecurityModel') AND device_type_id IN (1548);


REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0',' ' FROM css_networking_device_prop_def
WHERE variable_name in('alarmSynchronize') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Synchronize' FROM css_networking_device_prop_def
WHERE variable_name in('alarmSynchronize') AND device_type_id IN (1548);


REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0',' ' FROM css_networking_device_prop_def
WHERE variable_name in('sysCtrlApplication') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Reboot' FROM css_networking_device_prop_def
WHERE variable_name in('sysCtrlApplication') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','IPv4' FROM css_networking_device_prop_def
WHERE variable_name in('connLanAddressType') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','IPv6' FROM css_networking_device_prop_def
WHERE variable_name in('connLanAddressType') AND device_type_id IN (1548);


REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0',' ' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentDisconnectTrigger') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Disconnect' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentDisconnectTrigger') AND device_type_id IN (1548);



REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','HTTP' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentWebCommunicationProtocol') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','HTTPS' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentWebCommunicationProtocol') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3','HTTP and HTTPS' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentWebCommunicationProtocol') AND device_type_id IN (1548);


REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Trap' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentNotificationType') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','Inform' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentNotificationType') AND device_type_id IN (1548);


REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '10','010' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '20','020' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '30','030' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '40','040' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '50','050' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '60','060' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '120','120' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '180','180' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '240','240' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '300','300' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '360','360' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '420','420' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '480','480' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '540','540' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '600','600' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformTimeout') AND device_type_id IN (1548);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '-1','Until response' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','01' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','02' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3','03' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '4','04' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '5','05' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '6','06' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '7','07' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '8','08' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '9','09' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '10','10' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '11','11' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '12','12' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '13','13' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '14','14' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '15','15' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '16','16' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '17','17' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '18','18' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '19','19' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '20','20' FROM css_networking_device_prop_def
WHERE variable_name in('connAgentInformRetries') AND device_type_id IN ('1548');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-85','-85' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-84.5','-84.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-84','-84' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-83.5','-83.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-83','-83' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-82.5','-82.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-82','-82' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-81.5','-81.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-81','-81' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-80.5','-80.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-80','-80' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-79.5','-79.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-79','-79' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-78.5','-78.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-78','-78' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-77.5','-77.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-77','-77' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-76.5','-76.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-76','-76' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-75.5','-75.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-75','-75' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-74.5','-74.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-74','-74' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-73.5','-73.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-73','-73' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-72.5','-72.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-72','-72' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-71.5','-71.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-71','-71' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-70.5','-70.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-70','-70' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-69.5','-69.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-69','-69' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-68.5','-68.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-68','-68' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-67.5','-67.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-67','-67' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-66.5','-66.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-66','-66' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-65.5','-65.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-65','-65' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-64.5','-64.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-64','-64' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-63.5','-63.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-63','-63' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-62.5','-62.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-62','-62' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-61.5','-61.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-61','-61' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-60.5','-60.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-60','-60' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-59.5','-59.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-59','-59' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-58.5','-58.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-58','-58' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-57.5','-57.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-57','-57' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-56.5','-56.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-56','-56' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-55.5','-55.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-55','-55' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-54.5','-54.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-54','-54' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-53.5','-53.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-53','-53' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-52.5','-52.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-52','-52' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-51.5','-51.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-51','-51' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-50.5','-50.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-50','-50' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-49.5','-49.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-49','-49' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-48.5','-48.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-48','-48' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-47.5','-47.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-47','-47' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-46.5','-46.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-46','-46' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-45.5','-45.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-45','-45' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-44.5','-44.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-44','-44' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-43.5','-43.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-43','-43' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-42.5','-42.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-42','-42' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-41.5','-41.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-41','-41' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-40.5','-40.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-40','-40' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-39.5','-39.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-39','-39' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-38.5','-38.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-38','-38' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-37.5','-37.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-37','-37' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-36.5','-36.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-36','-36' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-35.5','-35.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-35','-35' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-34.5','-34.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-34','-34' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-33.5','-33.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-33','-33' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-32.5','-32.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-32','-32' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-31.5','-31.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-31','-31' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-30.5','-30.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-30','-30' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-29.5','-29.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-29','-29' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-28.5','-28.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-28','-28' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-27.5','-27.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-27','-27' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-26.5','-26.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-26','-26' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-25.5','-25.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-25','-25' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-24.5','-24.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-24','-24' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-23.5','-23.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-23','-23' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-22.5','-22.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-22','-22' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-21.5','-21.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-21','-21' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-20.5','-20.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-20','-20' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-19.5','-19.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-19','-19' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-18.5','-18.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-18','-18' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-17.5','-17.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-17','-17' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-16.5','-16.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-16','-16' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-15.5','-15.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-15','-15' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-14.5','-14.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-14','-14' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-13.5','-13.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-13','-13' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-12.5','-12.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-12','-12' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-11.5','-11.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-11','-11' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-10.5','-10.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-10','-10' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-9.5','-09.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-9','-09' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-8.5','-08.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-8','-08' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-7.5','-07.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-7','-07' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-6.5','-06.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-6','-06' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-5.5','-05.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-5','-05' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-4.5','-04.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-4','-04' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-3.5','-03.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-3','-03' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-2.5','-02.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-2','-02' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-1.5','-01.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-1','-01' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '-0.5','-00.5' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '0','00' FROM css_networking_device_prop_def
WHERE variable_name in('poiAttenuationCurrentValue') AND device_type_id IN ('1604');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2',' ' FROM css_networking_device_prop_def
WHERE variable_name in('ruReboot') AND device_type_id IN (1551);

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','Reboot' FROM css_networking_device_prop_def
WHERE variable_name in('ruReboot') AND device_type_id IN (1551);

-- END

-- START
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','02' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3','03' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '4','04' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '5','05' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '6','06' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '7','07' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '8','08' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '9','09' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '10','10' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '11','11' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '12','12' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '13','13' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '14','14' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '15','15' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '16','16' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '17','17' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '18','18' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '19','19' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '20','20' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '21','21' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '22','22' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '23','23' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '24','24' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '25','25' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '26','26' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '27','27' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '28','28' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '29','29' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '30','30' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '31','31' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '32','32' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '33','33' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdLow') AND device_type_id IN ('1602');


REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '15','15' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '16','16' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '17','17' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '18','18' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '19','19' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '20','20' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '21','21' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '22','22' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '23','23' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '24','24' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '25','25' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '26','26' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '27','27' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '28','28' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '29','29' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '30','30' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '31','31' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '32','32' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '33','33' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '34','34' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '35','35' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '36','36' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '37','37' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '38','38' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '39','39' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '40','40' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmThresholdHigh') AND device_type_id IN ('1602');


REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','01' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmLatency','poiModuleServiceMapAlarmLatency','ruVswrAlarmLatency') AND device_type_id IN ('1602','1603','1551');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','02' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmLatency','poiModuleServiceMapAlarmLatency','ruVswrAlarmLatency') AND device_type_id IN ('1602','1603','1551');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3','03' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmLatency','poiModuleServiceMapAlarmLatency','ruVswrAlarmLatency') AND device_type_id IN ('1602','1603','1551');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '4','04' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmLatency','poiModuleServiceMapAlarmLatency','ruVswrAlarmLatency') AND device_type_id IN ('1602','1603','1551');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '5','05' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmLatency','poiModuleServiceMapAlarmLatency','ruVswrAlarmLatency') AND device_type_id IN ('1602','1603','1551');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '10','10' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmLatency','poiModuleServiceMapAlarmLatency','ruVswrAlarmLatency') AND device_type_id IN ('1602','1603','1551');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '20','20' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmLatency','poiModuleServiceMapAlarmLatency','ruVswrAlarmLatency') AND device_type_id IN ('1602','1603','1551');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','02' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmLatency','poiModuleServiceMapAlarmLatency','ruVswrAlarmLatency') AND device_type_id IN ('1602','1603','1551');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '30','30' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmLatency','poiModuleServiceMapAlarmLatency','ruVswrAlarmLatency') AND device_type_id IN ('1602','1603','1551');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '40','40' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmLatency','poiModuleServiceMapAlarmLatency','ruVswrAlarmLatency') AND device_type_id IN ('1602','1603','1551');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '50','50' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmLatency','poiModuleServiceMapAlarmLatency','ruVswrAlarmLatency') AND device_type_id IN ('1602','1603','1551');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '60','60' FROM css_networking_device_prop_def
WHERE variable_name in('ruServiceAlarmLatency','poiModuleServiceMapAlarmLatency','ruVswrAlarmLatency') AND device_type_id IN ('1602','1603','1551');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '33','33' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlcThreshold') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '34','34' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlcThreshold') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '35','35' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlcThreshold') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '36','36' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlcThreshold') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '37','37' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlcThreshold') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '38','38' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlcThreshold') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '39','39' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlcThreshold') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '40','40' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlcThreshold') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '41','41' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlcThreshold') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '42','42' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlcThreshold') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '43','43' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlcThreshold') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '44','44' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlcThreshold') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '45','45' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdAlc','poiModuleServiceMapAlcThreshold') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '33','33' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdHigh') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '34','34' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdHigh') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '35','35' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdHigh') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '36','36' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdHigh') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '37','37' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdHigh') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '38','38' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdHigh') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '39','39' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdHigh') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '40','40' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdHigh') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '11','11' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '12','12' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '13','13' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '14','14' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '15','15' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '16','16' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '17','17' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '18','18' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '19','19' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '20','20' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '21','21' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '22','22' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '23','23' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '24','24' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '25','25' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '26','26' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '27','27' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '28','28' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '29','29' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '30','30' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '31','31' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '32','32' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '33','33' FROM css_networking_device_prop_def
WHERE variable_name in('poiModuleServiceMapAlarmThresholdLow') AND device_type_id IN ('1603');

-- END

-- START
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiModuleServiceMapCTA','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiModuleServiceMapDlPort','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiModuleServiceMapUlPort','1603','RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('poiModuleServiceMapInputPower','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiModuleServiceMapAlcThreshold','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiModuleServiceMapSectorGroupInstanceId','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiModuleServiceMapAlarmThresholdLow','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiModuleServiceMapAlarmThresholdLowActive','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiModuleServiceMapAlarmThresholdHigh','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiModuleServiceMapAlarmThresholdHighActive','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiModuleServiceMapAlarmThresholdAlc','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiModuleServiceMapAlarmThresholdAlcActive','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiModuleServiceMapAlarmLatency','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorGroupInstanceId','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorGroupInstanceSectorId','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorGroupInstanceName','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorGroupInstanceUserDefinedId','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorGroupInstanceSectorGroupId','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileId','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileOperator','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileTechnology','1603','RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfilePilotRatio','1603','RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileNumChannels','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileBandId','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileBandwidthValue','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileBandwidthUnit','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileSectorGroupId','1603','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileCarrierFrequency','1603','RF Parameters');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruServiceCTA','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruServiceBandId','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruServiceSectorProfileId','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruServiceMimoPath','1602','RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ruServiceOutputPower','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruServiceAlarmThresholdLow','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruServiceAlarmThresholdLowActive','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruServiceAlarmThresholdHigh','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruServiceAlarmThresholdHighActive','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ruServiceAlarmLatency','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileId','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileOperator','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileTechnology','1602','RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfilePilotRatio','1602','RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileNumChannels','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileBandId','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileBandwidthValue','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileBandwidthUnit','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileSectorGroupId','1602','RF Parameters');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ionuSectorProfileCarrierFrequency','1602','RF Parameters');

-- END

-- START
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2048','poiUlPowerPortLabel','poiUlPowerPortLabel','UL Power Port','STRING','0','1','UL Power Port');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip) VALUES ('2048','poiUlPower','poiUlPower','UL Power','INTEGER','0','2','UL Power');

REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('poiUlPowerPortLabel','2048','RF Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('poiUlPower','2048','RF Parameters');
-- END

-- START 
UPDATE css_networking_device_type SET defaultSNMPWrite='PUBLIC' WHERE id=1548;
-- END

-- START
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0','00' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0.25','00.25' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0.5','00.5' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '0.75','00.75' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1','01' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1.25','01.25' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1.5','01.5' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '1.75','01.75' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2','02' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2.25','02.25' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2.5','02.5' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '2.75','02.75' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3','03' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3.25','03.25' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3.5','03.5' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '3.75','03.75' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '4','04' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '4.25','04.25' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '4.5','04.5' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '4.75','04.75' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '5','05' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '5.25','05.25' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '5.5','05.5' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '5.75','05.75' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '6','06' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '6.25','06.25' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '6.5','06.5' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '6.75','06.75' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '7','07' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '7.25','07.25' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '7.5','07.5' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '7.75','07.75' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '8','08' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '8.25','08.25' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '8.5','08.5' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '8.75','08.75' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '9','09' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '9.25','09.25' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '9.5','09.5' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '9.75','09.75' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '10','10' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '10.25','10.25' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '10.5','10.5' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '10.75','10.75' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '11','11' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '11.25','11.25' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '11.5','11.5' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '11.75','11.75' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
SELECT id, '12','12' FROM css_networking_device_prop_def
WHERE variable_name in('ruBandDlAttenuator') AND device_type_id IN ('1601');

-- END

-- START add the prop scanner file
UPDATE css_networking_device_type SET prop_scan_file='andrew_prop_scanner_launcher.php' WHERE id=1548;
-- END
RAWSQL
            );
            // R7.3 - B5771
            DB::unprepared(<<<RAWSQL
REPLACE INTO css_general_config (setting_name, var1, description) VALUES ('max_parallel_IP_scans', '2', '-1 = Allows infinite parallel scans per IP address, 1 = Allows 1 parallel scan per IP address, and so on across the set of integers');

REPLACE INTO css_general_config (setting_name, var1, description) VALUES ('enable_parallel_IP_scanning', '1', '1 = Enables the simultaneous scanning of multiple nodes sharing a single IP address, with a limit set by the max_parallel_IP_scans setting.');


RAWSQL
            );
            // R7.3 - B5779
            DB::unprepared(<<<RAWSQL
UPDATE css_authentication_user_pref SET value = 'PolledAlarms,Traps,Critical,Major,Minor,Warning,Information,Ignored,Delayed,Chronic,SLA,AllAlarmType,AllAlarmLevel,AllMisc'
WHERE variable_name = 'alarmGridFilter';


RAWSQL
            );
            // R7.3 - B5925
            DB::unprepared(<<<RAWSQL
UPDATE css_networking_report_types SET report_type = 'Energy Report' WHERE  id = 40;
-- 12282015
RAWSQL
            );
            // R7.3 - B6011
            DB::unprepared(<<<RAWSQL
-- delete possible redundant fuel related property - the dupe prevents all fuel properties from being recorded on scan
delete from css_networking_device_prop_def where device_type_id = 1242 and name = 'ne_template_name';

RAWSQL
            );
            // R7.3 - B6055
            DB::unprepared(<<<RAWSQL
-- Start change the builder and scanner files associated with the Cisco ASA devices Wayne 10272015
UPDATE css_networking_device_type SET build_file='Cisco_ASA_Builder_Launcher.php', scan_file='trap_receiver_launcher.php', prop_scan_file=NULL WHERE id='1466';
-- End  change the builder and scanner files associated with the Cisco ASA devices Wayne 10272015
RAWSQL
            );
            // R7.3 - B6055b
            DB::unprepared(<<<RAWSQL
-- START Updating build file and scan file for device type 1466 - Wayne 11182015
UPDATE css_networking_device_type 
SET build_file = 'cisco_ASA_builder.php', scan_file = 'cisco_dummy_alarm_scanner_launcher.php', prop_scan_file = 'cisco_ASA_scanner_launcher.php' 
WHERE id = 1466;
-- END Updating build file and scan file for device type 1466 - Wayne 11182015

-- START Insert a new device type for Cisco ASA 5500 series trap handler - Wayne 11182015
REPLACE INTO css_networking_device_type set id = 2325, class_id = 1129, vendor = 'Cisco', model = 'ASA 5500 Series Trap Handler', 
auto_build_enabled = 1, uses_snmp = 1, snmp_only = 1, can_add_children = 0, can_disable = 0, defaultSNMPVer = '2c', defaultSNMPRead = 'public', 
defaultSNMPWrite = 'public', main_device = 1, general_device_id = 0, uses_default_value = 1, build_file = 'Cisco_ASA_Trap_Handler_Builder_Launcher.php', 
scan_file = 'trap_receiver_launcher.php', SNMPauthEncryption = '', SNMPprivEncryption = '', SNMPauthType = '', support_traps = 1, has_web_interface = 0;
-- END Insert a new device type for Cisco ASA 5500 series trap handler - Wayne 11182015

-- START Insert a port def for Cisco ASA 5500 series trap handler - Wayne 11182015
REPLACE INTO css_networking_device_port_def 
set device_type_id = '2325', variable_name = 'snmp', name = 'SNMP', default_port = 161, date_updated = '2015-11-18 11:21:00';
-- END Insert a port def for Cisco ASA 5500 series trap handler - Wayne 11182015
RAWSQL
            );
            // R7.3 - B6146
            DB::unprepared(<<<RAWSQL
-- Clean up css_networking_device_prop_group and modify the table to disallow further duplicate entries.

delete pg.* from css_networking_device_prop_group pg
inner join (select * from (select MIN(id) as id, name, count(*) as count from css_networking_device_prop_group group by name) tmp where tmp.count > 1) dups
on dups.`name` = pg.`name` and pg.id <> dups.id;

ALTER TABLE css_networking_device_prop_group DROP INDEX css_networking_device_prop_group_name;
ALTER TABLE css_networking_device_prop_group ADD UNIQUE css_networking_device_prop_group_name (name);
RAWSQL
            );
            // R7.3 - B6153
            DB::unprepared(<<<RAWSQL
-- Copies the device_type, prop_def and port_def definitions for SiteGate to a new device type.
-- This is to enable the use of a new builder and avoid the hardcoded SiteGate logic in the UI which is triggered by type_id = 5000.
-- The new id created is of no importance.
-- The new definition is only used to select an "auto-builder". The device added by the builder will still have type_id = 5000

-- copy the device_type while hardcoding a few columns

SET @newId = 5013;

INSERT INTO css_networking_device_type
(id,
class_id,
vendor,
model,
auto_build_enabled,
uses_snmp,
snmp_only,
can_add_children,
can_disable,
defaultWebUi,
defaultWebUiUser,
defaultWebUiPw,
defaultSNMPVer,
defaultSNMPRead,
defaultSNMPWrite,
date_updated,
main_device,
general_device_id,
node_type,
uses_default_value,
build_file,
scan_file,
prop_scan_file,
controller_file,
SNMPuserName,
SNMPauthPassword,
SNMPauthEncryption,
SNMPprivPassword,
SNMPprivEncryption,
SNMPauthType,
rebuilder_file,
support_traps,
has_web_interface,
canvas_pref_top,
canvas_pref_bottom,
canvas_default_top,
canvas_default_bottom,
canvas_list,
development_flag,
auto_detect_flag,
heartbeat_threshold_enabled)
SELECT
@newId,
class_id,
vendor,
model,
1,
uses_snmp,
snmp_only,
can_add_children,
can_disable,
defaultWebUi,
'G8Keeper',
'65b5bdb2322772a76c92d6fc1ec26e8b',
defaultSNMPVer,
defaultSNMPRead,
defaultSNMPWrite,
date_updated,
main_device,
general_device_id,
node_type,
uses_default_value,
'SiteGateBuilderLauncher.php',
scan_file,
prop_scan_file,
controller_file,
SNMPuserName,
SNMPauthPassword,
SNMPauthEncryption,
SNMPprivPassword,
SNMPprivEncryption,
SNMPauthType,
rebuilder_file,
support_traps,
has_web_interface,
canvas_pref_top,
canvas_pref_bottom,
canvas_default_top,
canvas_default_bottom,
canvas_list,
development_flag,
auto_detect_flag,
heartbeat_threshold_enabled
FROM css_networking_device_type
WHERE id = 5000;

-- copy the prop_defs
INSERT INTO css_networking_device_prop_def
(prop_type_id,
device_class_id,
device_type_id,
prop_group_id,
use_snmp,
snmp_oid,
variable_name,
name,
data_type,
editable,
visible,
internal,
min,
min_val,
max,
max_val,
severity_id,
severity_id_two,
secure,
date_updated,
graph_type,
thresh_enable,
tooltip,
valuetip,
alarm_exempt)
SELECT
prop_type_id,
device_class_id,
@newId,
prop_group_id,
use_snmp,
snmp_oid,
variable_name,
name,
data_type,
editable,
visible,
internal,
min,
min_val,
max,
max_val,
severity_id,
severity_id_two,
secure,
date_updated,
graph_type,
thresh_enable,
tooltip,
valuetip,
alarm_exempt
FROM css_networking_device_prop_def
WHERE device_type_id = 5000;

-- insert port_def. https is the only one that matters
INSERT INTO css_networking_device_port_def
(device_type_id, variable_name, name, default_port)
VALUES (@newId, 'https', 'HTTPS', 443);

-- remove the old definition from the dropdown list
UPDATE css_networking_device_type
SET auto_build_enabled = 0
WHERE id = 5000;

RAWSQL
            );
            // R7.3 - B6190
            DB::unprepared(<<<RAWSQL
REPLACE INTO def_status_groups (group_var_name, group_breadCrumb) VALUES('Temperature','Temperature');

UPDATE def_status_groups_map SET group_var_name = 'Temperature' WHERE group_var_name LIKE '%Temperature\\\%' AND device_type_id = '1268';

REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Temperature','Temperature');

UPDATE def_prop_groups_map SET group_var_name = 'Temperature' WHERE group_var_name LIKE '%Temperature\\\%' AND device_type_id = '1268';

RAWSQL
            );
            // R7.3 - B6520
            DB::unprepared(<<<RAWSQL
-- ----------------------------------------------------------------------------------------------------------------------------------------------
-- Types 2034 and 5022 are missing scan file extensions for device types, Bug 6520
-- ----------------------------------------------------------------------------------------------------------------------------------------------

UPDATE css_networking_device_type SET scan_file='trap_receiver_launcher.php' WHERE (id='2034');  -- Generic	Traps Receiver
UPDATE css_networking_device_type SET scan_file='trap_receiver_launcher.php' WHERE (id='5022');  -- Solid	DMS Rel6 Trap Receiver

-- ----------------------------------------------------------------------------------------------------------------------------------------------
-- End
-- ----------------------------------------------------------------------------------------------------------------------------------------------

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
