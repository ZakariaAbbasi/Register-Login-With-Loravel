<?php

namespace App\Services\Notifications\Providers;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use App\Services\Notifications\Contracts\ProviderInterface;

class EmailProvider implements ProviderInterface
{
    public function __construct(private User $user, private Mailable $mailable)
    {
        # code...
    }

    public function send()
    {
        return Mail::to($this->user)->send($this->mailable);
    }
}
