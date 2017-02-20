<?php

namespace Unified\Models;

use Eloquent;
use DB;
use Unified\Http\Helpers\QueryParameters;

class DeviceClass extends Eloquent
{
    use QueryTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_device_class';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');
    
    /**
     * Returns list of node classes matching provided query parameters
     *
     * @param QueryParameters $config            
     */
    public static function getDeviceClasses(QueryParameters $config) {
        return self::getRecords($config, get_called_class(), 'nodeClasses');
    }
    
    
    /**
     * Returns list of device classes for building new devices
     *        
     */
    public static function getDeviceClassList()
    {
        return DB::table('css_networking_device_class AS dc')
                ->select('dc.id', 'dc.description')
                ->join('css_networking_device_type AS dt', 'dc.id', '=', 'dt.class_id') 
                ->where('dt.auto_build_enabled', '=', 1)
                ->groupBy('dc.description')
                ->orderBy('dc.description')
                ->get();
    }
}
