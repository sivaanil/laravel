<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateDefFilterQuery extends Migration
    {

        public function up()
        {
            if (!Schema::hasTable('def_filter_query')) {
                Schema::create('def_filter_query', function ($t) {
                    $t->integer('id');
                    $t->string('filter_selected_condition', 20)->default("=");
                    $t->string('filter_value', 64);
                    $t->string('db_column_name', 64);
                    $t->string('db_table_name', 64);
                    //$t->primary('id');
                    $t->engine = 'InnoDB';
                });
            }
        }

        public function down()
        {
            Schema::drop('def_filter_query');
        }

    }
