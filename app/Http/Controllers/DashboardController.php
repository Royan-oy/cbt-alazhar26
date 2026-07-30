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
                // 1. Ambil data ujian hari ini / sedang aktif untuk kelas siswa ini
                $ujians = \App\Models\Ujian::with(['bankSoal.mataPelajaran'])
                    ->whereHas('kelas', function($query) use ($kelasAktif) {
                        $query->where('kelas.id', $kelasAktif->id);
                    })
                    ->where(function($query) use ($sekarang) {
                        $query->whereDate('waktu_mulai', $sekarang->toDateString())
                              ->orWhere(function($q) use ($sekarang) {
                                  $q->where('waktu_mulai', '<=', $sekarang)
                                    ->where('waktu_selesai', '>=', $sekarang);
                              });
                    })
                    ->orderBy('waktu_mulai', 'desc')
                    ->get();
            } else {
                $ujians = collect();
            }

            // 2. Map data ujian untuk mengecek status pengerjaan siswa secara real-time
            $ujian_with_status = $ujians->map(function ($ujian) use ($user, $sekarang) {
                $riwayat = DB::table('nilais')
                    ->where('ujian_id', $ujian->id)
                    ->where('siswa_id', $user->siswa->id)
                    ->first();

                $mulai = \Carbon\Carbon::parse($ujian->waktu_mulai);
                $selesai = \Carbon\Carbon::parse($ujian->waktu_selesai);

                // ==========================
                // STATUS WAKTU
                // ==========================
                if ($sekarang->lt($mulai)) {
                    $ujian->status_waktu = 'belum_mulai';
                } elseif ($sekarang->gt($selesai)) {
                    $ujian->status_waktu = 'berakhir';
                } else {
                    $ujian->status_waktu = 'aktif';
                }

                // ==========================
                // STATUS SISWA (database nilais)
                // ==========================
                if (!$riwayat) {
                    $ujian->status_siswa = 'belum';
                } else {
                    $ujian->status_siswa = $riwayat->status;
                }

                $ujian->is_aktif = $sekarang->between($mulai, $selesai);
                $ujian->durasi_menit = $mulai->diffInMinutes($selesai);

                $startStr = $mulai->isToday() ? 'Hari ini, ' . $mulai->format('H:i') : $mulai->format('d M Y, H:i');
                $endStr = $selesai->isToday() ? 'Hari ini, ' . $selesai->format('H:i') : $selesai->format('d M Y, H:i');
                $ujian->display_tanggal = $startStr . ' - ' . $endStr;

                return $ujian;
            });

            // Sort agar ujian yang sedang aktif berada di paling atas, lalu diikuti yang belum lewat
            $ujian_with_status = $ujian_with_status->sortByDesc(function ($ujian) {
                return $ujian->is_aktif ? 1 : 0;
            })->values();

            // 3. Masukkan ke dalam array data untuk dikirim ke view
            $data['ujian_hari_ini'] = $ujian_with_status;
            
            // Riwayat ujian total milik siswa
            $data['riwayat_ujian']  = DB::table('nilais')
                ->where('siswa_id', $user->siswa->id)
                ->count();
        }

        return view('dashboard', $data);
        
    }
}