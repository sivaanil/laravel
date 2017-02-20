
-- 2309 Westell RMM-800 device integration - Wayne 20160831
REPLACE INTO css_networking_device_type SET id = 2309, class_id = 1074, vendor = 'Westell', model = 'RMM-800', auto_build_enabled = 1,
uses_snmp = 1, defaultSNMPVer = '2c', defaultSNMPRead = 'eea478269a2bbc0498ac382ed500822c', defaultSNMPWrite = '31cfdf3e44c7ed4a34262041fc664d8c',
main_device = 1, build_file = 'rmx_flatlist_builder.php', scan_file = 'rmx_3200_launcher.php', SNMPauthEncryption = 'SHA',
SNMPprivEncryption = 'AES', SNMPauthType = 'authPriv', support_traps = 1, has_web_interface = 1, canvas_pref_top = 'KentroxRMM1400SelectionTop',
canvas_pref_bottom = 'KentroxRMM1400SelectionBottom', canvas_list = 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,
Generator Details View,Graph,Location and Access Info,Log,Map,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera';

-- 2309 Westell RMM-800 port definitions
REPLACE INTO css_networking_device_port_def SET device_type_id = 2309, variable_name = 'http', name = 'HTTP', default_port = 80;
REPLACE INTO css_networking_device_port_def SET device_type_id = 2309, variable_name = 'https', name = 'HTTPS', default_port = 443;
REPLACE INTO css_networking_device_port_def SET device_type_id = 2309, variable_name = 'snmp', name = 'SNMP', default_port = 161;
REPLACE INTO css_networking_device_port_def SET device_type_id = 2309, variable_name = 'ftp', name = 'FTP', default_port = 21;
REPLACE INTO css_networking_device_port_def SET device_type_id = 2309, variable_name = 'sftp', name = 'SFTP', default_port = 22;
-- 2309 Westell RMM-800 device integration - Wayne 20160831-- Bug 7674 - SpiderNET API - Integrate the SpiderCloud Services Node using SpiderNET  - Nick Atkocaitis 7/21/16

REPLACE INTO css_general_config (setting_name, var1, var2, var3, var4, description)
                                VALUES ("spidercloud_environment_info", "", "0fa0325bbf7a88acb9cef967c8402d1d", "a6afbde6fc1e16c81fcd905bfaa44c53", '443',
                                    "The SpiderCloud environment corresponding with this SitePortal server.  Var1 is ip, Var2 is username, Var3 is password, var4 is port");

INSERT IGNORE INTO css_networking_device_class (id, description, is_license)
                                VALUES ('1157', 'SpiderCloud', '0');

INSERT IGNORE INTO css_networking_device_class (id, description, is_license)
                                VALUES ('1158', 'SpiderCloud Device', '0');

INSERT IGNORE INTO css_networking_device_type (id, class_id, vendor, model, auto_build_enabled, uses_snmp, snmp_only, can_add_children, can_disable, defaultWebUi, defaultWebUiUser, defaultWebUiPw, defaultSNMPVer, defaultSNMPRead, defaultSNMPWrite, date_updated, main_device, general_device_id, node_type, uses_default_value, build_file, scan_file, prop_scan_file, controller_file, SNMPuserName, SNMPauthPassword, SNMPauthEncryption, SNMPprivPassword, SNMPprivEncryption, SNMPauthType, rebuilder_file, support_traps, has_web_interface, canvas_pref_top, canvas_pref_bottom, canvas_default_top, canvas_default_bottom, canvas_list, development_flag, auto_detect_flag, heartbeat_threshold_enabled)
                                VALUES ('5056', '1157', 'SpiderCloud', 'Services Node', '1', '0', '0', '0', '0', '', '', '', '', '', '', '2016-03-28 11:20:34', '1', '0', '0', '1', 'spidernet_builder_launcher.php', 'spidernet_alarm_scanner_launcher.php', 'spidernet_property_scanner_launcher.php', NULL, 0, NULL, NULL, NULL, NULL, NULL, 'spidernet_rebuilder_launcher.php', '0', '1', NULL, NULL, '0', '0', 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Ignored Alarms,Location and Access Info,Log,Map,Notes,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', '0', '0', '0');

INSERT IGNORE INTO css_networking_device_type (id, class_id, vendor, model, auto_build_enabled, uses_snmp, snmp_only, can_add_children, can_disable, defaultWebUi, defaultWebUiUser, defaultWebUiPw, defaultSNMPVer, defaultSNMPRead, defaultSNMPWrite, date_updated, main_device, general_device_id, node_type, uses_default_value, build_file, scan_file, prop_scan_file, controller_file, SNMPuserName, SNMPauthPassword, SNMPauthEncryption, SNMPprivPassword, SNMPprivEncryption, SNMPauthType, rebuilder_file, support_traps, has_web_interface, canvas_pref_top, canvas_pref_bottom, canvas_default_top, canvas_default_bottom, canvas_list, development_flag, auto_detect_flag, heartbeat_threshold_enabled)
                                VALUES ('5057', '1158', 'SpiderCloud', 'Radio Node', '0', '0', '0', '0', '0', '', '', '', '', '', '', '2016-03-28 11:20:34', '0', '0', '0', '1', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '0', '1', NULL, NULL, '0', '0', 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Ignored Alarms,Location and Access Info,Log,Map,Notes,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', '0', '0', '0');

INSERT IGNORE INTO css_networking_device_type (id, class_id, vendor, model, auto_build_enabled, uses_snmp, snmp_only, can_add_children, can_disable, defaultWebUi, defaultWebUiUser, defaultWebUiPw, defaultSNMPVer, defaultSNMPRead, defaultSNMPWrite, date_updated, main_device, general_device_id, node_type, uses_default_value, build_file, scan_file, prop_scan_file, controller_file, SNMPuserName, SNMPauthPassword, SNMPauthEncryption, SNMPprivPassword, SNMPprivEncryption, SNMPauthType, rebuilder_file, support_traps, has_web_interface, canvas_pref_top, canvas_pref_bottom, canvas_default_top, canvas_default_bottom, canvas_list, development_flag, auto_detect_flag, heartbeat_threshold_enabled)
                                VALUES ('5058', '1158', 'SpiderCloud', 'Band', '0', '0', '0', '0', '0', '', '', '', '', '', '', '2016-03-28 11:20:34', '0', '0', '0', '1', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '0', '1', NULL, NULL, '0', '0', 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Ignored Alarms,Location and Access Info,Log,Map,Notes,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', '0', '0', '0');

INSERT IGNORE INTO css_networking_device_type (id, class_id, vendor, model, auto_build_enabled, uses_snmp, snmp_only, can_add_children, can_disable, defaultWebUi, defaultWebUiUser, defaultWebUiPw, defaultSNMPVer, defaultSNMPRead, defaultSNMPWrite, date_updated, main_device, general_device_id, node_type, uses_default_value, build_file, scan_file, prop_scan_file, controller_file, SNMPuserName, SNMPauthPassword, SNMPauthEncryption, SNMPprivPassword, SNMPprivEncryption, SNMPauthType, rebuilder_file, support_traps, has_web_interface, canvas_pref_top, canvas_pref_bottom, canvas_default_top, canvas_default_bottom, canvas_list, development_flag, auto_detect_flag, heartbeat_threshold_enabled)
								VALUES ('5059', '1158', 'SpiderCloud', 'Management Device', '0', '0', '0', '0', '0', '', '', '', '', '', '', '2016-03-28 11:20:34', '0', '0', '0', '1', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '0', '1', NULL, NULL, '0', '0', 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Ignored Alarms,Location and Access Info,Log,Map,Notes,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', '0', '0', '0');

INSERT IGNORE INTO css_networking_device_type (id, class_id, vendor, model, auto_build_enabled, uses_snmp, snmp_only, can_add_children, can_disable, defaultWebUi, defaultWebUiUser, defaultWebUiPw, defaultSNMPVer, defaultSNMPRead, defaultSNMPWrite, date_updated, main_device, general_device_id, node_type, uses_default_value, build_file, scan_file, prop_scan_file, controller_file, SNMPuserName, SNMPauthPassword, SNMPauthEncryption, SNMPprivPassword, SNMPprivEncryption, SNMPauthType, rebuilder_file, support_traps, has_web_interface, canvas_pref_top, canvas_pref_bottom, canvas_default_top, canvas_default_bottom, canvas_list, development_flag, auto_detect_flag, heartbeat_threshold_enabled)
								VALUES ('5061', '1158', 'SpiderCloud', 'LAN Device', '0', '0', '0', '0', '0', '', '', '', '', '', '', '2016-03-28 11:20:34', '0', '0', '0', '1', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '0', '1', NULL, NULL, '0', '0', 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Ignored Alarms,Location and Access Info,Log,Map,Notes,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', '0', '0', '0');

INSERT IGNORE INTO css_networking_device_type (id, class_id, vendor, model, auto_build_enabled, uses_snmp, snmp_only, can_add_children, can_disable, defaultWebUi, defaultWebUiUser, defaultWebUiPw, defaultSNMPVer, defaultSNMPRead, defaultSNMPWrite, date_updated, main_device, general_device_id, node_type, uses_default_value, build_file, scan_file, prop_scan_file, controller_file, SNMPuserName, SNMPauthPassword, SNMPauthEncryption, SNMPprivPassword, SNMPprivEncryption, SNMPauthType, rebuilder_file, support_traps, has_web_interface, canvas_pref_top, canvas_pref_bottom, canvas_default_top, canvas_default_bottom, canvas_list, development_flag, auto_detect_flag, heartbeat_threshold_enabled)
                                VALUES ('2065', '1158', 'SpiderCloud', 'Subdevice', '0', '0', '0', '0', '0', '', '', '', '', '', '', '2016-03-28 11:20:34', '0', '0', '0', '1', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '0', '1', NULL, NULL, '0', '0', 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Ignored Alarms,Location and Access Info,Log,Map,Notes,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', '0', '0', '0');


REPLACE INTO css_networking_device_port_def (device_type_id, variable_name, name, default_port)
    SELECT '5056', 'http', 'HTTP', '80'
        FROM dual
        WHERE NOT EXISTS (SELECT * FROM css_networking_device_port_def
                             WHERE device_type_id = '5056'
                               AND variable_name = 'http');

REPLACE INTO css_networking_device_prop_def (device_type_id,variable_name,name,visible,data_type)
VALUES (5056,'original_name','original_name', 0, 'STRING');


INSERT IGNORE INTO def_prop_groups (group_var_name, group_breadCrumb)
        VALUES ('Disk Partition Info', 'Disk Partition Info');

INSERT IGNORE INTO def_prop_groups (group_var_name, group_breadCrumb)
        VALUES ('Fan Info', 'Fan Info');

INSERT IGNORE INTO def_prop_groups (group_var_name, group_breadCrumb)
        VALUES ('Mezzanine Card Info', 'Mezzanine Card Info');

INSERT IGNORE INTO def_prop_groups (group_var_name, group_breadCrumb)
        VALUES ("Mezzanine Card Info\\Mezzanine Card Memory", "Mezzanine Card Info\\Mezzanine Card Memory");

INSERT IGNORE INTO def_prop_groups (group_var_name, group_breadCrumb)
        VALUES ("Mezzanine Card Info\\Mezzanine Card CPU", "Mezzanine Card Info\\Mezzanine Card CPU");

INSERT IGNORE INTO def_prop_groups (group_var_name, group_breadCrumb)
        VALUES ("Mezzanine Card Info\\Mezzanine Card Env", "Mezzanine Card Info\\Mezzanine Card Env");

INSERT IGNORE INTO def_prop_groups (group_var_name, group_breadCrumb)
        VALUES ("Mezzanine Card Info\\Mezzanine Card Mfg Data", "Mezzanine Card Info\\Mezzanine Card Mfg Data");

INSERT IGNORE INTO def_prop_groups (group_var_name, group_breadCrumb)
        VALUES ('USB Info', 'USB Info');

INSERT IGNORE INTO def_prop_groups (group_var_name, group_breadCrumb)
        VALUES ('Memory Info', 'Memory Info');

INSERT IGNORE INTO def_prop_groups (group_var_name, group_breadCrumb)
        VALUES ('CEC Info', 'CEC Info');

INSERT IGNORE INTO def_prop_groups (group_var_name, group_breadCrumb)
        VALUES ('PSU Info', 'PSU Info');

INSERT IGNORE INTO def_prop_groups (group_var_name, group_breadCrumb)
        VALUES ('CPU Info', 'CPU Info');




INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ('Disk Partition Info', 'Disk Partition Info');

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ('Fan Info', 'Fan Info');

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ('Mezzanine Card Info', 'Mezzanine Card Info');

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ("Mezzanine Card Info\\Mezzanine Card Memory", "Mezzanine Card Info\\Mezzanine Card Memory");

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ("Mezzanine Card Info\\Mezzanine Card CPU", "Mezzanine Card Info\\Mezzanine Card CPU");

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ("Mezzanine Card Info\\Mezzanine Card Env", "Mezzanine Card Info\\Mezzanine Card Env");

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ('USB Info', 'USB Info');

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ('Memory Info', 'Memory Info');

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ('CEC Info', 'CEC Info');

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ('PSU Info', 'PSU Info');

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ('CPU Info', 'CPU Info');

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ('Unicast Info', 'Unicast Info');

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ('Multicast Info', 'Multicast Info');

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ('Errors', 'Errors');

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ('Discard Packets', 'Discard Packets');

INSERT IGNORE INTO def_status_groups (group_var_name, group_breadCrumb)
        VALUES ('Data Info', 'Data Info');



INSERT IGNORE INTO def_device_filter_tree (id, name, path, parent_id, ui)
        VALUES (329, 'SpiderCloud', '0:11:27', 27, 'MAPPING,USER_PREF');

INSERT IGNORE INTO def_device_filter_tree (id, name, path, parent_id, ui)
        VALUES (330, 'SpiderCloud', '0:200:211:227', 227, 'CUSTOM_TREE');

INSERT IGNORE INTO def_device_filter_map (id, device_type_id, config_id)
        VALUES (1195, 5056, 330);

INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('1', NULL, '5057', '1', '0', NULL, 'Enable', 'Enable', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('1', NULL, '5058', '1', '0', NULL, 'Enable', 'Enable', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('1', NULL, '5059', '1', '0', NULL, 'Enable', 'Enable', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('1', NULL, '5061', '1', '0', NULL, 'Enable', 'Enable', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('1', NULL, '5059', '1', '0', NULL, 'DHCPServer Enable', 'DHCPServer Enable', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('1', NULL, '5061', '1', '0', NULL, 'DHCPServer Enable', 'DHCPServer Enable', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('1', NULL, '5061', '1', '0', NULL, 'Enable Factory Reset', 'Enable Factory Reset', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('1', NULL, '5056', '1', '0', NULL, 'Enable Factory Reset', 'Enable Factory Reset', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('1', NULL, '5056', '1', '0', NULL, 'Enable Field Recovery Console Access', 'Enable Field Recovery Console Access', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('1', NULL, '5056', '1', '0', NULL, 'Scan Status', 'Scan Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('1', NULL, '5056', '1', '0', NULL, 'Services Module Interface Enable', 'Services Module Interface Enable', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('1', NULL, '5061', '1', '0', NULL, 'Services Module Interface Enable', 'Services Module Interface Enable', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('2', NULL, '5059', '1', '0', NULL, 'Oper State', 'Oper State', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('2', NULL, '5061', '1', '0', NULL, 'Oper State', 'Oper State', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('2', NULL, '5058', '1', '0', NULL, 'Oper State', 'Oper State', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('2', NULL, '5057', '1', '0', NULL, 'Oper State', 'Oper State', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('2', NULL, '1158', '1', '0', NULL, 'Oper State', 'Oper State', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, secure, graph_type, tooltip, valuetip, thresh_enable, severity_id_two, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Oper State', 'Oper State', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '0', '0', NULL, NULL, '0', '4', '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Fan 1: Status', 'Fan 1: Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Fan 2: Status', 'Fan 2: Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Fan 3: Status', 'Fan 3: Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Fan 4: Status', 'Fan 4: Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Fan 5: Status', 'Fan 5: Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Mezzanine Card Env: Ambient Status', 'Mezzanine Card Env: Ambient Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Mezzanine Card Env: Core Status', 'Mezzanine Card Env: Core Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'USB Status', 'USB Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'CEC Ambient Status', 'CEC Ambient Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'CEC Core Status', 'CEC Core Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'PSU 1: Status', 'PSU 1: Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'PSU 2: Status', 'PSU 2: Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Scan Status', 'Scan Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5061', '1', '0', NULL, 'Status', 'Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Current Time', 'Current Time', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Fan 1: Speed', 'Fan 1: Speed', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Fan 2: Speed', 'Fan 2: Speed', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Fan 3: Speed', 'Fan 3: Speed', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Fan 4: Speed', 'Fan 4: Speed', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Fan 5: Speed', 'Fan 5: Speed', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');
INSERT IGNORE INTO css_networking_device_prop_def (prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id, severity_id_two, secure, graph_type, thresh_enable, tooltip, valuetip, alarm_exempt)
        VALUES ('2', NULL, '5056', '1', '0', NULL, 'Last Scan Time', 'Last Scan Time', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '0', '0', NULL, NULL, '0');/* Bug 7298
    Adds a device type for the Ericsson SE600 trap handler. This is just the one-way trap handler device, as opposed to
    the full SE600 driver with type id 5025
*/

-- Rename the full-fledged driver to 'Ericsson SE 600' from 'Ericsson SE600 Trap Handler'
UPDATE css_networking_device_type  SET model='Ericsson SE600' WHERE id=5025;

-- Add the new device type if it doesn't exist
INSERT IGNORE INTO css_networking_device_type (id, class_id, vendor, model, auto_build_enabled, uses_snmp, can_disable, 
    defaultWebUiUser, defaultWebUiPw, defaultSNMPVer, main_device, general_device_id, build_file, support_traps, 
    has_web_interface, development_flag) 
VALUES ('5035', '6', 'Ericsson', 'SE600 Trap Handler', '1', '1', '1', 
        '(NOTINUSE)', '1146ac27939eb9e2397f552ee5e53a6b', '2c', '1', '0', 'ericsson_se600_trap_handler_builder_launcher.php', '1', '0', '0');


DELIMITER $$
DROP PROCEDURE IF EXISTS create_ericsson_se600_trap_handler_device_type $$
CREATE PROCEDURE create_ericsson_se600_trap_handler_device_type()

BEGIN
    DECLARE CheckExists int;
    SET CheckExists = 0; 

    -- Give the new device a port. This isn't real, it's just so the device can be built
    SELECT COUNT(*)  INTO CheckExists
    FROM css_networking_device_port_def
    WHERE device_type_id = 5035;

    IF (CheckExists = 0) THEN
        INSERT INTO css_networking_device_port_def (device_type_id, variable_name, name, default_port) VALUES ('5035', 'snmp', 'SNMP', '161');
    END IF;
END  $$

CALL create_ericsson_se600_trap_handler_device_type $$
DROP PROCEDURE IF EXISTS create_ericsson_se600_trap_handler_device_type $$

DELIMITER ;

-- Author: Jay Stoffle
-- Bug: 7791
-- Description: SHJ Type and Port Def Inserts

INSERT IGNORE css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children, main_device,build_file,scan_file,controller_file)
VALUES
(2423,1074,"SHJ","Generic RTU",1,1,0,0,1,"genericDeviceBuilder.php","genericScannerLauncher.php","genericDeviceController.php");

INSERT IGNORE css_networking_device_port_def (device_type_id, variable_name, name, default_port) VALUES (2423, 'modbus', 'MODBUS', 502);
-- Author: Jay Stoffle
-- Bug: 7791
-- Description: SHJ Prop Defs, Prop Opts, and Prop Groups

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1620,'DI Register Alarm State','Alarm State','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1620, name = 'Alarm State', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1621,'RE Register Alarm State','Alarm State','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1621, name = 'Alarm State', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1622,'AI Register Alarm State','Alarm State','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1622, name = 'Alarm State', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Digital Input Alarm', 'DI Register Alarm State', 1620);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Relay Alarm', 'RE Register Alarm State', 1621);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Analog Input Alarm', 'AI Register Alarm State', 1622);

delete from css_networking_device_prop_opts where value = '1' and text = 'Enabled' and prop_def_id = ( SELECT id FROM css_networking_device_prop_def WHERE variable_name = 'DI Register Enable/Disable' AND device_type_id = 1620 ); 
delete from css_networking_device_prop_opts where value = '0' and text = 'Disabled' and prop_def_id = ( SELECT id FROM css_networking_device_prop_def WHERE variable_name = 'DI Register Enable/Disable' AND device_type_id = 1620 ); 

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'1','Critical' FROM css_networking_device_prop_def WHERE (variable_name = 'DI Register Severity' AND device_type_id = 1620) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'2','Major' FROM css_networking_device_prop_def WHERE (variable_name = 'DI Register Severity' AND device_type_id = 1620) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'3','Minor' FROM css_networking_device_prop_def WHERE (variable_name = 'DI Register Severity' AND device_type_id = 1620) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'4','Warning' FROM css_networking_device_prop_def WHERE (variable_name = 'DI Register Severity' AND device_type_id = 1620) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'5','Information' FROM css_networking_device_prop_def WHERE (variable_name = 'DI Register Severity' AND device_type_id = 1620) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'Enabled','Enabled' FROM css_networking_device_prop_def WHERE (variable_name = 'DI Register Enable/Disable' AND device_type_id = 1620) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'Disabled','Disabled' FROM css_networking_device_prop_def WHERE (variable_name = 'DI Register Enable/Disable' AND device_type_id = 1620) );

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'Closed','Closed' FROM css_networking_device_prop_def WHERE (variable_name = 'DI Register Alarm State' AND device_type_id = 1620) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'Open','Open' FROM css_networking_device_prop_def WHERE (variable_name = 'DI Register Alarm State' AND device_type_id = 1620) );

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'Inactive','Inactive' FROM css_networking_device_prop_def WHERE (variable_name = 'RE Register Alarm State' AND device_type_id = 1621) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'Active','Active' FROM css_networking_device_prop_def WHERE (variable_name = 'RE Register Alarm State' AND device_type_id = 1621) );
-- START
REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children, main_device,build_file,scan_file,controller_file)
VALUES
(9,1156,"GENERAC","H Panel",1,1,0,1,1,"generacHpanel_builder_launcher.php","generacHpanel_scanner_launcher.php","generacHpanelController.php");
-- END

-- START
INSERT IGNORE INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(9,'telnet','MODBUS',502);
INSERT IGNORE INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(9,'snmp','SNMP',161);
-- END


-- START
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','oil_temperature','Oil Temperature (F)','DECIMAL','0','2','Lube Oil Temperature');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','coolant_temperature','Coolant Temperature (F)','DECIMAL','0','2','Coolant Temperature');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','oil_pressure','Oil Pressure (PSI)','DECIMAL','0','2','Oil Pressure (psi)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','coolant_level','Coolant Level (%)','DECIMAL','0','2','Coolant Level (%)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','volume_percentlevel','Fuel Level %','DECIMAL','0','2','USER CFG 05');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','fuelPressure','Fuel Pressure (PSI)','DECIMAL','0','2','USER CFG 06');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','','throt_pos','Throttle Position (Steps)','DECIMAL','0','2','Throttle Position (Steps)',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','o2_sensor','Oxygen Sensor','DECIMAL','0','2','Throt Pos');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','user_cfg09','USER CFG 09','DECIMAL','0','2','USER CFG 09');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','battery_voltage','Battery Voltage (VDC)','DECIMAL','0','2','ECM Battery Voltage (VDC)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','current_phsa','Current Phase A (Amps)','DECIMAL','0','2','Current Phase A');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','current_phsb','Current Phase B (Amps)','DECIMAL','0','2','Current Phase B');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','current_phsc','Current Phase N (Amps)','DECIMAL','0','2','Current Phase C');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','avrg_current','Average Current (Amps)','DECIMAL','0','2','Current Phase C');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','volt_phsa','Voltage Phase A-N (Volts)','DECIMAL','0','2','Voltage Phase A');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','volt_phsb','Voltage Phase B-N (Volts)','DECIMAL','0','2','Voltage Phase B');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','volt_phsc','Voltage Phase A-B (Volts)','DECIMAL','0','2','Voltage Phase C');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','avrg_voltage','Average Voltage (Volts)','DECIMAL','0','2','Average Voltage');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','total_power','Total Power (KW)','DECIMAL','0','2','Total Power (KW)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','total_pf','Total Power Factor','DECIMAL','0','2','Total PF');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','gen_frequency','Generator Frequency (Hz)','DECIMAL','0','2','Generator Frequency (Hz)');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','engine_rpm','Engine Speed (RPM)','DECIMAL','0','2','Engine RPM');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','','af_duty_cycle','A/F Duty Cycle','DECIMAL','0','2','A/F Duty Cycle');

REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','1156','volume_tankvolume','Fuel Tank Capacity','INTEGER','1','1','The Fuel Tank Capacity Used by the Generator');
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','1156','gen_fuel_type','Fuel Type','INTEGER','1','1','The Fuel Type Used by the Generator');
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','1156','setRunTime','Generator Run Time (minutes)','INTEGER','1','1','Generator Run Time in minutes when start the generator');
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','1156','Generator Status','Generator Status','INTEGER','1','1','Generator Status Running/Not Running');
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','generator_running_state','Generator Running State','INTEGER','0','2','Generator Running State',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip) VALUES ('9','1156','generator_running','Generator Running','INTEGER','0','2','Generator Running');
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','generator_report_request_time','generator_report_request_time','INTEGER','1','1','generator_report_request_time',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','generator_run_end_time','generator_run_end_time','INTEGER','1','1','generator_run_end_time',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','generator_run_start_time','generator_run_start_time','INTEGER','1','1','generator_run_start_time',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','relayState','relayState','INTEGER','1','1','relayState',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','start_time','start_time','INTEGER','1','1','start_time',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','generator_running_time','generator_running_time','INTEGER','1','1','generator_running_time',0);

REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','auto_switch','Auto Switch','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','manual_switch','Manual Switch','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','emergency_stop','Emergency Stop','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','remote_start','Remote Start','INTEGER','1','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','di1user_Cfg05','DI1/USR CFG 5','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','di2fuel_pressure','DI2/Fuel Pressure','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','di3line_power','DI3/Line Power','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','di4gen_pwer','DI4/GEN Power','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','modem_dcd','Modem DCD','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','modem_enabled','Modem Enabled','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','gen_overspeed','GEN Overspeed','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','huio1_cfg12','HUIO 1 CFG 12','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','huio1_cfg13','HUIO 1 CFG 13','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','huio1_cfg14','HUIO 1 CFG 14','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','huio1_cfg15','HUIO 1 CFG 15','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','huio2_cfg16','HUIO 2 CFG 16','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','common_alarm','Common Alarm','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','common_warning','Common Warning','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','alarm_enabled','Alarm Enabled','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','ready_for_load','Ready For Load','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','gen_ready_to_run','GEN Ready to Run','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','gen_stopped_alarm','GEN Stopped Alarm','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','gen_stopped','Gen Stopped','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','gen_in_maunal','Gen In Manual','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','gen_in_auto','GEN In Auto','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','gen_in_off','GEN In OFF','INTEGER','0','1','',1);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','overcrank_alarm','OverCrank Alarm','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','oil_inhibit_alarm','Oil Inhibit Alarm','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','annunc_spr_light','Annunc SPR Light','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','oil_temp_hi_alarm','Oil Temperature Hi Alarm','INTEGER','0','1','',0);
REPLACE INTO css_networking_device_prop_def (device_type_id,device_class_id,variable_name, name, data_type,editable,prop_type_id,tooltip,visible) VALUES ('9','1156','oil_temp_low_alarm','Oil Temperature Low Alarm','INTEGER','0','1','',0);

-- END


-- START
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'0','Stop'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'Generator Status'
 ) 
 AND device_type_id in ('9');
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'1','Start'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'Generator Status'
 )
 AND device_type_id in ('9');
-- END

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'0','Propane'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'gen_fuel_type'
 ) 
 AND device_type_id in ('9');
 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'1','Natural Gas'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'gen_fuel_type'
 )
 AND device_type_id in ('9');
 
 REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'2','Diesel'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'gen_fuel_type'
 )
 AND device_type_id in ('9');


-- start
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '1','01' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '2','02' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '3','03' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '4','04' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '5','05' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '6','06' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '7','07' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '8','08' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '9','09' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '10','10' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '11','11' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '12','12' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '13','13' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '14','14' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '15','15' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '16','16' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '17','17' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '18','18' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '19','19' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '20','20' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '21','21' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '22','22' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '23','23' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '24','24' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '25','25' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '26','26' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '27','27' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '28','28' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '29','29' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '30','30' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '31','31' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '32','32' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '33','33' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '34','34' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '35','35' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '36','36' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '37','37' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '38','38' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '39','39' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '40','40' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '41','41' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '42','42' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '43','43' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '44','44' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '45','45' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '46','46' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '47','47' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '48','48' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '49','49' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '50','50' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '51','51' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '52','52' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '53','53' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '54','54' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '55','55' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '56','56' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '57','57' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '58','58' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '59','59' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) 
SELECT id, '60','60' FROM css_networking_device_prop_def
WHERE variable_name in('setRunTime') AND device_type_id IN ('9');
-- end

-- start









 REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Control','Control');
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('setRunTime','9','Control'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('Generator Status','9','Control'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_fuel_type','9','Control'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('remote_start','9','Control');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('volume_tankvolume','9','Control');
  
     REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Device Parameters','Device Parameters');
 REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_in_off','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_in_auto','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_in_maunal','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_stopped','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_ready_to_run','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('ready_for_load','9','Device Parameters'); 
      REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('auto_switch','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('manual_switch','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('emergency_stop','9','Device Parameters');
   REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('huio1_cfg12','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('huio1_cfg13','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('huio1_cfg14','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('huio1_cfg15','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('huio2_cfg16','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('user_cfg05','9','Device Parameters'); 
      REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('user_cfg06','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('user_cfg09','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('modem_enabled','9','Device Parameters');
    REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('di1user_Cfg05','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('di2fuel_pressure','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('di3line_power','9','Device Parameters'); 
      REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('di4gen_pwer','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('gen_overspeed','9','Device Parameters');
       REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('annunc_spr_light','9','Device Parameters'); 
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('modem_dcd','9','Device Parameters');
  REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('original_name','9','Device Parameters'); 
  
  
  REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Parameters','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('oil_temperature','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('oil_pressure','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('coolant_temperature','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('coolant_level','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('volume_percentlevel','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('fuelPressure','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('throt_pos','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('o2_sensor','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('user_cfg09','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('battery_voltage','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('current_phsa','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('current_phsb','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('current_phsc','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('avrg_current','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('volt_phsa','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('volt_phsb','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('volt_phsc','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('avrg_voltage','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_power','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('total_pf','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('gen_frequency','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('engine_rpm','9','Parameters');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('af_duty_cycle','9','Parameters');

REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('generator_running','9','Parameters'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('Generator Status','9','Parameters'); 
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('generator_running_state','9','Parameters'); 
-- end

 UPDATE css_networking_device_type SET uses_default_value=0 WHERE id=9;-- Bug 8075 Phoenix CellMetrix - Phoenix Trap Handler - Any trap will clear all raised alarms - Mike Zhukovskiy 7/21/2106 
REPLACE INTO css_networking_device_prop_def (id, prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id,  secure, graph_type, thresh_enable, tooltip, valuetip)  VALUES (NULL, 2, 1104, 5030, 1, 0, 'PBT-ENTITYSTATUS-MIB::pbtEntityComStatus.1', 'pbtEntityComStatus', 'Entity Communication Status', 'STRING', 0, 1, 0, 0, NULL, 0, NULL, 4, 0, 0, 1, 'This object describes the status of the communications with the specified entity.', NULL);
REPLACE INTO css_networking_device_prop_def (id, prop_type_id, device_class_id, device_type_id, prop_group_id, use_snmp, snmp_oid, variable_name, name, data_type, editable, visible, internal, min, min_val, max, max_val, severity_id,  secure, graph_type, thresh_enable, tooltip, valuetip)  VALUES (NULL, 2, 1104, 5030, 1, 0, 'PBT-ENTITYSTATUS-MIB::pbtEntityCompositeStatus.1', 'pbtEntityCompositeStatus', 'Entity Composite Status', 'STRING', 0, 1, 0, 0, NULL, 0, NULL, 4, 0, 0, 1, 'This object describes the status of the composite alarm of the specified entity.  This status is typically indicated by a green/red LED on the equipment monitored.', NULL);

REPLACE INTO def_status_groups_map  VALUES ('pbtEntityCompositeStatus', 5030, 'Device Info');
REPLACE INTO def_status_groups_map  VALUES ('pbtEntityComStatus', 5030, 'Device Info');
-- End 8075-- auth: Nicole Gager
-- Bug 8342
-- make sure id column is auto-incremented to avoid a duplicate key violation

ALTER TABLE css_ticketing_escalation_policies
MODIFY COLUMN id INT NOT NULL AUTO_INCREMENT;-- Bug 8608 -- Silas Baker
-- TEKO TSPV - MOTRX Receiver Fiber Loss should be a Status instead of a property

Replace into def_status_groups_map (status_def_variable_name, device_type_id, group_var_name) Values ('spvRuFiberLoss', '2010', 'Reciever');	Replace into def_status_groups (group_var_name, group_breadCrumb) Values ('Reciever', 'Reciever');	Update css_networking_device_prop_def set name = 'Fiber Loss', prop_type_id = 2 where device_type_id = 2010 and variable_name = 'spvRuFiberLoss';
Replace into def_status_groups_map (status_def_variable_name, device_type_id, group_var_name) Values ('spvMOTRXFiberLossCh1', '1167', 'Reciever');	Replace into def_status_groups (group_var_name, group_breadCrumb) Values ('Reciever', 'Reciever');	Update css_networking_device_prop_def set name = 'Master Unit Optical Transceiver Fiber Loss Channel 1', prop_type_id = 2 where device_type_id = 1167 and variable_name = 'spvMOTRXFiberLossCh1';
Replace into def_status_groups_map (status_def_variable_name, device_type_id, group_var_name) Values ('spvMOTRXFiberLossCh2', '1167', 'Reciever');	Replace into def_status_groups (group_var_name, group_breadCrumb) Values ('Reciever', 'Reciever');	Update css_networking_device_prop_def set name = 'Master Unit Optical Transceiver Fiber Loss Channel 2', prop_type_id = 2 where device_type_id = 1167 and variable_name = 'spvMOTRXFiberLossCh2';
Replace into def_status_groups_map (status_def_variable_name, device_type_id, group_var_name) Values ('spvMOTRXFiberLossCh3', '1167', 'Reciever');	Replace into def_status_groups (group_var_name, group_breadCrumb) Values ('Reciever', 'Reciever');	Update css_networking_device_prop_def set name = 'Master Unit Optical Transceiver Fiber Loss Channel 3', prop_type_id = 2 where device_type_id = 1167 and variable_name = 'spvMOTRXFiberLossCh3';
Replace into def_status_groups_map (status_def_variable_name, device_type_id, group_var_name) Values ('spvMOTRXFiberLossCh4', '1167', 'Reciever');	Replace into def_status_groups (group_var_name, group_breadCrumb) Values ('Reciever', 'Reciever');	Update css_networking_device_prop_def set name = 'Master Unit Optical Transceiver Fiber Loss Channel 4', prop_type_id = 2 where device_type_id = 1167 and variable_name = 'spvMOTRXFiberLossCh4';
Replace into def_status_groups_map (status_def_variable_name, device_type_id, group_var_name) Values ('spvMotrxFiberLoss', '1991', 'Reciever');	Replace into def_status_groups (group_var_name, group_breadCrumb) Values ('Reciever', 'Reciever');	Update css_networking_device_prop_def set name = 'Motrx Fiber Loss', prop_type_id = 2 where device_type_id = 1991 and variable_name = 'spvMotrxFiberLoss';
Replace into def_status_groups_map (status_def_variable_name, device_type_id, group_var_name) Values ('spvRuFiberLoss', '1204', 'Reciever');	Replace into def_status_groups (group_var_name, group_breadCrumb) Values ('Reciever', 'Reciever');	Update css_networking_device_prop_def set name = 'Remote Unit Fiber Loss', prop_type_id = 2 where device_type_id = 1204 and variable_name = 'spvRuFiberLoss';
-- ---------------------------------------------------------------------------------------------------------------------------------------------
-- Bug 7204 - User Management Report - Invalid login durations: Filtering out bad records with out logout times which cause negative durations.
-- Notes: This is a band-aid for now.  A report redesign will occur in 'Unified'.
-- ---------------------------------------------------------------------------------------------------------------------------------------------
DELIMITER ;

DROP PROCEDURE IF EXISTS UserOverView;

DELIMITER ;;

CREATE DEFINER = 'root'@'%' PROCEDURE UserOverView(IN csv_file_name_in TEXT(0), IN user_list TEXT(0), IN time_in TEXT(0))
BEGIN
SET @fileName = csv_file_name_in;
SET @userlist = user_list;
SET @timeString = time_in;

SET NAMES utf8;

SET @mysql = CONCAT("SELECT 
a.firstName,
a.lastName,
a.userName,
a.Role,
a.Status,
a.homeNodeName,
IFNULL(b.Num_Logins,'Did not login...') AS Num_Logins,
IFNULL(b.total_time,'NA') AS total_time

INTO OUTFILE ","'",
@fileName,"'",
" FIELDS TERMINATED BY ';'",
" ENCLOSED BY ''",
" ESCAPED BY ''",
" LINES TERMINATED BY '\r\n' ",

"FROM (
SELECT 
au.id AS userid,
au.first_name AS firstName,
au.last_name AS lastName,
au.username AS userName,
au.role AS Role,
IF(au.active = 1,'active','inactive') as 'Status',
ng.name AS homeNodeName

FROM  css_authentication_user au
INNER JOIN css_networking_network_tree nt ON (au.home_node_id = nt.id)
INNER JOIN css_networking_group ng ON (nt.group_id = ng.id)
WHERE au.id IN (",@userlist,")
ORDER BY au.id) a

LEFT JOIN(
SELECT 
sl.user_id AS userid,
COUNT(*) AS Num_Logins,
SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(sl.date_logged_out,sl.date_logged_in)))) AS total_time
FROM css_sessions_log sl
WHERE sl.user_id IN (",@userlist,")  AND sl.date_logged_out <> '0000-00-00 00:00:00' "
,@timeString,
" GROUP BY sl.user_id
ORDER BY sl.user_id) b ON a.userid = b.userid"
);

PREPARE stmt
FROM @mysql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

END;;

DELIMITER ;

DROP PROCEDURE IF EXISTS GetDailySumUserSessionData;

DELIMITER ;;

CREATE DEFINER = 'root'@'%' PROCEDURE GetDailySumUserSessionData(IN csv_file_name_in TEXT(0), IN user_list TEXT(0), IN time_in TEXT(0))
BEGIN
SET @fileName = csv_file_name_in;
SET @userlist = user_list;
SET @timeString = time_in;

SET NAMES utf8;

SET @mysql = CONCAT("SELECT 
DATE_FORMAT(sl.date_logged_in,'%Y-%m-%d'),
au.first_name as firstName,
au.last_name as lastName,
au.username as userName,
au.role as Role,            
ng.name as homeNodeName,
COUNT(*) AS Num_Logins,
SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(sl.date_logged_out,sl.date_logged_in))))

INTO OUTFILE ","'",
@fileName,"'",
" FIELDS TERMINATED BY ';'",
" ENCLOSED BY ''",
" ESCAPED BY ''",
" LINES TERMINATED BY '\r\n' ",

"FROM css_sessions_log sl
INNER JOIN css_authentication_user au ON (sl.user_id = au.id)
INNER JOIN css_networking_network_tree nt ON (au.home_node_id = nt.id)
INNER JOIN css_networking_group ng ON (nt.group_id = ng.id)
WHERE au.id IN (",@userlist,") AND sl.date_logged_out <> '0000-00-00 00:00:00' "
,@timeString,
"GROUP BY sl.user_id, DATE_FORMAT(sl.date_logged_in,'%Y-%c-%d')
ORDER BY sl.date_logged_in;"
);

PREPARE stmt
FROM @mysql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

END;;

DELIMITER ;

DROP PROCEDURE IF EXISTS GetRawUserSessionData;

DELIMITER ;;

CREATE DEFINER = 'root'@'%' PROCEDURE GetRawUserSessionData(IN csv_file_name_in TEXT(0), IN user_list TEXT(0), IN time_in TEXT(0))
BEGIN
SET @fileName = csv_file_name_in;
SET @userlist = user_list;
SET @timeString = time_in;

SET NAMES utf8;

SET @mysql = CONCAT("SELECT 
au.first_name as firstName,
au.last_name as lastName,
au.username as userName,
au.role as Role,
sl.date_logged_in as date_loggedin,
sl.date_logged_out as date_loggedout,
TIMEDIFF(sl.date_logged_out,sl.date_logged_in) AS Timediff,            
ng.name as homeNodeName

INTO OUTFILE ","'",
@fileName,"'",
" FIELDS TERMINATED BY ';'",
" ENCLOSED BY ''",
" ESCAPED BY ''",
" LINES TERMINATED BY '\r\n' ",

"FROM css_sessions_log sl
INNER JOIN css_authentication_user au ON (sl.user_id = au.id)
INNER JOIN css_networking_network_tree nt ON (au.home_node_id = nt.id)
INNER JOIN css_networking_group ng ON (nt.group_id = ng.id)
WHERE au.id IN (",@userlist,") AND sl.date_logged_out <> '0000-00-00 00:00:00' "
,@timeString,
"ORDER BY sl.date_logged_in;"
);

PREPARE stmt
FROM @mysql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

END;;

DELIMITER ;

-- ---------------------------------------------------------------------------------------------------------------------------------------------
-- End of Bug 7204 - User Management Report - Invalid login durations
-- ----------------------------------------------------------------------------------------------------------------------------------------------- Bug 8492 - MultiTech Conduit - adding scan file to the device  - Ranveer Kushwaha 08/09/2016

UPDATE css_networking_device_type 
SET scan_file = 'multiConnectConduitScannerLauncher.php' 
WHERE id = 6006;-- Bugs 4834  Reporting - WiFi Gameday Report - New report tab for vendor wifi access unique clients - Mike Zhukovskiy 8/7/2016 
UPDATE css_networking_report_types
SET help_text = 'This report will produce an Excel spreadsheet containing the following reports from the selected Cisco Prime:  - C2 Busiest AP GameDay  - C2 Busiest Clients GameDay  - C2 Client Count GameDay  - C2 Client Sessions GameDay  - C2 Client Traffic GameDay  - C2 Executive Summary  - C2 Unique Clients Detail GameDay  - C2 Gameday UniqueClientSummary AllSSIDs - C2 Gameday UniqueClientSummary CitiFieldWiFi - C2 Gameday UniqueClientSummary VerizonWiFiAccess'
WHERE report_type = 'Gameday Reports';
-- End Bug 4834-- Author: Tareq AlWrekat
-- Bug: 7791
-- Description: SHJ Prop Defs, Prop Opts, and Prop Groups

-- START
SET foreign_key_checks = 0;
REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, can_add_children, main_device,build_file,scan_file,controller_file)
VALUES
(1616,1074,"SHJ","RTU",1,0,0,1,1,"shj_builder_launcher.php","shj_scanner_launcher.php","shj_controller.php");

REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
VALUES
(1612,2,"SHJ","S5138",0,0,0,"shj_controller.php");

REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
VALUES
(1613,2,"SHJ","S5302",0,0,0,"shj_controller.php");

REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
VALUES
(1614,2,"SHJ","S3308",0,0,0,"shj_controller.php");

REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
VALUES
(1615,2,"SHJ","S5140",0,0,0,"shj_controller.php");

REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
VALUES
(1617,2,"SHJ","Contact Closures",0,0,0,"shj_controller.php");

REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
VALUES
(1618,2,"SHJ","Analog",0,0,0,"shj_controller.php");

REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
VALUES
(1619,2,"SHJ","Relays",0,0,0,"shj_controller.php");

REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
VALUES
(1620,1083,"SHJ","Contact Closure",0,0,0,"shj_controller.php");

REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
VALUES
(1621,1082,"SHJ","Relay",0,0,0,"shj_controller.php");

REPLACE INTO css_networking_device_type
(id,class_id,vendor,model,auto_build_enabled, uses_snmp, snmp_only, controller_file)
VALUES
(1622,1084,"SHJ","Analog Input",0,0,0,"shj_controller.php");
-- END
SET foreign_key_checks = 1;

-- START
INSERT IGNORE INTO css_networking_device_port_def(device_type_id,variable_name,name,default_port)VALUES(1616,'telnet','MODBUS',502);
-- END

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,thresh_enable) VALUES ('1612','','firmware','Firmware','STRING','0','1','S5138 Firmware','0');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,thresh_enable) VALUES ('1612','','hardwareVer','Hardware Version','STRING','0','1','Hardware Version','0');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,thresh_enable) VALUES ('1612','','model','Model','STRING','0','1','Model','0');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,thresh_enable) VALUES ('1612','','serialnumber','Serial Number','STRING','0','1','Serial Number','0');

REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,thresh_enable) VALUES ('1620','','DI Register Description','Alarm Description','STRING','1','1','Description of the alarm for this register','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,thresh_enable) VALUES ('1620','','DI Register Severity','Alarm Severity','INTEGER','1','1','Severity of the alarm for this register','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,thresh_enable) VALUES ('1620','','DI Register Enable/Disable','Enable/Disable Alarm','INTEGER','1','1','Enable/Disable the alarm','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,thresh_enable) VALUES ('1620','','DI Register Status','Status','INTEGER','0','2','Status','0');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,thresh_enable) VALUES ('1620','','DI Register Normal State','Normal State','INTEGER','1','1','Normal State for this variable','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,thresh_enable) VALUES ('1620','','DI Register Alarm Alias','Alarm Alias','STRING','1','1','Alarm Alias of the alarm for this register','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,thresh_enable) VALUES ('1620','','DI Register Normal Alias','Normal Alias','STRING','1','1','Normal Alias of the alarm for this register','1');
REPLACE INTO css_networking_device_prop_def (device_type_id,snmp_oid,variable_name, name, data_type,editable,prop_type_id,tooltip,thresh_enable) VALUES ('1620','','DI Register Counter','Alarm Counter','INTEGER','0','2','Alarm Counter','0');

-- start
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'0','Disabled'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'DI Register Enable/Disable'
 ) 
 AND device_type_id in ('1620');
 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'1','Enabled'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'DI Register Enable/Disable'
 )
 AND device_type_id in ('1620');
 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'0','Open'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
'DO Register Status'
 ) 
 AND device_type_id in ('1620');
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'1','Close'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
'DO Register Status'
 )
 AND device_type_id in ('1620');
 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'0','Normally Closed'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
  'DI Register Normal State'
 ) AND device_type_id in ('1620');
 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'1','Normally Open'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
  'DI Register Normal State'
) AND device_type_id in ('1620');
 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'1','Critical'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'DI Register Severity'
 ) 
 AND device_type_id in ('1620');
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'2','Major'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'DI Register Severity'
 )
 AND device_type_id in ('1620');
 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'3','Minor'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'DI Register Severity'
 )
 AND device_type_id in ('1620');
 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'4','Warning'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'DI Register Severity'
 )
 AND device_type_id in ('1620');
 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text)
 SELECT id,'6','Information'
 FROM css_networking_device_prop_def
 WHERE variable_name in (
 'DI Register Severity'
 )
 AND device_type_id in ('1620');
 
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Device Information','Device Information');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('firmware','1612','Device Information');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('hardwareVer','1612','Device Information');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('model','1612','Device Information');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('serialnumber','1612','Device Information');
	
REPLACE INTO def_prop_groups (group_var_name,group_breadCrumb) VALUES ('Digital Input Alarm','Digital Input Alarm');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('DI Register Description','1620','Digital Input Alarm');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('DI Register Severity','1620','Digital Input Alarm');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('DI Register Enable/Disable','1620','Digital Input Alarm');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('DI Register Normal State','1620','Digital Input Alarm');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('DI Register Alarm Alias','1620','Digital Input Alarm');
REPLACE INTO def_prop_groups_map (prop_def_variable_name,device_type_id,group_var_name) VALUES ('DI Register Normal Alias','1620','Digital Input Alarm');

REPLACE INTO def_status_groups (group_var_name,group_breadCrumb) VALUES ('Digital Input Alarm','Digital Input Alarm');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('DI Register Counter','1620','Digital Input Alarm');
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name) VALUES ('DI Register Status','1620','Digital Input Alarm');
-- Author: Jay Stoffle
-- Bug: 7791
-- Description: SHJ Prop Defs, Prop Opts, and Prop Groups

-- insert/update new props
INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1620,'DI Register Alarm Alias','Alarm Alias','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1620, name = 'Alarm Alias', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (2,1620,'DI Register Counter','Alarm Counter','INTEGER',0,1,0)
ON DUPLICATE KEY UPDATE prop_type_id = 2, device_type_id = 1620, name = 'Alarm Counter', data_type = 'INTEGER', editable = 0, visible = 1, thresh_enable = 0;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1620,'DI Register Description','Alarm Description','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1620, name = 'Alarm Description', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1620,'DI Register Enable/Disable','Alarm Enable/Disable','INTEGER',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1620, name = 'Alarm Enable/Disable', data_type = 'INTEGER', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1620,'DI Register Normal Alias','Normal Alias','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1620, name = 'Normal Alias', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1620,'DI Register Normal State','Normal State','INTEGER',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1620, name = 'Normal State', data_type = 'INTEGER', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1620,'DI Register Severity','Alarm Severity','INTEGER',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1620, name = 'Alarm Severity', data_type = 'INTEGER', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (2,1620,'DI Register Status','Status','INTEGER',0,1,0)
ON DUPLICATE KEY UPDATE prop_type_id = 2, device_type_id = 1620, name = 'Status', data_type = 'INTEGER', editable = 0, visible = 1, thresh_enable = 0;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (2,1620,'DI Value','Value','STRING',0,1,0)
ON DUPLICATE KEY UPDATE prop_type_id = 2, device_type_id = 1620, name = 'Value', data_type = 'STRING', editable = 0, visible = 1, thresh_enable = 0;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1621,'RE Register Alarm Alias','Alarm Alias','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1621, name = 'Alarm Alias', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (2,1621,'RE Register Counter','Alarm Counter','DECIMAL',0,1,0)
ON DUPLICATE KEY UPDATE prop_type_id = 2, device_type_id = 1621, name = 'Alarm Counter', data_type = 'DECIMAL', editable = 0, visible = 1, thresh_enable = 0;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1621,'RE Register Description','Description','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1621, name = 'Description', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1621,'RE Register Enable/Disable','Alarm Enable/Disable','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1621, name = 'Alarm Enable/Disable', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1621,'RE Register Normal Alias','Normal Alias','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1621, name = 'Normal Alias', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1621,'RE Register Normal State','Normal State','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1621, name = 'Normal State', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1621,'RE Register Severity','Alarm Severity','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1621, name = 'Alarm Severity', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (2,1621,'RE Register Status','Status','INTEGER',0,1,0)
ON DUPLICATE KEY UPDATE prop_type_id = 2, device_type_id = 1621, name = 'Status', data_type = 'INTEGER', editable = 0, visible = 1, thresh_enable = 0;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1621,'RE Value','Value','DECIMAL',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1621, name = 'Value', data_type = 'DECIMAL', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1622,'AI Register Alarm Alias','Alarm Alias','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1622, name = 'Alarm Alias', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1622,'AI Register Deadband','Alarm Deadband','DECIMAL',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1622, name = 'Alarm Deadband', data_type = 'DECIMAL', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1622,'AI Register Description','Description','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1622, name = 'Description', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1622,'AI Register Enable/Disable','Alarm Enable/Disable','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1622, name = 'Alarm Enable/Disable', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1622,'AI Register High Threshold','Alarm High Threshold','DECIMAL',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1622, name = 'Alarm High Threshold', data_type = 'DECIMAL', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1622,'AI Register Low Threshold','Alarm Low Threshold','DECIMAL',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1622, name = 'Alarm Low Threshold', data_type = 'DECIMAL', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1622,'AI Register Normal Alias','Normal Alias','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1622, name = 'Normal Alias', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1622,'AI Register Severity','Alarm Severity','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1622, name = 'Alarm Severity', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1622,'AI Register Very High Threshold','Alarm Very High Threshold','DECIMAL',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1622, name = 'Alarm Very High Threshold', data_type = 'DECIMAL', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1622,'AI Register Very Low Threshold','Alarm Very Low Threshold','DECIMAL',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1622, name = 'Alarm Very Low Threshold', data_type = 'DECIMAL', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (1,1622,'AI Units','Units','STRING',1,1,1)
ON DUPLICATE KEY UPDATE prop_type_id = 1, device_type_id = 1622, name = 'Units', data_type = 'STRING', editable = 1, visible = 1, thresh_enable = 1;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (2,1622,'AI Value','Value','DECIMAL',0,1,0)
ON DUPLICATE KEY UPDATE prop_type_id = 2, device_type_id = 1622, name = 'Value', data_type = 'DECIMAL', editable = 0, visible = 1, thresh_enable = 0;

INSERT INTO css_networking_device_prop_def (prop_type_id, device_type_id, variable_name, name, data_type, editable, visible, thresh_enable)
VALUES (2,1622,'AI Register Status','Status','STRING',0,1,0)
ON DUPLICATE KEY UPDATE prop_type_id = 2, device_type_id = 1622, name = 'Status', data_type = 'STRING', editable = 0, visible = 1, thresh_enable = 0;

-- insert/update new prop opts
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'1','Critical' FROM css_networking_device_prop_def WHERE (variable_name = 'RE Register Severity' AND device_type_id = 1621) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'2','Major' FROM css_networking_device_prop_def WHERE (variable_name = 'RE Register Severity' AND device_type_id = 1621) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'3','Minor' FROM css_networking_device_prop_def WHERE (variable_name = 'RE Register Severity' AND device_type_id = 1621) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'4','Warning' FROM css_networking_device_prop_def WHERE (variable_name = 'RE Register Severity' AND device_type_id = 1621) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'5','Information' FROM css_networking_device_prop_def WHERE (variable_name = 'RE Register Severity' AND device_type_id = 1621) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'Enabled','Enabled' FROM css_networking_device_prop_def WHERE (variable_name = 'RE Register Enable/Disable' AND device_type_id = 1621) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'Disabled','Disabled' FROM css_networking_device_prop_def WHERE (variable_name = 'RE Register Enable/Disable' AND device_type_id = 1621) ); 

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'1','Critical' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Register Severity' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'2','Major' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Register Severity' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'3','Minor' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Register Severity' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'4','Warning' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Register Severity' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'5','Information' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Register Severity' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'Enabled','Enabled' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Register Enable/Disable' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'Disabled','Disabled' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Register Enable/Disable' AND device_type_id = 1622) ); 

REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'Int','Integer' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Units' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'V5','Voltage (V5)' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Units' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'V10','Voltage (V10)' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Units' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'mA','Amperage (mA)' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Units' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'On/Off','On/Off' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Units' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'Off/On','Off/On' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Units' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'C','Celcius (C)' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Units' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'F','Fahrenheit (F)' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Units' AND device_type_id = 1622) ); 
REPLACE INTO css_networking_device_prop_opts (prop_def_id,value,text) ( SELECT id,'%','Percent (%)' FROM css_networking_device_prop_def WHERE (variable_name = 'AI Units' AND device_type_id = 1622) ); 

-- insert/update new prop groups
REPLACE INTO def_prop_groups (group_var_name, group_breadCrumb) VALUES ('Relay Alarm', 'Relay Alarm');
REPLACE INTO def_status_groups (group_var_name, group_breadCrumb) VALUES ('Relay Alarm','Relay Alarm');
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Relay Alarm', 'RE Register Enable/Disable', 1621);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Relay Alarm', 'RE Register Normal Alias', 1621);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Relay Alarm', 'RE Register Alarm Alias', 1621);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Relay Alarm', 'RE Register Normal State', 1621);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Relay Alarm', 'RE Register Severity', 1621);
REPLACE INTO def_status_groups_map (group_var_name, status_def_variable_name, device_type_id) VALUES ('Relay Alarm', 'RE Register Counter', 1621);
REPLACE INTO def_status_groups_map (group_var_name, status_def_variable_name, device_type_id) VALUES ('Relay Alarm', 'RE Register Status', 1621);

REPLACE INTO def_prop_groups (group_var_name, group_breadCrumb) VALUES ('Analog Input Alarm', 'Analog Input Alarm');
REPLACE INTO def_status_groups (group_var_name, group_breadCrumb) VALUES ('Analog Input Alarm','Analog Input Alarm');
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Analog Input Alarm', 'AI Register Enable/Disable', 1622);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Analog Input Alarm', 'AI Register Normal Alias', 1622);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Analog Input Alarm', 'AI Register Alarm Alias', 1622);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Analog Input Alarm', 'AI Register Normal State', 1622);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Analog Input Alarm', 'AI Register Severity', 1622);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Analog Input Alarm', 'AI Register Counter', 1622);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Analog Input Alarm', 'AI Register Deadband', 1622);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Analog Input Alarm', 'AI Register Very Low Threshold', 1622);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Analog Input Alarm', 'AI Register Low Threshold', 1622);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Analog Input Alarm', 'AI Register High Threshold', 1622);
REPLACE INTO def_prop_groups_map (group_var_name, prop_def_variable_name, device_type_id) VALUES ('Analog Input Alarm', 'AI Register Very High Threshold', 1622);
REPLACE INTO def_status_groups_map (group_var_name, status_def_variable_name, device_type_id) VALUES ('Analog Input Alarm', 'AI Register Status', 1622);
-- auth: Ranveer Kushwaha
-- Bug 8492
-- Adding property defination, adding new device type, port def, and grouping properties and status for new device integration  MultiTech Conduit 3G & 4G Modem - QoS Device Driver


REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'carrier', 'Carrier', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:51', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'code', 'Code', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:51', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'firmware', 'Firmware', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-19 17:30:33', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'hardware', 'Hardware', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:51', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'iccid', 'Integrated Circuit Card Id', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'imei', 'International Mobile Station Equipment Identity', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'imsi', 'International Mobile Subscriber Identity', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'manufacturer', 'Radio Manufacturer', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'mdn', 'Mobile Directory Number', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'model', 'Model', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'msid', 'Mobil Station ID', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'original_name', 'Original Name', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-19 17:30:33', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'Sequence_Number', 'Sequence_Number', 'DECIMAL', '0', '0', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:51', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6006', '1', '0', NULL, 'type', 'Type', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'avgDownload', 'Avg. Download', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'avgTime', 'Avg. Time', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'avgUpload', 'Avg. Upload', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'channel', 'Channel', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6007', '1', '0', NULL, 'cid', 'Cellular ID (Tower) in HEX', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'currentSpeed', 'Current Speed', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'datetime', 'Date Time', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'error', 'Error', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-25 10:22:34', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6007', '1', '0', NULL, 'imsi', 'International Mobile Subscriber Identity', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6007', '1', '0', NULL, 'lac', 'Location Area Code in HEX', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'lastBand', 'Last Band', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'maxTime', 'Max Time', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6007', '1', '0', NULL, 'mcc', 'Country Code', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'mdevTime', 'Standard Deviation From Mean Time (MDEV Time)', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'minTime', 'Min. Time', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'mm', 'Mobility Management State', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6007', '1', '0', NULL, 'mnc', 'Operator Code', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6007', '1', '0', NULL, 'network', 'Network', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6007', '1', '0', NULL, 'nom', 'Network Operator Mode', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'pktRx', 'Packet Rx', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'pktTx', 'Packet Tx', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6007', '1', '0', NULL, 'rac', 'Routing Area Code in HEX', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'receivedPercent', 'Received Percent', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'receivedSize', 'Received Size', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6007', '1', '0', NULL, 'roaming', 'Roaming', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'rr', 'Radio Resource State', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'rssi', 'Signal Strength', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'rssidBm', 'Signal Strength in dBm', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6007', '1', '0', NULL, 'sd', 'Service Domain', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '1', NULL, '6007', '1', '0', NULL, 'service', 'Service', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'status', 'Status', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'timeLeft', 'Time Left', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'timeSpent', 'Time Spent', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'timeTotal', 'Time Total', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'totalPercent', 'Total Percent', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'totalSize', 'Total Size', 'STRING', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'txpwr', 'Tx Power', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'xferdPercent', 'Transferred Percent', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');
REPLACE INTO `css_networking_device_prop_def` VALUES (NULL, '2', NULL, '6007', '1', '0', NULL, 'xferdSize', 'Transferred Size', 'DECIMAL', '0', '1', '0', '0', NULL, '0', NULL, '4', '4', '0', '2016-07-22 12:54:52', '0', '1', NULL, NULL, '0');



REPLACE INTO `css_networking_device_type` VALUES (6006, '5', 'MultiTech', 'MultiConnect Conduit', '1', '1', '0', '1', '0', NULL, NULL, NULL, '2c', '8bf79860eef9f6beea23994dcfa890aa3c6ade7fe5d8ab4bafb295ccb918b43b', 'e8142df3c5713e83cb98ddda28f174ea0dbc0c586962ebb913bf6ce158b26c0c', '2016-07-19 16:58:03', '1', '0', NULL, '1', 'multiConnectConduitBuilderLauncher.php', '', NULL, NULL, NULL, NULL, 'SHA', NULL, 'AES', 'authPriv', NULL, '0', '1', NULL, NULL, '0', '0', 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Ignored Alarms,Location and Access Info,Log,Map,Notes,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', '0', '0', '0', NULL);

REPLACE INTO `css_networking_device_type` VALUES (6007, '2', 'MultiTech', 'Band', '0', '1', '0', '0', '0', NULL, NULL, NULL, '2c', '8bf79860eef9f6beea23994dcfa890aa3c6ade7fe5d8ab4bafb295ccb918b43b', 'e8142df3c5713e83cb98ddda28f174ea0dbc0c586962ebb913bf6ce158b26c0c', '2016-07-19 16:54:09', '0', '0', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, 'SHA', NULL, 'AES', 'authPriv', NULL, '0', '1', NULL, NULL, '0', '0', 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,Generator Details View,Graph,Ignored Alarms,Location and Access Info,Log,Map,Notes,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera', '0', '0', '0', NULL);


REPLACE INTO css_networking_device_port_def VALUES (null, 6006, 'http', 'HTTP', 80, null);
REPLACE INTO css_networking_device_port_def VALUES (null, 6006, 'c2socket', 'C2Socket', 44556, null);


REPLACE INTO def_prop_groups_map  VALUES ('carrier', 6006, 'Service');
REPLACE INTO def_prop_groups_map  VALUES ('code', 6006, 'Firmware');
REPLACE INTO def_prop_groups_map  VALUES ('firmware', 6006, 'Firmware');
REPLACE INTO def_prop_groups_map  VALUES ('hardware', 6006, 'Hardware ');
REPLACE INTO def_prop_groups_map  VALUES ('iccid', 6006, 'Device Identification');
REPLACE INTO def_prop_groups_map  VALUES ('imei', 6006, 'Device Identification');
REPLACE INTO def_prop_groups_map  VALUES ('imsi', 6006, 'Device Identification');
REPLACE INTO def_prop_groups_map  VALUES ('manufacturer', 6006, 'Hardware');
REPLACE INTO def_prop_groups_map  VALUES ('mdn', 6006, 'Identification Numbers');
REPLACE INTO def_prop_groups_map  VALUES ('model', 6006, 'Hardware ');
REPLACE INTO def_prop_groups_map  VALUES ('msid', 6006, 'Device Identification');
REPLACE INTO def_prop_groups_map  VALUES ('original_name', 6006, 'Device Identification');
REPLACE INTO def_prop_groups_map  VALUES ('type', 6006, 'Hardware ');

REPLACE INTO def_prop_groups_map  VALUES ('cid', 6007, 'Location');
REPLACE INTO def_prop_groups_map  VALUES ('lac', 6007, 'Location');
REPLACE INTO def_prop_groups_map  VALUES ('mcc', 6007, 'Location');
REPLACE INTO def_prop_groups_map  VALUES ('mnc', 6007, 'Location');
REPLACE INTO def_prop_groups_map  VALUES ('network', 6007, 'Service');
REPLACE INTO def_prop_groups_map  VALUES ('nom', 6007, 'Service');
REPLACE INTO def_prop_groups_map  VALUES ('rac', 6007, 'Location');
REPLACE INTO def_prop_groups_map  VALUES ('roaming', 6007, 'Service');
REPLACE INTO def_prop_groups_map  VALUES ('sd', 6007, 'Service');
REPLACE INTO def_prop_groups_map  VALUES ('service', 6007, 'Service');
REPLACE INTO def_prop_groups_map  VALUES ('imsi', 6007, 'Identification Numbers');

REPLACE INTO def_prop_groups VALUES ('Service', 'Service');
REPLACE INTO def_prop_groups VALUES ('Firmware', 'Firmware');
REPLACE INTO def_prop_groups VALUES ('Hardware', 'Hardware');
REPLACE INTO def_prop_groups VALUES ('Device Identification', 'Device Identification');
REPLACE INTO def_prop_groups VALUES ('Identification Numbers', 'Identification Numbers');
REPLACE INTO def_prop_groups VALUES ('Location', 'Location');



REPLACE INTO def_status_groups_map  VALUES ('avgDownload', 6007, 'Key Performance Indicators\File Transfer');
REPLACE INTO def_status_groups_map  VALUES ('avgTime', 6007, 'Key Performance Indicators\Time');
REPLACE INTO def_status_groups_map  VALUES ('avgUpload', 6007, 'Key Performance Indicators\File Transfer');
REPLACE INTO def_status_groups_map  VALUES ('channel', 6007, 'Key Performance Indicators\Radio Frequency');
REPLACE INTO def_status_groups_map  VALUES ('currentSpeed', 6007, 'Key Performance Indicators\File Transfer');
REPLACE INTO def_status_groups_map  VALUES ('datetime', 6007, 'General');
REPLACE INTO def_status_groups_map  VALUES ('error', 6007, 'General');
REPLACE INTO def_status_groups_map  VALUES ('lastBand', 6007, 'Key Performance Indicators\Radio Frequency');
REPLACE INTO def_status_groups_map  VALUES ('maxTime', 6007, 'Key Performance Indicators\Time');
REPLACE INTO def_status_groups_map  VALUES ('mdevTime', 6007, 'Key Performance Indicators\Time');
REPLACE INTO def_status_groups_map  VALUES ('minTime', 6007, 'Key Performance Indicators\Time');
REPLACE INTO def_status_groups_map  VALUES ('mm', 6007, 'Key Performance Indicators\Radio Frequency');
REPLACE INTO def_status_groups_map  VALUES ('pktRx', 6007, 'Key Performance Indicators\File Transfer');
REPLACE INTO def_status_groups_map  VALUES ('pktTx', 6007, 'Key Performance Indicators\File Transfer');
REPLACE INTO def_status_groups_map  VALUES ('receivedPercent', 6007, 'Key Performance Indicators\File Transfer');
REPLACE INTO def_status_groups_map  VALUES ('receivedSize', 6007, 'Key Performance Indicators\File Transfer');
REPLACE INTO def_status_groups_map  VALUES ('rr', 6007, 'Key Performance Indicators\Radio Frequency');
REPLACE INTO def_status_groups_map  VALUES ('rssi', 6007, 'Key Performance Indicators\Radio Frequency');
REPLACE INTO def_status_groups_map  VALUES ('rssidBm', 6007, 'Key Performance Indicators\Radio Frequency');
REPLACE INTO def_status_groups_map  VALUES ('status', 6007, 'General ');
REPLACE INTO def_status_groups_map  VALUES ('timeLeft', 6007, 'Key Performance Indicators\Time');
REPLACE INTO def_status_groups_map  VALUES ('timeSpent', 6007, 'Key Performance Indicators\Time');
REPLACE INTO def_status_groups_map  VALUES ('timeTotal', 6007, 'Key Performance Indicators\Time');
REPLACE INTO def_status_groups_map  VALUES ('totalPercent', 6007, 'Key Performance Indicators\File Transfer');
REPLACE INTO def_status_groups_map  VALUES ('totalSize', 6007, 'Key Performance Indicators\File Transfer');
REPLACE INTO def_status_groups_map  VALUES ('txpwr', 6007, 'Key Performance Indicators\Radio Frequency');
REPLACE INTO def_status_groups_map  VALUES ('xferdPercent', 6007, 'Key Performance Indicators\File Transfer');
REPLACE INTO def_status_groups_map  VALUES ('xferdSize', 6007, 'Key Performance Indicators\File Transfer');

REPLACE INTO def_status_groups VALUES ('Key Performance Indicators\File Transfer', 'Key Performance Indicators\File Transfer');
REPLACE INTO def_status_groups VALUES ('Key Performance Indicators\Time', 'Key Performance Indicators\Time');
REPLACE INTO def_status_groups VALUES ('General', 'General');
REPLACE INTO def_status_groups VALUES ('Key Performance Indicators\File Transfer', 'Key Performance Indicators\Radio Frequency');-- -------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Bug 8670 - Kentrox RMX3200 - HTTPS port is not built into SitePortal 
-- Author: LFF
-- Adding the missing port def to the definitions table.  Then update the exiting builds with the missing port.
-- -------------------------------------------------------------------------------------------------------------------------------------------------------------------

REPLACE INTO css_networking_device_port_def (device_type_id, variable_name, name, default_port) VALUES ('1293', 'https', 'HTTPS', '443');

INSERT INTO css_networking_device_port (port_def_id,device_id,port)
SELECT	dpd.id AS DPDID, d.id AS DID, dpd.default_port AS default_port
FROM css_networking_device d
INNER JOIN css_networking_device_port_def dpd ON dpd.variable_name = 'https' AND d.type_id = 1150
LEFT JOIN	css_networking_device_port dp ON d.id = dp.device_id AND dpd.id = dp.port_def_id
WHERE dp.port IS NULL;

-- -------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Bug 8670 - End of Script
-- -------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Bug 8428 - Patrick Aliberti
-- Updates any user that does not have password expiration disabled to have the correct setting as defined in password policy rules

UPDATE css_authentication_user SET pwd_never_expire = (
SELECT rule_values FROM css_authentication_password_policy_rules WHERE variable_name = 'expiry')
WHERE pwd_never_expire <> 0;-- Tristan Burgess - Bug 8629 - Generic Ping Props, updating status names to be more clear as to what they mean

UPDATE css_networking_device_prop_def
SET variable_name = "Round Trip Time (Max)", name = "Round Trip Time (Max)"
WHERE device_type_id = 1179 AND variable_name = "max";

UPDATE css_networking_device_prop_def
SET variable_name = "Round Trip Time (Min)", name = "Round Trip Time (Min)"
WHERE device_type_id = 1179 AND variable_name = "min";

UPDATE css_networking_device_prop_def
SET variable_name = "Round Trip Time (Average)", name = "Round Trip Time (Average)"
WHERE device_type_id = 1179 AND variable_name = "avg";

UPDATE css_networking_device_prop_def
SET variable_name = "Packet Loss (%)", name = "Packet Loss (%)"
WHERE device_type_id = 1179 AND variable_name = "loss";

UPDATE css_networking_device_prop_def
SET variable_name = "Total Transaction Time (s)", name = "Total Transaction Time (s)"
WHERE device_type_id = 1179 AND variable_name = "time";

-- Bug 8581 - Phoenix CellMetrix - Remote Agent Input statuses are in the properties canvas Mike Zhukovskiy 7/13/2016
UPDATE css_networking_device_prop_def
SET  prop_type_id = 2
WHERE variable_name IN ('pbtRaInputAnalogValue','pbtRaInputAnalogRawValue','pbtRaInputDigitalState') AND device_type_id = 5034;    
-- End Bug 8581 
-- Bug 8553 Phoenix CellMetrix - Connection Admittance (Mho) status not graphable Mike Zhukovskiy 7/12/2016
UPDATE css_networking_device_prop_def
SET  graph_type = 0
WHERE variable_name = 'pbtBatStringConnectionAdmittance' AND device_type_id = 1268;    
-- End Bug 8553 
-- Bug 8531 - Phoenix CellMetrix - Remote Agent Temperature is a property when it should be a status and has no units - Mike Zhukovskiy 7/10/16
UPDATE css_networking_device_prop_def
SET prop_type_id = 2,
    name = 'Remote Unit Temperature (C)'
WHERE variable_name='pbtRaUnitTemperature';

UPDATE css_networking_device_prop_def
SET prop_type_id = 2,
    name = 'Remote Unit Humidity (%)'
WHERE variable_name='pbtRaUnitHumidity';

UPDATE css_networking_device_prop_def
SET prop_type_id = 2,
    name = 'Remote Unit AC Voltage (VAC)'
WHERE variable_name='pbtRaUnitAcVoltage';

UPDATE css_networking_device_prop_def
SET prop_type_id = 2,
    name = 'Remote Unit Bus Voltage (VDC)',
    tooltip = 'The P-Bus supply voltage measured by the remote Module.'
WHERE variable_name='pbtRaUnitBusVoltage';

UPDATE css_networking_device_prop_def
    SET name = 'Communication Entity Index'
WHERE variable_name='pbtEntityComEntity';

REPLACE INTO def_prop_groups_map  VALUES ('pbtRemoteUnitControl', 5033, 'Remote Agent Info');
REPLACE INTO def_status_groups_map  VALUES ('pbtRemoteUnitStatus', 5033, 'Remote Agent Info');
REPLACE INTO def_status_groups_map  VALUES ('pbtRaUnitTemperature', 5033, 'Remote Agent Info\\Environmental');
REPLACE INTO def_status_groups_map  VALUES ('pbtRaUnitAcVoltage', 5033, 'Remote Agent Info');
REPLACE INTO def_status_groups_map  VALUES ('pbtRaUnitBusVoltage', 5033, 'Remote Agent Info');

-- End Bug 8531 
-- Bug 5017 Alarm Rule Types are missing on some of the environemnts 
-- Golnaz Rouhi 

Truncate table data_alarm_rule_type;

INSERT INTO data_alarm_rule_type VALUES ('1', 'Delay'); 
INSERT INTO data_alarm_rule_type VALUES ('2', 'Chronic');
INSERT INTO data_alarm_rule_type VALUES ('3', 'Group'); 

-- auth: Nicole Gager
-- Bug 8344
-- sychronize css_ticketing_ticket_status table so that required statuses have the correct settings/indexes

DROP TABLE IF EXISTS css_ticketing_ticket_status;
CREATE TABLE css_ticketing_ticket_status (
  id int(11) NOT NULL AUTO_INCREMENT,
  description varchar(32) NOT NULL,
  use_followup tinyint(4) DEFAULT NULL,
  default_followup_period smallint(6) DEFAULT NULL,
  canFollowUp tinyint(1) DEFAULT '0',
  is_custom int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

INSERT INTO `css_ticketing_ticket_status` VALUES ('1', 'Open', '0', '0', '0', '0');
INSERT INTO `css_ticketing_ticket_status` VALUES ('2', 'Closed', '0', '0', '0', '0');
INSERT INTO `css_ticketing_ticket_status` VALUES ('3', 'Follow Up', '1', '7', '1', '0');
INSERT INTO `css_ticketing_ticket_status` VALUES ('5', 'Added to Project tracker', '1', '30', '1', '0');
INSERT INTO `css_ticketing_ticket_status` VALUES ('6', 'Fix Posted to Customer Server', '1', '7', '1', '0');
INSERT INTO `css_ticketing_ticket_status` VALUES ('7', 'Waiting on Vendor', '1', '7', '1', '0');
INSERT INTO `css_ticketing_ticket_status` VALUES ('8', 'Active', '0', '0', '0', '0');
INSERT INTO `css_ticketing_ticket_status` VALUES ('10', 'Waiting on Customer', null, null, '0', '0');
INSERT INTO `css_ticketing_ticket_status` VALUES ('11', 'New Setting ', null, null, '0', '0');
REPLACE INTO css_networking_device_type SET id = 2310, class_id = 1074, vendor = 'Westell', model = 'RMM-1400', auto_build_enabled = 1,
uses_snmp = 1, defaultSNMPVer = '2c', defaultSNMPRead = 'eea478269a2bbc0498ac382ed500822c', defaultSNMPWrite = '31cfdf3e44c7ed4a34262041fc664d8c',
main_device = 1, build_file = 'rmx_flatlist_builder.php', scan_file = 'rmx_3200_launcher.php', SNMPauthEncryption = 'SHA',
SNMPprivEncryption = 'AES', SNMPauthType = 'authPriv', support_traps = 1, has_web_interface = 1, canvas_pref_top = 'KentroxRMM1400SelectionTop',
canvas_pref_bottom = 'KentroxRMM1400SelectionBottom', canvas_list = 'Last Selection,Alarms,Custom Fields,Device Information,Donor Sites,Files,
Generator Details View,Graph,Location and Access Info,Log,Map,Notifications,Properties,Property History,Power Plant Information,RETs,Status,Camera';

REPLACE INTO css_networking_device_port_def SET device_type_id = 2310, variable_name = 'http', name = 'HTTP', default_port = 80;
REPLACE INTO css_networking_device_port_def SET device_type_id = 2310, variable_name = 'snmp', name = 'SNMP', default_port = 161;

-- Bug 7453 -- Tristan Burgess
-- Making default invalid generator schedule time be 0000-00-00 00:00:00 (set by the scanner)
-- This will cause the UI to correctly display No Schedule Set for the generator.

UPDATE css_networking_generator_schedule
SET last_start_date = "0000-00-00 00:00:00"
WHERE last_start_date = "1969-12-31 00:00:00";

UPDATE css_networking_generator_schedule
SET last_stop_date = "0000-00-00 00:00:00"
WHERE last_stop_date = "1969-12-31 00:00:00";

UPDATE css_networking_generator_schedule
SET next_start_date = "0000-00-00 00:00:00"
WHERE next_start_date = "1969-12-31 00:00:00";

UPDATE css_networking_generator_schedule
SET next_stop_date = "0000-00-00 00:00:00"
WHERE next_stop_date = "1969-12-31 00:00:00";

UPDATE css_networking_generator_schedule
SET first_start_date = "0000-00-00"
WHERE first_start_date = "1969-12-31";

UPDATE css_networking_generator_schedule
SET first_stop_date = "0000-00-00"
WHERE first_stop_date = "1969-12-31";

-- -----------------------------------------------------------------------------------------------------------------------------------------------
-- Author: Lowell Farrell
-- Bug 8318 - Admin Password policy - display wrong summary
-- Moving the policy description to the DB instead of using hard coded values in the SFW
-- -----------------------------------------------------------------------------------------------------------------------------------------------
SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = 'css_authentication_password_policy_rules'
        AND table_schema = DATABASE()
        AND column_name = 'description'
    ) > 0,
    "SELECT 1",
    "ALTER TABLE css_authentication_password_policy_rules ADD COLUMN description  text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL AFTER Max;"
));
PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

UPDATE `css_authentication_password_policy_rules` SET `description`='PASSWORD LENGTH: \n\nThis field sets the minimum length for passwords \n\nTo set this field, select the active check-box and select a value.' WHERE (`variable_name`='password_length');
UPDATE css_authentication_password_policy_rules SET description='NO PERSONAL INFORMATION: \n\n Password cannot include personal information such as: \n\n 1.FirstName \n 2.LastName \n 3.Username \n 4.Cellphone Number' WHERE (variable_name='no_personal_information');
UPDATE css_authentication_password_policy_rules SET description='NO DICTIONARY WORDS: \n\nNo Dictionary words will be allowed in creating a password, this is the default password policy. \n\nIf this option is selected then no dictionary word 3 letters or longer can be included anywhere in the password. For example: \"GPtHew\" and \"F@miliar\" would both be invalid passwords because they contain \"the\" and \"liar\", however \"V3rizon\" is acceptable because it does not contain any dictionary words with 3 or more characters.' WHERE (variable_name='no_keywords');
UPDATE css_authentication_password_policy_rules SET description='COMPLEXITY REQUIREMENT: \n\nRequires a password to contain at least one of each:\n\n 1.Upper case Letters (A-Z) \n 2.Lower case Letters (a-z) \n 3.Digits (0-9) \n 4.Special characters (Ex:@%$)' WHERE (variable_name='char_groups');
UPDATE css_authentication_password_policy_rules SET description='CONSECUTIVE CHARACTERS LIMIT: \n\nPassword must not contain more than 3 consecutive characters \n\nFor Example: admin1111 will not be allowed' WHERE (variable_name='consecutive_chars');
UPDATE css_authentication_password_policy_rules SET description='PARTIAL DICTIONARY WORDS: \n\nDictionary words can be used as a part of passwords. \n\nIf this option is selected \"No Dictionary Words\" will be turned off. With this option the password may contain a dictionary word as long as the dictionary word is not the entire password. For Example \"Password\" and \"Familiar\" would be invalid passwords, but \"GPtHew\" and \"F@miliar\" would be acceptable as well as \"Password123\" and \"NewPassword\" because they are made up of more than a dictionary word by itself.' WHERE (variable_name='no_dictionary_words');
UPDATE css_authentication_password_policy_rules SET description='MAXIMUM ATTEMPTS: \n\nThis field sets the maximum allowed number of attempts to enter the right password. If the user exceeds this limit, the password will expire. \n\nTo set this field, select the active check-box and select a value. \n\nIf this field is not selected the maximum value will be set at 50.' WHERE (variable_name='max_attempts');
UPDATE css_authentication_password_policy_rules SET description='PASSWORD EXPIRATION:\n\nThis field determines the period of time (in days) that a password can be used before the system requires the user to change it. \n\nTo set this field, select the active check-box and select a value.' WHERE (variable_name='expiry');

-- -----------------------------------------------------------------------------------------------------------------------------------------------
-- Bug 8318 End
-- -----------------------------------------------------------------------------------------------------------------------------------------------
-- Add a 'MA HX Band' class to replace the 'MA GX Band' class HX Band devices are currently set to. Update the device type with the new class id. Wayne 20160621

SET foreign_key_checks = 0;

REPLACE INTO css_networking_device_class SET id = 1160, description = 'MA HX Band', date_updated = '2016-06-16 17:04:00', is_license = 0;

UPDATE css_networking_device_type SET class_id = (SELECT id from css_networking_device_class where description like'%HX%') WHERE vendor LIKE '%MobileAccess%' AND model LIKE '%HX Band%';

SET foreign_key_checks = 1;

-- Add a 'MA HX Band' class to replace the 'MA GX Band' class HX Band devices are currently set to. Update the device type with the new class id. Wayne 20160621-- Delete duplicate variables stored under the wrong device type and rename mislabeled props Wayne 20160621

-- Remove duplicate props found under the second band
DELETE FROM css_networking_device_prop WHERE prop_def_id in (select id from css_networking_device_prop_def where device_type_id = 300 AND variable_name LIKE '%first%');
DELETE FROM css_networking_device_prop_def WHERE device_type_id = 300 AND variable_name LIKE '%first%';
DELETE FROM def_status_groups_map WHERE device_type_id = 300 AND status_def_variable_name LIKE '%first%';

-- Rename mislabeled props
UPDATE css_networking_device_prop_def SET name = "Band ID" WHERE variable_name = "rhuFirstBandId" AND name = "ID";
UPDATE css_networking_device_prop_def SET name = "UL Oper AGC (dB)" WHERE variable_name = "rhuUplinkOperAgcFirstBandDCA" AND name = "UL Oper AGC DCA (dB)";
UPDATE css_networking_device_prop_def SET name = "UL Attenuation (dB)" WHERE variable_name = "rhuUplinkOperFirstBandDCA" AND name = "UL Oper DCA (dB)";
UPDATE css_networking_device_prop_def SET name = "UL Output Power (dBm)" WHERE variable_name = "rhuFirstBandUplinkOutputPower" AND name = "UL Output PWR (dBm)";

UPDATE css_networking_device_prop_def SET name = "Band ID" WHERE variable_name = "rhuSecondBandId" AND name = "ID";
UPDATE css_networking_device_prop_def SET name = "UL Oper AGC (dB)" WHERE variable_name = "rhuUplinkOperAgcSecondBandDCA" AND name = "UL Oper AGC DCA (dB)";
UPDATE css_networking_device_prop_def SET name = "UL Attenuation (dB)" WHERE variable_name = "rhuUplinkOperSecondBandDCA" AND name = "UL Oper DCA (dB)";
UPDATE css_networking_device_prop_def SET name = "UL Output Power (dBm)" WHERE variable_name = "rhuSecondBandUplinkOutputPower" AND name = "UL Output PWR (dBm)";

-- Delete duplicate variables stored under the wrong device type and rename mislabeled props Wayne 20160621-- Bug 8067
-- Auth: Nicole Gager
-- Sets the status id to 0 (the id for 'unassigned' status) if the status has been removed/doesn't exist in css_ticketing_ticket_status


UPDATE css_ticketing_ticket_header
SET css_ticketing_ticket_status_id = 0
WHERE css_ticketing_ticket_status_id NOT IN (SELECT id FROM css_ticketing_ticket_status);-- Bug 8159 - Tristan Burgess - User Management - Usernames can fail to be unique

-- Remove invalid duplicate css_authentication_user entries so that unique index can be added
-- Begin with DeleteUser queries from UserManager
DELETE FROM css_ticketing_user_escId WHERE user_id IN (
  SELECT id 
  FROM css_authentication_user
  WHERE id NOT IN(
      SELECT id FROM (
          SELECT DISTINCT id, username
              FROM css_authentication_user
          GROUP BY username
      ) as tmp
  )
);

Update css_networking_notification_queue set sent=66 WHERE user_id IN (
  SELECT id 
  FROM css_authentication_user
  WHERE id NOT IN(
      SELECT id FROM (
          SELECT DISTINCT id, username
              FROM css_authentication_user
          GROUP BY username
      ) as tmp
  )
);

DELETE FROM css_networking_report_users WHERE user_id IN (
  SELECT id 
  FROM css_authentication_user
  WHERE id NOT IN(
      SELECT id FROM (
          SELECT DISTINCT id, username
              FROM css_authentication_user
          GROUP BY username
      ) as tmp
  )
);

Update css_ticketing_ticket_header set assigned_to_user_id='5' WHERE assigned_to_user_id IN (
  SELECT id 
  FROM css_authentication_user
  WHERE id NOT IN(
      SELECT id FROM (
          SELECT DISTINCT id, username
              FROM css_authentication_user
          GROUP BY username
      ) as tmp
  )
);

Update css_ticketing_ticket_header set created_by_user_id = '5' WHERE created_by_user_id IN (
  SELECT id 
  FROM css_authentication_user
  WHERE id NOT IN(
      SELECT id FROM (
          SELECT DISTINCT id, username
              FROM css_authentication_user
          GROUP BY username
      ) as tmp
  )
);

Update css_ticketing_ticket_details set author_user_id='5' WHERE author_user_id IN (
  SELECT id 
  FROM css_authentication_user
  WHERE id NOT IN(
      SELECT id FROM (
          SELECT DISTINCT id, username
              FROM css_authentication_user
          GROUP BY username
      ) as tmp
  )
);

update css_general_file set user_id = 5 where user_id IN (
  SELECT id 
  FROM css_authentication_user
  WHERE id NOT IN(
      SELECT id FROM (
          SELECT DISTINCT id, username
              FROM css_authentication_user
          GROUP BY username
      ) as tmp
  )
);

DELETE FROM css_authentication_user
WHERE id NOT IN(
    SELECT id FROM (
        SELECT DISTINCT id, username
            FROM css_authentication_user
        GROUP BY username
    ) as tmp
);
-- End DeleteUser queries from UserManager

-- Remove normal username idx
SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE table_name = 'css_authentication_user'
        AND table_schema = DATABASE()
        AND index_name = 'idx_css_authentication_username'
    ) > 0,
    "SELECT 1",
    "ALTER TABLE css_authentication_user DROP INDEX idx_css_authentication_username
    (username) USING BTREE;"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add unique index to prevent duplicate css_authentication_user entries from being added
SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE table_name = 'css_authentication_user'
        AND table_schema = DATABASE()
        AND index_name = 'idx_css_authentication_username_unique'
    ) > 0,
    "SELECT 1",
    "ALTER TABLE css_authentication_user ADD UNIQUE KEY idx_css_authentication_username_unique
    (username) USING BTREE;"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Bug 8111
-- Auth: Samer Dukhan
-- add Native ID property to css_networking_device_prop_def

REPLACE INTO css_networking_device_prop_def (device_type_id,variable_name, name, data_type,editable,prop_type_id,thresh_enable,tooltip) VALUES ('1540','Native ID','Native ID','STRING','0','1','0','Native ID');
REPLACE INTO css_networking_device_prop_def (device_type_id,variable_name, name, data_type,editable,prop_type_id,thresh_enable,tooltip) VALUES ('1543','Native ID','Native ID','STRING','0','1','0','Native ID');
REPLACE INTO css_networking_device_prop_def (device_type_id,variable_name, name, data_type,editable,prop_type_id,thresh_enable,tooltip) VALUES ('1544','Native ID','Native ID','STRING','0','1','0','Native ID');
-- add metaMibPath to css_networking_device_type Table

SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = 'css_networking_device_type'
        AND table_schema = DATABASE()
        AND column_name = 'metaMibPath'
    ) > 0,
    "SELECT 1",
    "ALTER TABLE css_networking_device_type ADD COLUMN metaMibPath VARCHAR( 255 ) NULL COMMENT 'FQN to metaMib file for this device' 
  AFTER heartbeat_threshold_enabled"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
UPDATE css_networking_device_prop_def SET name='RH Output Power (dBm)',prop_type_id=2 WHERE device_type_id=1370 AND variable_name='Max Det Output Power Downlink 0';
REPLACE INTO def_status_groups_map (status_def_variable_name,device_type_id,group_var_name)VALUES('Max Det Output Power Downlink 0','1370','RF Parameters');