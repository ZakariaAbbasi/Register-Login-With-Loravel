<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    // use ThrottlesLogins;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        #validation
        $this->validateForm($request);
        
        #check user and pass

        if ($this->attemptLogin($request)) {
            return $this->sendLoginSuccessResponse();
        }
        return $this->sendLoginFailedResponse();
    }

    protected function validateForm(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:users',
                'password' => 'required',
            ]
        );
    }

    protected function attemptLogin(Request $request)
    {
        return Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'));
    }

    protected function sendLoginSuccessResponse()
    {
        session()->regenerate();
        return redirect()->intended();
    }

    protected function sendLoginFailedResponse()
    {
        return back()->with('wrongCredentials', true);
    }

    public function logout()
    {
        session()->invalidate();

        Auth::logout();
        return redirect()->route('home');
    }
}
