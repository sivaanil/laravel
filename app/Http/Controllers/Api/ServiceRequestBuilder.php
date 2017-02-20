<?php

namespace Unified\Http\Controllers\Api;

use Unified\Http\Controllers\Api\ResourceProcessors\ApiServiceRequest;
use Unified\Services\API\ServiceRequest;

/**
 * API service response builder.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class ServiceRequestBuilder {
    private $control = [ ];
    private $filter = [ ];
    private $sortby = [ ];
    private $fields = [ ];
    private $content = [ ];
    private $user = [ ];
    private $type = "";
    private $action = "";
    private $servicePath = "";
    
    /**
     *
     * @param unknown $type
     *            Request type
     * @param unknown $action
     *            Request action
     */
    public function __construct($type, $action, $servicePath = 'Unified\\Services\\API\\') {
        $this->type = $type;
        $this->action = $action;
        $this->servicePath = $servicePath;
    }
    
    /**
     * Get data from HTTP request.
     *
     * @param RequestParameters $request
     *            Incoming HTTP request parameters
     * @param ResourceDescription $description
     *            Resource description
     */
    final public function fromRequest(RequestParameters $request, ResourceDescription $description) {
        if (empty ( $request ) || empty ( $description )) {
            return $this;
        }
        
        if ($request->getMethod () === "GET") {
            // Get query parameters from request
            $parameters = $request->getParameters ();
            if (is_array ( $parameters )) {
                
                // Get list of requested fields if they are present
                $this->fields = [ ];
                if (isset ( $parameters ) and isset ( $parameters ["fields"] )) {
                    // Read list of fields, explode it to the array
                    $this->fields = array_fill_keys ( explode ( ",", $parameters ["fields"] ), true );
                    if ($this->fields) {
                        // Implode array replacing "," with =1& to make it similar to URL query parameters
                        $this->fields = implode ( "=1&", array_keys ( $this->fields ) ) . "=1";
                        // Parse "fake" URL query parameters to get structurized fields
                        parse_str ( $this->fields, $this->fields );
                    }
                }
                
                // Get list of requested fields if they are present
                $this->sortby = [ ];
                if (isset ( $parameters ) and isset ( $parameters ["sortby"] )) {
                    // Read list of fields, explode it to the array
                    $this->sortby = array_fill_keys ( explode ( ",", $parameters ["sortby"] ), true );
                    if ($this->sortby) {
                        // Implode array replacing "," with =1& to make it similar to URL query parameters
                        $this->sortby = implode ( "=1&", array_keys ( $this->sortby ) ) . "=1";
                        // Parse "fake" URL query parameters to get structurized fields
                        parse_str ( $this->sortby, $this->sortby );
                    }
                }
                
                // Get control parameters, such as offset, limit, fields, etc. and filters
                $controlParams = $description->getDescriptionParameter ( ResourceDescription::GET, 
                        ApiServiceRequest::CONTROL );
                // Save all parameters as filter, then move control parameters from filters to separate array
                $this->filter = $parameters;
                $this->control = [ ];
                if ($controlParams !== ApiErrorCodes::NOT_FOUND) {
                    foreach ( $this->filter as $k => $v ) {
                        if (array_key_exists ( $k, $controlParams )) {
                            $this->control [$k] = $v;
                            unset ( $this->filter [$k] );
                        }
                    }
                }
            }
            
            if (empty ( $this->fields )) {
                // check if list of default fields is present
                $defFields = $description->getDescriptionParameter ( ResourceDescription::GET, 
                        ApiServiceRequest::DEFAULT_FIELDS );
                if ($defFields !== ApiErrorCodes::NOT_FOUND) {
                    $this->fields = $defFields;
                }
            }
        } else {
            $this->content = $request->getContent ();
        }
        $this->user = $request->getUser ();
        return $this;
    }
    
    /**
     * Sets fields.
     *
     * @param unknown $fields
     *            Requested fields
     */
    final public function setFields($fields) {
        $this->fields = $fields;
        return $this;
    }
    
    /**
     * Sets sortby.
     *
     * @param unknown $sortby
     *            Requested sort fields
     */
    final public function setSortby($sortby) {
        $this->sortby = $sortby;
        return $this;
    }
    
    /**
     * Sets filter.
     *
     * @param unknown $filter
     *            Request filter
     */
    final public function setFilter($filter) {
        $this->filter = $filter;
        return $this;
    }
    
    /**
     * Sets content.
     *
     * @param unknown $content
     *            Request body content
     */
    final public function setContent($content) {
        $this->content = $content;
        return $this;
    }
    
    /**
     * Add parameter to the filter.
     *
     * @param unknown $key            
     * @param unknown $value            
     * @return \Unified\Http\Controllers\Api\ServiceRequestBuilder
     */
    final public function addFilterParam($key, $value) {
        if (empty ( $this->filter )) {
            $this->filter = [ ];
        }
        $this->filter [$key] = $value;
        return $this;
    }
    
    /**
     * Add parameter to the content.
     *
     * @param unknown $key            
     * @param unknown $value            
     * @return \Unified\Http\Controllers\Api\ServiceRequestBuilder
     */
    final public function addContentParam($key, $value) {
        if (empty ( $this->content )) {
            $this->content = [ ];
        }
        $this->content [$key] = $value;
        return $this;
    }
    
    /**
     * Add parameter to the list of control parameters.
     *
     * @param unknown $key            
     * @param unknown $value            
     * @return \Unified\Http\Controllers\Api\ServiceRequestBuilder
     */
    final public function addControlParam($key, $value) {
        if (empty ( $this->control )) {
            $this->control = [ ];
        }
        $this->control [$key] = $value;
        return $this;
    }
    
    /**
     * Create instance of Service Request based on incoming HTTP request parameters and
     * resource description.
     *
     * @return Instance of service request representing current HTTP request
     */
    final public function build() {
        $data = [ ];
        $data ["filter"] = $this->filter;
        $data ["fields"] = $this->fields;
        $data ["sortby"] = $this->sortby;
        $data ["control"] = $this->control;
        $data ["content"] = $this->content;
        $sr = new ServiceRequest ( $this->type, $this->action, $data, $this->user, $this->servicePath );
        return $sr;
    }
}
