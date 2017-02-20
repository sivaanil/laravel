<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualDeviceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'map_virtual_device',
            'def_virtual_device_structure',
            'data_virtual_device',
            'data_virtual_device',
            'data_virtual_device_property_filter',
            'data_property_filter_entry',
        ];

        foreach ($tables as $t) {
            if (Schema::hasTable($t)) {
                Schema::drop($t);
            }
        }

        Schema::create('map_virtual_device', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('virtual_node_id');
            $table->integer('device_node_id');
            $table->timestamps();
        });
        Schema::create("def_virtual_device_structure", function(Blueprint $table) {
            $table->increments('id');
            $table->text('structure');
            $table->timestamps();
        });
        //default device template - can't build a virtual device without this entry
        DB::unprepared('INSERT INTO def_virtual_device_structure (structure, created_at, updated_at) VALUES '
                . '(\'[{"type":5057,"children":[{"type":5057,"children":[{"type":5057,"children":[]},{"type":5057,"children":[]}]},{"type":5057,"children":[{"type":5057,"children":[]},{"type":5057,"children":[]}]},{"type":5057,"children":[]},{"type":5057,"children":[]}]}]\', '
                . 'NOW(), NOW())');

        Schema::create('data_virtual_device', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id');
            $table->timestamps();
        });
        Schema::create('data_virtual_device_property_filter', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('virtual_device_id');
            $table->timestamps();
        });
        Schema::create('data_property_filter_entry', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('filter_id');
            $table->integer('prop_def_id');
            $table->integer('prop_type_id');
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
        Schema::drop('map_virtual_device');
        Schema::drop('data_virtual_device');
        Schema::drop('data_property_filter_entry');
        Schema::drop('data_virtual_device_property_filter');
        Schema::drop('def_virtual_device_structure');
    }
}
