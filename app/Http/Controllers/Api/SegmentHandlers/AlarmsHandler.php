<?php

namespace Unified\Http\Controllers\Api\SegmentHandlers;

use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\SegmentHandler;

/**
 * Alarms handler.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class AlarmsHandler extends SegmentHandler {
    protected function getHandler(Request $request, $segment) {
        if (is_null ( $request->segment ( $segment ) )) {
            // next segment after "alarms" is empty, so this is /alarms resource
            return "Unified\Http\Controllers\Api\ResourceProcessors\AlarmsProcessor";
        } else if (is_null ( $request->segment ( $segment + 1 ) )) {
            // next segment after "alarms/{ID}" is empty, so this is /alarms/{ID} resource
            return "Unified\Http\Controllers\Api\ResourceProcessors\AlarmsIdProcessor";
        } else {
            // /alarms/{id]/?
            return null;
        }
    }
}
