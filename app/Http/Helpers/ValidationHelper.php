<?php

namespace Unified\Http\Helpers;

/**
 * API validator Helper.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class ValidationHelper {
    
    /**
     * Returns value by index
     * @param unknown $array Array to be search
     * @param unknown $key Traget key
     * @return mixed|NULL Target value
     */
    public static function getControlParam(&$array, $key) {
        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }
        return null;
    }
    
    /**
     * Function starts hierarchical validation of $array against $descrArray.
     *
     * @param unknown $errorMessage
     *            Array type
     * @param unknown $array
     *            Incoming array
     * @param unknown $descrArray
     *            Array listing allowed elements
     * @return unknown List of error messages
     */
    public static function compareArrays($errorMessage, $array, $descrArray) {
        $results = [ ];
        
        // Initialize arrays if they are empty
        if (empty ( $array )) {
            $array = [ ];
        }
        
        if (empty ( $descrArray )) {
            $descrArray = [ ];
        }
        self::compareKeys ( '', $errorMessage, $array, $descrArray, $results );
        
        return $results;
    }
    /**
     * Define if array is indexed.
     * Function is used to distinguish between
     * subobject and list of the same type objects during parameter validation.
     * 
     * @param unknown $array            
     */
    public static function isIndexedArray($array) {
        if (!is_array($array)) {
            return false;
        }
        return array_keys ( $array ) === range ( 0, count ( $array ) - 1 );
    }
    
    /**
     * Function check if all keys are present in $array array is also present in $descrArray
     * and recursively validates child objects if they are present
     *
     * @param string $path
     *            recursion path
     * @param string $errorMessage
     *            Error message
     * @param unknown $array
     *            Incoming array
     * @param unknown $descrArray
     *            Description array
     * @param unknown $results
     *            Error string array
     */
    private static function compareKeys($path, $errorMessage, $array, $descrArray, &$results) {
        // Be aware that Laravel returns only one last parameter in case of multiple
        // parameters with the same name
        $isDescrIndexed = self::isIndexedArray ( $descrArray );
        $isArrayIndexed = self::isIndexedArray ( $array );

        // check if array of objects is expected
        if ($isDescrIndexed && ! $isArrayIndexed) {
            $results [] = "Expected array of objects {$path}";
            return;
        }
        
        // check if subobjects are expected
        if (is_array($array) != is_array($descrArray)) {
            $results [] = "Expected subobject of {$path}";
            return;
        }
        
         // Check if indexed array of objects is present in the $array
        if ($isArrayIndexed) {
            
            // check if array of objects is allowed in parameter description
            if (! $isDescrIndexed) {
                $results [] = "Unexpected array of objects {$path}";
                return;
            }

            // Compare next level
            foreach ( $array as $k => $v ) {
                self::compareKeys ( $path . "[$k]", $errorMessage, $v, $descrArray [0], $results );
            }
            return;
        }

        // Do quick check to verify that all top level parameter names are allowed.
        if (! (count ( array_intersect ( array_keys ( $array ), array_keys ( $descrArray ) ) ) === count ( 
                array_keys ( $array ) ))) {
            // Some of the incoming parameter names are not present in the list of allowed parameter names
            foreach ( $array as $k => $v ) {
                if (! array_key_exists ( $k, $descrArray )) {
                    $results [] = "{$errorMessage} {$path}[{$k}]";
                }
            }
            return;
        }
        
        // Check if incoming array has subobjects
        if (count ( $array ) !== count ( $array, COUNT_RECURSIVE )) {
            // For each $array element with subojects find corresponding $descrArray element
            // to validate parameters in subobject
            foreach ( $array as $k => $v ) {
                if (! is_array ( $v )) {
                    // Plain element. Go to next one
                    continue;
                }
                
                // Check if subobjects are allowed for curent element
                if (! array_key_exists ( $k, $descrArray ) || ! is_array ( $descrArraySubobject = $descrArray [$k] )) {
                    $results [] = "Subobjects are not allowed for {$path}[{$k}]";
                    continue;
                }
                
                // Make recursive call to check subobject parameters.
                self::compareKeys ( $path . $k, $errorMessage, $v, $descrArraySubobject, $results );
            }
        }
    }
}
