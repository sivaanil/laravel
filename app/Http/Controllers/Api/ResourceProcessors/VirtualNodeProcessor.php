<?php

namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ServiceRequestBuilder;


/**
 * API V1 Alarms processor.
 *
 * @author Golnaz Rouhi <golnaz.rouhi@csquaredsystems.com>
 */
final class VirtualNodeProcessor extends ApiServiceRequest
{

    public function __construct()
    {
        $description = [
            ResourceDescription::GET => [
                ApiServiceRequest::API_SERVICE => 'VirtualDevice',
                        ApiServiceRequest::API_SERVICE_ACTION => 'getVirtualDevices',
                ResourceDescription::MANDATORY => [],
                ResourceDescription::OPTIONAL => [
                    'nodeId'
                ]
            ],
            ResourceDescription::POST => [
                ResourceDescription::MANDATORY => [],
                ResourceDescription::OPTIONAL => [ /* parameters list involving in add node operation */]
            ]
                ]
        ;
        parent::__construct($description);
    }

    public function getServiceRequest(RequestParameters $rp)
    {
        // Build service request objest
        $sr = new ServiceRequestBuilder($this->getApiService($rp->getMethod()), $this->getApiServiceAction($rp->getMethod()));
        $sr->fromRequest($rp, $this->getDescription());

        return $sr->build();
    }

}
