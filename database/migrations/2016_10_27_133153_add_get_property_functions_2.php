<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Move field uuid from networkTreeMap to NetworkTree
 */

class AddGetPropertyFunctions2 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
DB::unprepared(<<<RAWSQL
DROP FUNCTION IF EXISTS GetMainDevicePropertyByNodeMap;
CREATE FUNCTION GetMainDevicePropertyByNodeMap(node_map varchar(255), variable_name varchar(255)) RETURNS VARCHAR(255)
BEGIN
DECLARE retVal varchar(250);
		return (select ndp.value from css_networking_network_tree_map nntm
                    inner join css_networking_network_tree nnt on nnt.id = nntm.node_id and nnt.device_id <> 0
                    inner join css_networking_device nd on nd.id = nnt.device_id
                    inner join css_networking_device_type ndt on ndt.id = nd.type_id
                    inner join css_networking_device_prop_def ndpd on ndpd.device_type_id = nd.type_id and ndpd.variable_name = variable_name
                    inner join css_networking_device_prop ndp on ndpd.id = ndp.prop_def_id and ndp.device_id=nd.id
                  where ndt.main_device = 1 and instr(node_map, nntm.node_map)
                  order by nntm.node_map DESC limit 1);
END;
        RAWSQL
        );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
DB::unprepared(<<<RAWSQL
DROP FUNCTION IF EXISTS GetMainDevicePropertyByNodeMap;
RAWSQL
);
	}
}
