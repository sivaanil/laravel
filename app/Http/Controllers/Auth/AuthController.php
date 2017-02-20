<?php namespace Unified\Http\Controllers\Auth;

use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Session;
use Unified\Browser\BrowserManager;
use Unified\Http\Controllers\Controller;

class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    protected $loginPath = '/login';

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @param  Guard     $auth
     * @param  Registrar $registrar
     *
     * @return void
     */
    public function __construct(Guard $auth, Registrar $registrar)
    {
        //$this->auth = $auth;
        //$this->registrar = $registrar;

        //$this->middleware('guest', ['except' => 'getLogout']);
    }

    public function authenticate(Request $request)
    {
        $username = Input::get('username');
        $password = Input::get('password');

        $user = [
            'username' => $username,
            'password' => $password
        ];

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        if (Auth::attempt(['username' => $username, 'password' => $password, 'force_pwd_change' => 1])) {
            return Redirect::route('reset')->with([
                'flash_error' => "Password expired, Please reset.",
                'username'    => $username
            ]);
        } elseif (Auth::attempt(['username' => $username, 'password' => $password, 'force_pwd_change' => 0])) {
            return Redirect::route('home');
        } else {
            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            if ($throttles) {
                $this->incrementLoginAttempts($request);
            }

            return Redirect::route('root')->with([
                'flash_error' => 'Incorrect Username/Password',
                'attempt'     => Session::get('attempt')
            ]);
        }
    }

    public function logout(BrowserManager  $browserManager)
    {
        $browserManager->ExpireSlots(session()->getId());
        Auth::logout();

        return Redirect::route('root')->with('flash_notice', 'You are now logged out');
    }

    public function postReset()
    {
        if (Auth::attempt(['username' => Input::get('username'), 'password' => Input::get('old_password'), 'force_pwd_change' => 1])) {
            $user = Auth::user();
            $user->password = Hash::make(Input::get('new_password'));
            $user->pwd_modified_date = Carbon::now();
            $user->force_pwd_change = 0;
            $user->save();
            return Redirect::route('home')->with(['username' => Input::get('username'), 'password' => Input::get('new_password')]);
        } else {
            return Redirect::route('reset')->with([
                'flash_error' => "An error has occured, please try again.",
                'username'    => Input::get('username')
            ]);
        }
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = app(RateLimiter::class)->availableIn(
            $this->getThrottleKey($request)
        );

        return Redirect::route('root')->with([
            'flash_error' => $this->getLockoutErrorMessage($seconds),
            'username'    => Input::get('username')
        ]);

    }
}
