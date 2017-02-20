

SET @s = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE table_name = 'data_device_generator_status'
        AND table_schema = DATABASE()
        AND column_name = 'data_generator_run_id'
    ) > 0,
    "SELECT 1",
    "ALTER TABLE data_device_generator_status ADD COLUMN data_generator_run_id INTEGER"
));

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;



