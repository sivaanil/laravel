<?php

namespace Unified\Http\Controllers\Api\SegmentHandlers;

use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\SegmentHandler;

/**
 * Notifications handler.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class NotificationsHandler extends SegmentHandler {
    protected function getHandler(Request $request, $segment) {
        if (is_null ( $request->segment ( $segment ) )) {
            // next segment after "Notifications" is empty, so this is /Notifications resource
            return "Unified\Http\Controllers\Api\ResourceProcessors\NotificationsProcessor";
        } else if (is_null ( $request->segment ( $segment + 1 ) )) {
            // next segment after "Notifications/{ID}" is empty, so this is /Notifications/{ID} resource
            return "Unified\Http\Controllers\Api\ResourceProcessors\NotificationsIdProcessor";
        } else {
            // /Notifications/{id]/?
            return null;
        }
    }
}
