<?php

namespace App\Exports\Concerns;

use Maatwebsite\Excel\Events\AfterSheet;

trait HasReportMetadata
{
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $sheet->insertNewRowBefore(1, 4);

                foreach ($this->metadata as $row => $values) {
                    $sheet->fromArray($values, null, 'A'.$row);
                }

                $sheet->getStyle('A1:B4')->getFont()->setBold(true);
                $sheet->getStyle('A1:B4')->getFill()
                    ->setFillType('solid')
                    ->getStartColor()->setARGB('FFF3F4F6');
            },
        ];
    }
}
