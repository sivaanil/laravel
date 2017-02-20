<?php namespace Unified\Http\Controllers\devices;

class ScanTimesController extends \BaseController
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

        $node = NetworkTree::find($id);
        $device = Device::find($node->device_id);
        if ($device == null) {
            //placehold for a 'device not found' view
            //return View::make('h');
        } else {
            /*$basicNodeInfo = getNodeInfo($id);
            $node = NetworkTree::find($basicNodeInfo->id);//This will need to be replace with inheritance
            $device->node_id = $basicNodeInfo->id;
            $device->breadcrumb = $basicNodeInfo->breadcrumb;*/
            $device->device = $node->device_id;
            if ($node->scan_interval != null) {
                $device->scan_interval = $node->scan_interval;
            } else {
                $device->scan_interval = "INHERIT TODO";
            }
            if ($node->retry_interval != null) {
                $device->retry_interval = $node->retry_interval;
            } else {
                $device->retry_interval = "INHERIT TODO";
            }
            if ($node->fail_threshold != null) {
                $device->fail_threshold = $node->fail_threshold;
            } else {
                $device->fail_threshold = "INHERIT TODO";
            }
            $device->fail_count = $node->fail_count;
            $name = "scanTimes";

            //Log::debug("Device Object:\n" . print_r($device->toArray(), true));
            return GeneralHelper::makeWithExtras('devices/ScanTimes', $device->toArray(), $id, $name);
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
