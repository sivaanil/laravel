<?php

    use Illuminate\Database\Seeder;
    use Illuminate\Database\Eloquent\Model;

    class def_filter_options extends Seeder
    {

        public function run()
        {
            DB::table('def_filter_options')->delete();

            $items = array(
                //method
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'method',
                    'filter_id'       => 'allmethods',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '1',
                    'filter_order'    => '1',
                    'default_state'   => '1'
                ),
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'method',
                    'filter_id'       => 'polledalarms',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '0',
                    'filter_order'    => '2',
                    'default_state'   => '1'
                ),
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'method',
                    'filter_id'       => 'traps',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '0',
                    'filter_order'    => '3',
                    'default_state'   => '1'
                ),
                //priority
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'priority',
                    'filter_id'       => 'allpriorities',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '1',
                    'filter_order'    => '1',
                    'default_state'   => '1'
                ),
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'priority',
                    'filter_id'       => 'critical',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '0',
                    'filter_order'    => '2',
                    'default_state'   => '1'
                ),
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'priority',
                    'filter_id'       => 'major',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '0',
                    'filter_order'    => '3',
                    'default_state'   => '1'
                ),
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'priority',
                    'filter_id'       => 'minor',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '0',
                    'filter_order'    => '4',
                    'default_state'   => '1'
                ),
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'priority',
                    'filter_id'       => 'warning',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '0',
                    'filter_order'    => '5',
                    'default_state'   => '1'
                ),
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'priority',
                    'filter_id'       => 'info',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '0',
                    'filter_order'    => '6',
                    'default_state'   => '1'
                ),
                //state
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'state',
                    'filter_id'       => 'allstates',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '1',
                    'filter_order'    => '1',
                    'default_state'   => '0'
                ),
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'state',
                    'filter_id'       => 'active',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '0',
                    'filter_order'    => '2',
                    'default_state'   => '1'
                ),
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'state',
                    'filter_id'       => 'cleared',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '0',
                    'filter_order'    => '3',
                    'default_state'   => '0'
                ),
                //ignored
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'ignored',
                    'filter_id'       => 'ignored',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '1',
                    'filter_order'    => '1',
                    'default_state'   => '1'
                ),
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'ignored',
                    'filter_id'       => 'excludeIgnored',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '0',
                    'filter_order'    => '2',
                    'default_state'   => '1'
                ),
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'ignored',
                    'filter_id'       => 'includeIgnored',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '0',
                    'filter_order'    => '3',
                    'default_state'   => '1'
                ),
                //delayed
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'delayed',
                    'filter_id'       => 'delayed',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '1',
                    'filter_order'    => '1',
                    'default_state'   => '1'
                ),
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'delayed',
                    'filter_id'       => 'excludeDelayed',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '0',
                    'filter_order'    => '2',
                    'default_state'   => '1'
                ),
                array(
                    'filter_module'   => 'alarm',
                    'filter_group'    => 'delayed',
                    'filter_id'       => 'includeDelayed',
                    'filter_type'     => '1',
                    'filter_visiable' => '1',
                    'filter_editable' => '1',
                    'filter_parent'   => '0',
                    'filter_order'    => '3',
                    'default_state'   => '1'
                ),

            );

            DB::table('def_filter_options')->insert($items);
        }
    }