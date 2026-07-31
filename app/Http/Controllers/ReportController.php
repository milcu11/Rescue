<?php

namespace App\Http\Controllers;

use App\Exports\DonationsExport;
use App\Exports\EvacuationExport;
use App\Exports\InventoryExport;
use App\Exports\ReliefExport;
use App\Models\Donation;
use App\Models\EvacuationCenter;
use App\Models\InventoryItem;
use App\Models\ReliefDistribution;
use App\Models\ReliefOperation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $summary = [
            'inventory' => [
                'total' => InventoryItem::count(),
                'available' => InventoryItem::where('status', 'available')->count(),
                'low_stock' => InventoryItem::where('status', 'low_stock')->count(),
                'depleted' => InventoryItem::where('status', 'depleted')->count(),
            ],
            'donations' => [
                'total' => Donation::count(),
                'pending' => Donation::where('status', 'pending')->count(),
                'received' => Donation::where('status', 'received')->count(),
                'distributed' => Donation::where('status', 'distributed')->count(),
                'monetary_total' => Donation::where('type', 'monetary')->sum('amount'),
            ],
            'evacuation' => [
                'total' => EvacuationCenter::count(),
                'active' => EvacuationCenter::where('status', 'active')->count(),
                'full' => EvacuationCenter::where('status', 'full')->count(),
                'occupancy' => EvacuationCenter::sum('current_occupancy'),
                'capacity' => EvacuationCenter::sum('capacity'),
            ],
            'relief' => [
                'total' => ReliefOperation::count(),
                'active' => ReliefOperation::where('status', 'active')->count(),
                'completed' => ReliefOperation::where('status', 'completed')->count(),
                'distributions' => ReliefDistribution::count(),
                'beneficiaries' => ReliefDistribution::sum('beneficiaries_count'),
            ],
        ];

        return view('reports.index', compact('summary'));
    }

    public function inventoryPrint()
    {
        $items = InventoryItem::with('creator')->orderBy('category')->get();
        return view('reports.print.inventory', compact('items'));
    }

    public function donationsPrint()
    {
        $donations = Donation::orderByDesc('created_at')->get();
        return view('reports.print.donations', compact('donations'));
    }

    public function evacuationPrint()
    {
        $centers = EvacuationCenter::withCount([
            'evacuees as active_count' => fn($q) => $q->where('status', 'checked_in'),
        ])->get();

        return view('reports.print.evacuation', compact('centers'));
    }

    public function reliefPrint()
    {
        $distributions = ReliefDistribution::with(['operation', 'center', 'item', 'distributor'])
            ->orderByDesc('distributed_at')
            ->get();

        return view('reports.print.relief', compact('distributions'));
    }

    public function exportInventoryExcel()
    {
        return Excel::download(new InventoryExport, 'inventory-'.date('Y-m-d').'.xlsx');
    }

    public function exportDonationsExcel()
    {
        return Excel::download(new DonationsExport, 'donations-'.date('Y-m-d').'.xlsx');
    }

    public function exportEvacuationExcel()
    {
        return Excel::download(new EvacuationExport, 'evacuation-'.date('Y-m-d').'.xlsx');
    }

    public function exportReliefExcel()
    {
        return Excel::download(new ReliefExport, 'distributions-'.date('Y-m-d').'.xlsx');
    }

    public function exportInventoryPdf()
    {
        $items = InventoryItem::with('creator')->orderBy('category')->get();
        $pdf = Pdf::loadView('reports.pdf.inventory', compact('items'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('inventory-'.date('Y-m-d').'.pdf');
    }

    public function exportDonationsPdf()
    {
        $donations = Donation::orderByDesc('created_at')->get();
        $pdf = Pdf::loadView('reports.pdf.donations', compact('donations'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('donations-'.date('Y-m-d').'.pdf');
    }

    public function exportEvacuationPdf()
    {
        $centers = EvacuationCenter::all();
        $pdf = Pdf::loadView('reports.pdf.evacuation', compact('centers'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('evacuation-'.date('Y-m-d').'.pdf');
    }

    public function exportReliefPdf()
    {
        $distributions = ReliefDistribution::with(['operation', 'center', 'item'])
            ->orderByDesc('distributed_at')
            ->get();

        $pdf = Pdf::loadView('reports.pdf.relief', compact('distributions'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('distributions-'.date('Y-m-d').'.pdf');
    }
}
