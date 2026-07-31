<?php

namespace App\Exports;

use App\Models\ReliefDistribution;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReliefExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    public function collection()
    {
        return ReliefDistribution::with(['operation', 'center', 'item', 'distributor'])
            ->get()
            ->map(fn($d) => [
                'Operation' => $d->operation?->name ?? '—',
                'Center' => $d->center?->name ?? '—',
                'Item' => $d->item?->name ?? '—',
                'Qty Distributed' => $d->quantity_distributed,
                'Unit' => $d->item?->unit ?? '—',
                'Beneficiaries' => $d->beneficiaries_count,
                'Distributed By' => $d->distributor?->name ?? '—',
                'Date' => $d->distributed_at?->format('M d, Y h:i A') ?? '—',
                'Notes' => $d->notes ?? '—',
            ]);
    }

    public function headings(): array
    {
        return ['Operation', 'Center', 'Item', 'Qty Distributed', 'Unit', 'Beneficiaries', 'Distributed By', 'Date', 'Notes'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Distributions';
    }
}
