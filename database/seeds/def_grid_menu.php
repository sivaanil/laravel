<?php

    use Illuminate\Database\Seeder;
    use Illuminate\Database\Eloquent\Model;

    class def_grid_menu extends Seeder
    {

        public function run()
        {
            DB::table('def_grid_menu')->delete();

            $items = array(
                array(
                    'module_id'         => 'alarm',
                    'item_id'           => 'wedInterface',
                    'action'            => "",
                    'flag_two_exponent' => 0,
                    'order'             => 1
                ),
                array(
                    'module_id'         => 'alarm',
                    'item_id'           => 'scanDevice',
                    'action'            => "",
                    'flag_two_exponent' => 1,
                    'order'             => 2
                ),
                array(
                    'module_id'         => 'alarm',
                    'item_id'           => 'alarmScan',
                    'action'            => "",
                    'flag_two_exponent' => 2,
                    'order'             => 3
                ),
                array(
                    'module_id'         => 'alarm',
                    'item_id'           => 'propScan',
                    'action'            => "",
                    'flag_two_exponent' => 3,
                    'order'             => 4
                ),
                array(
                    'module_id'         => 'alarm',
                    'item_id'           => 'stopScan',
                    'action'            => "",
                    'flag_two_exponent' => 4,
                    'order'             => 5
                ),
                array(
                    'module_id'         => 'alarm',
                    'item_id'           => 'stopAlarmScan',
                    'action'            => "",
                    'flag_two_exponent' => 5,
                    'order'             => 6
                ),
                array(
                    'module_id'         => 'alarm',
                    'item_id'           => 'stopPropScan',
                    'action'            => "",
                    'flag_two_exponent' => 6,
                    'order'             => 7
                ),
                array(
                    'module_id'         => 'alarm',
                    'item_id'           => 'startScan',
                    'action'            => "",
                    'flag_two_exponent' => 7,
                    'order'             => 8
                ),
                array(
                    'module_id'         => 'alarm',
                    'item_id'           => 'startAlarmScan',
                    'action'            => "",
                    'flag_two_exponent' => 8,
                    'order'             => 9
                ),
                array(
                    'module_id'         => 'alarm',
                    'item_id'           => 'startPropScan',
                    'action'            => "",
                    'flag_two_exponent' => 9,
                    'order'             => 10
                ),
                array(
                    'module_id'         => 'alarm',
                    'item_id'           => 'ignore',
                    'action'            => "",
                    'flag_two_exponent' => 10,
                    'order'             => 11
                ),
                array(
                    'module_id'         => 'alarm',
                    'item_id'           => 'unignore',
                    'action'            => "",
                    'flag_two_exponent' => 11,
                    'order'             => 12
                ),
                array(
                    'module_id'         => 'alarm',
                    'item_id'           => 'ack',
                    'action'            => "",
                    'flag_two_exponent' => 12,
                    'order'             => 13
                ),
            );


            DB::table('def_grid_menu')->insert($items);
        }
    }
