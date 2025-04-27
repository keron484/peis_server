<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use illuminate\Support\Str;

class Otp extends Model
{
    //

    protected $fillable = [
        'id',
        'token_header',
        'actorable_id',
        'actorable_type',
        'otp',
        'expires_at',
        'used',
    ];


    public $table = 'otp';
    public $incrementing = false;
    public $keyType = 'string';

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function actorable()
    {
        return $this->morphTo();
    }

    public function isExpired()
    {
        return now()->greaterThan($this->expires_at);
    }
    public function scopeValid($query)
    {
        return $query->where('used', false)
                     ->where('expires_at', '>', now());
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
