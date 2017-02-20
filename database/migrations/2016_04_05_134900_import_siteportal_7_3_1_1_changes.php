<?php

// brings cswapi database from 7.3.1 to 7.3.1.1
// (SiteGate 2.6 to SiteGate 2.7)

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportSiteportal7311Changes extends Migration
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
            // R7.3.1.1 - B5230
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
            // R7.3.1.1 - B6566
            DB::unprepared(<<<RAWSQL
-- --------------------------------------------------------------------------------------------------------------------------
-- Bug 6566 - We a removing duplicate custom property values and adding an unique index to prevent new ones from being added.
-- We will take the newest record.
-- --------------------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS tmp_custom_prop_table;
DROP TABLE IF EXISTS css_networking_device_custom_prop_BAK_7_3_1_1;

CREATE TABLE css_networking_device_custom_prop_BAK_7_3_1_1 LIKE css_networking_device_custom_prop;

CREATE TABLE tmp_custom_prop_table AS
SELECT *
FROM css_networking_device_custom_prop
GROUP BY custom_prop_def_id, node_id
ORDER BY date_updated DESC;

DELETE cp.* 
FROM css_networking_device_custom_prop cp
LEFT JOIN tmp_custom_prop_table tcp ON tcp.id = cp.id
WHERE tcp.id IS NULL;

DROP TABLE IF EXISTS tmp_custom_prop_table;

SELECT IF (
    EXISTS (
	SELECT DISTINCT index_name FROM information_schema.statistics 
        WHERE table_schema = DATABASE() AND table_name = 'css_networking_device_custom_prop' AND index_name = 'css_networking_device_custom_prop_custom_prop_def_id_node_id'
	)
    ,'SELECT ''INDEX css_networking_device_custom_prop_custom_prop_def_id_node_id EXISTS'' _______;'
    ,'ALTER TABLE css_networking_device_custom_prop ADD UNIQUE INDEX css_networking_device_custom_prop_custom_prop_def_id_node_id(custom_prop_def_id, node_id) USING BTREE') INTO @a;
PREPARE stmt1 FROM @a;
EXECUTE stmt1;
DEALLOCATE PREPARE stmt1;
-- -------------------------------------------------------------------------------------------
-- End of Script
-- -------------------------------------------------------------------------------------------

RAWSQL
            );
            // R7.3.1.1 - B7634
            DB::unprepared(<<<RAWSQL
-- --------------------------------------------------------------------------------------------------------
-- Bug 7634: SNMP Notification - Queue not being populated
-- Setting defaults for the SNMP notification table to 1 for 'send_outage' and 'send_degradation' columns.
-- Also updating existing SNMP notifications to set send_outage and send_degradation to 1.
-- Full feature functionality will be in bug 7637
-- --------------------------------------------------------------------------------------------------------
ALTER TABLE css_snmp_notification MODIFY COLUMN send_outage  tinyint(4) NOT NULL DEFAULT 1 AFTER send_perimeter;
ALTER TABLE css_snmp_notification MODIFY COLUMN send_degradation  tinyint(4) NOT NULL DEFAULT 1 AFTER send_outage;
UPDATE `css_snmp_notification` SET `send_outage`='1', `send_degradation`='1';
-- --------------------------------------------------------------------------------------------------------
-- End of script
-- --------------------------------------------------------------------------------------------------------

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
