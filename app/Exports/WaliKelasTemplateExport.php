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
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Models\Kelas;
use App\Models\TahunAjaran;

class WaliKelasTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting, WithEvents
{
    public function array(): array
    {
        return [
            [
                '198501012010011001',
                '', // Kelas
                '', // Tahun Ajaran
                ''  // Semester
            ],
            [
                '198802022012022002',
                '',
                '',
                ''
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

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // nip_guru
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $spreadsheet = $sheet->getParent();

                // Buat hidden sheet untuk opsi dropdown
                $optionsSheet = $spreadsheet->createSheet();
                $optionsSheet->setTitle('DropdownOptions');
                $optionsSheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

                // Data Kelas
                $kelasList = Kelas::pluck('nama_kelas')->toArray();
                foreach ($kelasList as $index => $kelas) {
                    $optionsSheet->setCellValue('A' . ($index + 1), $kelas);
                }
                $kelasRange = count($kelasList) > 0 ? 'DropdownOptions!$A$1:$A$' . count($kelasList) : '"Tidak ada data"';

                // Data Tahun Ajaran
                $tahunList = TahunAjaran::select('nama_tahun')->distinct()->pluck('nama_tahun')->toArray();
                foreach ($tahunList as $index => $tahun) {
                    $optionsSheet->setCellValue('B' . ($index + 1), $tahun);
                }
                $tahunRange = count($tahunList) > 0 ? 'DropdownOptions!$B$1:$B$' . count($tahunList) : '"Tidak ada data"';

                // Data Semester
                $semesterList = ['Ganjil', 'Genap'];
                foreach ($semesterList as $index => $semester) {
                    $optionsSheet->setCellValue('C' . ($index + 1), $semester);
                }
                $semesterRange = 'DropdownOptions!$C$1:$C$' . count($semesterList);

                // Validasi Kolom B (Kelas)
                $kelasValidation = $sheet->getCell('B2')->getDataValidation();
                $kelasValidation->setType(DataValidation::TYPE_LIST);
                $kelasValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $kelasValidation->setAllowBlank(true);
                $kelasValidation->setShowDropDown(true);
                $kelasValidation->setShowErrorMessage(true);
                $kelasValidation->setErrorTitle('Input tidak valid');
                $kelasValidation->setError('Silakan pilih kelas dari dropdown.');
                $kelasValidation->setFormula1($kelasRange);

                // Validasi Kolom C (Tahun Ajaran)
                $tahunValidation = $sheet->getCell('C2')->getDataValidation();
                $tahunValidation->setType(DataValidation::TYPE_LIST);
                $tahunValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $tahunValidation->setAllowBlank(true);
                $tahunValidation->setShowDropDown(true);
                $tahunValidation->setShowErrorMessage(true);
                $tahunValidation->setErrorTitle('Input tidak valid');
                $tahunValidation->setError('Silakan pilih tahun ajaran dari dropdown.');
                $tahunValidation->setFormula1($tahunRange);

                // Validasi Kolom D (Semester)
                $semesterValidation = $sheet->getCell('D2')->getDataValidation();
                $semesterValidation->setType(DataValidation::TYPE_LIST);
                $semesterValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $semesterValidation->setAllowBlank(true);
                $semesterValidation->setShowDropDown(true);
                $semesterValidation->setShowErrorMessage(true);
                $semesterValidation->setErrorTitle('Input tidak valid');
                $semesterValidation->setError('Semester harus Ganjil atau Genap.');
                $semesterValidation->setFormula1($semesterRange);

                // Aplikasikan ke 200 baris ke bawah
                for ($i = 2; $i <= 200; $i++) {
                    $sheet->getCell("B{$i}")->setDataValidation(clone $kelasValidation);
                    $sheet->getCell("C{$i}")->setDataValidation(clone $tahunValidation);
                    $sheet->getCell("D{$i}")->setDataValidation(clone $semesterValidation);
                }

                // Kembalikan fokus ke sheet utama
                $spreadsheet->setActiveSheetIndex(0);
            },
        ];
    }
}
