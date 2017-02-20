<?php

namespace Unified\Services\SitePortalAPI;

use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Unified\Http\Requests\Request;
use Unified\Models\User;

/**
 * Various authentication related functions for SitePortal to SiteGate communication
 *
 * @author ross.keatinge
 */
class Authentication
{

    /**
     * Standard Lavarel authentication
     *
     * @param type $username
     * @param type $password
     * @return boolean true on success
     */
    public function AuthenticateWithCredentials($username, $password)
    {
        $ok = false;

        if (!empty($username) && !empty($password)) {
            $ok = Auth::Attempt(['username' => $username, 'password' => $password]);
        }

        return $ok;
    }

    /**
     * Put token in a blacklist until it expires
     * Returns an array [0 => boolean true on success, 1 => error message 2 => http response code]
     *
     * @return array
     */
    public function disableToken()
    {
        $success = false;

        try {
            if ($user = JWTAuth::parseToken()->invalidate()) {
                $status = 200;
                $message = '';
                $success = true;
            } else {
                $status = 404;
                $message = 'User not found';
            }
        } catch (TokenExpiredException $e) {
            $status = $e->getStatusCode();
            $message = 'Token_expired';
        } catch (TokenInvalidException $e) {
            $status = $e->getStatusCode();
            $message = 'Invalid token';
        } catch (JWTException $e) {
            $status = $e->getStatusCode();
            $message = 'Absent token';
        }

        return [$success, $status, $message];
    }

    /**
     * Authenticate with JWT token from the request
     * Return error response on error or null on success.
     * Returns an array [0 => boolean true on success, 1 => error message 2 => http response code 3 => user]
     * @param Request $request
     * @return array
     */
    public function AuthenticateWithToken()
    {
        $success = false;
        $user = null;

        try {
            if ($user = JWTAuth::parseToken()->authenticate()) {
                $status = 200;
                $message = '';
                $success = true;
            } else {
                $status = 404;
                $message = 'user_not_found';
            }
        } catch (TokenExpiredException $e) {
            $status = $e->getStatusCode();
            $message = 'token_expired';
        } catch (TokenInvalidException $e) {
            $status = $e->getStatusCode();
            $message = 'token_invalid';
        } catch (JWTException $e) {
            $status = $e->getStatusCode();
            $message = 'token_absent';
        }

        return [$success, $status, $message, $user];
    }

    /**
     * Creates a new user to be used for API calls
     *
     * @param Request $request
     * @return array with new credentials
     */
    public function CreateNewAPIUser()
    {
        // generate a unique username
        // The chance of a duplicate is very low if str_random is any good but we might as well check.
        do {
            $apiUserName = 'api-' . str_random(8);
            $user = User::where(['username' => $apiUserName])->first();
        } while ($user !== null);

        $apiPassword = str_random(20);

        $user = new User();
        $user->username = $apiUserName;
        $user->password = bcrypt($apiPassword);
        $user->first_name = 'API';
        $user->last_name = $apiUserName;
        $user->email_address = 'sitegate@example.com';
        $user->role_id = 5;
        $user->home_node_id = 321;
        $user->save();

        return [
            'username' => $apiUserName,
            'password' => $apiPassword
        ];
    }

    /**
     * Get a new token by authenticating with username and password from request
     * Returns false if auth fails
     *
     * @param Request $request
     * @return false|string
     */
    public function GetNewToken($username, $password)
    {
        $success = false;
        $token = null;

        if ($username !== null && $password !== null) {

            // JWTAuth access the request
            $token = JWTAuth::attempt(['username' => $username, 'password' => $password]);

            if ($token !== false) {
                $success = true;
            }
        }

        return $success ? $token : false;
    }

}
