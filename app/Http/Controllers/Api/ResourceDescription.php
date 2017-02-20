<?php

namespace Unified\Http\Controllers\Api;

use Unified\Http\Controllers\Api\Response\MethodNotAllowedResponse;
use Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse;
use Unified\Http\Helpers\ValidationHelper;

/**
 * API Resource description.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class ResourceDescription {
    // Resource method const
    const GET = "GET";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";
    
    // Mandatory parameters
    const MANDATORY = "mandatory";
    // Optional parameters
    const OPTIONAL = "optional";
    private $resourceDescription;
    public function __construct($description) {
        $this->resourceDescription = $this->standardizeList ( $description );
    }
    
    /**
     * Convert resource description in human readable format to "standard" resource description.
     * Tokens without subobject represented as value in the incoming array will be converted to the keys.
     *
     * @param object $list
     *            List representing resource description in human readable format.
     */
    private function standardizeList($list) {
        if (isset ( $list ) === FALSE) {
            return [ ];
        }
        
        // - Move keys with subobjects from the source list to destination list
        // - Move keys with values from the source list to destination list
        // - Convert remaining flat values to keys and add them to the destination list
        $objectList = array ();
        foreach ( $list as $k => $v ) {
            if (is_array ( $v )) {
                // Element contains subobjects
                $objectList [$k] = $this->standardizeList ( $v );
                unset ( $list [$k] );
            } else if (! is_int ( $k )) {
                // Element with value.
                $objectList [$k] = $v;
                unset ( $list [$k] );
            }
        }
        $objectList = array_merge ( $objectList, array_fill_keys ( $list, true ) );
        return $objectList;
    }
    
    /**
     * Validates REST API request parameters.
     *
     * @param RequestParameters $request            
     * @return \Unified\Http\Controllers\Api\Response\UnprocessableEntityResponse|number
     */
    public function validate(RequestParameters $request) {
        $requestMethod = $request->getMethod ();
        if ($this->getDescription ( $requestMethod ) === ApiErrorCodes::NOT_FOUND) {
            return new MethodNotAllowedResponse ( 
                    "Method {$request->getMethod()} is not allowed for resource {$request->getPath()}" );
        }
        
        $validationErrors = [ ];
        switch ($requestMethod) {
            case ResourceDescription::GET :
                $validationErrors = $this->validateGet ( $request );
                break;
            case ResourceDescription::POST :
                $validationErrors = $this->validatePost ( $request );
                break;
            case ResourceDescription::PUT :
                $validationErrors = $this->validatePut ( $request );
                break;
            case ResourceDescription::DELETE :
                $validationErrors = $this->validateDelete ( $request );
                break;
            default :
                return new MethodNotAllowedResponse ( 
                        "Method {$request->getMethod()} is not allowed for resource {$request->getPath()}" );
        }
        
        if (isset ( $validationErrors ) && count ( $validationErrors ) > 0) {
            return new UnprocessableEntityResponse ( "Unable to process {$request->getMethod()} {$request->getPath()}", 
                    $validationErrors );
        }
        
        return ApiErrorCodes::SUCCESS;
    }
    /**
     * Finds description of resource parameters by type and method
     *
     * @param string $method
     *            Resource method
     * @param string $type
     *            Description type
     * @return Decription of parameter or null if not found
     */
    public function getDescriptionParameter($method, $type) {
        $methodDescr = $this->getDescription ( $method );
        if ($methodDescr === ApiErrorCodes::NOT_FOUND) {
            return ApiErrorCodes::NOT_FOUND;
        }
        if (is_array ( $methodDescr ) && array_key_exists ( $type, $methodDescr )) {
            return $this->resourceDescription [$method] [$type];
        } else {
            return ApiErrorCodes::NOT_FOUND;
        }
    }
    private function getDescription($key) {
        if (is_array ( $this->resourceDescription ) && array_key_exists ( $key, $this->resourceDescription )) {
            return $this->resourceDescription [$key];
        }
        return ApiErrorCodes::NOT_FOUND;
    }
    private function validateDelete(RequestParameters $request) {
        $results = array ();
        
        // Verify that query parameter list is empty
        if (! empty ( $request->getQuery () )) {
            $results [] = "Unexpected query parameters {$request->getQuery()}";
        }
        
        // Verify that content is empty
        if (! empty ( $request->getContent () )) {
            $results [] = "Unexpected content parameters " . implode ( ",", array_keys ( $request->getContent () ) );
        }
        
        return $results;
    }
    private function validatePut(RequestParameters $request) {
        $results = array ();
        
        // Check optional parameters if configuration is present
        $optionalParams = $this->getDescriptionParameter ( ResourceDescription::PUT, ResourceDescription::OPTIONAL );
        if ($optionalParams !== ApiErrorCodes::NOT_FOUND) {
            $results += ValidationHelper::compareArrays ( "Unknown content parameter", 
                    $request->getContent (), 
                    $optionalParams );
        }
        
        // Verify that query parameter list is empty
        if (! empty ( $request->getQuery () )) {
            $results [] = "Unexpected query parameters {$request->getQuery()}";
        }
        return $results;
    }
    private function validatePost(RequestParameters $request) {
        $results = array ();
        
        // Check mandatory parameters if they are configured
        $mandatoryParams = $this->getDescriptionParameter ( ResourceDescription::POST, ResourceDescription::MANDATORY );
        if ($mandatoryParams !== ApiErrorCodes::NOT_FOUND) {
            $results += ValidationHelper::compareArrays ( "Missing mandatory parameter", 
                    $mandatoryParams, 
                    $request->getContent () );
        }
        
        // Check optional parameters if they are configured
        $optionalParams = $this->getDescriptionParameter ( ResourceDescription::POST, ResourceDescription::OPTIONAL );
        if ($optionalParams !== ApiErrorCodes::NOT_FOUND) {
            // Add mandatory parameters to the list of optional parameters
            if ($mandatoryParams !== ApiErrorCodes::NOT_FOUND) {
                $optionalParams = array_merge ( $optionalParams, $mandatoryParams );
            }
            // check incoming parameters against merged array
            $results += ValidationHelper::compareArrays ( "Unknown content parameter", 
                    $request->getContent (), 
                    $optionalParams );
        }
        
        // Verify that query parameter list is empty
        if (! empty ( $request->getQuery () )) {
            $results [] = "Unexpected query parameters {$request->getQuery()}";
        }
        return $results;
    }
    private function validateGet(RequestParameters $request) {
        $results = array ();
        
        // Check optional parameters if configuration is present
        $params = $this->getDescriptionParameter ( ResourceDescription::GET, ResourceDescription::OPTIONAL );
        if ($params !== ApiErrorCodes::NOT_FOUND) {
            $results += ValidationHelper::compareArrays ( "Unknown parameter", $request->getParameters (), $params );
        }
        
        // Verify that body parameter list is empty
        if (! empty ( $request->getContent () )) {
            $results [] = "Unexpected body parameters " . implode ( ",", array_keys ( $request->getContent () ) );
        }
        
        return $results;
    }
}
