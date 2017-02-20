<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HaveAclRolePermissionsUseSlugs extends Migration
{
    private $tableName = 'acl_role_permission';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function ($table) {


            $keys = ['acl_role_permission_ibfk_1','acl_role_permission_ibfk_2', 'acl_role_permission_permission_id_foreign', 'acl_role_permission_role_id_foreign'];

            // For each of the keys, determine whether it is a foreign key or index, and act accordingly to drop it
            foreach ($keys as $keyName) {

                if ($this->isForeignKey($keyName)) {
                    $table->dropForeign($keyName);
                    continue;
                }
                $query = "SHOW KEYS FROM {$this->tableName} WHERE key_name = '$keyName'";
                $keyExists = DB::select(DB::raw($query));
                if ($keyExists) {
                    $table->dropIndex($keyName);
                }
            }

            $table->dropColumn(['role_id', 'permission_id']);

            $fields = ['role_slug', 'permission_slug'];
            foreach ($fields as $field) {
                if (!Schema::hasColumn($this->tableName, $field)) {
                    $table->string($field);
                }
            }
            $table->primary(['role_slug', 'permission_slug']);
            $table->foreign('role_slug')->references('slug')->on('acl_roles');
            $table->foreign('permission_slug')->references('slug')->on('acl_permissions');
        });

    }

    protected function isForeignKey($keyName) {
        $query = "select constraint_name from information_schema.key_column_usage where constraint_name='$keyName'";
        return DB::select(DB::raw($query));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table($this->tableName, function ($table) {

            $table->dropForeign(['role_slug']);
            $table->dropForeign(['permission_slug']);

            $table->dropColumn(['role_slug', 'permission_slug']);

            $table->unsignedInteger('role_id');
            $table->unsignedInteger('permission_id');
            $table->primary(['role_id', 'permission_id']);

            $table->foreign('role_id', 'acl_role_permission_ibfk_1')->references('id')->on('acl_roles');
            $table->foreign('permission_id', 'acl_role_permission_ibfk_2')->references('id')->on('acl_permissions');

        });

    }
}
