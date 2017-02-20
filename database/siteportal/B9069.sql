-- Author: Jay Stoffle
-- Bug: 9069
-- Description: SHJ Type and Port Def Inserts

INSERT IGNORE css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children, main_device,build_file,scan_file,controller_file)
VALUES
(2423,1074,"SHJ","Generic RTU",1,0,0,0,1,"genericDeviceBuilder.php","genericScannerLauncher.php","genericDeviceController.php");

INSERT IGNORE css_networking_device_port_def (device_type_id, variable_name, name, default_port) VALUES (2423, 'modbus', 'MODBUS', 502);
