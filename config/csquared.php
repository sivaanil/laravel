<?php

return [

    /*
     |--------------------------------------------------------------------------
     | SiteGate Specific Configuration Variables
     |--------------------------------------------------------------------------
     |
     | Specific configurations about the sitegate
     |
    */
    'sitegate' => [
        /*
         |--------------------------------------------------------------------------
         | Sitegate Version Configuration Variables
         |--------------------------------------------------------------------------
         |
         |All config settings for the SiteGates Version
         |
         */
        'version' => [
            // Current Sitegate version that is running on the system
            'current' => '0.0.0.0',
            // Version Sitegate will be upgraded to if the upgrade check
            // returns a new firmware to be install else it will be null
            'upgrade' => null
            // Upgrade Release Type

        ],
    ],
    'dmons'    => [
        'connection' => [
            'ip'     => env('DMONS_IP'),
            'port'   => env('DMONS_PORT'),
            'prefix' => env('DMONS_URI_PREFIX')
        ],
        'auth'       => [
            'public'  => '',
            'private' => '',
            'token'   => '',
        ],
        'version'    => "dmons/api/" . env('DMONS_VERSION') . "/"
    ]

];

