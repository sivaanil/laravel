<?php

namespace Unified\Models\VirtualDevice;

/**
 *
 * @author ira
 */
use Eloquent;

class VirtualDeviceThreshold extends Eloquent
{

    protected $table = "data_virtual_device_threshold";

    public function exceedsThreshold($value)
    {

        $inRange = ($this->lower_bound <= $value) && ( $value <= $this->upper_bound);

        $inRange = $this->alarm_inclusive == 1 ? !$inRange : $inRange;
        return $inRange;
    }

    public function exceedMessageToString($value)
    {
        if ($this->alarm_inclusive == 1) {
            return "Value '$value' is between {$this->lower_bound} and {$this->upper_bound}";
        } else {
            return "Value '$value' is outside of range {$this->lower_bound} to {$this->upper_bound}";
        }
    }
    
    public function rangeToString()
    {
        if ($this->alarm_inclusive == 1) {
            return "Alarms when value is between {$this->lower_bound} and {$this->upper_bound}";
        } else {
            return "Alarms when value is outside of range {$this->lower_bound} to {$this->upper_bound}";
        }
    }

    public function __toString()
    {
        return "<VirtualDeviceAlarmThreshold ID={$this->id} RANGE=" . $this->rangeToString() . ">";
    }

}
