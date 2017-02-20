<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Move field uuid from networkTreeMap to NetworkTree
 */

class MoveUuidFromNntmToNnt extends Migration {

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
-- Add uuid to css_networking_network_tree
DROP PROCEDURE IF EXISTS add_uuid_to_network_tree;
CREATE PROCEDURE add_uuid_to_network_tree()
BEGIN
  -- check if uuid field is not present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_network_tree'
      AND table_schema = DATABASE ()
      AND column_name = 'uuid') = 0) THEN
    -- add uuid to the table
    ALTER TABLE css_networking_network_tree ADD COLUMN uuid varchar(36);
  END IF;
END;
-- drop update triggers to avoid unnecessary modification of date_updated column.
DROP TRIGGER IF EXISTS css_networking_network_tree_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_network_tree_update_trigger;
CALL add_uuid_to_network_tree();
DROP PROCEDURE IF EXISTS add_uuid_to_network_tree;
RAWSQL
);
DB::unprepared(<<<RAWSQL
-- Initialize empty uuids
UPDATE css_networking_network_tree SET uuid=UUID() where uuid is null;
RAWSQL
);
DB::unprepared(<<<RAWSQL
CREATE TRIGGER css_networking_network_tree_update_trigger BEFORE UPDATE ON css_networking_network_tree FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cnntmu :=sequence)+1;
  SET NEW.sequence = @cnntmu;
  SET NEW.date_updated = now();
END;
CREATE TRIGGER css_networking_network_tree_map_insert_trigger BEFORE INSERT ON css_networking_network_tree FOR EACH ROW
BEGIN
  IF NEW.uuid is null THEN
    SET NEW.uuid = UUID();
  END IF;
  UPDATE data_sequence_nodes set sequence = (@cnntmi :=sequence)+1;
  SET NEW.sequence = @cnntmi;
  SET NEW.date_updated = now();
END;
        
RAWSQL
);


DB::unprepared(<<<RAWSQL
-- Delete uuid from css_networking_network_tree_map
DROP PROCEDURE IF EXISTS delete_uuid_from_network_tree_map;
CREATE PROCEDURE delete_uuid_from_network_tree_map()
BEGIN
  -- check if uuid field is present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_network_tree_map'
      AND table_schema = DATABASE ()
      AND column_name = 'uuid') > 0) THEN
    -- delete uuid from the table
    ALTER TABLE css_networking_network_tree_map DROP COLUMN uuid;
  END IF;
END;
DROP TRIGGER IF EXISTS css_networking_network_tree_map_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_network_tree_map_update_trigger;
CALL delete_uuid_from_network_tree_map();
DROP PROCEDURE IF EXISTS delete_uuid_from_network_tree_map;
RAWSQL
        );
DB::unprepared(<<<RAWSQL
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
DB::unprepared(<<<RAWSQL
-- Delete uuid from css_networking_network_tree
DROP PROCEDURE IF EXISTS delete_uuid_from_network_tree;
CREATE PROCEDURE delete_uuid_from_network_tree()
BEGIN
  -- check if uuid field is present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_network_tree'
      AND table_schema = DATABASE ()
      AND column_name = 'uuid') > 0) THEN
    -- delete uuid from the table
    ALTER TABLE css_networking_network_tree DROP COLUMN uuid;
  END IF;
END;
DROP TRIGGER IF EXISTS css_networking_network_tree_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_network_tree_update_trigger;
CALL delete_uuid_from_network_tree();
DROP PROCEDURE IF EXISTS delete_uuid_from_network_tree;
RAWSQL
);
DB::unprepared(<<<RAWSQL
CREATE TRIGGER css_networking_network_tree_update_trigger BEFORE UPDATE ON css_networking_network_tree FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cnntmu :=sequence)+1;
  SET NEW.sequence = @cnntmu;
  SET NEW.date_updated = now();
END;
CREATE TRIGGER css_networking_network_tree_insert_trigger BEFORE INSERT ON css_networking_network_tree FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cnntmi :=sequence)+1;
  SET NEW.sequence = @cnntmi;
  SET NEW.date_updated = now();
END;
RAWSQL
);

DB::unprepared(<<<RAWSQL
-- Add uuid to css_networking_network_tree_map
DROP PROCEDURE IF EXISTS add_uuid_to_network_tree_map;
CREATE PROCEDURE add_uuid_to_network_tree_map()
BEGIN
  -- check if uuid field is not present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_network_tree_map'
      AND table_schema = DATABASE ()
      AND column_name = 'uuid') = 0) THEN
    -- add uuid to the table
    ALTER TABLE css_networking_network_tree_map ADD COLUMN uuid varchar(36);
  END IF;
END;
-- drop update triggers to avoid unnecessary modification of date_updated column.
DROP TRIGGER IF EXISTS css_networking_network_tree_map_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_network_tree_map_update_trigger;
CALL add_uuid_to_network_tree_map();
DROP PROCEDURE IF EXISTS add_uuid_to_network_tree_map;
RAWSQL
        );
DB::unprepared(<<<RAWSQL
-- Initialize empty uuids
UPDATE css_networking_network_tree_map SET uuid=UUID() where uuid is null;
RAWSQL
        );
DB::unprepared(<<<RAWSQL
CREATE TRIGGER css_networking_network_tree_map_update_trigger BEFORE UPDATE ON css_networking_network_tree_map FOR EACH ROW
BEGIN
  UPDATE data_sequence_nodes set sequence = (@cnntmu :=sequence)+1;
  SET NEW.sequence = @cnntmu;
  SET NEW.date_updated = now();
END;
CREATE TRIGGER css_networking_network_tree_map_insert_trigger BEFORE INSERT ON css_networking_network_tree_map FOR EACH ROW
BEGIN
  IF NEW.uuid is null THEN
    SET NEW.uuid = UUID();
  END IF;
  UPDATE data_sequence_nodes set sequence = (@cnntmi :=sequence)+1;
  SET NEW.sequence = @cnntmi;
  SET NEW.date_updated = now();
END;

RAWSQL
        );
        }
	}
}
