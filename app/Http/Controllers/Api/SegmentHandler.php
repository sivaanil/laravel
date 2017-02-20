<?php

namespace Unified\Http\Controllers\Api;

use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\ResourceProcessor;
use Unified\Http\Controllers\Api\Response\ApiResponse;
use Unified\Http\Controllers\Api\Response\InternalErrorResponse;
use Unified\Http\Controllers\Api\Response\NotFoundResponse;

/**
 * API resource segment handler base class.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
abstract class SegmentHandler {

    /**
     * Returns handler for particular segment in REST API request
     *
     * @param Request $request
     *            REST aPI request
     * @param number $segment
     *            Segment number
     * @return String representation of handler class or null
     */
    abstract protected function getHandler(Request $request, $segment);

    /**
     * Handles API request.
     * Method MUST always return object based on ApiResponse class.
     *
     * @param Request $request
     *            HTTP Request
     * @param number $segment
     *            Segment of URI to be "controlled" by controller
     * @return Class based on \Unified\Http\Controllers\Api\Response\ApiResponse
     */
    final public function handle(Request $request, $segment = 1) {
        $response = null;

        // Go to the next URI segment
        $segment ++;

        // Find handler class, instantiate object
        $handlerClass = $this->getHandler ( $request, $segment );
        if (empty ( $handlerClass )) {
            return new NotFoundResponse ( "Resource {$request->getMethod()} {$request->path()} not found.",
                    [
                            "Unknown token [{$request->segment($segment )}]"
                    ] );
        }
        $handler = new $handlerClass ();

        if ($handler instanceof ResourceProcessor) {
            // Check if request URL still have unparsed segments
            if (! empty ( $request->segment ( $segment + 1 ) )) {
                return new NotFoundResponse ( "Resource {$request->getMethod()} {$request->path()} not found.",
                        [
                                "Unknown token [{$request->segment($segment+1)}]"
                        ] );
            }

            // Handle request
            $response = $handler->process ( $request, $segment );
            if (empty ( $response )) {
                $response = new InternalErrorResponse (
                        "Resource {$request->getMethod()} {$request->path()} handler error.",
                        [
                                "Handler " . get_class ( $handler )
                        ] );
            }
        } else if ($handler instanceof SegmentHandler) {
            $response = $handler->handle ( $request, $segment );
        } else {
            $response = new InternalErrorResponse ( "Invalid handler  for {$request->getMethod()} {$request->path()}",
                    [
                            "Handler " . get_class ( $handler )
                    ] );
        }
        return $response;
    }
}
