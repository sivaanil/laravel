<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateDefFilterOptions extends Migration
    {

        public function up()
        {
            if (!Schema::hasTable('def_filter_options')) {
                Schema::create('def_filter_options', function ($t) {
                    $t->increments('id');
                    $t->string('filter_module', 20);
                    $t->string('filter_group', 20);
                    $t->string('filter_id', 20);
                    $t->integer('filter_type')->default(1);
                    $t->tinyInteger('filter_visiable')->default(1);;
                    $t->tinyInteger('filter_editable')->default(1);;
                    $t->tinyInteger('filter_parent')->default(0);;
                    $t->integer('filter_order');
                    $t->string('default_state', 5);
                    $t->string('db_table_name', 64);
                    //$t->primary('id');
                    $t->unique(array('filter_module', 'filter_group', 'filter_id'));
                    $t->engine = 'InnoDB';
                });
            }
        }

        public function down()
        {
            Schema::drop('def_filter_options');
        }

    }
