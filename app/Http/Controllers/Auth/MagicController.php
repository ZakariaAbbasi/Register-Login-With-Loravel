<?php

namespace App\Http\Controllers\Auth;

use App\Models\LoginToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Auth\MagicAuthentication;

class MagicController extends Controller
{

    public function __construct(private MagicAuthentication $auth)
    {
        $this->middleware('guest');
    }


    public function showMagicForm()
    {
        return view('auth.magic-login');
    }

    public function sendToken(Request $request)
    {
        $this->validateForm($request);

        $this->auth->requestLink();

        return redirect()->back()->with('magicLinkSend', true);
    }

    protected function validateForm($request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users'],
        ]);
    }

    public function login(LoginToken $token)
    {
        return $this->auth->authenticate($token) === $this->auth::AUTHENTICATED
        ? redirect()->route('home')
        : redirect()->route('auth.magic.login.form')->with('invalidToken', true);
    }
}
