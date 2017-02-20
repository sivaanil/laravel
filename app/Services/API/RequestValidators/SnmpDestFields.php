<?php

namespace Unified\Services\API\RequestValidators;

/**
 * SnmpDests fields.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class SnmpDestFields {
    public static function __callStatic($function, $args) {
        if ($function === 'snmpDestMandatoryParams') {
            return [ 
                    'name' => 'name',
                    'homeNodeId' => 'home_node_id',
                    'ipAddress' => 'ip_address',
                    'snmpVersion' => 'snmp_version_id' 
            ];
        } else if ($function === 'snmpDestOptionalParams') {
            return SnmpDestFields::editSnmpDestParams () + [ 
                    'updated' => 'UNIX_TIMESTAMP(date_updated)',
                    'created' => 'UNIX_TIMESTAMP(date_created)' 
            ];
        } else if ($function === 'editSnmpDestParams') {
            return [ 
                    'readCommunity' => 'read_community',
                    'writeCommunity' => 'write_community',
                    'companyId' => 'company_id',
                    'format' => 'format',
                    'includeCustomThresholds' => 'include_custom_thresholds',
                    'username' => 'SNMPuserName',
                    'authType' => 'SNMPauthType',
                    'authPassword' => 'SNMPauthPassword',
                    'authEncryption' => 'SNMPauthEncryption',
                    'privPassword' => 'SNMPprivPassword',
                    'privEncryption' => 'SNMPprivEncryption',
                    'engineID' => 'SNMPengineID' 
            ]
            ;
        } else if ($function === 'snmpDestParams') {
            return [ 
                    'id' => 'id' 
            ] + SnmpDestFields::snmpDestMandatoryParams () + SnmpDestFields::snmpDestOptionalParams ();
        } else {
            return [ ];
        }
    }
}
