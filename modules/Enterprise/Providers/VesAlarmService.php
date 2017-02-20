<?php

namespace Modules\Enterprise\Providers;

use Modules\Enterprise\Models\VesAlarm;
use Unified\Services\API\APIService;
use Unified\Services\API\RequestValidators\RequestValidator;
use Unified\Services\API\ServiceRequest;
use Unified\Services\API\ServiceResponse;

/**
 * Handles Alarm related functions for the API.
 */
class VesAlarmService extends APIService {
    /**
     * Alarm Service constructor.
     *
     * @param ServiceRequest $request
     *            Service request
     */
    public function __construct(ServiceRequest $request) {
        parent::__construct ( $request, RequestValidator::getValidator ( $request->getType (), $request->getAction (), 'Modules\\Enterprise\\Validators\\' ) );
    }
    
    /**
     * Return list of Alarms
     *
     * @return Service response object with the following status codes:
     */
    public function getAlarms() {
        $retVal = VesAlarm::getAlarms ( $this->getQueryParameters () );
        // Structurize result object
        RequestValidator::structurizeObject ( $retVal );
        return new ServiceResponse ( ServiceResponse::SUCCESS, $retVal );
    }
}
