<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PhpTimezoneAdditions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add column for php-friendly name of timezone
        if (!Schema::hasColumn('css_system_time_zones', 'php_name')) {
            Schema::table('css_system_time_zones', function($table) {
                $table->string('php_name', 40);
            });
        }


        // Insert PHP-friendly timezone names based on description
        $phpnames = [
            "(UTC-12:00) International Date Line West" => "Pacific/Wake",
            "(UTC-11:00) Samoa" =>  "Pacific/Apia",
            "(UTC-10:00) Hawaii" => "Pacific/Honolulu",
            "(UTC-09:00) Alaska" => "America/Anchorage",
            "(UTC-08:00)  Pacific Time - US & Canada" => "America/LosAngeles",
            "(UTC-07:00) Arizona" => "America/Phoenix",
            "(UTC-07:00) Chihuahua, La Paz, Mazatlan" => "America/Chihuahua",
            "(UTC-07:00) Mountain Time - US & Canada" => "America/Denver",
            "(UTC-06:00) Central America" => "America/Managua",
            "(UTC-06:00) Central Time - US & Canada" => "America/Chicago",
            "(UTC-06:00) Guadalajara, Mexico City, Monterrey" => "America/Mexico_City",
            "(UTC-06:00) Saskatchewan" => "America/Regina",
            "(UTC-05:00) Bogota, Lima, Quito" => "America/Bogota",
            "(UTC-05:00) Eastern Time - US & Canada" => "America/New_York",
            "(UTC-04:00) Atlantic Time (Canada)" => "America//Halifax",
            "(UTC-04:30) Carcaras" => "America/Caracas",
            "(UTC-04:00) Georgetown, La Paz, Manaus, San Juan" => "America/Argentina/Buenos_Aires",
            "(UTC-04:00) Santiago" => "America/Santiago",
            "(UTC-03:30) Newfoundland" => "America/St_Johns",
            "(UTC-03:00) Brasilia" => "America/Sao_Paulo",
            "(UTC-03:00) Buenos Aires" => "America/Argentina/Buenos_Aires",
            "(UTC-03:00) Greenland" => "America/Godthab",
            "(UTC-03:00) Montevideo" => "America/Godthab",
            "(UTC-02:00) Mid-Atlantic" => "America/Noronha",
            "(UTC-01:00) Azores" => "Atlantic/Azores",
            "(UTC-01:00) Cape Verde Is." => "Antlantic/Cape_Verde",
            "(UTC) Casablanca" => "Africa/Casablanca",
            "(UTC) Dublin, Edinburgh, Lisbon, London" => "Europe/London",
            "(UTC+1:00) Amsterdam, Berlin, Bern, Rome, Stockholm" => "Europe/Berlin",
            "(UTC+1:00) Belgrade, Bratislava, Budapest, Ljubljana" => "Europe/Belgrade",
            "(UTC+1:00) Brussels, Copenhagen, Madrid, Paris, Prague" => "Europe/Paris",
            "(UTC+1:00) Sarajeva,Skopje, Warsaw, Zagreb, Vienna" => "Europe/Sarajevo",
            "(UTC+2:00) Amman" => "Africa/Johannesburg",
            "(UTC+02:00) Athens, Bucharest, Istanbul" => "Europe/Istanbul",
            "(UTC+02:00) Beirut" => "Africa/Cairo",
            "(UTC+02:00) Cairo" => "Africa/Cairo",
            "(UTC+02:00) Harare, Pretoria" => "Africa/Johannesburg",
            "(UTC+02:00) Helsinki, Kyiv, Rig, Sofia, Tallin, Vilnius" => "Europe/Helsinki",
            "(UTC+02:00) Jerusalem" => "Asia/Jerusalem",
            "(UTC+02:00) Minsk" => "Europe/Istanbul",
            "(UTC+01:00) Windhoek" => "Africa/Windhoek",
            "(UTC+03:00) Baghdad" => "Asia/Baghdad",
            "(UTC+03:00) Kuwait, Riyadh" => "Asia/Kuwait",
            "(UTC+03:00)  Moscow, St. Petersburg, Volgograd" => "Europe/Moscow",
            "(UTC+03:00) Nairobi" => "Africa/Nairobi",
            "(UTC+04:00) Tbilisi" => "Asia/Tbilisi",
            "(UTC+03:30) Tehran" => "Asia/Tehran",
            "(UTC+04:00) Abu Dhabi, Muscat" => "Asia/Muscat",
            "(UTC+04:00) Baku" => "Asia/Baku",
            "(UTC+04:00) Yerevan" => "Asia/Yerevan",
            "(UTC+04:30) Kabul" => "Asia/Kabul",
            "(UTC+05:00) Ekaterinburg" => "Asia/Yekaterinburg",
            "(UTC+05:00)  Islamabad, Karachi" => "Asia/Karachi",
            "(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi" => "Asia/Kolkata",
            "Sri Jayawardenepura" => "Asia/Kolkata",
            "(UTC+05:45) Kathmandu" => "Asia/Kathmandu",
            "(UTC+06:00) Novosibirsk" => "Asia/Novosibirsk",
            "(UTC+06:00) Astana, Dhaka" => "Asia/Dhaka",
            "(UTC+06:30) Yangon (Rangoon)" => "Asia/Rangoon",
            "(UTC+07:00) Bangkok, Hanoi, Jakarta" => "Asia/Bangkok",
            "(UTC+07:00) Krasnoyarsk" => "Asia/Krasnoyarsk",
            "(UTC+08:00) Beijing, Chongping, Hong Kong, Urmaqi" => "Asia/Hong_Kong",
            "(UTC+08:00) Irkutsk" => "Asia/Irkutsk",
            "(UTC+08:00) Kuala Lumpur, Singapore" => "Asia/Singapore",
            "(UTC+08:00) Perth" => "Australia/Perth",
            "(UTC+08:00) Taipei" => "Asia/Taipei",
            "(UTC+09:00) Osaka, Sapporo, Tokyo" => "Asia/Tokyo",
            "(UTC+09:00) Seoul" => "Asia/Seoul",
            "(UTC+09:00) Yakutsk" => "Asia/Yakutsk",
            "(UTC+09:30) Adelaide" => "Australia/Adelaide",
            "(UTC+09:30) Darwin" => "Australia/Darwin",
            "(UTC+10:00) Brisbane" => "Australia/Brisbane",
            "(UTC+10:00) Canberra, Melbourne, Sydney" => "Australia/Sydney",
            "(UTC+10:00) Guam, Port Moresby" => "Pacifi/Guam",
            "(UTC+10:00) Hobart" => "Australia/Hobart",
            "(UTC+10:00) Vladivostok" => "Asia/Vladivostok",
            "(UTC+11:00) Solomon Is., New Caledonia" => "Asia/Magadan",
        ];

        foreach ($phpnames as $description => $phpname) {
            DB::table('css_system_time_zones')
                ->where('description', $description)
                ->update(['php_name' => $phpname]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // drop the php_name column
        Schema::table('css_system_time_zones', function($table) {
            $table->dropColumn('php_name');
        });
    }
}
