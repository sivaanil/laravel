<?php

namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ServiceRequestBuilder;

/**
 * API V1 Notifications processor.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class NotificationsProcessor extends ApiServiceRequest {
    public function __construct() {
        $description = [ 
                ResourceDescription::GET => [ 
                        ApiServiceRequest::API_SERVICE => 'Notifications',
                        ApiServiceRequest::API_SERVICE_ACTION => 'getNotifications',
                        ApiServiceRequest::CONTROL => [ 
                                'all',
                                'offset',
                                'limit',
                                'count',
                                'sortby',
                                'fields' 
                        ],
                        ApiServiceRequest::DEFAULT_FIELDS => [ 
                                'id',
                                'snmpDestId',
                                'nodeId' 
                        ] 
                ],
                ResourceDescription::POST => [ 
                        ApiServiceRequest::API_SERVICE => 'Notifications',
                        ApiServiceRequest::API_SERVICE_ACTION => 'addNotifications' 
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
