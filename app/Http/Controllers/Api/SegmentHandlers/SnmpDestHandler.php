<?php

namespace Unified\Http\Controllers\Api\SegmentHandlers;

use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\SegmentHandler;

/**
 * SnmpDest handler.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class SnmpDestHandler extends SegmentHandler {
    protected function getHandler(Request $request, $segment) {
        if (is_null ( $request->segment ( $segment ) )) {
            // next segment after "SnmpDest" is empty, so this is /SnmpDest resource
            return "Unified\Http\Controllers\Api\ResourceProcessors\SnmpDestProcessor";
        } else if (is_null ( $request->segment ( $segment + 1 ) )) {
            // next segment after "SnmpDest/{ID}" is empty, so this is /SnmpDest/{ID} resource
            return "Unified\Http\Controllers\Api\ResourceProcessors\SnmpDestIdProcessor";
        } else {
            // /SnmpDest/{id]/?
            return null;
        }
    }
}
