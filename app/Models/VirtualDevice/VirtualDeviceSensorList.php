<?php

namespace Unified\Models\VirtualDevice;

use DB;
use Eloquent;

class VirtualDeviceSensorList extends Eloquent
{

    protected $table = "def_virtual_device_sensors";
    
    public static function getSensorTypes($virtualDeviceType)
    {
        return DB::table('def_virtual_device_sensors AS vds')
                ->select('vds.template_id', 'vds.included_sensors', 'vdt.name')
                ->join('def_virtual_device_templates AS vdt', 'vdt.id', '=', 'vds.template_id')
                ->where('vds.template_id', '=', $virtualDeviceType)
                ->get();
    }
    
    public static function getSensorList($typeId)
    {
        return DB::table('css_networking_network_tree AS nt')
                ->select('nt.id', 'mainDevice.name AS mainDevice', 'd.name AS subDevice')
                ->join('css_networking_network_tree_map AS ntm', 'nt.id', '=', 'ntm.node_id')
                ->join('css_networking_device AS d', 'd.id', '=', 'nt.device_id')
                ->join('css_networking_device_type AS ndt', 'd.type_id', '=', 'ndt.id')
                ->join('css_networking_device_class AS ndc', 'ndt.class_id', '=', 'ndc.id')
                ->join('css_networking_network_tree AS pnt', 'pnt.id', '=', 'nt.parent_node_id')
                ->join('css_networking_device AS mainDevice', 'mainDevice.id', '=', 'pnt.parent_device_id')
                ->where('ndc.id', '=', $typeId)
                ->where('ntm.visible', '=', 1)
                ->where('ntm.deleted', '=', 0)
                ->get();
    }

}