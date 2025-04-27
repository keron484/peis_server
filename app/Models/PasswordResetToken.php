<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class PasswordResetToken extends Model
{
    protected $fillable = [
        'token',
        'actorable_id',
        'actorable_type',
        'expires_at'
    ];

    public $keyType = 'string';
    public $table = 'password_reset_tokens';
    public $incrementing = false;

    public function isExpired()
    {
        return now()->greaterThan($this->expires_at);
    }

    public function actorable()
    {
        return $this->morphTo();
    }
    protected static function boot()
    {
        parent::boot();

         static::creating(function ($user){
            $uuid = str_replace('-', '', Str::uuid()->toString());
            $user->id = substr($uuid, 0, 25);
         });

    }
}
