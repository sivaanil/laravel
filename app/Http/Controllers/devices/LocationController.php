<?php namespace Unified\Http\Controllers\devices;


class LocationController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
//TODO make inheritance work and fetch contacts
        $node = NetworkTree::find($id);
        $device = Device::find($node->device_id);
        if ($device == null) {
            //placehold for a 'device not found' view
            //return View::make('h');
        } else {
            $device->node_id = $node->id;
            $device->breadcrumb = $node->breadcrumb;
            $device->device = $id;
            $device->description = str_replace("SIZE=\"", "STYLE=”font-size: ", $device->description);
            $name = "location";
            //Log::debug("Device Object:\n" . print_r($device->toArray(), true));
            //View::make('devices/Location', $device->toArray());
            return GeneralHelper::makeWithExtras('devices/Location', $device->toArray(), $id, $name);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
