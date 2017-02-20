<?php

namespace Unified\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\Response\ApiResponse;
use Unified\Http\Controllers\Api\Response\InternalErrorResponse;
use Unified\Http\Controllers\Api\SegmentHandlers\ApiRootHandler;

/**
 * Provides the endpoint for SiteGate REST API calls.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
class RestAPIController extends Controller {
    public function process(Request $request, $module) {
        
        // Send REST API request to the root controller
        try {
            $response = (new ApiRootHandler ())->handle ( $request );
        } catch ( Exception $e ) {
            return (new InternalErrorResponse ( "Internal error: {$e->getMessage()}", 
                    [ 
                            "Resource {$request->getMethod()} {$request->path ()}",
                            $e->getTraceAsString () 
                    ] ))->response ();
        }
        
        // Check response. Return an internal error if
        // - response is empty
        // - response is based on class different from ApiResponse
        if (empty ( $response ) || ! ($response instanceof ApiResponse)) {
            return (new InternalErrorResponse ( "Invalid response on {$request->getMethod()} {$request->path()}", 
                    [ 
                            "Response " . get_class () 
                    ] ))->response ();
        }
        
        return $response->response ();
    }
}
