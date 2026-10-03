<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'order_item_id',
        'ticket_code',
        'holder_name',
        'is_used',
        'used_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }
}
