<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class AddRememberTokenAuthenticationUser extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            if (!Schema::hasColumn('css_authentication_user', 'remember_token')) {
                Schema::table('css_authentication_user', function (Blueprint $table) {
                    //
                    $table->string('remember_token', 100)->nullable();
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
            Schema::table('css_authentication_user', function (Blueprint $table) {
                //
                $table->dropColumn('remember_token');
            });
        }

    }
