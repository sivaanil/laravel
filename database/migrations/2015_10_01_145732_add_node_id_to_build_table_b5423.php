<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNodeIdToBuildTableB5423 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        SET @s = (SELECT IF(
            (SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE table_name = 'css_networking_build'
                AND table_schema = DATABASE()
                AND column_name = 'node_id'
            ) > 0,
            \"SELECT 1\",
            \"ALTER TABLE css_networking_build ADD COLUMN node_id INT NULL\"
        ));

        PREPARE stmt FROM @s;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // one way
    }
}
