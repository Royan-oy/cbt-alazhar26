<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Ujian;
use App\Models\JenisUjian;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasilNilaiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $siswa = Siswa::with(['kelasAktif.kelas.tingkat.jenjang'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $kelasAktif = optional($siswa->kelasAktif)->kelas;

        if (!$kelasAktif) {
            return view('dashboard-siswa.hasil-nilai.index', [
                'siswa'        => $siswa,
                'kelasAktif'   => null,
                'ujians'       => collect(),
                'stats'        => (object) ['total' => 0, 'rata_rata' => 0, 'tuntas' => 0, 'belum_tuntas' => 0],
                'jenisUjians'  => collect(),
                'tahunAjarans' => collect(),
            ]);
        }

        $query = Ujian::with([
                'bankSoal.mataPelajaran',
                'jenisUjian',
                'tahunAjaran',
                'nilais' => function ($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->id);
                }
            ])
            ->where('publish_nilai', true)
            ->whereHas('kelas', function ($q) use ($kelasAktif) {
                $q->where('kelas.id', $kelasAktif->id);
            });

        if ($request->filled('jenis_ujian')) {
            $query->where('jenis_ujian_id', $request->jenis_ujian);
        }

        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_ujian', 'like', "%{$search}%")
                  ->orWhereHas('bankSoal.mataPelajaran', function ($m) use ($search) {
                      $m->where('nama_mapel', 'like', "%{$search}%");
                  });
            });
        }

        $ujians = $query->orderBy('waktu_mulai', 'desc')->get();

        $totalNilaiSum = 0;
        $totalNilaiCount = 0;
        $tuntasCount = 0;
        $belumTuntasCount = 0;

        $ujians->transform(function ($ujian) use ($siswa, &$totalNilaiSum, &$totalNilaiCount, &$tuntasCount, &$belumTuntasCount) {
            $nilaiRecord = $ujian->nilais->first();

            $kkm = optional($ujian->bankSoal)->kkm ?? 75;
            $ujian->kkm = $kkm;

            if ($nilaiRecord && $nilaiRecord->status === 'selesai') {
                $ujian->has_submitted = true;
                $ujian->status_penilaian = $nilaiRecord->status_penilaian;

                if ($nilaiRecord->status_penilaian === 'selesai') {
                    $nilaiAkhir = (float) $nilaiRecord->nilai_akhir;
                    $ujian->nilai_akhir = $nilaiAkhir;
                    $ujian->is_tuntas = ($nilaiAkhir >= $kkm);

                    $totalNilaiSum += $nilaiAkhir;
                    $totalNilaiCount++;

                    if ($ujian->is_tuntas) {
                        $tuntasCount++;
                    } else {
                        $belumTuntasCount++;
                    }
                } else {
                    $ujian->nilai_akhir = null;
                    $ujian->is_tuntas = false;
                }
            } else {
                $ujian->has_submitted = false;
                $ujian->status_penilaian = 'belum';
                $ujian->nilai_akhir = null;
                $ujian->is_tuntas = false;
            }

            return $ujian;
        });

        $stats = (object) [
            'total'        => $ujians->count(),
            'rata_rata'    => $totalNilaiCount > 0 ? round($totalNilaiSum / $totalNilaiCount, 1) : 0,
            'tuntas'       => $tuntasCount,
            'belum_tuntas' => $belumTuntasCount,
        ];

        $jenisUjians = JenisUjian::where('aktif', true)->orderBy('nama')->get();
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('nama_tahun')->get();

        return view('dashboard-siswa.hasil-nilai.index', compact(
            'siswa',
            'kelasAktif',
            'ujians',
            'stats',
            'jenisUjians',
            'tahunAjarans'
        ));
    }
}
