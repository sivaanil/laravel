<?php

namespace Unified\Services\API\RequestValidators;

/**
 * SnmpDest description validators.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class SnmpDestDescription {
    public function getSnmpDestValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => SnmpDestFields::snmpDestParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    public function getSnmpDestByIdValidator() {
        $description = [ 
                RequestValidator::MANDATORY => [ 
                        'id' => 'id' 
                ],
                RequestValidator::OPTIONAL => SnmpDestFields::snmpDestParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        return $validator;
    }
    public function modifySnmpDestValidator() {
        $description = [ 
                RequestValidator::MANDATORY => [ 
                        'id' => 'id' 
                ],
                RequestValidator::OPTIONAL => 
                    SnmpDestFields::snmpDestMandatoryParams () + 
                    SnmpDestFields::editSnmpDestParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateContent ();
        return $validator;
    }
    public function addSnmpDestValidator() {
        $description = [ 
                RequestValidator::MANDATORY => SnmpDestFields::snmpDestMandatoryParams(),
                RequestValidator::OPTIONAL => SnmpDestFields::editSnmpDestParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateContent ();
        return $validator;
    }
    public function deleteSnmpDestValidator() {
        $description = [ 
                RequestValidator::MANDATORY => [ 
                        'id' => 'id' 
                ],
                RequestValidator::OPTIONAL => [
                ] 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateContent ();
        return $validator;
    }
}
