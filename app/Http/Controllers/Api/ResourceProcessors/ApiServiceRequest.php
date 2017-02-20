<?php

namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\ApiErrorCodes;
use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceProcessor;
use Unified\Http\Controllers\Api\Response\BadRequestResponse;
use Unified\Http\Controllers\Api\Response\InternalErrorResponse;
use Unified\Http\Controllers\Api\Response\NoContentResponse;
use Unified\Http\Controllers\Api\Response\NotFoundResponse;
use Unified\Http\Controllers\Api\Response\NotImplementedResponse;
use Unified\Http\Controllers\Api\Response\OkResponse;
use Unified\Http\Controllers\Api\Response\UnauthorizedResponse;
use Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse;
use Unified\Http\Controllers\Api\ValidationException;
use Unified\Services\API\APIService;
use Unified\Services\API\ServiceResponse;

/**
 * Handler for APIService requests.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
abstract class ApiServiceRequest extends ResourceProcessor {
    // API service related constants
    const API_SERVICE = 'apiService';
    const API_SERVICE_ACTION = 'apiServiceAction';
    // Query control parameters, such as offset, limit, fields, etc..
    const CONTROL = 'control';
    // List of the fields to be returned if parameter fields is not specified
    const DEFAULT_FIELDS = 'defaultFields';
    public function __construct($description) {
        parent::__construct ( $description );
    }
    /**
     * Returns API Service request.
     *
     * @param RequestParameters $rp
     *            Request parameters
     * @return ServiceRequest object
     */
    abstract public function getServiceRequest(RequestParameters $rp);
    /**
     * Function processes incoming request and returns proper HTTP response.
     *
     * @param RequestParameters $request
     *            HTTP request
     * @return Class based on \Unified\Http\Controllers\Api\Response\ApiResponse
     */
    public function processRequest(RequestParameters $rp) {
        $response = APIService::process ( $this->getServiceRequest ( $rp ) );
        
        switch ($response->getStatus()) {
            case ServiceResponse::SUCCESS :
                $content = $response->getContent ();
                if (count($content) == 0) {
                    return new NoContentResponse ();
                } else {
                    return new OkResponse ( $response->getContent () );
                }
            case ServiceResponse::BAD_REQUEST:
                return new BadRequestResponse ( "Invalid request {$rp->getMethod()} {$rp->getPath()}", $response->getContent());
            case ServiceResponse::UNPROCESSABLE_ENTITY :
                return new UnprocessableEntityResponse ( "Unable to process {$rp->getMethod()} {$rp->getPath()}", 
                        $response->getContent () );
            case ServiceResponse::INTERNAL_ERROR :
                return new InternalErrorResponse ( "Internal error on {$rp->getMethod()} {$rp->getPath()}", 
                        $response->getContent () );
            case ServiceResponse::FORBIDDEN :
                return new UnauthorizedResponse ();
            case ServiceResponse::NOT_IMPLEMENTED :
                return new NotImplementedResponse("{$rp->getMethod()} {$rp->getPath()} is not implemented",$response->getContent());
            case ServiceResponse::NOT_FOUND :
                return new NotFoundResponse("{$rp->getMethod()} {$rp->getPath()} is not found");
            default :
                // Generic failure
                return new BadRequestResponse ( "Unable to process {$rp->getMethod()} {$rp->getPath()}", $response->getContent () );
        }
    }
    protected function getApiService($method) {
        $retVal = $this->getDescription ()->getDescriptionParameter ( $method, self::API_SERVICE );
        return ($retVal === ApiErrorCodes::NOT_FOUND ? "" : $retVal);
    }
    protected function getApiServiceAction($method) {
        $retVal = $this->getDescription ()->getDescriptionParameter ( $method, self::API_SERVICE_ACTION );
        return ($retVal === ApiErrorCodes::NOT_FOUND ? "" : $retVal);
    }
}
