<?php

    use Illuminate\Database\Seeder;
    use Illuminate\Database\Eloquent\Model;

    class def_grid_columns extends Seeder
    {

        public function run()
        {
            DB::table('def_grid_columns')->delete();

            $items = array(
                array(
                    'grid_id'                 => 'alarm',
                    'column_id'               => 'clear',
                    'column_display_text'     => 'Cleared Time',
                    'column_header_alignment' => 'center',
                    'column_cell_alignment'   => 'center',
                    'column_min_width'        => '175',
                    'column_static_width'     => '1',
                    'column_data_type'        => 'date',
                    'column_date_format'      => 'MMM d yyyy (h:mm:ss tt)',
                    'column_visiable'         => '0',
                    'column_editable'         => '0',
                    'column_detail_area_only' => '0',
                    'db_column_name'          => 'cleared',
                    'db_table_name'           => 'css_networking_device_alarm',
                    'column_order_priority'   => '5'
                ),
                array(
                    'grid_id'                 => 'alarm',
                    'column_id'               => 'description',
                    'column_display_text'     => 'Description',
                    'column_header_alignment' => 'center',
                    'column_cell_alignment'   => 'left',
                    'column_min_width'        => '250',
                    'column_static_width'     => '0',
                    'column_data_type'        => 'string',
                    'column_date_format'      => null,
                    'column_visiable'         => 1,
                    'column_editable'         => 0,
                    'column_detail_area_only' => 0,
                    'db_column_name'          => 'description',
                    'db_table_name'           => 'css_networking_device_alarm',
                    'column_order_priority'   => '3'
                ),
                array(
                    'grid_id'                 => 'alarm',
                    'column_id'               => 'id',
                    'column_display_text'     => 'Id',
                    'column_header_alignment' => 'center',
                    'column_cell_alignment'   => 'center',
                    'column_min_width'        => '60',
                    'column_static_width'     => '1',
                    'column_data_type'        => 'int',
                    'column_date_format'      => null,
                    'column_visiable'         => 1,
                    'column_editable'         => 0,
                    'column_detail_area_only' => 0,
                    'db_column_name'          => 'id',
                    'db_table_name'           => 'css_networking_device_alarm',
                    'column_order_priority'   => '1'
                ),
                array(
                    'grid_id'                 => 'alarm',
                    'column_id'               => 'path',
                    'column_display_text'     => 'Device Path',
                    'column_header_alignment' => 'center',
                    'column_cell_alignment'   => 'left',
                    'column_min_width'        => '350',
                    'column_static_width'     => '0',
                    'column_data_type'        => 'string',
                    'column_date_format'      => null,
                    'column_visiable'         => 1,
                    'column_editable'         => 0,
                    'column_detail_area_only' => 0,
                    'db_column_name'          => 'breadcrumb',
                    'db_table_name'           => 'css_networking_network_tree_map',
                    'column_order_priority'   => '2'
                )
                ,
                array(
                    'grid_id'                 => 'alarm',
                    'column_id'               => 'raise',
                    'column_display_text'     => 'Raised Time',
                    'column_header_alignment' => 'center',
                    'column_cell_alignment'   => 'center',
                    'column_min_width'        => '175',
                    'column_static_width'     => '1',
                    'column_data_type'        => 'date',
                    'column_date_format'      => 'MMM d yyyy (h:mm:ss tt)',
                    'column_visiable'         => '1',
                    'column_editable'         => '0',
                    'column_detail_area_only' => '0',
                    'db_column_name'          => 'raised',
                    'db_table_name'           => 'css_networking_device_alarm',
                    'column_order_priority'   => '4'
                ),
                array(
                    'grid_id'                 => 'alarm',
                    'column_id'               => 'severity',
                    'column_display_text'     => 'Severity',
                    'column_header_alignment' => 'center',
                    'column_cell_alignment'   => 'center',
                    'column_min_width'        => '85',
                    'column_static_width'     => '1',
                    'column_data_type'        => 'string',
                    'column_date_format'      => null,
                    'column_visiable'         => '1',
                    'column_editable'         => '0',
                    'column_detail_area_only' => '0',
                    'db_column_name'          => 'severity_id',
                    'db_table_name'           => 'css_networking_device_alarm',
                    'column_order_priority'   => '6'
                ),
                array(
                    'grid_id'                 => 'alarm',
                    'column_id'               => 'notes',
                    'column_display_text'     => 'Notes',
                    'column_header_alignment' => 'center',
                    'column_cell_alignment'   => 'center',
                    'column_min_width'        => '75',
                    'column_static_width'     => '1',
                    'column_data_type'        => 'string',
                    'column_date_format'      => null,
                    'column_visiable'         => '1',
                    'column_editable'         => '0',
                    'column_detail_area_only' => '0',
                    'db_column_name'          => '',
                    'db_table_name'           => '',
                    'column_order_priority'   => '7'
                )
            );

            DB::table('def_grid_columns')->insert($items);
        }
    }