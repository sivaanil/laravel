<?php
namespace Unified\Models;

use DB;
use Unified\Models\GridModel;
use Unified\Http\Helpers\GridParamParser;
use Unified\Http\Helpers\QueryParameters;
use Eloquent;

class SNMPDestination extends Eloquent implements GridModel {
    use QueryTrait;
    
    protected $table = 'css_snmp_dest';
    public $timestamps = false;

    /**
     * Gets data for SNMPDestination grid
     *
     * @param GridParamParser $parser
     */
    public function getForGrid(GridParamParser $parser, $param=null) {
        $parser->parse();

        $filters    = $parser->getFilters();
        $sort       = $parser->getSort();
        $pagination = $parser->getPagination();

        $query = new SNMPDestination();

        // Build the query based on the parameters above

        // 1. Build the filters (where clause)
        foreach ($filters as $filter) {
            $whereParams = $parser->getWhereClause($filter);

            $opMethod = "where";
            if ($filter->operator) {
                switch (strtoupper($filter->operator)) {
                    case "OR":
                        $opMethod = "orWhere";
                        break;
                    case "AND":
                        $opMethod = "andWhere";
                        break;
                }
            }

            if ($whereParams->value) {
                $query = $query->$opMethod($whereParams->field, $whereParams->condition, $whereParams->value);
            } else {
                $query = $query->$opMethod($whereParams->field, $whereParams->condition);
            }
        }

        // 2. Perform sorting
        if ($sort) {
            $query = $query->orderBy($sort->datafield, $sort->order);

        }

        // 3. Perform pagination ops
        if ($pagination) {
            $skip = ($pagination->page - 1) * $pagination->pagesize;
            $query = $query->skip($skip);
            $query = $query->take($pagination->pagesize);
        }

        // Perform the query
        $result = $query->get();
        return $result;
    }


    /**
     * Get the company associated with this SNMPDestination, if any
     */
    public function company() {
        // TODO - implement relationship getter
    }

    /**
     * Get the node related to this SNMPDestination
     */
    public function node() {
        // TODO - since we're doing this in the global scope for V1, not setting this
    }

    /**
     * Returns list of SNMP destinations
     * @param QueryParameters $config            
     */
    public static function getSnmpDest(QueryParameters $config) {
        return self::getRecords($config, get_called_class(), 'snmpDest');
    }
    
    /**
     * Modifyes SNMP destination
     * @param new $config            
     */
    public static function modifySnmpDest($content) {
        // Verify snmp destination ID
        $destId = QueryTrait::popByKey ( $content, 'id' );
        if (is_null ( $destId )) {
            return QueryTrait::error ( 'SNMP destination ID is empty.' );
        }
        $dest = SNMPDestination::find($destId);
        if ($dest == false) {
            return QueryTrait::error ( 'Unable to modify SNMP destination with ID '. $destId );
        }
        DB::beginTransaction ();
        
        // Set SNMP destination attributes attributes
        QueryTrait::setEntityAttributes($dest, $content);
        $dest->save ();
        
        $retVal = self::checkFinalContent ( $content );
        if ($retVal ['status']) {
            DB::commit ();
        } else {
            DB::rollback ();
        }
        return $retVal;
    }
    
    /**
     * Adds SNMP destination
     * @param new $config            
     */
    public static function addSnmpDest($content) {
        DB::beginTransaction ();
        $dest = new SNMPDestination();
        // Set SNMP destination attributes attributes
        QueryTrait::setEntityAttributes($dest, $content);
        $dest->save ();
        
        $retVal = self::checkFinalContent ( $content );
        if ($retVal ['status']) {
            $retVal ['id'] = $dest->id;
            DB::commit ();
        } else {
            DB::rollback ();
        }
        return $retVal;
    }
    
    /**
     * Deletes SNMP destination
     * @param QueryParameters $config            
     */
    public static function deleteSnmpDest($content) {
        // Verify snmp destination ID
        $destId = QueryTrait::popByKey ( $content, 'id' );
        if (is_null ( $destId )) {
            return QueryTrait::error ( 'SNMP destination ID is empty.' );
        }
        DB::beginTransaction ();
        
        // Delete the SNMP destination
        // TODO add additional verifications, such as check if it used in notifications and hours
        $status = SNMPDestination::destroy($destId);
        
        if ($status) {
            DB::commit ();
            return [ 'status' => true];
        } else {
            DB::rollback ();
            return QueryTrait::error ( 'Unable to delete SNMP destination with id ' . $destId );
        }
    }
}

