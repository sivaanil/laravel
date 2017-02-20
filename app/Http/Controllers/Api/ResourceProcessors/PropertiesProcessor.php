<?php

namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ServiceRequestBuilder;

/**
 * API V1 Properties processor.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class PropertiesProcessor extends ApiServiceRequest {
    public function __construct() {
        $description = [ 
                ResourceDescription::GET => [ 
                        ApiServiceRequest::API_SERVICE => 'Property',
                        ApiServiceRequest::API_SERVICE_ACTION => 'getProperties',
                        ApiServiceRequest::CONTROL => [ 
                                'offset',
                                'limit',
                                'count',
                                'fields',
                                'sortby',
                                'all',
                                'updated',
                                'root'
                        ],
                        ApiServiceRequest::DEFAULT_FIELDS => [ 
                                'id',
                                'name',
                                'nodeId',
                                'nodeTypeId',
                                'value' 
                        ] 
                ] 
        ];
        parent::__construct ( $description );
    }
    public function getServiceRequest(RequestParameters $rp) {
        // Build service request objest
        $sr = new ServiceRequestBuilder ( $this->getApiService ( $rp->getMethod () ), 
                $this->getApiServiceAction ( $rp->getMethod () ) );
        $sr->fromRequest ( $rp, $this->getDescription () );
        
        return $sr->build ();
    }
}
