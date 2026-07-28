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

class RekapNilaiMatriksExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $waliKelas;
    protected $kelas;
    protected $siswas;
    protected $mapels;
    protected $matrixData;
    protected $avgScores;
    protected $jenisFilter;

    public function __construct($waliKelas, $jenisFilter)
    {
        $this->waliKelas = $waliKelas;
        $this->jenisFilter = $jenisFilter;

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

        $ujians = DB::table('ujian_kelas')
            ->join('ujians', 'ujian_kelas.ujian_id', '=', 'ujians.id')
            ->join('bank_soals', 'ujians.bank_soal_id', '=', 'bank_soals.id')
            ->join('mata_pelajarans', 'bank_soals.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->join('jenis_ujians', 'ujians.jenis_ujian_id', '=', 'jenis_ujians.id')
            ->where('ujian_kelas.kelas_id', $waliKelas->kelas_id)
            ->where('ujians.tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->where('jenis_ujians.nama', $jenisFilter)
            ->select('ujians.id', 'mata_pelajarans.nama_mapel')
            ->get();

        $nilaiData = DB::table('nilais')
            ->whereIn('ujian_id', $ujians->pluck('id'))
            ->whereIn('siswa_id', $this->siswas->pluck('id'))
            ->where('status', 'selesai')
            ->select('ujian_id', 'siswa_id', 'nilai_akhir')
            ->get()
            ->groupBy('siswa_id');

        $groupedUjiansByMapel = $ujians->groupBy('nama_mapel');
        $this->mapels = $groupedUjiansByMapel->keys()->values();

        $this->matrixData = collect();
        $this->avgScores = collect();

        foreach ($this->siswas as $siswa) {
            $nilaiSiswa = $nilaiData->get($siswa->id, collect());
            $mapelScores = collect();
            $totalAvgSum = 0;
            $totalAvgCnt = 0;

            foreach ($groupedUjiansByMapel as $mapelNama => $mapelUjians) {
                $sum = 0;
                $cnt = 0;
                $records = $nilaiSiswa->whereIn('ujian_id', $mapelUjians->pluck('id'));

                foreach ($mapelUjians as $u) {
                    $rec = $records->firstWhere('ujian_id', $u->id);
                    $val = $rec ? (float)$rec->nilai_akhir : null;
                    if ($val !== null) {
                        $sum += $val;
                        $cnt++;
                    }
                }

                $avg = $cnt > 0 ? round($sum / $cnt, 1) : null;
                if ($avg !== null) {
                    $totalAvgSum += $avg;
                    $totalAvgCnt++;
                }
                $mapelScores->put($mapelNama, $avg);
            }

            $overallAvg = $totalAvgCnt > 0 ? round($totalAvgSum / $totalAvgCnt, 1) : null;
            $this->matrixData->put($siswa->id, $mapelScores);
            $this->avgScores->put($siswa->id, $overallAvg);
        }
    }

    public function collection()
    {
        return $this->siswas;
    }

    public function title(): string
    {
        return 'Matriks ' . substr(str_replace(['/', '\\', '?', '*', ':', '[', ']'], '', $this->jenisFilter), 0, 20);
    }

    public function headings(): array
    {
        $headers = ['No', 'NIS', 'Nama Siswa'];

        foreach ($this->mapels as $mapel) {
            $headers[] = strtoupper($mapel);
        }

        $headers[] = 'Rata-rata';

        return $headers;
    }

    public function map($siswa): array
    {
        static $no = 0;
        $no++;

        $row = [$no, $siswa->nis, $siswa->nama];

        $mapelScores = $this->matrixData->get($siswa->id);

        foreach ($this->mapels as $mapel) {
            $score = $mapelScores->get($mapel);
            $row[] = $score !== null ? $score : '-';
        }

        $overallAvg = $this->avgScores->get($siswa->id);
        $row[] = $overallAvg !== null ? $overallAvg : '-';

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3 + count($this->mapels) + 1);
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

        $sheet->getStyle('A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $dataColStart = 4;
        $dataColEnd = 3 + count($this->mapels) + 1;
        for ($col = $dataColStart; $col <= $dataColEnd; $col++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet->getStyle($colLetter)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $lastRow = count($this->siswas) + 1;
        for ($i = 2; $i <= $lastRow; $i++) {
            if ($i % 2 === 0) {
                $sheet->getStyle('A' . $i . ':' . $lastCol . $i)->applyFromArray([
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8FAFC'],
                    ],
                ]);
            }
        }

        $sheet->getRowDimension(1)->setRowHeight(30);

        return [];
    }
}
