<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Add field sequence to node related tables
 */

class AddSequenceToNodeTreeRelatedTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // SiteGate only!
        if (env('C2_SERVER_TYPE') == 'sitegate') {
DB::unprepared(<<<RAWSQL
-- add table storing sequence for nodes
DROP PROCEDURE IF EXISTS add_node_sequence_tale;
CREATE PROCEDURE add_node_sequence_tale()
BEGIN
  -- check if sequence field is not present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'data_sequence_nodes'
      AND table_schema = DATABASE ()) = 0) THEN
    -- Create table data_sequence_nodes
    CREATE TABLE IF NOT EXISTS `data_sequence_nodes` (
      sequence int(11) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1 MAX_ROWS=1;
  ELSE
    -- delete all records from table storing sequence
    DELETE from data_sequence_nodes;
  END IF;
  -- Initialize stored sequence ID to be started with current epoch timestamp 
  INSERT INTO data_sequence_nodes (sequence) VALUES (UNIX_TIMESTAMP(now())+1);
END;
CALL add_node_sequence_tale();
DROP PROCEDURE IF EXISTS add_node_sequence_tale;
RAWSQL
);

DB::unprepared(<<<RAWSQL
-- Add sequence to css_networking_network_tree_map
DROP PROCEDURE IF EXISTS add_sequence_to_network_tree_map;
CREATE PROCEDURE add_sequence_to_network_tree_map()
BEGIN
  -- check if sequence field is not present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_network_tree_map'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') = 0) THEN
    -- add sequence to the table
    ALTER TABLE css_networking_network_tree_map ADD COLUMN sequence int(11);
    -- Initialize sequence with values from date_updated
    UPDATE css_networking_network_tree_map SET sequence=UNIX_TIMESTAMP(date_updated);
  END IF;
END;
DROP TRIGGER IF EXISTS css_networking_network_tree_map_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_network_tree_map_update_trigger;
CALL add_sequence_to_network_tree_map();
CREATE TRIGGER css_networking_network_tree_map_update_trigger BEFORE UPDATE ON css_networking_network_tree_map FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cnntmu :=sequence)+1;
  SET NEW.sequence = @cnntmu;
  SET NEW.date_updated = now();
END;
CREATE TRIGGER css_networking_network_tree_map_insert_trigger BEFORE INSERT ON css_networking_network_tree_map FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cnntmi :=sequence)+1;
  SET NEW.sequence = @cnntmi;
  SET NEW.date_updated = now();
END;
DROP PROCEDURE IF EXISTS add_sequence_to_network_tree_map;
RAWSQL
);

DB::unprepared(<<<RAWSQL
-- Add sequence to css_networking_network_tree
DROP PROCEDURE IF EXISTS add_sequence_to_network_tree;
CREATE PROCEDURE add_sequence_to_network_tree()
BEGIN
  -- check if sequence field is not present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_network_tree'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') = 0) THEN
    -- add sequence to the table
    ALTER TABLE css_networking_network_tree ADD COLUMN sequence int(11);
    -- Initialize sequence with values from date_updated
    UPDATE css_networking_network_tree SET sequence=UNIX_TIMESTAMP(date_updated);
  END IF;
END;
DROP TRIGGER IF EXISTS css_networking_network_tree_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_network_tree_update_trigger;
CALL add_sequence_to_network_tree();
CREATE TRIGGER css_networking_network_tree_update_trigger BEFORE UPDATE ON css_networking_network_tree FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cnntu :=sequence)+1;
  SET NEW.sequence = @cnntu;
  SET NEW.date_updated = now();
END;
CREATE TRIGGER css_networking_network_tree_insert_trigger BEFORE INSERT ON css_networking_network_tree FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cnnti :=sequence)+1;
  SET NEW.sequence = @cnnti;
  SET NEW.date_updated = now();
END;
DROP PROCEDURE IF EXISTS add_sequence_to_network_tree;
RAWSQL
);

DB::unprepared(<<<RAWSQL
-- Add sequence to css_networking_device
DROP PROCEDURE IF EXISTS add_sequence_to_device;
CREATE PROCEDURE add_sequence_to_device()
BEGIN
  -- check if sequence field is not present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_device'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') = 0) THEN
    -- add sequence to the table
    ALTER TABLE css_networking_device ADD COLUMN sequence int(11);
    -- Initialize sequence with values from date_updated
    UPDATE css_networking_device SET sequence=UNIX_TIMESTAMP(date_updated);
  END IF;
END;
DROP TRIGGER IF EXISTS css_networking_device_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_device_update_trigger;
CALL add_sequence_to_device();
CREATE TRIGGER css_networking_device_update_trigger BEFORE UPDATE ON css_networking_device FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cndmu :=sequence)+1;
  SET NEW.sequence = @cndmu;
  SET NEW.date_updated = now();
END;
CREATE TRIGGER css_networking_device_insert_trigger BEFORE INSERT ON css_networking_device FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cndmi :=sequence)+1;
  SET NEW.sequence = @cndmi;
  SET NEW.date_created = now();        
  SET NEW.date_updated = now();
END;
DROP PROCEDURE IF EXISTS add_sequence_to_device;
RAWSQL
);

DB::unprepared(<<<RAWSQL
-- Add sequence to css_networking_group
DROP PROCEDURE IF EXISTS add_sequence_to_group;
CREATE PROCEDURE add_sequence_to_group()
BEGIN
  -- check if sequence field is not present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_group'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') = 0) THEN
    -- add sequence to the table
    ALTER TABLE css_networking_group ADD COLUMN sequence int(11);
    -- Initialize sequence with values from date_updated
    UPDATE css_networking_group SET sequence=UNIX_TIMESTAMP(date_updated);
  END IF;
END;
DROP TRIGGER IF EXISTS css_networking_group_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_group_update_trigger;
CALL add_sequence_to_group();
CREATE TRIGGER css_networking_group_update_trigger BEFORE UPDATE ON css_networking_group FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cngu :=sequence)+1;
  SET NEW.sequence = @cngu;
  SET NEW.date_updated = now();
END;
CREATE TRIGGER css_networking_group_insert_trigger BEFORE INSERT ON css_networking_group FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cngi :=sequence)+1;
  SET NEW.sequence = @cngi;
  SET NEW.date_updated = now();
  SET NEW.date_created = now();
END;
DROP PROCEDURE IF EXISTS add_sequence_to_group;
RAWSQL
);

DB::unprepared(<<<RAWSQL
-- Add sequence to css_networking_device_port
DROP PROCEDURE IF EXISTS add_sequence_to_device_port;
CREATE PROCEDURE add_sequence_to_device_port()
BEGIN
  -- check if sequence field is not present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_device_port'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') = 0) THEN
    -- add sequence to the table
    ALTER TABLE css_networking_device_port ADD COLUMN sequence int(11);
    -- Initialize sequence with values from date_updated
    UPDATE css_networking_device_port SET sequence=UNIX_TIMESTAMP(date_updated);
  END IF;
END;
DROP TRIGGER IF EXISTS css_networking_device_port_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_device_port_update_trigger;
CALL add_sequence_to_device_port();
CREATE TRIGGER css_networking_device_port_update_trigger BEFORE UPDATE ON css_networking_device_port FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cndpu :=sequence)+1;
  SET NEW.sequence = @cndpu;
  SET NEW.date_updated = now();
END;
CREATE TRIGGER css_networking_device_port_insert_trigger BEFORE INSERT ON css_networking_device_port FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cndpi :=sequence)+1;
  SET NEW.sequence = @cndpi;
  SET NEW.date_updated = now();
END;
DROP PROCEDURE IF EXISTS add_sequence_to_device_port;
RAWSQL
);
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        // SiteGate only!
        if (env('C2_SERVER_TYPE') == 'sitegate') {		
            $sql = <<<'SQL'
            
-- Delete sequence from css_networking_network_tree_map
DROP PROCEDURE IF EXISTS delete_sequence_from_network_tree_map;
CREATE PROCEDURE delete_sequence_from_network_tree_map()
BEGIN
  -- check if sequence field is present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_network_tree_map'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') > 0) THEN
    -- delete sequence to the table
    ALTER TABLE css_networking_network_tree_map DROP COLUMN sequence;
  END IF;
END;
DROP TRIGGER IF EXISTS css_networking_network_tree_map_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_network_tree_map_update_trigger;
CALL delete_sequence_from_network_tree_map();
CREATE TRIGGER css_networking_network_tree_map_update_trigger BEFORE UPDATE ON css_networking_network_tree_map FOR EACH ROW
BEGIN
  SET NEW.date_updated = now();
END;
CREATE TRIGGER css_networking_network_tree_map_insert_trigger BEFORE INSERT ON css_networking_network_tree_map FOR EACH ROW
BEGIN
  SET NEW.date_updated = now();
END;
DROP PROCEDURE IF EXISTS delete_sequence_from_network_tree_map;

-- Delete sequence from css_networking_network_tree
DROP PROCEDURE IF EXISTS delete_sequence_from_network_tree;
CREATE PROCEDURE delete_sequence_from_network_tree()
BEGIN
  -- check if sequence field is present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_network_tree'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') > 0) THEN
    -- delete sequence to the table
    ALTER TABLE css_networking_network_tree DROP COLUMN sequence;
  END IF;
END;
DROP TRIGGER IF EXISTS css_networking_network_tree_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_network_tree_update_trigger;
CALL delete_sequence_from_network_tree();
CREATE TRIGGER css_networking_network_tree_update_trigger BEFORE UPDATE ON css_networking_network_tree FOR EACH ROW
BEGIN
  SET NEW.date_updated = now();
END;
CREATE TRIGGER css_networking_network_tree_insert_trigger BEFORE INSERT ON css_networking_network_tree FOR EACH ROW
BEGIN
  SET NEW.date_updated = now();
END;
DROP PROCEDURE IF EXISTS delete_sequence_from_network_tree;
        
-- Delete sequence from css_networking_device
DROP PROCEDURE IF EXISTS delete_sequence_from_device;
CREATE PROCEDURE delete_sequence_from_device()
BEGIN
  -- check if sequence field is present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_device'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') > 0) THEN
    -- delete sequence to the table
    ALTER TABLE css_networking_device DROP COLUMN sequence;
  END IF;
END;
DROP TRIGGER IF EXISTS css_networking_device_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_device_update_trigger;
CALL delete_sequence_from_device();
CREATE TRIGGER css_networking_device_update_trigger BEFORE UPDATE ON css_networking_device FOR EACH ROW
BEGIN
  SET NEW.date_updated = now();
END;
CREATE TRIGGER css_networking_device_insert_trigger BEFORE INSERT ON css_networking_device FOR EACH ROW
BEGIN
  SET NEW.date_updated = now();
END;
DROP PROCEDURE IF EXISTS delete_sequence_from_device;
        
-- Delete sequence from css_networking_group
DROP PROCEDURE IF EXISTS delete_sequence_from_group;
CREATE PROCEDURE delete_sequence_from_group()
BEGIN
  -- check if sequence field is present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_group'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') > 0) THEN
    -- delete sequence to the table
    ALTER TABLE css_networking_group DROP COLUMN sequence;
  END IF;
END;
DROP TRIGGER IF EXISTS css_networking_group_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_group_update_trigger;
CALL delete_sequence_from_group();
CREATE TRIGGER css_networking_group_update_trigger BEFORE UPDATE ON css_networking_group FOR EACH ROW
BEGIN
  SET NEW.date_updated = now();
END;
CREATE TRIGGER css_networking_group_insert_trigger BEFORE INSERT ON css_networking_group FOR EACH ROW
BEGIN
  SET NEW.date_updated = now();
END;
DROP PROCEDURE IF EXISTS delete_sequence_from_group;
        
-- Delete sequence from css_networking_device_port
DROP PROCEDURE IF EXISTS delete_sequence_from_device_port;
CREATE PROCEDURE delete_sequence_from_device_port()
BEGIN
  -- check if sequence field is present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_device_port'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') > 0) THEN
    -- delete sequence to the table
    ALTER TABLE css_networking_device_port DROP COLUMN sequence;
  END IF;
END;
DROP TRIGGER IF EXISTS css_networking_device_port_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_device_port_update_trigger;
CALL delete_sequence_from_device_port();
CREATE TRIGGER css_networking_device_port_update_trigger BEFORE UPDATE ON css_networking_device_port FOR EACH ROW
BEGIN
  SET NEW.date_updated = now();
END;
CREATE TRIGGER css_networking_device_port_insert_trigger BEFORE INSERT ON css_networking_device_port FOR EACH ROW
BEGIN
  SET NEW.date_updated = now();
END;
DROP PROCEDURE IF EXISTS delete_sequence_from_device_port;
        
-- drop table data_sequence_alarms
DROP TABLE IF EXISTS `data_sequence_nodes`;
SQL;
            DB::unprepared($sql);           
		}
	}
}
