<?php
namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ServiceRequestBuilder;

/**
 * RolesProcessor
 * 
 * @author Bret Sheeley <bret.sheeley@csquaredsystems.com>
 */
class RolesProcessor extends ApiServiceRequest
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
                ApiServiceRequest::API_SERVICE => 'Role',
                ApiServiceRequest::API_SERVICE_ACTION => 'getRoles',
                ApiServiceRequest::CONTROL => ['offset', 'limit', 'count'],
                ApiServiceRequest::DEFAULT_FIELDS => [
                    'id', 'slug', 'title', 'description', 'whitelistNodeIds', 'blacklistNodeIds'
                ]
            ],
            ResourceDescription::POST => [
                ApiServiceRequest::API_SERVICE => 'Role',
                ApiServiceRequest::API_SERVICE_ACTION => 'addRole'
            ]
        ];
        parent::__construct($description);
    }

    /**
     * generateServiceRequest
     */
    public function generateServiceRequest($methodName)
    {
        return new ServiceRequestBuilder($this->getApiService($methodName), $this->getApiServiceAction($methodName));
    }

    /**
     * getServiceRequest
     * 
     * Build and return the service request object based on the request parameters.
     * 
     * @param RequestParameters $requestParameters Request Parameters
     * 
     * @return ServiceRequest
     */
    public function getServiceRequest(RequestParameters $requestParameters)
    {
        $methodName = $requestParameters->getMethod();

        $serviceRequest = $this->generateServiceRequest($methodName);

        $serviceRequest->fromRequest($requestParameters, $this->getDescription());

        return $serviceRequest->build();
    }
}