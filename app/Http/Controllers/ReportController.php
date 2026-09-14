<?php

namespace App\Http\Controllers;

use App\Exports\DonationsExport;
use App\Exports\EvacuationExport;
use App\Exports\InventoryExport;
use App\Exports\InventoryMovementExport;
use App\Exports\ReliefExport;
use App\Exports\AuditLogExport;
use App\Models\Donation;
use App\Models\EvacuationCenter;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\ReliefDistribution;
use App\Models\ReliefOperation;
use App\Models\Evacuee;
use App\Models\AuditLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->filters($request);
        $inventory = $this->inventoryQuery($filters)->get();
        $donations = $this->donationQuery($filters)->get();
        $centers = $this->evacuationQuery($filters)->get();
        $distributions = $this->reliefQuery($filters)->get();

        $summary = [
            'inventory' => [
                'total' => $inventory->count(),
                'available' => $inventory->where('status', 'available')->count(),
                'low_stock' => $inventory->where('status', 'low_stock')->count(),
                'depleted' => $inventory->where('status', 'depleted')->count(),
            ],
            'donations' => [
                'total' => $donations->count(),
                'pending' => $donations->where('status', 'pending')->count(),
                'received' => $donations->where('status', 'received')->count(),
                'distributed' => $donations->where('status', 'distributed')->count(),
                'monetary_total' => $donations->where('type', 'monetary')->sum('amount'),
            ],
            'evacuation' => [
                'total' => $centers->count(),
                'active' => $centers->where('status', 'active')->count(),
                'full' => $centers->where('status', 'full')->count(),
                'occupancy' => $centers->sum('current_occupancy'),
                'capacity' => $centers->sum('capacity'),
            ],
            'relief' => [
                'total' => $distributions->pluck('relief_operation_id')->unique()->count(),
                'active' => ReliefOperation::whereIn('id', $distributions->pluck('relief_operation_id'))->where('status', 'active')->count(),
                'completed' => ReliefOperation::whereIn('id', $distributions->pluck('relief_operation_id'))->where('status', 'completed')->count(),
                'distributions' => $distributions->count(),
                'beneficiaries' => $distributions->sum('beneficiaries_count'),
            ],
        ];

        return view('reports.index', compact('summary', 'filters'));
    }

    public function inventoryPrint(Request $request)
    {
        $filters = $this->filters($request);
        $items = $this->inventoryQuery($filters)->get();
        return view('reports.print.inventory', compact('items', 'filters'));
    }

    public function donationsPrint(Request $request)
    {
        $filters = $this->filters($request);
        $donations = $this->donationQuery($filters)->get();
        return view('reports.print.donations', compact('donations', 'filters'));
    }

    public function evacuationPrint(Request $request)
    {
        $filters = $this->filters($request);
        $centers = $this->evacuationQuery($filters)->get();

        return view('reports.print.evacuation', compact('centers', 'filters'));
    }

    public function reliefPrint(Request $request)
    {
        $filters = $this->filters($request);
        $distributions = $this->reliefQuery($filters)->get();

        return view('reports.print.relief', compact('distributions', 'filters'));
    }

    public function exportInventoryExcel(Request $request)
    {
        $filters = $this->filters($request);
        return Excel::download(new InventoryExport($this->inventoryQuery($filters)->get(), $this->metadata('Inventory Report', $filters)), 'inventory-'.date('Y-m-d').'.xlsx');
    }

    public function exportDonationsExcel(Request $request)
    {
        $filters = $this->filters($request);
        return Excel::download(new DonationsExport($this->donationQuery($filters)->get(), $this->metadata('Donations Report', $filters)), 'donations-'.date('Y-m-d').'.xlsx');
    }

    public function exportEvacuationExcel(Request $request)
    {
        $filters = $this->filters($request);
        $centers = $this->evacuationQuery($filters)->get();
        return Excel::download(new EvacuationExport($centers, $this->metadata('Evacuation Centers Report', $filters)), 'evacuation-'.date('Y-m-d').'.xlsx');
    }

    public function exportReliefExcel(Request $request)
    {
        $filters = $this->filters($request);
        return Excel::download(new ReliefExport($this->reliefQuery($filters)->get(), $this->metadata('Relief Distributions Report', $filters)), 'distributions-'.date('Y-m-d').'.xlsx');
    }

    public function exportInventoryPdf(Request $request)
    {
        $filters = $this->filters($request);
        $items = $this->inventoryQuery($filters)->get();
        $pdf = Pdf::loadView('reports.pdf.inventory', compact('items', 'filters'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('inventory-'.date('Y-m-d').'.pdf');
    }

    public function exportDonationsPdf(Request $request)
    {
        $filters = $this->filters($request);
        $donations = $this->donationQuery($filters)->get();
        $pdf = Pdf::loadView('reports.pdf.donations', compact('donations', 'filters'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('donations-'.date('Y-m-d').'.pdf');
    }

    public function exportEvacuationPdf(Request $request)
    {
        $filters = $this->filters($request);
        $centers = $this->evacuationQuery($filters)->get();
        $pdf = Pdf::loadView('reports.pdf.evacuation', compact('centers', 'filters'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('evacuation-'.date('Y-m-d').'.pdf');
    }

    public function exportReliefPdf(Request $request)
    {
        $filters = $this->filters($request);
        $distributions = $this->reliefQuery($filters)->get();

        $pdf = Pdf::loadView('reports.pdf.relief', compact('distributions', 'filters'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('distributions-'.date('Y-m-d').'.pdf');
    }

    public function movementPrint(Request $request)
    {
        $filters = $this->filters($request);
        $movements = $this->movementQuery($filters)->get();
        return view('reports.pdf.movements', compact('movements', 'filters'));
    }

    public function exportMovementExcel(Request $request)
    {
        $filters = $this->filters($request);
        return Excel::download(new InventoryMovementExport($this->movementQuery($filters)->get(), $this->metadata('Stock Movement Report', $filters)), 'stock-movements-'.date('Y-m-d').'.xlsx');
    }

    public function exportMovementPdf(Request $request)
    {
        $filters = $this->filters($request);
        $movements = $this->movementQuery($filters)->get();
        return Pdf::loadView('reports.pdf.movements', compact('movements', 'filters'))->setPaper('a4', 'landscape')->download('stock-movements-'.date('Y-m-d').'.pdf');
    }

    public function auditPrint(Request $request)
    {
        $filters = $this->filters($request);
        $logs = $this->auditQuery($filters)->get();
        return view('reports.pdf.audit', compact('logs', 'filters'));
    }

    public function exportAuditExcel(Request $request)
    {
        $filters = $this->filters($request);
        return Excel::download(new AuditLogExport($this->auditQuery($filters)->get(), $this->metadata('Audit Trail Report', $filters)), 'audit-trail-'.date('Y-m-d').'.xlsx');
    }

    public function exportAuditPdf(Request $request)
    {
        $filters = $this->filters($request);
        $logs = $this->auditQuery($filters)->get();
        return Pdf::loadView('reports.pdf.audit', compact('logs', 'filters'))->setPaper('a4', 'landscape')->download('audit-trail-'.date('Y-m-d').'.pdf');
    }

    private function filters(Request $request): array
    {
        return $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'status' => ['nullable', 'string', 'max:40'],
            'category' => ['nullable', 'string', 'max:80'],
            'item' => ['nullable', 'integer'],
            'center' => ['nullable', 'integer'],
            'module' => ['nullable', 'string', 'max:80'],
            'action' => ['nullable', 'string', 'max:40'],
        ]) + ['from' => null, 'to' => null, 'status' => null, 'category' => null, 'item' => null, 'center' => null, 'module' => null, 'action' => null];
    }

    private function metadata(string $title, array $filters): array
    {
        $applied = collect($filters)->filter()->map(fn($value, $key) => ucfirst($key).'='.$value)->join(', ') ?: 'None';
        return [
            [$title, ''],
            ['Coverage', ($filters['from'] ?? 'Beginning').' to '.($filters['to'] ?? 'Present')],
            ['Generated', now()->format('F d, Y h:i A').' by '.Auth::user()->name],
            ['Filters', $applied],
        ];
    }

    private function inventoryQuery(array $filters)
    {
        return InventoryItem::with('creator')->when($filters['status'], fn($q, $value) => $q->where('status', $value))
            ->when($filters['category'], fn($q, $value) => $q->where('category', $value))
            ->when($filters['from'], fn($q, $value) => $q->whereDate('created_at', '>=', $value))
            ->when($filters['to'], fn($q, $value) => $q->whereDate('created_at', '<=', $value))->orderBy('category');
    }

    private function donationQuery(array $filters)
    {
        return Donation::when($filters['status'], fn($q, $value) => $q->where('status', $value))
            ->when($filters['from'], fn($q, $value) => $q->whereDate('created_at', '>=', $value))
            ->when($filters['to'], fn($q, $value) => $q->whereDate('created_at', '<=', $value))->orderByDesc('created_at');
    }

    private function reliefQuery(array $filters)
    {
        return ReliefDistribution::with(['operation', 'center', 'item', 'distributor'])
            ->when($filters['item'], fn($q, $value) => $q->where('inventory_item_id', $value))
            ->when($filters['center'], fn($q, $value) => $q->where('evacuation_center_id', $value))
            ->when($filters['from'], fn($q, $value) => $q->whereDate('distributed_at', '>=', $value))
            ->when($filters['to'], fn($q, $value) => $q->whereDate('distributed_at', '<=', $value))->orderByDesc('distributed_at');
    }

    private function evacuationQuery(array $filters)
    {
        return EvacuationCenter::withCount(['evacuees as active_count' => fn($q) => $q->where('status', 'checked_in')])
            ->when($filters['status'], fn($q, $value) => $q->where('status', $value))
            ->when($filters['from'], fn($q, $value) => $q->whereDate('created_at', '>=', $value))
            ->when($filters['to'], fn($q, $value) => $q->whereDate('created_at', '<=', $value));
    }

    private function movementQuery(array $filters)
    {
        return InventoryMovement::with(['item', 'user'])
            ->when($filters['status'], fn($q, $value) => $q->where('type', $value))
            ->when($filters['item'], fn($q, $value) => $q->where('inventory_item_id', $value))
            ->when($filters['category'], fn($q, $value) => $q->whereHas('item', fn($item) => $item->where('category', $value)))
            ->when($filters['from'], fn($q, $value) => $q->whereDate('occurred_at', '>=', $value))
            ->when($filters['to'], fn($q, $value) => $q->whereDate('occurred_at', '<=', $value))->latest('occurred_at');
    }

    private function auditQuery(array $filters)
    {
        return AuditLog::when($filters['module'], fn($q, $value) => $q->where('module', $value))
            ->when($filters['action'], fn($q, $value) => $q->where('action', $value))
            ->when($filters['from'], fn($q, $value) => $q->whereDate('created_at', '>=', $value))
            ->when($filters['to'], fn($q, $value) => $q->whereDate('created_at', '<=', $value))->latest();
    }
}
