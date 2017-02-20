<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Add sequence to css_networking_network_alarm table
 */

class AddSequenceToAlarms extends Migration {

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
-- Creating table storing sequence for alarms
DROP PROCEDURE IF EXISTS add_alarms_sequence_table;
CREATE PROCEDURE add_alarms_sequence_table()
BEGIN
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'data_sequence_alarms'
      AND table_schema = DATABASE ()) = 0) THEN
    -- Create table data_sequence_alarms
    CREATE TABLE IF NOT EXISTS `data_sequence_alarms` (
      sequence int(11) NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1 MAX_ROWS=1;
  ELSE
    -- delete all records from table storing sequence
    DELETE from data_sequence_alarms;
  END IF;
  -- Initialize stored sequence to be started with current epoch timestamp
  INSERT INTO data_sequence_alarms (sequence) VALUES (UNIX_TIMESTAMP(now())+1);
END;
-- call function adding table storing sequence for alarms
CALL add_alarms_sequence_table();
DROP PROCEDURE IF EXISTS add_alarms_sequence_table;
RAWSQL
);

DB::unprepared(<<<RAWSQL
-- Create function adding field sequence to alarms table
DROP PROCEDURE IF EXISTS add_alarms_sequence_id;
CREATE PROCEDURE add_alarms_sequence_id()
BEGIN
  -- check if sequence field is not present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_device_alarm'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') = 0) THEN
    -- add sequence to the table
    ALTER TABLE css_networking_device_alarm ADD COLUMN sequence int(11);
    -- Initialize sequence with values from date_updated
    UPDATE css_networking_device_alarm SET sequence=UNIX_TIMESTAMP(date_updated);
  END IF;
END;

-- drop update/insert triggers to preserve date_updated field from be modified during possible table update.
DROP TRIGGER IF EXISTS css_networking_device_alarm_update_trigger;
DROP TRIGGER IF EXISTS css_networking_device_alarm_insert_trigger;
-- call function adding sequence_id to the css_networking_device_alarm_table
CALL add_alarms_sequence_id();
DROP PROCEDURE IF EXISTS add_alarms_sequence_id;
RAWSQL
);

DB::unprepared(<<<RAWSQL
-- create update trigger
CREATE TRIGGER css_networking_device_alarm_update_trigger BEFORE UPDATE ON css_networking_device_alarm FOR EACH ROW
BEGIN
  UPDATE data_sequence_alarms set sequence = (@cndau :=sequence)+1;
  SET NEW.sequence = @cndau;

  SET NEW.date_updated = now();
  IF (NEW.cleared is not null) THEN
     SET NEW.cleared_order = (4102358400 - UNIX_TIMESTAMP(NEW.cleared));
     SET NEW.cleared_bit = 1;
  END IF;
END;

-- create insert trigger
CREATE TRIGGER css_networking_device_alarm_insert_trigger BEFORE INSERT ON css_networking_device_alarm FOR EACH ROW
BEGIN
  UPDATE data_sequence_alarms set sequence = (@cndai :=sequence)+1;
  SET NEW.sequence = @cndai;

  SET NEW.date_updated = now();
  IF (NEW.cleared is not null) THEN
     SET NEW.cleared_order = (4102358400 - UNIX_TIMESTAMP(NEW.cleared));
     SET NEW.cleared_bit = 1;
  END IF;
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
            $sql = <<<'SQL'
-- Removing sequence from css_networking_device_alarm
DROP PROCEDURE IF EXISTS delete_alarms_sequence_id;
CREATE PROCEDURE delete_alarms_sequence_id()
BEGIN
  -- check if sequence_id field is not present in the table
  IF ((SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE
      table_name = 'css_networking_device_alarm'
      AND table_schema = DATABASE ()
      AND column_name = 'sequence') = 1) THEN
    -- add sequence_id to the table
    ALTER TABLE css_networking_device_alarm DROP COLUMN sequence;
  END IF;
END;

-- drop update/insert triggers to preserve date_updated field from be modified during possible table update.
DROP TRIGGER IF EXISTS css_networking_device_alarm_update_trigger;
DROP TRIGGER IF EXISTS css_networking_device_alarm_insert_trigger;
-- call function adding sequence_id to the css_natworking_Alarm_table
CALL delete_alarms_sequence_id();
DROP PROCEDURE IF EXISTS add_alarms_sequence_id;
-- drop table data_sequence_alarms
DROP TABLE IF EXISTS `data_sequence_alarms`;
-- recreate update trigger
CREATE TRIGGER css_networking_device_alarm_update_trigger BEFORE UPDATE ON css_networking_device_alarm FOR EACH ROW
BEGIN
  SET NEW.date_updated = now();
  IF (NEW.cleared is not null) THEN
     SET NEW.cleared_order = (4102358400 - UNIX_TIMESTAMP(NEW.cleared));
     SET NEW.cleared_bit = 1;
  END IF;
END;

-- modify inset trigger
CREATE TRIGGER css_networking_device_alarm_insert_trigger BEFORE INSERT ON css_networking_device_alarm FOR EACH ROW
BEGIN
  SET NEW.date_updated = now();
  IF (NEW.cleared is not null) THEN
     SET NEW.cleared_order = (4102358400 - UNIX_TIMESTAMP(NEW.cleared));
     SET NEW.cleared_bit = 1;
  END IF;
END;
SQL;
            DB::unprepared($sql);
		}
	}
}
