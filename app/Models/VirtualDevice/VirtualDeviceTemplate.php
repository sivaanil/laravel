<?php

namespace Unified\Models\VirtualDevice;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Eloquent;
use Illuminate\Support\Facades\Log;

class VirtualDeviceTemplate extends Eloquent
{

    protected $table = "def_virtual_device_structure";

    
    public function inflatedTemplate() {
        return json_decode($this->structure, true);
    }

}
