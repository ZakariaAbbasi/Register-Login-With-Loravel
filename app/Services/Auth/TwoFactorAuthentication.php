<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\TwoFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthentication
{
    const CODE_SEND = 'code.send';
    const INVALID_CODE = 'invalid.code';
    const ACTIVATED = 'code.activated';
    const AUTHENTICATED = 'code.authenticated';

    protected $code;


    public function __construct(protected Request $request)
    {
        # code...
    }

    public function requestCode(User $user)
    {
        $code = TwoFactor::generateCodeFor($user);

        $this->setSession($code);

        $code->send();

        return static::CODE_SEND;
    }


    protected function setSession(TwoFactor $code)
    {
        session(
            [
                'code_id' => $code->id,
                'user_id' => $code->user_id,
                'remember' => $this->request->remember,
            ]
        );
    }


    public function activate()
    {
        # validation code
        if (!$this->isValidCode()) return  static::INVALID_CODE;

        # delete code
        $this->getToken()->delete();


        # activ two factor
        $this->getUser()->activateTwoFactor();

        $this->forgetSession();

        # return response
        return static::ACTIVATED;
    }

    public function login()
    {
        # validation code
        if (!$this->isValidCode()) return  static::INVALID_CODE;

        # delete code
        $this->getToken()->delete();
        Auth::login($this->getUser(), session('remember'));

        $this->forgetSession();
        return static::AUTHENTICATED;
    }

    public function resent()
    {
        return $this->requestCode($this->getUser());
    }

    protected function forgetSession()
    {
        session(['user_id', 'code_id', 'remember']);
    }

    protected function isValidCode()
    {
        return !$this->getToken()->isExpaired() && $this->getToken()->isEqualWith($this->request->code);
    }

    protected function getToken()
    {

        return $this->code ?? $this->code = TwoFactor::findOrfail(session('code_id'));
    }


    protected function getUser()
    {
        return User::findOrfail(session('user_id'));
    }


    public function deactivate(User $user)
    {
        return $user->deactivateTwoFacotor();
    }
}
