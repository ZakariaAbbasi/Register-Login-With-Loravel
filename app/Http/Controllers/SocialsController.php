<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialsController extends Controller
{

    protected $providers = ['google', 'github'];

    public function redirectToProvider($driver)
    {
        if (!$this->isProviderAllowed($driver)) return redirect()->route('home');

        try {
            return Socialite::driver($driver)->redirect();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function handleProviderCallback($driver)
    {
        $user = Socialite::driver($driver)->user();

        Auth::login($this->loginAccount($user, $driver));

        return redirect()->route('home');
    }


    protected function loginAccount($user, $driver)
    {
        $providerUser = User::where('email', $user->getEmail())->first();

        if (!is_null($providerUser)) {

            $this->findOrUpdateUser($providerUser, $user, $driver);
            return $providerUser;
        }

        $this->findOrCreateUser($user, $driver);
    }

    protected function findOrUpdateUser($providerUser, $user, $driver)
    {
        return $providerUser->update([
            'provider' => $driver,
            'provider_id' => $user->getId(),
            'avatar' => $user->getAvatar(),
            'email_verified_at' => now(),
        ]);
    }

    protected function findOrCreateUser($user, $driver)
    {

        return User::create([
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'provider' => $driver,
            'provider_id' => $user->getId(),
            'avatar' => $user->getAvatar(),
            'email_verified_at' => now(),
        ]);
    }

    protected function isProviderAllowed($driver)
    {
        return in_array($driver, $this->providers) && config()->has("services.{$driver}");
    }
}
