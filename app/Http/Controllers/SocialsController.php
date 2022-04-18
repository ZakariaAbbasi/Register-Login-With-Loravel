<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialsController extends Controller
{
    public function redirectToProvider($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function handleProviderCallback($driver)
    {
        $user =Socialite::driver($driver)->user();

        Auth::login($this->findOrCreateUser($user, $driver));

        return redirect()->route('home');


    }
    protected function findOrCreateUser($user, $driver)
    {
        $providerUser = User::where('email', $user->getEmail())->first();

        if (!is_null($providerUser)) return $providerUser;
 
        return User::create([
            'email' => $user->getEmail(),
            'name' =>$user->getName(),
            'provider' => $driver,
            'provider_id' => $user->getId(),
            'avatar' => $user->getAvatar(),
            'email_verified_at' => now(),
 
        ]);

    }
}
