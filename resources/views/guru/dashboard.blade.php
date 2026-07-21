@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<style>
    /* =========================================
       MODERN MINIMALIST DASHBOARD CSS
       ========================================= */
    :root {
        --primary-slate: #0f172a;
        --secondary-slate: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --bg-hover: #f8fafc;
        --accent-blue: #3b82f6;
    }

    /* Banner Section */
    .welcome-banner {
        background: linear-gradient(135deg, var(--primary-slate) 0%, var(--secondary-slate) 100%);
        border: none;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
    }

    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Minimalist Stat Cards */
    .stat-card {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -2px rgba(0, 0, 0, 0.02);
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    .icon-shape {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Modern Action Buttons */
    .action-btn {
        display: inline-flex;
        align-items: center;
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        color: #334155;
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02);
    }

    .action-btn i {
        font-size: 1.1rem;
        color: var(--text-muted);
        transition: color 0.2s ease;
    }

    .action-btn:hover {
        background-color: var(--bg-hover);
        border-color: var(--accent-blue);
        color: var(--accent-blue);
        transform: translateY(-1px);
    }

    .action-btn:hover i {
        color: var(--accent-blue);
    }

    /* Section Titles */
    .section-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    
    .section-title::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: var(--accent-blue);
        border-radius: 50%;
        margin-right: 0.5rem;
    }

    /* =========================================
       MOBILE RESPONSIVENESS (!important)
       ========================================= */
    @media (max-width: 767.98px) {
        .welcome-banner { 
            padding: 1.5rem !important; 
            text-align: center;
            border-radius: 1rem !important;
        }
        .welcome-banner .badge {
            align-self: center !important;
            margin: 0 auto 1rem auto !important;
            display: inline-flex;
        }
        .welcome-banner h1 { font-size: 1.5rem !important; }
        .welcome-banner p { font-size: 0.85rem !important; }
        
        .stat-card { 
            padding: 1.25rem !important; 
            border-radius: 0.875rem !important;
        }
        .icon-shape { 
            width: 40px !important; 
            height: 40px !important; 
            font-size: 1rem !important; 
        }
        .stat-card h3 { font-size: 1.5rem !important; }
        
        .action-btn {
            width: 100%;
            justify-content: center;
            margin-bottom: 0.5rem;
        }
        
        .security-badge {
            width: 100%;
            justify-content: center;
            margin-top: 1rem !important;
        }
    }
</style>

<div class="container-fluid px-0 py-2">
    
    <!-- HEADER / BANNER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-banner p-4 p-md-5 rounded-4">
                <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 align-self-start mb-3 px-3 py-2 rounded-pill" style="font-size: 10px; font-weight: 600; letter-spacing: 0.5px; backdrop-filter: blur(4px);">
                    <i class="fa-solid fa-shield-check me-1"></i> Sesi Aman Terenkripsi
                </span>
                
                <h1 class="fw-bold text-white mb-2" style="letter-spacing: -0.5px;">
                    Halo, {{ Auth::user()->nama }} 👋
                </h1>
                
                <p class="mb-0 text-white text-opacity-75" style="font-size: 14px; line-height: 1.6; max-width: 600px;">
                    Selamat datang di <strong class="text-white">CBT Smart Online</strong> Sekolah Islam Al Azhar Pekalongan. Berikut adalah ringkasan aktivitas akademik Anda hari ini.
                </p>
            </div>
        </div>
    </div>

    <!-- STATISTIK GURU -->
    <h5 class="section-title">Ringkasan Akademik</h5>
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="icon-shape bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium d-block mb-1" style="font-size: 12px;">Bank Soal Anda</span>
                    <h3 class="mb-0 fw-bold text-dark lh-1">{{ $total_bank_soal ?? 0 }} <span class="fs-6 text-muted fw-normal ms-1">Paket</span></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- PEMANTAUAN WALI KELAS (Dinamis) -->
    @if($isWaliKelas)
    <h5 class="section-title mt-2">Pemantauan Ruang Kelas</h5>
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="icon-shape bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-laptop-file"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium d-block mb-1" style="font-size: 12px;">Sedang Mengerjakan</span>
                    <h3 class="mb-0 fw-bold text-dark lh-1">{{ $siswa_ujian ?? 0 }} <span class="fs-6 text-muted fw-normal ms-1">Siswa</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="stat-card d-flex align-items-center gap-3">
                <div class="icon-shape bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium d-block mb-1" style="font-size: 12px;">Sudah Selesai</span>
                    <h3 class="mb-0 fw-bold text-dark lh-1">{{ $siswa_selesai ?? 0 }} <span class="fs-6 text-muted fw-normal ms-1">Siswa</span></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- QUICK ACTIONS -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-white p-4 rounded-4 border-0 shadow-sm mt-2" style="border: 1px solid var(--border-color) !important;">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
                    <h6 class="fw-bold text-dark mb-3 mb-md-0 m-0" style="font-size: 15px;">Aksi Cepat</h6>
                    <div class="security-badge d-flex align-items-center gap-2 text-muted" style="font-size: 12px; font-weight: 500;">
                        <i class="fa-solid fa-server text-success"></i> Server Status: Online
                    </div>
                </div>
                
                <hr class="text-muted opacity-25 mt-0 mb-3">
                
                <div class="d-flex flex-wrap gap-2 gap-md-3">
                    <a href="{{ route('dashboard-guru.bank-soal.create') }}" class="action-btn">
                        <i class="fa-solid fa-circle-plus me-2"></i> Buat Bank Soal
                    </a>
                    <a href="{{ route('dashboard-guru.jadwal-ujian.index') }}" class="action-btn">
                        <i class="fa-solid fa-calendar-plus me-2"></i> Jadwal Ujian
                    </a>
                    
                    @if($isWaliKelas)
                    <a href="{{ route('dashboard-guru.wali-kelas.rekap-nilai') }}" class="action-btn">
                        <i class="fa-solid fa-chart-line me-2"></i> Rekap Kelas
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection