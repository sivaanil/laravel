<?php

    use Illuminate\Database\Seeder;
    use Illuminate\Database\Eloquent\Model;

    class def_grid extends Seeder
    {

        public function run()
        {
            DB::table('def_grid')->delete();

            $items = array(
                array(
                    'grid_id'           => 'alarm',
                    'class_column_id'   => 'severity',
                    'has_button_column' => 1
                )
            );

            DB::table('def_grid')->insert($items);
        }
    }
