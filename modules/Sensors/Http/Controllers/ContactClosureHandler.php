<?php

namespace Modules\Sensors\Http\Controllers;

use Unified\Http\Controllers\Api\SegmentHandler;
use Illuminate\Http\Request;

class ContactClosureHandler extends SegmentHandler {

    public function getHandler(Request $request, $segment) {
        $output = null;
        if (!empty($request->segment($segment))) {
            $output = 'Modules\\Sensors\\Http\\Controllers\\ContactClosureIdProcessor';
        } else {
            $output = 'Modules\\Sensors\\Http\\Controllers\\ContactClosureProcessor';
        }
        return $output;
    }
}
