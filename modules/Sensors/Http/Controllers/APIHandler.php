<?php

namespace Modules\Sensors\Http\Controllers;

use Unified\Http\Controllers\Api\SegmentHandler;
use Illuminate\Http\Request;

class APIHandler extends SegmentHandler {

    public function getHandler(Request $request, $segment) {
        $output = null;

        switch ($request->segment($segment)) {
            case "contactclosure":
                   return "Modules\Sensors\Http\Controllers\ContactClosureHandler";
                break;
            case "relay":
                    return "Modules\Sensors\Http\Controllers\RelayHandler";
                break;
            case "analogsensor":
                    return "Modules\Sensors\Http\Controllers\AnalogSensorHandler";
                break;
        }
        return null;
    }

}
