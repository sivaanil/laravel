<?php

namespace Modules\Enterprise\Validators;

use Unified\Services\API\RequestValidators\RequestValidator;

/**
 * Alarm description validators.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class VesAlarmDescription {
    public function getAlarmsValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => AlarmFields::alarmParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
}
