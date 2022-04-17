<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private User $user, private string $text)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Notification $notification)
    {
        return $notification->sendSms($this->user, $this->text);
    }
}
