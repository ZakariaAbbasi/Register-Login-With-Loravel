<?php

namespace App\Models;

use App\Jobs\SendEmail;
use App\Mail\VerificationEmail;
use App\Mail\ResetPasswordEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Services\Auth\Traits\HasTwoFactor;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\Auth\Traits\MagicallyAuthenticable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, MagicallyAuthenticable, HasTwoFactor;
    // use HasApiTokens, HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'provider', 'provider_id', 'avatar', 'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification()
    {

        SendEmail::dispatch($this, new  VerificationEmail($this));
    }

    public function sendPasswordResetNotification($token)
    {
        SendEmail::dispatch($this, new  ResetPasswordEmail($this, $token));
    }
}
