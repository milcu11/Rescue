<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'module',
        'record_label',
        'record_id',
        'old_values',
        'new_values',
        'ip_address',
        'notes',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActionBadgeAttribute(): string
    {
        return match ($this->action) {
            'created'  => 'success',
            'updated'  => 'primary',
            'deleted'  => 'danger',
            'login'    => 'info',
            'logout'   => 'secondary',
            'restored' => 'warning',
            default    => 'secondary',
        };
    }
}
