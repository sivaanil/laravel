<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualDeviceSensorList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::dropIfExists('def_virtual_device_sensors');
        Schema::create('def_virtual_device_sensors', function (Blueprint $table) {
            $table->integer('template_id');
            $table->integer("included_sensors");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('def_virtual_device_sensors');
    }
}
