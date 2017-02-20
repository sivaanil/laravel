<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrowserSlotsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('browser_slots')) {
            Schema::create('browser_slots', function (Blueprint $table) {
                $table->integer('id');
                $table->string('session_id', 40);
                $table->string('connection_id', 40);
                $table->integer('last_activity_at');
                $table->integer('status');
                $table->integer('row_version');
                $table->primary('id');
                $table->unique('connection_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('browser_slots');
    }

}
