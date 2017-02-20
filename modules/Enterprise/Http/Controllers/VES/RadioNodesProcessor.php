<?php

namespace Modules\Enterprise\Http\Controllers\VES;

use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ServiceRequestBuilder;
use Unified\Http\Controllers\Api\ResourceProcessors\ApiServiceRequest;

/**
 * API Radio Node handler.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class RadioNodesProcessor extends ApiServiceRequest {
    public function __construct() {
        $description = [ 
                ResourceDescription::GET => [ 
                        ApiServiceRequest::API_SERVICE => 'VesRadioNode',
                        ApiServiceRequest::API_SERVICE_ACTION => 'getNodes',
                        ApiServiceRequest::CONTROL => [ 
                                'offset',
                                'limit',
                                'count',
                                'fields',
                                'all'
                        ],
                        ApiServiceRequest::DEFAULT_FIELDS => [ 
                                'serialNumber',
                                'name'
                        ] 
                ]
        ];
        parent::__construct ( $description );
    }
    public function getServiceRequest(RequestParameters $rp) {
        // Build service request objest
        $sr = new ServiceRequestBuilder ( $this->getApiService ( $rp->getMethod () ), 
                $this->getApiServiceAction ( $rp->getMethod () ), 'Modules\\Enterprise\\Providers\\' );
        $sr->fromRequest ( $rp, $this->getDescription () );
        
        return $sr->build ();
    }
}
