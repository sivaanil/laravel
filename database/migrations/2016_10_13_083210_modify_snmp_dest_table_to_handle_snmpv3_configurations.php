<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySnmpDestTableToHandleSnmpv3Configurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared ( 
<<<RAWSQL
DROP PROCEDURE IF EXISTS modify_snmp_dest_to_support_v3;
CREATE PROCEDURE modify_snmp_dest_to_support_v3()
BEGIN
                
        IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
                table_name = 'css_snmp_dest'
                AND table_schema = DATABASE ()
                AND column_name = 'SNMPauthType') = 0) THEN
                -- add uuid to the table
                ALTER TABLE css_snmp_dest ADD COLUMN SNMPauthType varchar(32) after snmp_version_id;
        END IF;
        IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
                table_name = 'css_snmp_dest'
                AND table_schema = DATABASE ()
                AND column_name = 'SNMPuserName') = 0) THEN
                -- add uuid to the table
                ALTER TABLE css_snmp_dest ADD COLUMN SNMPuserName varchar(32) after SNMPauthType;
        END IF;
        IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
                table_name = 'css_snmp_dest'
                AND table_schema = DATABASE ()
                AND column_name = 'SNMPauthPassword') = 0) THEN
                -- add uuid to the table
                ALTER TABLE css_snmp_dest ADD COLUMN SNMPauthPassword varchar(64) after SNMPuserName;
        END IF;
        IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
                table_name = 'css_snmp_dest'
                AND table_schema = DATABASE ()
                AND column_name = 'SNMPauthEncryption') = 0) THEN
                -- add uuid to the table
                ALTER TABLE css_snmp_dest ADD COLUMN SNMPauthEncryption varchar(32) after SNMPauthPassword;
        END IF;
        IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
                table_name = 'css_snmp_dest'
                AND table_schema = DATABASE ()
                AND column_name = 'SNMPprivPassword') = 0) THEN
                -- add uuid to the table
                ALTER TABLE css_snmp_dest ADD COLUMN SNMPprivPassword varchar(64) after SNMPauthEncryption;
        END IF;
        IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
                table_name = 'css_snmp_dest'
                AND table_schema = DATABASE ()
                AND column_name = 'SNMPprivEncryption') = 0) THEN
                -- add uuid to the table
                ALTER TABLE css_snmp_dest ADD COLUMN SNMPprivEncryption varchar(32) after SNMPprivPassword;
        END IF;
        IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
                table_name = 'css_snmp_dest'
                AND table_schema = DATABASE ()
                AND column_name = 'SNMPengineId') = 0) THEN
                -- add uuid to the table
                ALTER TABLE css_snmp_dest ADD COLUMN SNMPengineId varchar(24) after SNMPprivEncryption;
        END IF;
END;
CALL modify_snmp_dest_to_support_v3();
DROP PROCEDURE IF EXISTS modify_snmp_dest_to_support_v3;
RAWSQL
         );
    }
                
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('css_snmp_dest', function (Blueprint $table) {
            if (Schema::hasColumn('css_snmp_dest', 'SNMPauthType')) {
                $table->dropColumn('SNMPauthType');
            }
            if (Schema::hasColumn('css_snmp_dest', 'SNMPuserName')) {
                $table->dropColumn('SNMPuserName');
            }
            if (Schema::hasColumn('css_snmp_dest', 'SNMPauthPassword')) {
                $table->dropColumn('SNMPauthPassword');
            }
            if (Schema::hasColumn('css_snmp_dest', 'SNMPauthEncryption')) {
                $table->dropColumn('SNMPauthEncryption');
            }
            if (Schema::hasColumn('css_snmp_dest', 'SNMPprivPassword')) {
                $table->dropColumn('SNMPprivPassword');
            }
            if (Schema::hasColumn('css_snmp_dest', 'SNMPprivEncryption')) {
                $table->dropColumn('SNMPprivEncryption');
            }
            if (Schema::hasColumn('css_snmp_dest', 'SNMPengineId')) {
                $table->dropColumn('SNMPengineId');
            }
        });
    }
}
