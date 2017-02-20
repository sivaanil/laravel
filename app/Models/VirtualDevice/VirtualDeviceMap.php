<?php

namespace Unified\Models\VirtualDevice;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VirtualDevice
 *
 * @author ira
 */

use Eloquent;

class VirtualDeviceMap extends Eloquent
{
    protected $table = "map_virtual_device";
    
    
    public function realDeviceNode() {
        return $this->hasOne("Unified\Models\NetworkTree", "id", "device_node_id");
    }
    
    public function virtualDeviceNode() {
        return $this->hasOne("Unified\Models\NetworkTree", "virtual_node_id");
    }
    
    
  
}
