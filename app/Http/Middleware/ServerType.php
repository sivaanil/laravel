<?php namespace Unified\Http\Middleware;

use Closure;

class ServerType
{

    public function handle($request, Closure $next, $serverType)
    {
        // if the required server type != the environment server type, deny access
        if ($serverType != env('C2_SERVER_TYPE')) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                //return redirect()->guest('/');
                return response('Unauthorized.', 401);
            }
        }
        return $next($request);
    }

}
