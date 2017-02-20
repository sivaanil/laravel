<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyingPropertyFilterStructureForVirtualDevices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('data_property_filter_entry');
        Schema::dropifExists('data_virtual_device_property_filter');

        Schema::create('data_virtual_device_property_filter', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('virtual_device_id');
            $table->integer('prop_def_id');
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
        //there's no reversing this one
    }
}
