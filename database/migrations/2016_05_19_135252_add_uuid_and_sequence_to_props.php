<?php
use Illuminate\Database\Migrations\Migration;

/**
 * Add uuid and sequence to css_networking_network_prop table
 */
class AddUuidAndSequenceToProps extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        // SiteGate only!
        if (env ( 'C2_SERVER_TYPE' ) == 'sitegate') {

             DB::unprepared (
                    <<<RAWSQL
-- increase size of filed timestamp in the data_networking_siteportal_device to bigint
-- to be able to handle sequence number for property logs (timestamp+propId)
    ALTER TABLE data_networking_siteportal_device MODIFY timestamp bigint;
RAWSQL
 );
           DB::unprepared (
                    <<<RAWSQL
-- drop update triggers to avoid unnecessary modification of date_updated column.
DROP TRIGGER IF EXISTS css_networking_device_prop_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_device_prop_update_trigger;
RAWSQL
 );

            DB::unprepared (
                    <<<RAWSQL
-- Creating table storing sequence for props
DROP PROCEDURE IF EXISTS add_props_sequence_table;
CREATE PROCEDURE add_props_sequence_table()
BEGIN
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'data_sequence_props'
      AND table_schema = DATABASE ()) = 0) THEN
    -- Create table data_sequence_props
    CREATE TABLE IF NOT EXISTS `data_sequence_props` (
      sequence int(11) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1 MAX_ROWS=1;
  ELSE
    -- delete all records from table storing sequence
    DELETE from data_sequence_props;
  END IF;
  -- Initialize stored sequence to be started with 1
  INSERT INTO data_sequence_props (sequence) VALUES (1);
END;
-- call function adding table storing sequence for props
CALL add_props_sequence_table();
DROP PROCEDURE IF EXISTS add_props_sequence_table;
RAWSQL
 );
            DB::unprepared (
                    <<<RAWSQL
-- Add uuid to css_networking_device_prop
DROP PROCEDURE IF EXISTS add_uuid_to_device_prop;
CREATE PROCEDURE add_uuid_to_device_prop()
BEGIN
  -- check if uuid field is not present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_device_prop'
      AND table_schema = DATABASE ()
      AND column_name = 'uuid') = 0) THEN
    -- add uuid to the table
    ALTER TABLE css_networking_device_prop ADD COLUMN uuid varchar(36);
  END IF;
  #Initialize UUID field
  UPDATE css_networking_device_prop SET uuid=UUID() where uuid is null;
END;
CALL add_uuid_to_device_prop();
DROP PROCEDURE IF EXISTS add_uuid_to_device_prop;
RAWSQL
 );


            DB::unprepared (
                    <<<RAWSQL
-- Create function adding field sequence to props table
DROP PROCEDURE IF EXISTS add_props_sequence_id;
CREATE PROCEDURE add_props_sequence_id()
BEGIN
  -- check if sequence field is not present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_device_prop'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') = 0) THEN
    -- add sequence to the table
    ALTER TABLE css_networking_device_prop ADD COLUMN sequence int(11);
  END IF;
END;

-- call function adding sequence_id to the css_networking_device_prop_table
CALL add_props_sequence_id();
DROP PROCEDURE IF EXISTS add_props_sequence_id;
RAWSQL
 );

            DB::unprepared (
                    <<<RAWSQL
-- create update trigger
DROP TRIGGER IF EXISTS css_networking_device_prop_update_trigger;
CREATE TRIGGER css_networking_device_prop_update_trigger BEFORE UPDATE ON css_networking_device_prop FOR EACH ROW
BEGIN
  UPDATE data_sequence_props set sequence = (@cndpsu :=sequence)+1;
  SET NEW.sequence = @cndpsu;

  SET NEW.date_updated = now();
END;

-- create insert trigger
DROP TRIGGER IF EXISTS css_networking_device_prop_insert_trigger;
CREATE TRIGGER css_networking_device_prop_insert_trigger BEFORE INSERT ON css_networking_device_prop FOR EACH ROW
BEGIN
  IF NEW.uuid is null THEN
    SET NEW.uuid = UUID();
  END IF;
  UPDATE data_sequence_props set sequence = (@cndpsi :=sequence)+1;
  SET NEW.sequence = @cndpsi;

  SET NEW.date_updated = now(),NEW.date_created = now();
END;

UPDATE css_networking_device_prop SET min = min order by device_id,date_updated;
RAWSQL
 );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        // SiteGate only!
        if (env ( 'C2_SERVER_TYPE' ) == 'sitegate') {
            $sql = <<<'SQL'
-- drop update triggers to avoid unnecessary modification of date_updated column.
DROP TRIGGER IF EXISTS css_networking_device_prop_insert_trigger;
DROP TRIGGER IF EXISTS css_networking_device_prop_update_trigger;

-- Delete uuid from css_networking_device_prop
DROP PROCEDURE IF EXISTS delete_uuid_from_device_prop;
CREATE PROCEDURE delete_uuid_from_device_prop()
BEGIN
  -- check if uuid field is present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_device_prop'
      AND table_schema = DATABASE ()
      AND column_name = 'uuid') > 0) THEN
    -- delete uuid from the table
    ALTER TABLE css_networking_device_prop DROP COLUMN uuid;
  END IF;
END;
CALL delete_uuid_from_device_prop();
DROP PROCEDURE IF EXISTS delete_uuid_from_device_prop;

-- Removing sequence from css_networking_device_prop
DROP PROCEDURE IF EXISTS delete_props_sequence;
CREATE PROCEDURE delete_props_sequence()
BEGIN
  -- check if sequence_id field is not present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_device_prop'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') = 1) THEN
    -- add sequence_id to the table
    ALTER TABLE css_networking_device_prop DROP COLUMN sequence;
  END IF;
END;

-- call function deleteing sequence to the css_networking_device_prop
CALL delete_props_sequence();
DROP PROCEDURE IF EXISTS delete_props_sequence;
-- drop table data_sequence_props
DROP TABLE IF EXISTS `data_sequence_props`;
-- recreate update trigger
CREATE TRIGGER css_networking_device_prop_update_trigger BEFORE UPDATE ON css_networking_device_prop FOR EACH ROW
BEGIN
  SET NEW.date_updated = now();
END;

-- modify inset trigger
CREATE TRIGGER css_networking_device_prop_insert_trigger BEFORE INSERT ON css_networking_device_prop FOR EACH ROW
BEGIN
  SET NEW.date_updated = now(),NEW.date_created = now();
END;
SQL;
            DB::unprepared ( $sql );
        }
    }
}
