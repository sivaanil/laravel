<?php namespace Unified\Config;

use Illuminate\Log\Writer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\ConfigureLogging as BaseLoggingConfiguration;

/**
 * Overrides default log config to generate unique logs per user.
 * This could also be used to change the location of the logs.
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class ConfigureLogging extends BaseLoggingConfiguration
{
    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureSingleHandler(Application $app, Writer $log)
    {
        $date = date('Y-m-d');
        $logDir = $app->storagePath() . '/logs/';

        if (!file_exists($logDir . $date)) {
            mkdir($logDir . $date);
            chmod($logDir.$date, 0777);
        }

        if (function_exists('posix_getpwuid')) {
            $processUser = posix_getpwuid(posix_geteuid());
            $userName = $processUser['name'];
        } else {
            $userName = 'unknown';  // this allows artisan commands to run on Windows
        }

        $log->useFiles($logDir . $date . '/' . $userName . '-' . $app->environment() . '-laravel.log');
    }
}
