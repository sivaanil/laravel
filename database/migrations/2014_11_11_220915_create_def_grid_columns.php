<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateDefGridColumns extends Migration
    {

        public function up()
        {
            if (!Schema::hasTable('def_grid_columns')) {
                Schema::create('def_grid_columns', function ($t) {
                    $t->string('grid_id', 20);
                    $t->string('column_id', 20);
                    $t->string('column_display_text', 100);
                    $t->string('column_header_alignment', 10);
                    $t->string('column_cell_alignment', 10);
                    $t->integer('column_min_width');
                    $t->tinyInteger('column_static_width');
                    $t->string('column_data_type', 20);
                    $t->string('column_date_format', 40);
                    $t->tinyInteger('column_visiable');
                    $t->tinyInteger('column_editable');
                    $t->tinyInteger('column_detail_area_only');
                    $t->string('db_column_name', 64);
                    $t->string('db_table_name', 64);
                    $t->integer('column_order_priority');
                    $t->primary(array('grid_id', 'column_id'));
                    $t->engine = 'InnoDB';
                });
            }
        }

        public function down()
        {
            Schema::drop('def_grid_columns');
        }

    }
