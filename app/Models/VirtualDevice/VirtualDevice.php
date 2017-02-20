<?php

namespace Unified\Models\VirtualDevice;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Eloquent;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException,
    RuntimeException;

class VirtualDevice extends Eloquent
{

    protected $table = "data_virtual_device";

    public function virtualDeviceNode()
    {
        return $this->belongsTo("Unified\Models\NetworkTree", 'node_id');
    }

    public function realDeviceNode()
    {
        $mapEntry = VirtualDeviceMap::where('virtual_node_id', $this->node_id)->first();
        if (!$mapEntry) {
            throw new RuntimeException("Virtual device is not linked to a real device");
        }
        return $mapEntry->realDeviceNode();
    }

    public function statusFilter()
    {
        return $this->hasOne("Unified\Models\VirtualDevice\PropertyFilter", "virtual_device_id");
    }

    public function propertyFilter()
    {
        return $this->hasMany("Unified\Models\VirtualDevice\PropertyFilter", "virtual_device_id");
    }

    public function alarmFilter()
    {
        return $this->hasOne("Unified\Models\VirtualDevice\AlarmFilter", "virtual_device_id");
    }

    public function ownsProperty($propDefId) {
        return !($this->propertyFilter()->where('prop_def_id', '=', $propDefId)->first() == false);
    }
    
    public function propertyThresholds()
    {
        return $this->hasMany("Unified\Models\VirtualDevice\VirtualDeviceThreshold", "virtual_device_id");
    }
    
    public function textThresholds()
    {
        return $this->hasMany("Unified\Models\VirtualDevice\VirtualDeviceTextThreshold", "virtual_device_id");
    }
    
    public function properties()
    {

        $realDeviceNode = $this->realDeviceNode;
        $realDevice = $realDeviceNode->device;
        $partialQuery = \Unified\Models\DeviceProp::where('device_id', $realDevice->id);
        return $partialQuery;
    }

    public function getProperties($convertToCustomRange = true)
    {
        $targetPropDefIds = [];
        $propertyRanges = [];
        foreach ($this->propertyFilter()->get() as $filterElement) {
            $targetPropDefIds[] = $filterElement->prop_def_id;
            $propertyRanges[$filterElement->prop_def_id] = $filterElement->custom_range_map;
        }
        $originalProperties = $this->properties()->whereIn('prop_def_id', $targetPropDefIds)->get();
        if ($convertToCustomRange) {
            for ($i = 0; $i < count($originalProperties); $i++) {
                $targetRange = (array_key_exists($originalProperties[$i]->prop_def_id, $propertyRanges) ?
                                $propertyRanges[$originalProperties[$i]->prop_def_id] : FALSE);
                if (!$targetRange) {
                    Log::notice("No custom property value range exists for prop_def_id={$originalProperties[$i]->prop_def_id}");
                    continue;
                }
                $range = \Unified\Models\DevicePropRange::find($targetRange);
                if (!$range) {
                    Log::warning("Range '{$targetRange}' does not exist");
                    continue;
                }
                $originalProperties[$i]->value = $originalProperties[$i]->mapToRange($range);
            }
        }
        return $originalProperties;
    }

}
