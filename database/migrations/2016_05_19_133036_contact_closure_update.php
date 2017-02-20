<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContactClosureUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Raw SQL from SitePortal update script
        if (env('C2_SERVER_TYPE') == 'sitegate') {

        DB::unprepared("

            -- START
            SET foreign_key_checks = 0;
            REPLACE INTO css_networking_device_type
            (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children, main_device,build_file,scan_file,controller_file)
            VALUES
            (1616,2,'SHJ','RTU',1,0,0,1,1,'shj_builder_launcher.php','shj_scanner_launcher.php','shjController.php');

            REPLACE INTO css_networking_device_type
            (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
            VALUES
            (1612,2,'SHJ','S5138',0,0,0,'shjController.php');

            REPLACE INTO css_networking_device_type
            (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
            VALUES
            (1613,2,'SHJ','S5302',0,0,0,'shjController.php');

            REPLACE INTO css_networking_device_type
            (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
            VALUES
            (1614,2,'SHJ','S3308',0,0,0,'shjController.php');

            REPLACE INTO css_networking_device_type
            (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
            VALUES
            (1615,2,'SHJ','S5140',0,0,0,'shjController.php');

            REPLACE INTO css_networking_device_type
            (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
            VALUES
            (1617,2,'SHJ','Contact Closures',0,0,0,'shjController.php');

            REPLACE INTO css_networking_device_type
            (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
            VALUES
            (1618,2,'SHJ','Analog',0,0,0,'shjController.php');

            REPLACE INTO css_networking_device_type
            (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
            VALUES
            (1619,2,'SHJ','Relays',0,0,0,'shjController.php');

            REPLACE INTO css_networking_device_type
            (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
            VALUES
            (1620,1083,'SHJ','Contact Closure',0,0,0,'shjController.php');

            REPLACE INTO css_networking_device_type
            (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
            VALUES
            (1621,1082,'SHJ','Relay',0,0,0,'shjController.php');

            REPLACE INTO css_networking_device_type
            (id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
            VALUES
            (1622,1084,'SHJ','Analog Input',0,0,0,'shjController.php');
            -- END
            SET foreign_key_checks = 1;




            -- START
            INSERT IGNORE INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1616,'telnet','MODBUS',502);
            -- END
            ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
