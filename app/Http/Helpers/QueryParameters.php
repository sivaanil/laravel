<?php

namespace Unified\Http\Helpers;

use DateTime;
use DateTimeZone;
use DB;

/**
 * Query config.
 */
final class QueryParameters {
    /* Field list */
    private $fields;
    /* Filter list */
    private $filters;
    /* Control parameters list */
    private $control;
    /* sortby parameters list */
    private $sortby;
    /* is count requested */
    private $isCount = false;
    /* record offset */
    private $offset = 0;
    /* record limit */
    private $limit = 1000;
    
    /**
     * Query configuration constructor
     *
     * @param unknown $fields
     *            Requested fields
     * @param unknown $filters
     *            Filters to be applied
     * @param unknown $control
     *            List of control parameters
     * @param unknown $sortby
     *            List of sortby parameters
     */
    public function __construct($fields, $filters, $control, $sortby = null) {
        if (count ( $fields ) == 0) {
            throw new Exception ( 'Empty list of requested fields' );
        }
        $this->fields = $fields;
        $this->filters = $filters;
        $this->control = $control;
        $this->sortby = $sortby;
        if (is_array ( $control )) {
            if (isset ( $control ['count'] ) || array_key_exists ( 'count', $control )) {
                $this->isCount = true;
            }
            if (isset ( $control ['offset'] ) || array_key_exists ( 'offset', $control )) {
                $this->offset = $control['offset'];
            }
            if (isset ( $control ['limit'] ) || array_key_exists ( 'limit', $control )) {
                $this->limit = $control['limit'];
            }
        }
    }
    
    /**
     * Returns list of requested fields
     *
     * @return unknown List of requested fields
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * Returns list of requested sort fields
     *
     * @return unknown List of requested sort fields
     */
    public function getSortby() {
        return $this->sortby;
    }
    
    /**
     * Returns list of filters to be applyed to the query
     *
     * @return unknown List of filters
     */
    public function getFilters() {
        return $this->filters;
    }
    
    /**
     * Returns list of control parameters
     *
     * @return unknown List of control parameters
     */
    public function getControl() {
        return $this->control;
    }
    
    /**
     * Returns request control parameter or null
     *
     * @param unknown $parameter
     *            Target control parameter
     * @return mixed|NULL
     */
    public function getControlParam($parameter) {
        if (is_array ( $this->control ) && array_key_exists ( $parameter, $this->control )) {
            return $this->control [$parameter];
        }
        return null;
    }
    
    /**
     * Pop elements starting with provided substring from array
     *
     * @param unknown $array
     *            Array with string values
     * @param unknown $startWith
     *            Start string to be searched for
     *            
     * @return Array of records removed from target array
     */
    public static function popElementsStartingWith(&$array, $startsWith) {
        $removed = array ();
        foreach ( $array as $key => $value ) {
            if (strpos ( $value, $startsWith ) === 0) {
                $removed [$key] = $value;
                unset ( $array [$key] );
            }
        }
        return $removed;
    }
    
    /**
     * Pop elements containing provided substring from array
     *
     * @param unknown $array
     *            Array with string values
     * @param unknown $str
     *            Substring to be searched for
     *            
     * @return Array of records removed from target array
     */
    public static function popElementsContainingString(&$array, $str) {
        $removed = array ();
        foreach ( $array as $key => $value ) {
            if (strpos ( $value, $str ) != false) {
                $removed [$key] = $value;
                unset ( $array [$key] );
            }
        }
        return $removed;
    }
    
    /**
     * Check if array contains element with provided substring
     *
     * @param unknown $array
     *            Array with string values
     * @param unknown $substr
     *            Target substring
     *            
     * @return true if substr is present in array elements, false otherwise
     */
    public static function isPresent(&$array, $substr) {
        foreach ( $array as $key => $value ) {
            if (strpos ( $value, $substr ) !== false) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Pop filters starting with provided substring from array
     *
     * @param unknown $array
     *            Target filters
     * @param unknown $startWith
     *            Start string to be searched for
     *            
     * @return Array of records removed from target array
     */
    public static function popFiltersStartingWith(&$filters, $startsWith) {
        $removed = array ();
        foreach ( $filters as $key => $filter ) {
            if (strpos ( $filter[0], $startsWith ) === 0) {
                $removed [] = $filter;
                unset ( $filters [$key] );
            }
        }
        return $removed;
    }
    /**
     * Check if filters contains element with provided substring
     *
     * @param unknown $filters
     *            Filters
     * @param unknown $substr
     *            Target substring
     *
     * @return true if substr is present in array elements, false otherwise
     */
    public static function isPresentInFilters(&$filters, $substr) {
        foreach ( $filters as &$filter ) {
            if (strpos ( $filter [0], $substr ) !== false) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Modify filter element
     *
     * @param unknown $name
     *            Filtered field
     * @param unknown $condition
     *            Condition
     * @param unknown $value
     *            Value
     */
    public function modifyFilter($name, $condition, $value) {
        foreach ( $this->filters as &$filter ) {
            if ($filter [0] == $name) {
                $filter [1] = $condition;
                $filter [2] = $value;
                return;
            }
        }
        $this->filters [] = [ 
                $name,
                $condition,
                $value 
        ];
        return;
    }
    
    /**
     * Function converts UTC unix timestamp to the string representation of local time
     *
     * @param unknown $utcTime
     *            UNIX timestamp
     */
    public static function getLocalTime(&$utcTime) {
        if (empty ( $utcTime )) {
            return null;
        }
        $dateTimeZone = new DateTimeZone ( date_default_timezone_get () );
        $localDate = new DateTime ( '@' . $utcTime );
        $localDate->setTimeZone ( $dateTimeZone );
        return $localDate->format ( 'Y-m-d H:i:s' );
    }
    
    /**
     * Check if flag "count" is set in query parameters
     *
     * @return boolean true - flag set, otherwise false
     */
    public function isCount() {
        return $this->isCount;
    }
    
    /**
     * Return count of total records for latest SQL query using SQL_CALC_FOUND_ROWS.
     * 
     * @return number
     */
    public static function getLatestCount() {
        $retObj = DB::select ( DB::raw ( 'SELECT FOUND_ROWS() AS TotalCount;' ) );
        if ($retObj != false) {
            return $retObj [0]->TotalCount;
        }
        return 0;
    }
    
    /**
     * Get list offset.
     *
     * @return int List offset (default 0)
     */
    public function getOffset() {
        return $this->offset;
    }
    
    /**
     * Get number of requested records.
     *
     * @return int Requested records number (default 1000)
     */
    public function getLimit() {
        return $this->limit;
    }
    
    /**
     * toJsonString
     */
    public function toJsonString()
    {
    	$results = [
    		'fields' => $this->fields,
    		'filters' => $this->filters,
    		'control' => $this->control,
    		'sortby' => $this->sortby
    	];

    	foreach ($results as $key => $list) {
    		ksort($results[$key]);
    	}
    	
    	return json_encode($results);
    }
    
    /**
     * Convert UTC to local datetime if element is present in the incoming array
     * @param unknown $array Incoming array
     * @param unknown $key Incoming value key
     */
    public static function convertUtcToLocalTime(&$array, $key)
    {
        if (isset($array[$key]) || array_key_exists($key, $array)) {
            $array[$key] = self::getLocalTime($array[$key]);
        }
    }
}
