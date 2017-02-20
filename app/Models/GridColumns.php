<?php

namespace Unified\Models;

use Eloquent;
use DB;

class GridColumns extends Eloquent {

    protected $table = "";


    public static function alarmColumnList($gridName) {
        $alarmColumnList = DB::table('def_grid_columns as gc')
            ->select('column_id', 'column_display_text', 'column_header_alignment', 'column_cell_alignment',
                'column_min_width',
                'column_static_width', 'column_data_type', 'column_visiable', 'column_editable',
                'column_detail_area_only', 'db_column_name', 'db_table_name')
            ->where('grid_id', '=', $gridName)
            ->orderBy('column_order_priority')
            ->get();

        //var_dump($alarmColumnList);
        return $alarmColumnList;
    }

}
