<?php namespace Unified\Models;

require_once ENV('CSWAPI_ROOT') . '/common/class/cssEncryption.php';

use DB;
use Eloquent;
use Unified\Models\NetworkTree;

class Device extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'css_networking_device';

    protected $encrypted = ['read_community', 'write_community', 'password', 'SNMPauthPassword', 'SNMPprivPassword'];

    public $timestamps = false;

    public static function clearScanningFlags() {
        self::where('scanning', '=', 1)->update(['scanning' => 0]);
    }

    public static function getIPAddress($nodeId) {
        $ipAddress = DB::table('css_networking_device as d')
            ->select('d.ip_address as ip')
            ->where('id', '=', DB::raw("(select Main_Device_Id(?))"))
            ->setBindings([$nodeId])
            ->get();
        return $ipAddress;
    }

    /**
     * Sets properties for the device (only in DB)
     * @param Array<string=>mixed> $properties Properties to set by name
     */
    public function setProperties(Array $properties) {

        foreach ($properties as $name => $value) {
            // get the property id
            $propDef = PropDef::where('variable_name', '=', $name)
                ->where('device_type_id', '=', $this->type_id)
                ->first();

            $prop = DeviceProp::where('prop_def_id', '=', $propDef->id)
                ->where('device_id', '=', $this->id)
                ->first();

            // Set the property value
            $prop->value = $value;
            $prop->save();
        }
    }

    public function getProperty($propName) {
        // TODO - get the named property for this device
    }


    public function setProperty($propName, $value) {

    }

    /**
     * Get the path to this device in the network tree map
     *
     * @return String breadcrumb path with names of devices
     */
    public function getPath() {


        $breadcrumb = DB::table('css_networking_network_tree_map AS nntm')
            ->select('nntm.breadcrumb')
            ->join('css_networking_network_tree AS nnt', 'nntm.node_id', '=', 'nnt.id')
            ->where('nnt.device_id', '=', $this->id)
            ->first();


        return $breadcrumb->breadcrumb;

    }

    /**
     * Get all of the child devices of the specified type.
     * @param int $typeId Type ID of the devices to return under this one.
     *
     * @return Array
     */
    public static function getDevicesByType($typeId, $nodeId) {

        // Gets the nodeId and deviceId of all devices under this device
        $children = DB::table('css_networking_network_tree_map AS nntm')
            ->select('nntm.node_id AS nodeId', 'd.id AS deviceId')
            ->leftJoin('css_networking_network_tree AS nnt', function($join) {
                $join->on('nnt.id', '=', 'nntm.node_id');
            })
            ->leftJoin('css_networking_device AS d', function ($join) {
                $join->on('d.id', '=', 'nnt.device_id');
            })
            ->where('d.type_id', $typeId)
            ->where('nntm.node_map', 'like', "%." . $nodeId . ".%")
            ->where('nntm.deleted', 0)
            ->where('nntm.build_in_progress', 0)
            ->where('nntm.node_id', '<>', $nodeId)
            ->get();



        return $children;
    }

    public static function getDeviceInventory($nodeId) {
        $devices = DB::table('css_networking_network_tree_map AS nntm')
            ->selectRaw('nntm.breadcrumb as path, d.ip_address as ipAddress, (SELECT GROUP_CONCAT(dp.port) FROM css_networking_device_port dp WHERE dp.device_id=d.id) AS ports, d.last_alarms_scan as lastAlarmsScan, d.last_properties_scan as lastPropertiesScan, COUNT(da.id) as alarmCount')
            ->leftJoin('css_networking_network_tree AS nnt', function($join) {
                $join->on('nnt.id', '=', 'nntm.node_id');
            })
            ->leftJoin('css_networking_device AS d', function ($join) {
                $join->on('d.id', '=', 'nnt.device_id');
            })
            ->leftJoin('css_networking_device_type AS dt', function ($join) {
                $join->on('dt.id', '=', 'd.type_id');
            })
            ->join('css_networking_network_tree_map as children', function ($join) {
                $join->on('children.node_map', 'like',
                    DB::Raw("concat(nntm.node_map, '%')"))->where('children.deleted', '=',
                    '0')->where('children.visible', '=', '1')->where('children.build_in_progress', '=', '0');
            })
            ->join('css_networking_network_tree as chnt', 'chnt.id', '=', 'children.node_id')
            ->leftJoin('css_networking_device_alarm as da', function ($join) {
                $join->on('da.device_id', '=', "chnt.device_id")->where('da.cleared_bit', '=',
                    '0')->where('da.ignored', '=', '0')->where('da.severity_id', '<=', '4')->where('da.severity_id',
                    '>=', '1');
            })
            ->where('nntm.node_map', 'like', "%." . $nodeId . ".%")
            ->where('nntm.deleted', 0)
            ->where('nntm.build_in_progress', 0)
            ->where('nntm.node_id', '<>', '5000')
            ->where('dt.main_device', 1)
            ->groupBy('d.id')
            ->get();

        // natural sort by breadcrumb
        usort($devices, function ($a, $b) {
            return strnatcasecmp($a->path, $b->path);
        });

        return $devices;
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //we want to show the device's password
    //protected $hidden = array('password');

    // group __belongs_to__ Node
    public function networkTree()
    {
        return $this->belongsTo('Unified\Models\NetworkTree', 'device_id');
    }

    public function port()
    {
        return $this->hasMany('Unified\Models\DevicePort', 'device_id', 'id');
    }

    public function getAttribute($key)
    {
        if (array_key_exists($key, array_flip($this->encrypted)) && ctype_xdigit($returnArray[$key]))
        {
            return \cssEncryption::getInstance()->Decrypt(parent::getAttribute($key));
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if (array_key_exists($key, array_flip($this->encrypted)))
        {
            parent::setAttribute($key, \cssEncryption::getInstance()->Encrypt($value));
            return;
        }

        parent::setAttribute($key, $value);
    }

    public function toArray(){
        $returnArray = $this->attributesToArray();
        foreach ($this->encrypted as $key) {
            if (array_key_exists($key, $returnArray) && ctype_xdigit($returnArray[$key]))
            {
                $returnArray[$key] = \cssEncryption::getInstance()->Decrypt($returnArray[$key]);
            }
        }
        return $returnArray;
    }

}
