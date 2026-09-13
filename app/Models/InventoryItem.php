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
                if ($item->status === 'low_stock') {
                    NotificationService::sendToRole(
                        'lgu_staff',
                        'low_stock',
                        'Low stock alert',
                        "Inventory item '{$item->name}' is running low ({$item->quantity} {$item->unit}).",
                        $itemLink
                    );
                }

                if ($item->status === 'depleted') {
                    NotificationService::sendToRole(
                        'lgu_staff',
                        'low_stock',
                        'Out of stock',
                        "Inventory item '{$item->name}' has been depleted.",
                        $itemLink
                    );
                }
            }

            $expiresSoon = $item->expires_at
                && $item->expires_at->isFuture()
                && $item->expires_at->diffInDays(Carbon::now(), true) <= config('notifications.near_expiration_days');
            $originalExpiry = $item->getOriginal('expires_at');
            $wasOutsideExpiryWindow = !$originalExpiry
                || Carbon::parse($originalExpiry)->diffInDays(Carbon::now(), true) > 30;

            if ($expiresSoon && $wasOutsideExpiryWindow) {
                NotificationService::sendToRole(
                    'lgu_staff',
                    'near_expiration',
                    'Item expiring soon',
                    "Inventory item '{$item->name}' expires on {$item->expires_at->format('M d, Y')}.",
                    $itemLink
                );
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
