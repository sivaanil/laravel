<?php
namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ServiceRequestBuilder;

/**
 * PermissionsIdProcessor
 *
 * @author Bret Sheeley <bret.sheeley@csquaredsystems.com>
 */
class PermissionsIdProcessor extends ApiServiceRequest
{

    /**
     * construct
     */
    public function __construct()
    {
        $description = [
            ResourceDescription::PUT => [
                ApiServiceRequest::API_SERVICE => 'Permission',
                ApiServiceRequest::API_SERVICE_ACTION => 'modify'
            ],
            ResourceDescription::DELETE => [
                ApiServiceRequest::API_SERVICE => 'Permission',
                ApiServiceRequest::API_SERVICE_ACTION => 'delete'
            ]
        ];
        parent::__construct($description);
    }

    /**
     * buildServiceRequest
     *
     * Creates a ServiceRequest based on the request parameters and the permission id.
     */
    public function buildServiceRequest(RequestParameters $requestParameters, $permissionId)
    {
        // Instantiate the builder
        $methodName = $requestParameters->getMethod();
        $serviceRequestBuilder = new ServiceRequestBuilder(
            $this->getApiService($methodName),
            $this->getApiServiceAction($methodName)
        );
        $serviceRequestBuilder->fromRequest($requestParameters, $this->getDescription());

        // If GET, add node id to the filter parameter list,
        // otherwise add it to the content list.
        if ($methodName === 'GET') {
            $serviceRequestBuilder->addFilterParam('permission_id', $permissionId);
        } else {
            $serviceRequestBuilder->addContentParam('permission_id', $permissionId);
        }

        // Instantiate the service request and return it.
       return $serviceRequestBuilder->build();
    }

    /**
     * getServiceRequest
     */
    public function getServiceRequest(RequestParameters $requestParameters)
    {
        // get permission id from the url
        $permissionId = $requestParameters->getRequest()
            ->segment($requestParameters->getSegment());

        // build the service request based on the request parameters and the node id.
        return $this->buildServiceRequest($requestParameters, $permissionId);
    }
}