<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class SoalTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    /**
     * Baris contoh serbaguna (Unified) untuk 6 jenis soal
     */
    public function array(): array
    {
        return [
            [
                'Pilihan Ganda',
                10,
                'Siapakah Nabi terakhir yang diutus Allah SWT?',
                'Nabi Ibrahim AS',
                'Nabi Musa AS',
                'Nabi Muhammad SAW',
                'Nabi Isa AS',
                '',
                '',
                '3', // atau 'C'
            ],
            [
                'Pilihan Ganda Kompleks',
                15,
                'Manakah yang termasuk Rukun Islam? (Pilih lebih dari satu)',
                'Syahadat',
                'Membaca Al-Qur\'an tiap malam',
                'Sholat 5 Waktu',
                'Zakat',
                '',
                '',
                '1, 3, 4', // atau 'A, C, D'
            ],
            [
                'Benar / Salah',
                15,
                'Tentukan hukum bacaan Tajwid berikut Benar atau Salah:',
                'Idgham Bighunnah apabila Nun Mati bertemu Mim',
                'Izhar Halqi dibaca mendengung 3 harakat',
                'Huruf Qalqalah ada 5 huruf (ق ط ب ج د)',
                '',
                '',
                '',
                'Benar, Salah, Benar', // atau 'B, S, B'
            ],
            [
                'Mencocokkan',
                15,
                'Pasangkan Kitab Allah dengan Nabi penerimanya:',
                'Kitab Taurat',
                'Kitab Zabur',
                'Kitab Injil',
                '',
                '',
                '',
                'Nabi Musa AS | Nabi Daud AS | Nabi Isa AS', // dipisah tanda | sesuai urutan opsi
            ],
            [
                'Isian Singkat',
                10,
                'Kota tempat kelahiran Nabi Muhammad SAW adalah ____.',
                '', '', '', '', '', '',
                'Makkah; Mekkah; Kota Makkah', // Kunci alternatif dipisah semikolon ;
            ],
            [
                'Essay',
                20,
                'Jelaskan hikmah sholat berjamaah dalam kehidupan masyarakat!',
                '', '', '', '', '', '',
                '', // Kosong untuk essay
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Jenis Soal',
            'Bobot',
            'Teks Soal',
            'Opsi 1 / Item 1',
            'Opsi 2 / Item 2',
            'Opsi 3 / Item 3',
            'Opsi 4 / Item 4',
            'Opsi 5 / Item 5',
            'Opsi 6 / Item 6',
            'Kunci Jawaban (Sesuai Petunjuk Jenis Soal)',
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
            'A' => 38,
            'B' => 10,
            'C' => 45,
            'D' => 24,
            'E' => 24,
            'F' => 24,
            'G' => 24,
            'H' => 20,
            'I' => 20,
            'J' => 40,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                $validation = $sheet->getCell('A2')->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input Error');
                $validation->setError('Jenis soal tidak valid. Silakan pilih dari dropdown.');
                $validation->setPromptTitle('Pilih Jenis Soal');
                $validation->setPrompt('Silakan pilih salah satu dari daftar jenis soal yang tersedia.');
                // Formula list in double quotes, comma-separated
                $validation->setFormula1('"Pilihan Ganda,Pilihan Ganda Kompleks,Benar / Salah,Mencocokkan,Isian Singkat,Essay"');
                
                // Aplikasikan validasi ke baris 2 hingga 500
                for ($i = 2; $i <= 500; $i++) {
                    $sheet->getCell("A{$i}")->setDataValidation(clone $validation);
                }
            },
        ];
    }
}