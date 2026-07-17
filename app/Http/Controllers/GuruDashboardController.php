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
        
        // Ambil tahun ajaran yang sedang aktif
        $activeTahunAjaran = DB::table('tahun_ajarans')->where('is_aktif', true)->first();

        // Tentukan status Wali Kelas berdasarkan Tahun Ajaran Aktif
        if ($user->guru && $activeTahunAjaran) {
            $isWaliKelas = DB::table('wali_kelas')
                ->where('guru_id', $user->guru->id)
                ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                ->exists();
        }

        $data['isWaliKelas'] = $isWaliKelas;
        $guru = $user->guru;

        if ($guru) {
            // Dapatkan seluruh ID pemetaan mata pelajaran yang diajar oleh guru pada tahun ajaran aktif
            $guruMapelQuery = DB::table('guru_mapels')
                ->where('guru_id', $guru->id);
            
            if ($activeTahunAjaran) {
                $guruMapelQuery->where('tahun_ajaran_id', $activeTahunAjaran->id);
            }
            
            $guruMapelIds = $guruMapelQuery->pluck('id');

            // Hitung akumulasi bank soal dari semua mapel yang diajar
            $data['total_bank_soal'] = DB::table('bank_soals')
                ->whereIn('guru_mapel_id', $guruMapelIds)
                ->count();
        } else {
            $data['total_bank_soal'] = 0;
        }

        // Jika Guru juga merupakan Wali Kelas, hitung data kelasnya
        if ($isWaliKelas && $activeTahunAjaran) {
            $wali = DB::table('wali_kelas')
                ->where('guru_id', $user->guru->id)
                ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                ->first();

            if ($wali) {
                // Ambil daftar siswa_id yang terdaftar di kelas yang diwalikan pada tahun ajaran aktif
                $siswaIds = DB::table('siswa_kelas')
                    ->where('kelas_id', $wali->kelas_id)
                    ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                    ->pluck('siswa_id');

                $data['siswa_ujian'] = DB::table('nilais')
                    ->whereIn('siswa_id', $siswaIds)
                    ->where('status', 'mengerjakan')
                    ->count();

                $data['siswa_selesai'] = DB::table('nilais')
                    ->whereIn('siswa_id', $siswaIds)
                    ->where('status', 'selesai')
                    ->count();
            }
        }

        return view('guru.dashboard', $data);
    }
}