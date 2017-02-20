<?php
namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ServiceRequestBuilder;

/**
 * RolesIdProcessor
 *
 * @author Bret Sheeley <bret.sheeley@csquaredsystems.com>
 */
class RolesIdProcessor extends ApiServiceRequest
{
    /**
     * construct
     */
    public function __construct()
    {
        $description = [
            ResourceDescription::GET => [
                        ApiServiceRequest::API_SERVICE => 'Role',
                        ApiServiceRequest::API_SERVICE_ACTION => 'getRoleById',
                        ApiServiceRequest::CONTROL => [],
                        ApiServiceRequest::DEFAULT_FIELDS => ['id', 'slug', 'title', 'description', 'whitelistNodeIds', 'blacklistNodeIds'],
                        ResourceDescription::OPTIONAL => []
                ],
                ResourceDescription::PUT => [
                        ApiServiceRequest::API_SERVICE => 'Role',
                        ApiServiceRequest::API_SERVICE_ACTION => 'modifyRole'
                ],
                ResourceDescription::DELETE => [
                        ApiServiceRequest::API_SERVICE => 'Role',
                        ApiServiceRequest::API_SERVICE_ACTION => 'deleteRole'
                ]
        ];
        parent::__construct($description);
    }

    /**
     * buildServiceRequest
     *
     * Creates a ServiceRequest based on the request parameters and the node id.
     */
    public function buildServiceRequest(RequestParameters $requestParameters, $nodeId)
    {
        // validate the node id
        if ($nodeId == 0) {
            $nodeId = Auth::user()->home_node_id;
        }

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
            $serviceRequestBuilder->addFilterParam('id', $nodeId);
        } else {
            $serviceRequestBuilder->addContentParam('id', $nodeId);
        }

        // Instantiate the service request and return it.
       return $serviceRequestBuilder->build();
    }

    /**
     * getServiceRequest
     */
    public function getServiceRequest(RequestParameters $requestParameters)
    {
        // get node id
        $nodeId = $requestParameters->getRequest()
            ->segment($requestParameters->getSegment());

        // build the service request based on the request parameters and the node id.
        return $this->buildServiceRequest($requestParameters, $nodeId);
    }
}