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

class InventoryExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    use HasReportMetadata;

    public function __construct(private Collection $items, private array $metadata) {}

    public function collection(): Enumerable
    {
        return $this->items->map(fn($item) => [
                'ID' => $item->id,
                'Name' => $item->name,
                'Category' => ucfirst($item->category),
                'Quantity' => $item->quantity,
                'Unit' => $item->unit,
                'Min. Threshold' => $item->minimum_threshold,
                'Status' => ucfirst(str_replace('_', ' ', $item->status)),
                'Location' => $item->location ?? '—',
                'Added By' => $item->creator?->name ?? '—',
                'Date Added' => $item->created_at?->format('M d, Y') ?? '—',
            ]);
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Category', 'Quantity', 'Unit', 'Min. Threshold', 'Status', 'Location', 'Added By', 'Date Added'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            5 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Inventory';
    }
}
