<?php

namespace Unified\Models;

use Eloquent;
use DB;

class FilterQuery extends Eloquent {

    protected $table = 'def_filter_query';

    public static function fromModule($module) {
        // get the filter queries from def table
        $gridFilterQueries = DB::table('def_filter_query as fq')
            ->select('fq.id', 'fq.filter_selected_condition', 'fq.filter_value', 'fq.db_column_name',
                'fq.db_table_name', 'fo.filter_group')
            ->join('def_filter_options as fo', function ($join) use ($module) {
                $join->on('fo.id', '=', 'fq.id')->where('fo.filter_module', '=', $module);
            })
            ->orderBy('fq.db_column_name');
        return $gridFilterQueries->get();
    }

    public static function getForIds($ids, $module) {

        $gridFilterQueries = DB::table('def_filter_query as fq')
            ->select('fq.id', 'fq.filter_selected_condition', 'fq.filter_value', 'fq.db_column_name',
                'fq.db_table_name', 'fo.filter_group')
            ->join('def_filter_options as fo', function ($join) use ($module) {
                $join->on('fo.id', '=', 'fq.id')->where('fo.filter_module', '=', $module);
            })
            ->whereIn('fq.id', $ids)
            //->whereIn('fq.id', $oneTrue)
            ->orderBy('fq.db_column_name');
        //$count=count($idList);
        //for($i=0; $i<$count; $i++){
        // $gridFilterQuerys = $gridFilterQuerys;
        //}
        //echo $gridFilterQuerys->toSql();
        return $gridFilterQueries->get();
    }

}
