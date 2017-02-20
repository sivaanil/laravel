<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DisableSitegateScanAndClearAlarms extends Migration
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
            DB::unprepared("
            UPDATE css_networking_device SET scan_enabled=0 WHERE id = 1;
            DELETE * from css_networking_device_alarm WHERE device_id = 1;
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
        // unspecified
    }
}
