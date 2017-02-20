<?php

namespace Modules\Enterprise\Http\Controllers\VES;
use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\SegmentHandler;

/**
 * VES enterprise handler.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class Handler extends SegmentHandler {
    private $vesHandlers;
    public function __construct() {
        $this->vesHandlers = array (
                "serviceNodes" => "Modules\Enterprise\Http\Controllers\VES\ServiceNodesProcessor",
                "radioNodes" => "Modules\Enterprise\Http\Controllers\VES\RadioNodesProcessor",
                "alarms" => "Modules\Enterprise\Http\Controllers\VES\AlarmsProcessor"
        );
    }
    protected function getHandler(Request $request, $segment) {
        // Get resource name
        $resource = $request->segment ( $segment );
        if (! empty ( $resource ) && isset ( $this->vesHandlers [$resource] )) {
            return $this->vesHandlers [$resource];
        }

        return null;
    }
}
