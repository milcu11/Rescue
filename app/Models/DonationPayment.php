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
        'gcash_reference_number',
        'proof_image_path',
        'amount',
        'status',
        'checkout_url',
        'paymongo_response',
        'paid_at',
        'verified_by',
        'verified_at',
        'rejection_reason',
    ];

    protected $casts = [
        'paymongo_response' => 'array',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'success',
            'pending', 'pending_verification' => 'warning',
            'failed', 'rejected' => 'danger',
            'refunded' => 'secondary',
            default => 'secondary',
        };
    }
}
