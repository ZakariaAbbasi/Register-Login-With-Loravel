<?php

namespace App\Services\Notifications;

use App\Services\Notifications\Contracts\ProviderInterface;
use App\Services\Notifications\Exceptions\ClassDoesNotProviderException;

/**
 * @method sendEmail(\App\Models\User $user, \Illuminate\Mail\Mailable $malable)
 * @method sendSms(\App\Models\User $user, string $text)
 */

class Notification
{


    public function __call($method, $args)
    {
        $providerPath = '\App\Services\Notifications\Providers\\' . substr($method, 4) . 'Provider';

        if (!class_exists($providerPath)) {
            throw new ClassDoesNotProviderException();
        }
        $providerInstance = new $providerPath(...$args);

        if (!is_subclass_of($providerInstance, ProviderInterface::class)) {
            throw new \Exception('class must implements App\Services\Notifications\Contracts\ProviderInterface');
        }

        return $providerInstance->send();
    }
}
