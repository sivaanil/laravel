<?php
use Illuminate\Database\Migrations\Migration;

class AddCreateAndUpdateDatesToPermissions extends Migration
{
    private $tableName = 'acl_permissions';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function ($table) {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('updated_at');
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     * 
     * @return void
     */
    public function down()
    {
        Schema::table($this->tableName, function ($table) {
      //      $table->dropIndex('acl_permissions_updated_at_index');
            $table->dropColumn(['created_at', 'updated_at', 'deleted']);
        });
    }
}
