<?php

namespace App\Mail;

use App\Models\LoginToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMagicLink extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private LoginToken $token, private array $options)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.magic-link')->with(
            [
                'link' => $this->biuldLink(),
                'name' => $this->token->user->name,
            ]
        );
    }

    protected function biuldLink()
    {
        return route('auth.magic.login', [
            'token' => $this->token->token,
        ] + $this->options);
    }
}
