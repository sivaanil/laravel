<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateDefGrid extends Migration
    {

        public function up()
        {
            if (!Schema::hasTable('def_grid')) {
                Schema::create('def_grid', function ($t) {
                    $t->string('grid_id', 20)->unique();
                    $t->string('class_column_id', 20);
                    $t->primary('grid_id');
                    $t->engine = 'InnoDB';
                });
            }
        }

        public function down()
        {
            Schema::drop('def_grid');
        }

    }
