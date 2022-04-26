<?php

namespace App\Models;

use App\Models\User;
use App\Jobs\SendEmail;
use App\Mail\SendMagicLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginToken extends Model
{
    use HasFactory;

    private const TOKEN_EXPAIRY = 10;
    
    protected $fillable = ['token'];

    public function getRouteKeyName()
    {
        return 'token';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function send(array $options)
    {
        SendEmail::dispatch($this->user, new SendMagicLink($this, $options));
    }

    public function isExpaired()
    {
        return $this->created_at->diffInSeconds(now()) > self::TOKEN_EXPAIRY;
    }

    public function scopeExpaired($query)
    {
        return $query->where('created_at', '<', now()->subSeconds(self::TOKEN_EXPAIRY));
    }
}
