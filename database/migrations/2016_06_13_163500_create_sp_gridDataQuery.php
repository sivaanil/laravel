<?php

use Illuminate\Database\Migrations\Migration;

class CreateSpGridDataQuery extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::unprepared(
                <<<RAWSQL
-- Add two queries.
                     
create index IX1 on css_networking_network_tree_map(node_map, deleted, build_in_progress, visible);

create index idx_css_networking_network_tree_map_breadcrumb on css_networking_network_tree_map(breadcrumb) using BTREE;
RAWSQL
        );
        DB::unprepared(
                <<<RAWSQL
-- Add Stored Procedure

-- ------------------------------------------------------------------------------                                                                                                                                   
-- Function: sp_gridDataQuery                                                                                                                                                                                       
-- Auth:     Craig Haynie                                                                                                                                                                                           
--                                                                                                                                                                                                                  
-- Desc: Does the work for the corresponding method 'gridDataQuery' in:                                                                                                                                             
--       /home/c2-maintenance/sites/unified/app/Http/Controllers/nodes/AlarmController.php                                                                                                                          
--                                                                                                                                                                                                                  
-- Input:                                                                                                                                                                                                           
--       v_numRows    : The number of rows to return.                                                                                                                                                               
--       v_start      : The offset into the return list to start counting the number of rows to return.                                                                                                             
--       v_nodeMap    : The identifier for the node, from which the data will be returned.                                                                                                                          
--       v_selectStmt : The 'Select statement' part of the query. Should be in the form "Select x1, x2, x3 ..."                                                                                                     
--       v_sortBy     : The ordering for the query. Should be in the form "ORDER BY ..."                                                                                                                            
--       v_filterBy   : The qualifiers for the query. Should be in the form "Term1 = X and Term2 = Y ..."                                                                                                           
--       v_clearedTerm: Used to filter out the initial records as 'cleared' or not. Should be in the form "css_networking_device_alarm.cleared_bit in (0|1)"                                                        
--                                                                                                                                                                                                                  
-- ------------------------------------------------------------------------------                                                                                                                                   
-- LastMod: mm/dd/yyyy <Name>        <Description of modification>                                                                                                                                                  
-- ------------------------------------------------------------------------------                                                                                                                                   
                   
-- delimiter //

drop procedure if exists sp_gridDataQuery;

CREATE PROCEDURE sp_gridDataQuery(v_numRows int, v_start int, v_nodeMap varchar(256), v_selectStmt text, v_sortBy text, v_filterBy text, v_clearedTerm text)
proc:BEGIN

DECLARE v_linkTreeMap varchar(150) DEFAULT " ";

DROP TABLE IF EXISTS tmp_CNNTM;

DROP TABLE IF EXISTS tmp_Keys;

-- Holds the primary key for the css_networking_network_tree table. 
CREATE TEMPORARY TABLE `tmp_CNNTM` (
  `node_id` int(11) NOT NULL Primary Key
  );

-- Holds the primary keys for the main tables in the query: the css_networking_network_true, css_networking_device, and css_networking_device_alarm table.
CREATE TEMPORARY TABLE tmp_Keys(
       	id     	     int(10) unsigned not null,
	device_id    int(10) unsigned not null,
	alarm_id     int(10) unsigned not null,
	KEY IX1 (id)        USING BTREE,
	KEY IX2 (device_id) USING BTREE,
	KEY IX3 (alarm_id)  USING BTREE
	);

-- Grab only those nodes which are necessary. Perform common filtering here. This is a very fast query.
set @qry = concat("insert into tmp_CNNTM(node_id) select node_id from css_networking_network_tree_map 
    force index(ix1)
    where  
      css_networking_network_tree_map.node_map like '", v_nodeMap, "%' and
      css_networking_network_tree_map.deleted = 0 and
      css_networking_network_tree_map.build_in_progress = 0 and
      css_networking_network_tree_map.visible = 1;");

PREPARE	stmt FROM @qry;
EXECUTE stmt;

-- If we are filtering or sorting on 'Device Path', then the breadcrumb field is needed, and we need to link the css_networking_network_tree_map table into the next query.
IF POSITION("breadcrumb" IN v_sortBy) > 0 or POSITION("breadcrumb" IN v_filterBy) > 0 THEN
    set v_linkTreeMap = " straight_join css_networking_network_tree_map on css_networking_network_tree_map.node_id = tmp_CNNTM.node_id ";
END IF;

-- This query performs the main body of work. The previous query, and the subsequent query are very fast.
-- This query loads the keys for the three major tables into a temporary table, based on the nodes selected in the tmp_CNNTM table.
-- It then filters and sorts and limits the result set to a very small set.
set @qry = concat("insert into tmp_Keys(id, device_id, alarm_id) 
                   Select css_networking_network_tree.id, css_networking_network_tree.device_id, css_networking_device_alarm.id 
                   from tmp_CNNTM ", 
		     v_linkTreeMap, 
		    "straight_join css_networking_network_tree on css_networking_network_tree.id = tmp_CNNTM.node_id 
		     straight_join css_networking_device_alarm on css_networking_device_alarm.device_id = css_networking_network_tree.device_id 
                   where ", v_filterBy, " and ", v_clearedTerm, " ", v_sortBy, " limit ", v_numRows, " offset ", v_start, ";");

PREPARE	stmt FROM @qry;
EXECUTE stmt;

-- This query just attaches the relevant data in the v_selectStmt, to the keys in the tmp_Keys table, which already filtered and sorted. This query just supplies the data.
set @qry = concat(replace(v_selectStmt, 'css_networking_network_tree.', 'tmp_Keys.'), 
" from tmp_Keys 
    straight_join css_networking_device on css_networking_device.id =  tmp_Keys.device_id
    straight_join css_networking_device_alarm on css_networking_device_alarm.id = tmp_Keys.alarm_id
    straight_join css_networking_device_type on css_networking_device_type.id = css_networking_device.type_id
  left join css_alarms_dictionary on css_alarms_dictionary.device_type_id = css_networking_device.type_id and css_alarms_dictionary.alarm_description = css_networking_device_alarm.description ");

PREPARE stmt FROM @qry;
EXECUTE stmt;

DROP TABLE IF EXISTS tmp_Keys;

DROP TABLE IF EXISTS tmp_CNNTM;

end;

-- //

-- delimiter ;
RAWSQL
        );
    }

// Up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<'SQL'
   
-- Drop the indexes
drop index ix1 on css_networking_network_tree_map;
drop index idx_css_networking_network_tree_map_breadcrumb on css_networking_network_tree_map;

-- Drop the Procedure
drop procedure sp_gridDataQuery;
SQL;
        DB::unprepared($sql);
    }

// down
}

// create_sp_gridDataQuery

    
