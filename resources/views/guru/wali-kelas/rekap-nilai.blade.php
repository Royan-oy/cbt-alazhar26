@extends('layouts.app')

@section('title', 'Rekap Nilai - Wali Kelas')

@section('content')
<style>
    /* =========================================
       LEADERBOARD REKAP STYLES
       ========================================= */
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0284c7;
        --accent-blue-light: #38bdf8;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --bg-hover: #f8fafc;
    }

    /* Page Header */
    .page-header-card {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        border: none;
        border-radius: 1.25rem;
        position: relative;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
    }
    .page-header-card::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 350px; height: 350px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Executive Stat Cards */
    .summary-stat-card {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -2px rgba(0, 0, 0, 0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
    }
    .summary-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }
    .stat-icon-shape {
        width: 48px;
        height: 48px;
        border-radius: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .form-control-modern {
        border-radius: 10px;
        padding: 0.6rem 1rem;
        border: 1px solid #cbd5e1;
        background-color: #f8fafc;
        font-size: 0.875rem;
        color: #334155;
    }
    .form-control-modern:focus {
        background-color: #ffffff;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        outline: none;
    }
    .input-group-text-modern {
        background-color: #f8fafc;
        border: 1px solid #cbd5e1;
        border-right: none;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    /* Export Button */
    .export-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        padding: 0.625rem 1.25rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .export-btn:hover {
        background: linear-gradient(135deg, #059669, #047857);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
    }
    .export-btn.dropdown-toggle::after {
        margin-left: 0.35rem;
    }

    /* =============================================
       LEADERBOARD TABLE — PREMIUM DESIGN
       ============================================= */
    .leaderboard-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 1.25rem;
        box-shadow: 0 4px 24px -4px rgba(15, 23, 42, 0.06), 0 1px 2px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        position: relative;
    }
    .leaderboard-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #0284c7, #38bdf8, #7dd3fc, #38bdf8, #0284c7);
        background-size: 200% 100%;
        animation: shimmerBar 3s ease infinite;
        z-index: 5;
    }
    @keyframes shimmerBar {
        0%, 100% { background-position: 200% 0; }
        50% { background-position: 0% 0; }
    }

    .leaderboard-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 0;
    }

    .leaderboard-table thead th {
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        color: #334155;
        font-size: 0.6875rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 1rem 1.25rem;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
    }
    .leaderboard-table thead th:nth-child(2) {
        text-align: left;
    }

    .leaderboard-table tbody tr {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .leaderboard-table tbody tr:nth-child(even) td {
        background-color: #fafbfc;
    }
    .leaderboard-table tbody tr:hover td {
        background-color: #eef5ff !important;
    }
    .leaderboard-table tbody tr.rank-gold:hover td { background-color: #fefce8 !important; }
    .leaderboard-table tbody tr.rank-silver:hover td { background-color: #f5f5f5 !important; }
    .leaderboard-table tbody tr.rank-bronze:hover td { background-color: #fff7ed !important; }

    .leaderboard-table tbody td {
        padding: 0.875rem 1.25rem;
        font-size: 0.875rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        text-align: center;
        background-color: #ffffff;
        transition: background-color 0.2s ease;
    }
    .leaderboard-table tbody td:nth-child(2) {
        text-align: left;
    }

    /* Rank Highlight Rows */
    .leaderboard-table tbody tr.rank-gold td {
        background-color: #fffbeb;
    }
    .leaderboard-table tbody tr.rank-silver td {
        background-color: #fafafa;
    }
    .leaderboard-table tbody tr.rank-bronze td {
        background-color: #fff7ed;
    }

    /* Ranking Badges */
    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px; height: 36px;
        border-radius: 50%;
        font-size: 0.8rem;
        font-weight: 800;
        line-height: 1;
    }
    .rank-1 {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: #78350f;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.35);
        animation: pulseGold 2s ease-in-out infinite;
    }
    .rank-2 {
        background: linear-gradient(135deg, #d1d5db, #9ca3af);
        color: #374151;
        box-shadow: 0 2px 6px rgba(156, 163, 175, 0.3);
    }
    .rank-3 {
        background: linear-gradient(135deg, #fdba74, #f97316);
        color: #7c2d12;
        box-shadow: 0 2px 6px rgba(249, 115, 22, 0.3);
    }
    .rank-normal {
        background: #f1f5f9;
        color: #94a3b8;
        font-weight: 600;
        width: 32px; height: 32px;
        font-size: 0.75rem;
    }
    @keyframes pulseGold {
        0%, 100% { box-shadow: 0 2px 8px rgba(245, 158, 11, 0.35); }
        50% { box-shadow: 0 2px 16px rgba(245, 158, 11, 0.55); }
    }

    /* Avatar */
    .avatar-student {
        width: 40px; height: 40px;
        border-radius: 12px;
        color: #fff;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: transform 0.2s ease;
    }
    .leaderboard-table tbody tr:hover .avatar-student {
        transform: scale(1.08);
    }
    .avatar-v1 { background: linear-gradient(135deg, #38bdf8, #0284c7); box-shadow: 0 2px 6px rgba(2, 132, 199, 0.2); }
    .avatar-v2 { background: linear-gradient(135deg, #a78bfa, #7c3aed); box-shadow: 0 2px 6px rgba(124, 58, 237, 0.2); }
    .avatar-v3 { background: linear-gradient(135deg, #34d399, #059669); box-shadow: 0 2px 6px rgba(5, 150, 105, 0.2); }
    .avatar-v4 { background: linear-gradient(135deg, #fb923c, #ea580c); box-shadow: 0 2px 6px rgba(234, 88, 12, 0.2); }
    .avatar-v5 { background: linear-gradient(135deg, #f472b6, #db2777); box-shadow: 0 2px 6px rgba(219, 39, 119, 0.2); }
    .avatar-v6 { background: linear-gradient(135deg, #fbbf24, #d97706); box-shadow: 0 2px 6px rgba(217, 119, 6, 0.2); }

    /* Score Chip */
    .score-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 1rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 800;
        min-width: 56px;
        letter-spacing: -0.01em;
    }
    .score-high {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #15803d;
        border: 1px solid #86efac;
        box-shadow: 0 1px 4px rgba(34, 197, 94, 0.12);
    }
    .score-low {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #b91c1c;
        border: 1px solid #fca5a5;
        box-shadow: 0 1px 4px rgba(239, 68, 68, 0.12);
    }
    .score-none {
        background: #f8fafc;
        color: #cbd5e1;
        border: 1px dashed #e2e8f0;
        font-size: 1rem;
    }

    /* Detail Button */
    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 1rem;
        border-radius: 0.625rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        color: #0284c7;
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
        border: 1px solid #7dd3fc;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
    }
    .btn-detail:hover {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #ffffff;
        border-color: #0284c7;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        text-decoration: none;
    }

    /* Empty State */
    .empty-state-icon {
        width: 88px; height: 88px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Legend Footer */
    .legend-footer {
        background: linear-gradient(180deg, #f8fafc, #f1f5f9);
        border-top: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }
    .legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
    }
    .legend-dot {
        width: 10px; height: 10px;
        border-radius: 4px;
        flex-shrink: 0;
    }
    .legend-dot-high { background: linear-gradient(135deg, #4ade80, #22c55e); }
    .legend-dot-low  { background: linear-gradient(135deg, #f87171, #ef4444); }
    .legend-dot-none { background: #e2e8f0; border: 1px dashed #cbd5e1; }

    /* Dropdown menu customization */
    .dropdown {
        position: relative;
        z-index: 1050;
    }
    .dropdown-menu {
        border-radius: 10px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 24px -4px rgba(15,23,42,0.18) !important;
        padding: 0.4rem 0 !important;
        z-index: 1060 !important;
        position: absolute !important;
    }
    .dropdown-item {
        transition: background 0.15s ease;
    }
    .dropdown-item:hover {
        background-color: #f1f5f9 !important;
    }

    /* Mobile Responsiveness */
    @media (max-width: 767.98px) {
        .page-header-card {
            padding: 1.25rem !important;
            border-radius: 1rem;
        }
        .page-header-card h1 {
            font-size: 1.35rem !important;
        }
        .export-btn {
            width: 100%;
            justify-content: center;
        }
        .leaderboard-table thead th,
        .leaderboard-table tbody td {
            padding: 0.65rem 0.75rem;
        }
        .avatar-student { width: 34px; height: 34px; font-size: 0.75rem; border-radius: 10px; }
        .rank-badge { width: 30px; height: 30px; font-size: 0.7rem; }
        .rank-normal { width: 28px; height: 28px; font-size: 0.65rem; }
        .score-chip { padding: 0.25rem 0.7rem; font-size: 0.8rem; min-width: 48px; }
        .btn-detail { padding: 0.35rem 0.75rem; font-size: 0.75rem; }
        .legend-footer { padding: 0.75rem 1rem; }
    }
</style>

<div class="container-fluid px-0 py-2">

    <!-- PAGE HEADER -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-card p-4 p-md-5">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill mb-3 d-inline-flex align-items-center gap-2"
                              style="font-size: 0.75rem; font-weight: 600; backdrop-filter: blur(4px);">
                            <i class="fa-solid fa-ranking-star"></i>
                            Leaderboard Nilai — Wali Kelas
                        </span>
                        <h1 class="fw-bold text-white mb-2" style="font-size: 1.75rem; letter-spacing: -0.5px;">
                            Rekap Nilai Kelas {{ optional($kelas)->nama_kelas ?? '-' }}
                        </h1>
                        <p class="text-white text-opacity-75 mb-0 d-flex align-items-center gap-2 flex-wrap" style="font-size: 0.875rem;">
                            <span><i class="fa-solid fa-graduation-cap me-1"></i> {{ optional($kelas)->nama_tingkat ?? '' }}</span>
                            <span class="text-white text-opacity-50">•</span>
                            <span><i class="fa-solid fa-calendar-alt me-1"></i> Tahun Ajaran {{ optional($waliKelas->tahunAjaran)->nama_tahun ?? '-' }}</span>
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        {{-- DROPDOWN EXPORT PDF --}}
                        <div class="dropdown d-inline-block">
                            <button class="export-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                                <i class="fa-solid fa-file-pdf fs-6"></i>
                                Export PDF
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 250px;">
                                <li>
                                    <a class="dropdown-item py-2 fw-semibold text-dark" style="font-size: 13px;"
                                       href="{{ route('dashboard-guru.wali-kelas.rekap-nilai.export-pdf') }}" target="_blank">
                                        <i class="fa-solid fa-list-ol me-2 text-danger"></i> Cetak Leaderboard Kelas
                                        <small class="d-block text-muted ms-4" style="font-size: 11px;">Semua ujian — format potret</small>
                                    </a>
                                </li>
                                <li id="pdf-jenis-ujian-li" style="display: none;">
                                    <a class="dropdown-item py-2 fw-semibold text-dark" id="btnExportPdfJenis"
                                       style="font-size: 13px;" href="#" target="_blank">
                                        <i class="fa-solid fa-table me-2 text-primary"></i> Cetak Matriks: <span id="pdf-jenis-ujian-text"></span>
                                        <small class="d-block text-muted ms-4" style="font-size: 11px;">Matriks per mapel — format lanskap</small>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        {{-- DROPDOWN EXPORT EXCEL --}}
                        <div class="dropdown d-inline-block">
                            <button class="export-btn dropdown-toggle" type="button" id="dropdownExcelButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fa-solid fa-file-excel fs-6"></i>
                                Export Excel
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownExcelButton" style="min-width: 250px;">
                                <li>
                                    <a class="dropdown-item py-2 fw-semibold text-dark" style="font-size: 13px;"
                                       href="{{ route('dashboard-guru.wali-kelas.rekap-nilai.export') }}" target="_blank">
                                        <i class="fa-solid fa-list-ol me-2 text-success"></i> Cetak Leaderboard Kelas
                                        <small class="d-block text-muted ms-4" style="font-size: 11px;">Semua ujian — format rekap</small>
                                    </a>
                                </li>
                                <li id="excel-jenis-ujian-li" style="display: none;">
                                    <a class="dropdown-item py-2 fw-semibold text-dark" id="btnExportExcelJenis"
                                       style="font-size: 13px;" href="#" target="_blank">
                                        <i class="fa-solid fa-table me-2 text-success"></i> Cetak Matriks: <span id="excel-jenis-ujian-text"></span>
                                        <small class="d-block text-muted ms-4" style="font-size: 11px;">Matriks per mapel — rata-rata mapel</small>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EXECUTIVE STAT CARDS (4 CARD RINGKASAN KELAS) -->
    <div class="row g-3 g-md-4 mb-4">
        <!-- Rata-Rata Kelas -->
        <div class="col-6 col-lg-3">
            <div class="summary-stat-card d-flex align-items-center gap-3">
                <div class="stat-icon-shape bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="text-muted small fw-medium d-block mb-1" style="font-size: 12px;">Rata-Rata Kelas</span>
                    <h3 class="mb-0 fw-bold text-dark lh-1">{{ $rerataKelas }} <span class="fs-6 text-muted fw-normal">/ 100</span></h3>
                </div>
            </div>
        </div>

        <!-- Ketuntasan Belajar -->
        <div class="col-6 col-lg-3">
            <div class="summary-stat-card d-flex align-items-center gap-3">
                <div class="stat-icon-shape bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="text-muted small fw-medium d-block mb-1" style="font-size: 12px;">Tingkat Ketuntasan</span>
                    <h3 class="mb-0 fw-bold text-dark lh-1">{{ $persenTuntas }}% <span class="fs-6 text-muted fw-normal ms-1">({{ $tuntasCount }} Siswa)</span></h3>
                </div>
            </div>
        </div>

        <!-- Top Scorer / Nilai Tertinggi -->
        <div class="col-6 col-lg-3">
            <div class="summary-stat-card d-flex align-items-center gap-3">
                <div class="stat-icon-shape bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="text-muted small fw-medium d-block mb-1" style="font-size: 12px;">Nilai Tertinggi</span>
                    <h3 class="mb-0 fw-bold text-dark lh-1">{{ $topScore }}</h3>
                    <small class="text-muted text-truncate d-block mt-1" style="font-size: 11px;" title="{{ $topSiswaNama }}">{{ $topSiswaNama }}</small>
                </div>
            </div>
        </div>

        <!-- Total Siswa -->
        <div class="col-6 col-lg-3">
            <div class="summary-stat-card d-flex align-items-center gap-3">
                <div class="stat-icon-shape bg-info bg-opacity-10 text-info">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="text-muted small fw-medium d-block mb-1" style="font-size: 12px;">Total Siswa</span>
                    <h3 class="mb-0 fw-bold text-dark lh-1">{{ count($siswas) }} <span class="fs-6 text-muted fw-normal">Siswa</span></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTROL CARD: PENCARIAN & FILTER -->
    <div class="card border-0 shadow-sm mb-4 rounded-4" style="background-color: #ffffff;">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('dashboard-guru.wali-kelas.rekap-nilai') }}">
                <div class="row g-3 align-items-end">
                    <!-- Cari Nama / NIS -->
                    <div class="col-12 col-md-7">
                        <label for="search-input" class="form-label text-muted fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.8px;">CARI SISWA</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-modern">
                                <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            </span>
                            <input type="search" id="search-input" class="form-control form-control-modern border-start-0 ps-0" placeholder="Cari nama atau NIS siswa...">
                        </div>
                    </div>

                    <!-- Filter Jenis Ujian -->
                    <div class="col-12 col-md-5">
                        @if($allJenisUjian->isNotEmpty())
                            <label for="jenis_ujian" class="form-label text-muted fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.8px;">JENIS UJIAN</label>
                            <select name="jenis_ujian" id="jenis_ujian" class="form-control form-control-modern" onchange="this.form.submit()">
                                <option value="">Semua Jenis Ujian</option>
                                @foreach($allJenisUjian as $jenis)
                                    <option value="{{ $jenis }}" {{ $jenisFilter === $jenis ? 'selected' : '' }}>
                                        {{ $jenis }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- LEADERBOARD SECTION TITLE -->
    <div class="d-flex align-items-center mb-4 gap-2">
        <h5 class="fw-bold mb-0" style="color: #0f172a;">
            <i class="fa-solid fa-ranking-star me-2" style="color: #0284c7;"></i> Peringkat Siswa
        </h5>
        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1" style="font-size: 11px; font-weight: 600;">
            {{ count($siswas) }} siswa
        </span>
        @if($jenisFilter)
            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3 py-1" style="font-size: 11px; font-weight: 600;">
                <i class="fa-solid fa-filter me-1"></i> {{ $jenisFilter }}
            </span>
        @endif
    </div>

    <!-- LEADERBOARD TABLE -->
    <div class="leaderboard-card">
        @if(count($siswas) === 0)
            <div class="text-center py-5" style="padding-top: 3rem !important;">
                <div class="empty-state-icon mb-3 mx-auto">
                    <i class="fa-solid fa-clipboard-list fa-2x" style="color: #94a3b8;"></i>
                </div>
                <h5 class="fw-bold mb-2" style="color: #334155;">Belum Ada Data Ujian</h5>
                <p class="mb-0 text-muted" style="font-size: 0.875rem; max-width: 360px; margin: 0 auto;">Belum ada data ujian dilaksanakan untuk kelas ini.</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">
                            <i class="fa-solid fa-medal me-1" style="font-size: 10px;"></i> Peringkat
                        </th>
                        <th>
                            <i class="fa-solid fa-user me-1" style="font-size: 10px;"></i> Nama Siswa
                        </th>
                        <th style="width: 140px;">
                            <i class="fa-solid fa-chart-simple me-1" style="font-size: 10px;"></i> Rata-Rata
                        </th>
                        <th style="width: 120px;">
                            <i class="fa-solid fa-gear me-1" style="font-size: 10px;"></i> Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $avatarVariants = ['avatar-v1','avatar-v2','avatar-v3','avatar-v4','avatar-v5','avatar-v6'];
                    @endphp
                    @foreach($siswas as $idx => $siswa)
                    @php
                        $summary = $studentSummaries->get($siswa->id);
                        $avg = $summary['avg'] ?? null;
                        $status = $summary['status'] ?? 'belum';
                        $rank = $rankMap[$siswa->id] ?? null;
                        $avatarClass = $avatarVariants[$idx % count($avatarVariants)];
                        $rowClass = '';
                        if ($rank === 1) $rowClass = 'rank-gold';
                        elseif ($rank === 2) $rowClass = 'rank-silver';
                        elseif ($rank === 3) $rowClass = 'rank-bronze';
                    @endphp
                    <tr class="siswa-row-item {{ $rowClass }}" data-nama="{{ strtolower($siswa->nama) }}" data-nis="{{ $siswa->nis }}">
                        <!-- Peringkat -->
                        <td>
                            @if($rank === 1)
                                <span class="rank-badge rank-1" title="Peringkat 1">🥇</span>
                            @elseif($rank === 2)
                                <span class="rank-badge rank-2" title="Peringkat 2">🥈</span>
                            @elseif($rank === 3)
                                <span class="rank-badge rank-3" title="Peringkat 3">🥉</span>
                            @elseif($rank)
                                <span class="rank-badge rank-normal">{{ $rank }}</span>
                            @else
                                <span class="rank-badge rank-normal">—</span>
                            @endif
                        </td>

                        <!-- Nama Siswa + NIS + Avatar -->
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-student {{ $avatarClass }}">{{ strtoupper(substr($siswa->nama, 0, 2)) }}</div>
                                <div class="overflow-hidden">
                                    <span class="fw-semibold text-dark text-truncate d-block" style="max-width: 280px; font-size: 0.9rem; line-height: 1.3;" title="{{ $siswa->nama }}">{{ $siswa->nama }}</span>
                                    <small class="text-muted font-monospace d-block" style="font-size: 0.7rem; opacity: 0.7;">NIS: {{ $siswa->nis }}</small>
                                </div>
                            </div>
                        </td>

                        <!-- Rata-rata -->
                        <td>
                            @if($avg !== null)
                                @if($avg >= 75)
                                    <span class="score-chip score-high">{{ $avg }}</span>
                                @else
                                    <span class="score-chip score-low">{{ $avg }}</span>
                                @endif
                            @else
                                <span class="score-chip score-none">—</span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td>
                            <a href="{{ route('dashboard-guru.wali-kelas.rekap-nilai.detail-siswa', $siswa->id) }}" class="btn-detail" title="Lihat detail nilai {{ $siswa->nama }}">
                                <i class="fa-solid fa-eye"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- LEGEND FOOTER -->
        <div class="legend-footer">
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <span class="legend-item">
                        <span class="legend-dot legend-dot-high"></span> ≥ KKM (Tuntas)
                    </span>
                    <span class="legend-item">
                        <span class="legend-dot legend-dot-low"></span> &lt; KKM (Belum Tuntas)
                    </span>
                    <span class="legend-item">
                        <span class="legend-dot legend-dot-none"></span> Belum Ujian
                    </span>
                </div>
                <span style="color: #e2e8f0; font-size: 14px;" class="d-none d-md-inline">|</span>
                <span class="legend-item">
                    🥇🥈🥉 Peringkat 3 besar berdasarkan rata-rata
                </span>
            </div>
        </div>
        @endif
    </div>

</div>

<!-- JAVASCRIPT: LIVE SEARCH & DROPDOWN EXPORT -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('search-input');
    const jenisSelect = document.getElementById('jenis_ujian');
    
    // PDF Elements
    const btnExportPdfJenis = document.getElementById('btnExportPdfJenis');
    const pdfJenisLi = document.getElementById('pdf-jenis-ujian-li');
    const pdfJenisText = document.getElementById('pdf-jenis-ujian-text');
    const basePdfUrl = "{{ route('dashboard-guru.wali-kelas.rekap-nilai.export-pdf') }}";

    // Excel Elements
    const btnExportExcelJenis = document.getElementById('btnExportExcelJenis');
    const excelJenisLi = document.getElementById('excel-jenis-ujian-li');
    const excelJenisText = document.getElementById('excel-jenis-ujian-text');
    const baseExcelUrl = "{{ route('dashboard-guru.wali-kelas.rekap-nilai.export') }}";

    // Dropdown Export: tampilkan opsi cetak matriks jika jenis ujian dipilih
    function updateExportUrls() {
        const jenisVal = jenisSelect ? jenisSelect.value : '';

        // Update PDF
        if (jenisVal && btnExportPdfJenis && pdfJenisLi && pdfJenisText) {
            pdfJenisText.textContent = jenisVal;
            btnExportPdfJenis.href = `${basePdfUrl}?jenis_ujian=${encodeURIComponent(jenisVal)}`;
            pdfJenisLi.style.display = 'block';
        } else if (pdfJenisLi) {
            pdfJenisLi.style.display = 'none';
        }

        // Update Excel
        if (jenisVal && btnExportExcelJenis && excelJenisLi && excelJenisText) {
            excelJenisText.textContent = jenisVal;
            btnExportExcelJenis.href = `${baseExcelUrl}?jenis_ujian=${encodeURIComponent(jenisVal)}`;
            excelJenisLi.style.display = 'block';
        } else if (excelJenisLi) {
            excelJenisLi.style.display = 'none';
        }
    }

    // Live Search filter baris tabel
    function filterStudents() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const tableRows = document.querySelectorAll('.siswa-row-item');

        tableRows.forEach(row => {
            const nama = row.dataset.nama || '';
            const nis = row.dataset.nis || '';
            const matchQuery = !query || nama.includes(query) || nis.includes(query);
            row.style.display = matchQuery ? '' : 'none';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterStudents);
    }
    if (jenisSelect) {
        jenisSelect.addEventListener('change', updateExportUrls);
    }

    // Inisialisasi awal (saat halaman dimuat dengan jenis ujian sudah aktif)
    updateExportUrls();
});
</script>
@endsection
