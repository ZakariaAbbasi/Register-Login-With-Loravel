<?php

namespace App\Models;

use App\Models\User;
use App\Jobs\SendSms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TwoFactor extends Model
{
    use HasFactory;

    const CODE_EXPAIRY = 60; // seconds

    protected $table = 'two_factors';

    protected $fillable = ['code', 'user_id'];

    public static function generateCodeFor(User $user)
    {
        $user->code()->delete();

        return static::create([
            'user_id' => $user->id,
            'code' => mt_rand(1000, 9999),
        ]);
    }

    protected function  getCodeForSendAttribute()
    {
        return __('auth.codeForSend', ['code' => $this->code]);
    }

    public function send()
    {        
        SendSms::dispatch($this->user, $this->code_for_send);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpaired()
    {
        return $this->created_at->diffInSeconds(now()) > static::CODE_EXPAIRY;
    }

    public function isEqualWith(string $code)
    {
        return $this->code == $code;
    }


}

