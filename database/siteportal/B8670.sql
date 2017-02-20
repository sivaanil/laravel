-- -------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Bug 8670 - Kentrox RMX3200 - HTTPS port is not built into SitePortal 
-- Author: LFF
-- Adding the missing port def to the definitions table.  Then update the exiting builds with the missing port.
-- -------------------------------------------------------------------------------------------------------------------------------------------------------------------

REPLACE INTO css_networking_device_port_def (device_type_id, variable_name, name, default_port) VALUES ('1293', 'https', 'HTTPS', '443');

INSERT INTO css_networking_device_port (port_def_id,device_id,port)
SELECT dpd.id AS DPDID, d.id AS DID, dpd.default_port AS default_port
FROM css_networking_device d
INNER JOIN css_networking_device_port_def dpd ON dpd.variable_name = 'https' AND dpd.device_type_id = 1150 AND d.type_id = 1150
LEFT JOIN css_networking_device_port dp ON d.id = dp.device_id AND dp.port_def_id IN (SELECT dpd2.id FROM css_networking_device_port_def dpd2 WHERE dpd2.variable_name = 'https' AND dpd2.device_type_id IN (1150,1293))
WHERE dp.port IS NULL;

-- -------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Bug 8670 - End of Script
-- -------------------------------------------------------------------------------------------------------------------------------------------------------------------
