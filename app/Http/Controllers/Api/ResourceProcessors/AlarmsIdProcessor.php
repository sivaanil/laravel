<?php

namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ServiceRequestBuilder;

/**
 * API V1 Alarms/{ID} handler.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class AlarmsIdProcessor extends ApiServiceRequest {
    public function __construct() {
        $queryParameters = [ 
                'all',
                'fields' 
        ];
        $description = [ 
                ResourceDescription::GET => [ 
                        ApiServiceRequest::API_SERVICE => 'Alarm',
                        ApiServiceRequest::API_SERVICE_ACTION => 'getAlarmById',
                        ApiServiceRequest::CONTROL => $queryParameters,
                        ApiServiceRequest::DEFAULT_FIELDS => [ 
                                'id',
                                'nodeId',
                                'severityId',
                                'description',
                                'raised',
                                'cleared' 
                        ],
                        ResourceDescription::OPTIONAL => $queryParameters 
                ],
                ResourceDescription::PUT => [ 
                        ApiServiceRequest::API_SERVICE => 'Alarm',
                        ApiServiceRequest::API_SERVICE_ACTION => 'modifyAlarm' 
                ]
        ];
        parent::__construct ( $description );
    }
    public function getServiceRequest(RequestParameters $rp) {
        // Build service request objest
        $sr = new ServiceRequestBuilder ( $this->getApiService ( $rp->getMethod () ), 
                $this->getApiServiceAction ( $rp->getMethod () ) );
        $sr->fromRequest ( $rp, $this->getDescription () );
        $s12 = $sr->build ();
        if ($rp->getMethod () === 'GET') {
            // Add ID from last URI segment in the content of request
            $sr->addFilterParam ( 'id', $rp->getRequest ()->segment ( $rp->getSegment () ) );
        } else {
            // Add ID from last URI segment in the content of request
            $sr->addContentParam ( 'id', $rp->getRequest ()->segment ( $rp->getSegment () ) );
        }
        
        return $sr->build ();
    }
}
