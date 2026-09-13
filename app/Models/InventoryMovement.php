<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'inventory_item_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'reference',
        'user_id',
        'occurred_at',
        'source_type',
        'source_id',
        'notes',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
