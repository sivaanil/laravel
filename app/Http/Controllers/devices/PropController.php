<?php namespace Unified\Http\Controllers\devices;

class PropController extends \BaseController
{
//This controller handles both status and properties
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
    public function show($id, $propOrStatus)
    {
//TODO make inheritance work and fetch contacts

        $node = NetworkTree::find($id);
        $device = Device::find($node->device_id);
        if ($device == null) {
            //placehold for a 'device not found' view
            //return View::make('h');
        } else {
            if ($propOrStatus == "stats") {

                $props = DeviceProp::getStats($device->id);

/*                $props = DB::table('css_networking_device_prop as p')
                    ->select(//'p.id',
                        'pd.name',
                        //'pd.prop_type_id as type',
                        'p.value'
                    'pd.visible as visible',
                    'p.min as prop_min',
                    'p.min_val as prop_min_val',
                    'p.max as prop_max',
                    'p.max_val as prop_max_val',
                    'p.severity_id as prop_sev',
                    'p.prop_def_id',
                    'p.use_defaults as use_def',
                    'pd.min as def_min',
                    'pd.prop_group_id',
                    'pd.min_val as def_min_val',
                    'pd.max as def_max',
                    'pd.max_val as def_max_val',
                    'pd.severity_id as def_sev',
                    'pd.tooltip',
                    'pd.variable_name',
                    'pd.secure as secure',
                    'pd.thresh_enable as enable',
                    'pg.name as group_name')
                    //'case when pd.graph_type = 1 then 'true' else 'false' end as 'binary'')
                    ->join('css_networking_device_prop_def as pd', 'p.prop_def_id', '=', 'pd.id')
                    ->join('css_networking_device_prop_group as pg', 'pd.prop_group_id', '=', 'pg.id')
                    ->where('p.device_id', '=', $device->id)
                    ->where('p.value', '!=', 'Not In Use')
                    ->where('p.value', '!=', '""')
                    ->where('pd.prop_type_id', '=', 2)
                    ->where('pd.visible', '=', 1)
                    ->orderBy('pd.name', 'asc')
                    ->get();
*/
                $device->title = "Device Statuses";
            } else if ($propOrStatus == "props") {
                $props = DeviceProp::getProps($device->id);
//                $props = DB::table('css_networking_device_prop as p')
//                    ->select( //'p.id',
//                        'p.value',
//                        /*'p.alarm_change',
//                        'p.alarm_siteportal_change',
//                        'p.user_id_last_updated',*/
//                        'pd.name'
//                    /*'pd.prop_type_id',
//                    'pd.id as prop_def_id',
 //                   'pd.data_type',
  //                  'pd.editable',
   //                 'pd.tooltip',
//                    'pd.variable_name',
//                    'pg.group_breadCrumb',
//                    'p.severity_id as prop_sev'*/
//                    )
//                    ->join('css_networking_device_prop_def as pd', 'p.prop_def_id', '=', 'pd.id')
//                    ->leftjoin('def_prop_groups_map as pgm', function ($join) {
//                        $join->on('pgm.prop_def_variable_name', '=', 'pd.variable_name')->where('pd.device_type_id',
//                            '=', '`pgm`.`device_type_id`');
//                    })
//                    ->leftJoin('def_prop_groups as pg', 'pg.group_var_name', '=', 'pgm.group_var_name')
//                    ->where('p.device_id', '=', $device->id)
//                    ->where('p.value', '!=', 'Not In Use')
//                    ->where('p.value', '!=', '""')
//                    ->where('pd.prop_type_id', '=', 1)
//                    ->where('pd.visible', '=', 1)
//                    ->get();
                $device->title = "Device Properties";
            } else {
                //TODO Something that makes sense we aren't on a good page
            }
            //var_dump($props);
            $propArr = Array();
            for ($i = 0; $i < count($props); $i ++) {
                //var_dump($props[$i]);
                $propArr[$i]['name'] = $props[$i]->name;
                $propArr[$i]['value'] = $props[$i]->value;
            }
            $device->props = $propArr;
            //var_dump($device->props);
            $device->device = $node->device_id;
            //$device->description = str_replace("SIZE=\"12\"", "SIZE=\"12px\"", $device->description);
            //Log::debug("Device Object:\n" . print_r($device->toArray(), true));

            return GeneralHelper::makeWithExtras('devices/Prop', $device->toArray(), $id, $propOrStatus);
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
