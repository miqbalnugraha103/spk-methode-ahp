<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request) {

        $identity = $request->get("usernameOrEmail");
        $password = $request->get("password");
        $remember = ($request->has('rememberme')) ? true : false;

        return \Auth::attempt([
            filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $identity,
            'password' => $password
        ], $remember);
    }

    public function logout()
    {
        $this->guard()->logout();
        return redirect('/login');
    }

    public function username()
    {
        return 'usernameOrEmail';
    }

}
