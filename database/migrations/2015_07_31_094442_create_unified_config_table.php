<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnifiedConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('unified_config')){
        Schema::create('unified_config', function (Blueprint $table)
		{
            $table->string('name', 25)->unique();
            $table->string('value', 50)->notNull();
            $table->text('description')->nullable();
            $table->primary('name');
            $table->engine = 'InnoDB';

        });

        DB::table('unified_config')->insert([
            'name'        => 'current_version',
            'value'       => '0.0.0.0',
            'description' => 'Current Release of code on the sitegate'
        ]);

        DB::table('unified_config')->insert([
            'name'        => 'upgrade_version',
            'value'       => '0.0.0.0',
            'description' => 'Release to be installed on the sitegate'
        ]);

        DB::table('unified_config')->insert([
            'name'        => 'current_branch_type',
            'value'       => '',
            'description' => 'Current Branch Status of code on the sitegate'
        ]);

        DB::table('unified_config')->insert([
            'name'        => 'upgrade_branch_type',
            'value'       => '',
            'description' => 'Update Branch Status to be installed on the sitegate'
        ]);

        DB::table('unified_config')->insert([
            'name'        => 'process_update',
            'value'       => '0',
            'description' => 'Flag for informing the system to update during its nightly backup.'
        ]);

        DB::table('unified_config')->insert([
            'name'        => 'message',
            'value'       => '0',
            'description' => '50 char text block for a status message.'
        ]);

        DB::table('unified_config')->insert([
            'name'        => 'disabled',
            'value'       => '0',
            'description' => 'Flag if the device has been disabled by C-Squared.'
        ]);
        DB::table('unified_config')->insert([
            'name'        => 'force_update',
            'value'       => '0',
            'description' => 'Flag if the device has been told to force update.'
        ]);
    }}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('unified_config');
    }
}
