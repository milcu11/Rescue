<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\EvacuationCenter;
use App\Models\InventoryItem;
use App\Models\ReliefOperation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role->slug === 'donor') {
            return redirect()->route('donor.index');
        }

        if ($user->role->slug === 'volunteer') {
            Auth::logout();

            return redirect()->route('login')
                ->with('error', 'Volunteers are not permitted to access the dashboard.');
        }

        $activeOps = ReliefOperation::where('status', 'active')->count();
        $totalDistributions = \App\Models\ReliefDistribution::count();

        $totalCapacity = EvacuationCenter::where('status', '!=', 'closed')->sum('capacity');
        $totalOccupancy = EvacuationCenter::where('status', '!=', 'closed')->sum('current_occupancy');
        $occupancyPercent = $totalCapacity > 0
            ? round(($totalOccupancy / $totalCapacity) * 100)
            : 0;

        $totalDonations = Donation::where('status', 'received')->count();
        $lowStockItems = collect();
        $inventoryCount = null;

        if (in_array($user->role->slug, ['super_admin', 'drrm_officer', 'warehouse_staff'])) {
            $inventoryCount = InventoryItem::count();
            $lowStockItems = InventoryItem::whereIn('status', ['low_stock', 'depleted'])->get();
        }

        return view('dashboard', compact(
            'activeOps',
            'totalDistributions',
            'occupancyPercent',
            'totalDonations',
            'lowStockItems',
            'inventoryCount'
        ));
    }
}

