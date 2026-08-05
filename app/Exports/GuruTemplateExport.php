<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class GuruTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    public function array(): array
    {
        return [
            [
                'Budi Santoso, S.Pd',
                '198501012010011001',
                'budi@example.com',
                '081234567890',
                '123456',
                'SD'
            ],
            [
                'Siti Aminah, M.Pd',
                '198802022012022002',
                'siti@example.com',
                '081234567891',
                '123456',
                'SD'
            ]
        ];
    }

    public function headings(): array
    {
        return ['nama', 'nip', 'email', 'no_hp', 'password', 'jenjang'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:F1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('0F172A');

        return [];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // nip
            'D' => NumberFormat::FORMAT_TEXT, // no_hp
        ];
    }
}
