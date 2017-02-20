<?php

namespace Unified\Services\API;

use Unified\Models\AlarmRuleType;
use Unified\Models\AlarmSeverity;
use Unified\Models\DeviceAlarm;
use Unified\Services\API\RequestValidators\RequestValidator;
use Unified\Services\API\ServiceResponse;

/**
 * Handles Alarm related functions for the API.
 */
class AlarmService extends APIService {
    /**
     * Alarm Service constructor.
     *
     * @param ServiceRequest $request
     *            Service request
     */
    public function __construct(ServiceRequest $request) {
        parent::__construct ( $request, RequestValidator::getValidator ( $request->getType (), $request->getAction () ) );
    }
    
    /**
     * Return list of Alarm severities
     *
     * @return Service response object with the following status codes:
     */
    public function getAlarmSeverities() {
        return new ServiceResponse ( ServiceResponse::SUCCESS, AlarmSeverity::getAlarmSeverities ( $this->getQueryParameters() ) );
    }
    
    /**
     * Return list of Alarm rule types
     *
     * @return Service response object with the following status codes:
     */
    public function getAlarmRuleTypes() {
        return new ServiceResponse ( ServiceResponse::SUCCESS, AlarmRuleType::getAlarmRuleTypes ( $this->getQueryParameters() ) );
    }

    /**
     * Return list of Alarms
     *
     * @return Service response object with the following status codes:
     */
    public function getAlarms() {
        $retVal = DeviceAlarm::getAlarms ( $this->getQueryParameters() );
        // Structurize result object
        RequestValidator::structurizeObject ( $retVal );
        return new ServiceResponse ( ServiceResponse::SUCCESS, $retVal );
    }

    /**
     * Return Alarm with specified ID
     *
     * @return Service response object with the following status codes:
     */
    public function getAlarmById() {
        // Utilize DeviceAlarm::getAlarms call. Incoming filters should already have filter by alarm id.
        $alarm = DeviceAlarm::getAlarms ( $this->getQueryParameters() );
        // Structurize result object
        RequestValidator::structurizeObject ( $alarm );
        if (count($alarm['alarms']) == 0) {
            return new ServiceResponse ( ServiceResponse::NOT_FOUND );
        } else {
            return new ServiceResponse ( ServiceResponse::SUCCESS, $alarm );
        }
    }
    
    /**
     * Modify Alarm
     *
     * @return Service response object with the following status codes:
     */
    public function modifyAlarm() {
        $content = $this->getContent();
        // Verify alarm ID
        $alarmId = isset ( $content ['id'] ) ? $content ['id'] : null;
        if (is_null ( $alarmId )) {
            return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY, 
                    array (
                            'error' => 'Alarm ID is empty.' 
                    ) );
        }
        $alarm = DeviceAlarm::where ('id','=', $alarmId)->first ();
        if ($alarm == false || count ( $alarm ) == 0) {
            return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY, 
                    array (
                            'error' => 'Alarm does not exists.' 
                    ) );
        }
        unset($content['id']);
        
        // Convert datetime content attributes from UTC to local
        QueryParameters::convertUtcToLocalTime ( $content, 'raised' );
        QueryParameters::convertUtcToLocalTime ( $content, 'cleared' );
        QueryParameters::convertUtcToLocalTime ( $content, 'ignoreUntil' );
    
        $retVal = $alarm->modifyAttributes($content);
    
        if (isset ( $retVal ['status'] ) && $retVal ['status']) {
            return new ServiceResponse ( ServiceResponse::SUCCESS, [] );
        } else {
            return new ServiceResponse ( ServiceResponse::UNPROCESSABLE_ENTITY,
                    array ('error' => $retVal ['error']) );
        }
    }
    
}
