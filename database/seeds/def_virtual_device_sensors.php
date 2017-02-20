<?php

    use Illuminate\Database\Seeder;
    use Illuminate\Database\Eloquent\Model;

    class def_virtual_device_sensors extends Seeder
    {

        public function run()
        {
            DB::table('def_virtual_device_sensors')->delete();

            $items = array(
                array(
                    'template_id'            => '1',
                    'included_sensors'       => '1083'
                ),
                array(
                    'template_id'            => '2',
                    'included_sensors'       => '1082'
                ),
                array(
                    'template_id'            => '2',
                    'included_sensors'       => '1083'
                ),
                array(
                    'template_id'            => '3',
                    'included_sensors'       => '1083'
                ),
                array(
                    'template_id'            => '3',
                    'included_sensors'       => '1084'
                ),
                array(
                    'template_id'            => '4',
                    'included_sensors'       => '1082'
                ),
                array(
                    'template_id'            => '4',
                    'included_sensors'       => '1083'
                ),
                array(
                    'template_id'            => '4',
                    'included_sensors'       => '1084'
                )
            );

            DB::table('def_virtual_device_sensors')->insert($items);
        }
    }