<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RekapNilaiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $waliKelas;
    protected $siswas;
    protected $ujians;
    protected $nilaiData;
    protected $kelas;

    public function __construct($waliKelas)
    {
        $this->waliKelas = $waliKelas;

        $this->kelas = DB::table('kelas')
            ->join('tingkats', 'kelas.tingkat_id', '=', 'tingkats.id')
            ->where('kelas.id', $waliKelas->kelas_id)
            ->select('kelas.*', 'tingkats.nama_tingkat')
            ->first();

        $this->siswas = DB::table('siswa_kelas')
            ->join('siswas', 'siswa_kelas.siswa_id', '=', 'siswas.id')
            ->where('siswa_kelas.kelas_id', $waliKelas->kelas_id)
            ->where('siswa_kelas.tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->select('siswas.id', 'siswas.nama', 'siswas.nis')
            ->orderBy('siswas.nama', 'asc')
            ->get();

        $this->ujians = DB::table('ujian_kelas')
            ->join('ujians', 'ujian_kelas.ujian_id', '=', 'ujians.id')
            ->join('bank_soals', 'ujians.bank_soal_id', '=', 'bank_soals.id')
            ->join('mata_pelajarans', 'bank_soals.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->join('jenis_ujians', 'ujians.jenis_ujian_id', '=', 'jenis_ujians.id')
            ->where('ujian_kelas.kelas_id', $waliKelas->kelas_id)
            ->where('ujians.tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->select(
                'ujians.id',
                'ujians.nama_ujian',
                'mata_pelajarans.nama_mapel',
                'jenis_ujians.nama as nama_jenis_ujian'
            )
            ->orderBy('ujians.waktu_mulai', 'asc')
            ->get();

        $this->nilaiData = DB::table('nilais')
            ->whereIn('ujian_id', $this->ujians->pluck('id'))
            ->whereIn('siswa_id', $this->siswas->pluck('id'))
            ->where('status', 'selesai')
            ->select('ujian_id', 'siswa_id', 'nilai_akhir')
            ->get()
            ->groupBy('siswa_id');
    }

    public function collection()
    {
        return $this->siswas;
    }

    public function title(): string
    {
        return 'Rekap Nilai ' . ($this->kelas->nama_kelas ?? 'Kelas');
    }

    public function headings(): array
    {
        $headers = ['No', 'Nama Siswa', 'NIS'];

        foreach ($this->ujians as $ujian) {
            $headers[] = $ujian->nama_ujian . "\n(" . $ujian->nama_mapel . ')';
        }

        $headers[] = 'Rata-rata';

        return $headers;
    }

    public function map($siswa): array
    {
        static $no = 0;
        $no++;

        $row = [$no, $siswa->nama, $siswa->nis];

        $nilaiSiswa = $this->nilaiData->get($siswa->id, collect());
        $nilaiSiswaKeyedByUjian = $nilaiSiswa->keyBy('ujian_id');

        $totalNilai = 0;
        $countNilai = 0;

        foreach ($this->ujians as $ujian) {
            $nilaiRecord = $nilaiSiswaKeyedByUjian->get($ujian->id);
            if ($nilaiRecord) {
                $nilai = (float) $nilaiRecord->nilai_akhir;
                $row[] = $nilai;
                $totalNilai += $nilai;
                $countNilai++;
            } else {
                $row[] = '-';
            }
        }

        $row[] = $countNilai > 0 ? round($totalNilai / $countNilai, 2) : '-';

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3 + count($this->ujians) + 1);
        $headerRange = 'A1:' . $lastCol . '1';

        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0F172A'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);

        // Zebra striping untuk baris data
        $lastRow = count($this->siswas) + 1;
        for ($i = 2; $i <= $lastRow; $i++) {
            if ($i % 2 === 0) {
                $sheet->getStyle('A' . $i . ':' . $lastCol . $i)->applyFromArray([
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F1F5F9'],
                    ],
                ]);
            }
        }

        $sheet->getRowDimension(1)->setRowHeight(36);

        return [];
    }
}
