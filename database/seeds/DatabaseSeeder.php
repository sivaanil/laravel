<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // $this->call('UserTableSeeder');
        $this->call('def_grid');
        $this->call('def_grid_columns');
        $this->call('def_filter_options');
        $this->call('def_filter_query');
        $this->call('def_grid_menu');
        //$this->call('css_authentication_user');
        $this->call('def_virtual_device_templates');
        $this->call('def_virtual_device_sensors');

    }

}
