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

class EvacuationExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    use HasReportMetadata;

    public function __construct(private Collection $centers, private array $metadata) {}

    public function collection(): Enumerable
    {
        return $this->centers->map(fn($c) => [
            'ID' => $c->id,
            'Center Name' => $c->name,
            'Barangay' => $c->barangay,
            'Address' => $c->address,
            'Capacity' => $c->capacity,
            'Current Occupancy' => $c->current_occupancy,
            'Occupancy %' => $c->occupancy_percent . '%',
            'Status' => ucfirst($c->status),
            'Contact Person' => $c->contact_person ?? '—',
            'Contact Number' => $c->contact_number ?? '—',
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Center Name', 'Barangay', 'Address', 'Capacity', 'Current Occupancy', 'Occupancy %', 'Status', 'Contact Person', 'Contact Number'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            5 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Evacuation Centers';
    }
}
