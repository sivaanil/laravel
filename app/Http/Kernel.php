<?php

namespace Unified\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Foundation\Application;

class Kernel extends HttpKernel
{

    /**
     * Create a new HTTP kernel instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function __construct(Application $app, Router $router)
    {
        parent::__construct($app, $router);

        // replace the log config with our own
        array_walk($this->bootstrappers, function(&$bootstrapper) {
            if ($bootstrapper === 'Illuminate\Foundation\Bootstrap\ConfigureLogging') {
                $bootstrapper = 'Unified\Config\ConfigureLogging';
            }
        });
    }

    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        'Unified\Http\Middleware\DetectReboot',
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Unified\Http\Middleware\EncryptCookies',
        'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
        'Illuminate\Session\Middleware\StartSession',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
        'Unified\Http\Middleware\VerifyCsrfToken',        
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'       => 'Unified\Http\Middleware\Authenticate',
        'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
        'guest'      => 'Unified\Http\Middleware\RedirectIfAuthenticated',
        'servertype' => 'Unified\Http\Middleware\ServerType',
        'acl'        => 'Unified\Http\Middleware\CheckPermission',
    ];

}
