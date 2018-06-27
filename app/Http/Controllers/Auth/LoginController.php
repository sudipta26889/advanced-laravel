<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Entity;
use App\Models\Language;
use App\Helpers\Helper;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request) {
        $this->parseRequest($request);
        $responseWithData = array(
            'canLoginFromWeb' => true
        );
        return $this->returnView('auth.login', $responseWithData);
    }

    public function login(Request $request) {
        $this->parseRequest($request);
        $this->validateLogin($request);
        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    protected function attemptLogin(Request $request) {
        $attemptOption = $this->credentials($request);
        $request->filled('remember');
        $attemptOption['entity_id'] = $this->entity->id;
        return $this->guard()->attempt(
            $attemptOption
        );
    }

    protected function validateLogin(Request $request) {
        $this->parseRequest($request);
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string'
        ]);
    }
}
