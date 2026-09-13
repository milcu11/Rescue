<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\Donation;
use App\Models\EvacuationCenter;
use App\Models\ReliefOperation;
use App\Models\ReliefDistribution;
use App\Models\Evacuee;

class ApiStatsController extends Controller
{
    public function index()
    {
        $totalCapacity  = EvacuationCenter::where('status','!=','closed')->sum('capacity');
        $totalOccupancy = Evacuee::where('status', 'checked_in')->sum('family_members');

        return response()->json([
            'success' => true,
            'data' => [
                'inventory' => [
                    'total'     => InventoryItem::where('is_active', true)->count(),
                    'low_stock' => InventoryItem::where('is_active', true)->whereIn('status',['low_stock','depleted'])->count(),
                    'depleted'  => InventoryItem::where('is_active', true)->where('status','depleted')->count(),
                ],
                'donations' => [
                    'total'           => Donation::count(),
                    'received'        => Donation::whereIn('status', ['received', 'verified', 'allocated', 'distributed'])->count(),
                    'pending'         => Donation::where('status','pending')->count(),
                    'monetary_total'  => Donation::where('type','monetary')
                                            ->whereIn('status', ['received', 'verified', 'allocated', 'distributed'])
                                            ->sum('amount'),
                ],
                'evacuation' => [
                    'total_centers'   => EvacuationCenter::count(),
                    'active_centers'  => EvacuationCenter::where('status','active')->count(),
                    'full_centers'    => EvacuationCenter::where('status','full')->count(),
                    'total_occupancy' => $totalOccupancy,
                    'total_capacity'  => $totalCapacity,
                    'occupancy_percent' => $totalCapacity > 0
                        ? round(($totalOccupancy / $totalCapacity) * 100)
                        : 0,
                ],
                'relief' => [
                    'active_operations'  => ReliefOperation::where('status','active')->count(),
                    'total_distributions'=> ReliefDistribution::count(),
                    'total_beneficiaries'=> ReliefDistribution::sum('beneficiaries_count'),
                ],
            ],
            'generated_at' => now()->toISOString(),
        ]);
    }
}
