<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataScheduledTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('data_scheduled_tasks')) {
            Schema::drop('data_scheduled_tasks');
        }
        Schema::create('data_scheduled_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id');
            $table->string('type', 35);
            $table->string('command', 35);
            $table->timestamp('next_run_time');
            $table->string('mode', 35);
            $table->integer('enabled');
            $table->integer('duration');
            $table->integer('recurrence');
            $table->timestamp('start_time');
            $table->timestamp('stop_time');
            $table->string('day_of_week', 60);
            $table->string('month_of_year', 60);
            $table->string('on_the_mode', 60)->nullable();
            $table->string('each_mode', 60)->nullable();
            $table->string('repeat_mode', 35);
            $table->integer('repeat_counter');
            $table->timestamp('date_updated');
            $table->timestamp('queued');
            $table->timestamp('executed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('data_scheduled_tasks');
    }
}

