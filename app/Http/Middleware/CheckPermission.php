<?php

namespace Unified\Http\Middleware;
use Closure;
use Log;
use App;

/**
 * Exists as a piece to check permissions for an action
 * by the currently logged in user.
 *
 * @author Anthony Levensalor <anthony.levensalor@csquaredsystems.com>
 */
class CheckPermission {
    
    /**
     * Handle a request requiring permissions to execute
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param [\App\Permission\Permission $permission=null]
     * @return mixed 
     */
    public function handle($request, Closure $next, $permission = null) {
        Log::info('Permission: ' . $permission);
 
        if (!app('Illuminate\Contracts\Auth\Guard')->guest()
            && !is_null($permission)
        ) {
            $hasPermission = $request->user()->can($permission);
            Log::info("hasPermission: " .var_export($hasPermission, true));
            if ($hasPermission) {
                // Pass through, access granted
                return $next($request);
            }
        }      

        // Display a different denied message for Ajax vs direct access
        if ($request->ajax) {
            return response('Unauthorized', 401);
        } else {
            App::abort('401', 'You do not have permission to do that');
        }
    }

}
