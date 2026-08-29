<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\EvacuationCenter;
use App\Models\InventoryItem;
use App\Models\ReliefOperation;
use App\Models\ReliefDistribution;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
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

        $stats = $this->getStats($role);

        return view('dashboard', compact('stats', 'role'));
    }

    private function getStats(string $role): array
    {
        $base = [
            'active_ops'          => ReliefOperation::where('status','active')->count(),
            'total_distributions' => ReliefDistribution::count(),
            'total_capacity'      => EvacuationCenter::where('status','!=','closed')->sum('capacity'),
            'total_occupancy'     => EvacuationCenter::where('status','!=','closed')->sum('current_occupancy'),
            'total_donations'     => Donation::where('status','received')->count(),
            'low_stock_items'     => InventoryItem::whereIn('status',['low_stock','depleted'])->get(),
            'active_operations'   => ReliefOperation::where('status','active')->latest()->take(5)->get(),
        ];

        $base['occupancy_percent'] = $base['total_capacity'] > 0
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

