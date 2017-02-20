<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualDeviceTextThreshold extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::dropIfExists('data_virtual_device_text_threshold');
        Schema::create('data_virtual_device_text_threshold', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("virtual_device_id");
            $table->integer("prop_def_id");
            $table->integer("alarm_on_match");
            $table->float("case_sensitive");
            $table->float("text");
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
        Schema::drop('data_virtual_device_text_threshold');
    }
}
