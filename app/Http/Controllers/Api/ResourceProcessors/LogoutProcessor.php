<?php

namespace Unified\Http\Controllers\Api\ResourceProcessors;

use Unified\Http\Controllers\Api\RequestParameters;
use Unified\Http\Controllers\Api\ResourceDescription;
use Unified\Http\Controllers\Api\ResourceProcessor;
use Unified\Http\Controllers\Api\Response\BadRequestResponse;
use Unified\Http\Controllers\Api\Response\NoContentResponse;
use Unified\Http\Controllers\Api\Response\UnauthorizedResponse;
use Unified\Services\SitePortalAPI\Authentication;

/**
 * API logout handler.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class LogoutProcessor extends ResourceProcessor {
    public function __construct() {
        $description = [ 
                ResourceDescription::POST => [ ] 
        ];
        parent::__construct ( $description );
    }
    public function processRequest(RequestParameters $rp) {
        // Check header for authorization token
        $headerToken = $rp->getRequest ()->header ( 'Authorization' );
        
        if (empty ( $headerToken )) {
            return new BadRequestResponse ( 'Authentication token is not present' );
        }
        
        $service = new Authentication ();
        $response = $service->disableToken ();
        
        if ($response [0]) {
            return new NoContentResponse ();
        } else {
            return new UnauthorizedResponse ( $response [2] );
        }
    }
}
