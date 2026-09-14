<?php

namespace App\Exports;

use App\Exports\Concerns\HasReportMetadata;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryMovementExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    use HasReportMetadata;

    public function __construct(private Collection $movements, private array $metadata) {}

    public function collection(): Enumerable
    {
        return $this->movements->map(fn($movement) => [
            'Reference' => $movement->reference,
            'Item' => $movement->item?->name ?? '—',
            'Type' => ucfirst(str_replace('_', ' ', $movement->type)),
            'Quantity' => $movement->quantity,
            'Before' => $movement->quantity_before,
            'After' => $movement->quantity_after,
            'Recorded By' => $movement->user?->name ?? 'System',
            'Occurred At' => $movement->occurred_at?->format('M d, Y h:i A') ?? '—',
            'Notes' => $movement->notes ?? '—',
        ]);
    }

    public function headings(): array
    {
        return ['Reference', 'Item', 'Type', 'Quantity', 'Before', 'After', 'Recorded By', 'Occurred At', 'Notes'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [5 => ['font' => ['bold' => true]]];
    }

    public function title(): string
    {
        return 'Stock Movements';
    }
}
