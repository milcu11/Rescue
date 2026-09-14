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

class DonationsExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    use HasReportMetadata;

    public function __construct(private Collection $donations, private array $metadata) {}

    public function collection(): Enumerable
    {
        return $this->donations->map(fn($d) => [
            'Tracking Code' => $d->tracking_code,
            'Donor Name' => $d->donor_name,
            'Contact' => $d->donor_contact ?? '—',
            'Email' => $d->donor_email ?? '—',
            'Type' => ucfirst($d->type),
            'Amount (₱)' => $d->type === 'monetary' ? number_format($d->amount, 2) : '—',
            'Items' => $d->type === 'in-kind' ? $d->items_description : '—',
            'Status' => ucfirst($d->status),
            'Received By' => $d->received_by ?? '—',
            'Location' => $d->location ?? '—',
            'Date Recorded' => $d->created_at?->format('M d, Y') ?? '—',
        ]);
    }

    public function headings(): array
    {
        return ['Tracking Code', 'Donor Name', 'Contact', 'Email', 'Type', 'Amount (₱)', 'Items', 'Status', 'Received By', 'Location', 'Date Recorded'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            5 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Donations';
    }
}
