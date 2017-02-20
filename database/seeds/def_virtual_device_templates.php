<?php

    use Illuminate\Database\Seeder;
    use Illuminate\Database\Eloquent\Model;

    class def_virtual_device_templates extends Seeder
    {

        public function run()
        {
            DB::table('def_virtual_device_templates')->delete();

            $items = array(
                array(
                    'id'            => '1',
                    'type_id'       => '5063',
                    'name'          => 'Monitor Only'
                ),
                array(
                    'id'            => '2',
                    'type_id'       => '5063',
                    'name'          => 'Monitor and Control'
                ),
                array(
                    'id'            => '3',
                    'type_id'       => '5063',
                    'name'          => 'Monitor Only with Fuel Sensor'
                ),
                array(
                    'id'            => '4',
                    'type_id'       => '5063',
                    'name'          => 'Monitor and Control with Fuel Sensor'
                )
            );

            DB::table('def_virtual_device_templates')->insert($items);
        }
    }
