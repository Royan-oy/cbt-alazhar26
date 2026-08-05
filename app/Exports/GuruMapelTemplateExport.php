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
    /**
     * Format kolom PERSIS sama dengan GuruMapelExport, supaya file hasil
     * export bisa langsung dipakai ulang sebagai file import (misal saat
     * ganti tahun ajaran: tinggal export, ubah kolom tahun_ajaran/semester
     * & penugasan kelas, lalu import lagi).
     *
     * Catatan: kolom 'nama_guru' dan 'jenjang' HANYA untuk referensi visual
     * (memudahkan user tahu baris itu milik guru siapa). Kedua kolom ini
     * TIDAK dibaca/divalidasi oleh GuruMapelImport — identitas guru tetap
     * ditentukan dari 'nip_guru'.
     */
    public function array(): array
    {
        return [
            [
                '198501012010011001',
                'Contoh Nama Guru A',
                'SD',
                'Matematika',
                'Kelas VII - VII A, Kelas VII - VII B',
                '2025/2026',
                'Ganjil',
            ],
            [
                '198802022012022002',
                'Contoh Nama Guru B',
                'SD',
                'Bahasa Indonesia',
                'Kelas VII - VII A',
                '2025/2026',
                'Ganjil',
            ],
        ];
    }

    public function headings(): array
    {
        return ['nip_guru', 'nama_guru', 'jenjang', 'mata_pelajaran', 'kelas', 'tahun_ajaran', 'semester'];
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