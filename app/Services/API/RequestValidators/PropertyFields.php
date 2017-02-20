<?php

namespace Unified\Services\API\RequestValidators;

/**
 * Properties fields.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class PropertyFields {
    public static function __callStatic($function, $args) {
        if ($function === 'propertyMandatoryParams') {
            return [ 
            'id' => 'ndp.id',
            'nodeId' => 'nnt.id',
            'propDefId' => 'ndp.prop_def_id',
            'value' => 'ndp.value'
            ];
        } else if ($function === 'propertyOptionalParams') {
            return [ 
            'uuid' => 'ndp.uuid',
            'nodeTypeId' => 'nd.type_id',
            'name' => 'ndpd.name',
            'variableName' => 'ndpd.variable_name',
            'isMinValue' => 'ndp.min',
            'minValue' => 'ndp.min_val',
            'isMaxValue' => 'ndp.max',
            'maxValue' => 'ndp.max_val',
            'severityId' => 'ndp.severity_id',
            'severityIdTwo' => 'ndp.severity_id_two',
            'dateUpdated' => 'UNIX_TIMESTAMP(ndp.date_updated)',
            'dateCreated' => 'UNIX_TIMESTAMP(ndp.date_created)',
            'alarmId' => 'ndp.alarm_id',
            'alarmChange' => 'ndp.alarm_change',
            'alarmSiteportalChange' => 'ndp.alarm_siteportal_change',
            'useDefaults' => 'ndp.use_defaults',
            'sequence' => 'ndp.sequence',
            'lastUpdatedUserId' => 'ndp.user_id_last_updated'
            ];
        } else if ($function === 'editPropertyParams') {
            return [ 
            'value' => 'value',
            'uuid' => 'uuid',
            'isMinValue' => 'min',
            'minValue' => 'min_val',
            'isMaxValue' => 'max',
            'maxValue' => 'max_val',
            'severityId' => 'severity_id',
            'severityIdTwo' => 'severity_id_two',
            'alarmId' => 'alarm_id',
            'alarmChange' => 'alarm_change',
            'alarmSiteportalChange' => 'alarm_siteportal_change',
            'useDefaults' => 'use_defaults',
            'lastUpdatedUserId' => 'user_id_last_updated'
            ];
        } else if ($function === 'propertyParams') {
            return PropertyFields::propertyMandatoryParams () + PropertyFields::propertyOptionalParams ();
        } else if ($function === 'propertyDefinitionsParams') {
            return [ 
            'id' => 'id',
            'typeId' => 'prop_type_id',
            'groupId' => 'prop_group_id',
            'nodeTypeId' => 'device_type_id',
            'name' => 'name',
            'useSnmp' => 'use_snmp',
            'snmpOID' => 'snmp_oid',
            'dataType' => 'data_type',
            'variableName' => 'variable_name',
            'isMinValue' => 'min',
            'minValue' => 'min_val',
            'isMaxValue' => 'max',
            'maxValue' => 'max_val',
            'severityId' => 'severity_id',
            'severityIdTwo' => 'severity_id_two',
            'dateUpdated' => 'UNIX_TIMESTAMP(date_updated)',
            'tooltip' => 'tooltip',
            'valuetip' => 'valuetip',
            'editable' => 'editable',
            'visible' => 'visible',
            'internal' => 'internal',
            'secure' => 'secure',
            'graphType' => 'graph_type',
            'enableThreshold' => 'thresh_enable',
            'alarmExempt' => 'alarm_exempt'
            ];
        } else {
            return [ ];
        }
    }
}
