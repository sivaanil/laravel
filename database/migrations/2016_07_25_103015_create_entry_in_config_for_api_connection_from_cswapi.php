<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntryInConfigForApiConnectionFromCswapi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $entry = \Unified\Models\CssGeneralConfig::getEntry('local_sitegate_api_credentials');
        if($entry == null) {
            $cswapiRoot = env('CSWAPI_ROOT'); // :`(
            require_once($cswapiRoot . '/common/class/cssEncryption.php');

            $newEntry = new \Unified\Models\CssGeneralConfig();
            $newEntry->setting_name = 'local_sitegate_api_credentials';
            $newEntry->var1 = "https://localhost";
            $newEntry->var2 = cssEncryption::getInstance()->Encrypt('api');
            $newEntry->var3 = '3976d7fbe75d6a89423e0ae79d24cd58';//TODO: hardcore encrypted hash until UN-736 is resolved
            $newEntry->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $entry = \Unified\Models\CssGeneralConfig::getEntry('sitegate_api_credentials');
        if($entry) {
            $entry->delete();
        }
    }
}
