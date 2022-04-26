<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\LoginToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MagicAuthentication
{

    const INVALID_TOKEN = 'invalid.token';
    const AUTHENTICATED = 'authenticated';

    public function __construct(protected Request $request)
    {
    }

    public function requestLink()
    {
        $user = $this->getUser();

        $token = $user->createToken()->send(
            [
                'remember' => $this->request->has('remember'),
                'email' => $user->email,
            ]
        );
    }

    protected function getUser()
    {
        return User::where('email', $this->request->email)->firstOrFail();
    }

    public function authenticate(LoginToken $token)
    {

        $token->delete();

        # validation token
        
        if ($token->isExpaired()) {
            return self::INVALID_TOKEN;
        }

        # login
        Auth::login($token->user, $this->request->query('remember'));
        
        # return response

        return self::AUTHENTICATED;
    }
}
