<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Update sequence to prevent multiple repeated numbers
 */
 
class UpdateSequenceToPreventMultipleRepeatedNumbers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // SiteGate only!
	// Modify any field to trigger sequence modification
        if (env('C2_SERVER_TYPE') == 'sitegate') {
DB::unprepared(<<<RAWSQL
    UPDATE css_networking_device_alarm SET severity_id = severity_id order by device_id,raised;
    UPDATE css_networking_network_tree_map SET deleted=deleted;
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
		}
	}
}
