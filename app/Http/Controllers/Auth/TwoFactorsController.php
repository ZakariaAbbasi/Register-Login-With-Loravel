<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\TwoFactor;
use Illuminate\Http\Request;
use App\Http\Requests\CodeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Auth\TwoFactorAuthentication;

class TwoFactorsController extends Controller
{

    public function __construct(private TwoFactorAuthentication $two_factor)
    {
        $this->middleware('auth')->except('resent');
    }

    public function toggle()
    {
        return view('auth.two-factor.toggle');
    }

    public function activate()
    {
        $response = $this->two_factor->requestCode(Auth::user());

        return $response == $this->two_factor::CODE_SEND
            ? redirect()->route('auth.two.factor.code.form')
            : back()->with('cantSendCode', true);
    }

    public function showEnterCodeForm()
    {
        return view('auth.two-factor.enter-code');
    }

    public function confirmCode(CodeRequest $request)
    {
       $response = $this->two_factor->activate();
        return $response == $this->two_factor::ACTIVATED
        ? redirect()->route('home')->with('twoFactorActivated', true)
        : back()->with('invalidCode', true);
    }



    public function deactivate()
    {
        $this->two_factor->deactivate(Auth::user());

        return redirect()->back()->with('twoFactorDeactivated', true);
        
    }

    public function resent()
    {
        $this->two_factor->resent();

        return back()->with('codeResent', true);
    }

}
