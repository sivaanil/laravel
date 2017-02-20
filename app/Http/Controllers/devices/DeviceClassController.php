<?php namespace Unified\Http\Controllers\devices;

use Unified\Http\Controllers\Controller;
use Unified\Models\DeviceClass;
use Illuminate\Http\Response as Response;


class DeviceClassController extends Controller {

    public function index()
    {
        $deviceClass = DeviceClass::getDeviceClassList();
        return $deviceClass;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function show($id)
    {
        $deviceClass = DeviceClass::find($id);
        return $deviceClass;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        
    }
}
