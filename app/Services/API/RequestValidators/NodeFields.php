<?php

namespace Unified\Services\API\RequestValidators;

/**
 * Nodes fields.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class NodeFields {
    public static function __callStatic($function, $args) {
        if ($function === 'snmpParams') {
            return [ 
                    'snmpVersion' => 'nd.snmp_version',
                    'snmpNativeId' => 'nd.native_id',
                    'snmpRead' => 'css_decrypt(nd.read_community)',
                    'snmpWrite' => 'css_decrypt(nd.write_community)',
                    'authType' => 'nd.SNMPauthType',
                    'username' => 'nd.SNMPuserName',
                    'authPassword' => 'css_decrypt(nd.SNMPauthPassword)',
                    'authEncryption' => 'nd.SNMPauthEncryption',
                    'privPassword' => 'css_decrypt(nd.SNMPprivPassword)',
                    'privEncryption' => 'nd.SNMPprivEncryption' 
            ];
        } else if ($function === 'setSnmpParams') {
            return [ 
                    'snmpVersion' => 'd.snmp_version',
                    'snmpNativeId' => 'd.native_id',
                    'snmpRead' => 'd.read_community',
                    'snmpWrite' => 'd.write_community',
                    'authType' => 'd.SNMPauthType',
                    'username' => 'd.SNMPuserName',
                    'authPassword' => 'd.SNMPauthPassword',
                    'authEncryption' => 'd.SNMPauthEncryption',
                    'privPassword' => 'd.SNMPprivPassword',
                    'privEncryption' => 'd.SNMPprivEncryption' 
            ];
        } else if ($function === 'webUiParams') {
            return [ 
                    'webUiLink' => 'ndt.defaultWebUi',
                    'username' => 'nd.username',
                    'password' => 'css_decrypt(nd.password)' 
            ];
        } else if ($function === 'setWebUiParams') {
            return [ 
                    'username' => 'd.username',
                    'password' => 'd.password' 
            ];
        } else if ($function === 'portsParams') {
            return [ 
                    'portDefId' => 'ndpd.id',
                    'name' => 'ndpd.name',
                    'variable' => 'ndpd.variable_name',
                    'modemPort' => 'ndpd.default_port', 
                    'devicePort' => 'ndp.port'
            ];
        } else if ($function === 'setPortsParams') {
            return [ 
                    'portDefId' => 'ndp.port_def_id',
                    'devicePort' => 'ndp.port'
            ];
        } else if ($function === 'statsParams') {
            return [ 
                    'lastHeartbeat' => 'UNIX_TIMESTAMP(nd.last_heartbeat)',
                    'lastScan' => 'UNIX_TIMESTAMP(nd.last_scan)',
                    'lastAlarmsScan' => 'UNIX_TIMESTAMP(nd.last_alarms_scan)',
                    'lastPropertiesScan' => 'UNIX_TIMESTAMP(nd.last_properties_scan)',
                    'lastFailedScan' => 'UNIX_TIMESTAMP(nd.last_failed_scan)',
                    'lastFailedAlarmsScan' => 'UNIX_TIMESTAMP(nd.last_failed_alarms_scan)',
                    'lastFailedPropertiesScan' => 'UNIX_TIMESTAMP(nd.last_failed_properties_scan)',
                    'failedScanCount' => 'nnt.fail_count',
                    'failedAlarmsScanCount' => 'nnt.fail_alarms_count',
                    'failedPropertiesScanCount' => 'nnt.fail_properties_count',
                    'stuckCount' => 'nd.stuck_count',
                    'scanning' => 'nd.scanning' 
            ];
        } else if ($function === 'setStatsParams') {
            return [ 
                    'lastHeartbeat' => 'd.last_heartbeat',
                    'lastScan' => 'd.last_scan',
                    'lastAlarmsScan' => 'd.last_alarms_scan',
                    'lastPropertiesScan' => 'd.last_properties_scan',
                    'lastFailedScan' => 'd.last_failed_scan',
                    'lastFailedAlarmsScan' => 'd.last_failed_alarms_scan',
                    'lastFailedPropertiesScan' => 'd.last_failed_properties_scan',
                    'failedScanCount' => 'nt.fail_count',
                    'failedAlarmsScanCount' => 'nt.fail_alarms_count',
                    'failedPropertiesScanCount' => 'nt.fail_properties_count',
                    'stuckCount' => 'd.stuck_count',
                    'scanning' => 'd.scanning' 
            ];
        } else if ($function === 'configParams') {
            return [ 
                    'inheritContact' => 'COALESCE(nd.inherit_contact, ng.inherit_contact)',
                    'siteLicense' => 'COALESCE(nd.site_license, ng.site_license)',
                    'scanPropertiesEnabled' => 'nd.scan_properties_enabled',
                    'propertiesScanInterval' => 'nnt.scan_properties_interval',
                    'stopPropertyUntil' => 'UNIX_TIMESTAMP(nd.stop_property_until)',
                    'stopPropertyNotes' => 'nd.stop_property_notes',
                    'alarmsScanEnabled' => 'nd.scan_alarms_enabled',
                    'alarmsScanInterval' => 'nnt.scan_alarms_interval',
                    'offlineAlarmExempt' => 'nd.offline_alarm_exempt',
                    'alarmExempt' => 'nd.alarm_exempt',
                    'stopAlarmUntil' => 'UNIX_TIMESTAMP(nd.stop_alarm_until)',
                    'stopAlarmNotes' => 'nd.stop_alarm_notes',
                    'scanEnabled' => 'nd.scan_enabled',
                    'stopScanUntil' => 'UNIX_TIMESTAMP(nd.stop_scan_until)',
                    'stopScanNotes' => 'nd.stop_scan_notes',
                    'scanInterval' => 'nnt.scan_interval',
                    'heartbeatEnabled' => 'nd.heartbeat_enabled',
                    'autoTicketEnabled' => 'COALESCE(nd.auto_ticket_enabled, ng.auto_ticket_enabled)',
                    'trapEnabled' => 'nd.trap_enabled',
                    'queueDevice' => 'nd.queue_device', 
                    'secure' => 'nd.secure' 
            ];
        } else if ($function === 'setConfigParams') {
            return [ 
                    'inheritContact' => 'dg.inherit_contact',
                    'siteLicense' => 'dg.site_license',
                    'scanPropertiesEnabled' => 'd.scan_properties_enabled',
                    'propertiesScanInterval' => 'nt.scan_properties_interval',
                    'stopPropertyUntil' => 'd.stop_property_until',
                    'stopPropertyNotes' => 'd.stop_property_notes',
                    'alarmsScanEnabled' => 'd.scan_alarms_enabled',
                    'alarmsScanInterval' => 'nt.scan_alarms_interval',
                    'offlineAlarmExempt' => 'd.offline_alarm_exempt',
                    'alarmExempt' => 'd.alarm_exempt',
                    'stopAlarmUntil' => 'd.stop_alarm_until',
                    'stopAlarmNotes' => 'd.stop_alarm_notes',
                    'scanEnabled' => 'd.scan_enabled',
                    'stopScanUntil' => 'd.stop_scan_until',
                    'stopScanNotes' => 'd.stop_scan_notes',
                    'scanInterval' => 'nt.scan_interval',
                    'heartbeatEnabled' => 'd.heartbeat_enabled',
                    'autoTicketEnabled' => 'dg.auto_ticket_enabled',
                    'trapEnabled' => 'd.trap_enabled',
                    'queueDevice' => 'd.queue_device', 
                    'secure' => 'd.secure' 
            ];
        } else if ($function === 'addressParams') {
            return [ 
                    'street' => 'COALESCE(nd.street, ng.street)',
                    'city' => 'COALESCE(nd.city, ng.city)',
                    'state' => 'COALESCE(nd.state, ng.state)',
                    'zip' => 'COALESCE(nd.zip, ng.zip)',
                    'country' => 'COALESCE(nd.country, ng.country)' 
            ];
        } else if ($function === 'setAddressParams') {
            return [ 
                    'street' => 'dg.street',
                    'city' => 'dg.city',
                    'state' => 'dg.state',
                    'zip' => 'dg.zip',
                    'country' => 'dg.country' 
            ];
        } else if ($function === 'locationParams') {
            return [ 
                    'coordMode' => 'nd.coord_mode',
                    'latitudeOrigin' => 'nd.latitude_origin',
                    'longitudeOrigin' => 'nd.longitude_origin',
                    'longitude' => 'COALESCE(nd.longitude, ng.longitude)',
                    'latitude' => 'COALESCE(nd.latitude, ng.latitude)',
                    'perimeter' => 'nd.perim_value' 
            ];
        } else if ($function === 'setLocationParams') {
            return [ 
                    'coordMode' => 'd.coord_mode',
                    'latitudeOrigin' => 'd.latitude_origin',
                    'longitudeOrigin' => 'd.longitude_origin',
                    'longitude' => 'dg.longitude',
                    'latitude' => 'dg.latitude',
                    'perimeter' => 'd.perim_value' 
            ];
        } else if ($function === 'infoParams') {
            return [ 
                    'ipAddress' => 'nd.ip_address',
                    'ipAddress2' => 'nd.ip_address_2',
                    'macAddress' => 'nd.mac_address',
                    'firmware' => 'nd.firmware',
                    'description' => 'COALESCE(nd.description,ng.description)',
                    'contactNotes' => 'COALESCE(nd.contact_notes,ng.contact_notes)',
                    'comments' => 'COALESCE(nd.comments,ng.comments)',
                    'notes' => 'COALESCE(nd.notes,ng.notes)',
                    'address' => NodeFields::addressParams (),
                    'coordinates' => NodeFields::locationParams (),
                    'contactName' => 'COALESCE(nd.contact_name,ng.contact_name)',
                    'contactPhone' => 'COALESCE(nd.contact_phone,ng.contact_phone)',
                    'contactEmail' => 'COALESCE(nd.email,ng.email)',
                    'mobile' => 'COALESCE(nd.mobile,ng.mobile)',
                    'fax' => 'COALESCE(nd.fax,ng.fax)' 
            ];
        } else if ($function === 'setInfoParams') {
            return [ 
                    'ipAddress' => 'd.ip_address',
                    'ipAddress2' => 'd.ip_address_2',
                    'macAddress' => 'd.mac_address',
                    'firmware' => 'd.firmware',
                    'description' => 'dg.description',
                    'contactNotes' => 'dg.contact_notes',
                    'comments' => 'dg.comments',
                    'notes' => 'dg.notes',
                    'address' => NodeFields::setAddressParams (),
                    'coordinates' => NodeFields::setLocationParams (),
                    'contactName' => 'dg.contact_name',
                    'contactPhone' => 'dg.contact_phone',
                    'contactEmail' => 'dg.email',
                    'mobile' => 'dg.mobile',
                    'fax' => 'dg.fax' 
            ];
        } else if ($function === 'nodeParams') {
            return [ 
            'sequence' => 'sequence',
            'id' => 'nntm.node_id',
            'name' => 'COALESCE(nd.name, ng.name)',
            'nodeMap' => 'nntm.node_map',
            'parent' => 'nnt.parent_node_id',
            'uuid' => 'nnt.uuid',
            'visible' => 'nntm.visible',
            'currentStatusId' => 'nd.current_status_id',
            'deleted' => 'nntm.deleted',
            'class' => 'ndc.description',
            'classId' => 'ndt.class_id',
            'vendor' => 'ndt.vendor',
            'model' => 'ndt.model',
            'typeId' => 'nd.type_id',
            'dateAdded' => 'UNIX_TIMESTAMP(nd.date_added)',
            'dateCreated' => 'UNIX_TIMESTAMP(COALESCE(nd.date_created, ng.date_created))',
            'dateUpdated' => 'UNIX_TIMESTAMP(COALESCE(nd.date_updated, ng.date_updated))',
            'userId' => 'nd.user_id',
            'ports' => [NodeFields::portsParams ()],
            'stats' => NodeFields::statsParams (),
            'config' => NodeFields::configParams (),
            'info' => NodeFields::infoParams (),
            'isSiteportalDevice' => 'nd.is_siteportal_device',
            'webEnabled' => 'nd.web_enabled',
            'webUi' => NodeFields::webUiParams (),
            'snmp' => NodeFields::snmpParams ()
            ];
        } else {
            return [ ];
        }
    }
}
