<?php

namespace App\Models;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class InventoryItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sku',
        'name',
        'category',
        'quantity',
        'unit',
        'expires_at',
        'minimum_threshold',
        'status',
        'is_active',
        'warehouse',
        'location',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function ($item) {
            $originalStatus = $item->getOriginal('status');
            $itemLink = $item->exists ? route('inventory.edit', $item) : route('inventory.index');

            $item->status = $item->calculatedStatus();

            if ($item->status !== $originalStatus) {
                $service = app(NotificationService::class);

                if ($item->status === 'low_stock') {
                    $service->create([
                        'type' => 'low_stock',
                        'title' => 'Low stock alert',
                        'message' => "Inventory item '{$item->name}' is running low ({$item->quantity} {$item->unit}).",
                        'link' => $itemLink,
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

            $expiresSoon = $item->expires_at
                && $item->expires_at->isFuture()
                && $item->expires_at->diffInDays(Carbon::now(), true) <= 30;
            $originalExpiry = $item->getOriginal('expires_at');
            $wasOutsideExpiryWindow = !$originalExpiry
                || Carbon::parse($originalExpiry)->diffInDays(Carbon::now(), true) > 30;

            if ($expiresSoon && $wasOutsideExpiryWindow) {
                app(NotificationService::class)->create([
                    'type' => 'near_expiration',
                    'title' => 'Item expiring soon',
                    'message' => "Inventory item '{$item->name}' expires on {$item->expires_at->format('M d, Y')}.",
                    'link' => $itemLink,
                ]);
            }
        });
    }

    public function calculatedStatus(): string
    {
        if ($this->quantity <= 0) {
            return 'depleted';
        }

        return $this->quantity <= $this->minimum_threshold ? 'low_stock' : 'available';
    }

    public function syncStatus(): void
    {
        $status = $this->calculatedStatus();

        if ($this->status !== $status) {
            $this->forceFill(['status' => $status])->saveQuietly();
        }
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class)->latest('occurred_at');
    }

    public function isLowStock(): bool
    {
        return $this->status === 'low_stock';
    }

    public function isDepleted(): bool
    {
        return $this->status === 'depleted';
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'food'      => 'Food & rations',
            'medicine'  => 'Medical supplies',
            'medical'   => 'Medical supplies',
            'clothing'  => 'Clothing',
            'tools'     => 'Emergency equipment',
            'emergency' => 'Emergency equipment',
            'first_aid' => 'First aid kits',
            'hygiene'   => 'Hygiene kits',
            'water'     => 'Water & sanitation',
            'other'     => 'Other supplies',
            default     => ucfirst((string) $this->category),
        };
    }

    public function getIsLowStockAttribute(): bool
    {
        return in_array($this->status, ['low_stock', 'depleted']);
    }
}
