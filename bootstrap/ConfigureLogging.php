<?php namespace Bootstrap;

/**
 * Developer: Ryan Jarnutowski
 * Email: 'Ryan Jarnutowski <Ryan.Jarnutowski@csquaredsystems.com>'
 * Project: unified-sitegate
 * File: ConfigureLogging.php
 * Date: 7/30/2015
 */

use Monolog\Logger as Monolog;
use Monolog\Formatter\ScalarFormatter;
use Illuminate\Log\Writer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\ConfigureLogging as BaseConfigureLogging;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\WebProcessor;


class ConfigureLogging extends BaseConfigureLogging
{
    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @param  \Illuminate\Log\Writer                       $log
     *
     * @return void
     */
    /**
     * OVERRIDE PARENT
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @param  \Illuminate\Log\Writer                       $log
     *
     * @return void
     */
    protected function configureHandlers(Application $app, Writer $log)
    {

        $bubble = false;

        // Stream Handlers
        $debugStreamHandler = new StreamHandler(storage_path("/logs/laravel_debug.log"), Monolog::DEBUG, $bubble);
        //$debugStreamHandler->setFormatter(new ScalarFormatter());
        $infoStreamHandler = new StreamHandler(storage_path("/logs/laravel_info.log"), Monolog::INFO, $bubble);
        //$infoStreamHandler->setFormatter(new ScalarFormatter());
        $warningStreamHandler = new StreamHandler(storage_path("/logs/laravel_warning.log"), Monolog::WARNING, $bubble);
        //$warningStreamHandler->setFormatter(new ScalarFormatter());
        $errorStreamHandler = new StreamHandler(storage_path("/logs/laravel_error.log"), Monolog::ERROR, $bubble);
        //$errorStreamHandler->setFormatter(new ScalarFormatter());

        // Get monolog instance and push handlers
        $monolog = $log->getMonolog();
        $monolog->pushHandler($debugStreamHandler);
        $monolog->pushHandler($infoStreamHandler);
        $monolog->pushHandler($warningStreamHandler);
        $monolog->pushHandler($errorStreamHandler);
        $monolog->pushProcessor(new WebProcessor());
        $monolog->pushProcessor(new MemoryUsageProcessor());


        $log->useDailyFiles($app->storagePath() . '/logs/daily.log');
    }
}