<?php

namespace Modules\Enterprise\Providers;

use Modules\Enterprise\Models\VesRadioNode;
use Unified\Services\API\APIService;
use Unified\Services\API\RequestValidators\RequestValidator;
use Unified\Services\API\ServiceRequest;
use Unified\Services\API\ServiceResponse;

/**
 * Handles VES radio node related functions for the API.
 */
class VesRadioNodeService extends APIService {
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
        $nodes = VesRadioNode::getNodes ( $this->getQueryParameters () );
        // Structurize result object
        RequestValidator::structurizeObject ( $nodes );
        return new ServiceResponse ( ServiceResponse::SUCCESS, $nodes );
    }
}
