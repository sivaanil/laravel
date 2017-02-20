<?php

namespace Unified\Http\Controllers\Api\SegmentHandlers;

use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\SegmentHandler;

/**
 * Properties handler.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class PropertiesHandler extends SegmentHandler {
    protected function getHandler(Request $request, $segment) {
        if (is_null ( $request->segment ( $segment ) )) {
            // next segment after "properties" is empty, so this is /properties resource
            return "Unified\Http\Controllers\Api\ResourceProcessors\PropertiesProcessor";
        } else if (is_null ( $request->segment ( $segment + 1 ) )) {
            // next segment after "properties/{ID}" is empty, so this is /properties/{ID} resource
            return "Unified\Http\Controllers\Api\ResourceProcessors\PropertiesIdProcessor";
        } else {
            // /property/{id]/?
            return null;
        }
    }
}
