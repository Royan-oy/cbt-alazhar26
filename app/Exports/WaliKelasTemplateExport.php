<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class WaliKelasTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                '198501012010011001',
                '1A',
                '2025/2026',
                'Ganjil'
            ],
            [
                '198802022012022002',
                '1B',
                '2025/2026',
                'Ganjil'
            ]
        ];
    }

    public function headings(): array
    {
        return ['nip_guru', 'kelas', 'tahun_ajaran', 'semester'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A1:D1')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:D1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('0F172A');

        return [];
    }
}
