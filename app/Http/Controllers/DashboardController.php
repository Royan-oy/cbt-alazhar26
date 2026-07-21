<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            return redirect()->route('dashboard-guru.index');
        }

        $data = [];

        $isGuru = $user->role == 'guru';
        $isWaliKelas = false;
        
        // Ambil tahun ajaran yang sedang aktif
        $activeTahunAjaran = DB::table('tahun_ajarans')->where('is_aktif', true)->first();

        // 1. Tentukan status Wali Kelas berdasarkan Tahun Ajaran Aktif
        if ($isGuru && $user->guru && $activeTahunAjaran) {
            $isWaliKelas = DB::table('wali_kelas')
                ->where('guru_id', $user->guru->id)
                ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                ->exists();
        }

        $data['isWaliKelas'] = $isWaliKelas;

        // Ambil data dinamis berdasarkan role masing-masing
        if ($user->role == 'super_admin') {
            $data['total_jenjang'] = DB::table('jenjangs')->count(); // Sesuaikan nama tabel Anda
            $data['total_users'] = DB::table('users')->count();
        } 
        
        elseif ($user->role == 'admin_jenjang') {
            // Admin jenjang menghitung data yang sesuai dengan kelas/jenjangnya
            $data['total_kelas'] = DB::table('kelas')->count(); 
            $data['total_siswa'] = DB::table('users')->where('role', 'siswa')->count();
            $data['total_mapel'] = DB::table('mata_pelajarans')->count();
        } 
        
        elseif ($user->role == 'guru') {
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

            // 3. LOGIKA TAMBAHAN: Jika Guru juga merupakan Wali Kelas, hitung data kelasnya
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
        }
        
        elseif ($user->role == 'siswa') {
            $sekarang = now();

            $siswa = \App\Models\Siswa::with(['kelasAktif.kelas'])->where('user_id', $user->id)->first();
            $kelasAktif = optional($siswa->kelasAktif)->kelas;
            
            if ($kelasAktif) {
                // 1. Ambil data semua ujian untuk kelas siswa ini
                $ujians = \App\Models\Ujian::with(['bankSoal.mataPelajaran'])
                    ->whereHas('kelas', function($query) use ($kelasAktif) {
                        $query->where('kelas.id', $kelasAktif->id);
                    })
                    ->orderBy('waktu_mulai', 'desc')
                    ->get();
            } else {
                $ujians = collect();
            }

            // 2. Map data ujian untuk mengecek status pengerjaan siswa secara real-time
            $ujian_with_status = $ujians->map(function ($ujian) use ($user, $sekarang) {
                // Cek riwayat di tabel nilais berdasarkan ujian_id dan user_id siswa
                $riwayat = DB::table('nilais')
                    ->where('ujian_id', $ujian->id)
                    ->where('siswa_id', $user->siswa->id)
                    ->first();      

                $waktuMulai = \Carbon\Carbon::parse($ujian->waktu_mulai);
                $waktuSelesai = \Carbon\Carbon::parse($ujian->waktu_selesai);
                
                $isWaktuAktif = $sekarang->between($waktuMulai, $waktuSelesai);

                // Tentukan status berdasarkan data di tabel nilais
                if (!$riwayat) {
                    $ujian->status_siswa = 'Belum Dikerjakan';
                    $ujian->badge_color  = 'bg-danger';
                } elseif ($riwayat->status == 'mengerjakan') { // Sesuaikan string 'mengerjakan' dengan DB Anda
                    $ujian->status_siswa = 'Sedang Mengerjakan';
                    $ujian->badge_color  = 'bg-warning text-dark';
                } else {
                    $ujian->status_siswa = 'Sudah Selesai';
                    $ujian->badge_color  = 'bg-success';
                }

                $ujian->is_aktif = $isWaktuAktif;

                if ($sekarang->lt($waktuMulai)) {
                    $ujian->status_waktu = 'belum_mulai';
                } elseif ($sekarang->gt($waktuSelesai)) {
                    $ujian->status_waktu = 'berakhir';
                } else {
                    $ujian->status_waktu = 'aktif';
                }

                // Kalkulasi durasi aktual
                $ujian->durasi_menit = $waktuMulai->diffInMinutes($waktuSelesai);

                // Format tampilan tanggal
                $startStr = $waktuMulai->isToday() ? 'Hari ini, ' . $waktuMulai->format('H:i') : $waktuMulai->format('d M Y, H:i');
                $endStr = $waktuSelesai->isToday() ? 'Hari ini, ' . $waktuSelesai->format('H:i') : $waktuSelesai->format('d M Y, H:i');
                $ujian->display_tanggal = $startStr . ' - ' . $endStr;

                // dd($ujian);
                return $ujian;
            });


            // Filter: Hilangkan jadwal ujian yang sudah selesai, waktunya sudah berakhir, atau jadwalnya bukan hari ini
            $ujian_with_status = $ujian_with_status->reject(function ($ujian) {
                $isHariIni = \Carbon\Carbon::parse($ujian->waktu_mulai)->isToday();
                return $ujian->status_siswa == 'Sudah Selesai' || $ujian->status_waktu == 'berakhir' || !$isHariIni;
            });

            // Sort agar ujian yang sedang aktif berada di paling atas, lalu diikuti yang belum lewat
            $ujian_with_status = $ujian_with_status->sortByDesc(function ($ujian) {
                return $ujian->is_aktif ? 1 : 0;
            })->values();

            // 3. Masukkan ke dalam array data untuk dikirim ke view
            // Mengambil top 5 atau semua, di sini kita ambil maksimal 5 untuk di dashboard (sebagai preview)
            // Namun karena user minta "tampilkan semua", kita akan pass semua, tapi di blade bisa kita styling jika perlu
            $data['ujian_hari_ini'] = $ujian_with_status;
            
            // Riwayat ujian total milik siswa
            $data['riwayat_ujian']  = DB::table('nilais')
                ->where('siswa_id', $user->siswa->id)
                ->count();
        }

        return view('dashboard', $data);
    }
}