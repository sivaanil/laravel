<?php

namespace Modules\Enterprise\Validators;

/**
 * Nodes fields.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class NodeFields {
    public static function __callStatic($function, $args) {
        if ($function === 'statsParams') {
            return [ 
                    'lastAlarmsScan' => 'UNIX_TIMESTAMP(nd.last_alarms_scan)',
                    'lastPropertiesScan' => 'UNIX_TIMESTAMP(nd.last_properties_scan)',
                    'lastFailedAlarmsScan' => 'UNIX_TIMESTAMP(nd.last_failed_alarms_scan)',
                    'lastFailedPropertiesScan' => 'UNIX_TIMESTAMP(nd.last_failed_properties_scan)' 
            ];
        } else if ($function === 'diskParams') {
            return [ 
                    'dp1Available' => 'sn.Disk Partition 1: Available',
                    'dp1Name' => 'sn.Disk Partition 1: Name',
                    'dp1PercUsed' => 'sn.Disk Partition 1: Percent Used',
                    'dp1TotalSize' => 'sn.Disk Partition 1: Total Size',
                    'dp1Used' => 'sn.Disk Partition 1: Used',
                    'dp2Available' => 'sn.Disk Partition 2: Available',
                    'dp2Name' => 'sn.Disk Partition 2: Name',
                    'dp2PercUsed' => 'sn.Disk Partition 2: Percent Used',
                    'dp2TotalSize' => 'sn.Disk Partition 2: Total Size',
                    'dp2Used' => 'sn.Disk Partition 2: Used',
                    'dp3Available' => 'sn.Disk Partition 3: Available',
                    'dp3Name' => 'sn.Disk Partition 3: Name',
                    'dp3PercUsed' => 'sn.Disk Partition 3: Percent Used',
                    'dp3TotalSize' => 'sn.Disk Partition 3: Total Size',
                    'dp3Used' => 'sn.Disk Partition 3: Used',
                    'dp4Available' => 'sn.Disk Partition 4: Available',
                    'dp4Name' => 'sn.Disk Partition 4: Name',
                    'dp4PercUsed' => 'sn.Disk Partition 4: Percent Used',
                    'dp4TotalSize' => 'sn.Disk Partition 4: Total Size',
                    'dp4Used' => 'sn.Disk Partition 4: Used',
                    'dp5Available' => 'sn.Disk Partition 5: Available',
                    'dp5Name' => 'sn.Disk Partition 5: Name',
                    'dp5PercUsed' => 'sn.Disk Partition 5: Percent Used',
                    'dp5TotalSize' => 'sn.Disk Partition 5: Total Size',
                    'dp5Used' => 'sn.Disk Partition 5: Used' 
            ];
        } else if ($function === 'fansParams') {
            return [ 
                    'fan1Name' => 'sn.Fan 1: Display Name',
                    'fan1Speed' => 'sn.Fan 1: Speed',
                    'fan1Status' => 'sn.Fan 1: Status',
                    'fan2Name' => 'sn.Fan 2: Display Name',
                    'fan2Speed' => 'sn.Fan 2: Speed',
                    'fan2Status' => 'sn.Fan 2: Status',
                    'fan3Name' => 'sn.Fan 3: Display Name',
                    'fan3Speed' => 'sn.Fan 3: Speed',
                    'fan3Status' => 'sn.Fan 3: Status',
                    'fan4Name' => 'sn.Fan 4: Display Name',
                    'fan4Speed' => 'sn.Fan 4: Speed',
                    'fan4Status' => 'sn.Fan 4: Status',
                    'fan5Name' => 'sn.Fan 5: Display Name',
                    'fan5Speed' => 'sn.Fan 5: Speed',
                    'fan5Status' => 'sn.Fan 5: Status' 
            ];
        } else if ($function === 'mcParams') {
            return [ 
                    'cpuIdle' => 'sn.Mezzanine Card CPU: Idle',
                    'cpuKernel' => 'sn.Mezzanine Card CPU: Kernel',
                    'cpuLoadAvg1' => 'sn.Mezzanine Card CPU: Load Avg 1 (%)',
                    'cpuLoadAvg15' => 'sn.Mezzanine Card CPU: Load Avg 15 (%)',
                    'cpuLoadAvg5' => 'sn.Mezzanine Card CPU: Load Avg 5 (%)',
                    'cpuUser' => 'sn.Mezzanine Card CPU: User',
                    'ambStatus' => 'sn.Mezzanine Card Env: Ambient Status',
                    'ambTemp' => 'sn.Mezzanine Card Env: Ambient Temp (C)',
                    'coreStatus' => 'sn.Mezzanine Card Env: Core Status',
                    'coreTemp' => 'sn.Mezzanine Card Env: Core Temp (C)',
                    'coreThrsCritical' => 'sn.Mezzanine Card Env: Core Threshold Critical (C)',
                    'coreThrsMajor' => 'sn.Mezzanine Card Env: Core Threshold Major (C)',
                    'coreThrsMinor' => 'sn.Mezzanine Card Env: Core Threshold Minor (C)',
                    'memCache' => 'sn.Mezzanine Card Memory: Cache (Bytes)',
                    'memFree' => 'sn.Mezzanine Card Memory: Free (Bytes)',
                    'memTotal' => 'sn.Mezzanine Card Memory: Total (Bytes)',
                    'memUsed' => 'sn.Mezzanine Card Memory: Used (Bytes)',
                    'mfgPartNumber' => 'sn.Mezzanine Card Mfg Data: Part Number',
                    'mfgRevision' => 'sn.Mezzanine Card Mfg Data: Revision',
                    'mfgSerialNumber' => 'sn.Mezzanine Card Mfg Data: Serial Number' 
            ];
        } else if ($function === 'bandsParams') {
            return [
                    'nativeId' => 'b.native_id',
                    'enable' => 'b.Enable',
                    'operState' => 'b.Oper State',
                    'actualBand' => 'b.Actual Band',
                    'band' => 'b.Band',
                    'bypassAlarms' => 'b.Bypass Alarms',
                    'maxTxPower' => 'b.Max Tx Power'
            ];
        } else if ($function === 'radioNodeParams') {
            return [
                    'nativeId' => 'rn.native_id',
                    'bands' => [NodeFields::bandsParams ()],
                    'enable' => 'rn.Enable',
                    'operState' => 'rn.Oper State',
                    'altitude' => 'rn.Altitude (Meters)',
                    'altitudeUnc' => 'rn.Altitude Uncertainty (Meters)',
                    'arriveTime' => 'rn.Arrive Time',
                    'bypassAlarm' => 'rn.Bypass Alarms',
                    'confidence' => 'rn.Confidence',
                    'description' => 'rn.Description',
                    'macAddress' => 'rn.MAC Address',
                    'fwdEngineRetries' => 'rn.Forwarding Engine Number Of Entries',
                    'geoAreaFormat' => 'rn.Geographical Area Format',
                    'horizontalUnc' => 'rn.Horizontal Uncertainty',
                    'serviceNodeLocation' => 'rn.Inherit Services Node Location',
                    'innerIp' => 'rn.Inner IPAddress',
                    'ip' => 'rn.IPAddress',
                    'lanRetries' => 'rn.LANDevice Number Of Entries',
                    'latitude' => 'rn.Latitude',
                    'longitude' => 'rn.Longitude',
                    'modelNumber' => 'rn.Model Number',
                    'name' => 'rn.Name',
                    'outerIp' => 'rn.Outer IPAddress',
                    'securityMode' => 'rn.Security Mode',
                    'uncAxisOrient' => 'rn.Uncertainty Ellipse Axis Orientation',
                    'uncSemiMajor' => 'rn.Uncertainty Ellipse Semi Major',
                    'uncSemiMinor' => 'rn.Uncertainty Ellipse Semi Minor',
                    'partNumber' => 'rn.Part Number',
                    'revision' => 'rn.Revision',
                    'serialNumber' => 'rn.Serial Number'
            ];
        } else if ($function === 'mgmtDeviceParams') {
            return [
                    'nativeId' => 'md.native_id',
                    'dhcpEnable' => 'md.DHCPServer Enable',
                    'enable' => 'md.Enable',
                    'operState' => 'md.Oper State',
                    'bypassAlarm' => 'md.Bypass Alarms',
                    'description' => 'md.Description',
                    'egressGroup' => 'md.Egress Classification Group',
                    'fwdGroup' => 'md.Forwarding Group Index',
                    'ingressGroup' => 'md.Ingress Classification Group',
                    'ip' => 'md.IPInterface IPAddress',
                    'ipPrefix' => 'md.IPInterface Prefix Length',
                    'ipSubnetMask' => 'md.IPInterface Subnet Mask',
                    'name' => 'md.Name',
                    'vlanId' => 'md.VLANID'
            ];
        } else if ($function === 'lanDeviceParams') {
            return [
                    'nativeId' => 'ld.native_id',
                    'dhcpEnable' => 'ld.DHCPServer Enable',
                    'enable' => 'ld.Enable',
                    'operState' => 'ld.Oper State',
                    'serviceEnable' => 'ld.Services Module Interface Enable',
                    'status' => 'ld.Status',
                    'bypassAlarm' => 'ld.Bypass Alarms',
                    'description' => 'ld.Description',
                    'duplexMode' => 'ld.Duplex Mode',
                    'egressGroup' => 'ld.Egress Classification Group',
                    'egressProfile' => 'ld.Egress Qo SProfile',
                    'factoryReset' => 'ld.Enable Factory Reset',
                    'fwdGroupIndex' => 'ld.Forwarding Group Index',
                    'ingressGroup' => 'ld.Ingress Classification Group',
                    'ipAddrType' => 'ld.IPInterface Addressing Type',
                    'ip' => 'ld.IPInterface IPAddress',
                    'ipPrefix' => 'ld.IPInterface Prefix Length',
                    'ipSubnetMask' => 'ld.IPInterface Subnet Mask',
                    'mac' => 'ld.MACAddress',
                    'maxBitRate' => 'ld.Max Bit Rate',
                    'mtu' => 'ld.MTU',
                    'name' => 'ld.Name',
                    'queueNumberEntries' => 'ld.Queue Number Of Entries',
                    'serviceHostIndex' => 'ld.Services Host Index',
                    'sharedEgressQos' => 'ld.Shared Egress Qo S',
                    'trunkDeviceIdx' => 'ld.Trunk Device Index',
                    'unknownProtoPacketsReceived' => 'ld.Unknown Proto Packets Received',
                    'unknownProtoPacketsReceived64' => 'ld.Unknown Proto Packets Received 64 Bit',
                    'vlanId' => 'ld.VLANID',
                    'bcastPktsReceived' => 'ld.Broadcast Packets Received',
                    'bcastPktsReceived64' => 'ld.Broadcast Packets Received 64 Bit',
                    'bcastPktsSent' => 'ld.Broadcast Packets Sent',
                    'bcastPktsSent64' => 'ld.Broadcast Packets Sent 64 Bit',
                    'bytesReceived' => 'ld.Bytes Received',
                    'bytesReceived64' => 'ld.Bytes Received 64 Bit',
                    'bytesSent' => 'ld.Bytes Sent',
                    'bytesSent64' => 'ld.Bytes Sent 64 Bit',
                    'discardPktsReceived' => 'ld.Discard Packets Received',
                    'discardPktsReceived64' => 'ld.Discard Packets Received 64 Bit',
                    'discardPktsSent' => 'ld.Discard Packets Sent',
                    'discardPktsSent64' => 'ld.Discard Packets Sent 64 Bit',
                    'errorsReceived' => 'ld.Errors Received',
                    'errorsReceived64' => 'ld.Errors Received 64 Bit',
                    'errorsSent' => 'ld.Errors Sent',
                    'errorsSent64' => 'ld.Errors Sent 64 Bit',
                    'mcastPktsReceived' => 'ld.Multicast Packets Received',
                    'mcastPktsReceived64' => 'ld.Multicast Packets Received 64 Bit',
                    'mcastPktsSent' => 'ld.Multicast Packets Sent',
                    'mcastPktsSent64' => 'ld.Multicast Packets Sent 64 Bit',
                    'pktsReceived' => 'ld.Packets Received',
                    'pktsReceived64' => 'ld.Packets Received 64 Bit',
                    'pktsSent' => 'ld.Packets Sent',
                    'pktsSent64' => 'ld.Packets Sent 64 Bit',
                    'ucastPktsReceived' => 'ld.Unicast Packets Received',
                    'ucastPktsReceived64' => 'ld.Unicast Packets Received 64 Bit',
                    'ucastPktsSent' => 'ld.Unicast Packets Sent',
                    'ucastPktsSent64' => 'ld.Unicast Packets Sent 64 Bit'
            ];
        } else if ($function === 'cecParams') {
            return [ 
                    'cpuIdle' => 'sn.CPU Idle',
                    'cpuKernel' => 'sn.CPU Kernel',
                    'cpuLoadAvg1' => 'sn.CPU Load Avg 1 (%)',
                    'cpuLoadAvg15' => 'sn.CPU Load Avg 5 (%)',
                    'cpuLoadAvg5' => 'sn.CPU Load Avg 15 (%)',
                    'cpuUser' => 'sn.CPU User',
                    'ambStatus' => 'sn.CEC Ambient Status',
                    'ambTemp' => 'sn.CEC Ambient Temp (C)',
                    'ambThrshCritical' => 'sn.CEC Ambient Threshold Critical (C)',
                    'ambThrshMajor' => 'sn.CEC Ambient Threshold Major (C)',
                    'ambThrshMinor' => 'sn.CEC Ambient Threshold Minor (C)',
                    'coreStatus' => 'sn.CEC Core Status',
                    'coreTemp' => 'sn.CEC Core Temp (C)',
                    'coreThrshCritical' => 'sn.CEC Core Threshold Critical (C)',
                    'coreThrshMajor' => 'sn.CEC Core Threshold Major (C)',
                    'coreThrshMinor' => 'sn.CEC Core Threshold Minor (C)',
                    'memCache' => 'sn.Memory Cache (Bytes)',
                    'memFree' => 'sn.Memory Free (Bytes)',
                    'memTotal' => 'sn.Memory Total (Bytes)',
                    'memUsed' => 'sn.Memory Used (Bytes)',
                    'revision' => 'sn.Revision',
                    'partNumber' => 'sn.Part Number',
            ];
        } else if ($function === 'nodeParams') {
            return [ 
                    'serialNumber' => 'sn.Serial Number',
                    'originalName' => 'sn.original_name',
                    'dateCreated' => 'UNIX_TIMESTAMP(nd.date_created)',
                    'dateUpdated' => 'UNIX_TIMESTAMP(nd.date_updated)',
                    'stats' => NodeFields::statsParams (),
                    'radioNodes' => [NodeFields::radioNodeParams ()],
                    'mgmtDevices' => [NodeFields::mgmtDeviceParams ()],
                    'lanDevices' => [NodeFields::lanDeviceParams ()],
                    'altitude' => 'sn.Altitude (Meters)',
                    'arriveTime' => 'sn.Arrive Time',
                    'bypassAlarms' => 'sn.Bypass Alarms',
                    'currentTime' => 'sn.Current Time',
                    'description' => 'sn.Description',
                    'disk' => NodeFields::diskParams (),
                    'displayName' => 'sn.Display Name',
                    'factoryReset' => 'sn.Enable Factory Reset',
                    'consoleAccess' => 'sn.Enable Field Recovery Console Access',
                    'fans' => NodeFields::fansParams (),
                    'firmware' => 'sn.firmware',
                    'formattedPosition' => 'sn.Formatted Position',
                    'fwdEngineRetries' => 'sn.Forwarding Engine Number Of Entries',
                    'hostName' => 'sn.Host Name',
                    'scanTime' => 'sn.Last Scan Time',
                    'latitude' => 'sn.Latitude',
                    'location' => 'sn.Location',
                    'longitude' => 'sn.Longitude',
                    'mezzanineCard' => NodeFields::mcParams (),
                    'commonEquipmentCard' => NodeFields::cecParams (),
                    'modelNumber' => 'sn.Model Number',
                    'name' => 'sn.Name',
                    'nameServer' => 'sn.Name Server',
                    'operState' => 'sn.Oper State',
                    'operMode' => 'sn.Operating Mode',
                    'psu1Name' => 'sn.PSU 1: Display Name',
                    'psu1Status' => 'sn.PSU 1: Status',
                    'psu2Name' => 'sn.PSU 2: Display Name',
                    'psu2Status' => 'sn.PSU 2: Status',
                    'scanMode' => 'sn.Scan Mode',
                    'scanStatus' => 'sn.Scan Status',
                    'moduleInterfaceEnable' => 'sn.Services Module Interface Enable',
                    'uptime' => 'sn.Up Time',
                    'usbName' => 'sn.USB Display Name',
                    'usbMake' => 'sn.USB Make',
                    'usbModel' => 'sn.USB Model',
                    'usbStatus' => 'sn.USB Status',
                    'nativeId' => 'nd.native_id' 
            ];
        } else {
            return [ ];
        }
    }
}
