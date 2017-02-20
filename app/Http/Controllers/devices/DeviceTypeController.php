<?php namespace Unified\Http\Controllers\devices;

use Unified\Http\Requests;
use Unified\Http\Controllers\Controller;
use Unified\Models\DeviceType;
use Unified\Models\DevicePortDef;

use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;
use DB;
use Validator;
use Input;

class DeviceTypeController extends Controller {

    public function index()
    {
        $deviceType = DeviceType::select('id', 'vendor', 'model')->where('auto_build_enabled', '=', 1)->orderBy('vendor')->orderBy('model')->get();
        return $deviceType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function show($id)
    {
        $deviceType = DeviceType::find($id);
        //dd($deviceType);

        return $deviceType;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
    }

    public function getPorts($id)
    {
        $ports = DevicePortDef::select('id', 'variable_name', 'name', 'default_port')->where('device_type_id', '=', $id)->orderBy('default_port')->get();
        return $ports;
    }
    
    public function getDeviceTypes($className)
    {
        return DeviceType::getDeviceTypeList($className);
    }

}
