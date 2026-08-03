<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Proteksi ganda: Pastikan hanya guru yang bisa mengakses halaman ini
        if ($user->role !== 'guru') {
            return redirect()->route('dashboard');
        }

        $data = [];
        $isWaliKelas = false;
        $activeTahunAjaran = DB::table('tahun_ajarans')->where('is_aktif', true)->first();

        $data['active_tahun_ajaran'] = $activeTahunAjaran;
        $guru = $user->guru;

        if ($guru) {
            $guruMapelQuery = \App\Models\GuruMapel::with(['mataPelajaran', 'kelas', 'bankSoals'])
                ->where('guru_id', $guru->id);
            
            if ($activeTahunAjaran) {
                $guruMapelQuery->where('tahun_ajaran_id', $activeTahunAjaran->id);
            }
            
            $guruMapels   = $guruMapelQuery->get();
            $guruMapelIds = $guruMapels->pluck('id');

            // 1. Total Mapel Diampu
            $data['total_mapel'] = $guruMapels->pluck('mata_pelajaran_id')->unique()->count();

            // 2. Total Kelas Diampu
            $kelasIdsInMapel = DB::table('guru_mapel_kelas')
                ->whereIn('guru_mapel_id', $guruMapelIds)
                ->pluck('kelas_id')
                ->unique();
            $data['total_kelas'] = $kelasIdsInMapel->count();

            // 3. Total Bank Soal Anda
            $data['total_bank_soal'] = DB::table('bank_soals')
                ->whereIn('guru_mapel_id', $guruMapelIds)
                ->count();

            // 4. Total Ujian Mapel
            $bankSoalIds = DB::table('bank_soals')
                ->whereIn('guru_mapel_id', $guruMapelIds)
                ->pluck('id');

            $data['total_ujian_mapel'] = DB::table('ujians')
                ->whereIn('bank_soal_id', $bankSoalIds)
                ->count();

            // 5. Mapel Diampu List
            $data['mapel_diampu'] = $guruMapels->map(function ($gm) {
                return (object) [
                    'id'               => $gm->id,
                    'nama_mapel'       => $gm->mataPelajaran->nama_mapel ?? '-',
                    'kode_mapel'       => $gm->mataPelajaran->kode_mapel ?? '-',
                    'kelas_list'       => $gm->kelas->pluck('nama_kelas')->join(', '),
                    'total_bank_soal'  => $gm->bankSoals->count(),
                ];
            });
        } else {
            $data['total_mapel']       = 0;
            $data['total_kelas']       = 0;
            $data['total_bank_soal']   = 0;
            $data['total_ujian_mapel'] = 0;
            $data['mapel_diampu']      = collect();
        }

        // Tentukan status & data Wali Kelas
        if ($guru && $activeTahunAjaran) {
            $waliRecord = \App\Models\WaliKelas::with('kelas')
                ->where('guru_id', $guru->id)
                ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                ->first();

            if ($waliRecord && $waliRecord->kelas) {
                $isWaliKelas = true;
                $kelasWali   = $waliRecord->kelas;

                $siswaKelasQuery = DB::table('siswa_kelas')
                    ->where('kelas_id', $kelasWali->id)
                    ->where('tahun_ajaran_id', $activeTahunAjaran->id);

                $siswaIds = $siswaKelasQuery->pluck('siswa_id');

                // Ambil ujian yang dijadwalkan hari ini untuk kelas binaan
                $ujianHariIniIds = DB::table('ujians')
                    ->join('ujian_kelas', 'ujians.id', '=', 'ujian_kelas.ujian_id')
                    ->where('ujian_kelas.kelas_id', $kelasWali->id)
                    ->whereDate('ujians.waktu_mulai', now()->toDateString())
                    ->pluck('ujians.id');

                $totalUjianHariIni = $ujianHariIniIds->count();
                $totalTargetNilai = $siswaIds->count() * $totalUjianHariIni;

                $siswaUjian = DB::table('nilais')
                    ->whereIn('siswa_id', $siswaIds)
                    ->whereIn('ujian_id', $ujianHariIniIds)
                    ->where('status', 'mengerjakan')
                    ->count();

                $siswaSelesai = DB::table('nilais')
                    ->whereIn('siswa_id', $siswaIds)
                    ->whereIn('ujian_id', $ujianHariIniIds)
                    ->where('status', 'selesai')
                    ->count();

                $data['wali_kelas_info'] = (object) [
                    'nama_kelas'  => $kelasWali->nama_kelas,
                    'total_siswa' => $siswaIds->count(),
                ];
                $data['siswa_ujian']   = $siswaUjian;
                $data['siswa_selesai'] = $siswaSelesai;
                // Hitung dari total target nilai (jumlah siswa x jumlah ujian hari ini)
                $data['siswa_belum']   = max(0, $totalTargetNilai - ($siswaUjian + $siswaSelesai));
            }
        }

        $data['isWaliKelas'] = $isWaliKelas;

        return view('guru.dashboard', $data);
    }
}