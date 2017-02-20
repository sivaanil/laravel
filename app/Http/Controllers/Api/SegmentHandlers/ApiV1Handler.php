<?php

namespace Unified\Http\Controllers\Api\SegmentHandlers;

use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\SegmentHandler;

/**
 * API V1 request handler.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class ApiV1Handler extends SegmentHandler {
    private $v1Handlers;
    public function __construct() {
        $this->v1Handlers = array (
                "login" => "Unified\Http\Controllers\Api\ResourceProcessors\LoginProcessor",
                "logon" => "Unified\Http\Controllers\Api\ResourceProcessors\LoginProcessor",
                "logoff" => "Unified\Http\Controllers\Api\ResourceProcessors\LogoutProcessor",
                "logout" => "Unified\Http\Controllers\Api\ResourceProcessors\LogoutProcessor",
                "system" => "Unified\Http\Controllers\Api\ResourceProcessors\SystemProcessor",
                "nodes" => "Unified\Http\Controllers\Api\SegmentHandlers\NodesHandler",
                "nodeClasses" => "Unified\Http\Controllers\Api\ResourceProcessors\NodeClassesProcessor",
                "nodeTypes" => "Unified\Http\Controllers\Api\ResourceProcessors\NodeTypesProcessor", 
                "alarmSeverities" => "Unified\Http\Controllers\Api\ResourceProcessors\AlarmSeveritiesProcessor", 
                "alarmRuleTypes" => "Unified\Http\Controllers\Api\ResourceProcessors\AlarmRuleTypesProcessor", 
                "alarms" => "Unified\Http\Controllers\Api\SegmentHandlers\AlarmsHandler", 
                "permissions" => "Unified\Http\Controllers\Api\SegmentHandlers\PermissionHandler",
                "propertyGroups" => "Unified\Http\Controllers\Api\ResourceProcessors\PropertyGroupsProcessor", 
                "propertyTypes" => "Unified\Http\Controllers\Api\ResourceProcessors\PropertyTypesProcessor", 
                "propertyOptions" => "Unified\Http\Controllers\Api\ResourceProcessors\PropertyOptionsProcessor", 
                "propertyDefinitions" => "Unified\Http\Controllers\Api\ResourceProcessors\PropertyDefinitionsProcessor", 
                "properties" => "Unified\Http\Controllers\Api\SegmentHandlers\PropertiesHandler", 
                "propertyLogs" => "Unified\Http\Controllers\Api\ResourceProcessors\PropertyLogsProcessor", 
        		"roles" => "Unified\Http\Controllers\Api\SegmentHandlers\RolesHandler",
                "snmpDest" => "Unified\Http\Controllers\Api\SegmentHandlers\SnmpDestHandler", 
                "notifications" => "Unified\Http\Controllers\Api\SegmentHandlers\NotificationsHandler", 
                "virtualDevices" => "Unified\Http\Controllers\Api\SegmentHandlers\VirtualDeviceHandler", 
        );
    }
    protected function getHandler(Request $request, $segment) {
        // Get resource name
        $resource = $request->segment ( $segment );
        if (! empty ( $resource ) && isset ( $this->v1Handlers [$resource] )) {
            return $this->v1Handlers [$resource];
        }

        return null;
    }
}
