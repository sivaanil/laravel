<?php
// set environment variables that we don't want in the public .env file
// in the future, it may be preferable to move these to an app/Config file
putenv('DB_PASSWORD=[[DB_PASSWORD]]');
putenv('TRAP_PASSWORD=[[TRAP_PASSWORD]]');

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__ . '/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    'Illuminate\Contracts\Http\Kernel',
    'Unified\Http\Kernel'
);

$app->singleton(
    'Illuminate\Contracts\Console\Kernel',
    'Unified\Console\Kernel'
);

$app->singleton(
    'Illuminate\Contracts\Debug\ExceptionHandler',
    'Unified\Exceptions\Handler'
);


//$app->singleton(
//    'Illuminate\Foundation\Bootstrap\ConfigureLogging',
//    'Bootstrap\ConfigureLogging'
//);
/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
