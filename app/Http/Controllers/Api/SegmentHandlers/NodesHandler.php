<?php

namespace Unified\Http\Controllers\Api\SegmentHandlers;

use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\SegmentHandler;

/**
 * Nodes controller.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class NodesHandler extends SegmentHandler {
    protected function getHandler(Request $request, $segment) {
        if (is_null($request->segment ( $segment ))) {
            // next segment after "nodes" is empty, so this is /nodes resource
            return "Unified\Http\Controllers\Api\ResourceProcessors\NodesProcessor";
        } else if (is_null ( $request->segment ( $segment + 1 ) )) {
            // next segment after "nodes/{ID}" is empty, so this is /nodes/{ID} resource
            return "Unified\Http\Controllers\Api\ResourceProcessors\NodesIdProcessor";
        } else {
            // /nodes/{id]/?
            return null;
        }
    }
}
