<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Support\Str;
class GrandPriceWinners extends Model
{
    //
    protected $fillable = [
        'user_id',
        'campaign_id',
        'winnings',
    ];

    protected $table = 'grand_price_winners';
    public $incrementing = 'false';
    public $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

         static::creating(function ($user){
            $uuid = str_replace('-', '', Str::uuid()->toString());
            $user->id = substr($uuid, 0, 25);
         });

    }
}
