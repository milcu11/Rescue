<?php

namespace App\Exports;

use App\Models\InventoryItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    public function collection()
    {
        return InventoryItem::with('creator')
            ->get()
            ->map(fn($item) => [
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
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Inventory';
    }
}
