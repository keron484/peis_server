<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Support\Str;
class PricesCategory extends Model
{
    //
    protected $fillable = [
       'name',
       'winnings',
       'campaign_id',
    ];

    public $table = 'prices_category';
    public $incrementing = false;
    public $keyType = 'string';

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
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
