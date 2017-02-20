<?php

namespace Unified\Http\Controllers\Api;

use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\Response\UnauthorizedResponse;
use Unified\Services\SitePortalAPI\Authentication;
use Auth;

/**
 * API Resource processor.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
abstract class ResourceProcessor {
    private $resourceDescription;
    protected function __construct($description) {
        $this->resourceDescription = new ResourceDescription ( $description );
    }

    /**
     * Function processes incoming request and returns proper HTTP response.
     *
     * @param RequestParameters $request
     *            HTTP request
     * @return Class based on \Unified\Http\Controllers\Api\Response\ApiResponse
     */
    abstract public function processRequest(RequestParameters $request);

    /**
     * Function processes incoming request and returns proper HTTP response.
     *
     * @param unknown $request
     *            HTTP request
     * @param number $segment
     *            Last URI segmetn number
     * @return Class based on \Unified\Http\Controllers\Api\Response\ApiResponse
     */
    final public function process(Request $request, $segment) {
        // All resources but login should be authenticated
        $lastSegment = $request->segment ( $segment );
        $user = null;
        if ($lastSegment !== "login") {
            $service = new Authentication ();
            $response = $service->AuthenticateWithToken ();
            if (! $response [0]) {

                // See if there is an authenticated user session
                $user = Auth::user();
                if (!$user) {
                    return new UnauthorizedResponse ( $response [2] );
                }
            }
            $user = $response [3];
        }

        $rp = new RequestParameters ( $request, $segment, $user );

        // validate request
        $response = $this->resourceDescription->validate ( $rp );
        if ($response !== ApiErrorCodes::SUCCESS) {
            return $response;
        }

        // Process request
        return $this->processRequest ( $rp );
    }
    /**
     *
     * @return Returns resource description
     */
    final public function getDescription() {
        return $this->resourceDescription;
    }
}
