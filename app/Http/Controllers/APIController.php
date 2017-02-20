<?php

namespace Unified\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Unified\Browser\BrowserManager;
use Unified\Services\SitePortalAPI\AlarmScan;
use Unified\Services\SitePortalAPI\Authentication;
use Unified\Services\SitePortalAPI\BuildServer;
use Unified\Services\SitePortalAPI\DeviceStatusScan;
use Unified\Services\SitePortalAPI\PropDefScan;
use Unified\Services\SitePortalAPI\PropScan;
use Unified\Services\SitePortalAPI\GeneratorPassthrough;
use Unified\Services\SitePortalAPI\DeviceWritePassthrough;
use Unified\Services\SitePortalAPI\SchedulerAPI;

/**
 * Provides the endpoint for SitePortal API calls.
 *
 * @author Ross Keatinge <ross.keatinge@csquaredsystems.com>
 */
class APIController extends Controller
{

    const API_VERSION = 250;
    const AUTH_BY_NONE = 1;
    const AUTH_BY_CREDENTIALS = 2;
    const AUTH_BY_TOKEN = 3;

    /**
     * Call the API using a route that looks like the old file path.
     * @param Request $request
     * @return string|Response
     */
    public function OldCall(Request $request)
    {
        // Permitted actions and their required authenication type
        // key is the proc parameter passed in the query string

        $actions = [
            'auth' => ['AuthenticateAndGetNewToken', self::AUTH_BY_NONE],
            'apiinfo' => ['GetAPIInfo', self::AUTH_BY_NONE],
            'newuser' => ['CreateAPIUser', self::AUTH_BY_CREDENTIALS],
            'browsedevice' => ['BrowseToDevice', self::AUTH_BY_CREDENTIALS],
            'generator_passthrough' => ['GeneratorPassthrough', self::AUTH_BY_TOKEN],
            'device_write_passthrough' => ['DeviceWritePassthrough', self::AUTH_BY_TOKEN],
            'scheduler_passthrough' => ['SchedulerPassthrough', self::AUTH_BY_TOKEN],
            'vf.sbd' => ['GetBuildData', self::AUTH_BY_TOKEN],
            'vf.sad' => ['AlarmScan', self::AUTH_BY_TOKEN],
            'vf.spd' => ['PropScan', self::AUTH_BY_TOKEN],
            'vf.sdd' => ['PropDefScan', self::AUTH_BY_TOKEN],
            'vf.dad' => ['DeviceStatusScan', self::AUTH_BY_TOKEN],
        ];

        $action = $request->input('proc');

        if ($action === null || !isset($actions[$action])) {
            return response('Invalid request', 400);
        }

        $method = $actions[$action][0];
        $authType = $actions[$action][1];

        // authenticate if required
        switch ($authType) {
            case self::AUTH_BY_CREDENTIALS:

                if (!$this->AuthenticateWithCredentials($request)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }

                break;
            /*
             * Temporarily disable authenticate by token

              case self::AUTH_BY_TOKEN:
              list($success, $status, $message) = $this->AuthenticateWithToken($request);

              if (!$success) {
              return response()->json([$message], $status);
              }

              break;
             */
        }

        // These methods return either an array which Laravel will json encode
        // or a response object. The response object is used for errors.
        $response = $this->$method($request);

        // if we returning an array (i.e., a good response) then add the api version
        if (is_array($response)) {
            $response['version'] = self::API_VERSION;
        }

        return $response;
    }

    /**
     * Authenticate with a username and password
     * @param Request $request
     * @return boolean true for success
     */
    private function AuthenticateWithCredentials(Request $request)
    {
        $service = new Authentication();
        return $service->AuthenticateWithCredentials($request->input('username'), $request->input('password'));
    }

    /**
     * Authenticate with user name and password
     * Return response object for error or array with token on success
     *
     * @param Request $request
     * @return Response|array
     */
    private function AuthenticateAndGetNewToken(Request $request)
    {
        $service = new Authentication();
        $token = $service->GetNewToken($request->input('username'), $request->input('password'));

        if ($token === false) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        return ['token' => $token];
    }

    /**
     * Try to authenticate with JWT token.  Uses JWTAuth which accesses the request.
     * Returns an array [0 => boolean true on success, 1 => error message 2 => http response code]
     *
     * @return array
     */
    private function AuthenticateWithToken()
    {
        $service = new Authentication();
        return $service->AuthenticateWithToken();
    }

    /**
     * Create a new user with random password. Used during build.
     * Returns an array of username and password
     *
     * @param Request $request
     * @return type
     */
    private function CreateAPIUser(Request $request)
    {
        $service = new Authentication();
        return $service->CreateNewAPIUser();
        ;
    }

    /**
     * Return API version.
     *
     * @param Request $request
     * @return Response
     */
    private function GetAPIInfo(Request $request)
    {
        return response()->json(['version' => self::API_VERSION]);
    }

    /**
     * Get device data for building.
     *
     * @param type $request
     * @return array
     */
    private function GetBuildData($request)
    {
        $service = new BuildServer();
        return $service->handle($request->input('nodeId'));
    }

    /**
     * Get alarm data
     *
     * @param type $request
     * @return array
     */
    private function AlarmScan($request)
    {
        $service = new AlarmScan();
        return $service->handle($request->input('nodeId'), $request->input('timestamp'), $request->input('limit'));
    }

    /**
     * Get device property data
     *
     * @param type $request
     * @return array
     */
    private function PropScan($request)
    {
        $service = new PropScan();
        return $service->handle($request->input('nodeId'), $request->input('timestamp'), $request->input('limit'));
    }

    /**
     * Get device property def data
     *
     * @param type $request
     * @return array
     */
    private function PropDefScan($request)
    {
        $service = new PropDefScan();
        return $service->handle($request->input('nodeId'), $request->input('timestamp'), $request->input('limit'));
    }

    /**
     * Get device status (last scan times etc)
     *
     * @param type $request
     * @return array
     */
    private function DeviceStatusScan($request)
    {
        $service = new DeviceStatusScan();
        return $service->handle($request->input('nodeId'));
    }

    /**
     * Get the Guacamole URL for a device behind the SiteGate
     * @param Request $request
     * @return array on success, Response on error
     */
    private function BrowseToDevice(Request $request)
    {
        $nodeId = $request->input('nodeId');

        if (!is_numeric($nodeId)) {
            return response()->json(['invalid_node_id'], 400);
        }

        $sessionId = $request->input('sessionId');

        if (strlen($sessionId) < 20) {
            return response()->json(['invalid_session_id'], 400);
        }

        $browserManager = new BrowserManager();
        $url = $browserManager->GetGuacUrlForNode($nodeId, $sessionId);

        if ($url) {
            return ['url' => $url];
        } else {
            return response()->json(['no_url'], 400);
        }
    }

    private function GeneratorPassthrough(Request $request)
    {

        $generators = json_decode($request->get('generators'));
        $duration = $request->get('duration');
        $newSetting = $request->get('new_setting');

        $generatorManager = new GeneratorPassthrough();
        $result = $generatorManager->handle($generators, $duration, $newSetting);
        return ['result' => $result];
    }

    private function DeviceWritePassthrough(Request $request)
    {

        $newProps = json_decode($request->get('new_properties'));
        $nodeID = $request->get('node_id');
        $userID = $request->get('user_id');

        $generatorManager = new DeviceWritePassthrough();
        $result = $generatorManager->handle($newProps, $nodeID, $userID);
        return ['result' => $result];
    }

    private function SchedulerPassthrough(Request $request)
    {

        $schedule = json_decode($request->get('schedule'));
        $execution = $request->get('execution');

        $scheduler = new SchedulerAPI();
        $result = $scheduler->handle($schedule, $execution);
        return ['result' => $result];
    }
}
