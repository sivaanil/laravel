<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWhitelistAndBlacklistToAclRoles extends Migration
{
	private $tableName = 'acl_roles';
	
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	if (!Schema::hasColumn($this->tableName, 'whitelist_node_ids')) {
    		Schema::table($this->tableName, function (Blueprint $table) {
    			$table->string('whitelist_node_ids', 255)->nullable(false)->default('');
    		});
    	}

		if (!Schema::hasColumn($this->tableName, 'blacklist_node_ids')) {
	    	Schema::table($this->tableName, function (Blueprint $table) {
	        	$table->string('blacklist_node_ids', 255)->nullable(false)->default('');
	        });
	    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	if (Schema::hasColumn($this->tableName, 'whitelist_node_ids')) {
    		Schema::table($this->tableName, function (Blueprint $table) {
    			$table->dropColumn('whitelist_node_ids');
    		});
    	}

		if (Schema::hasColumn($this->tableName, 'blacklist_node_ids')) {
	    	Schema::table($this->tableName, function (Blueprint $table) {
    			$table->dropColumn('blacklist_node_ids');
	        });
	    }
    }
}
