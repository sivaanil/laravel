<?php

namespace Modules\Enterprise\Providers;

use Modules\Enterprise\Models\VesServiceNode;
use Unified\Services\API\APIService;
use Unified\Services\API\RequestValidators\RequestValidator;
use Unified\Services\API\ServiceRequest;
use Unified\Services\API\ServiceResponse;

/**
 * Handles VES Service node related functions for the API.
 */
class VesServiceNodeService extends APIService {
    /**
     * Node Service constructor.
     *
     * @param ServiceRequest $request
     *            Service request
     */
    public function __construct(ServiceRequest $request) {
        parent::__construct ( $request, RequestValidator::getValidator ( $request->getType (), $request->getAction (), 'Modules\\Enterprise\\Validators\\' ) );
    }
    
    /**
     * Return list of nodes
     *
     * @return Service response object with the following status codes:
     */
    public function getNodes() {
        $nodes = VesServiceNode::getNodes ( $this->getQueryParameters () );
        // Structurize result object
        RequestValidator::structurizeObject ( $nodes );
        return new ServiceResponse ( ServiceResponse::SUCCESS, $nodes );
    }
}
