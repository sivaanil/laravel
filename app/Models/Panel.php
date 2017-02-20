<?php

namespace Unified\Models;
use Eloquent;

class Panel extends Eloquent {

    protected $table = "def_panels";

    public static function getByDeviceType($type) {
        if ($type == "device") {
            $typeId = array(1, 3);
        } else {
            $typeId = array(2, 3);
        }
        $panels = DB::table('def_panels as p')
            ->select('p.name as value', 'p.style_sheet as class', 'p.type')
            ->whereIn('p.type', $typeId)
            ->get();

        $panel = array();
        for ($i = 0; $i < count($panels); $i ++) {
            $panel[$i]['value'] = $panels[$i]->value;
            $panel[$i]['class'] = $panels[$i]->class;
            $panel[$i]['type'] = $panels[$i]->type;
        }
        return $panel;
    }
}
