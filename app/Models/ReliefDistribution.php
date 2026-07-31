<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReliefDistribution extends Model
{
    protected $fillable = [
        'relief_operation_id',
        'evacuation_center_id',
        'inventory_item_id',
        'quantity_distributed',
        'distributed_at',
        'beneficiaries_count',
        'notes',
        'distributed_by',
    ];

    protected $casts = [
        'distributed_at' => 'datetime',
    ];

    public function operation()
    {
        return $this->belongsTo(ReliefOperation::class, 'relief_operation_id');
    }

    public function center()
    {
        return $this->belongsTo(EvacuationCenter::class, 'evacuation_center_id');
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributed_by');
    }
}
