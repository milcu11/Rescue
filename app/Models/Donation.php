<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tracking_code',
        'donor_name',
        'donor_contact',
        'donor_email',
        'type',
        'amount',
        'items_description',
        'status',
        'received_by',
        'received_at',
        'location',
        'notes',
        'created_by',
        'paymongo_checkout_id',
        'paymongo_payment_id',
        'payment_status',
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($donation) {
            $donation->tracking_code = self::generateTrackingCode();
        });
    }

    private static function generateTrackingCode(): string
    {
        $year = date('Y');
        $sequence = self::withTrashed()
            ->whereYear('created_at', $year)
            ->count() + 1;

        do {
            $trackingCode = 'DON-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            $sequence++;
        } while (self::withTrashed()->where('tracking_code', $trackingCode)->exists());

        return $trackingCode;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isMonetary(): bool
    {
        return $this->type === 'monetary';
    }

    public function payments()
    {
        return $this->hasMany(DonationPayment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(DonationPayment::class)->latestOfMany();
    }
}
