<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\UserRegitered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserRegitered  $event
     * @return void
     */
    public function handle(UserRegitered $event)
    {
         $event->user->sendEmailVerificationNotification();
    }
}
