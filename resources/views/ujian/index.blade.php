@extends('layouts.app')

@section('title', 'Jadwal Ujian')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        /* -- Netral & struktur (Slate Navy Design System) -- */
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;

        /* -- Aksen utama (Sky Blue & Slate Indigo) -- */
        --accent-blue: #0ea5e9;
        --accent-sky: #38bdf8;
        --accent-indigo: #0284c7;

        /* -- Warna semantik khusus PUBLISH NILAI --
           emerald = nilai sudah tayang ke siswa,
           amber   = belum dipublish */
        --published: #059669;
        --published-soft: #ecfdf5;
        --published-border: rgba(5, 150, 105, 0.22);

        --pending: #d97706;
        --pending-soft: #fffbeb;
        --pending-border: rgba(217, 119, 6, 0.25);
    }

    .display-font { font-family: 'Plus Jakarta Sans', var(--bs-body-font-family), sans-serif; }

    .page-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        border-radius: 24px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .page-header::after {
        content: '';
        position: absolute;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        right: -60px;
        top: -100px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.18) 0%, rgba(56, 189, 248, 0) 70%);
        pointer-events: none;
    }

    .page-header::before {
        content: '';
        position: absolute;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        left: -60px;
        bottom: -120px;
        background: radial-gradient(circle, rgba(2, 132, 199, 0.22) 0%, rgba(2, 132, 199, 0) 70%);
        pointer-events: none;
    }

    .page-header h3 { font-family: 'Plus Jakarta Sans', var(--bs-body-font-family), sans-serif; }

    .page-header-content { position: relative; z-index: 1; }

    .stat-card {
        background: var(--surface-white);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.02);
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
        transition: transform .2s, box-shadow .2s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
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
        padding: 12px;
    }

    .form-control-custom {
        border-radius: 14px;
        height: 46px;
        border: 1px solid var(--border-color);
        padding-left: 16px;
        font-size: 14px;
        background-color: #f8fafc;
    }

    .form-control-custom:focus {
        background-color: #fff;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
    }

    .btn-add {
        border-radius: 14px;
        padding: 12px 24px;
        font-weight: 600;
        box-shadow: 0 8px 20px rgba(2, 132, 199, 0.28);
        white-space: nowrap;
    }

    .page-header .btn-info {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        border: none;
        color: #ffffff;
    }
    .page-header .btn-info:hover {
        background: linear-gradient(135deg, #0369a1, #075985);
        color: #ffffff;
        box-shadow: 0 10px 24px rgba(2, 132, 199, 0.38);
    }

    .btn-action-trigger {
        border-radius: 14px;
        height: 46px;
        padding: 0 20px;
        font-weight: 600;
    }

    .btn-filter-submit {
        background-color: var(--primary-dark);
        color: #ffffff;
        border: none;
        transition: background-color 0.2s ease;
    }

    .btn-filter-submit:hover {
        background-color: var(--secondary-dark);
        color: #ffffff;
    }

    .table thead th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        background-color: #f8fafc;
        padding: 16px;
        border-bottom: 1px solid var(--border-color);
        font-weight: 700;
    }

    .table tbody td {
        padding: 18px 16px;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 14px;
    }

    .table tbody tr:hover { background-color: #f8fafc; }

    .status-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .status-akan-datang { background: #eff6ff; color: #2563eb; border: 1px solid rgba(37, 99, 235, 0.15); }
    .status-berlangsung { background: #ecfdf5; color: #059669; border: 1px solid rgba(5, 150, 105, 0.15); }
    .status-selesai { background: #f8fafc; color: #64748b; border: 1px solid var(--border-color); }

    /* ============================================
       BANNER "PUBLISH NILAI MASSAL"
       Dipindah keluar dari header gelap (kontras
       rendah) menjadi kartu terang tersendiri yang
       lebih menonjol karena ini fitur utama halaman.
       ============================================ */
    .publish-banner {
        background: linear-gradient(120deg, var(--published-soft) 0%, #f0fdf4 55%, var(--surface-white) 100%);
        border: 1px solid var(--published-border);
        border-radius: 22px;
        padding: 22px 26px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .publish-banner-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: var(--published);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        box-shadow: 0 8px 18px rgba(5, 150, 105, 0.28);
    }

    .publish-banner-text h6 { font-family: 'Plus Jakarta Sans', var(--bs-body-font-family), sans-serif; }

    .btn-publish-massal {
        background: var(--published);
        border: none;
        color: #fff;
        border-radius: 14px;
        padding: 12px 22px;
        font-weight: 700;
        white-space: nowrap;
        box-shadow: 0 8px 18px rgba(5, 150, 105, 0.22);
        transition: transform .2s, box-shadow .2s, background .2s;
    }

    .btn-publish-massal:hover {
        background: #047857;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(5, 150, 105, 0.3);
    }

    .publish-count-chip {
        background: #fff;
        border: 1px solid var(--pending-border);
        color: var(--pending);
        font-weight: 700;
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* ============================================
       PILL STATUS "PUBLISH NILAI" per baris
       + tombol aksi cepat (tanpa buka dropdown)
       ============================================ */
    .nilai-cell { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

    .nilai-pill {
        font-size: 11.5px;
        font-weight: 700;
        padding: 6px 13px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .nilai-pill.is-published {
        background: var(--published);
        color: #fff;
    }

    .nilai-pill.is-pending {
        background: var(--pending-soft);
        color: var(--pending);
        border: 1px solid var(--pending-border);
    }

    .nilai-quick-toggle {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: #fff;
        color: var(--text-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12.5px;
        flex-shrink: 0;
        transition: all .15s ease;
    }

    .nilai-quick-toggle:hover { transform: translateY(-2px); }

    .nilai-quick-toggle.toggle-publish {
        color: var(--published);
        border-color: var(--published-border);
    }
    .nilai-quick-toggle.toggle-publish:hover { background: var(--published-soft); }

    .nilai-quick-toggle.toggle-unpublish {
        color: var(--pending);
        border-color: var(--pending-border);
    }
    .nilai-quick-toggle.toggle-unpublish:hover { background: var(--pending-soft); }

    .nilai-quick-toggle:disabled {
        opacity: .4;
        pointer-events: none;
    }

    .token-box {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        letter-spacing: 2px;
        background: #f8fafc;
        border: 1px dashed var(--border-color);
        border-radius: 8px;
        padding: 4px 10px;
        display: inline-block;
        font-size: 13px;
        white-space: nowrap;
    }

    .action-icon-btn {
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .action-icon-btn:hover { transform: translateY(-2px); }

    .btn-icon-more {
        background: #f1f5f9;
        color: var(--text-muted);
    }
    .btn-icon-more:hover,
    .btn-icon-more.is-active {
        background: var(--secondary-dark);
        color: #fff;
    }

    .pagination { gap: 6px; margin-bottom: 0; }

    .pagination .page-item .page-link {
        border-radius: 12px !important;
        border: 1px solid var(--border-color);
        color: var(--secondary-dark);
        padding: 10px 16px;
        font-weight: 500;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--secondary-dark) !important;
        border-color: var(--secondary-dark) !important;
        color: white !important;
    }

    /* ============================================
       DROPDOWN AKSI PER BARIS (titik tiga)
       Posisi "fixed" via JS supaya tidak kepotong
       oleh area tabel yang bisa digeser horizontal.
       ============================================ */
    .dropdown-action-wrap {
        position: relative;
        display: inline-block;
    }

    .dropdown-action-menu {
        display: none;
        position: fixed;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        padding: 8px;
        min-width: 210px;
        z-index: 3000;
    }

    .dropdown-action-menu.show { display: block; }

    .dropdown-action-item {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 9px 12px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: var(--secondary-dark);
        text-decoration: none;
        border: none;
        background: transparent;
        text-align: left;
        transition: background-color 0.15s;
    }

    .dropdown-action-item i { width: 16px; text-align: center; flex-shrink: 0; }

    .dropdown-action-item:hover { background-color: #f8fafc; color: var(--secondary-dark); }

    .dropdown-action-item.text-danger { color: #e11d48; }
    .dropdown-action-item.text-danger:hover { background-color: #fff1f2; color: #e11d48; }

    .dropdown-action-item.is-disabled {
        opacity: .45;
        pointer-events: none;
    }

    .dropdown-action-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 6px 4px;
    }

    /* ============================================
       TABEL: tetap berbentuk tabel di mobile,
       digeser horizontal, dengan kolom No & Aksi
       yang menempel (sticky) di kiri/kanan.
       ============================================ */
    .table-scroll-wrap {
        position: relative;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .table-scroll-wrap .table-responsive {
        border-radius: 0;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        margin-bottom: 0;
    }

    .table-scroll-wrap .table { margin-bottom: 0; }

    .table-scroll-wrap::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 28px;
        background: linear-gradient(to right, transparent, rgba(15, 23, 42, 0.06));
        pointer-events: none;
        border-radius: 0 16px 16px 0;
    }

    /* ============================================
       RESPONSIVE: TABLET & MOBILE (<= 768px)
       ============================================ */
    @media (max-width: 768px) {
        .container-fluid.py-2 { padding-left: 12px; padding-right: 12px; }

        .page-header { padding: 22px 18px; border-radius: 20px; }
        .page-header-content.d-flex.justify-content-between {
            flex-direction: column;
            align-items: stretch !important;
            gap: 16px !important;
        }
        .page-header h3 { font-size: 19px; margin-bottom: 4px; }
        .page-header p.small { font-size: 12.5px; }
        .btn-add { width: 100%; justify-content: center; height: 46px; }

        .row.g-3.mb-4 { row-gap: 12px !important; }
        .stat-card { padding: 16px; border-radius: 16px; gap: 12px; }
        .stat-icon { width: 40px; height: 40px; font-size: 17px; border-radius: 12px; }
        .stat-card h4 { font-size: 17px; margin-top: 2px !important; }
        .stat-card small { font-size: 9.5px; letter-spacing: 0.3px !important; }

        .content-card { padding: 4px; border-radius: 18px; }
        .content-card .card-body { padding: 14px !important; }

        /* Filter form */
        .row.g-3.mb-4.align-items-center { row-gap: 10px !important; }
        .col-lg-3, .col-lg-2, .col-lg-auto { width: 100%; }
        .col-lg-auto .d-flex { width: 100%; }
        .col-lg-auto .d-flex .btn-action-trigger:first-child { flex: 1; }

        /* Tabel: sesuaikan ukuran & sticky column */
        .table-scroll-wrap .table {
            min-width: 780px;
        }

        .table-scroll-wrap .table thead th {
            white-space: nowrap;
            padding: 12px 14px;
            font-size: 10.5px;
        }

        .table-scroll-wrap .table tbody td {
            padding: 14px;
            font-size: 13.5px;
        }

        .table-scroll-wrap .table tbody td .fs-6 { font-size: 13.5px !important; }
        .table-scroll-wrap .table tbody td small { font-size: 11px; }

        .table-scroll-wrap .table th:first-child,
        .table-scroll-wrap .table td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
            background: #fff;
            box-shadow: 2px 0 6px rgba(15, 23, 42, 0.04);
        }
        .table-scroll-wrap .table thead th:first-child { background: #f8fafc; }

        .table-scroll-wrap .table th:last-child,
        .table-scroll-wrap .table td:last-child {
            position: sticky;
            right: 0;
            z-index: 2;
            background: #fff;
            box-shadow: -2px 0 6px rgba(15, 23, 42, 0.04);
        }
        .table-scroll-wrap .table thead th:last-child { background: #f8fafc; }

        .table-scroll-wrap .table tbody tr:hover td:first-child,
        .table-scroll-wrap .table tbody tr:hover td:last-child {
            background: #f8fafc;
        }

        .action-icon-btn { width: 36px; height: 36px; }

        .pagination { justify-content: center !important; flex-wrap: wrap; }

        .publish-banner { padding: 18px; border-radius: 18px; flex-direction: column; align-items: stretch; text-align: left; }
        .btn-publish-massal { width: 100%; justify-content: center; display: flex; }
    }
</style>

<div class="container-fluid py-2">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div class="page-header-content d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge px-3 py-2 rounded-pill mb-2 fw-semibold" style="background: rgba(255,255,255,0.12); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.25); font-size: 11px; letter-spacing: 0.5px;">
                    UJIAN
                </span>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
                    Jadwal Ujian
                </h3>
                <p class="text-light opacity-75 mb-0 small">
                    Kelola jadwal, token, dan pengaturan ujian.
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('ujian.create') }}" class="btn btn-info text-white btn-add d-inline-flex align-items-center">
                    <i class="fa-solid fa-plus me-2"></i>
                    Buat Jadwal Ujian
                </a>
            </div>
        </div>
    </div>

    {{-- Widget Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(2, 132, 199, 0.1); color: #0284c7;">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <small class="text-muted d-block uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL JADWAL</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalUjian }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(5, 150, 105, 0.1); color: #059669;">
                    <i class="fa-solid fa-circle-play"></i>
                </div>
                <div>
                    <small class="text-muted d-block uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">SEDANG BERLANGSUNG</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalBerlangsung }}</h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(217, 119, 6, 0.1); color: #d97706;">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <small class="text-muted d-block uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">AKAN DATANG</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalAkanDatang }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Banner aksi utama: Publish Nilai Massal --}}
    <div class="publish-banner mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="publish-banner-icon">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div class="publish-banner-text">
                <h6 class="fw-bold text-dark mb-1">Publish Nilai Massal</h6>
                <small class="text-muted">
                    Umumkan nilai akhir untuk semua ujian yang sudah selesai, sesuai filter jenis ujian &amp; tahun ajaran.
                </small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            @isset($totalBelumPublish)
                <span class="publish-count-chip">
                    <i class="fa-solid fa-eye-slash"></i>
                    {{ $totalBelumPublish }} belum dipublish
                </span>
            @endisset
            <button type="button" class="btn btn-publish-massal d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#batchPublishModal">
                <i class="fa-solid fa-paper-plane me-2"></i>
                Publish Massal
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm d-flex align-items-center p-3 mb-4">
        <i class="fa-solid fa-circle-check fs-5 me-2"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm d-flex align-items-center p-3 mb-4">
        <i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    {{-- Main Content Card --}}
    <div class="card content-card">
        <div class="card-body">

            {{-- Filter & Search Form --}}
            <form method="GET" action="{{ route('ujian.index') }}">
                <div class="row g-3 mb-4 align-items-center">

                    <div class="col-lg-3">
                        <input
                            type="text"
                            name="search"
                            class="form-control form-control-custom"
                            placeholder="Cari nama ujian..."
                            value="{{ request('search') }}">
                    </div>

                    @if(Auth::user()->role != 'admin_jenjang')
                        <div class="col-lg-2">
                            <select name="jenjang" class="form-select form-control-custom">
                                <option value="">-- Semua Jenjang --</option>
                                @foreach($jenjangs as $jenjang)
                                    <option value="{{ $jenjang->id }}" {{ request('jenjang') == $jenjang->id ? 'selected' : '' }}>
                                        {{ $jenjang->nama_jenjang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-lg-3">
                        <select name="jenis_ujian" class="form-select form-control-custom">
                            <option value="">-- Semua Jenis Ujian --</option>
                            @foreach($jenisUjians as $jenis)
                                <option value="{{ $jenis->id }}" {{ request('jenis_ujian') == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <select name="tahun_ajaran" class="form-select form-control-custom">
                            <option value="">-- Tahun Ajaran --</option>

                            @foreach($tahunAjarans as $tahun)
                                <option value="{{ $tahun->id }}"
                                    {{
                                        (request()->filled('tahun_ajaran')
                                            ? request('tahun_ajaran') == $tahun->id
                                            : $tahun->is_aktif)
                                        ? 'selected'
                                        : ''
                                    }}>
                                    {{ $tahun->nama_tahun }} - {{ ucfirst($tahun->semester) }}
                                    @if($tahun->is_aktif)
                                        ⭐
                                    @endif
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-lg-auto">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-filter-submit btn-action-trigger">
                                <i class="fa fa-search me-2"></i>
                                Filter
                            </button>

                            @if(request()->filled('search') || request()->filled('jenjang') || request()->filled('jenis_ujian') || request()->filled('tahun_ajaran'))
                                <a href="{{ route('ujian.index') }}" class="btn btn-light border btn-action-trigger">
                                    <i class="fa-solid fa-rotate"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </form>

            {{-- Table --}}
            <div class="table-scroll-wrap">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Ujian</th>
                            <th>Waktu Pelaksanaan</th>
                            <th>Token</th>
                            <th width="130">Status Ujian</th>
                            <th width="140">Publish Nilai</th>
                            <th width="70" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ujians as $item)
                        <tr>
                            <td>
                                <span class="text-secondary fw-semibold">
                                    {{ $loop->iteration + ($ujians->firstItem() - 1) }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $item->nama_ujian }}</div>
                                <small class="text-muted">
                                    {{ optional($item->jenisUjian)->nama ?? '-' }}
                                    &middot; {{ optional(optional($item->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}
                                    &middot; {{ $item->kelas->count() }} kelas
                                </small>
                            </td>
                            <td>
                                <div>{{ $item->waktu_mulai->translatedFormat('d M Y, H:i') }}</div>
                                <small class="text-muted">s/d {{ $item->waktu_selesai->translatedFormat('d M Y, H:i') }}</small>
                            </td>
                            <td>
                                <span class="token-box">{{ $item->token ?? '-' }}</span>
                            </td>
                            <td>
                                @php $status = $item->status_waktu; @endphp
                                @if($status == 'akan_datang')
                                    <span class="status-badge status-akan-datang"><i class="fa-solid fa-clock"></i> Akan Datang</span>
                                @elseif($status == 'berlangsung')
                                    <span class="status-badge status-berlangsung"><i class="fa-solid fa-circle-play"></i> Berlangsung</span>
                                @else
                                    <span class="status-badge status-selesai"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                                @endif
                            </td>
                            <td>
                                <div class="nilai-cell">
                                    @if($item->publish_nilai)
                                        <span class="nilai-pill is-published">
                                            <i class="fa-solid fa-circle-check"></i> Published
                                        </span>
                                        <form action="{{ route('ujian.unpublish-nilai', $item->id) }}" method="POST" class="m-0 form-unpublish">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="nilai-quick-toggle toggle-unpublish" title="Tarik (unpublish) nilai">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="nilai-pill is-pending">
                                            <i class="fa-solid fa-eye-slash"></i> Belum Dipublish
                                        </span>
                                        <form action="{{ route('ujian.publish-nilai', $item->id) }}" method="POST" class="m-0 form-publish">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="nilai-quick-toggle toggle-publish"
                                                    title="{{ now()->lt($item->waktu_selesai) ? 'Ujian belum selesai' : 'Publish nilai ke siswa' }}"
                                                    {{ now()->lt($item->waktu_selesai) ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-bullhorn"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="dropdown-action-wrap">
                                    <button type="button" class="action-icon-btn btn-icon-more dropdown-action-toggle" title="Menu Aksi">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <div class="dropdown-action-menu">
                                        <a href="{{ route('ujian.show', $item->id) }}" class="dropdown-action-item">
                                            <i class="fa-solid fa-eye" style="color: var(--accent-indigo);"></i>
                                            Kontrol Token &amp; Detail
                                        </a>

                                        <a href="{{ route('ujian.edit', $item->id) }}"
                                           class="dropdown-action-item {{ $item->token_aktif ? 'is-disabled' : '' }}">
                                            <i class="fa-solid fa-pen" style="color: var(--accent-blue);"></i>
                                            Edit Jadwal
                                        </a>

                                        <div class="dropdown-action-divider"></div>

                                        <form action="{{ route('ujian.destroy', $item->id) }}" method="POST" class="form-delete w-100 m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-action-item text-danger">
                                                <i class="fa-solid fa-trash"></i>
                                                Hapus Jadwal
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-calendar-check fa-3x text-muted mb-3 opacity-50"></i>
                                    <h6 class="fw-bold text-secondary">Belum ada jadwal ujian</h6>
                                    <small class="text-muted">Silakan buat jadwal ujian baru.</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-4 pagination-container">
                {{ $ujians->links('vendor.pagination.bootstrap-4') }}
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.form-delete').forEach(function(form){
    form.addEventListener('submit', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Hapus Jadwal Ujian?',
            text: 'Jadwal beserta token dan penugasan kelasnya akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

document.querySelectorAll('.form-publish').forEach(function(form){
    form.addEventListener('submit', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Publish nilai ujian ini?',
            text: 'Siswa akan langsung bisa melihat nilai akhirnya setelah dipublish.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Publish',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

document.querySelectorAll('.form-unpublish').forEach(function(form){
    form.addEventListener('submit', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Tarik (unpublish) nilai ini?',
            text: 'Nilai akan disembunyikan kembali dari siswa sampai dipublish ulang.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d97706',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Tarik',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: "{{ session('success') }}",
    confirmButtonColor: '#0f172a'
});
</script>
@endif

<script>
/* =========================================================
   Dropdown Aksi (titik tiga) per baris tabel.
   Menggunakan position:fixed yang dihitung manual via JS
   supaya menu tidak kepotong oleh area tabel yang bisa
   digeser horizontal (overflow-x: auto pada wrapper tabel).
========================================================= */
(function () {
    function closeAllActionMenus(except) {
        document.querySelectorAll('.dropdown-action-menu.show').forEach(function (menu) {
            if (menu !== except) {
                menu.classList.remove('show');
                const toggle = menu.closest('.dropdown-action-wrap').querySelector('.dropdown-action-toggle');
                if (toggle) toggle.classList.remove('is-active');
            }
        });
    }

    function positionMenu(btn, menu) {
        const rect = btn.getBoundingClientRect();
        const menuWidth = menu.offsetWidth || 210;

        let left = rect.right - menuWidth;
        if (left < 8) left = 8;

        let top = rect.bottom + 6;
        const menuHeight = menu.offsetHeight || 160;
        if (top + menuHeight > window.innerHeight - 8) {
            top = rect.top - menuHeight - 6;
        }

        menu.style.top = top + 'px';
        menu.style.left = left + 'px';
    }

    document.querySelectorAll('.dropdown-action-toggle').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();

            const wrap = btn.closest('.dropdown-action-wrap');
            const menu = wrap.querySelector('.dropdown-action-menu');
            const isOpen = menu.classList.contains('show');

            closeAllActionMenus();

            if (!isOpen) {
                menu.classList.add('show');
                positionMenu(btn, menu);
                btn.classList.add('is-active');
            }
        });
    });

    document.addEventListener('click', function () {
        closeAllActionMenus();
    });

    document.querySelectorAll('.table-responsive').forEach(function (el) {
        el.addEventListener('scroll', function () {
            closeAllActionMenus();
        }, { passive: true });
    });

    window.addEventListener('scroll', function () {
        closeAllActionMenus();
    }, true);

    window.addEventListener('resize', function () {
        closeAllActionMenus();
    });

    document.querySelectorAll('.dropdown-action-menu').forEach(function (menu) {
        menu.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });
})();
</script>

<!-- Modal Batch Publish Nilai -->
<div class="modal fade" id="batchPublishModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <form action="{{ route('ujian.batch-publish-nilai') }}" method="POST">
                @csrf
                <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark)); border-bottom: none;">
                    <h5 class="modal-title fw-bold text-white fs-6">
                        <i class="fa-solid fa-bullhorn me-2"></i> Publish Nilai Massal
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex align-items-start gap-2 border-0 rounded-3 mb-4 small p-3" style="background: var(--published-soft); color: var(--published);">
                        <i class="fa-solid fa-circle-info mt-1"></i>
                        <div>Fitur ini akan mempublikasikan nilai akhir seluruh ujian yang sudah selesai dilaksanakan sesuai filter jenis ujian dan tahun ajaran yang Anda pilih.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Jenis Ujian</label>
                        <select name="jenis_ujian_id" class="form-select rounded-3">
                            <option value="">-- Semua Jenis Ujian --</option>
                            @foreach($jenisUjians as $ju)
                                <option value="{{ $ju->id }}">{{ $ju->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" class="form-select rounded-3">
                            <option value="">-- Semua Tahun Ajaran --</option>
                            @foreach($tahunAjarans as $ta)
                                <option value="{{ $ta->id }}">{{ $ta->nama_tahun }} {{ $ta->is_aktif ? '(Aktif)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3 border-top-0">
                    <button type="button" class="btn btn-light border rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white rounded-3 px-4" style="background: var(--published);">
                        <i class="fa-solid fa-paper-plane me-1"></i> Publish Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection