<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualDeviceTemplateList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::dropIfExists('def_virtual_device_templates');
        Schema::create('def_virtual_device_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("type_id");
            $table->text("name");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('def_virtual_device_templates');
    }
}
