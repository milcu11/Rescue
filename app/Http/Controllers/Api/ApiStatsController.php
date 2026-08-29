<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\Donation;
use App\Models\EvacuationCenter;
use App\Models\ReliefOperation;
use App\Models\ReliefDistribution;

class ApiStatsController extends Controller
{
    public function index()
    {
        $totalCapacity  = EvacuationCenter::where('status','!=','closed')->sum('capacity');
        $totalOccupancy = EvacuationCenter::where('status','!=','closed')->sum('current_occupancy');

        return response()->json([
            'success' => true,
            'data' => [
                'inventory' => [
                    'total'     => InventoryItem::count(),
                    'low_stock' => InventoryItem::whereIn('status',['low_stock','depleted'])->count(),
                    'depleted'  => InventoryItem::where('status','depleted')->count(),
                ],
                'donations' => [
                    'total'           => Donation::count(),
                    'received'        => Donation::where('status','received')->count(),
                    'pending'         => Donation::where('status','pending')->count(),
                    'monetary_total'  => Donation::where('type','monetary')
                                            ->where('status','received')
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
