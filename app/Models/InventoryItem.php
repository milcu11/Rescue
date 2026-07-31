<?php

namespace App\Models;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'category',
        'quantity',
        'unit',
        'minimum_threshold',
        'status',
        'location',
        'notes',
        'created_by',
    ];

    protected static function booted(): void
    {
        static::saving(function ($item) {
            $originalStatus = $item->getOriginal('status');

            if ($item->quantity <= 0) {
                $item->status = 'depleted';
            } elseif ($item->quantity <= $item->minimum_threshold) {
                $item->status = 'low_stock';
            } else {
                $item->status = 'available';
            }

            if ($item->status !== $originalStatus) {
                $service = app(NotificationService::class);

                if ($item->status === 'low_stock') {
                    $service->create([
                        'type' => 'low_stock',
                        'title' => 'Low stock alert',
                        'message' => "Inventory item '{$item->name}' is running low ({$item->quantity} {$item->unit}).",
                        'link' => route('inventory.edit', $item),
                    ]);
                }

                if ($item->status === 'depleted') {
                    $service->create([
                        'type' => 'low_stock',
                        'title' => 'Out of stock',
                        'message' => "Inventory item '{$item->name}' has been depleted.",
                        'link' => route('inventory.edit', $item),
                    ]);
                }
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isLowStock(): bool
    {
        return $this->status === 'low_stock';
    }

    public function isDepleted(): bool
    {
        return $this->status === 'depleted';
    }
}
