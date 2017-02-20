<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CssAuthenticationUserPrefUnified extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('css_authentication_user_pref_unified')) {
            Schema::create('css_authentication_user_pref_unified', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('variable_name', 256);
                $table->text('value');
                $table->dateTime('updated_at');
                $table->dateTime('created_at');
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
        Schema::drop('css_authentication_user_pref_unified');
    }
}

