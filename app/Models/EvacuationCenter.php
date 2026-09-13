<?php

namespace App\Models;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvacuationCenter extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'barangay', 'address', 'capacity',
        'current_occupancy', 'status', 'families_registered',
        'medical_needs_count', 'contact_person', 'contact_phone',
        'latitude', 'longitude', 'notes', 'created_by',
        'intake_procedures', 'required_items',
    ];

    public function evacuees()
    {
        return $this->hasMany(Evacuee::class);
    }

    public function activeEvacuees()
    {
        return $this->hasMany(Evacuee::class)
            ->where(function ($query) {
                $query->where('status', 'checked_in')
                    ->orWhere(function ($legacy) {
                        $legacy->where('status', 'registered')
                            ->whereNotNull('checked_in_at');
                    });
            });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusAttribute($value): string
    {
        return $value === 'active' ? 'open' : $value;
    }

    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = $value === 'open' ? 'active' : $value;
    }

    public function getOccupancyPercentAttribute(): int
    {
        if ($this->capacity === 0) return 0;
        return (int) round(($this->current_occupancy / $this->capacity) * 100);
    }

    // Auto-update status based on occupancy
    public function updateStatus(?int $previousOccupancy = null): void
    {
        $originalStatus = $this->status;
        $originalOccupancy = $previousOccupancy ?? (int) $this->getOriginal('current_occupancy');
        $nearCapacityThreshold = max(1, (int) ceil($this->capacity * config('notifications.near_capacity_percent') / 100));

        if ($this->current_occupancy >= $this->capacity) {
            $this->status = 'full';
        } elseif ($this->status === 'full') {
            $this->status = 'open';
        }

        $this->save();

        if ($originalStatus !== 'full' && $this->status === 'full') {
            NotificationService::sendToRole(
                'mdrrmo',
                'center_full',
                'Center full alert',
                "Evacuation center '{$this->name}' is now full.",
                route('evacuation.show', $this)
            );
        }

        if ($originalOccupancy < $nearCapacityThreshold
            && $this->current_occupancy >= $nearCapacityThreshold
            && $this->current_occupancy < $this->capacity) {
            NotificationService::sendToRole(
                'mdrrmo',
                'near_capacity',
                'Evacuation center nearing capacity',
                "Evacuation center '{$this->name}' is at {$this->occupancy_percent}% capacity.",
                route('evacuation.show', $this)
            );
        }
    }

    public function syncOccupancy(): int
    {
        $occupancy = (int) $this->activeEvacuees()->sum('family_members');

        if ((int) $this->current_occupancy !== $occupancy) {
            $this->forceFill(['current_occupancy' => $occupancy])->saveQuietly();
        }

        return $occupancy;
    }
}
