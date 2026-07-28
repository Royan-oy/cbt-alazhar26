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

class RekapNilaiLeaderboardExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $waliKelas;
    protected $kelas;
    protected $siswas;
    protected $avgScores;
    protected $rankMap;

    public function __construct($waliKelas)
    {
        $this->waliKelas = $waliKelas;

        // 1. Ambil data kelas
        $this->kelas = DB::table('kelas')
            ->join('tingkats', 'kelas.tingkat_id', '=', 'tingkats.id')
            ->where('kelas.id', $waliKelas->kelas_id)
            ->select('kelas.*', 'tingkats.nama_tingkat')
            ->first();

        // 2. Ambil data siswa (urut absen)
        $this->siswas = DB::table('siswa_kelas')
            ->join('siswas', 'siswa_kelas.siswa_id', '=', 'siswas.id')
            ->where('siswa_kelas.kelas_id', $waliKelas->kelas_id)
            ->where('siswa_kelas.tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->select('siswas.id', 'siswas.nama', 'siswas.nis')
            ->orderBy('siswas.nama', 'asc')
            ->get();

        // 3. Ambil data ujian untuk kelas & tahun ajaran aktif
        $ujians = DB::table('ujian_kelas')
            ->join('ujians', 'ujian_kelas.ujian_id', '=', 'ujians.id')
            ->join('bank_soals', 'ujians.bank_soal_id', '=', 'bank_soals.id')
            ->join('mata_pelajarans', 'bank_soals.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->where('ujian_kelas.kelas_id', $waliKelas->kelas_id)
            ->where('ujians.tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->select('ujians.id', 'mata_pelajarans.nama_mapel')
            ->get();

        // 4. Ambil data nilai
        $nilaiData = DB::table('nilais')
            ->whereIn('ujian_id', $ujians->pluck('id'))
            ->whereIn('siswa_id', $this->siswas->pluck('id'))
            ->where('status', 'selesai')
            ->select('ujian_id', 'siswa_id', 'nilai_akhir')
            ->get()
            ->groupBy('siswa_id');

        // 5. Hitung rata-rata
        $groupedUjiansByMapel = $ujians->groupBy('nama_mapel');
        $this->avgScores = collect();

        foreach ($this->siswas as $siswa) {
            $nilaiSiswa = $nilaiData->get($siswa->id, collect());
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
            }

            $overallAvg = $totalAvgCnt > 0 ? round($totalAvgSum / $totalAvgCnt, 1) : null;
            $this->avgScores->put($siswa->id, $overallAvg);
        }

        // 6. Tentukan Ranking (1, 2, 3...)
        $this->rankMap = [];
        $sorted = $this->avgScores->filter(function($avg) { return $avg !== null; })->sortByDesc(function($avg) { return $avg; });
        $currentRank = 0;
        foreach ($sorted as $sid => $avg) {
            $currentRank++;
            $this->rankMap[$sid] = $currentRank;
        }
    }

    public function collection()
    {
        return $this->siswas;
    }

    public function title(): string
    {
        return 'Leaderboard ' . ($this->kelas->nama_kelas ?? 'Kelas');
    }

    public function headings(): array
    {
        return [
            'No',
            'NIS',
            'Nama Siswa',
            'Rata-Rata',
            'Peringkat'
        ];
    }

    public function map($siswa): array
    {
        static $no = 0;
        $no++;

        $avg = $this->avgScores->get($siswa->id);
        $rank = $this->rankMap[$siswa->id] ?? null;
        
        $rankDisplay = $rank ? 'Ke-' . $rank : '-';

        return [
            $no,
            $siswa->nis,
            $siswa->nama,
            $avg !== null ? $avg : '-',
            $rankDisplay
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = 'E';
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
            ],
        ]);

        $sheet->getStyle('A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

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
