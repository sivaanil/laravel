<?php

namespace Unified\Services\API\RequestValidators;

/**
 * Alarms fields.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class AlarmFields {
    public static function __callStatic($function, $args) {
        if ($function === 'alarmMandatoryParams') {
            return [ 
            'id' => 'nda.id',
            'nodeId' => 'nnt.id',
            'severityId' => 'nda.severity_id',
            'description' => 'nda.description',
            'raised' => 'UNIX_TIMESTAMP(nda.raised)'
            ];
        } else if ($function === 'alarmOptionalParams') {
            return [ 
            'sequence' => 'nda.sequence',
            'cleared' => 'UNIX_TIMESTAMP(nda.cleared)',
            'uuid' => 'nda.uuid',
            'isOffline' => 'nda.is_offline',
            'ignored' => 'nda.ignored',
            'isTrap' => 'nda.is_trap',
            'isHeartbeat' => 'nda.is_heartbeat',
            'isThreshold' => 'nda.is_threshold',
            'ignoreNotes' => 'nda.ignore_notes',
            'ignoreUntil' => 'UNIX_TIMESTAMP(nda.ignore_until)',
            'hasNotes' => 'nda.has_notes',
            'notes' => 'nda.notes',
            'isChronic' => 'nda.is_chronic',
            'isPerimeter' => 'nda.is_perimeter',
            'permitNotifications' => 'nda.permit_notifications',
            'canAcknowledge' => 'nda.can_acknowledge',
            'acknowledged' => 'nda.acknowledged',
            'snmpObjectId' => 'nda.snmp_object_id',
            'dateUpdated' => 'UNIX_TIMESTAMP(nda.date_updated)',
            'durationExempt' => 'nda.duration_exempt',
            'propertyAlarm' => 'nda.prop_alarm',
            'logDateTime' => 'nda.log_date_time',
            'clearedBit' => 'nda.cleared_bit',
            'clearedOrder' => 'nda.cleared_order'
            ];
        } else if ($function === 'editAlarmParams') {
            return [ 
            'severityId' => 'severity_id',
            'description' => 'description',
            'raised' => 'raised',
            'cleared' => 'cleared',
            'uuid' => 'uuid',
            'isOffline' => 'is_offline',
            'ignored' => 'ignored',
            'isTrap' => 'is_trap',
            'isHeartbeat' => 'is_heartbeat',
            'isThreshold' => 'is_threshold',
            'ignoreNotes' => 'ignore_notes',
            'ignoreUntil' => 'ignore_until',
            'hasNotes' => 'has_notes',
            'notes' => 'notes',
            'isChronic' => 'is_chronic',
            'isPerimeter' => 'is_perimeter',
            'permitNotifications' => 'permit_notifications',
            'canAcknowledge' => 'can_acknowledge',
            'acknowledged' => 'acknowledged',
            'snmpObjectId' => 'snmp_object_id',
            'durationExempt' => 'duration_exempt',
            'propertyAlarm' => 'prop_alarm',
            'logDateTime' => 'log_date_time',
            'clearedBit' => 'cleared_bit',
            'clearedOrder' => 'cleared_order'
            ];
        } else if ($function === 'alarmParams') {
            return AlarmFields::alarmMandatoryParams () + AlarmFields::alarmOptionalParams ();
        } else {
            return [ ];
        }
    }
}
