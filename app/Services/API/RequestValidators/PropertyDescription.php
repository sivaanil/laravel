<?php

namespace Unified\Services\API\RequestValidators;

/**
 * Property description validators.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class PropertyDescription {
    public function getPropertyGroupsValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => [ 
                        'id' => 'id',
                        'name' => 'name' 
                ] 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    public function getPropertyTypesValidator() {
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
    public function getPropertyOptionsValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => [ 
                        'id' => 'id',
                        'propDefId' => 'prop_def_id',
                        'value' => 'value',
                        'text' => 'text',
                        'graphValue' => 'graph_value' 
                ] 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    public function getPropertyDefinitionsValidator() {
        $description = [
                RequestValidator::OPTIONAL => PropertyFields::propertyDefinitionsParams ()
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    public function getPropertiesValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => PropertyFields::propertyParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    public function getPropertyByIdValidator() {
        $description = [ 
                RequestValidator::MANDATORY => ['id' => 'ndp.id'], 
                RequestValidator::OPTIONAL => PropertyFields::propertyParams () 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    public function modifyPropertyValidator() {
        $description = [ 
                // ID fields is mandatory and should not be modified, so map it to the null
                RequestValidator::MANDATORY => ['id' => 'id'], 
                RequestValidator::OPTIONAL => PropertyFields::editPropertyParams () 
                ];
        $validator = new RequestValidator ( $description );
        $validator->validateContent ();
        return $validator;
    }
    public function getPropertyLogsValidator() {
        $description = [ 
                RequestValidator::OPTIONAL => [ 
                        'propId' => 'ndpl.prop_id',
                        'nodeId' => 'nnt.id',
                        'uuid' => 'ndp.uuid',
                        'value' => 'ndpl.value',
                        'created' => 'UNIX_TIMESTAMP(ndpl.date_created)' 
                ] 
        ];
        $validator = new RequestValidator ( $description );
        $validator->validateFields ();
        $validator->validateFilters ();
        return $validator;
    }
    
}
