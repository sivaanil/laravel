<?php

    use Illuminate\Database\Seeder;
    use Illuminate\Database\Eloquent\Model;

    class def_filter_query extends Seeder
    {

        public function run()
        {
            //(SELECT id from def_filter_options where filter_module='alarm' and filter_group = 'delayed' and filter_id = 'includeDelayed')


            DB::table('def_filter_query')->delete();

            $mod = 'alarm';
            $gm = 'method';
            $gp = 'priority';
            $gs = 'state';
            $gi = 'ignored';
            $gd = 'delayed';
            $items = array(
                //method
                array(
                    'id'                        => $this->getId($mod, $gm, 'allmethods'),
                    'filter_selected_condition' => 'in',
                    'filter_value'              => '0,1',
                    'db_column_name'            => 'is_trap',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gm, 'polledalarms'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '0',
                    'db_column_name'            => 'is_trap',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gm, 'traps'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '1',
                    'db_column_name'            => 'is_trap',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                //priority
                array(
                    'id'                        => $this->getId($mod, $gp, 'allpriorities'),
                    'filter_selected_condition' => 'in',
                    'filter_value'              => '1,2,3,4,6',
                    'db_column_name'            => 'severity_id',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gp, 'critical'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '1',
                    'db_column_name'            => 'severity_id',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gp, 'major'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '2',
                    'db_column_name'            => 'severity_id',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gp, 'minor'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '3',
                    'db_column_name'            => 'severity_id',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gp, 'warning'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '4',
                    'db_column_name'            => 'severity_id',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gp, 'info'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '6',
                    'db_column_name'            => 'severity_id',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                //state
                array(
                    'id'                        => $this->getId($mod, $gs, 'allstates'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '0',
                    'db_column_name'            => 'cleared_bit',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gs, 'allstates'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '1',
                    'db_column_name'            => 'cleared_bit',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gs, 'active'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '0',
                    'db_column_name'            => 'cleared_bit',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gs, 'cleared'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '1',
                    'db_column_name'            => 'cleared_bit',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                //ignored
                array(
                    'id'                        => $this->getId($mod, $gi, 'ignored'),
                    'filter_selected_condition' => 'in',
                    'filter_value'              => '0,1',
                    'db_column_name'            => 'ignored',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gi, 'excludeIgnored'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '0',
                    'db_column_name'            => 'ignored',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gi, 'includeIgnored'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '1',
                    'db_column_name'            => 'ignored',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                //delayed
                array(
                    'id'                        => $this->getId($mod, $gd, 'delayed'),
                    'filter_selected_condition' => 'in',
                    'filter_value'              => '0,1',
                    'db_column_name'            => 'permit_notifications',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gd, 'excludeDelayed'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '1',
                    'db_column_name'            => 'permit_notifications',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
                array(
                    'id'                        => $this->getId($mod, $gd, 'includeDelayed'),
                    'filter_selected_condition' => '=',
                    'filter_value'              => '0',
                    'db_column_name'            => 'permit_notifications',
                    'db_table_name'             => 'css_networking_device_alarm',
                ),
            );

            DB::table('def_filter_query')->insert($items);
        }

        public function getId($module, $group, $id)
        {
            //Log::info(print_r("The params: $module, $group, $id", true));
            $res = DB::table('def_filter_options')
                ->select('id')
                ->where('filter_module', '=', $module)
                ->where('filter_group', '=', $group)
                ->where('filter_id', '=', $id)
                ->first();

            return $res->id;
        }
    }
