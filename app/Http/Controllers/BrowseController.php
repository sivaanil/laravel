<?php

namespace Unified\Http\Controllers;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Unified\Browser\BrowserManager;
use Illuminate\Http\Request;

/**
 * Provides a few functions for the Gucamole browser
 *
 * @author ross.keatinge
 */
class BrowseController extends Controller
{

    /**
     * Redirect to the Guacamole login page.
     * The cookies are read by Javascript and used to login.
     * 
     * @param string $guacUserName
     *      Random user name generated by BrowserManager::CreateUserAndConnection
     * @param string $guacPassword
     *      Random password generated by BrowserManager::CreateUserAndConnection
     * @param string $connectionId
     *      The id used for PingConnection below
     * @return RedirectResponse
     */
    public function LaunchBrowser($guacUserName, $guacPassword, $connectionId)
    {
        // The GUAC_ADMIN cookie is used by Guacamole itself
        // We expire it to ensure that we are forced to the login page to start a new session

        return redirect("/guacamole/index.html")
                        ->withCookie('guacuser', $guacUserName, 0, '/guacamole/', null, false, false)
                        ->withCookie('guacpwd', $guacPassword, 0, '/guacamole/', null, false, false)
                        ->withCookie('guaccid', $connectionId, 0, '/guacamole/', null, false, false)
                        ->withCookie('GUAC_AUTH', '', -1000, '/guacamole/', null, false, false);
    }

    /**
     * Receive the "ping" from Javascript on the page telling us that the window is still active.
     * @param \Unified\Http\Controllers\Request $request
     * @param \Unified\Http\Controllers\BrowserManager $bManager
     * @return type
     */
    public function PingConnection(Request $request, BrowserManager $bManager)
    {
        $id = $request->input('id');
        return $bManager->PingConnection($id);
    }

}
