<?php

namespace Unified\Models;

use DB;
use Unified\Http\Helpers\QueryParameters;
use Eloquent;

class SNMPNotification extends Eloquent {
    use QueryTrait;
    protected $table = 'css_snmp_notification';
    public $timestamps = false;
    
    /**
     * Returns list of SNMP notifications
     *
     * @param QueryParameters $config            
     */
    public static function getSnmpNotification(QueryParameters $config) {
        $fields = $config->getFields ();
        $isCount = $config->isCount ();
        // Remove hour related fields. We will add hours related information later
        $hoursFields = QueryParameters::popElementsStartingWith ( $fields, "sh." );
        
        if (! empty ( $hoursFields )) {
            // Add ID to be latter used to search for hours information
            // Field __id__ will be removed during hours search
            $fields [] = "sn.id as __id__";
        }
        
        $query = DB::table ( 'css_snmp_notification as sn' );
        $query->leftjoin ( "css_snmp_hours as sh", "sh.snmp_notification_id", "=", "sn.id" );
        
        $query = $query->groupBy ( 'sn.id' );
        $query = self::setFields ( $query, $fields, $isCount );
        
        // Apply filters
        $query = self::setFilters ( $query, $config->getFilters () );
        
        // Apply sortby
        $query = self::setSortby ( $query, $config->getSortby () );
        
        // Set pagination parameters
        $query = self::setPagination ( $query, $config->getOffset (), $config->getLimit () );
        
        $result = $query->get ();
        
        if ($isCount) {
            $countElements = QueryParameters::getLatestCount ();
        }
        
        // Add hours if requested
        if (! empty ( $hoursFields )) {
            foreach ( $result as &$sne ) {
                $hours = DB::table ( 'css_snmp_hours as sh' )->select ( $hoursFields )->where ( 
                        "sh.snmp_notification_id", 
                        $sne->__id__ )->get ();
                // Add hours info to notification object
                $sne->hours = $hours;
                // Remove index used to get hours information
                unset ( $sne->__id__ );
            }
        }
        
        // Prepare return value
        $retVal = [ ];
        if ($isCount) {
            $retVal ['count'] = $countElements;
        }
        
        $retVal ["notifications"] = $result;
        
        return $retVal;
    }
    
    /**
     * Modifyes SNMP notification
     *
     * @param new $config            
     */
    public static function modifySnmpNotifications($content) {
        // Verify snmp notification ID
        $notificationId = QueryTrait::popByKey ( $content, 'sn.id' );
        if (is_null ( $notificationId )) {
            return QueryTrait::error ( 'SNMP notification ID is empty.' );
        }
        $notification = SNMPNotification::find ( $notificationId );
        if ($notification == false) {
            return QueryTrait::error ( 'Unable to modify SNMP notification with ID ' . $notificationId );
        }
        DB::beginTransaction ();
        
        // Set SNMP notification attributes attributes
        QueryTrait::setEntityAttributesWithPrefix ( $notification, $content, 'sn.' );
        $notification->save ();
        
        // Modify notification hours if present
        if (array_key_exists ( 'hours', $content )) {
            // Get list of currently present notification hours
            $curHoursList = SNMPNotificationHours::where ( 'snmp_notification_id', $notificationId )->get ();
            // Add/modify incoming hours
            foreach ( $content ['hours'] as $key => $he ) {
                if (array_key_exists ( 'sh.id', $he )) {
                    $hoursRec = SNMPNotificationHours::find ( $he ['sh.id'] );
                    if ($hoursRec == false) {
                        DB::rollback ();
                        return QueryTrait::error ( 'Unable to modify SNMP notification hours with ID ' . $he ['sh.id'] );
                    }
                    // Find and mark record in currently present List
                    foreach ( $curHoursList as &$chl ) {
                        if ($chl->id == $he ['sh.id']) {
                            if (isset ( $chl->recordHasNeemModified )) {
                                DB::rollback ();
                                return QueryTrait::error ( 
                                        'SNMP notification hours record with ID ' . $he ['sh.id'] .
                                                 ' has already been modified' );
                            } else {
                                $chl->recordHasNeemModified = 1;
                            }
                        }
                    }
                } else {
                    $hoursRec = new SNMPNotificationHours ();
                }
                $hoursRec->snmp_notification_id = $notification->id;
                // TODO It seems like snmp_dest_id is not necessary should be present in hours table
                $hoursRec->snmp_dest_id = $notification->snmp_dest_id;
                QueryTrait::setEntityAttributesWithPrefix ( $hoursRec, $he, 'sh.' );
                $hoursRec->save ();
                unset ( $content ['hours'] [$key] );
            }
            // Remove curently present not modified records
            foreach ( $curHoursList as $chl ) {
                if (! isset ( $chl->recordHasNeemModified )) {
                    SNMPNotificationHours::destroy ( $chl->id );
                }
            }
            // Mark hours as p[rocessed.
            if (empty ( $content ['hours'] )) {
                unset ( $content ['hours'] );
            }
        }
        
        $retVal = self::checkFinalContent ( $content );
        if ($retVal ['status']) {
            DB::commit ();
        } else {
            DB::rollback ();
        }
        return $retVal;
    }
    
    /**
     * Adds SNMP notification
     *
     * @param new $config            
     */
    public static function addSnmpNotification($content) {
        DB::beginTransaction ();
        $notification = new SNMPNotification ();
        // Set SNMP notification attributes attributes
        QueryTrait::setEntityAttributesWithPrefix ( $notification, $content, 'sn.' );
        
        // TODO Check if snmp_dest_id is present in the css_snmp_Dest table
        $snmpDest = SNMPDestination::find ( $notification->snmp_dest_id );
        if ($snmpDest == false) {
            DB::rollback ();
            return QueryTrait::error ( 'Unable to modify SNMP notification with ID ' . $notificationId );
        }
        $notification->save ();
        
        // Add notification hours if present
        if (array_key_exists ( 'hours', $content )) {
            foreach ( $content ['hours'] as $key => $he ) {
                $hoursRec = new SNMPNotificationHours ();
                $hoursRec->snmp_notification_id = $notification->id;
                // TODO It seems like snmp_dest_id is not necessary should be present in hours table
                $hoursRec->snmp_dest_id = $notification->snmp_dest_id;
                QueryTrait::setEntityAttributesWithPrefix ( $hoursRec, $he, 'sh.' );
                $hoursRec->save ();
                unset ( $content ['hours'] [$key] );
            }
            if (empty ( $content ['hours'] )) {
                unset ( $content ['hours'] );
            }
        }
        
        $retVal = self::checkFinalContent ( $content );
        if ($retVal ['status']) {
            $retVal ['id'] = $notification->id;
            DB::commit ();
        } else {
            DB::rollback ();
        }
        return $retVal;
    }
    
    /**
     * Deletes SNMP notification
     *
     * @param QueryParameters $config            
     */
    public static function deleteSnmpNotification($content) {
        // Verify snmp notification ID
        $notificationId = QueryTrait::popByKey ( $content, 'sn.id' );
        if (is_null ( $notificationId )) {
            return QueryTrait::error ( 'SNMP notification ID is empty.' );
        }
        DB::beginTransaction ();
        
        // Delete the SNMP notification
        $status = SNMPNotification::destroy ( $notificationId );
        
        // Delete SNMP hours for particular notification ID
        SNMPNotificationHours::where ( 'snmp_notification_id', $notificationId )->delete ();
        
        if ($status) {
            DB::commit ();
            return [ 
                    'status' => true 
            ];
        } else {
            DB::rollback ();
            return QueryTrait::error ( 'Unable to delete SNMP notification with id ' . $notificationId );
        }
    }
}

