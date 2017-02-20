
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for data_current_generator_status
-- ----------------------------
DROP TABLE IF EXISTS `data_current_generator_status`;
CREATE TABLE `data_current_generator_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gen_device_id` int(11) NOT NULL,
  `fuel_sensor_id` int(11) DEFAULT NULL,
  `relay_id` int(11) DEFAULT NULL,
  `generator_running` text,
  `generator_running_state` text,
  `generator_running_time` text,
  `fuelLevel` text,
  `volume_percentlevel_id` int(11) DEFAULT NULL,
  `fuelNodeId` int(11) DEFAULT NULL,
  `min` int(1) DEFAULT NULL,
  `max` int(1) DEFAULT NULL,
  `min_val` varchar(16) DEFAULT NULL,
  `max_val` varchar(16) DEFAULT NULL,
  `volume` text,
  `capacity` text,
  `volume_vlowvalue` text,
  `volume_vlowvalue_id` int(11) DEFAULT NULL,
  `volume_vhighvalue` text,
  `volume_vhighvalue_id` int(11) DEFAULT NULL,
  `volumeUnits` text,
  `setRelayState` text,
  `fuelType` text,
  `last_updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_current_generator_status_gen_index` (`gen_device_id`) USING BTREE,
  UNIQUE KEY `data_current_generator_status_relay_index` (`relay_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
DROP TRIGGER IF EXISTS `data_current_generator_status_insert_trigger`;
DELIMITER ;;
CREATE TRIGGER `data_current_generator_status_insert_trigger` BEFORE INSERT ON `data_current_generator_status` FOR EACH ROW BEGIN
	SET NEW.last_updated = now();
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `data_current_generator_status_update_trigger`;
DELIMITER ;;
CREATE TRIGGER `data_current_generator_status_update_trigger` BEFORE UPDATE ON `data_current_generator_status` FOR EACH ROW BEGIN
	SET NEW.last_updated = now();
END
;;
DELIMITER ;
SET FOREIGN_KEY_CHECKS=1;





REPLACE INTO data_current_generator_status (gen_device_id, fuel_sensor_id, relay_id, generator_running, generator_running_state, generator_running_time, fuelLevel, 
volume_percentlevel_id, fuelNodeId, min, max, min_val, max_val, volume, capacity, volume_vlowvalue, volume_vlowvalue_id, volume_vhighvalue, volume_vhighvalue_id, volumeUnits, setRelayState, fuelType)

SELECT  sub_device_id, 
MAX(IF(sub_device_class_id = 1086, sub_device_id, NULL))AS 'fuel_sensor_id',
MAX(IF(sub_device_class_id = 1082, sub_device_id, NULL))AS 'relay_id',
MAX(IF(variable_name = 'generator_running'      , value, NULL)) AS 'generator_running',
MAX(IF(variable_name = 'generator_running_state', value, NULL)) AS 'generator_running_state',
MAX(IF(variable_name = 'generator_running_time' , value, NULL)) AS 'generator_running_time',
MAX(IF(variable_name = 'volume_percentlevel'    , value, NULL)) AS 'fuelLevel',
MAX(IF(variable_name = 'volume_percentlevel'    , propId, NULL)) AS 'volume_percentlevel_id',
MAX(IF(variable_name = 'volume_percentlevel'    , prop_node_id, NULL)) AS 'fuelNodeId',
MAX(IF(variable_name = 'volume_percentlevel'    , min, NULL)) AS 'min',
MAX(IF(variable_name = 'volume_percentlevel'    , max, NULL)) AS 'max',
MAX(IF(variable_name = 'volume_percentlevel'    , min_val, NULL)) AS 'min_val',
MAX(IF(variable_name = 'volume_percentlevel'    , max_val, NULL)) AS 'max_val',
MAX(IF(variable_name = 'volume_value'           , value, NULL)) AS 'volume',
MAX(IF(variable_name = 'volume_tankvolume'      , value, NULL)) AS 'capacity',
MAX(IF(variable_name = 'volume_vlowvalue'       , value, NULL)) AS 'volume_vlowvalue',
MAX(IF(variable_name = 'volume_vlowvalue'       , propId, NULL)) AS 'volume_vlowvalue_id',
MAX(IF(variable_name = 'volume_vhighvalue'      , value, NULL)) AS 'volume_vhighvalue',
MAX(IF(variable_name = 'volume_vhighvalue'      , propId, NULL)) AS 'volume_vhighvalue_id',
MAX(IF(variable_name = 'volume_volumeunit'      , value, NULL)) AS 'volumeUnits',
MAX(IF(variable_name = 'setRelayState'          , value, NULL)) AS 'setRelayState',
MAX(IF(variable_name = 'gen_fuel_type' 	    , value, NULL)) AS 'fuelType'
FROM ( 
		SELECT  n.main_group, n.nid, n.did, n.name as deviceName, n.type_id, n.sub_device_id, n.sub_device_class_id, pd.variable_name, dp.value, 
						dp.id as propId, n.nid as prop_node_id, dp.min, dp.max, dp.min_val, dp.max_val, 
						n.date_added, n.model, n.is_siteportal_device 
		FROM (


SELECT main_group, n.nid, n.did, n.name, n.type_id, n2.did AS sub_device_id, n2.class_id as sub_device_class_id, n.class_id, n.date_added, n.model, n.is_siteportal_device
                                FROM (

SELECT Node_Nearest_Group_Name (nt.nid) AS main_group, nt.did, nt.name, nt.type_id, nt.model, nt.class_id, nt.nid, nt.parent_node_id, nt.node_map, nt.date_created as date_added, nt.is_siteportal_device
                                FROM (

SELECT  ntm.id as node_map_id, ntm.node_id, ntm.node_map, ntm.deleted, ntm.build_in_progress, ntm.breadcrumb, ntm.visible, 
                                        nt.id as nid, nt.device_id, nt.parent_device_id, nt.parent_node_id,
                                        d.id as did, d.type_id, d.`name`, d.date_created,
                                        dt.class_id, dt.model, d.is_siteportal_device
                                FROM css_networking_network_tree_map ntm
                                JOIN css_networking_network_tree nt ON (nt.id = ntm.node_id)
                                JOIN css_networking_device d on (d.id = nt.device_id)
                                JOIN css_networking_device_type dt ON (dt.id = d.type_id AND dt.class_id IN (1077,1156))
                                WHERE ntm.deleted = 0 AND ntm.visible = 1 AND ntm.build_in_progress = 0

) nt

) n
                                JOIN (
SELECT  ntm.id as node_map_id, ntm.node_id, ntm.node_map, ntm.deleted, ntm.build_in_progress, ntm.breadcrumb, ntm.visible, 
                                        nt.id as nid, nt.device_id, nt.parent_device_id, nt.parent_node_id,
                                        d.id as did, d.type_id, d.`name`, d.date_created,
                                        dt.class_id, dt.model
                                FROM css_networking_network_tree_map ntm
                                JOIN css_networking_network_tree nt ON (nt.id = ntm.node_id)
                                JOIN css_networking_device d on (d.id = nt.device_id)
                                JOIN css_networking_device_type dt ON (dt.id = d.type_id AND dt.class_id in (1082,1086,1077,1156))
                                WHERE ntm.deleted = 0 AND ntm.visible = 1 AND ntm.build_in_progress = 0

) n2 on (n2.node_map LIKE concat(n.node_map, '%'))


) n 
		LEFT JOIN css_networking_device_prop dp ON (dp.device_id = n.sub_device_id)
		LEFT JOIN css_networking_device_prop_def pd ON (pd.id = dp.prop_def_id)
		WHERE (n.class_id = 1086 OR n.class_id = 1082 OR n.type_id = 1093 OR n.type_id = 1173 OR n.class_id = 1156)
		AND pd.variable_name IN ('generator_running','generator_running_state','generator_running_time','volume_percentlevel','volume_value','volume_tankvolume','volume_vlowvalue','volume_vhighvalue','volume_volumeunit','setRelayState', 'gen_fuel_type')
) DATA
GROUP BY nid;