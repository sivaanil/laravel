<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cswapi73To75 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $seven_five_scripts = [
            '73_to_75.sql',
            '7-5-RC8-scripts.sql',
        ];
        if (env('C2_SERVER_TYPE') == 'sitegate') {
            foreach ($seven_five_scripts as $script) {
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
