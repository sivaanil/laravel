<?php

namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ResourceProcessor;
use Unified\Http\Controllers\Api\Response\OkResponse;
use Unified\Http\Controllers\Api\Response\UnauthorizedResponse;
use Unified\Services\SitePortalAPI\Authentication;

/**
 * API login handler.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class LoginProcessor extends ResourceProcessor {
    public function __construct() {
        $description = [ 
                ResourceDescription::POST => [ 
                        ResourceDescription::MANDATORY => [ 
                                'username',
                                'password' 
                        ],
                        ResourceDescription::OPTIONAL => [ ] 
                ] 
        ];
        parent::__construct ( $description );
    }
    public function processRequest(RequestParameters $rp) {
        $service = new Authentication ();
        $token = $service->GetNewToken ( $rp->getContentByKey ( 'username' ), $rp->getContentByKey ( 'password' ) );
        
        if ($token === false) {
            return new UnauthorizedResponse ( 'Invalid credentials' );
        }
        
        return new OkResponse ( [ 
                'token' => $token 
        ] );
    }
}
