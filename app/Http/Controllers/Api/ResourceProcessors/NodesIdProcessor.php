<?php

namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Auth;
use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ServiceRequestBuilder;

/**
 * API V1 Nodes/{ID} handler.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class NodesIdProcessor extends ApiServiceRequest {
    public function __construct() {
        $controlParameters = [ 
                'all',
                'depth',
                'children',
                'hasChildren',
                'fields' 
        ];
        $description = [ 
                ResourceDescription::GET => [ 
                        ApiServiceRequest::API_SERVICE => 'Node',
                        ApiServiceRequest::API_SERVICE_ACTION => 'getNodeById',
                        ApiServiceRequest::CONTROL => $controlParameters,
                        ApiServiceRequest::DEFAULT_FIELDS => [ 
                                'id',
                                'name',
                                'parent' 
                        ],
                        ResourceDescription::OPTIONAL => $controlParameters 
                ],
                ResourceDescription::PUT => [ 
                        ApiServiceRequest::API_SERVICE => 'Node',
                        ApiServiceRequest::API_SERVICE_ACTION => 'modifyNode' 
                ],
                ResourceDescription::DELETE => [ 
                        ApiServiceRequest::API_SERVICE => 'Node',
                        ApiServiceRequest::API_SERVICE_ACTION => 'deleteNode' 
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
        $nodeId = $rp->getRequest ()->segment ( $rp->getSegment ());
        if ($nodeId == 0) {
            $nodeId = Auth::user()->home_node_id;
        }
        if ($rp->getMethod () === 'GET') {
            // Add ID from last URI segment in the content of request
            $sr->addFilterParam ( 'id', $nodeId );
        } else {
            // Add ID from last URI segment in the content of request
            $sr->addContentParam ( 'id', $nodeId );
        }
        
        return $sr->build ();
    }
}
