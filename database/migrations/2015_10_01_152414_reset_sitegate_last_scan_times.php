<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ResetSiteGateLastScanTimes extends Migration
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
              UPDATE css_networking_device SET last_scan = '2000-01-01 00:00:00', last_alarms_scan = '2000-01-01 00:00:00' WHERE id = 1
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
        // one way
    }
}
