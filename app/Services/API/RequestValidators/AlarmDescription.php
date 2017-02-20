<?php

namespace Unified\Services\API\RequestValidators;

/**
 * Alarm description validators.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class AlarmDescription {
    public function getAlarmSeveritiesValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => [ 
                        'id' => 'id',
                        'name' => 'description' 
                ] 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    public function getAlarmRuleTypesValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => [ 
                        'id' => 'id',
                        'name' => 'description' 
                ] 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    public function getAlarmsValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => AlarmFields::alarmParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    public function getAlarmByIdValidator() {
        $description = [ 
                RequestValidator::MANDATORY => [ 
                        'id' => 'nda.id' 
                ],
                RequestValidator::OPTIONAL => AlarmFields::alarmParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        return $validator;
    }
    public function modifyAlarmValidator() {
        $description = [ 
                RequestValidator::MANDATORY => [ 
                        'id' => 'id' 
                ],
                RequestValidator::OPTIONAL => AlarmFields::editAlarmParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateContent ();
        return $validator;
    }
}
