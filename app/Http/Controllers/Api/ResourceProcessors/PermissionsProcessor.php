<?php
namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ServiceRequestBuilder;

class PermissionsProcessor extends ApiServiceRequest
{
    /**
     * construct
     * 
     * Sets the ApiServiceRequest's description
     */
    public function __construct()
    {
        $description = [
            ResourceDescription::GET => [
                ApiServiceRequest::API_SERVICE => 'Permission',
                ApiServiceRequest::API_SERVICE_ACTION => 'getPermissions',
                ApiServiceRequest::CONTROL => [ 'since' ],
                ApiServiceRequest::DEFAULT_FIELDS => [
                    'id', 'slug', 'title', 'description', 'updatedAt'
                ]
            ],
            ResourceDescription::POST => [
                ApiServiceRequest::API_SERVICE => 'Permission',
                ApiServiceRequest::API_SERVICE_ACTION => 'add'
            ]
        ];
        parent::__construct($description);
    }

    /**
     * getServiceRequest
     * 
     * {@inheritDoc}
     * @see \Unified\Http\Controllers\Api\ResourceProcessors\ApiServiceRequest::getServiceRequest()
     */
    public function getServiceRequest(RequestParameters $requestParameters) 
    {
        // Build service request objest
        $serviceRequestBuilder = new ServiceRequestBuilder(
            $this->getApiService($requestParameters->getMethod()),
            $this->getApiServiceAction($requestParameters->getMethod())
        );
        $serviceRequestBuilder->fromRequest($requestParameters, $this->getDescription());
    
        return $serviceRequestBuilder->build();
    }
}