@extends('layouts.app')

@section('title', 'Dashboard Utama CBT')

@section('content')

<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        border-radius: 24px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .page-header::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        right: -50px;
        top: -80px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, rgba(14, 165, 233, 0) 70%);
        pointer-events: none;
    }

    .stat-card {
        background: var(--surface-white);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 22px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.02);
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        border-color: #cbd5e1;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .content-card {
        background: var(--surface-white);
        border-radius: 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.03);
        padding: 24px;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--secondary-dark);
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-responsive { border-radius: 16px; overflow: hidden; }

    .table thead th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        background-color: #f8fafc;
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-color);
        font-weight: 700;
    }

    .table tbody td {
        padding: 16px;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 14px;
    }

    .table tbody tr:hover { background-color: #f8fafc; }

    .exam-status-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-belum { background: #eff6ff; color: #2563eb; border: 1px solid rgba(37, 99, 235, 0.15); }
    .status-berjalan { background: #fffbeb; color: #d97706; border: 1px solid rgba(217, 119, 6, 0.15); }
    .status-selesai { background: #ecfdf5; color: #059669; border: 1px solid rgba(5, 150, 105, 0.15); }

    .btn-exam-action {
        border-radius: 10px;
        padding: 8px 18px;
        font-weight: 700;
        font-size: 12px;
        border: none;
    }

    .quick-action-btn {
        border-radius: 14px;
        padding: 14px 16px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s;
        border: 1px solid var(--border-color);
        color: var(--secondary-dark);
        background: #f8fafc;
    }

    .quick-action-btn:hover {
        background: var(--secondary-dark);
        color: #fff;
        border-color: var(--secondary-dark);
        transform: translateY(-2px);
        text-decoration: none;
    }

    .quick-action-btn.primary {
        background: linear-gradient(135deg, var(--accent-blue), #0284c7);
        color: #fff;
        border: none;
        box-shadow: 0 6px 16px rgba(14, 165, 233, 0.25);
    }

    .quick-action-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(14, 165, 233, 0.35);
        color: #fff;
    }

    .quick-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255,255,255,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .quick-action-btn.primary .quick-icon-box {
        background: rgba(255,255,255,0.2);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state i {
        font-size: 38px;
        color: #cbd5e1;
        margin-bottom: 12px;
        display: block;
    }

    .secure-badge {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid rgba(5, 150, 105, 0.15);
        font-size: 12px;
        font-weight: 600;
        padding: 8px 14px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .urgent-card {
        background: linear-gradient(135deg, #d97706, #b45309);
        border-radius: 20px;
        padding: 22px 26px;
        color: #fff;
        box-shadow: 0 12px 28px rgba(217, 119, 6, 0.25);
    }

    .urgent-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: rgba(255,255,255,0.85);
        margin-bottom: 2px;
    }

    .urgent-title {
        font-size: 18px;
        font-weight: 800;
    }

    .urgent-pulse {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        position: relative;
    }

    .urgent-pulse::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.6);
        animation: pulseRing 1.6s infinite;
    }

    @keyframes pulseRing {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(1.6); opacity: 0; }
    }

    .pulse-badge {
        animation: pulseBadge 1.6s infinite;
    }

    @keyframes pulseBadge {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    @media (max-width: 767.98px) {
        .page-header { padding: 24px; border-radius: 18px; text-align: center; }
        .page-header h1 { font-size: 22px !important; }
        .page-header p { font-size: 12px !important; }
        .stat-card { padding: 16px; }
        .stat-icon { width: 42px; height: 42px; font-size: 16px; }
        .content-card { padding: 16px; border-radius: 18px; }
    }
</style>

<div class="container-fluid px-0">

    {{-- Header Sambutan --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <span class="badge text-uppercase bg-info bg-opacity-25 text-info align-self-start mb-3 px-3 py-2 rounded-pill"
                    style="font-size:10px;font-weight:700;letter-spacing:.7px;">
                    <i class="fa-solid fa-circle-check me-1"></i>
                    Portal Akademik Aktif
                </span>

                <h1 class="fw-bold text-white mb-2" style="font-size: 26px;">
                    {{ $sapaanWaktu = now()->format('H') < 11 ? 'Selamat Pagi' : (now()->format('H') < 15 ? 'Selamat Siang' : (now()->format('H') < 18 ? 'Selamat Sore' : 'Selamat Malam')) }},
                    {{ Auth::user()->nama }} 👋
                </h1>

                <p class="mb-2" style="font-size:14px;color:#cbd5e1;line-height:1.8;">
                    Anda login sebagai
                    <strong class="text-info">
                        @switch(Auth::user()->role)
                            @case('super_admin') Super Administrator @break
                            @case('admin_jenjang') Administrator Jenjang @break
                            @case('guru') Guru @break
                            @case('siswa') Siswa @break
                            @default Pengguna
                        @endswitch
                    </strong>
                    di <strong class="text-white">CBT Smart Online</strong> Sekolah Islam Al Azhar Pekalongan.
                </p>

                <div class="d-flex align-items-center gap-2" style="font-size: 12px; color: #94a3b8;">
                    <i class="fa-regular fa-calendar"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SUPER ADMIN --}}
    {{-- ============================================================ --}}
    @if(Auth::user()->role == 'super_admin')
    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Overview Sistem Master</h5>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-server"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Total Jenjang</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ $total_jenjang ?? 0 }} <span class="fs-6 text-muted fw-normal">Unit</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fa-solid fa-users"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Total Pengguna</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ number_format($total_users ?? 0) }} <span class="fs-6 text-muted fw-normal">Akun</span></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- ADMIN JENJANG --}}
    {{-- ============================================================ --}}
    @if(Auth::user()->role == 'admin_jenjang')
    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Statistik Data Pendidikan</h5>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-landmark"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Total Kelas</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ $total_kelas ?? 0 }} <span class="fs-6 text-muted fw-normal">Ruang</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fa-solid fa-graduation-cap"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Siswa Terdaftar</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ $total_siswa ?? 0 }} <span class="fs-6 text-muted fw-normal">Anak</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fa-solid fa-book"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Mata Pelajaran</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ $total_mapel ?? 0 }} <span class="fs-6 text-muted fw-normal">Mapel</span></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(Auth::user()->role == 'guru')
    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Ringkasan Tugas Mengajar</h5>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-6">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-folder-open"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Bank Soal Anda</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ $total_bank_soal ?? 0 }} <span class="fs-6 text-muted fw-normal">Paket</span></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isWaliKelas ?? false)
    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Pemantauan Ruang Kelas Anda</h5>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-6">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-satellite-dish"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Siswa Sedang Ujian</span>
                    <h3 class="mb-0 fw-bold text-warning">{{ $siswa_ujian ?? 0 }} <span class="fs-6 text-muted fw-normal">Siswa</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-6">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fa-solid fa-clipboard-user"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Siswa Sudah Selesai</span>
                    <h3 class="mb-0 fw-bold text-success">{{ $siswa_selesai ?? 0 }} <span class="fs-6 text-muted fw-normal">Siswa</span></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- SISWA -- REDESIGN --}}
    {{-- ============================================================ --}}
    @if(Auth::user()->role == 'siswa')

    @php
        $ujianBerjalan = collect($ujian_hari_ini ?? [])->firstWhere('status_siswa', 'Sedang Mengerjakan');
        $jumlahBelum = collect($ujian_hari_ini ?? [])->where('status_siswa', 'Belum Dikerjakan')->count();
        $jumlahBerjalan = collect($ujian_hari_ini ?? [])->where('status_siswa', 'Sedang Mengerjakan')->count();
        $jumlahSelesai = collect($ujian_hari_ini ?? [])->where('status_siswa', 'Selesai')->count();
    @endphp

    @if($ujianBerjalan)
    <div class="row mb-4">
        <div class="col-12">
            <div class="urgent-card">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="urgent-pulse">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <div>
                            <div class="urgent-label">Sedang Berlangsung</div>
                            <div class="urgent-title">{{ $ujianBerjalan->nama_ujian }}</div>
                            <small class="text-white-50">Batas waktu {{ \Carbon\Carbon::parse($ujianBerjalan->waktu_selesai)->format('H:i') }} WIB &middot; Durasi {{ $ujianBerjalan->durasi_minimal }} menit</small>
                        </div>
                    </div>
                    <a href="#" class="btn btn-light fw-bold px-4 py-2 rounded-3">
                        <i class="fa-solid fa-arrow-right me-2"></i>Lanjutkan Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Ringkasan Evaluasi Anda</h5>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-pen-to-square"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Ujian Tersedia</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ count($ujian_hari_ini ?? []) }} <span class="fs-6 text-muted fw-normal">Hari Ini</span></h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Ujian Diselesaikan</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ $riwayat_ujian ?? 0 }} <span class="fs-6 text-muted fw-normal">Riwayat</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fa-solid fa-chart-line"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Rata-rata Nilai</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ $rata_nilai ?? '-' }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-8">
            <div class="content-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div class="section-title">
                        <i class="fa-solid fa-calendar-day text-primary"></i>
                        Jadwal Ujian
                    </div>
                    <a href="{{ route('dashboard-siswa.ujian-hari-ini') }}" class="text-primary text-decoration-none small fw-semibold">
                        Lihat semua <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>

                @if(isset($ujian_hari_ini) && count($ujian_hari_ini) > 0)
                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Ujian</th>
                                    <th>Batas Waktu</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ujian_hari_ini as $ujian)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $ujian->nama_ujian }}</div>
                                            <small class="text-muted">Durasi: {{ $ujian->durasi_menit }} Menit</small>
                                        </td>
                                        <td class="text-muted" style="white-space: nowrap;">
                                            <small class="d-block"><i class="fa-regular fa-clock me-1"></i>{{ $ujian->display_tanggal }}</small>
                                        </td>
                                        <td class="text-center">
                                            @if($ujian->status_waktu == 'belum_mulai')
                                                <span class="exam-status-badge status-belum"><i class="fa-regular fa-calendar-days"></i> Akan Datang</span>
                                            @elseif($ujian->status_siswa == 'Belum Dikerjakan')
                                                <span class="exam-status-badge status-berjalan"><i class="fa-solid fa-hourglass-start"></i> Belum Dikerjakan</span>
                                            @elseif($ujian->status_siswa == 'Sedang Mengerjakan')
                                                <span class="exam-status-badge status-berjalan pulse-badge"><i class="fa-solid fa-spinner"></i> Sedang Mengerjakan</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($ujian->status_siswa == 'Belum Dikerjakan')
                                                @if($ujian->status_waktu == 'belum_mulai')
                                                    <button class="btn btn-secondary text-white btn-exam-action" disabled>Belum Mulai</button>
                                                @elseif($ujian->status_waktu == 'berakhir')
                                                    <button class="btn btn-danger text-white btn-exam-action" disabled>Berakhir</button>
                                                @else
                                                    <a href="#" class="btn btn-primary btn-exam-action">Mulai</a>
                                                @endif
                                            @elseif($ujian->status_siswa == 'Sedang Mengerjakan')
                                                @if($ujian->status_waktu == 'berakhir')
                                                    <button class="btn btn-danger text-white btn-exam-action" disabled>Waktu Habis</button>
                                                @else
                                                    <a href="#" class="btn btn-warning text-dark btn-exam-action">Lanjutkan</a>
                                                @endif
                                            @else
                                                <button class="btn btn-light border btn-exam-action text-muted" disabled>
                                                    <i class="fa-solid fa-circle-check text-success me-1"></i> Selesai
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fa-regular fa-calendar-check"></i>
                        <p class="text-secondary small mb-0 fw-semibold">Alhamdulillah, tidak ada jadwal ujian aktif untuk Anda saat ini.</p>
                        <p class="text-muted small mb-0">Jadwal ujian baru akan muncul otomatis di sini saat sudah waktunya.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="content-card h-100">
                <div class="section-title mb-3">
                    <i class="fa-solid fa-chart-pie text-primary"></i>
                    Status Hari Ini
                </div>

                @if(count($ujian_hari_ini ?? []) > 0)
                    <div style="position: relative; height: 180px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="d-flex flex-column gap-2 mt-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="legend-dot" style="background:#2563eb;"></span>
                            <span class="flex-grow-1 small ms-2">Belum Dikerjakan</span>
                            <strong class="small">{{ $jumlahBelum }}</strong>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="legend-dot" style="background:#d97706;"></span>
                            <span class="flex-grow-1 small ms-2">Sedang Berjalan</span>
                            <strong class="small">{{ $jumlahBerjalan }}</strong>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="legend-dot" style="background:#059669;"></span>
                            <span class="flex-grow-1 small ms-2">Selesai</span>
                            <strong class="small">{{ $jumlahSelesai }}</strong>
                        </div>
                    </div>
                @else
                    <div class="empty-state py-4">
                        <i class="fa-regular fa-face-smile"></i>
                        <p class="text-muted small mb-0">Belum ada data untuk ditampilkan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @endif

    {{-- ============================================================ --}}
    {{-- AKSI PINTASAN --}}
    {{-- ============================================================ --}}
    <div class="row">
        <div class="col-12">
            <div class="content-card">
                <h6 class="fw-bold text-dark mb-3" style="font-size: 14px;">
                    <i class="fa-solid fa-bolt text-warning me-2"></i>
                    Aksi Pintasan
                </h6>

                <div class="row g-2">

                    @if(Auth::user()->role == 'super_admin')
                    <div class="col-6 col-md-3">
                        <a href="#" class="quick-action-btn">
                            <div class="quick-icon-box"><i class="fa-solid fa-gear"></i></div>
                            Pengaturan Sistem
                        </a>
                    </div>
                    @endif

                    @if(Auth::user()->role == 'admin_jenjang')
                    <div class="col-6 col-md-3">
                        <a href="{{ route('siswa.create') }}" class="quick-action-btn">
                            <div class="quick-icon-box"><i class="fa-solid fa-user-plus"></i></div>
                            Tambah Siswa Baru
                        </a>
                    </div>
                    @endif

                    {{-- @if(Auth::user()->role == 'guru')

                    <a href="#" class="btn shortcut-btn">
                        <i class="fa-solid fa-circle-plus me-2"></i>
                        Buat Bank Soal
                    </a>

                    <a href="#" class="btn shortcut-btn">
                        <i class="fa-solid fa-calendar-days me-2"></i>
                        Jadwalkan Ujian
                    </a>

                    @endif --}}

                    {{-- @if($isWaliKelas)

                    <a href="#" class="btn shortcut-btn">
                        <i class="fa-solid fa-file-export me-2"></i>
                        Rekap Kelas
                    </a>

                    @endif --}}
                    @if(Auth::user()->role == 'guru')
                    <div class="col-6 col-md-3">
                        <a href="#" class="quick-action-btn">
                            <div class="quick-icon-box"><i class="fa-solid fa-circle-plus"></i></div>
                            Buat Bank Soal
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('ujian.create') }}" class="quick-action-btn">
                            <div class="quick-icon-box"><i class="fa-solid fa-calendar-days"></i></div>
                            Jadwalkan Ujian
                        </a>
                    </div>
                    @endif

                    @if($isWaliKelas ?? false)
                    <div class="col-6 col-md-3">
                        <a href="#" class="quick-action-btn">
                            <div class="quick-icon-box"><i class="fa-solid fa-file-export"></i></div>
                            Rekap Kelas
                        </a>
                    </div>
                    @endif

                    @if(Auth::user()->role == 'siswa')
                    <div class="col-6 col-md-3">
                        <a href="#" class="quick-action-btn primary">
                            <div class="quick-icon-box"><i class="fa-solid fa-play"></i></div>
                            Masuk Ruang Ujian
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="#" class="quick-action-btn">
                            <div class="quick-icon-box"><i class="fa-solid fa-clock-rotate-left"></i></div>
                            Riwayat Ujian
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="#" class="quick-action-btn">
                            <div class="quick-icon-box"><i class="fa-solid fa-user"></i></div>
                            Profil Saya
                        </a>
                    </div>
                    @endif

                    <div class="col-12 col-md-3 ms-md-auto d-flex align-items-center justify-content-md-end mt-2 mt-md-0">
                        <span class="secure-badge">
                            <i class="fa-solid fa-shield-halved"></i> Sesi Enkripsi Terlindungi
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

@if(Auth::user()->role == 'siswa' && count($ujian_hari_ini ?? []) > 0)
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('statusChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Belum Dikerjakan', 'Sedang Berjalan', 'Selesai'],
            datasets: [{
                data: [{{ $jumlahBelum }}, {{ $jumlahBerjalan }}, {{ $jumlahSelesai }}],
                backgroundColor: ['#2563eb', '#d97706', '#059669'],
                borderWidth: 0,
                cutout: '72%',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            }
        }
    });
});
</script>
@endif

@endsection