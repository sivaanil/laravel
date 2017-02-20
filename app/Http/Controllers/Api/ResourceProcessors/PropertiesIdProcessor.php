<?php

namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ServiceRequestBuilder;

/**
 * API V1 Properties/{ID} handler.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class PropertiesIdProcessor extends ApiServiceRequest {
    public function __construct() {
        $queryParameters = [ 
                'all',
                'fields' 
        ];
        $description = [ 
                ResourceDescription::GET => [ 
                        ApiServiceRequest::API_SERVICE => 'Property',
                        ApiServiceRequest::API_SERVICE_ACTION => 'getPropertyById',
                        ApiServiceRequest::CONTROL => $queryParameters,
                        ApiServiceRequest::DEFAULT_FIELDS => [ 
                                'id',
                                'propDefId',
                                'nodeId',
                                'value' 
                        ], 
                        ResourceDescription::OPTIONAL => $queryParameters 
                ],
                ResourceDescription::PUT => [ 
                        ApiServiceRequest::API_SERVICE => 'Property',
                        ApiServiceRequest::API_SERVICE_ACTION => 'modifyProperty' 
                ]
        ]
        ;
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
