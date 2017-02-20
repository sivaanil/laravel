<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateDefGridMenuQueryTable extends Migration
    {

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            if (!Schema::hasTable('def_grid_menu')) {
                Schema::create('def_grid_menu', function ($t) {

                    $t->string('module_id', 20);
                    $t->string('item_id', 20);
                    $t->string('action', 100);
                    $t->primary(array('module_id', 'item_id'));
                    $t->engine = 'InnoDB';
                });
                Schema::table('def_grid_menu', function (Blueprint $t) {
                    //
                    $t->integer('flag_two_exponent');
                    $t->integer('order')->nullable();
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
            Schema::drop('def_grid_menu');
        }

    }
