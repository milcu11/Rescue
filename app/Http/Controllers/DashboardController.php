<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\EvacuationCenter;
use App\Models\Evacuee;
use App\Models\InventoryItem;
use App\Models\ReliefOperation;
use App\Models\ReliefDistribution;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->slug;

        if ($role === 'donor') {
            return redirect()->route('donor.index');
        }

        if ($role === 'volunteer' || $role === 'resident') {
            return redirect()->route('login')
                ->with('error', 'Your portal is managed by Group 1.');
        }

        $period = in_array($request->get('period'), ['7', '30', '90', 'all'], true)
            ? $request->get('period')
            : '30';
        $stats = $this->getStats($role, $period);

        return view('dashboard', compact('stats', 'role'));
    }

    private function getStats(string $role, string $period): array
    {
        $activeOccupancy = Evacuee::where('status', 'checked_in')->sum('family_members');
        $totalCapacity = EvacuationCenter::where('status', '!=', 'closed')->sum('capacity');
        $activity = AuditLog::query()->latest();
        if ($period !== 'all') {
            $activity->where('created_at', '>=', now()->subDays((int) $period));
        }

        $mostNeededItems = ReliefDistribution::with('item')
            ->selectRaw('inventory_item_id, SUM(quantity_distributed) as total_quantity')
            ->groupBy('inventory_item_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        $mapCenters = EvacuationCenter::orderBy('name')
            ->get(['name', 'barangay', 'latitude', 'longitude', 'status', 'capacity']);
        $mapCenters->each(function ($center) {
            $fallbacks = [
                'san jose' => [14.5220, 121.2584],
                'san juan' => [14.5257, 121.2661],
            ];
            $fallback = $fallbacks[strtolower(trim((string) $center->barangay))] ?? [14.5171, 121.2672];
            $center->latitude = is_numeric($center->latitude) ? (float) $center->latitude : $fallback[0];
            $center->longitude = is_numeric($center->longitude) ? (float) $center->longitude : $fallback[1];
            $center->current_occupancy = $center->activeEvacuees()->sum('family_members');
        });

        $base = [
            'active_ops'          => ReliefOperation::where('status','active')->count(),
            'total_distributions' => ReliefDistribution::count(),
            'total_centers'       => EvacuationCenter::count(),
            'total_evacuees'      => $activeOccupancy,
            'total_capacity'      => $totalCapacity,
            'total_occupancy'     => $activeOccupancy,
            'inventory_total'     => InventoryItem::where('is_active', true)->count(),
            'low_stock_items'     => InventoryItem::where('is_active', true)->whereIn('status',['low_stock','depleted'])->get(),
            'donations_total'     => Donation::count(),
            'received_donations'  => Donation::whereIn('status', ['received', 'verified', 'allocated', 'distributed'])->count(),
            'pending_donations'   => Donation::where('status', 'pending')->count(),
            'total_beneficiaries' => ReliefDistribution::sum('beneficiaries_count'),
            'most_needed_items'   => $mostNeededItems,
            'recent_activity'     => $activity->limit(8)->get(),
            'map_centers'         => $mapCenters,
            'period'              => $period,
            'active_operations'   => ReliefOperation::where('status','active')->latest()->take(5)->get(),
        ];

        $base['occupancy_percent'] = $totalCapacity > 0
            ? round(($base['total_occupancy'] / $base['total_capacity']) * 100)
            : 0;

        if ($role === 'evac_manager') {
            $base['centers_active'] = EvacuationCenter::where('status','active')->count();
            $base['centers_full']   = EvacuationCenter::where('status','full')->count();
            $base['recent_centers'] = EvacuationCenter::latest()->take(5)->get();
        }

        if ($role === 'lgu_staff') {
            $base['available_items'] = InventoryItem::where('status','available')->count();
            $base['depleted_items']  = InventoryItem::where('status','depleted')->count();
        }

        return $base;
    }
}

