@extends('layouts.app')

@section('title', 'Dashboard Utama CBT')

@section('content')
<style>
    /* --- CUSTOM PREMIUM DASHBOARD CSS --- */
    .welcome-banner {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        border: 1px solid rgba(255, 255, 255, 0.05);
        position: relative;
        overflow: hidden;
    }

    .welcome-banner::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.08) 0%, transparent 70%);
        top: -100px;
        right: -50px;
        border-radius: 50%;
    }

    .stat-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.05);
        border-color: #cbd5e1;
    }

    .icon-shape {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .shortcut-btn {
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
    }

    .shortcut-btn:hover {
        background-color: #f8fafc !important;
        border-color: #3b82f6;
        color: #3b82f6 !important;
    }

    /* ========================================== */
    /* --- MOBILE RESPONSIVE MEDIA QUERIES --- */
    /* ========================================== */
    @media (max-width: 767.98px) {
        .welcome-banner {
            padding: 24px !important;
            text-align: center;
        }
        .welcome-banner h1 {
            font-size: 22px !important;
        }
        .welcome-banner p {
            font-size: 12px !important;
        }
        .stat-card {
            padding: 16px;
        }
        .icon-shape {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        .stat-card h3 {
            font-size: 20px !important;
        }
    }
</style>

<div class="container-fluid px-0">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-banner p-4 p-md-5 rounded-4 shadow-sm">

                <span class="badge text-uppercase bg-info bg-opacity-10 text-info border border-info border-opacity-25 align-self-start mb-3 px-3 py-2 rounded-pill"
                    style="font-size:10px;font-weight:700;letter-spacing:.7px;">
                    <i class="fa-solid fa-circle-check me-1"></i>
                    Portal Akademik Aktif
                </span>

                <h1 class="fw-bold text-white mb-2">
                    Selamat Datang,
                    {{ Auth::user()->nama }} 👋
                </h1>

                <p class="mb-0" style="font-size:14px;color:#94a3b8;line-height:1.8;">

                    Anda login sebagai

                    <strong class="text-info">
                        @switch(Auth::user()->role)

                            @case('super_admin')
                                Super Administrator
                            @break

                            @case('admin_jenjang')
                                Administrator Jenjang
                            @break

                            @case('guru')
                                Guru
                            @break

                            @case('siswa')
                                Siswa
                            @break

                            @default
                                Pengguna
                        @endswitch
                    </strong>

                    di <strong class="text-white">CBT Smart Online</strong>
                    Sekolah Islam Al Azhar Pekalongan.

                </p>

            </div>
        </div>
    </div>

    @if(Auth::user()->role == 'super_admin')
    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Overview Sistem Master</h5>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="icon-shape bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-server"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Total Jenjang</span>
                    <h3 class="mb-0 fw-bold text-dark mt-0.5">{{ $total_jenjang ?? 0 }} <span class="fs-6 text-muted fw-normal">Unit</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="icon-shape bg-success bg-opacity-10 text-success"><i class="fa-solid fa-users"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Total Pengguna</span>
                    <h3 class="mb-0 fw-bold text-dark mt-0.5">{{ number_format($total_users ?? 0) }} <span class="fs-6 text-muted fw-normal">Akun</span></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(Auth::user()->role == 'admin_jenjang')
    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Statistik Data Pendidikan</h5>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="icon-shape bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-landmark"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Total Kelas</span>
                    <h3 class="mb-0 fw-bold text-dark mt-0.5">{{ $total_kelas ?? 0 }} <span class="fs-6 text-muted fw-normal">Ruang</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="icon-shape bg-info bg-opacity-10 text-info"><i class="fa-solid fa-graduation-cap"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Siswa Terdaftar</span>
                    <h3 class="mb-0 fw-bold text-dark mt-0.5">{{ $total_siswa ?? 0 }} <span class="fs-6 text-muted fw-normal">Anak</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="icon-shape bg-success bg-opacity-10 text-success"><i class="fa-solid fa-book"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Mata Pelajaran</span>
                    <h3 class="mb-0 fw-bold text-dark mt-0.5">{{ $total_mapel ?? 0 }} <span class="fs-6 text-muted fw-normal">Mapel</span></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(Auth::user()->role == 'guru')
    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Ringkasan Tugas Mengajar</h5>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-6">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="icon-shape bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-folder-open"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Bank Soal Anda</span>
                    <h3 class="mb-0 fw-bold text-dark mt-0.5">{{ $total_bank_soal ?? 0 }} <span class="fs-6 text-muted fw-normal">Paket</span></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isWaliKelas)
    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Pemantauan Ruang Kelas Anda</h5>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-6">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="icon-shape bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-satellite-dish"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Siswa Sedang Ujian</span>
                    <h3 class="mb-0 fw-bold text-warning mt-0.5">{{ $siswa_ujian ?? 0 }} <span class="fs-6 text-muted fw-normal">Siswa</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-6">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="icon-shape bg-success bg-opacity-10 text-success"><i class="fa-solid fa-clipboard-user"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Siswa Sudah Selesai</span>
                    <h3 class="mb-0 fw-bold text-success mt-0.5">{{ $siswa_selesai ?? 0 }} <span class="fs-6 text-muted fw-normal">Siswa</span></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(Auth::user()->role == 'siswa')
    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Lembar Informasi Evaluasi</h5>
    
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="stat-card d-flex align-items-center gap-3 h-100">
                <div class="icon-shape bg-secondary bg-opacity-10 text-secondary"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Ujian Diselesaikan</span>
                    <h3 class="mb-0 fw-bold text-dark mt-0.5">{{ $riwayat_ujian ?? 0 }} <span class="fs-6 text-muted fw-normal">Riwayat</span></h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="card bg-white p-4 rounded-4 border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 14px;"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Jadwal Ujian Hari Ini</h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill small px-2.5 py-1" style="font-size: 11px;">
                        {{ count($ujian_hari_ini ?? []) }} Tersedia
                    </span>
                </div>

                @if(isset($ujian_hari_ini) && count($ujian_hari_ini) > 0)
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0" style="font-size: 13px;">
                            <thead>
                                <tr class="text-muted border-bottom" style="font-size: 11px; text-uppercase: true; letter-spacing: 0.5px;">
                                    <th class="pb-2 ps-0">Nama Ujian</th>
                                    <th class="pb-2">Batas Waktu</th>
                                    <th class="pb-2 text-center">Status Anda</th>
                                    <th class="pb-2 text-end pe-0">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ujian_hari_ini as $ujian)
                                    <tr class="border-bottom-dashed">
                                        <td class="py-3 ps-0 fw-bold text-dark">
                                            {{ $ujian->nama_ujian }}
                                            <span class="d-block text-muted fw-normal mt-0.5" style="font-size: 11px;">Durasi: {{ $ujian->durasi }} Menit</span>
                                        </td>
                                        <td class="py-3 text-muted">
                                            {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }} WIB
                                        </td>
                                        <td class="py-3 text-center">
                                            <span class="badge {{ $ujian->badge_color }} rounded-pill px-3 py-1.5" style="font-size: 11px; font-weight: 600;">
                                                {{ $ujian->status_siswa }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-end pe-0">
                                            @if($ujian->status_siswa == 'Belum Dikerjakan')
                                                <a href="#" class="btn btn-sm btn-primary rounded-3 px-3 fw-bold" style="font-size: 12px;">Mulai</a>
                                            @elseif($ujian->status_siswa == 'Sedang Mengerjakan')
                                                <a href="#" class="btn btn-sm btn-warning rounded-3 px-3 fw-bold text-dark" style="font-size: 12px;">Lanjutkan</a>
                                            @else
                                                <button class="btn btn-sm btn-light rounded-3 px-3 text-muted fw-semibold" style="font-size: 12px;" disabled><i class="fa-solid fa-circle-check text-success me-1"></i> Selesai</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="text-muted mb-2 fs-3"><i class="fa-regular fa-calendar-xmark"></i></div>
                        <p class="text-secondary small mb-0">Alhamdulillah, tidak ada jadwal ujian aktif untuk Anda saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card bg-white p-4 rounded-4 border-0 shadow-sm">
                <h6 class="fw-bold text-dark mb-3" style="font-size: 14px;">Aksi Pintasan Aplikasi</h6>
                <div class="d-flex flex-wrap gap-2">
                    
                    @if(Auth::user()->role == 'super_admin')
                        <a href="#" class="btn shortcut-btn bg-white text-secondary font-medium px-3 py-2 rounded-3 small text-decoration-none"><i class="fa-solid fa-gear me-2"></i> Pengaturan Sistem</a>
                    @endif

                    @if(Auth::user()->role == 'admin_jenjang')
                        <a href="#" class="btn shortcut-btn bg-white text-secondary font-medium px-3 py-2 rounded-3 small text-decoration-none"><i class="fa-solid fa-user-plus me-2"></i> Tambah Siswa Baru</a>
                    @endif

                    @if(Auth::user()->role == 'guru')

                    <a href="#" class="btn shortcut-btn">
                        <i class="fa-solid fa-circle-plus me-2"></i>
                        Buat Bank Soal
                    </a>

                    <a href="#" class="btn shortcut-btn">
                        <i class="fa-solid fa-calendar-days me-2"></i>
                        Jadwalkan Ujian
                    </a>

                    @endif

                    @if($isWaliKelas)

                    <a href="#" class="btn shortcut-btn">
                        <i class="fa-solid fa-file-export me-2"></i>
                        Rekap Kelas
                    </a>

                    @endif

                    @if(Auth::user()->role == 'siswa')
                        <a href="#" class="btn btn-primary font-bold px-4 py-2 rounded-3 small shadow-sm text-decoration-none text-white"><i class="fa-solid fa-play me-2"></i> Masuk Ruang Ujian</a>
                    @endif

                    <div class="ms-md-auto d-flex align-items-center gap-2 mt-2 mt-md-0 text-muted small" style="font-size: 12px;">
                        <i class="fa-solid fa-shield-halved text-success"></i> Sesi Enkripsi Terlindungi
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection