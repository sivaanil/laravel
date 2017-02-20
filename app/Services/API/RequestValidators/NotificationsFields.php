<?php

namespace Unified\Services\API\RequestValidators;

/**
 * Notificationss fields.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class NotificationsFields {
    public static function __callStatic($function, $args) {
        if ($function === 'addHoursParams') {
            return [ 
                    'dayOfWeek' => 'sh.day_of_week',
                    'active' => 'sh.active',
                    'allDay' => 'sh.all_day',
                    'startTime' => 'sh.start_time',
                    'endTime' => 'sh.end_time',
            ];
        } else if ($function === 'modifyHoursParams') {
            return [ 'id' => 'sh.id' ] + NotificationsFields::addHoursParams ();
        } else if ($function === 'getHoursParams') {
            return NotificationsFields::modifyHoursParams () + [
                    'updated' => 'sh.date_updated',
                    'created' => 'sh.date_created'];
        } else if ($function === 'notificationsMandatoryParams') {
            return [ 
                    'snmpDestId' => 'sn.snmp_dest_id',
                    'nodeId' => 'sn.node_id'
            ];
        } else if ($function === 'notificationsOptionalParams') {
            return NotificationsFields::editNotificationsParams () + [ 
                    'updated' => 'UNIX_TIMESTAMP(sn.date_updated)',
                    'created' => 'UNIX_TIMESTAMP(sn.date_created)' 
            ];
        } else if ($function === 'modifyNotificationsParams') {
            return NotificationsFields::notificationsMandatoryParams () + 
                   NotificationsFields::commonNotificationsParams () + [ 
                    'hours' => [ 
                            NotificationsFields::modifyHoursParams () 
                    ] 
            ];
        } else if ($function === 'addNotificationsParams') {
            return NotificationsFields::commonNotificationsParams () + [ 
                    'hours' => [ 
                            NotificationsFields::addHoursParams () 
                    ] 
            ];
        } else if ($function === 'getNotificationsParams') {
            return [ 'id' => 'sn.id' ] + 
                   NotificationsFields::notificationsMandatoryParams () + 
                   NotificationsFields::commonNotificationsParams () + [ 
                    'hours' => [ 
                            NotificationsFields::getHoursParams () 
                    ] 
            ];
        } else if ($function === 'commonNotificationsParams') {
            return [
                    'useDefaultHours' => 'sn.use_default_hours',
                    'sendCritical' => 'sn.send_critical',
                    'sendMajor' => 'sn.send_major',
                    'sendMinor' => 'sn.send_minor',
                    'sendWarning' => 'sn.send_warning',
                    'treeName' => 'sn.tree_name',
                    'sendInformation' => 'sn.send_information',
                    'sendIgnored' => 'sn.send_ignored',
                    'sendPerimeter' => 'sn.send_perimeter',
                    'sendOutage' => 'sn.send_outage',
                    'sendDegradation' => 'sn.send_degradation'
            ];
        } else {
            return [ ];
        }
    }
}
