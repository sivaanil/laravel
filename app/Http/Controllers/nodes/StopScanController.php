<?php namespace Unified\Http\Controllers\nodes;

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

/**
 * Description of StopScan
 *
 * @author joseph.nagel
 */

class StopScanController extends \BaseController
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
    public function show($id, $type)
    {
        //$node = NetworkTree::find($id);
        /*$sql = "SELECT duration_limit FROM css_networking_durations
        WHERE active = 1 AND duration_type = 'Ignore_Alarm' LIMIT 1;";*/

        $max = Duration::getMaxStopScanDuration();
        $data['time'] = $time;
        $data['nodeId'] = $id;
        $data['type'] = $type;
        $name = "stopScan";

        return GeneralHelper::makeWithExtras('devices/StopScan', $data, $id, $name);
    }

    public function stopScanPost()
    {
        $nodeId = strip_tags(Input::get('nodeId'));

        $type = strip_tags(Input::get('type'));
        $indefCB = strip_tags(Input::get('indefinatecb'));
        //var_dump($indefCB);

        $clearAlarms = strip_tags(Input::get('clearAlarms'));

        $notes = strip_tags(Input::get('notes'));
        //var_dump($notes);
        $times = json_decode(Input::get('time'));

        $max = Duration::getMaxStopScanDuration();
        //var_dump($times);
        if ($indefCB === 'yes') {
            $time = - 1;
        } else {
            $time = $times->month * 30 * 24 * 60 * 60 +
                $times->week * 7 * 24 * 60 * 60 +
                $times->day * 24 * 60 * 60 +
                $times->hour * 60 * 60 +
                $times->minute * 60;
        }
        if ($max < $time && $max != - 1) {
            //TODo  use this to display error
            //http://api.jquery.com/submit/

            echo "ERROR";

            return "false";
            //return $this->show($nodeId, $type);
        }
        $mainDevNode = NodeHelper::getMainDevNodeFromNodeId($nodeId);
        $this->updateStopScanTime($mainDevNode->id, $type, $time, $notes);

        if ($clearAlarms === 'yes') {
            GeneralHelper::clearAlarms($mainDevNode->id, $notes);
        }

        return "true";
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


    public function updateStopScanTime($nodeId, $type, $timeInS, $notes)
    {
        $node = NetworkTree::find($nodeId);
        $device = Device::find($node->device_id);
        //$devType = DeviceType::find($device->type_id);
        if ($timeInS == - 1) {
            $date = new DateTime("2099-01-01 00:00:00");
        } else {
            $date = new DateTime();
            $dv = new DateInterval('PT' . $timeInS . 'S');
            $date->add($dv);
        }
        if ($type == "scan") {
            $device->stop_scan_until = $date->format('Y-m-d H:i:s');
            $device->stop_scan_notes = $notes;
        } else if ($type == "alarm") {
            $device->stop_alarm_until = $date->format('Y-m-d H:i:s');
            $device->stop_alarm_notes = $notes;
        } else if ($type == "prop") {
            $device->stop_property_until = $date->format('Y-m-d H:i:s');
            $device->stop_property_notes = $notes;
        }
        $device->save();

        return;
    }


}