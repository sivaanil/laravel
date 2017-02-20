<?php

namespace Unified\Services\API\RequestValidators;

use Exception;
use stdClass;
use Unified\Http\Helpers\ValidationHelper;
use Unified\Services\API\ServiceRequest;
use Unified\Http\Helpers\QueryParameters;

/**
 * API Request Validator.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
class RequestValidator
{
    // Request description const
    const MANDATORY = 'mandatory';
    const OPTIONAL = 'optional';
    private $requestDescription;
    private $validateFields = false;
    private $validateSortby = false;
    private $validateFilters = false;
    private $validateContent = false;
    private $customContentValidator = null;
    private $allowedFilterConditions = [];
    private $allowedSortbyConditions = [];
    
    public static function getDefaultValidator(ServiceRequest $request) {
        $validator = new RequestValidator([]);
        // Default validator should be used only for requests without any parameters, validate everything.
        $validator->validateFilters();
        $validator->validateContent();
        return $validator;
    }
    /**
     * Returns request validator.
     *
     * @param unknown $type
     *            Service type
     * @param unknown $action
     *            Service action
     *            
     * @return Instance of request validator or exception
     */
    public static function getValidator($type, $action, $path = 'Unified\\Services\\API\\RequestValidators\\') {
        $classname = $path . $type . 'Description';
        if (class_exists ( $classname )) {
            $instance = new $classname ();
            $getValidator = $action . 'Validator';
            if (is_callable ( 
                    array (
                            $instance,
                            $getValidator 
                    ) )) {
                return $instance->$getValidator ();
            }
        }
        throw new Exception ( 'Unable to find request validator' );
    }
    
    /**
     * Hide constructor to enforce usera to initiate object using getValidator()
     *
     * @param unknown $description            
     */
    public function __construct($description) {
        $this->requestDescription = $description;
        $this->allowedFilterConditions = [ 
                'eq' => '=',
                'ne' => '<>',
                'gt' => '>',
                'gte' => '>=',
                'lt' => '<',
                'lte' => '<=',
                'isnull' => 'isnull',
                'isnotnull' => 'isnotnull' 
        ];
        
        $this->allowedSortbyConditions = [ 
                'asc' => 'ASC',
                'desc' => 'DESC' 
        ];
    }
    public function validateSortby() {
        $this->validateSortby = true;
    }
    public function validateFields() {
        $this->validateFields = true;
    }
    public function validateFilters() {
        $this->validateFilters = true;
    }
    public function validateContent(callable $customContentValidator = null) {
        $this->validateContent = true;
        $this->customContentValidator = $customContentValidator;
    }
    
    /**
     * Returns all fields
     */
    public function getAllFields() {
        return $this->unalias (  $this->getFieldMap (), 'field' );
    }
    
    /**
     * Returns unaliased fields
     *
     * @param ServiceRequest $sr            
     */
    public function unaliasFields(ServiceRequest $sr) {
        if (is_null ( self::getParameter ( 'all', $sr->getDataParameter ( 'control' ) ) )) {
            return $this->unalias ( $sr->getDataParameter ( 'fields' ), 'field' );
        } else {
            return $this->getAllFields ();
        }
    }
    
    /**
     * Returns unaliased sortby
     *
     * @param ServiceRequest $sr            
     */
    public function unaliasSortby(ServiceRequest $sr) {
        return $this->unalias ( $sr->getDataParameter ( 'sortby' ), 'sortby' );
    }
    
    /**
     * Returns unaliased filters
     *
     * @param ServiceRequest $sr            
     */
    public function unaliasFilters(ServiceRequest $sr) {
        return $this->unalias ( $sr->getDataParameter ( 'filter' ), 'filter' );
    }
    
    /**
     * Returns unaliased content
     *
     * @param ServiceRequest $sr            
     */
    public function unaliasContent(ServiceRequest $sr) {
        return $this->unalias ( $sr->getDataParameter ( 'content' ), 'content' );
    }
    
    /**
     * Validates API Service request parameters.
     *
     * @param ServiceRequest $request            
     * @return unknown List of validation errors
     */
    public function validate(ServiceRequest $request) {
        $results = array ();
        if ($this->validateFields) {
            $results = $this->validateArray ( 'field', $request->getDataParameter ( 'fields' ) );
        }
        if ($this->validateSortby) {
            $results = array_merge ( $this->validateArray ( 'sortby', $request->getDataParameter ( 'sortby' ) ), 
                    $results );
        }
        if ($this->validateFilters) {
            $results = array_merge ( $this->validateArray ( 'filter', $request->getDataParameter ( 'filter' ) ), 
                    $results );
        }
        if ($this->validateContent) {
            $results = array_merge ( 
                    $this->validateArray ( 'content parameter', $request->getDataParameter ( 'content' ) ), 
                    $results );
            if ($this->customContentValidator != null && is_callable ( $this->customContentValidator )) {
                $results = array_merge ( 
                        call_user_func_array ( $this->customContentValidator, 
                                array (
                                        $request->getDataParameter ( 'content' ) 
                                ) ), 
                        $results );
            }
        }
        return $results;
    }
    
    private function validateAndRemoveConditions(&$array, $conditionDictionary) {
        $errorList = [];
        foreach ( $array as $key => $value ) {
            if (is_array($value)) {
                $array[$key] = $this->validateAndRemoveConditions($value, $conditionDictionary);
            } else {
                // in case of invalid condition exception will be thrown from parseCondition
                // Catch it and add to the error list
                try {
                    $condition = '';
                    $origKey = $key;
                    $this->parseCondition($key, $condition, $conditionDictionary);
                    if (!empty($condition)) {
                        // Remove condition for more detailed future array verification
                        unset($array[$origKey]);
                        $array[$key] = $value;
                    }
                } catch (Exception $e) {
                    $errorList[] = $e->getMessage();
                }
            }
        }
        return $errorList;
    }
    
    /**
     * Validate array against provided request descritpion.
     *
     * @param $arrayName Name
     *            of the validated field
     * @param $array $request
     *            Array to be validated
     * @return unknown List of validation errors
     */
    private function validateArray($arrayName, $array) {
        // Check and remove filter and sortby conditions if present
        $resConditionValidation = [];
        if ($arrayName == 'filter') {
            $resConditionValidation = $this->validateAndRemoveConditions($array, $this->allowedFilterConditions);
        } else if ($arrayName == 'sortby') {
            $resConditionValidation = $this->validateAndRemoveConditions($array, $this->allowedSortbyConditions);
        }
        
        // Check mandatory parameters
        $resMandatory = ValidationHelper::compareArrays ( 'Expected ' . $arrayName, 
                $this->getDescription ( RequestValidator::MANDATORY ), 
                $array );
        
        // Check optional parameters
        $resOptional = ValidationHelper::compareArrays ( 'Unknown ' . $arrayName, $array, $this->getFieldMap () );
        return array_merge ($resConditionValidation, $resMandatory, $resOptional );
    }
    /**
     * Finds description of request parameters by type
     *
     * @param string $type
     *            Description type
     */
    private function getDescription($type) {
        if (is_array ( $this->requestDescription ) && array_key_exists ( $type, $this->requestDescription )) {
            return $this->requestDescription [$type];
        }
        return [ ];
    }
    
    /**
     * Returns table mapping REST API fields with DB tables fields
     */
    public function getFieldMap() {
       return array_merge ( $this->getDescription ( RequestValidator::OPTIONAL ),
                $this->getDescription ( RequestValidator::MANDATORY ) );
    }
    
    /**
     * Translate from incoming API parameters to model parameters
     *
     * @param unknown $incoming
     *            Incoming API parameters
     * @param unknown $type
     *            API parameter type
     * @return s List of model parameters
     */
    public function unalias($incoming, $type) {
        // Merge optional and mandatory parameters
        $params =  $this->getFieldMap ();
        $output = [ ];
        
        $this->unaliasArray ( $incoming, $output, $params, $type, '' );
        return $output;
    }
    
    private function parseCondition(&$apiParam, &$condition, $conditionDictionary) {
            // Check if parameter contains condition
        $arr = explode(':',$apiParam, 2);
        if (count($arr) <= 1) {
            // Condition is not present, so use default condition
            return;
        }
        
        // Condition found
        
        // Remove condition from parameter string
        $apiParam = $arr[0];
        // Try to find condition in provided dictionary
        $condition = $this->getParameter($arr[1], $conditionDictionary);
        if (is_null($condition)) {
            // Unknown condition has been requested
            throw new ValidationException ( 'Unknown condition '.$arr[1]);
        }
        return;
    }
    
    private function unaliasArray($incoming, &$output, $params, $type, $path) {
        
        if (!is_array($incoming)) {
            throw new Exception ( "Expected array parameter for {$type} '{$path}'" );
        }
        
        foreach ( $incoming as $apiParam => $apiValue ) {
            $condition = '';
                        
            // Check for filter conditions
            if ($type == 'filter') {
                $condition = '=';
                $this->parseCondition($apiParam, $condition, $this->allowedFilterConditions);
            } else if ($type == 'sortby') {
                $condition = 'ASC';
                $this->parseCondition($apiParam, $condition, $this->allowedSortbyConditions);
            }
            
            if (isset ( $params [$apiParam] )) {
                $paramVal = $params [$apiParam];
                if (is_array ( $paramVal )) {
                    if (is_array ( $apiValue )) {
                        // Check if array of the same type objects is requested
                        if (ValidationHelper::isIndexedArray($paramVal)) {
                            // Behaviour is different for content and fields/groups
                            // Field/groups need just add elements to the same level of the output array
                            // Content need to allow to handle multiple elements of the same type (ports for device)
                            // so it needs add one more level to the output elements
                            if ($type === 'content') {
                                $output[$apiParam] = [];
                                foreach ($apiValue as $index => $arrayElement) {
                                    $subarray = [];
                                    $this->unaliasArray ( $arrayElement, $subarray, $paramVal[0], $type, '' );
                                    $output[$apiParam][] = $subarray;
                                }
                            } else {
                                foreach ($apiValue as $index => $arrayElement) {
                                    $this->unaliasArray ( $arrayElement, $output, $paramVal[0], $type, '' );
                                }
                            }
                        } else {
                            // Parse simple subobject
                            if (empty ( $apiParam )) {
                                // Array is requested in the list of fields
                                // Start to create hierarchical structure from scratch. Current one will be present in the name of array
                                $this->unaliasArray ( $apiValue, $output, $paramVal, $type, '' );
                            } else {
                                $this->unaliasArray ( $apiValue, $output, $paramVal, $type, $path . $apiParam . '_' );
                            }
                        }
                    } else {
                        // Just name of the object is present in 'fields'.
                        // Add all subobjects to be returned
                        if ($type === 'field') {
                            $this->unaliasObjectRecords ( $type, $output, $paramVal, $path . $apiParam . '_' );
                        } else {
                            throw new Exception ( "Expected subobjects for '{$apiParam}'" );
                        }
                    }
                } else {
                    if (is_array ( $apiValue )) {
                        throw new Exception ( "Unexpected subobjects for {$type} '{$apiParam}'" );
                    }
                    $this->unaliasRecord ( $type, $output, $paramVal, $apiParam, $apiValue, $path, $condition );
                }
            } else {
                throw new Exception("Unknown {$type} '{$apiParam}' ");
            }
        }
    }
    private function unaliasObjectRecords($type, &$output, $paramVal, $path) {
        foreach ( $paramVal as $apiChildParameter => $modelChildParameter ) {
            if (is_array ( $modelChildParameter )) {
                if (empty($apiChildParameter)) {
                    // Array is requested in the list of fields
                    // Start to create hierarchical structure from scratch. Current one will be present in the name of array
                    $this->unaliasObjectRecords ( $type, $output, $modelChildParameter, '' );
                } else {
                    // Go deeper
                    $this->unaliasObjectRecords ( $type, $output, $modelChildParameter, $path . $apiChildParameter . '_' );
                }
            } else {
                $this->unaliasRecord ( $type, $output, $modelChildParameter, $apiChildParameter, true, $path, "=" );
            }
        }
        return $output;
    }
    
    private function unaliasRecord($type, &$output, $modelParameter, $apiParam, $apiValue, $path, $condition) {
        if (empty($modelParameter)) {
            throw new Exception ( "Unknown {$type} '{$apiParam}'" );
        }
        
        if ($type === 'field') {
            $output [] = $modelParameter . ' as ' . $path . $apiParam;
        } else if ($type === 'filter') {
            $output [] = [ 
                    $modelParameter,
                    $condition,
                    $apiValue 
            ];
        } else if ($type === 'sortby') {
            $output [] = [ 
                    $modelParameter,
                    $condition
            ];
        } else if ($type === 'content') {
            $output [ $modelParameter ] = $apiValue;
        }
    }
    
    /**
     * Find af any array value starts with provided substring
     * 
     * @param unknown $array
     *            Array with string values
     * @param unknown $startWith
     *            Start string top be searched for
     *            
     * @return Array of records removed from target array
     */
    public static function isValueStartingWith($array, $startsWith) {
        foreach ( $array as $key => $value ) {
            if (strpos ( $value, $startsWith ) === 0) {
                return true;
            }
        }
        return false;
    }
    
    /**
     *
     * @param unknown $object
     *            Object to be structurized. Each key containing '_' will be converted to subobjects.
     *            
     * @return s Array of structurized items
     */
    public static function structurizeObject(&$object) {
        if (is_array ( $object )) {
            // If array - iterate recursively through it.
            foreach ( $object as $element ) {
                self::structurizeObject ( $element );
            }
        } else {
            if (is_object ( $object )) {
                // If object - iterate through each element
                foreach ( $object as $key => $value ) {
                    // If element contains '_' - create subobject
                    if (strpos ( $key, '_' ) !== false) {
                        // get part before first '_' (object name) and after (subobjects path)
                        $path = explode ( '_', $key, 2 );
                        // Check if property with object name exists
                        if (! property_exists ( $object, $path [0] )) {
                            // If not - create property with object name
                            $object->{$path [0]} = new stdClass ();
                        }
                        // Add subobject to the object
                        $object->{$path [0]}->{$path [1]} = $value;
                        // Remove old value
                        unset ( $object->$key );
                            // Check subobject
                        self::structurizeObject ( $object->{$path [0]} );
                    } else if (is_array ( $value )) {
                        // check if value is array that needs to be structurized as well
                        foreach ( $value as $element ) {
                                self::structurizeObject ( $element );
                        }
                    }
                }
            }
        }
        return $object;
    }
    
    /**
     * Find particular parameter valu in array
     * 
     * @param unknown $parameter
     *            Target parameter
     * @return mixed|NULL
     */
    public static function getParameter($parameter, $array) {
        if (is_array ( $array ) && array_key_exists ( $parameter, $array )) {
            return $array [$parameter];
        }
        return null;
    }

    /**
     * get query parameters
     *  
     * @param unknown $request
     * @return \Unified\Http\Helpers\QueryParameters
     */
    public function getQueryParameters($request)
    {
        $filters = $this->unaliasFilters($request);
        $fields = $this->unaliasFields($request);
        $sortby = $this->unaliasSortby($request);
        $control = $request->getDataParameter('control');
        return new QueryParameters($fields, $filters, $control, $sortby);
    }
    
    final public function getContent($request) {
        return $content = $this->unaliasContent ( $request );
    }
    
}
