<?php

namespace Unified\Models;

use DB;
use Unified\Http\Helpers\QueryParameters;
trait QueryTrait {

    /**
     * Prepare query fields.
     * @param unknown $fields fields to be set
     * @param unknown $isCount include count to the list of fields
     * @return modified field arrayquery
     */
    public static function prepareFields($fields, $isCount) {
    
        // Remove 'calculatable' fields to add them as RAW sql
        // Field counts as 'calculatable' if '(' is present in the mapping table
        $calculatable = QueryParameters::popElementsContainingString ( $fields, '(' );
        if (! empty ( $calculatable )) {
            foreach ($calculatable as $dg => $calculatableRec) {
                $fields [] = DB::raw ( $calculatableRec);
            }
        }
         
        // Check if count is requested
        if ($isCount) {
            // Add SQL keyword SQL_CALC_FOUND_ROWS before first requested field
            $fieldsArrayKeys = array_keys ( $fields );
            $fields [$fieldsArrayKeys [0]] = DB::raw ( 'SQL_CALC_FOUND_ROWS ' . $fields [$fieldsArrayKeys [0]] );
        }
    
        // Return modified fields
        return $fields;
    }
    
    /**
     * Set query fields.
     * @param unknown $query query to be modified
     * @param unknown $fields fields to be set
     * @param unknown $isCount include count to the list of fields
     * @return modified query
     */
    public static function setFields(&$query, $fields, $isCount) {
        // Set fields
        return $query->select ( self::prepareFields($fields, $isCount) );
    }
    
    /**
     * Set query filters.
     * @param unknown $query query to be modified
     * @param unknown $filters filters to be set
     * @return modified query
     */
    public static function setFilters(&$query, $filters) {
        // Apply filters
        if (! empty ( $filters )) {
            foreach ( $filters as $filter ) {
                if (strpos($filter[0],'(') !== false) {
                    //add filter for calculatable fields as raw where
                    if ($filter[1]=="isnull") {
                        $query->whereRaw('? is null', [$filter [0]]);
                    } else if ($filter[1]=="isnotnull") {
                        $query->whereRaw('? is not null', [$filter [0]] );
                    } else {
                        $query->whereRaw ( $filter [0]. $filter [1] .'  ?', [$filter [2]]);
                    }
                } else {
                    if ($filter[1]=="isnull") {
                        $query->whereNull($filter [0]);
                    } else if ($filter[1]=="isnotnull") {
                        $query->whereNotNull($filter [0]);
                    } else {
                        $query->where ( $filter [0], $filter [1], $filter [2] );
                    }
                }
            }
        }
        return $query;
    }
    
    /**
     * Set sortby parameters.
     * 
     * @param unknown $query
     *            query to be modified
     * @param unknown $sortby
     *            sortby to be set
     * @return modified query
     */
    public static function setSortby(&$query, $sortby) {
        // Apply filters
        if (! empty ( $sortby )) {
            foreach ( $sortby as $sortp ) {
                if (strpos ( $sortp [0], '(' ) !== false) {
                    if ($sortp [1] == 'DESC') {
                        $query->orderByRaw ( $sortp [0] . ' DESC' );
                    } else {
                        $query->orderByRaw ( $sortp [0] . 'ASC' );
                    }
                } else {
                    if ($sortp [1] == 'DESC') {
                        $query->orderBy ( $sortp [0], 'DESC' );
                    } else {
                        $query->orderBy ( $sortp [0], 'ASC' );
                    }
                }
            }
        }
        return $query;
    }
    
    /**
     * Set pagination parameters 
     * @param unknown $query Query to be modified
     * @param unknown $offset Record offset
     * @param unknown $limit Limit of records to be returned
     * @return modified query
     */
    public static function setPagination(&$query, $offset, $limit) {
        // Set pagination parameters
        return $query->limit ( $limit )->offset ( $offset );
    }
    
    /**
     * Get query results
     * @param unknown $query Query to be executed
     * @param unknown $isCount include count to the list of fields
     * @param unknown $contentName Result content name
     * @return Query results
     */
    public static function getResults(&$query, $isCount, $contentName) {
        $result = $query->get ();
        
        // Prepare return value
        $retVal = [ ];
        if ($isCount) {
            $retVal ['count'] = QueryParameters::getLatestCount ();
        }

        $retVal [$contentName] = $result;
        
        return $retVal;
    }
    /**
     * Returns list of records matching provided query parameters
     *
     * @param QueryParameters $config            
     */
    public static function getRecords(QueryParameters $config, $modelClass, $contentName) {
        // Start query construction
        $query = $modelClass::select ( self::prepareFields($config->getFields (), $config->isCount () ));
        
        // Apply filters
        $query = self::setFilters ( $query, $config->getFilters () );
        
        // Apply sortby
        $query = self::setSortby ( $query, $config->getSortby () );
        
        // Set pagination parameters
        $query = self::setPagination ( $query, $config->getOffset (), $config->getLimit () );
        
        // Execute query
        return self::getResults ( $query, $config->isCount (), $contentName );
    }

    /**
     * Pop element by key.
     * @param unknown $array Target array
     * @param unknown $key Target key
     */
    public static function popByKey(&$array, $key) {
        $value = null;
        if (isset($array[$key]) || array_key_exists($key, $array)) {
            $value = $array[$key];
            unset($array[$key]);
        }
        return $value;
    }
    
    /**
     * Create and return array representing error.
     * 
     * @param unknown $errorMsg            
     */
    public static function error($errorMsg) {
        return [ 
                'status' => false,
                'error' => $errorMsg 
        ];
    }

    /**
     * Set content attributes started with target prefix as attributes of target entity
     * @param unknown $entity Target entity
     * @param unknown $content List of attributes
     * @param unknown $prefix Attribute prefix
     */
    public static function setEntityAttributesWithPrefix(&$entity, &$content, $prefix) {
        foreach ( $content as $key => $value ) {
            if (strpos ( $key, $prefix ) === 0) {
                $entity->setAttribute(substr($key, strlen($prefix)), $value);
                unset ( $content [$key] );
            }
        }
    }
    
    /**
     * Set entity attributes
     * @param unknown $entity Target entity
     * @param unknown $content List of attributes
     */
    public static function setEntityAttributes(&$entity, &$content) {
        foreach ( $content as $key => $value ) {
            $entity->setAttribute($key, $value);
            unset ( $content [$key] );
        }
    }
    
    /**
     * Check content after processing. All content records should be processed.
     * Create an error if any records still left in comtent.
     * @param unknown $content Content to be verified.
     */
    public static function checkFinalContent($content) {
        //Verify that all content values is set and content is empty
        if (count($content) == 0) {
            return ['status' => true];
        } else {
            return self::error('Unable to set '.implode(",", array_keys($content)));
        }
    }
    
}
