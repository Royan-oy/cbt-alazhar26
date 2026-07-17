@extends('layouts.app')

@section('title', 'Dashboard Guru')

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

    @media (max-width: 767.98px) {
        .welcome-banner { padding: 24px !important; text-align: center; }
        .welcome-banner h1 { font-size: 22px !important; }
        .welcome-banner p { font-size: 12px !important; }
        .stat-card { padding: 16px; }
        .icon-shape { width: 40px; height: 40px; font-size: 16px; }
        .stat-card h3 { font-size: 20px !important; }
    }
</style>

<div class="container-fluid px-0">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-banner p-4 p-md-5 rounded-4 shadow-sm">
                <span class="badge text-uppercase bg-info bg-opacity-10 text-info border border-info border-opacity-25 align-self-start mb-3 px-3 py-2 rounded-pill" style="font-size:10px;font-weight:700;letter-spacing:.7px;">
                    <i class="fa-solid fa-circle-check me-1"></i> Portal Akademik Aktif
                </span>
                <h1 class="fw-bold text-white mb-2">Selamat Datang, {{ Auth::user()->nama }} 👋</h1>
                <p class="mb-0" style="font-size:14px;color:#94a3b8;line-height:1.8;">
                    Anda login sebagai <strong class="text-info">Guru</strong> di <strong class="text-white">CBT Smart Online</strong> Sekolah Islam Al Azhar Pekalongan.
                </p>
            </div>
        </div>
    </div>

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

    <div class="row">
        <div class="col-12">
            <div class="card bg-white p-4 rounded-4 border-0 shadow-sm">
                <h6 class="fw-bold text-dark mb-3" style="font-size: 14px;">Aksi Pintasan Aplikasi</h6>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('dashboard-guru.bank-soal.create') ?? '#' }}" class="btn shortcut-btn text-dark text-decoration-none">
                        <i class="fa-solid fa-circle-plus me-2"></i> Buat Bank Soal
                    </a>
                    <a href="#" class="btn shortcut-btn text-dark text-decoration-none">
                        <i class="fa-solid fa-calendar-days me-2"></i> Jadwalkan Ujian
                    </a>
                    @if($isWaliKelas)
                    <a href="#" class="btn shortcut-btn text-dark text-decoration-none">
                        <i class="fa-solid fa-file-export me-2"></i> Rekap Kelas
                    </a>
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