<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role_target',
        'type',
        'title',
        'message',
        'link',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser($query, $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('role_target', $user->role->slug ?? null)
                ->orWhereNull('role_target');
        })->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhereNull('user_id');
        });
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
