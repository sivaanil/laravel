<?php

namespace Unified\Models;

use Eloquent;
use DB;

class FilterOptions extends Eloquent {

    protected $tbl = "def_filter_options";

    public static function fromModule($module) {
        $filterGroup = DB::table('def_filter_options as fo')
            ->select("filter_parent", "default_state", "id")
            ->where('filter_module', '=', $module)
            ->orderBy('filter_group')
            ->orderBy('filter_parent', "desc")
            ->orderBy('filter_order')
            ->get();
        return $filterGroup;
    }

}

