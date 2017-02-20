<?php

namespace Unified\Models\VirtualDevice;

use Eloquent;

class VirtualDeviceTextThreshold extends Eloquent
{

    protected $table = "data_virtual_device_text_threshold";

    public function exceedsThreshold($currentValue)
    {
        if(!$this->case_sensitive) {
            $raise = (strtolower($currentValue) == strtolower($this->text));
        }
        else {
            $raise = ($currentValue == $this->text);
        }
        //if the alarm is only supposed to be active when the value is not equal to the threshold, we need to invert 
        //the $raise state
        if(!$this->alarm_on_match) {
            $raise = !$raise;
        }

        return $raise;
    }

    public function exceedMessageToString($value)
    {
        return "Value '$value' is " . ($this->alarm_on_match ? "equal" : "not equal") 
                              . " to '$this->text'";
    }
    
    public function rangeToString()
    {
        return "Alarms when " . ($this->case_sensitive ? "" : "case-insensitive ") . "property value is " . ($this->alarm_on_match ? "equal" : "not equal") 
                              . " to '$this->text'";
    }

    public function __toString()
    {
        return "<VirtualDeviceAlarmTextThreshold ID={$this->id} ALARM-WHEN=" . $this->rangeToString() . ">";
    }

}
