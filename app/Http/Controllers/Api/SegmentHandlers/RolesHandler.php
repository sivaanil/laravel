<?php
namespace Unified\Http\Controllers\Api\SegmentHandlers;

use Illuminate\Http\Request;
use Unified\Http\Controllers\Api\SegmentHandler;

/**
 * Roles Handler
 * 
 * Handles the class routing for /roles calls.
 * 
 * @author Bret Sheeley <bret.sheeley@csquaredsystems.com>
 */
class RolesHandler extends SegmentHandler
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
        if (is_null($request->segment($segment))) {
            // if the segment after "/roles" is empty, use the "/roles" resource
            return 'Unified\Http\Controllers\Api\ResourceProcessors\RolesProcessor';
        } elseif (is_null($request->segment($segment+1))) {
            // if there is only one segment after "/roles", then use the "/roles/{$id}" resource
            return 'Unified\Http\Controllers\Api\ResourceProcessors\RolesIdProcessor';
        }
        
        // otherwise return nothing
        return null;
    }
}
