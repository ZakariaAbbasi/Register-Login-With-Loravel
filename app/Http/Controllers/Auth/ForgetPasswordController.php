<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;

class ForgetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showForgetForm()
    {
        return view('auth.forget-password');
    }

    public function sendResetLink(Request $request)
    {
        #validation
        $this->validateForm($request);

        #create link
        #send link
        $response = Password::broker()->sendResetLink($request->only('email'));
        if ($response == Password::RESET_LINK_SENT) {
            return back()->with('resetLinkSend', true);
        }
        return back()->with('resetLinkFailed', true);

    }
    
    protected function validateForm($request)
    {
        $request->validate(
            [
                'email' => ['required', 'email', 'exists:users'],
            ]
        );
    }
}
