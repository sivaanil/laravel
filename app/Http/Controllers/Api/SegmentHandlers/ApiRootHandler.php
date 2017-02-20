<?php

namespace Unified\Http\Controllers\Api\SegmentHandlers;

use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\SegmentHandler;
use Module;

/**
 * API root controller.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class ApiRootHandler extends SegmentHandler {
    private $rootHandlers;
    public function __construct() {
        $this->rootHandlers = array (
                "v1" => "Unified\Http\Controllers\Api\SegmentHandlers\ApiV1Handler"
        );
    }
    protected function getHandler(Request $request, $segment) {
        $rootElement = $request->segment ( $segment );
        if (isset ( $this->rootHandlers [$rootElement] )) {
            $controller = $this->rootHandlers [$rootElement];
            return $controller;
        } else {
            $module = Module::find($rootElement);
            if ($module) {

                if ($module->active()) {
                    // If the module has API endpoints, return the API Handler
                    if ($module->hasAPI()) {
                        return $module->getAPIHandler($rootElement);
                    }
                }
            }
        }

        return null;
    }
}
