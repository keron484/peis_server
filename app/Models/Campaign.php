<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use illuminate\Support\Str;

class Campaign extends Model
{
    //

    protected $fillable = [
        'id',
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'total_tickets',
        'ticket_price',
        'minimum_winnings',
        'availability',
    ];

    public $table = 'campaign';
    public $incrementing = false;
    public $keyType = 'string';

    public function pricesCategory(): HasMany {
        return $this->hasMany(PricesCategory::class, 'campaign_id');
    }
    public function tickets(): HasMany {
        return $this->hasMany(Tickets::class, 'campaign_id');
    }

    public function ticketsBoughtCount(): HasMany {
        return $this->hasMany(TicketsBoughtCount::class);
    }

}
