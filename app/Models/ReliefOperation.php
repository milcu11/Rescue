<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReliefOperation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'status',
        'start_date', 'end_date',
        'incident_name', 'incident_id',
        'notes', 'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function distributions()
    {
        return $this->hasMany(ReliefDistribution::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTotalBeneficiariesAttribute(): int
    {
        return $this->distributions->sum('beneficiaries_count');
    }

    public function getCentersServedAttribute(): int
    {
        return $this->distributions
            ->pluck('evacuation_center_id')
            ->unique()
            ->count();
    }
}
