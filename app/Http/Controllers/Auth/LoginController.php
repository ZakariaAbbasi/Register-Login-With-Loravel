<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;
use App\Http\Requests\CodeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Services\Auth\TwoFactorAuthentication;
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
    public function __construct(private TwoFactorAuthentication $twoFactor)
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showCodeForm()
    {
        return view('auth.two-factor.login-code');
    }

    public function login(Request $request)
    {
        #validation
        $this->validateForm($request);

        #check user and pass
        if (!$this->isValidCredential($request)) {
            return $this->sendLoginFailedResponse();
        }

        $user = $this->getUser($request);

        if ($user->hasTwoFactor()) {
            $this->twoFactor->requestCode($user);
            return $this->sendHasTwoFactorResponse();
        }

        Auth::login($user, $request->remember);
        return $this->sendLoginSuccessResponse();
    }

    public function confirmCode(CodeRequest $request)
    {
        $response = $this->twoFactor->login();

        return $response === $this->twoFactor::AUTHENTICATED
            ? $this->sendLoginSuccessResponse()
            : back()->with('invalidCode', true);
    }

    protected function validateForm(Request $request)
    {
        $request->validate(
            [
                'email' => ['required', 'email', 'exists:users'],
                'password' => ['required'],
                // 'g-recaptcha-response' => ['required', new Recaptcha],
            ],
            [
                'g-recaptcha-response.required' => __('auth.recaptcha'),
            ]
        );
    }

    protected function isValidCredential($request)
    {
        return Auth::validate($request->only(['email', 'password']));
    }

    protected function getUser($request)
    {
        return User::where('email', $request->email)->firstOrFail();
    }

    protected function sendHasTwoFactorResponse()
    {
        return redirect()->route('auth.login.code.form');
    }

    // protected function attemptLogin(Request $request)
    // {
    //     return Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'));
    // }

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
