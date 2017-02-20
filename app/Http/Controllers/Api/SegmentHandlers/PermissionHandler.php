<?php
namespace Unified\Http\Controllers\Api\SegmentHandlers;

use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\SegmentHandler;

/**
 * Permission Handler
 * 
 * Handles the class routing for /permission calls.
 * 
 * @author Bret Sheeley <bret.sheeley@csquaredsystems.com>
 */
class PermissionHandler extends SegmentHandler
{  
    /**
     * getHandler
     * 
     * Return the processor/handler to be used based on the request path supplied.
     * 
     * @param Request $request
     * @param type $segment
     * 
     * @return string|null Classname of processor or subhandler selected.
     */
    protected function getHandler(Request $request, $segment)
    {
        if ($request->segment($segment) == null) {
            // if the segment after "/roles" is empty, use the "/permissions" resource
            return 'Unified\Http\Controllers\Api\ResourceProcessors\PermissionsProcessor';
        } elseif ($request->segment($segment+1) == null) {
            // if there is only one segment after "/roles", then use the "/permissions/{$id}" resource
            return 'Unified\Http\Controllers\Api\ResourceProcessors\PermissionsIdProcessor';
        }
        
        // otherwise return nothing
        return null;
    }
}
