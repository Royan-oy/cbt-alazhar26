@extends('layouts.app')

@section('title', 'Rekap Nilai - Wali Kelas')

@section('content')
<style>
    /* =========================================
       MODERN DASHBOARD & REKAP STYLES
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
        overflow: hidden;
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

    /* FILTER PILLS STYLING (SAMAKAN PERSIS DENGAN JADWAL UJIAN INDEX) */
    .custom-filter-pills .btn-view-mode {
        color: #64748b;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        font-size: 13px;
        font-weight: 600;
    }
    .custom-filter-pills .btn-view-mode:hover {
        background-color: #f1f5f9;
        color: #0284c7;
    }
    .custom-filter-pills .btn-view-mode.active {
        background: linear-gradient(135deg, #38bdf8, #0284c7) !important;
        color: #ffffff !important;
        border-color: #0284c7;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
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
    }
    .export-btn:hover {
        background: linear-gradient(135deg, #059669, #047857);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
    }

    /* Rekap Table Styling (Grouped by Subject - 100% Fit) */
    .rekap-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .rekap-table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

    .rekap-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 0;
    }

    .rekap-table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.9rem 0.75rem;
        border-bottom: 2px solid var(--border-color);
        border-right: 1px solid #f1f5f9;
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
    }
    .rekap-table thead th:first-child,
    .rekap-table thead th:nth-child(2) {
        text-align: left;
    }

    .rekap-table tbody tr {
        transition: background-color 0.2s ease;
    }
    .rekap-table tbody tr:hover td { 
        background-color: #f1f5f9; 
    }
    .rekap-table tbody td {
        padding: 0.75rem;
        font-size: 0.875rem;
        color: #334155;
        border-bottom: 1px solid var(--border-color);
        border-right: 1px solid #f1f5f9;
        vertical-align: middle;
        text-align: center;
        white-space: nowrap;
        background-color: #ffffff;
    }
    .rekap-table tbody td:first-child,
    .rekap-table tbody td:nth-child(2) {
        text-align: left;
    }

    /* Sticky Columns Setup */
    .sticky-col {
        position: sticky;
        z-index: 10;
        background-color: inherit;
    }
    .rekap-table thead th.sticky-col {
        background-color: #f8fafc;
        z-index: 11;
    }
    
    .col-no { left: 0; min-width: 45px; max-width: 45px; }
    .col-nama { left: 45px; min-width: 220px; max-width: 250px; }

    .rekap-table td.sticky-col, .rekap-table th.sticky-col {
        box-shadow: 1px 0 0 0 #e2e8f0; 
        border-right: none !important;
    }

    /* Score Chips & Interactive Popover */
    .score-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        min-width: 44px;
        cursor: pointer;
        transition: transform 0.15s ease;
    }
    .score-chip:hover {
        transform: scale(1.08);
    }
    .score-high { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .score-mid  { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
    .score-low  { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .score-none { background: #f1f5f9; color: #94a3b8; cursor: default; }

    .avg-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.3rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 800;
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
        min-width: 50px;
    }

    .avatar-student {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #38bdf8, #0284c7);
        color: #fff;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 4px rgba(2, 132, 199, 0.2);
    }

    /* Student Cards (View Mode 2) */
    .student-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .student-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px -3px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }

    /* Subject Card (View Mode 3) */
    .subject-summary-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }
    .subject-summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
    }

    /* Custom Popover Styling */
    .popover {
        border-radius: 0.75rem !important;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
    .popover-header {
        background: #f8fafc !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-top-left-radius: 0.75rem !important;
        border-top-right-radius: 0.75rem !important;
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

        .col-no { min-width: 38px; max-width: 38px; padding: 0.5rem !important; }
        .col-nama { left: 38px; min-width: 150px; max-width: 160px; padding: 0.5rem !important; white-space: normal; }
        .col-nama .avatar-student { width: 28px; height: 28px; font-size: 0.7rem; }
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
                            <i class="fa-solid fa-clipboard-check"></i>
                            Rekapitulasi Nilai — Wali Kelas
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
                    <div>
                        <a href="{{ route('dashboard-guru.wali-kelas.rekap-nilai.export') }}" class="export-btn">
                            <i class="fa-solid fa-file-excel fs-6"></i>
                            Export Excel
                        </a>
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
                    <div class="col-12 col-md-5">
                        <label for="search-input" class="form-label text-muted fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.8px;">CARI SISWA</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-modern">
                                <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            </span>
                            <input type="search" id="search-input" class="form-control form-control-modern border-start-0 ps-0" placeholder="Cari nama atau NIS siswa...">
                        </div>
                    </div>

                    <!-- Filter Status Performa -->
                    <div class="col-12 col-md-3">
                        <label for="status-filter-select" class="form-label text-muted fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.8px;">STATUS SISWA</label>
                        <select id="status-filter-select" class="form-control form-control-modern">
                            <option value="semua">Semua Status</option>
                            <option value="tuntas">Tuntas (≥ 75)</option>
                            <option value="kurang">Belum Tuntas (< 75)</option>
                        </select>
                    </div>

                    <!-- Filter Jenis Ujian -->
                    <div class="col-12 col-md-4">
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

    <!-- TITLE SECTION & VIEW SWITCHER PILLS (POSISI DISAMAKAN PERSIS DENGAN JADWAL UJIAN INDEX) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h5 class="fw-bold mb-0" id="section-title" style="color: #0f172a;">
            <i class="fa-solid fa-table-cells me-2" style="color: #0284c7;"></i> <span>Rekapitulasi Matriks Mapel</span>
        </h5>
        
        <!-- BUTTON PILLS MODE TAMPILAN -->
        <div class="nav nav-pills custom-filter-pills gap-2 flex-wrap" role="tablist">
            <button type="button" class="nav-link active rounded-pill px-3 py-2 btn-view-mode" data-view="matrix">
                <i class="fa-solid fa-table-cells me-1"></i> Matriks Mapel
            </button>
            <button type="button" class="nav-link rounded-pill px-3 py-2 btn-view-mode" data-view="cards">
                <i class="fa-solid fa-address-card me-1"></i> Kartu Siswa
            </button>
            <button type="button" class="nav-link rounded-pill px-3 py-2 btn-view-mode" data-view="mapel">
                <i class="fa-solid fa-book-open me-1"></i> Analisis Mapel
            </button>
        </div>
    </div>

    <!-- VIEW MODE 1: MATRIKS TABEL (GROUPED BY SUBJECT - 100% PAS LAYAR) -->
    <div id="view-matrix" class="view-content-section">
        <div class="rekap-card">
            @if($ujians->isEmpty())
                <div class="text-center py-5">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 80px; height: 80px; background-color: #f1f5f9;">
                        <i class="fa-solid fa-clipboard-list fa-2x text-muted"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="color: #334155;">Belum Ada Data Ujian</h5>
                    <p class="mb-0 text-muted" style="font-size: 0.875rem;">Ujian untuk kelas ini belum tersedia atau belum dilaksanakan.</p>
                </div>
            @else
            <div class="rekap-table-wrapper">
                <table class="rekap-table">
                    <thead>
                        <tr>
                            <th class="sticky-col col-no">No</th>
                            <th class="sticky-col col-nama">Nama Siswa & NIS</th>
                            @foreach($mapels as $mapel)
                            <th>{{ $mapel }}</th>
                            @endforeach
                            <th>Rata-rata</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $idx => $siswa)
                        @php
                            $summary = $studentSummaries->get($siswa->id);
                            $avg = $summary['avg'] ?? null;
                            $status = $summary['status'] ?? 'belum';
                            $mapelScores = $studentMapelMatrix->get($siswa->id, collect());
                        @endphp
                        <tr class="siswa-row-item" data-nama="{{ strtolower($siswa->nama) }}" data-nis="{{ $siswa->nis }}" data-status="{{ $status }}">
                            <td class="sticky-col col-no text-muted text-center fw-medium">{{ $idx + 1 }}</td>
                            
                            <!-- Nama Siswa + Subtitle NIS (Merged to Save Width) -->
                            <td class="sticky-col col-nama">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="avatar-student">{{ strtoupper(substr($siswa->nama, 0, 2)) }}</div>
                                    <div class="overflow-hidden">
                                        <span class="fw-semibold text-dark text-truncate d-block" style="max-width: 190px; font-size: 0.875rem;" title="{{ $siswa->nama }}">{{ $siswa->nama }}</span>
                                        <small class="text-muted font-monospace d-block" style="font-size: 0.75rem;">NIS: {{ $siswa->nis }}</small>
                                    </div>
                                </div>
                            </td>

                            <!-- Nilai Per Mata Pelajaran (dengan Popover Hover/Click Rincian Ujian) -->
                            @foreach($mapels as $mapel)
                            @php
                                $mapelData = $mapelScores->get($mapel);
                                $mapelAvg  = $mapelData['avg'] ?? null;
                                $mapelKkm  = $mapelData['kkm'] ?? 75;
                                $mapelDetails = $mapelData['details'] ?? [];
                                $jsonDetails = json_encode($mapelDetails);
                            @endphp
                            <td>
                                @if($mapelAvg !== null)
                                    @if($mapelAvg >= $mapelKkm)
                                        <span class="score-chip score-high popover-trigger" tabindex="0" data-mapel="{{ $mapel }}" data-details="{{ $jsonDetails }}">{{ number_format($mapelAvg, 0) }}</span>
                                    @else
                                        <span class="score-chip score-low popover-trigger" tabindex="0" data-mapel="{{ $mapel }}" data-details="{{ $jsonDetails }}">{{ number_format($mapelAvg, 0) }}</span>
                                    @endif
                                @else
                                    <span class="score-chip score-none">—</span>
                                @endif
                            </td>
                            @endforeach

                            <!-- Rata-rata Keseluruhan -->
                            <td>
                                @if($avg !== null)
                                    <span class="avg-chip">{{ $avg }}</span>
                                @else
                                    <span class="score-chip score-none">—</span>
                                @endif
                            </td>

                            <!-- Status Ketuntasan -->
                            <td>
                                @if($status == 'tuntas')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">Tuntas</span>
                                @elseif($status == 'kurang')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">Belum Tuntas</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">Belum Ujian</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ 4 + count($mapels) }}" class="text-center py-5 text-muted">
                                Tidak ada data siswa untuk kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- LEGEND INFORMASI -->
            <div class="px-4 py-3 border-top d-flex flex-column flex-md-row align-items-md-center gap-3" style="background-color: #f8fafc; border-color: var(--border-color) !important; font-size: 0.8125rem; color: #475569;">
                <div class="fw-semibold me-2"><i class="fa-solid fa-circle-info me-1"></i> Keterangan Nilai & Fitur Interaktif:</div>
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <span class="d-flex align-items-center gap-2"><span class="score-chip score-high" style="min-width:28px;">—</span> ≥ KKM (Tuntas)</span>
                    <span class="d-flex align-items-center gap-2"><span class="score-chip score-low" style="min-width:28px;">—</span> &lt; KKM (Belum Tuntas)</span>
                    <span class="text-muted opacity-50">|</span>
                    <span class="text-muted"><i class="fa-solid fa-hand-pointer me-1"></i> Sentuh / Hover angka nilai mapel untuk melihat rincian tiap ujian.</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- VIEW MODE 2: KARTU SISWA (STUDENT CARDS) -->
    <div id="view-cards" class="view-content-section" style="display: none;">
        <div class="row g-4" id="student-card-grid">
            @forelse($siswas as $siswa)
            @php
                $summary = $studentSummaries->get($siswa->id);
                $avg = $summary['avg'] ?? null;
                $status = $summary['status'] ?? 'belum';
                $nilaiSiswa = $nilaiData->get($siswa->id, collect())->keyBy('ujian_id');
            @endphp
            <div class="col-12 col-md-6 col-xl-4 student-card-wrapper" data-nama="{{ strtolower($siswa->nama) }}" data-nis="{{ $siswa->nis }}" data-status="{{ $status }}">
                <div class="student-card p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-student" style="width: 44px; height: 44px; font-size: 1rem;">{{ strtoupper(substr($siswa->nama, 0, 2)) }}</div>
                            <div class="overflow-hidden">
                                <h6 class="fw-bold text-dark mb-0 text-truncate" title="{{ $siswa->nama }}">{{ $siswa->nama }}</h6>
                                <small class="text-muted font-monospace">NIS: {{ $siswa->nis }}</small>
                            </div>
                        </div>
                        @if($status == 'tuntas')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">Tuntas</span>
                        @elseif($status == 'kurang')
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">Belum Tuntas</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 11px;">Belum Ujian</span>
                        @endif
                    </div>

                    <div class="p-3 bg-light rounded-4 mb-3 border" style="border-color: #f1f5f9 !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small fw-medium">Rata-Rata Nilai</span>
                            <span class="fs-5 fw-bold text-primary-custom">{{ $avg !== null ? number_format($avg, 1) : '—' }}</span>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <small class="text-uppercase fw-bold d-block text-muted mb-2" style="font-size: 10px; letter-spacing: 0.5px;">Rincian Ujian Terdaftar</small>
                        <div class="d-flex flex-column gap-2" style="max-height: 180px; overflow-y: auto;">
                            @foreach($ujians as $ujian)
                            @php
                                $record = $nilaiSiswa->get($ujian->id);
                                $nilai  = $record ? (float) $record->nilai_akhir : null;
                                $kkmUjian = $ujian->kkm ?? 75;
                            @endphp
                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3" style="background: #f8fafc; font-size: 12px;">
                                <span class="text-dark fw-medium text-truncate me-2" style="max-width: 200px;">
                                    {{ $ujian->nama_mapel }} <small class="text-muted">({{ $ujian->nama_ujian }})</small>
                                </span>
                                <div>
                                    @if($nilai !== null)
                                        @if($nilai >= $kkmUjian)
                                            <span class="badge bg-success bg-opacity-10 text-success fw-bold">{{ number_format($nilai, 0) }}</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger fw-bold">{{ number_format($nilai, 0) }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">Belum ada data siswa.</div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- VIEW MODE 3: RINGKASAN MAPEL (SUBJECT ANALYSIS) -->
    <div id="view-mapel" class="view-content-section" style="display: none;">
        <div class="row g-4">
            @forelse($mapelStats as $stat)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="subject-summary-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $stat['nama_mapel'] }}</h6>
                            <small class="text-muted"><i class="fa-solid fa-file-lines me-1"></i> {{ $stat['total_ujian'] }} Ujian Dilaksanakan</small>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary-custom px-3 py-2 rounded-pill fw-bold" style="font-size: 14px;">
                            {{ $stat['rerata'] }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between small text-muted mb-1" style="font-size: 11px;">
                            <span>Rerata Capaian Mapel</span>
                            <span>{{ $stat['rerata'] }}%</span>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 4px; background: #e2e8f0;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, $stat['rerata']) }}%;"></div>
                        </div>
                    </div>

                    <div class="row g-2 text-center pt-2 border-top" style="font-size: 12px;">
                        <div class="col-6">
                            <span class="text-muted d-block" style="font-size: 11px;">Nilai Tertinggi</span>
                            <span class="fw-bold text-success"><i class="fa-solid fa-arrow-up me-1"></i> {{ $stat['max'] }}</span>
                        </div>
                        <div class="col-6 border-start">
                            <span class="text-muted d-block" style="font-size: 11px;">Nilai Terendah</span>
                            <span class="fw-bold text-danger"><i class="fa-solid fa-arrow-down me-1"></i> {{ $stat['min'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 text-muted">Belum ada analisis data mata pelajaran.</div>
            </div>
            @endforelse
        </div>
    </div>

</div>

<!-- JAVASCRIPT: VIEW SWITCHER, LIVE SEARCH, DAN BOOTSTRAP POPOVERS -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const viewBtns = document.querySelectorAll('.btn-view-mode');
    const viewSections = document.querySelectorAll('.view-content-section');
    const searchInput = document.getElementById('search-input');
    const statusSelect = document.getElementById('status-filter-select');
    const sectionTitleText = document.querySelector('#section-title span');
    const sectionTitleIcon = document.querySelector('#section-title i');

    const titleMap = {
        'matrix': { text: 'Rekapitulasi Matriks Mapel', icon: 'fa-table-cells' },
        'cards': { text: 'Kartu Performa Siswa', icon: 'fa-address-card' },
        'mapel': { text: 'Analisis Mata Pelajaran', icon: 'fa-book-open' }
    };

    // 1. Inisialisasi Bootstrap Popovers tanpa Bocor Kode HTML
    const popoverTriggers = document.querySelectorAll('.popover-trigger');
    popoverTriggers.forEach(el => {
        const detailsJson = el.getAttribute('data-details');
        const mapelNama = el.getAttribute('data-mapel');
        if (!detailsJson) return;

        try {
            const details = JSON.parse(detailsJson);
            if (!details || details.length === 0) return;

            let html = '<div class="p-1" style="font-size: 12px; min-width: 170px;">';
            details.forEach(d => {
                const scoreVal = d.nilai !== null ? Math.round(d.nilai) : 'Belum';
                let badgeStyle = 'background: #f1f5f9; color: #64748b;';
                if (d.nilai !== null) {
                    const kkm = d.kkm !== undefined ? d.kkm : 75;
                    if (d.nilai >= kkm) badgeStyle = 'background: #dcfce7; color: #166534; font-weight: 700;';
                    else badgeStyle = 'background: #fee2e2; color: #991b1b; font-weight: 700;';
                }
                html += `<div class="d-flex justify-content-between align-items-center gap-3 mb-1.5 pb-1 border-bottom border-light">
                            <span class="fw-medium text-dark text-truncate" style="max-width: 130px;" title="${d.nama_ujian}">${d.nama_ujian}</span>
                            <span class="badge rounded-pill px-2 py-1" style="${badgeStyle}">${scoreVal}</span>
                         </div>`;
            });
            html += '</div>';

            new bootstrap.Popover(el, {
                html: true,
                trigger: 'hover focus',
                title: `Rincian: ${mapelNama}`,
                content: html,
                placement: 'top'
            });
        } catch(e) {}
    });

    // 2. View Switcher Logic & Header Update
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const targetView = this.dataset.view;
            viewSections.forEach(sec => {
                if (sec.id === `view-${targetView}`) {
                    sec.style.display = 'block';
                } else {
                    sec.style.display = 'none';
                }
            });

            // Update judul seksi & ikon
            if (titleMap[targetView] && sectionTitleText && sectionTitleIcon) {
                sectionTitleText.textContent = titleMap[targetView].text;
                sectionTitleIcon.className = `fa-solid ${titleMap[targetView].icon} me-2`;
            }
        });
    });

    // 3. Instant Live Search & Status Filter
    function filterStudents() {
        const query = searchInput.value.toLowerCase().trim();
        const statusVal = statusSelect.value;

        // Filter Tabel Matriks
        const tableRows = document.querySelectorAll('.siswa-row-item');
        tableRows.forEach(row => {
            const nama = row.dataset.nama || '';
            const nis = row.dataset.nis || '';
            const status = row.dataset.status || '';

            const matchQuery = !query || nama.includes(query) || nis.includes(query);
            const matchStatus = (statusVal === 'semua') || (status === statusVal);

            if (matchQuery && matchStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Filter Kartu Siswa
        const studentCards = document.querySelectorAll('.student-card-wrapper');
        studentCards.forEach(card => {
            const nama = card.dataset.nama || '';
            const nis = card.dataset.nis || '';
            const status = card.dataset.status || '';

            const matchQuery = !query || nama.includes(query) || nis.includes(query);
            const matchStatus = (statusVal === 'semua') || (status === statusVal);

            if (matchQuery && matchStatus) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterStudents);
    }
    if (statusSelect) {
        statusSelect.addEventListener('change', filterStudents);
    }
});
</script>
@endsection
