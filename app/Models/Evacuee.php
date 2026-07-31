<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evacuee extends Model
{
    protected $fillable = [
        'evacuation_center_id', 'name', 'family_members',
        'barangay_origin', 'needs', 'id_presented',
        'status', 'checked_in_at', 'checked_out_at',
        'notes', 'recorded_by',
    ];

    protected $casts = [
        'checked_in_at'  => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    public function center()
    {
        return $this->belongsTo(EvacuationCenter::class, 'evacuation_center_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
