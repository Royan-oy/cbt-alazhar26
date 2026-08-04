<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SiswaTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'Ahmad Dahlan',
                '12345678',
                '0012345678',
                '123456',
                'SMP',
                'VII',
                'VII A'
            ],
            [
                'Siti Walidah',
                '12345679',
                '0012345679',
                '123456',
                'SD',
                'I',
                '1A'
            ]
        ];
    }

    public function headings(): array
    {
        return ['nama', 'nis', 'nisn', 'password', 'jenjang', 'tingkat', 'kelas'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:G1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('0F172A');

        return [];
    }
}
