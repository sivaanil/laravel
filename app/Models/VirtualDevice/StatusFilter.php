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

class StatusFilter extends Eloquent
{

    protected $table = "data_virtual_device_property_filter";

    public function filterElements()
    {
        return $this->hasMany("PropertyFilterElement", 'filter_id');
    }

}
