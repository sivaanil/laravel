<?php namespace Unified\Models;

use Eloquent;

class DevicePortDef extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_device_port_def';
    public function port()
    {
        return $this->belongsTo('DevicePort', 'port_def_id');
    }

}
