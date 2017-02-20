<?php

// brings cswapi database from 7.2.2.0.2 to 7.2.3
// (step 1 of SiteGate 2.5 to SiteGate 2.6)

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportSiteportal723Changes extends Migration
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
            // R7.2.3 - B4492
            DB::unprepared(<<<RAWSQL
DROP TABLE IF EXISTS data_generator_id_type_ref;
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
CREATE TABLE data_generator_id_type_ref (
  device_id   INT(10) UNSIGNED UNIQUE,
  gen_type_id INT(5) UNSIGNED,
  created     TIMESTAMP       NOT NULL,
  updated     TIMESTAMP       NOT NULL,
  user_id     INT(6) UNSIGNED NOT NULL DEFAULT 1,
  INDEX device_gen_type_id (device_id, gen_type_id),
  FOREIGN KEY (device_id)
  REFERENCES css_networking_device (id)
)
  ENGINE = INNODB;
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
DROP TABLE IF EXISTS def_generator_type;
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
CREATE TABLE def_generator_type (
  id            INTEGER(5) AUTO_INCREMENT UNIQUE,
  vendor        VARCHAR(50)             NOT NULL,
  model         VARCHAR(50)             NOT NULL,
  wattage       INTEGER(6) UNSIGNED     NOT NULL,
  horsepower    INTEGER(6) UNSIGNED     NOT NULL,
  heating_value DECIMAL(10, 5) UNSIGNED NOT NULL,
  fuel_type_id  INTEGER(2)              NOT NULL,
  fuel_usage    DECIMAL(10, 4) UNSIGNED NOT NULL,
  nox_per_hour  DECIMAL(10, 4) UNSIGNED,
  co_per_hour   DECIMAL(10, 4) UNSIGNED,
  sox_per_hour  DECIMAL(10, 4) UNSIGNED,
  pm_per_hour   DECIMAL(10, 4) UNSIGNED,
  voc_per_hour  DECIMAL(10, 4) UNSIGNED,
  hc_per_hour   DECIMAL(10, 4) UNSIGNED,
  PRIMARY KEY (id)
)
  ENGINE = INNODB;
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
DROP PROCEDURE IF EXISTS update_gen_fuel_types;
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
  CREATE PROCEDURE update_gen_fuel_types()
  
  BEGIN
  IF NOT EXISTS ( (  select *  from information_schema.columns where table_schema = DATABASE() 
  and table_name = 'def_generator_fuel_types'  and column_name = 'measurement_unit_abr') )
 then
    ALTER TABLE def_generator_fuel_types ADD COLUMN measurement_unit_abr VARCHAR(10) NOT NULL;
  END IF;

 IF NOT EXISTS ( (  select *  from information_schema.columns where table_schema = DATABASE() 
  and table_name = 'def_generator_fuel_types'  and column_name = 'measurement_unit_name') )
 then
    ALTER TABLE def_generator_fuel_types ADD COLUMN measurement_unit_name VARCHAR(50) NOT NULL;
  END IF;
   IF NOT EXISTS ( (  select *  from information_schema.columns where table_schema = DATABASE() 
  and table_name = 'def_generator_fuel_types'  and column_name = 'consumption_unit_abr') )
 then
    ALTER TABLE def_generator_fuel_types ADD COLUMN consumption_unit_abr VARCHAR(10) NOT NULL;
  END IF;
   IF NOT EXISTS ( (  select *  from information_schema.columns where table_schema = DATABASE() 
  and table_name = 'def_generator_fuel_types'  and column_name = 'consumption_unit_name') )
 then
    ALTER TABLE def_generator_fuel_types ADD COLUMN consumption_unit_name VARCHAR(50) NOT NULL;
  END IF;
END
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
CALL update_gen_fuel_types();
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
DROP PROCEDURE IF EXISTS update_gen_fuel_types;
RAWSQL
            );
            DB::unprepared(<<<RAWSQL
UPDATE def_generator_fuel_types
SET
  measurement_unit_abr  = 'lbs',
  measurement_unit_name = 'Pounds',
  consumption_unit_abr  = 'lph',
  consumption_unit_name = 'Pounds per Hour'
WHERE fuel_type LIKE "Propane";

UPDATE def_generator_fuel_types
SET
  measurement_unit_abr  = 'g',
  measurement_unit_name = 'Gallons',
  consumption_unit_abr  = 'gph',
  consumption_unit_name = 'Gallons per Hour'
WHERE fuel_type LIKE "Diesel";

UPDATE def_generator_fuel_types
SET
  measurement_unit_abr  = 'ccf',
  measurement_unit_name = 'Centum(100) Cubic Feet',
  consumption_unit_abr  = 'CCF/h',
  consumption_unit_name = 'Centum Cubic Feet per Hour'
WHERE fuel_type LIKE "Natural Gas";

ALTER TABLE def_generator_type
ADD UNIQUE INDEX gen_vendor_model (vendor ASC, model ASC);

INSERT INTO def_generator_type (
  vendor,
  model,
  wattage,
  horsepower,
  heating_value,
  fuel_type_id,
  fuel_usage,
  nox_per_hour,
  co_per_hour,
  sox_per_hour,
  pm_per_hour,
  voc_per_hour,
  hc_per_hour)
VALUES ("Generac - Standard 60KW Diesel", "0048122", 60000, 0, 0.602, 3, 5.2, 5.23, 0, 0, 0, 0, 0),
  ("Kohler - Standard 40KW Diesel", "40ROZJQS", 41000, 0, 0.602, 3, 3.4, 4.3, 0, 0, 0.12, 0, 0),
  ("Kohler - Standard 60KW Diesel", "60RE0ZJB", 60000, 0, 0.602, 3, 5.0, 5.23, 0, 0, 0, 0, 0),
  ("MTU Onsite Energy - Standard 30KW Propane", "30GC6NLT1", 30000, 0, 0.602, 1, 6.1, 6.09, 23.88, 0, 0, 0, 0),
  ("MTU Onsite Energy - Standard 30KW Diesel", "30JC6DT3", 30000, 0, 0.602, 3, 2.8, 4.9, 0, 0, 0.12, 0, 0),
  ("MTU Onsite Energy - Standard 30KW Nat Gas", "4R00765 GS30", 30000, 0, 0.602, 2, 13.9, 5.24, 16.38, 0, 0, 0, 0),
  ("MTU Onsite Energy - Standard 40KW Propane", "VER30DG6LT1", 40000, 0, 0.602, 1, 6.1, 6.09, 23.88, 0, 0, 0, 0),
  ("MTU Onsite Energy - Standard 50KW Diesel", "50DJ6DT3", 50000, 0, 0.602, 3, 4.6, 3.1, 0.8, 0, 0.18, 0, 0.22),
  ("MTU Onsite Energy - Standard 50KW Propane", "50GC6NLT1", 50000, 0, 0.602, 1, 7.5, 6.47, 29.59, 0, 0, 0, 0),
  ("MTU Onsite Energy - Standard 60KW Diesel", "MTU4R0113DS60", 60000, 0, 0.602, 3, 5.0, 3.48, 0, 0, 0, 0, 0),
  ("Katolight 60KW - Standard 60KW Diesel", "D60FJJ4", 60000, 0, 0.602, 3, 5.0, 5.23, 0, 0, 0, 0, 0);
RAWSQL
            );

            // R7.2.3 - B6275
            DB::unprepared(<<<RAWSQL
-- START
REPLACE INTO css_networking_device_type(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,defaultWebUiUser,defaultWebUiPw, main_device,build_file,scan_file,prop_scan_file,controller_file)VALUES(1605,1137,"Tempest","AIRSYS",0,1,1,1,"admin","Asentria1!",1,"airsys_builder_launcher.php","airsys_alarm_scanner_launcher.php","airsys_prop_scanner_launcher.php","airsys_controller.php");
-- END
 
-- START
REPLACE INTO css_networking_device_type(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children,defaultWebUiUser,defaultWebUiPw, main_device,build_file,scan_file,prop_scan_file,controller_file)VALUES(1606,1137,"Tempest","AIRSYS",0,1,1,1,"aii","aii",1,"airsys_builder_launcher.php","airsys_alarm_scanner_launcher.php","airsys_prop_scanner_launcher.php","airsys_controller.php");
-- END
 
-- START
REPLACE INTO css_networking_device_type(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children, main_device,build_file,scan_file,prop_scan_file,controller_file)VALUES(1607,1137,"Tempest","AIRSYS (auto-detect)",1,1,1,1,1,"airsys_builder_launcher.php","airsys_alarm_scanner_launcher.php","airsys_prop_scanner_launcher.php","airsys_controller.php");
-- END
 
 
-- START
INSERT IGNORE INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1605,'https','HTTPS',443);
INSERT IGNORE  INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1605,'http','HTTP',80);
INSERT IGNORE  INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1605,'snmp','SNMP',161);
INSERT IGNORE  INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1605,'ssh','SSH',22);
INSERT IGNORE  INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1606,'https','HTTPS',443);
INSERT IGNORE  INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1606,'http','HTTP',80);
INSERT IGNORE  INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1606,'snmp','SNMP',161);
INSERT IGNORE  INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1606,'ssh','SSH',22);
INSERT IGNORE  INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1607,'https','HTTPS',443);
INSERT IGNORE  INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1607,'http','HTTP',80);
INSERT IGNORE  INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1607,'snmp','SNMP',161); 
INSERT IGNORE  INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1607,'ssh','SSH',22);
-- END
 
 
-- START
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.enable','HVAC Enabled','STRING','0','1','HVAC Enabled','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.control.type','Controller Type','STRING','0','1','Controller Type','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.control.port','Serial Port','STRING','0','1','Serial port to use for Airsys controller.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.temp.indoor','Indoor Temperature(F)','INTEGER','0','2','Displays real time Indoor Temperature.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.temp.indoorbackup','Indoor Backup Temperature(F)','INTEGER','0','2','Displays real time Indoor Temperature (should be close to Indoor Temp reading).','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.humid.indoor','Indoor Humidity %','INTEGER','0','2','Displays real time indoor relative humidity in %.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.temp.outdoor','Outdoor Temperature(F)','INTEGER','0','2','Displays real time outdoor temperature.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.heater[1]','Heater Unit 1','INTEGER','0','2','On/Off','1','0'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.heater[2]','Heater Unit 2','INTEGER','0','2','On/Off','1','0'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.compressor[1]','Compressor Unit 1','INTEGER','0','2','On/Off','1','0'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.compressor[2]','Compressor Unit 2','INTEGER','0','2','On/Off','1','0'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.locked[1]','Lockout Status Unit 1','STRING','0','2','Lockout: Mechanical Cooling is locked out due to low/high pressure alarm or AC power loss. Lockout due to AC power loss will recover once AC power has been restored.','1','0'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.locked[2]','Lockout Status Unit 2','STRING','0','2','Lockout: Mechanical Cooling is locked out due to low/high pressure alarm or AC power loss. Lockout due to AC power loss will recover once AC power has been restored.','1','0'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.freeopening[1]','Free Cooling Airflow Unit 1','STRING','0','2','Displays damper opening in percentage of maximum.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.freeopening[2]','Free Cooling Airflow Unit 2','STRING','0','2','Displays damper opening in percentage of maximum.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.fanspeed[1]','Fan Speed Unit 1 %','INTEGER','0','2','Displays supply Fan Speed in percentage of maximum.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.fanspeed[2]','Fan Speed Unit 2 %','INTEGER','0','2','Displays supply Fan Speed in percentage of maximum.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.temp.supply[1]','Supply Temperature Unit 1(F)','STRING','0','2','Display real time supply air temperature.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.temp.supply[2]','Supply Temperature Unit 2(F)','STRING','0','2','Display real time supply air temperature.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.status[1]','Running Status Unit 1 (Seconds)','STRING','0','2','Running Status','1','0'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.status[2]','Running Status Unit 2 (Seconds)','STRING','0','2','Running Status','1','0'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysRemoteEnable.0','event.hvac.airsys.remoteenable','Enable Remote Control','STRING','1','1','Enable Remote Control','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.remotecontrol','Remote Power','STRING','1','1','Does not override off by keyboard.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysTempBaseThresh.0','event.hvac.airsys.temp.basethreshold','Cooling Setpoint (F)','STRING','1','1','Base Threshold','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysTempCoolDeadband.0','event.hvac.airsys.temp.cooldeadband','Cool Deadband (F)','STRING','1','1','Cut-off value before stopping cooling','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysTempHighValue.0','event.hvac.airsys.temp.highvalue','High Temperature Alarm Value (F)','STRING','1','1','Base threshold plus this value.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysHumidMaxFree.0','event.hvac.airsys.humid.maxfree','Humidity Threshold %','INTEGER','1','1','Max humidity allowed for free cooling to be viable.','1','1');  
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysRotationRuntime.0','event.hvac.airsys.runtime.rotation','Lead/Lag Rotation Period(hours)','INTEGER','1','1','Lead/Lag Rotation Period(hours)','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysFreeMode.0','event.hvac.airsys.freemode','Allow Free Cooling','STRING','1','1','Enable /disable Free Cooling','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysGeneratorLogic.0','event.hvac.airsys.logic.generator','Generator Logic','STRING','1','1','Generator Logic ON/OFF','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysTempHeatDeadband.0','event.hvac.airsys.temp.heatdeadband','Heat Deadband (F)','STRING','1','1','Cut-off value before stopping heating.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysTempLowValue.0','event.hvac.airsys.temp.lowvalue','Low Temperature Alarm Value (F)','STRING','1','1','Base threshold minus this value.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysHumidDeadband.0','event.hvac.airsys.humid.deadband','Humidity Deadband %','INTEGER','1','1','Cut-off value before allowing free cooling.','1','1');      
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysSmokeFireEnable.0','event.hvac.airsys.alarm.smokefire.enable','Smoke/Fire Alarm','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysPowerEnable.0','event.hvac.airsys.alarm.power.enable','AC Loss Alarm','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysLowRoomTempEnable.0','event.hvac.airsys.alarm.lowroomtemp.enable','Low Temperature Alarm','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysHighRoomTempEnable.0','event.hvac.airsys.alarm.highroomtemp.enable','High Temperature Alarm','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysUInProbeEnable.1','event.hvac.airsys.alarm.indoorprobe[1].enable','Indoor Temp Sensor Alarm Unit 1','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysUInProbeEnable.2','event.hvac.airsys.alarm.indoorprobe[2].enable','Indoor Temp Sensor Alarm Unit 2','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysOutProbeEnable.0','event.hvac.airsys.alarm.outdoorprobe.enable','Outdoor Temp Sensor Alarm','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysHumidProbeEnable.0','event.hvac.airsys.alarm.humidprobe.enable','Humidity Senor Alarm','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysBothCompressEnable.0','event.hvac.airsys.alarm.bothcompress.enable','Both Compressors Alarm','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysGeneratorEnable.0','event.hvac.airsys.alarm.generator.enable','Generator Mode Alarm','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysConnectionEnable.0','event.hvac.airsys.alarm.connection.enable','Connection Alarm','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysClockEnable.0','event.hvac.airsys.alarm.clock.enable','Clockcard Alarm','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsyspLANEnable.0','event.hvac.airsys.alarm.plan.enable','pLAN Alarm','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysUSupProbeEnable.1','event.hvac.airsys.alarm.supplyprobe[1].enable','Supply Air Temperature Sensor Alarm Unit 1','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysUSupProbeEnable.2','event.hvac.airsys.alarm.supplyprobe[2].enable','Supply Air Temperature Sensor Alarm Unit 2','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysUDamperEnable.1','event.hvac.airsys.alarm.damper[1].enable','Damper Alarm Unit 1','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysUDamperEnable.2','event.hvac.airsys.alarm.damper[2].enable','Damper Alarm Unit 2','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysULowPEnable.1','event.hvac.airsys.alarm.lowpressure[1].enable','Low Pressure Alarm Unit 1','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysULowPEnable.2','event.hvac.airsys.alarm.lowpressure[2].enable','Low Pressure Alarm Unit 2','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysUHighPEnable.1','event.hvac.airsys.alarm.highpressure[1].enable','High Pressure Alarm Unit 1','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysUHighPEnable.2','event.hvac.airsys.alarm.highpressure[2].enable','High Pressure Alarm Unit 2','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysUDirtyEnable.1','event.hvac.airsys.alarm.dirtyfilter[1].enable','Dirty Air Filter Alarm Unit 1','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysUDirtyEnable.2','event.hvac.airsys.alarm.dirtyfilter[2].enable','Dirty Air Filter Alarm Unit 2','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysUEvapFanEnable.1','event.hvac.airsys.alarm.evapfan[1].enable','Fan Overload /AC Loss Alarm Unit 1','STRING','1','1','Enable/Disable this alarm.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysUEvapFanEnable.2','event.hvac.airsys.alarm.evapfan[2].enable','Fan Overload /AC Loss Alarm Unit 2','STRING','1','1','Enable/Disable this alarm.','1','1');       
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.smokefire.actions','Smoke/Fire alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.power.actions','Power Failure alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowroomtemp.actions','Low Temperature alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highroomtemp.actions','High Temperature alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.indoorprobe[1].actions','Indoor Probe alarm actions Unit 1','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.indoorprobe[2].actions','Indoor Probe alarm actions Unit 2','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','','Indoor Backup Probe alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.outdoorprobe.actions','Outdoor Probe alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.humidprobe.actions','Humidity Probe alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.bothcompress.actions','Both Compressors alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.generator.actions','Generator alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.clock.actions','Clock Card alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.plan.actions','pLAN alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[1].actions','Supply Probe Unit 1 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[2].actions','Supply Probe Unit 2 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[1].actions','Free Cooling Damper Unit 1 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[2].actions','Free Cooling Damper Unit 2 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[1].actions','Low Pressure Unit 1 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[2].actions','Low Pressure Unit 2 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[1].actions','High Pressure Unit 1 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[2].actions','High Pressure Unit 2 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[1].actions','Dirty Filter Unit 1 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[2].actions','Dirty Filter Unit 2 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[1].actions','Evaporator Fan Unit 1 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[2].actions','Evaporator Fan Unit 2 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[1].actions','Supply Probe Unit 1 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[2].actions','Supply Probe Unit 2 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[1].actions','Free Cooling Damper Unit 1 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[2].actions','Free Cooling Damper Unit 2 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[1].actions','Low Pressure Unit 1 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[2].actions','Low Pressure Unit 2 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[1].actions','High Pressure Unit 1 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[2].actions','High Pressure Unit 2 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[1].actions','Dirty Filter Unit 1 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[2].actions','Dirty Filter Unit 2 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[1].actions','Evaporator Fan Unit 1 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[2].actions','Evaporator Fan Unit 2 alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.smokefire.class','Smoke/Fire Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.power.class','Power Failure Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowroomtemp.class','Low Temperature Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highroomtemp.class','High Temperature Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.indoorprobe[1].class','Indoor Probe Alarm CLass Unit 1','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.indoorprobe[2].class','Indoor Probe Alarm CLass Unit 2','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','','Indoor Backup Probe Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.outdoorprobe.class','Outdoor Probe Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.humidprobe.class','Humidity Probe Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.bothcompress.class','Both Compressors Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.generator.class','Generator Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.clock.class','Clock Card Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.plan.class','pLAN Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[1].class','Supply Probe Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[2].class','Supply Probe Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[1].class','Free Cooling Damper Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[2].class','Free Cooling Damper Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[1].class','Low Pressure Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[2].class','Low Pressure Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[1].class','High Pressure Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[2].class','High Pressure Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[1].class','Dirty Filter Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[2].class','Dirty Filter Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[1].class','Evaporator Fan Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[2].class','Evaporator Fan Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[1].class','Supply Probe Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[2].class','Supply Probe Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[1].class','Free Cooling Damper Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[2].class','Free Cooling Damper Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[1].class','Low Pressure Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[2].class','Low Pressure Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[1].class','High Pressure Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[2].class','High Pressure Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[1].class','Dirty Filter Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[2].class','Dirty Filter Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[1].class','Evaporator Fan Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[2].class','Evaporator Fan Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.smokefire.trapnum','Smoke/Fire alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.power.trapnum','Power Failure alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowroomtemp.trapnum','Low Temperature alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highroomtemp.trapnum','High Temperature alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.indoorprobe[1].trapnum','Indoor Probe alarm trap port Unit 1','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.indoorprobe[2].trapnum','Indoor Probe alarm trap port Unit 2','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','','Indoor Backup Probe alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.outdoorprobe.trapnum','Outdoor Probe alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.humidprobe.trapnum','Humidity Probe alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.bothcompress.trapnum','Both Compressors alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.generator.trapnum','Generator alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.clock.trapnum','Clock Card alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.plan.trapnum','pLAN alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[1].trapnum','Supply Probe Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[2].trapnum','Supply Probe Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[1].trapnum','Free Cooling Damper Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[2].trapnum','Free Cooling Damper Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[1].trapnum','Low Pressure Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[2].trapnum','Low Pressure Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[1].trapnum','High Pressure Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[2].trapnum','High Pressure Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[1].trapnum','Dirty Filter Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[2].trapnum','Dirty Filter Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[1].trapnum','Evaporator Fan Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[2].trapnum','Evaporator Fan Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[1].trapnum','Supply Probe Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[2].trapnum','Supply Probe Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[1].trapnum','Free Cooling Damper Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[2].trapnum','Free Cooling Damper Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[1].trapnum','Low Pressure Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[2].trapnum','Low Pressure Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[1].trapnum','High Pressure Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[2].trapnum','High Pressure Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[1].trapnum','Dirty Filter Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[2].trapnum','Dirty Filter Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[1].trapnum','Evaporator Fan Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[2].trapnum','Evaporator Fan Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1');   
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.smokefire.normalactions','Smoke/Fire alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.power.normalactions','Power Failure alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowroomtemp.normalactions','Low Temperature alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highroomtemp.normalactions','High Temperature alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.indoorprobe[1].normalactions','Indoor Probe alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.indoorprobe[2].normalactions','Indoor Probe alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','','Indoor Backup Probe alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.outdoorprobe.normalactions','Outdoor Probe alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.humidprobe.normalactions','Humidity Probe alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.bothcompress.normalactions','Both Compressors alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.generator.normalactions','Generator alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.clock.normalactions','Clock Card alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.plan.normalactions','pLAN alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[1].normalactions','Supply Probe Unit 1 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[2].normalactions','Supply Probe Unit 1 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[1].normalactions','Free Cooling Damper Unit 1 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[2].normalactions','Free Cooling Damper Unit 1 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[1].normalactions','Low Pressure Unit 1 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[2].normalactions','Low Pressure Unit 1 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[1].normalactions','High Pressure Unit 1 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[2].normalactions','High Pressure Unit 1 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[1].normalactions','Dirty Filter Unit 1 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[2].normalactions','Dirty Filter Unit 1 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[1].normalactions','Evaporator Fan Unit 1 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[2].normalactions','Evaporator Fan Unit 1 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[1].normalactions','Supply Probe Unit 2 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[2].normalactions','Supply Probe Unit 2 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[1].normalactions','Free Cooling Damper Unit 2 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[2].normalactions','Free Cooling Damper Unit 2 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[1].normalactions','Low Pressure Unit 2 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[2].normalactions','Low Pressure Unit 2 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[1].normalactions','High Pressure Unit 2 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[2].normalactions','High Pressure Unit 2 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[1].normalactions','Dirty Filter Unit 2 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[2].normalactions','Dirty Filter Unit 2 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[1].normalactions','Evaporator Fan Unit 2 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[2].normalactions','Evaporator Fan Unit 2 Alarm actions','STRING','1','1','Action fields accept one or more commands separated by a semicolon.  ASM Event: asme or asme(now|later) Cancel : cancel(idname) Check Alive : checkalive(CPE# or host, delay) Continue: continue(id) Dialup Pager : dpage(index) Dispatcher : dispatch(phone# or index) Email : email(email or index) Group : group(groupname) ID : id(id name) Inform : inform(ipaddress or index) Malert : malert(phone# or index) Modem : modem(phone# or index) Non-global : nonglobal Pause : pause(seconds) Postpone : postpone(idname, seconds) Power : power(action, eventsensor, point) Relay : relay(action, eventsensor, point) Script : script(action, name or number) SMS : sms(phone# or index) Stop if any/1 actions OK : okstop(any|1) Stop if any/2 actions OK : okstop(any|2) Syslog : syslog(ipaddress or index, optional facility,level) Talert : talert(ipaddress or index) Trap : trap(ipaddress or index)','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.smokefire.normalclass','Smoke/Fire Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.power.normalclass','Power Failure Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowroomtemp.normalclass','Low Temperature Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highroomtemp.normalclass','High Temperature Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.indoorprobe[1].normalclass','Indoor Probe Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.indoorprobe[2].normalclass','Indoor Probe Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','','Indoor Backup Probe Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.outdoorprobe.normalclass','Outdoor Probe Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.humidprobe.normalclass','Humidity Probe Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.bothcompress.normalclass','Both Compressors Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.generator.normalclass','Generator Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.clock.normalclass','Clock Card Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.plan.normalclass','pLAN Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[1].normalclass','Supply Probe Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[2].normalclass','Supply Probe Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[1].normalclass','Free Cooling Damper Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[2].normalclass','Free Cooling Damper Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[1].normalclass','Low Pressure Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[2].normalclass','Low Pressure Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[1].normalclass','High Pressure Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[2].normalclass','High Pressure Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[1].normalclass','Dirty Filter Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[2].normalclass','Dirty Filter Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[1].normalclass','Evaporator Fan Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[2].normalclass','Evaporator Fan Unit 1 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[1].normalclass','Supply Probe Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[2].normalclass','Supply Probe Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[1].normalclass','Free Cooling Damper Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[2].normalclass','Free Cooling Damper Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[1].normalclass','Low Pressure Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[2].normalclass','Low Pressure Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[1].normalclass','High Pressure Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[2].normalclass','High Pressure Unit 2 Alarm CLass','STRING','1','1','Event Severity level','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[1].normalclass','Dirty Filter Unit 2 Alarm CLass','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[2].normalclass','Dirty Filter Unit 2 Alarm CLass','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[1].normalclass','Evaporator Fan Unit 2 Alarm CLass','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[2].normalclass','Evaporator Fan Unit 2 Alarm CLass','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.smokefire.normaltrapnum','Smoke/Fire alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.power.normaltrapnum','Power Failure alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowroomtemp.normaltrapnum','Low Temperature alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highroomtemp.normaltrapnum','High Temperature alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.indoorprobe[1].normaltrapnum','Indoor Probe alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.indoorprobe[2].normaltrapnum','Indoor Probe alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','','Indoor Backup Probe alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.outdoorprobe.normaltrapnum','Outdoor Probe alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.humidprobe.normaltrapnum','Humidity Probe alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.bothcompress.normaltrapnum','Both Compressors alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.generator.normaltrapnum','Generator alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.clock.normaltrapnum','Clock Card alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.plan.normaltrapnum','pLAN alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[1].normaltrapnum','Supply Probe Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[2].normaltrapnum','Supply Probe Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[1].normaltrapnum','Free Cooling Damper Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[2].normaltrapnum','Free Cooling Damper Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[1].normaltrapnum','Low Pressure Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[2].normaltrapnum','Low Pressure Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[1].normaltrapnum','High Pressure Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[2].normaltrapnum','High Pressure Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[1].normaltrapnum','Dirty Filter Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[2].normaltrapnum','Dirty Filter Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[1].normaltrapnum','Evaporator Fan Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[2].normaltrapnum','Evaporator Fan Unit 1 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[1].normaltrapnum','Supply Probe Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.supplyprobe[2].normaltrapnum','Supply Probe Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[1].normaltrapnum','Free Cooling Damper Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.damper[2].normaltrapnum','Free Cooling Damper Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[1].normaltrapnum','Low Pressure Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.lowpressure[2].normaltrapnum','Low Pressure Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[1].normaltrapnum','High Pressure Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.highpressure[2].normaltrapnum','High Pressure Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[1].normaltrapnum','Dirty Filter Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[2].normaltrapnum','Dirty Filter Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[1].normaltrapnum','Evaporator Fan Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.evapfan[2].normaltrapnum','Evaporator Fan Unit 2 Alarm trap port','STRING','1','1','Trap definition designation in MIB','0','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.connection.event','Connected Status','STRING','0','1','Connected Status','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','net.eth[1].ip','IP Address','STRING','0','1','IP Address','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','net.eth[1].mask','Net Mask','STRING','0','1','Net Mask','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','net.eth[2].mask','Net Mask','STRING','0','1','Net Mask','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','net.eth[1].router','Gateway','STRING','0','1','Gateway','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','sys.product','Host Name','STRING','0','1','Host Name','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','sys.serial','Host Serial Number','STRING','0','1','Host Serial Number','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','sys.version','Host Version','STRING','0','1','Host Version','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','sys.mfgr','Host Manufacture','STRING','0','1','Host Manufacture','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','sys.build','Host Build','STRING','0','1','Host Build','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','sys.uptime','System Up Time','STRING','0','2','System Up Time','1','0'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','sys.uptimeseconds','System Up Time (Seconds)','INTEGER','0','2','System Up Time (Seconds)','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.runtime.supplyfan[1]','Supply fan Run Time Unit 1 (Seconds)','INTEGER','0','2','Supply fan Run Time','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.runtime.supplyfan[2]','Supply fan Run Time Unit 2 (Seconds)','INTEGER','0','2','Supply fan Run Time','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.runtime.compressor[1]','Compressor Run Time Unit 1 (Seconds)','INTEGER','0','2','Compressor Run Time','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.runtime.compressor[2]','Compressor Run Time Unit 2 (Seconds)','INTEGER','0','2','Compressor Run Time','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.runtime.heat[1]','Heater Run Time Unit 1 (Seconds)','INTEGER','0','2','Heater Run Time','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.runtime.heat[2]','Heater Run Time Unit 2 (Seconds)','INTEGER','0','2','Heater Run Time','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.runtime.freecooling[1]','Freecooling Run Time Unit 1 (Seconds)','INTEGER','0','2','Freecooling Run Time','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.runtime.freecooling[2]','Freecooling Run Time Unit 2 (Seconds)','INTEGER','0','2','Freecooling Run Time','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[1].event','Dirty Filter Unit 1 Alarm','STRING','0','2','Alarm status','1','0'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.alarm.dirtyfilter[2].event','Dirty Filter Unit 2 Alarm','STRING','0','2','Alarm status','1','0'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.supplyfan[1]','System Start Counter Unit 1','INTEGER','0','2','System Start Counter','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.supplyfan[2]','System Start Counter Unit 2','INTEGER','0','2','System Start Counter','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.compressor[1]','Compressor Start Counter Unit 1','INTEGER','0','2','Displays a count of the number of times the compressor has turned on.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.compressor[2]','Compressor Start Counter Unit 2','INTEGER','0','2','Displays a count of the number of times the compressor has turned on.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.heat[1]','Heater Start Counter Unit 1','INTEGER','0','2','Displays a count of the number of times the heater has turned on since the equipment was inst1ed.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.heat[1]','Heater Start Counter Unit 1','INTEGER','0','2','Displays a count of the number of times the heater has turned on since the equipment was inst2ed.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.heat[2]','Heater Start Counter Unit 2','INTEGER','0','2','Displays a count of the number of times the heater has turned on since the equipment was inst1ed.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.heat[2]','Heater Start Counter Unit 2','INTEGER','0','2','Displays a count of the number of times the heater has turned on since the equipment was inst2ed.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.freecooling[1]','Free Cooling Start Counter Unit 1','INTEGER','0','2','Displays a count of the number of times the unit has turned on Free Cooling to maintain site  temperature.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.freecooling[2]','Free Cooling Start Counter Unit 2','INTEGER','0','2','Displays a count of the number of times the unit has turned on Free Cooling to maintain site  temperature.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','highTempAlarmTrigger','High Temp Alarm Trigger (F)','INTEGER','0','1','High Temp Alarm Trigger','1','1');
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','lowTempAlarmTrigger','Low Temp Alarm Trigger (F)','INTEGER','0','1','Low Temp Alarm Trigger','1','1');
-- END
 
 
-- START
 
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Alarms','Alarms'); 
 REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Alarms','Alarms');  
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.smokefire.enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.power.enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.lowroomtemp.enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.highroomtemp.enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.indoorprobe[1].enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.indoorprobe[2].enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.outdoorprobe.enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.humidprobe.enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.bothcompress.enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.generator.enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.connection.enable','1605','Alarms');  
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.clock.enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.plan.enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.supplyprobe[1].enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.supplyprobe[2].enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.damper[1].enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.damper[2].enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.lowpressure[1].enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.lowpressure[2].enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.highpressure[1].enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.highpressure[2].enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.dirtyfilter[1].enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.dirtyfilter[2].enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.evapfan[1].enable','1605','Alarms'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.evapfan[2].enable','1605','Alarms');  
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.dirtyfilter[1].event','1605','Unit Status & Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.dirtyfilter[2].event','1605','Unit Status & Statistics'); 
-- END
 
 

 
-- START
 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)SELECT id, '0','NC' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.airsys.logic.generator") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)SELECT id, '1','NO' FROM css_networking_device_prop_def WHERE variable_name in("event.hvac.airsys.logic.generator") AND device_type_id IN (1605);		  
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '0','OFF' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.remotecontrol','event.hvac.airsys.freemode','event.hvac.enable','event.hvac.airsys.remoteenable','event.hvac.airsys.compressor[1]','event.hvac.airsys.compressor[2]','event.hvac.airsys.heater[1]','event.hvac.airsys.heater[2]') AND device_type_id IN (1605); 		
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)SELECT id, '1','ON' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.remotecontrol','event.hvac.airsys.freemode','event.hvac.enable','event.hvac.airsys.remoteenable','event.hvac.airsys.compressor[1]','event.hvac.airsys.compressor[2]','event.hvac.airsys.heater[1]','event.hvac.airsys.heater[2]') AND device_type_id IN (1605);
 
 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '1','COM1' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '2','COM2' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '3','COM3' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '4','COM4' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '5','COM5' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '6','COM6' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '7','COM7' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '8','COM8' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '9','COM9' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '10','COM10' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '11','COM11' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '12','COM12' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '13','COM13' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '14','COM14' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '15','COM15' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605); 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '16','COM16' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.control.port") AND device_type_id IN (1605);
 

 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '0','00' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '1','01' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '2','02' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '3','03' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '4','04' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '5','05' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '6','06' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '7','07' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '8','08' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '9','09' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '10','10' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '11','11' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '12','12' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '13','13' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '14','14' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '15','15' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '16','16' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '17','17' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '18','18' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '19','19' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '20','20' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '21','21' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '22','22' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '23','23' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '24','24' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '25','25' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '26','26' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '27','27' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '28','28' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '29','29' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '30','30' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '31','31' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '32','32' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '33','33' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '34','34' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '35','35' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '36','36' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '37','37' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '38','38' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '39','39' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '40','40' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '41','41' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '42','42' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '43','43' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '44','44' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '45','45' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '46','46' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '47','47' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '48','48' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '49','49' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '50','50' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '51','51' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '52','52' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '53','53' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '54','54' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '55','55' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '56','56' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '57','57' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '58','58' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '59','59' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '60','60' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '61','61' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '62','62' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '63','63' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '64','64' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '65','65' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '66','66' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '67','67' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '68','68' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '69','69' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '70','70' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '71','71' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '72','72' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '73','73' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '74','74' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '75','75' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '76','76' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '77','77' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '78','78' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '79','79' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '80','80' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '81','81' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '82','82' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '83','83' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '84','84' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '85','85' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '86','86' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '87','87' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '88','88' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '89','89' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '90','90' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '91','91' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '92','92' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '93','93' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '94','94' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '95','95' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '96','96' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '97','97' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '98','98' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '99','99' FROM css_networking_device_prop_def WHERE variable_name in('event.hvac.airsys.humid.maxfree','event.hvac.airsys.humid.deadband') AND device_type_id IN ('1605');
-- END
-- START
 
REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Runtimes','Run times');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.runtime.supplyfan[1]','1605','Runtimes');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.runtime.supplyfan[2]','1605','Runtimes');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.runtime.compressor[1]','1605','Runtimes');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.runtime.compressor[2]','1605','Runtimes');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.runtime.heat[1]','1605','Runtimes');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.runtime.heat[2]','1605','Runtimes');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.runtime.freecooling[1]','1605','Runtimes');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.runtime.freecooling[2]','1605','Runtimes');
 
-- END
-- start
 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.supplyfan[1]','System Start Count 1','INTEGER','0','2','System Start count','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.supplyfan[2]','System Start Count 2','INTEGER','0','2','System Start count','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.compressor[1]','Compressor Start Count 1','INTEGER','0','2','Displays a count of the number of times the compressor has turned on.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.compressor[2]','Compressor Start Count 2','INTEGER','0','2','Displays a count of the number of times the compressor has turned on.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.heat[1]','Heater Start Count 1','INTEGER','0','2','Displays a count of the number of times the heater has turned on since the equipment was inst1ed.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.heat[2]','Heater Start Count 2','INTEGER','0','2','Displays a count of the number of times the heater has turned on since the equipment was inst2ed.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.freecooling[1]','Free Cooling Start Count 1','INTEGER','0','2','Displays a count of the number of times the unit has turned on Free Cooling to maintain site  temperature.','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','','event.hvac.airsys.count.freecooling[2]','Free Cooling Start Count 2','INTEGER','0','2','Displays a count of the number of times the unit has turned on Free Cooling to maintain site  temperature.','1','1');
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Unit Status & Statistics','Unit Status & Statistics');
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Status','Status'); 
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Temperature & Humidity','Temperature & Humidity'); 
 REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Temperature & Humidity','Temperature & Humidity'); -- 
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Time','Time');
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.count.supplyfan[1]','1605','Unit Status & Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.count.supplyfan[2]','1605','Unit Status & Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.count.compressor[1]','1605','Unit Status & Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.count.compressor[2]','1605','Unit Status & Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.count.heat[1]','1605','Unit Status & Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.count.heat[2]','1605','Unit Status & Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.count.freecooling[1]','1605','Unit Status & Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.count.freecooling[2]','1605','Unit Status & Statistics');
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.locked[1]','1605','Unit Status & Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.locked[2]','1605','Unit Status & Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.freeopening[1]','1605','Unit Status & Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.freeopening[2]','1605','Unit Status & Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.fanspeed[1]','1605','Unit Status & Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.fanspeed[2]','1605','Unit Status & Statistics');
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.status[1]','1605','Status'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.status[2]','1605','Status'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.remoteenable','1605','Status'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.compressor[1]','1605','Status'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.compressor[2]','1605','Status'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.heater[1]','1605','Status'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.heater[2]','1605','Status'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.temp.indoor','1605','Temperature & Humidity'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.temp.indoorbackup','1605','Temperature & Humidity'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.humid.indoor','1605','Temperature & Humidity'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.temp.outdoor','1605','Temperature & Humidity'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.temp.supply[1]','1605','Temperature & Humidity'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.temp.supply[2]','1605','Temperature & Humidity'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('sys.uptimeseconds','1605','Runtimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('sys.uptime','1605','Runtimes');
 REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Temperature & Humidity','Temperature & Humidity'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.temp.basethreshold','1605','Temperature & Humidity'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.temp.cooldeadband','1605','Temperature & Humidity'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.temp.highvalue','1605','Temperature & Humidity'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.temp.heatdeadband','1605','Temperature & Humidity'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.temp.lowvalue','1605','Temperature & Humidity'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.humid.maxfree','1605','Temperature & Humidity'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.humid.deadband','1605','Temperature & Humidity'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('highTempAlarmTrigger','1605','Temperature & Humidity'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('lowTempAlarmTrigger','1605','Temperature & Humidity'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.freemode','1605','Temperature & Humidity'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.remoteenable','1605','Settings'); 
 REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Host','Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('sys.product','1605','Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('sys.serial','1605','Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('sys.version','1605','Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('sys.mfgr','1605','Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('sys.build','1605','Host');
 REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Networking','Networking'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('net.eth[1].ip','1605','Networking'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('net.eth[1].mask','1605','Networking'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('net.eth[2].mask','1605','Networking'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('net.eth[1].router','1605','Networking'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.control.port','1605','Networking');
 REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Settings','Settings'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.runtime.rotation','1605','Settings'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1605','Settings'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('firmware','1605','Settings'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.enable','1605','Settings'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.control.type','1605','Settings'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.remotecontrol','1605','Settings'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.logic.generator','1605','Settings'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('event.hvac.airsys.alarm.connection.event','1605','Settings');
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('firmware','1606','Settings'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','1606','Settings'); 
-- end
-- start
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Communication Error','Communication Error','STRING','0','2','Communication Error','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Configuration Mismatch','Configuration Mismatch','STRING','0','2','Configuration Mismatch','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Controller Critical Alarm','Controller Critical Alarm','STRING','0','2','Controller Critical Alarm','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Humidity too high','Humidity too high','STRING','0','2','Humidity too high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Humidity too low','Humidity too low','STRING','0','2','Humidity too low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Power failure detected','Power failure detected','STRING','0','2','Power failure detected','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Software compatibility alarm','Software compatibility alarm','STRING','0','2','Software compatibility alarm','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Temperature Critically high','Temperature Critically high','STRING','0','2','Temperature Critically high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Temperature Critically high','Temperature Critically high','STRING','0','2','Temperature Critically high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Temperature Critically low','Temperature Critically low','STRING','0','2','Temperature Critically low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Temperature Critically low','Temperature Critically low','STRING','0','2','Temperature Critically low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Temperature too high','Temperature too high','STRING','0','2','Temperature too high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Temperature too low','Temperature too low','STRING','0','2','Temperature too low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Temperature very high','Temperature very high','STRING','0','2','Temperature very high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Intake: Temperature Critically high','Unit 1 Intake: Temperature Critically high','STRING','0','2','Unit 1 Intake: Temperature Critically high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Intake: Temperature Critically high','Unit 1 Intake: Temperature Critically high','STRING','0','2','Unit 1 Intake: Temperature Critically high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Intake: Temperature Critically low','Unit 1 Intake: Temperature Critically low','STRING','0','2','Unit 1 Intake: Temperature Critically low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Intake: Temperature Critically low','Unit 1 Intake: Temperature Critically low','STRING','0','2','Unit 1 Intake: Temperature Critically low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Intake: Temperature too high','Unit 1 Intake: Temperature too high','STRING','0','2','Unit 1 Intake: Temperature too high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Intake: Temperature too low','Unit 1 Intake: Temperature too low','STRING','0','2','Unit 1 Intake: Temperature too low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Intake: Temperature very high','Unit 1 Intake: Temperature very high','STRING','0','2','Unit 1 Intake: Temperature very high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Outlet: Temperature Critically high','Unit 1 Outlet: Temperature Critically high','STRING','0','2','Unit 1 Outlet: Temperature Critically high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Outlet: Temperature Critically high','Unit 1 Outlet: Temperature Critically high','STRING','0','2','Unit 1 Outlet: Temperature Critically high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Outlet: Temperature Critically low','Unit 1 Outlet: Temperature Critically low','STRING','0','2','Unit 1 Outlet: Temperature Critically low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Outlet: Temperature Critically low','Unit 1 Outlet: Temperature Critically low','STRING','0','2','Unit 1 Outlet: Temperature Critically low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Outlet: Temperature too high','Unit 1 Outlet: Temperature too high','STRING','0','2','Unit 1 Outlet: Temperature too high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Outlet: Temperature too low','Unit 1 Outlet: Temperature too low','STRING','0','2','Unit 1 Outlet: Temperature too low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Outlet: Temperature very high','Unit 1 Outlet: Temperature very high','STRING','0','2','Unit 1 Outlet: Temperature very high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Intake: Temperature Critically high','Unit 2 Intake: Temperature Critically high','STRING','0','2','Unit 2 Intake: Temperature Critically high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Intake: Temperature Critically high','Unit 2 Intake: Temperature Critically high','STRING','0','2','Unit 2 Intake: Temperature Critically high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Intake: Temperature Critically low','Unit 2 Intake: Temperature Critically low','STRING','0','2','Unit 2 Intake: Temperature Critically low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Intake: Temperature Critically low','Unit 2 Intake: Temperature Critically low','STRING','0','2','Unit 2 Intake: Temperature Critically low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Intake: Temperature too high','Unit 2 Intake: Temperature too high','STRING','0','2','Unit 2 Intake: Temperature too high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Intake: Temperature too low','Unit 2 Intake: Temperature too low','STRING','0','2','Unit 2 Intake: Temperature too low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Intake: Temperature very high','Unit 2 Intake: Temperature very high','STRING','0','2','Unit 2 Intake: Temperature very high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Outlet: Temperature Critically high','Unit 2 Outlet: Temperature Critically high','STRING','0','2','Unit 2 Outlet: Temperature Critically high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Outlet: Temperature Critically high','Unit 2 Outlet: Temperature Critically high','STRING','0','2','Unit 2 Outlet: Temperature Critically high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Outlet: Temperature Critically low','Unit 2 Outlet: Temperature Critically low','STRING','0','2','Unit 2 Outlet: Temperature Critically low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Outlet: Temperature Critically low','Unit 2 Outlet: Temperature Critically low','STRING','0','2','Unit 2 Outlet: Temperature Critically low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Outlet: Temperature too high','Unit 2 Outlet: Temperature too high','STRING','0','2','Unit 2 Outlet: Temperature too high','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Outlet: Temperature too low','Unit 2 Outlet: Temperature too low','STRING','0','2','Unit 2 Outlet: Temperature too low','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Outlet: Temperature very high','Unit 2 Outlet: Temperature very high','STRING','0','2','Unit 2 Outlet: Temperature very high','1','1');


 REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Alarms\\Unit1','Alarms\\Unit1'); 
 REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Alarms\\Unit2','Alarms\\Unit2'); 
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Alarms\\Unit1','Alarms\\Unit1'); 
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Alarms\\Unit2','Alarms\\Unit2'); 
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('UnitStatus&Statistics\\Unit1','Unit Status & Statistics\\Unit1'); 
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('UnitStatus&Statistics\\Unit2','Unit Status & Statistics\\Unit2'); 
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('RunTimes\\Unit1','Runtimes\\Unit1'); 
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('RunTimes\\Unit2','Runtimes\\Unit2'); 
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Temperature&Humidity\\Unit1','Temperature & Humidity\\Unit1'); 
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Temperature&Humidity\\Unit2','Temperature & Humidity\\Unit2');
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','indoorTemperature__Temperature','indoorTemperature__Temperature','Indoor Temperature(F)','Indoor Temperature','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','indoorTemperature__CriticallyLowSeconds','indoorTemperature__CriticallyLowSeconds','Indoor Temperature Critically Low Duration (Seconds)','Indoor Temperature Critically Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','indoorTemperature__CriticallyLowSeconds','indoorTemperature__CriticallyLowSeconds','Indoor Temperature Critically Low Duration (Seconds)','Indoor Temperature Critically Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','indoorTemperature__LowSeconds','indoorTemperature__LowSeconds','Indoor Temperature Low Duration (Seconds)','Indoor Temperature Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','indoorTemperature__HighSeconds','indoorTemperature__HighSeconds','Indoor Temperature High Duration (Seconds)','Indoor Temperature High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','indoorTemperature__VeryHighSeconds','indoorTemperature__VeryHighSeconds','Indoor Temperature VeryHigh Duration (Seconds)','Indoor Temperature VeryHigh Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','indoorTemperature__CriticallyHighSeconds','indoorTemperature__CriticallyHighSeconds','Indoor Temperature Critically High Duration (Seconds)','Indoor Temperature Critically High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','indoorTemperature__CriticallyHighSeconds','indoorTemperature__CriticallyHighSeconds','Indoor Temperature Critically High Duration (Seconds)','Indoor Temperature Critically High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit1__CompressingRuntimeSeconds','hvacUnit1__CompressingRuntimeSeconds','Unit 1 Compressing Runtime (Seconds)','Unit 1 Compressing Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit1__EconomizingRuntimeSeconds','hvacUnit1__EconomizingRuntimeSeconds','Unit 1 Economizing Runtime (Seconds)','Unit 1 Economizing Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit1__FanOnlyRuntimeSeconds','hvacUnit1__FanOnlyRuntimeSeconds','Unit 1 FanOnly Runtime  (Seconds)','Unit 1 FanOnly Runtime  (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit1__HeatingRuntimeSeconds','hvacUnit1__HeatingRuntimeSeconds','Unit 1 Heating Runtime (Seconds)','Unit 1 Heating duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit1__CompressingCycles','hvacUnit1__CompressingCycles','Unit 1 Compressing Count','Unit 1 Compressing count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit1__EconomizingCycles','hvacUnit1__EconomizingCycles','Unit 1 Economizing Count','Unit 1 Economizing count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit1__FanOnlyCycles','hvacUnit1__FanOnlyCycles','Unit 1 FanOnly Count','Unit 1 FanOnly count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit1__HeatingCycles','hvacUnit1__HeatingCycles','Unit 1 Heating Count','Unit 1 Heating count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit1__SupplyFanSpeed','hvacUnit1__SupplyFanSpeed','Unit 1 Supply Fan Speed %','Unit 1 Supply Fan Speed %','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit1__FreeCoolingDamper','hvacUnit1__FreeCoolingDamper','Unit 1 Free Cooling Damper Opening %','Unit 1 Free cooling damper opening','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__CompressingRuntimeSeconds','hvacSystem__CompressingRuntimeSeconds','Compressing Runtime (Seconds)','Compressing Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__EconomizingRuntimeSeconds','hvacSystem__EconomizingRuntimeSeconds','Economizing Runtime (Seconds)','Economizing Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__FanOnlyRuntimeSeconds','hvacSystem__FanOnlyRuntimeSeconds','FanOnly Runtime (Seconds)','FanOnly Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__HeatingRuntimeSeconds','hvacSystem__HeatingRuntimeSeconds','Heating Runtime (Seconds)','Heating Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__CompressingCycles','hvacSystem__CompressingCycles','Compressing Count','Compressing count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__EconomizingCycles','hvacSystem__EconomizingCycles','Economizing Count','Economizing count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__FanOnlyCycles','hvacSystem__FanOnlyCycles','FanOnly Count','FanOnly Count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__HeatingCycles','hvacSystem__HeatingCycles','Heating Count','Heating Count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','hvacSystem: coolingSetpoint','hvacSystem: coolingSetpoint','HVAC System: Cooling Setpoint(F)','Cooling setpoint from controller','INTEGER','1','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit1LeadRuntimeSeconds','hvacSystem__Unit1LeadRuntimeSeconds','Lead Unit 1 Runtime (Seconds)','Lead Unit 1 Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit2LeadRuntimeSeconds','hvacSystem__Unit2LeadRuntimeSeconds','Lead Unit 2 Runtime (Seconds)','Lead Unit 2 Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit1LeadCycles','hvacSystem__Unit1LeadCycles','Lead Cycles Unit 1 Count','Lead Cycles Unit 1 count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit2LeadCycles','hvacSystem__Unit2LeadCycles','Lead Cycles Unit 2 Count','Lead Cycles Unit 2 count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit1LeadCompressingRuntimeSeconds','hvacSystem__Unit1LeadCompressingRuntimeSeconds','Unit 1 Lead and Compressing Runtime (Seconds)','Unit 1 Lead and compressing duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit1LeadEconomizingRuntimeSeconds','hvacSystem__Unit1LeadEconomizingRuntimeSeconds','Unit 1 Lead and Economizing Runtime (Seconds)','Unit 1 Lead and Economizing duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit1LagCompressingRuntimeSeconds','hvacSystem__Unit1LagCompressingRuntimeSeconds','Unit 1 Lag and Compressing Runtime (Seconds)','Unit 1 Lag and compressing duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit1LagEconomizingRuntimeSeconds','hvacSystem__Unit1LagEconomizingRuntimeSeconds','Unit 1 Lag and Economizing Runtime (Seconds)','Unit 1 Lag and Economizing duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit1LeadCompressingCycles','hvacSystem__Unit1LeadCompressingCycles','Unit 1 Lead and compressing Cycles Count','Unit 1 Lead and compressing Cycles count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit1LeadEconomizingCycles','hvacSystem__Unit1LeadEconomizingCycles','Unit 1 Lead and Economizing Cycles Count','Unit 1 Lead and Economizing Cycles count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit1LagCompressingCycles','hvacSystem__Unit1LagCompressingCycles','Unit 1 Lag and Compressing Cycles Count','Unit 1 Lag and Compressing Cycles count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit1LagEconomizingCycles','hvacSystem__Unit1LagEconomizingCycles','Unit 1 Lag and Economizing Cycles Count','Unit 1 Lag and Economizing Cycles count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit2LeadCompressingRuntimeSeconds','hvacSystem__Unit2LeadCompressingRuntimeSeconds','Unit 2 Lead and Compressing Runtime (Seconds)','Unit 2 Lead and Compressing Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit2LeadEconomizingRuntimeSeconds','hvacSystem__Unit2LeadEconomizingRuntimeSeconds','Unit 2 Lead and Economizing Runtime (Seconds)','Unit 2 Lead and Economizing Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit2LagCompressingRuntimeSeconds','hvacSystem__Unit2LagCompressingRuntimeSeconds','Unit 2 Lag and Compressing Runtime (Seconds)','Unit 2 Lag and Compressing Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit2LagEconomizingRuntimeSeconds','hvacSystem__Unit2LagEconomizingRuntimeSeconds','Unit 2 Lag and Economizing Runtime (Seconds)','Unit 2 Lag and Economizing Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit2LeadCompressingCycles','hvacSystem__Unit2LeadCompressingCycles','Unit 2 Lead and compressing Cycles Count','Unit 2 Lead and compressing Cycles count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit2LeadEconomizingCycles','hvacSystem__Unit2LeadEconomizingCycles','Unit 2 Lead and Economizing Cycles Count','Unit 2 Lead and Economizing Cycles count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit2LagCompressingCycles','hvacSystem__Unit2LagCompressingCycles','Unit 2 Lag and Compressing Cycles Count','Unit 2 Lag and Compressing Cycles count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__Unit2LagEconomizingCycles','hvacSystem__Unit2LagEconomizingCycles','Unit 2 Lag and Economizing Cycles Count','Unit 2 Lag and Economizing Cycles count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacSystem__CommunicationCount','hvacSystem__CommunicationCount','Number of Communication Error Occurrences','Number of communication error occurrences','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit2__CompressingRuntimeSeconds','hvacUnit2__CompressingRuntimeSeconds','Unit 2 Compressing Runtime (Seconds)','Unit2 Compressing Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit2__EconomizingRuntimeSeconds','hvacUnit2__EconomizingRuntimeSeconds','Unit 2 Economizing Runtime (Seconds)','Unit2 Economizing Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit2__FanOnlyRuntimeSeconds','hvacUnit2__FanOnlyRuntimeSeconds','Unit 2 FanOnly Runtime (Seconds)','Unit2 FanOnly Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit2__HeatingRuntimeSeconds','hvacUnit2__HeatingRuntimeSeconds','Unit 2 Heating Runtime (Seconds)','Unit2 Heating Runtime (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit2__CompressingCycles','hvacUnit2__CompressingCycles','Unit 2 Compressing Cycles Count','Unit2 Compressing Cycles count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit2__EconomizingCycles','hvacUnit2__EconomizingCycles','Unit 2 Economizing Cycles Count','Unit2 Economizing Cycles count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit2__FanOnlyCycles','hvacUnit2__FanOnlyCycles','Unit 2 FanOnly Cycles Count','Unit2 FanOnly Cycles count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit2__HeatingCycles','hvacUnit2__HeatingCycles','Unit 2 Heating Cycles Count','Unit2 Heating Cycles count','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit2__SupplyFanSpeed','hvacUnit2__SupplyFanSpeed','Unit 2 Supply Fan Speed %','Unit2 Supply Fan Speed %','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvacUnit2__FreeCoolingDamper','hvacUnit2__FreeCoolingDamper','Unit 2 Free Cooling Damper Opening %','Unit2 Free cooling damper opening','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','indoorHumidity__Humidity','indoorHumidity__Humidity','Indoor Humidity %','Indoor Humidity %','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1IntakeTemperature__Temperature','hvac1IntakeTemperature__Temperature','Unit 1 Intake Temperature(F)','Temperature','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1IntakeTemperature__CriticallyLowSeconds','hvac1IntakeTemperature__CriticallyLowSeconds','Unit 1 Intake Critically Low Duration (Seconds)','Critically Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1IntakeTemperature__CriticallyLowSeconds','hvac1IntakeTemperature__CriticallyLowSeconds','Unit 1 Intake Critically Low Duration (Seconds)','Critically Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1IntakeTemperature__LowSeconds','hvac1IntakeTemperature__LowSeconds','Unit 1 Intake Low Duration (Seconds)','Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1IntakeTemperature__HighSeconds','hvac1IntakeTemperature__HighSeconds','Unit 1 Intake High Duration (Seconds)','High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1IntakeTemperature__VeryHighSeconds','hvac1IntakeTemperature__VeryHighSeconds','Unit 1 Intake VeryHigh Duration (Seconds)','VeryHigh Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1IntakeTemperature__CriticallyHighSeconds','hvac1IntakeTemperature__CriticallyHighSeconds','Unit 1 Intake Critically High Duration (Seconds)','Critically High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1IntakeTemperature__CriticallyHighSeconds','hvac1IntakeTemperature__CriticallyHighSeconds','Unit 1 Intake Critically High Duration (Seconds)','Critically High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1OutletTemperature__Temperature','hvac1OutletTemperature__Temperature','Unit 1 Outlet Temperature(F)','Temperature','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1OutletTemperature__CriticallyLowSeconds','hvac1OutletTemperature__CriticallyLowSeconds','Unit 1 Outlet Critically Low Duration (Seconds)','Critically Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1OutletTemperature__CriticallyLowSeconds','hvac1OutletTemperature__CriticallyLowSeconds','Unit 1 Outlet Critically Low Duration (Seconds)','Critically Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1OutletTemperature__LowSeconds','hvac1OutletTemperature__LowSeconds','Unit 1 Outlet Low Duration (Seconds)','Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1OutletTemperature__HighSeconds','hvac1OutletTemperature__HighSeconds','Unit 1 Outlet High Duration (Seconds)','High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1OutletTemperature__VeryHighSeconds','hvac1OutletTemperature__VeryHighSeconds','Unit 1 Outlet VeryHigh Duration (Seconds)','VeryHigh Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1OutletTemperature__CriticallyHighSeconds','hvac1OutletTemperature__CriticallyHighSeconds','Unit 1 Outlet Critically High Duration (Seconds)','Critically High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac1OutletTemperature__CriticallyHighSeconds','hvac1OutletTemperature__CriticallyHighSeconds','Unit 1 Outlet Critically High Duration (Seconds)','Critically High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2IntakeTemperature__Temperature','hvac2IntakeTemperature__Temperature','Unit 2 Intake Temperature(F)','Temperature','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2IntakeTemperature__CriticallyLowSeconds','hvac2IntakeTemperature__CriticallyLowSeconds','Unit 2 Intake Critically Low Duration (Seconds)','Critically Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2IntakeTemperature__CriticallyLowSeconds','hvac2IntakeTemperature__CriticallyLowSeconds','Unit 2 Intake Critically Low Duration (Seconds)','Critically Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2IntakeTemperature__LowSeconds','hvac2IntakeTemperature__LowSeconds','Unit2 Intake Low Duration (Seconds)','Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2IntakeTemperature__HighSeconds','hvac2IntakeTemperature__HighSeconds','Unit2 Intake High Duration (Seconds)','High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2IntakeTemperature__VeryHighSeconds','hvac2IntakeTemperature__VeryHighSeconds','Unit2 Intake VeryHigh Duration (Seconds)','VeryHigh Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2IntakeTemperature__CriticallyHighSeconds','hvac2IntakeTemperature__CriticallyHighSeconds','Unit2 Intake Critically High Duration (Seconds)','Critically High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2IntakeTemperature__CriticallyHighSeconds','hvac2IntakeTemperature__CriticallyHighSeconds','Unit2 Intake Critically High Duration (Seconds)','Critically High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2OutletTemperature__Temperature','hvac2OutletTemperature__Temperature','Unit 2 Outlet Temperature(F)','Temperature','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2OutletTemperature__CriticallyLowSeconds','hvac2OutletTemperature__CriticallyLowSeconds','Unit 2 Outlet Critically Low Duration (Seconds)','Critically Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2OutletTemperature__CriticallyLowSeconds','hvac2OutletTemperature__CriticallyLowSeconds','Unit 2 Outlet Critically Low Duration (Seconds)','Critically Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2OutletTemperature__LowSeconds','hvac2OutletTemperature__LowSeconds','Unit 2 Outlet Low Duration (Seconds)','Low Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2OutletTemperature__HighSeconds','hvac2OutletTemperature__HighSeconds','Unit 2 Outlet High Duration (Seconds)','High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2OutletTemperature__VeryHighSeconds','hvac2OutletTemperature__VeryHighSeconds','Unit 2 Outlet VeryHigh Duration (Seconds)','VeryHigh Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2OutletTemperature__CriticallyHighSeconds','hvac2OutletTemperature__CriticallyHighSeconds','Unit 2 Outlet Critically High Duration (Seconds)','Critically High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','hvac2OutletTemperature__CriticallyHighSeconds','hvac2OutletTemperature__CriticallyHighSeconds','Unit 2 Outlet Critically High Duration (Seconds)','Critically High Duration (Seconds)','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','.1.3.6.1.2.1.1.3.0','System Uptime','System Uptime(D:H:M:S)','System Uptime Day:Hour:Minute:Second','INTEGER','0','1','0'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','.1.3.6.1.2.1.1.3.0','System Uptime2','System Uptime(Seconds)','System Uptime Seconds','INTEGER','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','.1.3.6.1.2.1.4.20.1.1.2','IP Address','IP Address','IP Address','STRING','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','.1.3.6.1.2.1.4.20.1.1.0','Gateway','Gateway','Gateway','STRING','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','.1.3.6.1.2.1.4.20.1.3.0','Net mask','Net mask','Net mask','STRING','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','aiSysInvSoftwareProductName.1.1.1.1','aiSysInvSoftwareProductName.1.1.1.1','Host Name','Host Name','STRING','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','aiSysInvCardManufacturer.1.1.1','aiSysInvCardManufacturer.1.1.1','Host Manufacture ','Host Manufacture ','STRING','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','aiSysInvCardSerialNumber.1.1.1','aiSysInvCardSerialNumber.1.1.1','Host Serial Number','Host Serial Number','STRING','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','aiSysInvSoftwareVersion.1.1.1.1','aiSysInvSoftwareVersion.1.1.1.1','Host Version','Host Version','STRING','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','aiSysInvSoftwareBuildId.1.1.1.1','aiSysInvSoftwareBuildId.1.1.1.1','Host Build','Host Build ID','STRING','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','aiSystem.4.1.4.1.1.1.1','aiSystem.4.1.4.1.1.1.1','Host Name','Host Name','STRING','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','aiSystem.2.1.3.1.1.1','aiSystem.2.1.3.1.1.1','Host Manufacture ','Host Manufacture ','STRING','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','aiSystem.2.1.5.1.1.1','aiSystem.2.1.5.1.1.1','Host Serial Number','Host Serial Number','STRING','0','1','1'); 
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','aiSystem.4.1.5.1.1.1.1','aiSystem.4.1.5.1.1.1.1','Host Version','Host Version','STRING','0','1','1'); 
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','1','aiSystem.4.1.7.1.1.1.1','aiSystem.4.1.7.1.1.1.1','Host Build','Host Build','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: Unit1Status','HVAC System: Unit 1 Status','STRING','0','1','Unit 1 Status','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: Unit2Status','HVAC System: Unit 2 Status','STRING','0','1','Unit 2 Status','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: Description','HVAC System: Description','STRING','0','1','Description','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacUnit1: Description','HVAC Unit 1: Description','STRING','0','1','Description','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacUnit2: Description','HVAC Unit 2: Description','STRING','0','1','Description','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1OutletTemperature: Description','HVAC1 Outlet Temperature: Description','STRING','0','1','Description','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2OutletTemperature: Description','HVAC2 Outlet Temperature: Description','STRING','0','1','Description','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1IntakeTemperature: Description','HVAC1 Intake Temperature: Description','STRING','0','1','Description','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2IntakeTemperature: Description','HVAC2 Intake Temperature: Description','STRING','0','1','Description','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: LeadUnit','HVAC System: LeadUnit','STRING','0','1','LeadUnit','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: Mode','HVAC System: Mode','STRING','0','1','Mode','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacUnit1: Mode','HVAC Unit 1: Mode','STRING','0','1','Mode','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacUnit2: Mode','HVAC Unit 2: Mode','STRING','0','1','Mode','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: Type','HVAC System: Type','STRING','0','1','Type','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacUnit1: Type','HVAC Unit 1: Type','STRING','0','1','Type','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacUnit2: Type','HVAC Unit 2: Type','STRING','0','1','Type','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1OutletTemperature: Type','HVAC1 Outlet Temperature: Type','STRING','0','1','Type','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2OutletTemperature: Type','HVAC2 Outlet Temperature: Type','STRING','0','1','Type','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1IntakeTemperature: Type','HVAC1 Intake Temperature: Type','STRING','0','1','Type','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2IntakeTemperature: Type','HVAC2 Intake Temperature: Type','STRING','0','1','Type','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: controller','HVAC System: Controller','STRING','0','1','Controller','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: coolingDifference','HVAC System: Cooling Difference','INTEGER','1','1','Cooling Difference (Default: 6 F)','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: cutThroughIp','HVAC System: Cut Through Ip','STRING','0','1','The IP address on which to allow cut-through connections','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: cutThroughPort','HVAC System: Cut Through Port','STRING','0','1','The port on which to allow cut-through connections','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: damperClosedTolerance','HVAC System: Damper Closed Tolerance','INTEGER','1','1','Tolerance for Damper Closed State Alarm (Default: 10 F)','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: damperOpenTolerance','HVAC System: Damper Open Tolerance','INTEGER','1','1','Tolerance for Damper Open State Alarm (Default: 10 F)','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: enableLagOveruseAlarm','HVAC System: Enable Lag Overuse Alarm','STRING','1','1','An alarm to indicate the lag unit is compressing when it shouldnt be','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: heatingDifference','HVAC System: Heating Difference','INTEGER','1','1','Heating Difference (Default: 3 F)','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: highTempAlarmTrigger','HVAC System: High Temp Alarm Trigger','INTEGER','1','1','High Temp Alarm Trigger (Default: 18 F Above Setpoint)','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: humidityDeadband','HVAC System: Humidity Deadband','INTEGER','1','1','Humidity Turn On Deadband (Default: 5%)','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: Internal State','HVAC System: Internal State','STRING','0','1','Internal State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacUnit1: Internal State','HVAC Unit 1: Internal State','STRING','0','1','Internal State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacUnit2: Internal State','HVAC Unit 2: Internal State','STRING','0','1','Internal State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1OutletTemperature: Internal State','HVAC1 Outlet Temperature: Internal State','STRING','0','1','Internal State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2OutletTemperature: Internal State','HVAC2 Outlet Temperature: Internal State','STRING','0','1','Internal State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1IntakeTemperature: Internal State','HVAC1 Intake Temperature: Internal State','STRING','0','1','Internal State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2IntakeTemperature: Internal State','HVAC2 Intake Temperature: Internal State','STRING','0','1','Internal State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: lowTempAlarmTrigger','HVAC System: low Temp Alarm Trigger','INTEGER','1','1','Low Temp Alarm Trigger (Default: 32 F Below Setpoint)','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: maximumFreecoolingHumidity','HVAC System: Maximum Freecooling Humidity','INTEGER','1','1','Maximum Indoor Humidity Allowed for Freecooling (Default: 85%)','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: minimumFreecoolingTempDiff','HVAC System: Minimum Freecooling Temp Diff','INTEGER','1','1','Minimum Outdoor-Indoor Temperature Difference for Freecooling (Default: 3 F)','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: modbusAddress','HVAC System: Modbus Address','STRING','0','1','Device MODBUS address','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1OutletTemperature: REF_temperatureUnits','HVAC1 Outlet Temperature: REF_temperature Units','STRING','0','1','Referenced from REF temperatureUnits. Per-NE configuration of units is not supported.','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2OutletTemperature: REF_temperatureUnits','HVAC2 Outlet Temperature: REF_temperature Units','STRING','0','1','Referenced from REF temperatureUnits. Per-NE configuration of units is not supported.','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1IntakeTemperature: REF_temperatureUnits','HVAC1 Intake Temperature: REF_temperature Units','STRING','0','1','Referenced from REF temperatureUnits. Per-NE configuration of units is not supported.','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2IntakeTemperature: REF_temperatureUnits','HVAC2 Intake Temperature: REF_temperature Units','STRING','0','1','Referenced from REF temperatureUnits. Per-NE configuration of units is not supported.','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: REF_temperatureUnits','HVAC System: REF_temperature Units','STRING','0','1','Referenced from REF temperatureUnits. Per-NE configuration of units is not supported.','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: State','HVAC System: State','STRING','0','1','State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacUnit1: State','HVAC Unit 1: State','STRING','0','1','State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacUnit2: State','HVAC Unit 2: State','STRING','0','1','State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1OutletTemperature: State','HVAC1 Outlet Temperature: State','STRING','0','1','State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2OutletTemperature: State','HVAC2 Outlet Temperature: State','STRING','0','1','State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1IntakeTemperature: State','HVAC1 Intake Temperature: State','STRING','0','1','State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2IntakeTemperature: State','HVAC2 Intake Temperature: State','STRING','0','1','State','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1OutletTemperature: criticallyHighTemperature','HVAC1 Outlet Temperature: Critically High Temperature','STRING','1','1','Critically High Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2OutletTemperature: criticallyHighTemperature','HVAC2 Outlet Temperature: Critically High Temperature','STRING','1','1','Critically High Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1IntakeTemperature: criticallyHighTemperature','HVAC1 Intake Temperature: Critically High Temperature','STRING','1','1','Critically High Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2IntakeTemperature: criticallyHighTemperature','HVAC2 Intake Temperature: Critically High Temperature','STRING','1','1','Critically High Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1OutletTemperature: criticallyLowTemperature','HVAC1 Outlet Temperature: Critically Low Temperature','STRING','1','1','Critically Low Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2OutletTemperature: criticallyLowTemperature','HVAC2 Outlet Temperature: Critically Low Temperature','STRING','1','1','Critically Low Temperaturee','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1IntakeTemperature: criticallyLowTemperature','HVAC1 Intake Temperature: Critically Low Temperature','STRING','1','1','Critically Low Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2IntakeTemperature: criticallyLowTemperature','HVAC2 Intake Temperature: Critically Low Temperature','STRING','1','1','Critically Low Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1OutletTemperature: veryHighTemperature','HVAC1 Outlet Temperature: Very High Temperature','STRING','1','1','Very High Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2OutletTemperature: veryHighTemperature','HVAC2 Outlet Temperature: Very High Temperature','STRING','1','1','Very High Temperaturee','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1IntakeTemperature: veryHighTemperature','HVAC1 Intake Temperature: Very High Temperature','STRING','1','1','Very High Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2IntakeTemperature: veryHighTemperature','HVAC2 Intake Temperature: Very High Temperature','STRING','1','1','Very High Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1OutletTemperature: highTemperature','HVAC1 Outlet Temperature: High Temperature','STRING','1','1','High Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2OutletTemperature: highTemperature','HVAC2 Outlet Temperature: High Temperature','STRING','1','1','High Temperaturee','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1IntakeTemperature: highTemperature','HVAC1 Intake Temperature: High Temperature','STRING','1','1','High Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2IntakeTemperature: highTemperature','HVAC2 Intake Temperature: High Temperature','STRING','1','1','High Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1OutletTemperature: lowTemperature','HVAC1 Outlet Temperature: Low Temperature','STRING','1','1','Low Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2OutletTemperature: lowTemperature','HVAC2 Outlet Temperature: Low Temperature','STRING','1','1','Low Temperaturee','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1IntakeTemperature: lowTemperature','HVAC1 Intake Temperature: Low Temperature','STRING','1','1','Low Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2IntakeTemperature: lowTemperature','HVAC2 Intake Temperature: Low Temperature','STRING','1','1','Low Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1OutletTemperature: maximumTemperature','HVAC1 Outlet Temperature: Maximum Temperature','STRING','1','1','Maximum Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2OutletTemperature: maximumTemperature','HVAC2 Outlet Temperature: Maximum Temperature','STRING','1','1','Maximum Temperaturee','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1IntakeTemperature: maximumTemperature','HVAC1 Intake Temperature: Maximum Temperature','STRING','1','1','Maximum Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2IntakeTemperature: maximumTemperature','HVAC2 Intake Temperature: Maximum Temperature','STRING','1','1','Maximum Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1OutletTemperature: minimumTemperature','HVAC1 Outlet Temperature: Minimum Temperature','STRING','1','1','Minimum Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2OutletTemperature: minimumTemperature','HVAC2 Outlet Temperature: Minimum Temperature','STRING','1','1','Minimum Temperaturee','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1IntakeTemperature: minimumTemperature','HVAC1 Intake Temperature: Minimum Temperature','STRING','1','1','Minimum Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2IntakeTemperature: minimumTemperature','HVAC2 Intake Temperature: Minimum Temperature','STRING','1','1','Minimum Temperature','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1OutletTemperature: hysteresis','HVAC1 Outlet Temperature: Hysteresis','STRING','1','1','Hysteresis','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2OutletTemperature: hysteresis','HVAC2 Outlet Temperature: Hysteresis','STRING','1','1','Hysteresis','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac1IntakeTemperature: hysteresis','HVAC1 Intake Temperature: Hysteresis','STRING','1','1','Hysteresis','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvac2IntakeTemperature: hysteresis','HVAC2 Intake Temperature: Hysteresis','STRING','1','1','Hysteresis','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','hvacSystem: leadSwapDelay','HVAC System: Lead Swap Delay (Days)','INTEGER','1','1','Lead Swap Delay','1','1');


REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Configuration','Configuration');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Status Points','Status Points');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Settings\\\StatusPoints','Settings\\\Status Points');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Settings\\\Alarms','Settings\\\Alarms');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Settings\\UnitInformation','Settings\\Unit Information');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Settings\\\Type','Settings\\\Type');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Settings\\\State','Settings\\\State');
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Settings\\\InternalState','Settings\\\Internal State');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: Description','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1: Description','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2: Description','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature: Description','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature: Description','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature: Description','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature: Description','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: Mode','1606','Settings\\StatusPoints');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1: Mode','1606','Settings\\StatusPoints');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2: Mode','1606','Settings\\StatusPoints');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: Unit1Status','1606','Settings\\StatusPoints');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: Unit2Status','1606','Settings\\StatusPoints');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: LeadUnit','1606','Settings\\StatusPoints');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: leadSwapDelay','1606','Settings\\StatusPoints');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: Type','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1: Type','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2: Type','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature: Type','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature: Type','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature: Type','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature: Type','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: controller','1606','Networking');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: coolingDifference','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: cutThroughIp','1606','Networking');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: cutThroughPort','1606','Networking');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: damperClosedTolerance','1606','Settings\\Alarms');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: damperOpenTolerance','1606','Settings\\Alarms');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: enableLagOveruseAlarm','1606','Settings\\Alarms');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: heatingDifference','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: highTempAlarmTrigger','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: humidityDeadband','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: Internal State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1: Internal State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2: Internal State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature: Internal State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature: Internal State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature: Internal State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature: Internal State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: lowTempAlarmTrigger','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: maximumFreecoolingHumidity','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: minimumFreecoolingTempDiff','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: modbusAddress','1606','Networking');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: REF_temperatureUnits','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature: REF_temperatureUnits','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature: REF_temperatureUnits','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature: REF_temperatureUnits','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature: REF_temperatureUnits','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1: State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2: State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature: State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature: State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature: State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature: State','1606','UnitInformation');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature: criticallyHighTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature: criticallyHighTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature: criticallyHighTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature: criticallyHighTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature: criticallyLowTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature: criticallyLowTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature: criticallyLowTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature: criticallyLowTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature: veryHighTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature: veryHighTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature: veryHighTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature: veryHighTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature: highTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature: highTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature: highTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature: highTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature: lowTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature: lowTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature: lowTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature: lowTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature: maximumTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature: maximumTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature: maximumTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature: maximumTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature: minimumTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature: minimumTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature: minimumTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature: minimumTemperature','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature: hysteresis','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature: hysteresis','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature: hysteresis','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature: hysteresis','1606','Temperature & Humidity');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem: coolingSetpoint',1606,'Temperature & Humidity');
-- end
-- start
 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('aiSysInvCardManufacturer.1.1.1',1606,'Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('aiSysInvCardSerialNumber.1.1.1',1606,'Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('aiSysInvSoftwareBuildId.1.1.1.1',1606,'Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('aiSysInvSoftwareProductName.1.1.1.1',1606,'Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('aiSysInvSoftwareVersion.1.1.1.1',1606,'Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('aiSystem.2.1.3.1.1.1',1606,'Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('aiSystem.2.1.5.1.1.1',1606,'Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('aiSystem.4.1.4.1.1.1.1',1606,'Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('aiSystem.4.1.5.1.1.1.1',1606,'Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('aiSystem.4.1.7.1.1.1.1',1606,'Host'); 
   REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Gateway',1606,'Networking'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Host Build',1606,'Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Host Manufacture',1606,'Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Host Name',1606,'Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Host Serial Number',1606,'Host'); 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Host Version',1606,'Host'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('IP Address',1606,'Networking'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Net mask',1606,'Networking');  

 

 
 
 REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Alarms\\\Duration','Alarms\\\Duration');
 REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Alarms\\\Duration','Alarms\\\Duration');
 
 
 
 
 
 
 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature__CriticallyHighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature__CriticallyHighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature__CriticallyLowSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature__CriticallyLowSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature__HighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature__LowSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature__Temperature',1606,'Temperature&Humidity'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1IntakeTemperature__VeryHighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature__CriticallyHighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature__CriticallyHighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature__CriticallyLowSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature__CriticallyLowSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature__HighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature__LowSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature__Temperature',1606,'Temperature&Humidity'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac1OutletTemperature__VeryHighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature__CriticallyHighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature__CriticallyHighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature__CriticallyLowSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature__CriticallyLowSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature__HighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature__LowSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature__Temperature',1606,'Temperature&Humidity'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2IntakeTemperature__VeryHighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature__CriticallyHighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature__CriticallyHighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature__CriticallyLowSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature__CriticallyLowSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature__HighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature__LowSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature__Temperature',1606,'Temperature&Humidity'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvac2OutletTemperature__VeryHighSeconds',1606,'Alarms\\Duration'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__CommunicationCount',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__CompressingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__CompressingRuntimeSeconds',1606,'RunTimes');
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__EconomizingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__EconomizingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__FanOnlyCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__FanOnlyRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__HeatingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__HeatingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit1LagCompressingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit1LagCompressingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit1LagEconomizingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit1LagEconomizingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit1LeadCompressingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit1LeadCompressingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit1LeadCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit1LeadEconomizingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit1LeadEconomizingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit1LeadRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit2LagCompressingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit2LagCompressingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit2LagEconomizingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit2LagEconomizingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit2LeadCompressingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit2LeadCompressingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit2LeadCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit2LeadEconomizingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit2LeadEconomizingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacSystem__Unit2LeadRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1__CompressingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1__CompressingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1__EconomizingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1__EconomizingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1__FanOnlyCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1__FanOnlyRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1__FreeCoolingDamper',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1__HeatingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1__HeatingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit1__SupplyFanSpeed',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2__CompressingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2__CompressingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2__EconomizingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2__EconomizingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2__FanOnlyCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2__FanOnlyRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2__FreeCoolingDamper',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2__HeatingCycles',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2__HeatingRuntimeSeconds',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('hvacUnit2__SupplyFanSpeed',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('indoorHumidity__Humidity',1606,'Temperature&Humidity'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('indoorTemperature__CriticallyHighSeconds',1606,'Alarms'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('indoorTemperature__CriticallyHighSeconds',1606,'Alarms'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('indoorTemperature__CriticallyLowSeconds',1606,'Alarms'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('indoorTemperature__CriticallyLowSeconds',1606,'Alarms'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('indoorTemperature__HighSeconds',1606,'Alarms'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('indoorTemperature__LowSeconds',1606,'Alarms'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('indoorTemperature__Temperature',1606,'Temperature&Humidity'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('indoorTemperature__VeryHighSeconds',1606,'Alarms'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('System Uptime',1606,'RunTimes'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('System Uptime2',1606,'RunTimes');
-- end 
-- START
-- UPDATE css_networking_device_prop_def SET graph_type=2 WHERE device_type_id=1605 AND variable_name IN ('event.hvac.airsys.compressor[1]','event.hvac.airsys.compressor[2]','event.hvac.airsys.heater[1]','event.hvac.airsys.heater[2]');
-- END
-- start
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '34','034' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '35','035' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '36','036' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '37','037' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '38','038' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '39','039' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '40','040' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '41','041' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '42','042' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '43','043' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '44','044' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '45','045' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '46','046' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '47','047' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '48','048' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '49','049' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '50','050' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '51','051' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '52','052' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '53','053' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '54','054' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '55','055' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '56','056' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '57','057' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '58','058' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '59','059' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '60','060' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '61','061' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '62','062' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '63','063' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '64','064' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '65','065' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '66','066' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '67','067' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '68','068' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '69','069' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '70','070' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '71','071' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '72','072' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '73','073' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '74','074' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '75','075' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '76','076' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '77','077' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '78','078' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '79','079' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '80','080' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '81','081' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '82','082' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '83','083' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '84','084' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '85','085' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '86','086' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '87','087' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '88','088' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '89','089' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '90','090' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '91','091' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '92','092' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '93','093' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '94','094' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '95','095' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '96','096' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '97','097' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '98','098' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '99','099' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '100','100' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '101','101' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '102','102' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '103','103' FROM css_networking_device_prop_def
WHERE variable_name in('event.hvac.airsys.temp.basethreshold') AND device_type_id IN ('1605');

 -- end

-- start
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '18','18' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '19','19' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '20','20' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '21','21' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '22','22' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '23','23' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '24','24' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '25','25' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '26','26' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '27','27' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '28','28' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '29','29' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '30','30' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '31','31' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '32','32' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '33','33' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '34','34' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '35','35' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '36','36' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '37','37' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '38','38' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '39','39' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '40','40' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '41','41' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '42','42' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '43','43' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '44','44' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '45','45' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '46','46' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '47','47' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '48','48' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '49','49' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '50','50' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '51','51' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '52','52' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '53','53' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '54','54' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '55','55' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '56','56' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '57','57' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '58','58' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '59','59' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '60','60' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '61','61' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '62','62' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '63','63' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '64','64' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '65','65' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '66','66' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '67','67' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '68','68' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '69','69' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '70','70' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '71','71' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '72','72' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '73','73' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '74','74' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '75','75' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '76','76' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '77','77' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '78','78' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '79','79' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '80','80' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '81','81' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '82','82' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '83','83' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '84','84' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '85','85' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '86','86' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '87','87' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '88','88' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '89','89' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '90','90' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '91','91' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '92','92' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '93','93' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '94','94' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '95','95' FROM css_networking_device_prop_def
WHERE variable_name in('hvacSystem: coolingSetpoint') AND device_type_id IN ('1606');

-- end
-- start

 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '1','01' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: leadSwapDelay') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '2','02' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: leadSwapDelay') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '3','03' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: leadSwapDelay') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '4','04' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: leadSwapDelay') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '5','05' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: leadSwapDelay') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '6','06' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: leadSwapDelay') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '7','07' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: leadSwapDelay') AND device_type_id IN ('1606');     
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '0','00' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '1','01' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '2','02' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '3','03' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '4','04' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '5','05' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '6','06' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '7','07' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '8','08' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '9','09' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '10','10' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '11','11' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '12','12' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '13','13' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '14','14' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '15','15' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '16','16' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '17','17' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '18','18' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '19','19' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '20','20' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '21','21' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '22','22' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '23','23' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '24','24' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '25','25' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '26','26' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '27','27' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '28','28' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '29','29' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '30','30' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '31','31' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '32','32' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '33','33' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '34','34' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '35','35' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '36','36' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '37','37' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '38','38' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '39','39' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '40','40' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '41','41' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '42','42' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '43','43' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '44','44' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '45','45' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '46','46' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '47','47' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '48','48' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '49','49' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '50','50' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '51','51' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '52','52' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '53','53' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '54','54' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '55','55' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '56','56' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '57','57' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '58','58' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '59','59' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '60','60' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '61','61' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '62','62' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '63','63' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '64','64' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '65','65' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '66','66' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '67','67' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '68','68' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '69','69' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '70','70' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '71','71' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '72','72' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '73','73' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '74','74' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '75','75' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '76','76' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '77','77' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '78','78' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '79','79' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '80','80' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '81','81' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '82','82' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '83','83' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '84','84' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '85','85' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '86','86' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '87','87' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '88','88' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '89','89' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '90','90' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '91','91' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '92','92' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '93','93' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '94','94' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '95','95' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '96','96' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '97','97' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '98','98' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '99','99' FROM css_networking_device_prop_def WHERE variable_name in('hvacSystem: maximumFreecoolingHumidity','hvacSystem: humidityDeadband') AND device_type_id IN ('1606');
-- END

-- START
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '0','Disabled' FROM css_networking_device_prop_def WHERE variable_name in("event.hvac.airsys.alarm.smokefire.enable","event.hvac.airsys.alarm.power.enable","event.hvac.airsys.alarm.lowroomtemp.enable","event.hvac.airsys.alarm.highroomtemp.enable","event.hvac.airsys.alarm.indoorprobe[1].enable","event.hvac.airsys.alarm.indoorprobe[2].enable","event.hvac.airsys.alarm.outdoorprobe.enable","event.hvac.airsys.alarm.humidprobe.enable","event.hvac.airsys.alarm.bothcompress.enable","event.hvac.airsys.alarm.generator.enable",				"event.hvac.airsys.alarm.connection.enable","event.hvac.airsys.alarm.clock.enable","event.hvac.airsys.alarm.plan.enable","event.hvac.airsys.alarm.supplyprobe[1].enable","event.hvac.airsys.alarm.supplyprobe[2].enable","event.hvac.airsys.alarm.damper[1].enable","event.hvac.airsys.alarm.damper[2].enable","event.hvac.airsys.alarm.lowpressure[1].enable","event.hvac.airsys.alarm.lowpressure[2].enable","event.hvac.airsys.alarm.highpressure[1].enable","event.hvac.airsys.alarm.highpressure[2].enable","event.hvac.airsys.alarm.dirtyfilter[1].enable","event.hvac.airsys.alarm.dirtyfilter[2].enable","event.hvac.airsys.alarm.evapfan[1].enable",  "event.hvac.airsys.alarm.evapfan[2].enable","hvacSystem: enableLagOveruseAlarm") AND device_type_id IN (1605,1606);		
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '1','Enabled' FROM css_networking_device_prop_def WHERE variable_name in ("event.hvac.airsys.alarm.smokefire.enable","event.hvac.airsys.alarm.power.enable","event.hvac.airsys.alarm.lowroomtemp.enable","event.hvac.airsys.alarm.highroomtemp.enable","event.hvac.airsys.alarm.indoorprobe[1].enable","event.hvac.airsys.alarm.indoorprobe[2].enable","event.hvac.airsys.alarm.outdoorprobe.enable","event.hvac.airsys.alarm.humidprobe.enable","event.hvac.airsys.alarm.bothcompress.enable","event.hvac.airsys.alarm.generator.enable",				"event.hvac.airsys.alarm.connection.enable","event.hvac.airsys.alarm.clock.enable","event.hvac.airsys.alarm.plan.enable","event.hvac.airsys.alarm.supplyprobe[1].enable","event.hvac.airsys.alarm.supplyprobe[2].enable","event.hvac.airsys.alarm.damper[1].enable","event.hvac.airsys.alarm.damper[2].enable","event.hvac.airsys.alarm.lowpressure[1].enable","event.hvac.airsys.alarm.lowpressure[2].enable","event.hvac.airsys.alarm.highpressure[1].enable","event.hvac.airsys.alarm.highpressure[2].enable","event.hvac.airsys.alarm.dirtyfilter[1].enable","event.hvac.airsys.alarm.dirtyfilter[2].enable","event.hvac.airsys.alarm.evapfan[1].enable","event.hvac.airsys.alarm.evapfan[2].enable","hvacSystem: enableLagOveruseAlarm") AND device_type_id IN (1605,1606);         
-- END
 
 
 -- start
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','evHVACAirsysHVACRotation.0','rotate_lead','Running Unit','STRING','1','1','Rotate Lead/Lag','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1605','evHVACAirsysHVACRotation.0','rotate_lead','Running Unit','STRING','1','1','Rotate Lead/Lag','1','1');
 
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rotate_lead','1606','Settings\\StatusPoints');
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('rotate_lead','1605','Settings');
 
  REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '0','Unit 1' FROM css_networking_device_prop_def WHERE variable_name in('rotate_lead') AND device_type_id IN ('1606','1605');
  REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) SELECT id, '1','Unit 2' FROM css_networking_device_prop_def WHERE variable_name in('rotate_lead') AND device_type_id IN ('1606','1605');
 
 
 -- end
 
 
 -- START
 REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 1 Low Pressure','Unit 1 Low Pressure','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 1 High Pressure','Unit 1 High Pressure','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 2 Low Pressure','Unit 2 Low Pressure','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Smoke/Fire Alarm','Smoke/Fire Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 2 High Pressure','Unit 2 High Pressure','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','High Temp Alarm','High Temp Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Low Temp Alarm','Low Temp Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','AC Loss Alarm','AC Loss Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 1 Fan Overload/AC Loss','Unit 1 Fan Overload/AC Loss','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 2 Fan Overload/AC Loss','Unit 2 Fan Overload/AC Loss','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 1 Dirty Air Filter','Unit 1 Dirty Air Filter','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 2 Dirty Air Filter','Unit 2 Dirty Air Filter','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Clockcard Alarm','Clockcard Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Backup Indoor Temp Sensor Alarm','Backup Indoor Temp Sensor Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Two Compressor Run','Two Compressor Run','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 1 Damper Alarm','Unit 1 Damper Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 2 Damper Alarm','Unit 2 Damper Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 2 Supply Air Temperature Sensor Alarm','Unit 2 Supply Air Temperature Sensor Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Airflow Alarm','Airflow Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Configuration Mismatch Detected','Configuration Mismatch Detected','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','pLAN Alarm','pLAN Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Humidity Sensor Alarm','Humidity Sensor Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 1 Indoor Temp Sensor Alarm','Unit 1 Indoor Temp Sensor Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Outdoor Temp Sensor Alarm','Outdoor Temp Sensor Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 1 Supply Air Temperature Sensor Alarm','Unit 1 Supply Air Temperature Sensor Alarm','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Generator Mode','Generator Mode','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 1 Outlet: Critically Low Temperature is less than 32.0','Unit 1 Outlet: Critically Low Temperature is less than 32.0','Alarm status Active/Inactive','STRING','0','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,prop_type_id,snmp_oid,variable_name,name,tooltip,data_type,editable,visible,thresh_enable) VALUES ('1606','2','','Unit 2 Outlet: Critically Low Temperature is less than 32.0','Unit 2 Outlet: Critically Low Temperature is less than 32.0','Alarm status Active/Inactive','STRING','0','1','1');

  REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Alarms\\\Status','Alarms\\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Low Pressure','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 High Pressure','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Low Pressure','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Smoke/Fire Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 High Pressure','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('High Temp Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Low Temp Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('AC Loss Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Fan Overload/AC Loss','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Fan Overload/AC Loss','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Dirty Air Filter','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Dirty Air Filter','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Clockcard Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Backup Indoor Temp Sensor Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Two Compressor Run','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Damper Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Damper Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Supply Air Temperature Sensor Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Airflow Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Configuration Mismatch Detected','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('pLAN Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Humidity Sensor Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Indoor Temp Sensor Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Outdoor Temp Sensor Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Supply Air Temperature Sensor Alarm','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Generator Mode','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Outlet: Critically Low Temperature is less than 32.0','1606','Alarms\\Status');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Outlet: Critically Low Temperature is less than 32.0','1606','Alarms\\Status');
 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Communication Error',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Configuration Mismatch',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Controller Critical Alarm',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Humidity too high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Humidity too low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Power failure detected',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Software compatibility alarm',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Temperature Critically high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Temperature Critically high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Temperature Critically low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Temperature Critically low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Temperature too high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Temperature too low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Temperature very high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Intake: Temperature Critically high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Intake: Temperature Critically high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Intake: Temperature Critically low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Intake: Temperature Critically low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Intake: Temperature too high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Intake: Temperature too low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Intake: Temperature very high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Outlet: Temperature Critically high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Outlet: Temperature Critically high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Outlet: Temperature Critically low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Outlet: Temperature Critically low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Outlet: Temperature too high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Outlet: Temperature too low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Outlet: Temperature very high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Intake: Temperature Critically high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Intake: Temperature Critically high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Intake: Temperature Critically low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Intake: Temperature Critically low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Intake: Temperature too high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Intake: Temperature too low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Intake: Temperature very high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Outlet: Temperature Critically high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Outlet: Temperature Critically high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Outlet: Temperature Critically low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Outlet: Temperature Critically low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Outlet: Temperature too high',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Outlet: Temperature too low',1606,'Alarms\\Status'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Outlet: Temperature very high',1606,'Alarms\\Status'); 

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 1 Dirty Air Filter Status','Unit 1 Dirty Air Filter Status','STRING','0','2','Unit 1 Dirty Air Filter Status','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Unit 2 Dirty Air Filter Status','Unit 2 Dirty Air Filter Status','STRING','0','2','Unit 2 Dirty Air Filter Status','1','1');
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 1 Dirty Air Filter Status',1606,'UnitStatus&Statistics'); 
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Unit 2 Dirty Air Filter Status',1606,'UnitStatus&Statistics'); 
 
 REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','backup_Temperature','Indoor Backup Temperature (F)','INTEGER','0','2','Indoor Backup Temperature (F)','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Supply_Temperature_Unit1','Supply Temperature Unit 1 (F)','INTEGER','0','2','Supply Temperature Unit 1 (F)','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','Supply_Temperature_Unit2','Supply Temperature Unit 2 (F)','INTEGER','0','2','Supply Temperature Unit 2 (F)','1','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name,name, data_type,editable,prop_type_id,tooltip,visible,thresh_enable) VALUES ('1606','','outdoor_Temperature','Outdoor Temperature (F)','INTEGER','0','2','Outdoor Temperature (F)','1','1');
 REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('backup_Temperature',1606,'Temperature&Humidity'); 
  REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Supply_Temperature_Unit1',1606,'Temperature&Humidity'); 
   REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Supply_Temperature_Unit2',1606,'Temperature&Humidity'); 
    REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('outdoor_Temperature',1606,'Temperature&Humidity'); 
	
	UPDATE css_networking_device_prop_def SET visible=0 WHERE device_type_id=1606 AND variable_name IN ('hvac1OutletTemperature__Temperature','hvac1IntakeTemperature__Temperature','hvac2IntakeTemperature__Temperature','hvac2OutletTemperature__Temperature');
 -- END
 
RAWSQL
            );

            // R7.2.3 - B6642
            DB::unprepared(<<<RAWSQL
UPDATE css_alarms_dictionary SET is_offline ='1' WHERE (device_type_id = '1291' AND alarm_description = 'Initial device authentication failed.');
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
