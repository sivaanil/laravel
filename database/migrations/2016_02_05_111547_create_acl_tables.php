<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclTables extends Migration
{

    const ROLES_TABLE = 'acl_roles';
    const PERMISSIONS_TABLE = 'acl_permissions';
    const USER_PERM_TABLE = 'acl_user_permission';
    const ROLE_USER_TABLE = 'acl_role_user';
    const ROLE_PERM_TABLE = 'acl_role_permission';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the roles table
        $this->createRolesTable();
        // Create the permissions table
        $this->createPermissionsTable();
        // Create the user_permission table
        $this->createUserPermissionTable();
        // Create the role_user table
        $this->createRoleUserTable();
        // create the role_permission table
        $this->createRolePermissionTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop tables
        Schema::drop(self::ROLES_TABLE);
        Schema::drop(self::PERMISSIONS_TABLE);
        Schema::drop(self::USER_PERM_TABLE);
        Schema::drop(self::ROLE_USER_TABLE);
        Schema::drop(self::ROLE_PERM_TABLE);
    }

    protected function createRolesTable() {
        if (!Schema::hasTable(self::ROLES_TABLE)) {
            Schema::create(self::ROLES_TABLE, function (Blueprint $t) {
                $t->increments('id')->unsigned();
                $t->string('title')->unique();
                $t->string('slug')->unique();
                $t->string('description', 150);
            });
        }
    }

    protected function createPermissionsTable() {
        if (!Schema::hasTable(self::PERMISSIONS_TABLE)) {
            Schema::create(self::PERMISSIONS_TABLE, function(Blueprint $t) {
                $t->increments('id');
                $t->string('title')->unique();
                $t->string('slug')->unique();
                $t->string('description', 150);
            });
        }
    }

    protected function createUserPermissionTable() {
        if (!Schema::hasTable(self::USER_PERM_TABLE)) {
            Schema::create(self::USER_PERM_TABLE, function(Blueprint $t) {
                $t->integer('user_id')->unsigned();
                $t->integer('permission_id')->unsigned();
                $t->primary(['user_id', 'permission_id']);

                $t->foreign('user_id')->references('id')->on('css_authentication_user');
                $t->foreign('permission_id')->references('id')->on(self::PERMISSIONS_TABLE);
            });
        }
    }
    protected function createRoleUserTable() {
        if (!Schema::hasTable(self::ROLE_USER_TABLE)) {
            Schema::create(self::ROLE_USER_TABLE, function(Blueprint $t) {
                $t->integer('role_id')->unsigned();
                $t->integer('user_id')->unsigned();
                $t->primary(['user_id', 'role_id']);

                $t->foreign('role_id')->references('id')->on(self::ROLES_TABLE);
                $t->foreign('user_id')->references('id')->on('css_authentication_user');
            });
        }
    }

    protected function createRolePermissionTable() {
        if (!Schema::hasTable(self::ROLE_PERM_TABLE)) {
            Schema::create(self::ROLE_PERM_TABLE, function(Blueprint $t) {
                $t->integer('role_id')->unsigned();
                $t->integer('permission_id')->unsigned();
                $t->primary(['role_id', 'permission_id']);

                $t->foreign('role_id')->references('id')->on(self::ROLES_TABLE);
                $t->foreign('permission_id')->references('id')->on(self::PERMISSIONS_TABLE);
            });
        }
    }
}
