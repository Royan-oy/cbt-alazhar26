<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class GuruMapelTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                '198501012010011001',
                'Matematika',
                '1A, 1B',
                '2025/2026',
                'Ganjil'
            ],
            [
                '198802022012022002',
                'Bahasa Indonesia',
                '1A',
                '2025/2026',
                'Ganjil'
            ]
        ];
    }

    public function headings(): array
    {
        return ['nip_guru', 'mata_pelajaran', 'kelas', 'tahun_ajaran', 'semester'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:E1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('0F172A');

        return [];
    }
}
