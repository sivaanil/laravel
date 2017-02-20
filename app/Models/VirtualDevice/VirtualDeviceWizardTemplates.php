<?php

namespace Unified\Models\VirtualDevice;

use DB;
use Eloquent;

class VirtualDeviceWizardTemplates extends Eloquent
{

    protected $table = "def_virtual_device_templates";
    
    public static function getDeviceList($deviceType)
    {
        return DB::table('def_virtual_device_templates')
                ->select('id', 'name')
                ->where('type_id', '=', DB::raw('?'))
                ->orderBy('id', 'DESC')
                ->setBindings([$deviceType])
                ->get();
    }

    public static function verifyTemplateAgainstType($type)
    {
        $templates = DB::Table('def_virtual_device_templates')
                ->select('id')
                ->where('type_id', '=', DB::raw('?'))
                ->setBindings([$type])
                ->get();

        $validTemplates = [];
        foreach ($templates as $tempalteId) {
            array_push($validTemplates, $tempalteId->id);
        }
        return implode(", ", $validTemplates);
    }

}