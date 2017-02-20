<?php

namespace Unified\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DetectReboot
{

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // file is created by @reboot cron
        $flagFile = storage_path('framework/rebooted');

        if (file_exists($flagFile)) {
            $service = App::make('\Unified\Services\Reboot');

            if (!$service->Process()) {
                // Either the database is not uo yet
                // or another process just beat us to processing the reboot
                // 590 is our invented response code
                // Shows a custom error view.
                throw new HttpException(590);
            }
        }

        return $next($request);
    }

}
