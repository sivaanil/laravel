<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AssignProperScannerLauncherToVirtualDeviceType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $deviceEntry = \Unified\Models\DeviceType::find(5056);//virtual device type id!
        $deviceEntry->scan_file = 'VirtualDeviceScannerLauncher.php';
        $deviceEntry->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $deviceEntry = \Unified\Models\DeviceType::find(5056);//virtual device type id!
        $deviceEntry->scan_file = '';
        $deviceEntry->save();
    }
}
