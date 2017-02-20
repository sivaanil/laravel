<?php

namespace Modules\Enterprise\Http\Controllers;

use Unified\Http\Controllers\Api\SegmentHandler;
use Illuminate\Http\Request;

/**
 * VES API handler.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
class APIHandler extends SegmentHandler {
    private $enterpriseHandlers;
    public function __construct() {
        $this->enterpriseHandlers = array (
                "ves01" => "Modules\Enterprise\Http\Controllers\VES\Handler"
        );
    }
    protected function getHandler(Request $request, $segment) {
        // Get resource name
        $resource = $request->segment ( $segment );
        if (! empty ( $resource ) && isset ( $this->enterpriseHandlers [$resource] )) {
            return $this->enterpriseHandlers [$resource];
        }
    
        return null;
    }
}
