<?php
use Illuminate\Database\Migrations\Migration;

/**
 * Add uuid and sequence to css_networking_network_prop table
 */
class SetUuidForNonSitegateDevices extends Migration {
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (env ( 'C2_SERVER_TYPE' ) != 'sitegate') {
            DB::unprepared ( 
                    <<<RAWSQL
-- Add UUID to alarms on non sitegate device which have UUID equal null
    UPDATE css_networking_device_alarm as cnda
     JOIN css_networking_device as cnd ON cnda.device_id = cnd.id
     SET cnda.uuid = uuid()
    WHERE cnda.uuid is null and cnd.is_siteportal_device = 0                    
RAWSQL
 );
            DB::unprepared ( 
                    <<<RAWSQL
-- Set UUID to null for alarms on itegate device hich have non null UUID
    UPDATE css_networking_device_alarm as cnda
     JOIN css_networking_device as cnd
     ON cnda.device_id = cnd.id
     SET cnda.uuid = null
    WHERE cnda.uuid is not null and cnd.is_siteportal_device = 1
RAWSQL
 );
            DB::unprepared ( 
                    <<<RAWSQL
-- Add UUID to alarms on non sitegate device which have UUID equal null
    UPDATE css_networking_network_tree as cnnt
     JOIN css_networking_device as cnd ON cnnt.device_id = cnd.id
     SET cnnt.uuid = uuid()
    WHERE cnnt.uuid is null and cnd.is_siteportal_device = 0;
RAWSQL
 );
        }
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        // SiteGate only!
    }
}
