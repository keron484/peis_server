<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tickets extends Model
{
    //
    protected $fillable = [
        'id',
        'code',
        'winnings',
        'cost_price',
        'availability',
        'status',
        'campaign_id'
    ];

    protected $table = 'tickets';
    public $incrementing = 'false';
    public $keyType = 'string';

    public function campaign():BelongsTo {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
    public function ticketsBought(): HasMany {
        return $this->hasMany(TicketsBought::class, 'ticket_id');
    }

}
