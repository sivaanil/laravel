<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Create tables for the Guacamole MySQL authentication plugin
 */

class CreateGuacamoleUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // SiteGate only!
        if (env('C2_SERVER_TYPE') == 'sitegate') {
			$sql = <<<'SQL'
			GRANT ALL ON *.* TO 'guacamole'@'localhost';
            DROP USER 'guacamole'@'localhost';
			CREATE USER 'guacamole'@'localhost' IDENTIFIED BY '2BV@]]LAuX>ua$J^{';
			GRANT ALL ON *.* TO 'guacamole'@'localhost';
SQL;
            DB::unprepared($sql);
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
			DROP USER 'guacamole'@'localhost';
SQL;
            DB::unprepared($sql);
		}
	}
}
