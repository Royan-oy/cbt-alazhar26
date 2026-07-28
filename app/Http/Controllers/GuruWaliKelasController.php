<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Nilai;
use App\Models\Ujian;
use App\Exports\RekapNilaiLeaderboardExport;
use App\Exports\RekapNilaiMatriksExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;

class GuruWaliKelasController extends Controller
{
    /**
     * Ambil data wali kelas aktif dari guru yang sedang login.
     * Mengembalikan object wali_kelas atau abort 403 jika tidak ditemukan.
     */
    private function getWaliKelasAktif()
    {
        $user = Auth::user();

        if ($user->role !== 'guru' || !$user->guru) {
            abort(403, 'Akses ditolak. Hanya guru yang dapat mengakses halaman ini.');
        }

        $activeTahunAjaran = DB::table('tahun_ajarans')->where('is_aktif', true)->first();

        if (!$activeTahunAjaran) {
            abort(403, 'Tidak ada tahun ajaran yang aktif saat ini.');
        }

        $waliKelas = DB::table('wali_kelas')
            ->where('guru_id', $user->guru->id)
            ->where('tahun_ajaran_id', $activeTahunAjaran->id)
            ->first();

        if (!$waliKelas) {
            abort(403, 'Anda tidak terdaftar sebagai Wali Kelas pada tahun ajaran yang aktif.');
        }

        $waliKelas->tahunAjaran = $activeTahunAjaran;

        return $waliKelas;
    }

    /**
     * Halaman: Data Kelas
     * Menampilkan informasi kelas dan daftar siswa yang diwalikan.
     */
    public function dataKelas(Request $request)
    {
        $waliKelas = $this->getWaliKelasAktif();

        $kelas = DB::table('kelas')
            ->join('tingkats', 'kelas.tingkat_id', '=', 'tingkats.id')
            ->join('jenjangs', 'tingkats.jenjang_id', '=', 'jenjangs.id')
            ->where('kelas.id', $waliKelas->kelas_id)
            ->select(
                'kelas.*',
                'tingkats.nama_tingkat',
                'jenjangs.nama_jenjang'
            )
            ->first();

        $search = $request->input('search', '');

        $siswaQuery = DB::table('siswa_kelas')
            ->join('siswas', 'siswa_kelas.siswa_id', '=', 'siswas.id')
            ->where('siswa_kelas.kelas_id', $waliKelas->kelas_id)
            ->where('siswa_kelas.tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->select(
                'siswas.id',
                'siswas.nama',
                'siswas.nis',
                'siswas.nisn',
                'siswas.foto',
                'siswa_kelas.id as siswa_kelas_id'
            )
            ->orderBy('siswas.nama', 'asc');

        if ($search) {
            $siswaQuery->where(function ($q) use ($search) {
                $q->where('siswas.nama', 'like', '%' . $search . '%')
                  ->orWhere('siswas.nis', 'like', '%' . $search . '%')
                  ->orWhere('siswas.nisn', 'like', '%' . $search . '%');
            });
        }

        $siswas = $siswaQuery->paginate(20)->withQueryString();

        $totalSiswa = DB::table('siswa_kelas')
            ->where('kelas_id', $waliKelas->kelas_id)
            ->where('tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->count();

        // Statistik jenis kelamin tidak ada di database, cukup tampilkan total
        return view('guru.wali-kelas.data-kelas', compact(
            'waliKelas',
            'kelas',
            'siswas',
            'totalSiswa',
            'search'
        ));
    }

    /**
     * Halaman: Detail Siswa
     * Menampilkan detail informasi seorang siswa.
     */
    public function showSiswa($id)
    {
        $waliKelas = $this->getWaliKelasAktif();

        // Pastikan siswa ini memang di kelas yang diwalikan
        $isInKelas = DB::table('siswa_kelas')
            ->where('siswa_id', $id)
            ->where('kelas_id', $waliKelas->kelas_id)
            ->where('tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->exists();

        if (!$isInKelas) {
            abort(403, 'Siswa ini tidak berada di kelas Anda.');
        }

        $siswa = DB::table('siswas')
            ->where('id', $id)
            ->first();

        if (!$siswa) {
            abort(404, 'Siswa tidak ditemukan.');
        }

        $kelas = DB::table('kelas')
            ->join('tingkats', 'kelas.tingkat_id', '=', 'tingkats.id')
            ->join('jenjangs', 'tingkats.jenjang_id', '=', 'jenjangs.id')
            ->where('kelas.id', $waliKelas->kelas_id)
            ->select(
                'kelas.*',
                'tingkats.nama_tingkat',
                'jenjangs.nama_jenjang'
            )
            ->first();

        // Ambil riwayat ujian siswa (optional, tapi bagus untuk detail)
        $riwayatUjian = DB::table('nilais')
            ->join('ujians', 'nilais.ujian_id', '=', 'ujians.id')
            ->join('bank_soals', 'ujians.bank_soal_id', '=', 'bank_soals.id')
            ->join('mata_pelajarans', 'bank_soals.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->where('nilais.siswa_id', $id)
            ->select(
                'ujians.nama_ujian',
                'mata_pelajarans.nama_mapel',
                'nilais.waktu_mulai_kerja',
                'nilais.waktu_kumpul',
                'nilais.nilai_akhir',
                'nilais.status'
            )
            ->orderBy('nilais.waktu_mulai_kerja', 'desc')
            ->get();

        return view('guru.wali-kelas.show-siswa', compact('waliKelas', 'siswa', 'kelas', 'riwayatUjian'));
    }

    /**
     * Halaman: Monitoring Siswa
     * Menampilkan status ujian siswa secara real-time.
     */
    public function monitoringSiswa(Request $request)
    {
        $waliKelas = $this->getWaliKelasAktif();

        // Ambil semua siswa_id di kelas ini
        $siswaIds = DB::table('siswa_kelas')
            ->where('kelas_id', $waliKelas->kelas_id)
            ->where('tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->pluck('siswa_id');

        // Ujian yang sedang aktif / berlangsung pada tahun ajaran yang sama
        $ujians = DB::table('ujians')
            ->join('bank_soals', 'ujians.bank_soal_id', '=', 'bank_soals.id')
            ->join('mata_pelajarans', 'bank_soals.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->join('jenis_ujians', 'ujians.jenis_ujian_id', '=', 'jenis_ujians.id')
            ->where('ujians.tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->where('ujians.waktu_mulai', '<=', now())
            ->where('ujians.waktu_selesai', '>=', now())
            ->select(
                'ujians.id',
                'ujians.nama_ujian',
                'ujians.waktu_mulai',
                'ujians.waktu_selesai',
                'mata_pelajarans.nama_mapel',
                'jenis_ujians.nama as nama_jenis_ujian'
            )
            ->get();

        // Filter ujian yang ada di kelas ini
        $ujianIds = DB::table('ujian_kelas')
            ->whereIn('ujian_id', $ujians->pluck('id'))
            ->where('kelas_id', $waliKelas->kelas_id)
            ->pluck('ujian_id');

        $ujians = $ujians->whereIn('id', $ujianIds->toArray())->values();

        // Data monitoring per-ujian
        $selectedUjianId = $request->input('ujian_id', optional($ujians->first())->id);

        $monitoring = collect();
        $totalSoal = 0;

        if ($selectedUjianId) {
            $totalSoal = DB::table('soals')
                ->join('bank_soals', 'soals.bank_soal_id', '=', 'bank_soals.id')
                ->join('ujians', 'ujians.bank_soal_id', '=', 'bank_soals.id')
                ->where('ujians.id', $selectedUjianId)
                ->count();

            $monitoring = DB::table('siswas')
                ->leftJoin('nilais', function ($join) use ($selectedUjianId) {
                    $join->on('nilais.siswa_id', '=', 'siswas.id')
                         ->where('nilais.ujian_id', '=', $selectedUjianId);
                })
                ->whereIn('siswas.id', $siswaIds)
                ->select(
                    'siswas.id as siswa_id',
                    'siswas.nama',
                    'siswas.nis',
                    'nilais.id as nilai_id',
                    'nilais.status',
                    'nilais.current_question',
                    'nilais.violation_count',
                    'nilais.waktu_mulai_kerja',
                    'nilais.waktu_kumpul',
                    'nilais.last_autosave',
                    'nilais.nilai_akhir'
                )
                ->orderBy('siswas.nama', 'asc')
                ->get()
                ->map(function ($row) use ($totalSoal) {
                    $row->status = $row->status ?? 'belum';
                    $row->progress = $totalSoal > 0
                        ? round(($row->current_question / $totalSoal) * 100)
                        : 0;
                    return $row;
                });
                
            // Simpan statistik keseluruhan sebelum di-filter
            $overallStats = [
                'totalSiswa' => $monitoring->count(),
                'cntBelum' => $monitoring->where('status', 'belum')->count(),
                'cntMengerjakan' => $monitoring->where('status', 'mengerjakan')->count(),
                'cntSelesai' => $monitoring->where('status', 'selesai')->count(),
            ];

            // Filter Pencarian (Search by Nama)
            $search = $request->input('search');
            if ($search) {
                $monitoring = $monitoring->filter(function ($row) use ($search) {
                    return stripos($row->nama, $search) !== false;
                })->values();
            }

            // Filter Status
            $statusFilter = $request->input('status');
            if ($statusFilter && $statusFilter !== 'semua') {
                $monitoring = $monitoring->where('status', $statusFilter)->values();
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'monitoring' => $monitoring,
                'totalSoal' => $totalSoal,
                'totalSiswa' => $overallStats['totalSiswa'] ?? 0,
                'cntBelum' => $overallStats['cntBelum'] ?? 0,
                'cntMengerjakan' => $overallStats['cntMengerjakan'] ?? 0,
                'cntSelesai' => $overallStats['cntSelesai'] ?? 0,
            ]);
        }

        return view('guru.wali-kelas.monitoring', compact(
            'waliKelas',
            'ujians',
            'selectedUjianId',
            'monitoring',
            'totalSoal'
        ));
    }

    /**
     * Action: Force Submit ujian seorang siswa
     */
    public function forceSubmit(Request $request, $nilaiId)
    {
        $waliKelas = $this->getWaliKelasAktif();

        $nilai = Nilai::findOrFail($nilaiId);

        // Pastikan siswa ini memang di kelas yang diwalikan
        $isInKelas = DB::table('siswa_kelas')
            ->where('siswa_id', $nilai->siswa_id)
            ->where('kelas_id', $waliKelas->kelas_id)
            ->where('tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->exists();

        if (!$isInKelas) {
            return back()->with('error', 'Siswa ini tidak berada di kelas Anda.');
        }

        if ($nilai->status === 'selesai') {
            return back()->with('warning', 'Ujian siswa ini sudah dalam status selesai.');
        }

        $nilai->update([
            'status'       => 'selesai',
            'waktu_kumpul' => now(),
        ]);

        return back()->with('success', 'Ujian siswa berhasil di-force submit.');
    }

    /**
     * Action: Reset sesi ujian siswa (agar bisa mengulang dari awal)
     */
    public function resetUjian(Request $request, $nilaiId)
    {
        $waliKelas = $this->getWaliKelasAktif();

        $nilai = Nilai::findOrFail($nilaiId);

        // Pastikan siswa ini memang di kelas yang diwalikan
        $isInKelas = DB::table('siswa_kelas')
            ->where('siswa_id', $nilai->siswa_id)
            ->where('kelas_id', $waliKelas->kelas_id)
            ->where('tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->exists();

        if (!$isInKelas) {
            return back()->with('error', 'Siswa ini tidak berada di kelas Anda.');
        }

        // Hapus semua jawaban siswa untuk sesi ini, lalu reset nilai record
        DB::table('jawaban_siswas')->where('nilai_id', $nilai->id)->delete();

        $nilai->update([
            'status'           => 'belum',
            'waktu_mulai_kerja' => null,
            'waktu_kumpul'     => null,
            'nilai_pg'         => 0,
            'nilai_essay'      => 0,
            'nilai_akhir'      => 0,
            'current_question' => 0,
            'violation_count'  => 0,
            'last_autosave'    => null,
        ]);

        return back()->with('success', 'Sesi ujian siswa berhasil direset. Siswa dapat mengulang ujian.');
    }

    /**
     * Halaman: Rekap Nilai
     * Menampilkan matrix nilai semua siswa untuk semua ujian di kelas ini.
     */
    public function rekapNilai(Request $request)
    {
        $waliKelas = $this->getWaliKelasAktif();

        $kelas = DB::table('kelas')
            ->join('tingkats', 'kelas.tingkat_id', '=', 'tingkats.id')
            ->where('kelas.id', $waliKelas->kelas_id)
            ->select('kelas.*', 'tingkats.nama_tingkat')
            ->first();

        // Semua siswa di kelas ini
        $siswas = DB::table('siswa_kelas')
            ->join('siswas', 'siswa_kelas.siswa_id', '=', 'siswas.id')
            ->where('siswa_kelas.kelas_id', $waliKelas->kelas_id)
            ->where('siswa_kelas.tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->select('siswas.id', 'siswas.nama', 'siswas.nis')
            ->orderBy('siswas.nama', 'asc')
            ->get();

        // Semua ujian yang ditargetkan ke kelas ini pada tahun ajaran aktif
        $ujians = DB::table('ujian_kelas')
            ->join('ujians', 'ujian_kelas.ujian_id', '=', 'ujians.id')
            ->join('bank_soals', 'ujians.bank_soal_id', '=', 'bank_soals.id')
            ->join('mata_pelajarans', 'bank_soals.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->join('jenis_ujians', 'ujians.jenis_ujian_id', '=', 'jenis_ujians.id')
            ->where('ujian_kelas.kelas_id', $waliKelas->kelas_id)
            ->where('ujians.tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->select(
                'ujians.id',
                'ujians.nama_ujian',
                'ujians.waktu_selesai',
                'mata_pelajarans.nama_mapel',
                'jenis_ujians.nama as nama_jenis_ujian',
                'bank_soals.kkm'
            )
            ->orderBy('ujians.waktu_mulai', 'asc')
            ->get();

        // Filter jenis ujian
        $jenisFilter = $request->input('jenis_ujian', '');
        if ($jenisFilter) {
            $ujians = $ujians->where('nama_jenis_ujian', $jenisFilter)->values();
        }

        // Ambil semua data nilai sekaligus (efisien, tidak N+1)
        $nilaiData = DB::table('nilais')
            ->whereIn('ujian_id', $ujians->pluck('id'))
            ->whereIn('siswa_id', $siswas->pluck('id'))
            ->where('status', 'selesai')
            ->select('ujian_id', 'siswa_id', 'nilai_akhir')
            ->get()
            ->groupBy('siswa_id');

        $allJenisUjian = DB::table('ujian_kelas')
            ->join('ujians', 'ujian_kelas.ujian_id', '=', 'ujians.id')
            ->join('jenis_ujians', 'ujians.jenis_ujian_id', '=', 'jenis_ujians.id')
            ->where('ujian_kelas.kelas_id', $waliKelas->kelas_id)
            ->where('ujians.tahun_ajaran_id', $waliKelas->tahun_ajaran_id)
            ->pluck('jenis_ujians.nama')
            ->unique()
            ->values();

        // Hitung Statistik Kelas & Per-Siswa
        $studentSummaries = collect();
        $totalNilaiKelas = 0;
        $countSiswaDenganNilai = 0;
        $tuntasCount = 0;
        $topScore = -1;
        $topSiswaNama = '-';

        foreach ($siswas as $siswa) {
            $nilaiSiswa = $nilaiData->get($siswa->id, collect());
            $sum = 0;
            $cnt = 0;

            foreach ($ujians as $ujian) {
                $record = $nilaiSiswa->firstWhere('ujian_id', $ujian->id);
                if ($record && $record->nilai_akhir !== null) {
                    $sum += (float)$record->nilai_akhir;
                    $cnt++;
                }
            }

            $avg = $cnt > 0 ? round($sum / $cnt, 1) : null;
            if ($avg !== null) {
                $totalNilaiKelas += $avg;
                $countSiswaDenganNilai++;
                if ($avg >= 75) {
                    $tuntasCount++;
                }
                if ($avg > $topScore) {
                    $topScore = $avg;
                    $topSiswaNama = $siswa->nama;
                }
            }

            $studentSummaries->put($siswa->id, [
                'avg'    => $avg,
                'count'  => $cnt,
                'status' => $avg === null ? 'belum' : ($avg >= 75 ? 'tuntas' : 'kurang'),
            ]);
        }

        $rerataKelas = $countSiswaDenganNilai > 0 ? round($totalNilaiKelas / $countSiswaDenganNilai, 1) : 0;
        $persenTuntas = $countSiswaDenganNilai > 0 ? round(($tuntasCount / $countSiswaDenganNilai) * 100) : 0;
        if ($topScore < 0) {
            $topScore = 0;
        }

        // Hitung Statistik per Mata Pelajaran
        $mapelStats = collect();
        $groupedUjiansByMapel = $ujians->groupBy('nama_mapel');
        $mapels = $groupedUjiansByMapel->keys()->values();

        // Construct Matriks Per-Siswa Per-Mata Pelajaran + Detail Popover
        $studentMapelMatrix = collect();

        foreach ($siswas as $siswa) {
            $nilaiSiswa = $nilaiData->get($siswa->id, collect());
            $mapelScores = collect();

            foreach ($groupedUjiansByMapel as $mapelNama => $mapelUjians) {
                $mapelUjianIds = $mapelUjians->pluck('id');
                $records = $nilaiSiswa->whereIn('ujian_id', $mapelUjianIds);

                $sum = 0;
                $cnt = 0;
                $details = [];

                foreach ($mapelUjians as $u) {
                    $rec = $records->firstWhere('ujian_id', $u->id);
                    $val = $rec ? (float)$rec->nilai_akhir : null;
                    if ($val !== null) {
                        $sum += $val;
                        $cnt++;
                    }
                    $details[] = [
                        'nama_ujian' => $u->nama_ujian,
                        'jenis'      => $u->nama_jenis_ujian,
                        'kkm'        => $u->kkm ?? 75,
                        'nilai'      => $val,
                    ];
                }

                $avg = $cnt > 0 ? round($sum / $cnt, 1) : null;
                $mapelScores->put($mapelNama, [
                    'avg'     => $avg,
                    'count'   => $cnt,
                    'kkm'     => $mapelUjians->first()->kkm ?? 75,
                    'details' => $details,
                ]);
            }

            $studentMapelMatrix->put($siswa->id, $mapelScores);
        }

        foreach ($groupedUjiansByMapel as $mapelNama => $mapelUjians) {
            $mapelUjianIds = $mapelUjians->pluck('id');
            $mapelNilais = collect();

            foreach ($nilaiData as $siswaId => $records) {
                foreach ($records as $rec) {
                    if ($mapelUjianIds->contains($rec->ujian_id) && $rec->nilai_akhir !== null) {
                        $mapelNilais->push((float)$rec->nilai_akhir);
                    }
                }
            }

            $mapelAvg = $mapelNilais->isNotEmpty() ? round($mapelNilais->avg(), 1) : 0;
            $mapelMax = $mapelNilais->isNotEmpty() ? round($mapelNilais->max(), 1) : 0;
            $mapelMin = $mapelNilais->isNotEmpty() ? round($mapelNilais->min(), 1) : 0;
            $mapelKkm = $mapelUjians->first()->kkm ?? 75;

            $mapelStats->push([
                'nama_mapel'  => $mapelNama,
                'total_ujian' => $mapelUjians->count(),
                'rerata'      => $mapelAvg,
                'max'         => $mapelMax,
                'min'         => $mapelMin,
                'kkm'         => $mapelKkm,
            ]);
        }

        // Hitung ranking berdasarkan rata-rata (descending)
        $rankMap = [];
        $sortedAvgs = $studentSummaries->filter(function($s) { return $s['avg'] !== null; })
            ->sortByDesc(function($s) { return $s['avg']; });
        $currentRank = 0;
        foreach ($sortedAvgs as $sid => $s) {
            $currentRank++;
            $rankMap[$sid] = $currentRank;
        }

        // Urutkan siswa berdasarkan rata-rata tertinggi
        $siswas = $siswas->sortBy(function($siswa) use ($rankMap) {
            return $rankMap[$siswa->id] ?? PHP_INT_MAX;
        })->values();

        return view('guru.wali-kelas.rekap-nilai', compact(
            'waliKelas',
            'kelas',
            'siswas',
            'allJenisUjian',
            'jenisFilter',
            'studentSummaries',
            'rankMap',
            'rerataKelas',
            'persenTuntas',
            'tuntasCount',
            'topScore',
            'topSiswaNama'
        ));
    }


    /**
     * Export: Rekap Nilai ke Excel
     */
    public function exportRekap(Request $request)
    {
        $waliKelas = $this->getWaliKelasAktif();

        $kelas = DB::table('kelas')
            ->join('tingkats', 'kelas.tingkat_id', '=', 'tingkats.id')
            ->where('kelas.id', $waliKelas->kelas_id)
            ->select('kelas.*', 'tingkats.nama_tingkat')
            ->first();

        $jenisFilter = $request->input('jenis_ujian', '');

        if (empty($jenisFilter)) {
            $filename = 'Rekap_Leaderboard_' . str_replace(' ', '_', $kelas->nama_kelas ?? 'kelas') . '.xlsx';
            return Excel::download(new RekapNilaiLeaderboardExport($waliKelas), $filename);
        } else {
            $filename = 'Rekap_Nilai_' . str_replace(' ', '_', $jenisFilter) . '_' . str_replace(' ', '_', $kelas->nama_kelas ?? 'kelas') . '.xlsx';
            return Excel::download(new RekapNilaiMatriksExport($waliKelas, $jenisFilter), $filename);
        }
    }

    /**
     * Export PDF: Rekap Matriks Nilai Kelas (Landscape)
     */
    public function exportPdf(Request $request)
    {
        $waliKelas = $this->getWaliKelasAktif();

        $kelas = DB::table('kelas')
            ->join('tingkats', 'kelas.tingkat_id', '=', 'tingkats.id')
            ->where('kelas.id', $waliKelas->kelas_id)
            ->select('kelas.*', 'tingkats.nama_tingkat')
            ->first();

        $activeTahunAjaran = $waliKelas->tahunAjaran;

        // Selalu ekspor seluruh siswa (abaikan filter pencarian nama)
        $siswas = DB::table('siswa_kelas')
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
            ->select(
                'ujians.id',
                'ujians.nama_ujian',
                'mata_pelajarans.nama_mapel',
                'jenis_ujians.nama as nama_jenis_ujian',
                'bank_soals.kkm'
            )
            ->orderBy('ujians.waktu_mulai', 'asc')
            ->get();

        $jenisFilter = $request->input('jenis_ujian', '');
        if ($jenisFilter) {
            $ujians = $ujians->where('nama_jenis_ujian', $jenisFilter)->values();
        }

        $nilaiData = DB::table('nilais')
            ->whereIn('ujian_id', $ujians->pluck('id'))
            ->whereIn('siswa_id', $siswas->pluck('id'))
            ->where('status', 'selesai')
            ->select('ujian_id', 'siswa_id', 'nilai_akhir')
            ->get()
            ->groupBy('siswa_id');

        $groupedUjiansByMapel = $ujians->groupBy('nama_mapel');
        $mapels = $groupedUjiansByMapel->keys()->values();

        $matrixData = collect();
        $avgScores = collect();

        foreach ($siswas as $siswa) {
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
                $mapelScores->put($mapelNama, ['avg' => $avg, 'kkm' => $mapelUjians->first()->kkm ?? 75]);
            }

            $overallAvg = $totalAvgCnt > 0 ? round($totalAvgSum / $totalAvgCnt, 1) : null;
            $matrixData->put($siswa->id, $mapelScores);
            $avgScores->put($siswa->id, $overallAvg);
        }

        // KONDISI A: Tanpa filter jenis ujian -> Leaderboard Kelas (Portrait)
        if (empty($jenisFilter)) {
            $rankMap = [];
            $sorted = $avgScores->filter(function($avg) { return $avg !== null; })->sortByDesc(function($avg) { return $avg; });
            $currentRank = 0;
            foreach ($sorted as $sid => $avg) {
                $currentRank++;
                $rankMap[$sid] = $currentRank;
            }
            // Siswa tetap diurutkan berdasarkan nama (absen) — rankMap hanya untuk kolom peringkat

            $pdf = PDF::loadView('pdf.rekap-leaderboard', compact(
                'kelas', 'activeTahunAjaran', 'siswas', 'avgScores', 'rankMap'
            ))->setPaper('a4', 'portrait');

            $fileName = 'Rekap_Leaderboard_' . Str::slug($kelas->nama_kelas) . '.pdf';
            return $pdf->download($fileName);
        }

        // KONDISI B: Dengan filter jenis ujian → Matriks Nilai (Landscape)
        $pdf = PDF::loadView('pdf.rekap-wali-kelas', compact(
            'kelas', 'activeTahunAjaran', 'siswas', 'mapels',
            'matrixData', 'avgScores', 'jenisFilter'
        ))->setPaper('a4', 'landscape');

        $fileName = 'Rekap_Nilai_' . Str::slug($jenisFilter) . '_' . Str::slug($kelas->nama_kelas) . '.pdf';
        return $pdf->download($fileName);
    }


    /**
     * Halaman: Detail Nilai Per-Siswa
     */
    public function detailNilaiSiswa(Request $request, $siswaId)
    {
        $waliKelas = $this->getWaliKelasAktif();

        $kelas = DB::table('kelas')
            ->join('tingkats', 'kelas.tingkat_id', '=', 'tingkats.id')
            ->where('kelas.id', $waliKelas->kelas_id)
            ->select('kelas.*', 'tingkats.nama_tingkat')
            ->first();

        $siswa = DB::table('siswas')->where('id', $siswaId)->first();
        abort_if(!$siswa, 404, 'Siswa tidak ditemukan.');

        $ujians = DB::table('ujian_kelas')
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
                'jenis_ujians.nama as nama_jenis_ujian',
                'bank_soals.kkm'
            )
            ->orderBy('ujians.waktu_mulai', 'asc')
            ->get();

        $nilaiRecords = DB::table('nilais')
            ->whereIn('ujian_id', $ujians->pluck('id'))
            ->where('siswa_id', $siswaId)
            ->where('status', 'selesai')
            ->select('ujian_id', 'nilai_akhir')
            ->get()
            ->keyBy('ujian_id');

        $groupedUjiansByMapel = $ujians->groupBy('nama_mapel');

        $mapelDetails = collect();
        $totalMapelAvg = 0;
        $totalMapelCount = 0;
        $mapelTuntas = 0;

        foreach ($groupedUjiansByMapel as $mapelNama => $mapelUjians) {
            $kkm = $mapelUjians->first()->kkm ?? 75;
            $details = [];
            $sum = 0;
            $cnt = 0;

            foreach ($mapelUjians as $ujian) {
                $rec = $nilaiRecords->get($ujian->id);
                $nilai = $rec ? (float)$rec->nilai_akhir : null;
                if ($nilai !== null) {
                    $sum += $nilai;
                    $cnt++;
                }
                $details[] = [
                    'nama_ujian'  => $ujian->nama_ujian,
                    'jenis_ujian' => $ujian->nama_jenis_ujian,
                    'kkm'         => $kkm,
                    'nilai'       => $nilai,
                ];
            }

            $avg = $cnt > 0 ? round($sum / $cnt, 1) : null;
            $status = $avg === null ? 'belum' : ($avg >= $kkm ? 'tuntas' : 'kurang');

            if ($avg !== null) {
                $totalMapelAvg += $avg;
                $totalMapelCount++;
                if ($avg >= $kkm) {
                    $mapelTuntas++;
                }
            }

            $mapelDetails->put($mapelNama, [
                'avg'     => $avg,
                'kkm'     => $kkm,
                'status'  => $status,
                'details' => $details,
            ]);
        }

        $rataRataKeseluruhan = $totalMapelCount > 0 ? round($totalMapelAvg / $totalMapelCount, 1) : null;
        $statusGlobal = $rataRataKeseluruhan === null ? 'belum' : ($rataRataKeseluruhan >= 75 ? 'tuntas' : 'kurang');

        return view('guru.wali-kelas.detail-nilai-siswa', compact(
            'waliKelas', 'kelas', 'siswa',
            'mapelDetails', 'rataRataKeseluruhan',
            'statusGlobal', 'mapelTuntas'
        ));
    }


    /**
     * Export PDF: Rapor Individual Siswa
     */
    public function exportPdfSiswa(Request $request, $siswaId)
    {
        $waliKelas = $this->getWaliKelasAktif();

        $kelas = DB::table('kelas')
            ->join('tingkats', 'kelas.tingkat_id', '=', 'tingkats.id')
            ->where('kelas.id', $waliKelas->kelas_id)
            ->select('kelas.*', 'tingkats.nama_tingkat')
            ->first();

        $activeTahunAjaran = $waliKelas->tahunAjaran;
        $siswa = DB::table('siswas')->where('id', $siswaId)->first();
        abort_if(!$siswa, 404, 'Siswa tidak ditemukan.');

        $ujians = DB::table('ujian_kelas')
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
                'jenis_ujians.nama as nama_jenis_ujian',
                'bank_soals.kkm'
            )
            ->orderBy('ujians.waktu_mulai', 'asc')
            ->get();

        $nilaiRecords = DB::table('nilais')
            ->whereIn('ujian_id', $ujians->pluck('id'))
            ->where('siswa_id', $siswaId)
            ->where('status', 'selesai')
            ->select('ujian_id', 'nilai_akhir')
            ->get()
            ->keyBy('ujian_id');

        $groupedUjiansByMapel = $ujians->groupBy('nama_mapel');
        $mapelDetails = collect();
        $totalMapelAvg = 0;
        $totalMapelCount = 0;

        foreach ($groupedUjiansByMapel as $mapelNama => $mapelUjians) {
            $kkm = $mapelUjians->first()->kkm ?? 75;
            $details = [];
            $sum = 0;
            $cnt = 0;

            foreach ($mapelUjians as $ujian) {
                $rec = $nilaiRecords->get($ujian->id);
                $nilai = $rec ? (float)$rec->nilai_akhir : null;
                if ($nilai !== null) {
                    $sum += $nilai;
                    $cnt++;
                }
                $details[] = [
                    'nama_ujian'  => $ujian->nama_ujian,
                    'jenis_ujian' => $ujian->nama_jenis_ujian,
                    'kkm'         => $kkm,
                    'nilai'       => $nilai,
                ];
            }

            $avg = $cnt > 0 ? round($sum / $cnt, 1) : null;
            $status = $avg === null ? 'belum' : ($avg >= $kkm ? 'tuntas' : 'kurang');

            if ($avg !== null) {
                $totalMapelAvg += $avg;
                $totalMapelCount++;
            }

            $mapelDetails->put($mapelNama, [
                'avg'     => $avg,
                'kkm'     => $kkm,
                'status'  => $status,
                'details' => $details,
            ]);
        }

        $rataRataKeseluruhan = $totalMapelCount > 0 ? round($totalMapelAvg / $totalMapelCount, 1) : null;
        $statusGlobal = $rataRataKeseluruhan === null ? 'belum' : ($rataRataKeseluruhan >= 75 ? 'tuntas' : 'kurang');

        $pdf = PDF::loadView('pdf.rekap-siswa-individual', compact(
            'kelas', 'activeTahunAjaran', 'siswa',
            'mapelDetails', 'rataRataKeseluruhan', 'statusGlobal'
        ))->setPaper('a4', 'portrait');

        $fileName = 'Rekap_Nilai_' . Str::slug($siswa->nama) . '_' . Str::slug($kelas->nama_kelas) . '.pdf';
        return $pdf->download($fileName);
    }
}
