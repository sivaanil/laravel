<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VirtualDeviceTypeAddition extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('css_networking_device_type')->delete('5056');
        DB::table('css_networking_device_type')->delete('5057');

        DB::table('css_networking_device_type')->insert(
                [ 'id' => '5063',
                    'class_id' => '1077',
                    'vendor' => 'C Squared Systems',
                    'model' => 'External RTU',
                    'auto_build_enabled' => '1',
                    'defaultWebUiUser' => '(NOT IN USE)',
                    'defaultWebUiPw' => '1146ac27939eb9e2397f552ee5e53a6b',
                    'defaultSNMPRead' => 'eea478269a2bbc0498ac382ed500822c',
                    'defaultSNMPWrite' => 'eea478269a2bbc0498ac382ed500822c',
                    'main_device' => '1',
                    'build_file' => 'VirtualDeviceBuilderLauncher.php',
                ]
        );
        DB::table('css_networking_device_type')->insert(
                [ 'id' => '5064',
                    'class_id' => '1128',
                    'vendor' => 'C Squared Systems',
                    'model' => 'Virtual Sub-Device',
                    'auto_build_enabled' => '0',
                    'defaultWebUiUser' => '(NOT IN USE)',
                    'defaultWebUiPw' => '1146ac27939eb9e2397f552ee5e53a6b',
                    'defaultSNMPRead' => 'eea478269a2bbc0498ac382ed500822c',
                    'defaultSNMPWrite' => 'eea478269a2bbc0498ac382ed500822c',
                    'main_device' => '0',
                    'build_file' => '',
                ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('css_networking_device_type')->delete('5063');
        DB::table('css_networking_device_type')->delete('5064');
    }

}
