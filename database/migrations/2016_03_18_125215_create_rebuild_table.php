<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRebuildTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('css_networking_rebuild', function (Blueprint $t) {
            $t->increments('id')->unsigned();
            $t->integer('device_id');
            $t->datetime('start_timestamp');
            $t->datetime('end_timestamp');
            $t->text('message');
            $t->tinyInteger('success');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop table
        Schema::drop('css_networking_rebuild');
    }

}
