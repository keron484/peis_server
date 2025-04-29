<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketsBought extends Model
{
    //
    protected $fillable = [
       'ticket_id',
       'user_id'
    ];
    protected $table = 'tickets_bought';
    public $incrementing = 'false';
    public $keyType = 'string';

    public function ticket():BelongsTo {
        return $this->belongsTo(Tickets::class, 'ticket_id');
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
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
