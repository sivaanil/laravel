<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBaseAclPermissionsForApi extends Migration
{
	private $tableName = 'acl_permissions';
	
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	$sql = "INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (1, 'Read system', 'read_system', 'Get system info - /system GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (2, 'Read node classes', 'read_node_classes', 'Read node classes - /nodeClasses GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (3, 'Read node types', 'read_node_types', 'Read node types - /nodeTypes GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (4, 'Read nodes', 'read_nodes', 'Read nodes - /nodes GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (5, 'Create nodes', 'create_nodes', 'Create nodes - /nodes POST');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (6, 'Read node', 'read_node', 'Read node - /node/{id} GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (7, 'Update node', 'update_node', 'Update node - /node/{id} PUT');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (8, 'Delete node', 'delete_node', 'Delete node - /node/{id} DELETE');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (9, 'Read alarm severities', 'read_alarm_severities', 'Read alarm severities - /alarmSeverities GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (10, 'Read alarm rule types', 'read_alarm_rule_types', 'Read alarm rule types - /alarmRuleTypes GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (11, 'Read alarms', 'read_alarms', 'Read alarms - /alarms GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (12, 'Read alarm', 'read_alarm', 'Read alarm - /alarms/{id} GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (13, 'Update alarm', 'update_alarm', 'Update alarm - /alarms/{id} PUT');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (14, 'Read property groups', 'read_property_groups', 'Read property groups - /propertyGroups GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (15, 'Read property types', 'read_property_types', 'Read property types - /propertyTypes GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (16, 'Read property definitions', 'read_property_definitions', 'Read property definitions - /propertyDefinitions GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (17, 'Read property options', 'read_property_options', 'Read property options - /propertyOptions GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (18, 'Read properties', 'read_properties', 'Read properties - /properties GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (19, 'Read property', 'read_property', 'Read property - /properties/{id} GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (20, 'Update property', 'update_property', 'Update property - /properties/{id} PUT');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (21, 'Read property logs', 'read_property_logs', 'Read property logs - /properties/{id} GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (22, 'Create property logs', 'create_property_logs', 'Create property logs - /properties/{id} POST');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (23, 'Read SNMP destinations', 'read_snmp_destinations', 'Read snmp destinations - /snmpDest GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (24, 'Create SNMP destinations', 'create_snmp_destinations', 'Create snmp destinations - /snmpDest POST');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (25, 'Read SNMP destination', 'read_snmp_destination', 'Read SNMP destination - /snmpDest/{id} GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (26, 'Update SNMP destination', 'update_snmp_destination', 'Update SNMP destination - /snmpDest/{id} PUT');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (27, 'Delete SNMP destination', 'delete_snmp_destination', 'Delete SNMP destination - /snmpDest/{id} DELETE');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (28, 'Read roles', 'read_roles', 'Read roles - /roles GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (29, 'Create roles', 'create_roles', 'Create roles - /roles POST');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (30, 'Read role', 'read_role', 'Read role - /roles/{id} GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (31, 'Update role', 'update_role', 'Update role - /roles/{id} PUT');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (32, 'Delete role', 'delete_role', 'Delete role - /roles/{id} DELETE');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (33, 'Read SNMP notifications', 'read_notifications', 'Read notifications - /notifications GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (34, 'Create SNMP notifications', 'create_notifications', 'Create notifications - /notifications POST');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (35, 'Read SNMP notification', 'read_notification', 'Read notification - /notifications/{id} GET');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (36, 'Update SNMP notification', 'update_notification', 'Update notification - /notifications/{id} PUT');
INSERT INTO `acl_permissions` (`id`, `title`, `slug`, `description`) VALUES (37, 'Delete SNMP notification', 'delete_notification', 'Delete notification - /notifications/{id} DELETE');
    			";
    
    	DB::raw($sql);
    }
    	
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	DB::raw('DELETE from `acl_permissions` WHERE id >= 1 AND id <= 37');
    }
}
