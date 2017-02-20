<?php

namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ServiceRequestBuilder;

/**
 * API V1 Alarms processor.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class AlarmsProcessor extends ApiServiceRequest {
    public function __construct() {
        $description = [ 
                ResourceDescription::GET => [ 
                        ApiServiceRequest::API_SERVICE => 'Alarm',
                        ApiServiceRequest::API_SERVICE_ACTION => 'getAlarms',
                        ApiServiceRequest::CONTROL => [ 
                                'all',
                                'offset',
                                'limit',
                                'updated',
                                'count',
                                'root',
                                'sortby',
                                'fields' 
                        ],
                        ApiServiceRequest::DEFAULT_FIELDS => [ 
                                'id',
                                'nodeId',
                                'severityId',
                                'description',
                                'raised',
                                'cleared' 
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
