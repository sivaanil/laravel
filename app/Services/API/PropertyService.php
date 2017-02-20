<?php

namespace Unified\Services\API;

use Unified\Models\DeviceProp;
use Unified\Models\DevicePropDef;
use Unified\Models\DevicePropGroup;
use Unified\Models\DevicePropLog;
use Unified\Models\DevicePropOpts;
use Unified\Models\DevicePropType;
use Unified\Services\API\RequestValidators\RequestValidator;
use Unified\Services\API\ServiceResponse;

/**
 * Handles property related endpoints for the API.
 */
class PropertyService extends APIService {
    /**
     * Property Service constructor.
     *
     * @param ServiceRequest $request
     *            Service request
     */
    public function __construct(ServiceRequest $request) {
        parent::__construct ( $request, 
                RequestValidator::getValidator ( $request->getType (), $request->getAction () ) );
    }
    
    /**
     * Return list of Property groups
     *
     * @return Service response object with the following status codes:
     */
    public function getPropertyGroups() {
        return new ServiceResponse ( ServiceResponse::SUCCESS, 
                DevicePropGroup::getPropertyGroups ( $this->getQueryParameters () ) );
    }
    
    /**
     * Return list of Property types
     *
     * @return Service response object with the following status codes:
     */
    public function getPropertyTypes() {
        return new ServiceResponse ( ServiceResponse::SUCCESS, 
                DevicePropType::getPropertyTypes ( $this->getQueryParameters () ) );
    }
    /**
     * Return list of Property options
     *
     * @return Service response object with the following status codes:
     */
    public function getPropertyOptions() {
        return new ServiceResponse ( ServiceResponse::SUCCESS, 
                DevicePropOpts::getPropertyOptions ( $this->getQueryParameters () ) );
    }
    
    /**
     * Return list of Property definitions
     *
     * @return Service response object with the following status codes:
     */
    public function getPropertyDefinitions() {
        return new ServiceResponse ( ServiceResponse::SUCCESS, 
                DevicePropDef::getPropertyDefinitions ( $this->getQueryParameters () ) );
    }
    
    /**
     * Return list of Properties
     *
     * @return Service response object with the following status codes:
     */
    public function getProperties() {
        return new ServiceResponse ( ServiceResponse::SUCCESS, 
                DeviceProp::getProperties ( $this->getQueryParameters () ) );
    }
    
    /**
     * Return property with particular ID
     *
     * @return Service response object with the following status codes:
     */
    public function getPropertyById() {
        // Utilize getProperties call. Incoming filters should already have filter by property id.
        $prop = DeviceProp::getProperties ( $this->getQueryParameters () );
        if (count ( $prop ['properties'] ) == 0) {
            return new ServiceResponse ( ServiceResponse::NOT_FOUND );
        } else {
            return new ServiceResponse ( ServiceResponse::SUCCESS, $prop );
        }
    }
    
    /**
     * Return list of Property logs
     *
     * @return Service response object with the following status codes:
     */
    public function getPropertyLogs() {
        return new ServiceResponse ( ServiceResponse::SUCCESS, 
                DevicePropLog::getPropertyLogs ( $this->getQueryParameters () ) );
    }
    
    /**
     * Modify Property
     *
     * @return Service response object with the following status codes:
     */
    public function modifyProperty() {
        $content = $this->getContent ();
        // Verify property ID
        $propId = isset ( $content ['id'] ) ? $content ['id'] : null;
        if (is_null ( $propId )) {
            return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY, 
                    array (
                            'error' => 'Property ID is empty.' 
                    ) );
        }
        $property = DeviceProp::find ( $propId );
        if ($property == false || count ( $property ) == 0) {
            return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY, 
                    array (
                            'error' => 'Property does not exists.' 
                    ) );
        }
        
        // Unset ID from content to pass content verification
        unset ( $content ['id'] );
        $retVal = $property->modifyAttributes ( $content );
        
        if (isset ( $retVal ['status'] ) && $retVal ['status'] = true) {
            return new ServiceResponse ( ServiceResponse::SUCCESS, [ ] );
        } else {
            return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY, array (
                    'error' => $retVal ['error'] 
            ) );
        }
    }
}
