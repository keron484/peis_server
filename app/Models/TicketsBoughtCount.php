<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketsBoughtCount extends Model
{
    //
    protected $fillable = [
        'tickets_bought_count',
        'total_spent',
        'total_winnings',
        'user_id',
        'campaign_id'
    ];

    public $incrementing = 'false';
    public $keyType = 'false';
    public $table = 'user_tickets_bought_count';

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function campaign(): BelongsTo {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $uuid = str_replace('-', '', \Illuminate\Support\Str::uuid()->toString());
            $user->id = substr($uuid, 0, 25);
        });
    }
}
