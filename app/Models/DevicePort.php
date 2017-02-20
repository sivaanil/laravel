<?php namespace Unified\Models;

use Eloquent;
use DB;

class DevicePort extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_device_port';

    protected $fillable = ['port_def_id','device_id','port'];

    public $timestamps = false;
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //we want to show the device's password
    //protected $hidden = array('password');

    public static function getPort($nodeId) {

        $port = DB::table('css_networking_device_port as dp')
            ->select('dp.*', 'dpd.default_port AS defaultPort', 'dpd.name AS portName', 'd.type_id as tid')
            ->join('css_networking_device as d', 'd.id', '=', 'dp.device_id')
            ->join("css_networking_device_port_def as dpd", "dpd.id", '=', 'dp.port_def_id')
            ->where('device_id', '=', DB::raw("(select Main_Device_Id(?))"))
            ->setBindings([$nodeId])
            ->where('dpd.name', 'like', '%http%')
            ->orderByRaw("length(dpd.name)")
            ->limit(1)
            ->get();
        return $port;
    }

    public static function getPortsByDeviceId($deviceId) {

        $port = DB::table('css_networking_device_port as dp')
            ->select('dp.*')
            ->join('css_networking_device as d', 'd.id', '=', 'dp.device_id')
            ->join("css_networking_device_port_def as dpd", "dpd.id", '=', 'dp.port_def_id')
            ->where('device_id', '=', '?')
            ->setBindings([$deviceId])
            ->get();
        return $port;
    }

    // group __belongs_to__ Node
    public function device()
    {
        return $this->belongsTo('Unified\Models\()Device', 'id');
    }

    public function portDef()
    {
        return $this->hasOne('Unified\Models\DevicePortDef', 'id', 'port_def_id');
    }

    public function node() {
        return $this->belongsTo('Unified\Models\NetworkTree', 'device_id', 'device_id');
    }

    public function treeEntry() {
        return $this->node->networkTreeMap;
    }

}

