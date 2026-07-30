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
            $data['total_jenjang']      = DB::table('jenjangs')->count();
            $data['total_users']        = DB::table('users')->count();
            $data['total_admin_jenjang']= DB::table('users')->where('role', 'admin_jenjang')->count();
            $data['total_guru']         = DB::table('users')->where('role', 'guru')->count();
            $data['total_siswa']        = DB::table('users')->where('role', 'siswa')->count();
            $data['total_bank_soal']    = DB::table('bank_soals')->count();
            $data['active_tahun_ajaran']= $activeTahunAjaran;

            // 1. Monitoring Live Ujian Hari Ini
            $sekarang = now();
            $ujiansHariIni = \App\Models\Ujian::with([
                    'bankSoal.mataPelajaran', 
                    'bankSoal.jenjang', 
                    'kelas', 
                    'jenisUjian'
                ])
                ->where(function($query) use ($sekarang) {
                    $query->whereDate('waktu_mulai', $sekarang->toDateString())
                          ->orWhere(function($q) use ($sekarang) {
                              $q->where('waktu_mulai', '<=', $sekarang)
                                ->where('waktu_selesai', '>=', $sekarang);
                          });
                })
                ->orderBy('waktu_mulai', 'desc')
                ->get();

            $ujiansHariIni->transform(function ($ujian) use ($sekarang) {
                $mulai = \Carbon\Carbon::parse($ujian->waktu_mulai);
                $selesai = \Carbon\Carbon::parse($ujian->waktu_selesai);

                if ($sekarang->lt($mulai)) {
                    $ujian->status_waktu = 'belum_mulai';
                } elseif ($sekarang->gt($selesai)) {
                    $ujian->status_waktu = 'berakhir';
                } else {
                    $ujian->status_waktu = 'aktif';
                }

                $siswaMengerjakan = DB::table('nilais')
                    ->where('ujian_id', $ujian->id)
                    ->where('status', 'mengerjakan')
                    ->count();

                $siswaSelesai = DB::table('nilais')
                    ->where('ujian_id', $ujian->id)
                    ->where('status', 'selesai')
                    ->count();

                $ujian->siswa_mengerjakan = $siswaMengerjakan;
                $ujian->siswa_selesai     = $siswaSelesai;
                $ujian->total_peserta     = $siswaMengerjakan + $siswaSelesai;

                return $ujian;
            });

            $data['ujians_hari_ini']   = $ujiansHariIni;
            $data['total_ujian_aktif'] = $ujiansHariIni->where('status_waktu', 'aktif')->count();

            // 2. Ringkasan Aktivitas Per Jenjang
            $jenjangs = \App\Models\Jenjang::with(['tingkats.kelas'])->get();
            $jenjangList = $jenjangs->map(function ($jenjang) use ($activeTahunAjaran) {
                // Admin jenjang penanggung jawab
                $admin = \App\Models\Admin::where('jenjang_id', $jenjang->id)
                    ->whereHas('user', function($q) {
                        $q->where('role', 'admin_jenjang');
                    })->first();

                // Total kelas under this jenjang
                $tingkatIds = $jenjang->tingkats->pluck('id');
                $totalKelas = \App\Models\Kelas::whereIn('tingkat_id', $tingkatIds)->count();

                // Total siswa in classes under this jenjang
                $kelasIds = \App\Models\Kelas::whereIn('tingkat_id', $tingkatIds)->pluck('id');
                
                $siswaQuery = DB::table('siswa_kelas')
                    ->whereIn('kelas_id', $kelasIds);
                
                if ($activeTahunAjaran) {
                    $siswaQuery->where('tahun_ajaran_id', $activeTahunAjaran->id);
                }
                
                $totalSiswa = $siswaQuery->distinct('siswa_id')->count('siswa_id');

                // Total bank soal for this jenjang
                $totalBankSoal = DB::table('bank_soals')
                    ->where('jenjang_id', $jenjang->id)
                    ->count();

                return (object) [
                    'id'              => $jenjang->id,
                    'nama_jenjang'    => $jenjang->nama_jenjang,
                    'slug'            => $jenjang->slug,
                    'admin_nama'      => $admin ? $admin->nama : 'Belum Ditentukan',
                    'total_kelas'     => $totalKelas,
                    'total_siswa'     => $totalSiswa,
                    'total_bank_soal' => $totalBankSoal,
                ];
            });

            $data['jenjang_summary'] = $jenjangList;
        } 
        
        elseif ($user->role == 'admin_jenjang') {
            $admin   = \App\Models\Admin::with('jenjang')->where('user_id', $user->id)->first();
            $jenjang = $admin ? $admin->jenjang : null;

            $data['admin_info']          = $admin;
            $data['jenjang']             = $jenjang;
            $data['active_tahun_ajaran'] = $activeTahunAjaran;

            if ($jenjang) {
                $tingkatIds = \App\Models\Tingkat::where('jenjang_id', $jenjang->id)->pluck('id');
                $kelases    = \App\Models\Kelas::with('tingkat')->whereIn('tingkat_id', $tingkatIds)->get();
                $kelasIds   = $kelases->pluck('id');

                $data['total_kelas'] = $kelases->count();

                // Total Siswa di jenjang ini (pada tahun ajaran aktif)
                $siswaQuery = DB::table('siswa_kelas')
                    ->whereIn('kelas_id', $kelasIds);

                if ($activeTahunAjaran) {
                    $siswaQuery->where('tahun_ajaran_id', $activeTahunAjaran->id);
                }

                $data['total_siswa'] = $siswaQuery->distinct('siswa_id')->count('siswa_id');

                // Total Guru Mapel di jenjang ini
                $countGuruMapel = DB::table('guru_mapels')
                    ->whereIn('id', function($q) use ($kelasIds) {
                        $q->select('guru_mapel_id')
                          ->from('guru_mapel_kelas')
                          ->whereIn('kelas_id', $kelasIds);
                    })
                    ->distinct('guru_id')
                    ->count('guru_id');

                if ($countGuruMapel == 0) {
                    $countGuruMapel = DB::table('guru_mapels')->distinct('guru_id')->count('guru_id');
                }

                $data['total_guru_mapel'] = $countGuruMapel;

                // Total Bank Soal di jenjang ini
                $data['total_bank_soal'] = DB::table('bank_soals')
                    ->where('jenjang_id', $jenjang->id)
                    ->count();

                // Ringkasan Kelas & Wali Kelas Unit
                $data['ringkasan_kelas'] = $kelases->map(function ($kelas) use ($activeTahunAjaran) {
                    $wali = null;
                    if ($activeTahunAjaran) {
                        $waliRecord = \App\Models\WaliKelas::with('guru')
                            ->where('kelas_id', $kelas->id)
                            ->where('tahun_ajaran_id', $activeTahunAjaran->id)
                            ->first();

                        if ($waliRecord && $waliRecord->guru) {
                            $wali = $waliRecord->guru->nama;
                        }
                    }

                    $jmlSiswaQuery = DB::table('siswa_kelas')
                        ->where('kelas_id', $kelas->id);
                    if ($activeTahunAjaran) {
                        $jmlSiswaQuery->where('tahun_ajaran_id', $activeTahunAjaran->id);
                    }

                    return (object) [
                        'id'           => $kelas->id,
                        'nama_kelas'   => $kelas->nama_kelas,
                        'nama_tingkat' => $kelas->tingkat->nama_tingkat ?? '-',
                        'wali_kelas'   => $wali ?? 'Belum Diatur',
                        'total_siswa'  => $jmlSiswaQuery->distinct('siswa_id')->count('siswa_id'),
                    ];
                });
            } else {
                $data['total_kelas']      = 0;
                $data['total_siswa']      = 0;
                $data['total_guru_mapel'] = 0;
                $data['total_bank_soal']  = 0;
                $data['ringkasan_kelas']  = collect();
            }
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