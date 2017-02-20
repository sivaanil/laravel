<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShjAdditionalTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $scripts = [
            'B8316_A.sql',
            'B8316_B.sql',
        ];
        if (env('C2_SERVER_TYPE') == 'sitegate') {
            foreach ($scripts as $script) {
                $filename = base_path().'/database/siteportal/'.$script;
                $command = 'mysql -u '.env('DB_ROOT_USERNAME').' -p'.env('DB_ROOT_PASSWORD')." ".env('DB_DATABASE')." < $filename";
    	        exec($command);
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
