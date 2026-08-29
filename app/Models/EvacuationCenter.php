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
            ->where('status', 'checked_in');
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
    public function updateStatus(): void
    {
        $originalStatus = $this->status;

        if ($this->current_occupancy >= $this->capacity) {
            $this->status = 'full';
        } elseif ($this->status === 'full') {
            $this->status = 'open';
        }

        $this->save();

        if ($originalStatus !== 'full' && $this->status === 'full') {
            app(NotificationService::class)->create([
                'type' => 'center_full',
                'title' => 'Center full alert',
                'message' => "Evacuation center '{$this->name}' is now full.",
                'link' => route('evacuation.show', $this),
            ]);
        }
    }
}
