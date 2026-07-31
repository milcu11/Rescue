<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationPayment extends Model
{
    protected $fillable = [
        'donation_id',
        'paymongo_checkout_id',
        'paymongo_payment_id',
        'payment_method',
        'amount',
        'status',
        'checkout_url',
        'paymongo_response',
        'paid_at',
    ];

    protected $casts = [
        'paymongo_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            'refunded' => 'secondary',
            default => 'secondary',
        };
    }
}
