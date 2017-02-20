<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualDeviceThreshold extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('data_virtual_device_threshold');
        Schema::create('data_virtual_device_threshold', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("virtual_device_id");
            $table->integer("prop_def_id");
            $table->integer("alarm_inclusive");
            $table->float("lower_bound");
            $table->float("upper_bound");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('data_virtual_device_threshold');
    }
}
