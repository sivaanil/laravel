<?php

namespace Unified\Http\Controllers\Api\SegmentHandlers;

use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\SegmentHandler;
use Unified\Http\Controllers\Api\Response\OkResponse;
use Unified\Http\Controllers\Api\Response\NotFoundResponse;
use Log;

final class VirtualDeviceHandler extends SegmentHandler
{
    private $topLevelCommands;
    private $virtualDeviceFunctions;

    public function __construct()
    {
        //top-level functions do not reference a particual virtual device ID
        $this->topLevelFunctions = [/*'addThreshold',*/ 'create', 'templates'];
        //virtual device functions are of the form /virtualDevices/x/poll-for-alarms,
        //where x id the ID of the virtual device being operated on
        $this->virtualDeviceFunctions = ['poll-for-alarms', 'write-device-settings'];
    }

    protected function getHandler(Request $request, $segment)
    {
        Log::debug( __class__ . " New Request: " . $request->segment($segment) . " with headers: " . print_r($request->header(), true));
        if (array_search($request->segment($segment), $this->topLevelFunctions) !== FALSE) {
            if ($request->segment($segment) == 'create') {
                return "Unified\Http\Controllers\Api\ResourceProcessors\VirtualDeviceConstructor";
            }
            if ($request->segment($segment) == 'templates') {
                return "Unified\Http\Controllers\Api\ResourceProcessors\VirtualDeviceTemplate";
            }
        }
        if (is_numeric($request->segment($segment)) && $request->segment($segment +1) == null ) {
            return "Unified\Http\Controllers\Api\ResourceProcessors\GetVirtualDevice";
        }
        if (array_search($request->segment($segment), $this->virtualDeviceFunctions) !== false) {
            return "Unified\Http\Controllers\Api\ResourceProcessors\VirtualDeviceProcessor";
        }
        return "Unified\Http\Controllers\Api\SegmentHandlers\VirtualDeviceHandler";
    }
}
