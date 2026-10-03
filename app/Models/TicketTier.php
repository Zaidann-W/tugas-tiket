<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketTier extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'event_id',
        'name',
        'price',
        'total_quota',
        'available_stock',
        'is_active',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStockPercentageAttribute(): float
    {
        if ($this->total_quota === 0) {
            return 0;
        }

        return round(($this->available_stock / $this->total_quota) * 100);
    }

    public function isSoldOut(): bool
    {
        return $this->available_stock <= 0;
    }

    public function isLowStock(): bool
    {
        return $this->available_stock > 0 && $this->available_stock <= 5;
    }
}
