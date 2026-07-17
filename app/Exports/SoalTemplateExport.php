<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SoalTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    /**
     * Baris contoh supaya guru langsung paham formatnya,
     * bukan cuma header kosong.
     */
    public function array(): array
    {
        return [
            [
                'pilihan_ganda',
                10,
                'Ibu kota Indonesia adalah...',
                'Jakarta',
                'Bandung',
                'Surabaya',
                'Medan',
                '',
                '',
                1,
            ],
            [
                'essay',
                20,
                'Jelaskan proses terjadinya hujan!',
                '', '', '', '', '', '',
                '',
            ],
            [
                'isian',
                5,
                'Planet terbesar di tata surya adalah ____.',
                '', '', '', '', '', '',
                '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Jenis Soal (pilihan_ganda/essay/isian)',
            'Bobot',
            'Teks Soal',
            'Opsi 1',
            'Opsi 2',
            'Opsi 3',
            'Opsi 4',
            'Opsi 5',
            'Opsi 6',
            'Jawaban Benar (nomor opsi, khusus pilihan_ganda)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 32,
            'B' => 10,
            'C' => 45,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 28,
        ];
    }
}