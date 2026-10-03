<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'title',
        'slug',
        'tagline',
        'description',
        'venue',
        'event_date',
        'banner_image',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function ticketTiers(): HasMany
    {
        return $this->hasMany(TicketTier::class);
    }
}
