<?php

namespace App\Exports;

use App\Exports\Concerns\HasReportMetadata;
use App\Models\AuditLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AuditLogExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    use HasReportMetadata;

    public function __construct(private Collection $logs, private array $metadata) {}

    public function collection(): Enumerable
    {
        return $this->logs->map(fn(AuditLog $log) => [
            'Date' => $log->created_at?->format('M d, Y h:i A') ?? '—',
            'User' => $log->user_name,
            'Role' => $log->user_role,
            'Action' => ucfirst($log->action),
            'Module' => ucfirst(str_replace('_', ' ', $log->module)),
            'Record' => $log->record_label ?? '—',
            'IP Address' => $log->ip_address ?? '—',
            'Notes' => $log->notes ?? '—',
        ]);
    }

    public function headings(): array
    {
        return ['Date', 'User', 'Role', 'Action', 'Module', 'Record', 'IP Address', 'Notes'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [5 => ['font' => ['bold' => true]]];
    }

    public function title(): string
    {
        return 'Audit Trail';
    }
}
