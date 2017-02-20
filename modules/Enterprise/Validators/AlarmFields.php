<?php

namespace Modules\Enterprise\Validators;

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
            'serialNumber' => 'GetMainDevicePropertyByNodeMap(nntm.node_map,"Serial Number")',
            'macAddress' => 'coalesce(GetNearestPropertyByNodeMap(nntm.node_map,"MAC Address"),"")',
            'nativeId' => 'nd.native_id',
            'description' => 'nda.description',
            'raised' => 'UNIX_TIMESTAMP(nda.raised)',
            'cleared' => 'coalesce(UNIX_TIMESTAMP(nda.cleared),0)'
            ];
        } else if ($function === 'alarmOptionalParams') {
            return [ 
            'sequence' => 'nda.sequence',
            'uuid' => 'nda.uuid',
            'isOffline' => 'nda.is_offline',
            'notes' => 'nda.notes'
            ];
        } else if ($function === 'alarmParams') {
            return AlarmFields::alarmMandatoryParams () + AlarmFields::alarmOptionalParams ();
        } else {
            return [ ];
        }
    }
}
