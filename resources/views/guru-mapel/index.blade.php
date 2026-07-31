@extends('layouts.app')

@section('title', 'Guru Mata Pelajaran')

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
        /* overflow: hidden;  <-- dihapus, ini biang keroknya */
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.05);
        z-index: 10;
    }

    /* Wrapper khusus untuk clip lingkaran dekorasi, terpisah dari overflow header */
    .page-header-glow {
        position: absolute;
        inset: 0;
        border-radius: 24px;
        overflow: hidden;
        z-index: 0;
        pointer-events: none;
    }

    .page-header-glow::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        right: -50px;
        top: -80px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, rgba(14, 165, 233, 0) 70%);
    }

    /* .page-header::after yang lama boleh dihapus, sudah digantikan .page-header-glow::after */


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

    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06); }

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

    .filter-bar {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .filter-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 6px;
        display: block;
    }

    .form-control-custom {
        border-radius: 14px;
        height: 46px;
        border: 1px solid var(--border-color);
        padding-left: 16px;
        font-size: 14px;
        background-color: #ffffff;
    }

    .form-control-custom:focus {
        background-color: #fff;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
    }

    .btn-add {
        border-radius: 14px;
        padding: 12px 24px;
        font-weight: 600;
        white-space: nowrap;
    }

    .btn-add.btn-info { box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25); }

    .btn-action-trigger {
        border-radius: 14px;
        height: 46px;
        padding: 0 20px;
        font-weight: 600;
    }

    .active-filter-chip {
        background: rgba(14, 165, 233, 0.08);
        color: var(--accent-blue);
        border: 1px solid rgba(14, 165, 233, 0.2);
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .active-filter-chip a { color: inherit; text-decoration: none; opacity: .7; }
    .active-filter-chip a:hover { opacity: 1; }

    .table-responsive { border-radius: 16px; overflow: visible; }

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

    .avatar-guru {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }

    .mapel-badge {
        background: #f8fafc;
        color: var(--secondary-dark);
        border: 1px solid var(--border-color);
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Dropdown aksi (header maupun per baris/kartu) */
    .dropdown-action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background-color: #fff;
        color: var(--text-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .dropdown-action-btn:hover, .dropdown-action-btn:focus {
        background-color: #f8fafc;
        border-color: var(--border-color);
        color: var(--primary-dark);
    }
    .dropdown-menu-custom {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        border-radius: 14px;
        padding: 8px;
        min-width: 210px;
        z-index: 1050;
    }
    .dropdown-menu-custom .dropdown-item {
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 500;
        color: var(--secondary-dark);
        transition: all 0.2s;
    }
    .dropdown-menu-custom .dropdown-item:hover { background-color: #f8fafc; }
    .dropdown-menu-custom .dropdown-item.text-danger:hover {
        background-color: #fff1f2;
        color: #e11d48 !important;
    }
    .form-delete { display: inline; margin: 0; }

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
       KARTU PENUGASAN UNTUK TAMPILAN MOBILE
       ============================================ */
    .gm-card-mobile {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 14px;
        margin-bottom: 12px;
    }
    .gm-card-mobile .gm-card-name {
        font-size: 14.5px;
        line-height: 1.3;
    }
    .gm-card-mobile .gm-card-meta { font-size: 12px; }
    .gm-card-mobile hr { margin: 12px 0; opacity: 1; border-color: #f1f5f9; }
    .gm-card-empty-mobile {
        text-align: center;
        padding: 48px 16px;
        background: #fff;
        border: 1px dashed var(--border-color);
        border-radius: 18px;
    }

    /* ============================================
       RESPONSIVE: TABLET & MOBILE (<= 768px)
       ============================================ */
    @media (max-width: 768px) {
        .container-fluid.py-2 { padding-left: 12px; padding-right: 12px; }

        .page-header { padding: 22px 18px; border-radius: 20px; text-align: left; }
        .page-header-content.d-flex,
        .page-header .d-flex.justify-content-between {
            flex-direction: column;
            align-items: stretch !important;
            gap: 18px !important;
        }
        .page-header h3 { font-size: 19px; margin-bottom: 4px; }
        .page-header p.small { font-size: 12.5px; }
        .page-header .badge { font-size: 10px !important; padding: 6px 12px !important; }

        .header-actions { width: 100%; display: flex; gap: 8px; }
        .header-actions .dropdown { flex: 0 0 auto; }
        .header-actions .dropdown .btn-add {
            width: 48px; height: 48px; padding: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .header-actions .dropdown .btn-add i { margin: 0 !important; font-size: 15px; }
        .header-actions .dropdown .btn-add-label { display: none; }
        .header-actions a.btn-add { flex: 1; width: auto; justify-content: center; height: 48px; }

        .row.g-3.mb-4 { margin-bottom: 18px !important; row-gap: 12px !important; }
        .stat-card { flex-direction: column; align-items: flex-start; padding: 14px; border-radius: 16px; gap: 8px; }
        .stat-icon { width: 36px; height: 36px; font-size: 15px; border-radius: 11px; }
        .stat-card h4, .stat-card h6 { font-size: 15px; margin-top: 4px !important; }
        .stat-card small { font-size: 9.5px; letter-spacing: 0.3px !important; }

        .content-card { padding: 14px; border-radius: 20px; }
        .filter-bar { padding: 14px; border-radius: 16px; }
        .filter-bar .row.g-3 { row-gap: 10px; }
        .filter-bar .col-lg-2.col-md-12 .d-flex { width: 100%; }

        .dropdown-menu-custom .dropdown-item { padding: 10px 12px; font-size: 13.5px; }
        .dropdown-action-btn { width: 38px; height: 38px; }

        .pagination-container { justify-content: center !important; text-align: center; }
        .pagination { justify-content: center !important; flex-wrap: wrap; gap: 5px; }
        .pagination .page-item .page-link { padding: 8px 13px; font-size: 13px; border-radius: 10px !important; }

        #importModal .modal-dialog { margin: 14px; }
    }
</style>

<div class="container-fluid py-2">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div class="page-header-glow"></div>

        <div class="page-header-content d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    PENGGUNA
                </span>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
                    Guru Mata Pelajaran
                </h3>
                <p class="text-light opacity-75 mb-0 small">
                    Kelola penugasan guru, mata pelajaran, kelas, dan tahun ajaran.
                </p>
            </div>

            {{-- Toolbar aksi: 1 tombol utama (Tambah Penugasan) + 1 dropdown untuk aksi sekunder --}}
            <div class="d-flex gap-2 header-actions">
                <div class="dropdown">
                    <button class="btn btn-light border btn-add dropdown-toggle d-inline-flex align-items-center"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi Lainnya">
                        <i class="fa-solid fa-ellipsis-vertical me-2"></i>
                        <span class="btn-add-label">Aksi Lainnya</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('guru-mapel.template') }}">
                                <i class="fa-solid fa-download text-secondary" style="width: 16px;"></i>
                                Download Template
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                            href="{{ route('guru-mapel.export', request()->only(['search', 'jenjang', 'guru', 'tahun_ajaran'])) }}">
                                <i class="fa-solid fa-file-export text-success" style="width: 16px;"></i>
                                Export Excel
                            </a>
                        </li>
                        <li>
                            <button type="button"
                                    class="dropdown-item d-flex align-items-center gap-2 w-100 border-0 bg-transparent"
                                    data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="fa-solid fa-file-excel text-warning" style="width: 16px;"></i>
                                Import Excel
                            </button>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('guru-mapel.create') }}" class="btn btn-info text-white btn-add d-inline-flex align-items-center fw-semibold">
                    <i class="fa-solid fa-plus me-2"></i>
                    Tambah Penugasan
                </a>
            </div>
        </div>
    </div>

    {{-- Widget Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL PENUGASAN</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalGuruMapel }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL GURU</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalGuru }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-book"></i>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL MAPEL</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalMapel }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TAHUN AKTIF</small>
                    @if($tahunAktif)
                        <h6 class="fw-bold text-dark mb-0 mt-1">
                            {{ $tahunAktif->nama_tahun }}
                            <span class="d-block text-muted fw-normal" style="font-size: 12px;">{{ ucfirst($tahunAktif->semester) }}</span>
                        </h6>
                    @else
                        <h6 class="fw-bold text-dark mb-0 mt-1">-</h6>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm d-flex align-items-center p-3 mb-4">
        <i class="fa-solid fa-circle-check fs-5 me-2"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if(session('import_failures') && count(session('import_failures')) > 0)
    <div class="alert alert-warning rounded-4 border-0 shadow-sm p-3 mb-4">
        <div class="fw-bold mb-2"><i class="fa-solid fa-triangle-exclamation me-2"></i>Baris gagal divalidasi:</div>
        <ul class="mb-0 small">
            @foreach(session('import_failures') as $failure)
                <li>Baris {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('import_gagal') && count(session('import_gagal')) > 0)
    <div class="alert alert-secondary rounded-4 border-0 shadow-sm p-3 mb-4">
        <div class="fw-bold mb-2"><i class="fa-solid fa-circle-info me-2"></i>Catatan perlu dicek:</div>
        <ul class="mb-0 small">
            @foreach(session('import_gagal') as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Main Content Card --}}
    <div class="card content-card">
        <div class="card-body">

            {{-- Filter & Search Form --}}
            <div class="filter-bar">
                <form method="GET" action="{{ route('guru-mapel.index') }}">
                    <div class="row g-3 align-items-end">

                        <div class="col-lg-3 col-md-6">
                            <label class="filter-label">Cari</label>
                            <input
                                type="text"
                                name="search"
                                class="form-control form-control-custom"
                                placeholder="Nama guru atau mata pelajaran..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="filter-label">Guru</label>
                            <select name="guru" class="form-select form-control-custom">
                                <option value="">-- Semua Guru --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}" {{ request('guru') == $guru->id ? 'selected' : '' }}>
                                        {{ $guru->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if(Auth::user()->role == 'super_admin')
                            <div class="col-lg-2 col-md-6">
                                <label class="filter-label">Jenjang</label>
                                <select name="jenjang" class="form-select form-control-custom">
                                    <option value="">-- Semua --</option>
                                    @foreach($jenjangs as $jenjang)
                                        <option value="{{ $jenjang->id }}" {{ request('jenjang') == $jenjang->id ? 'selected' : '' }}>
                                            {{ $jenjang->nama_jenjang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-lg-{{ Auth::user()->role == 'super_admin' ? '2' : '3' }} col-md-6">
                            <label class="filter-label">Tahun Ajaran</label>
                            <select name="tahun_ajaran" class="form-select form-control-custom">
                                <option value="">-- Semua --</option>

                                @foreach($tahunAjarans as $tahun)
                                    <option
                                        value="{{ $tahun->id }}"
                                        {{ request('tahun_ajaran', optional($tahunAktif)->id) == $tahun->id ? 'selected' : '' }}>
                                        {{ $tahun->nama_tahun }} - {{ ucfirst($tahun->semester) }}
                                        @if($tahun->is_aktif) ⭐ @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-dark btn-action-trigger flex-grow-1">
                                    <i class="fa fa-search me-2"></i>
                                    Filter
                                </button>

                                @if(request()->filled('search') || request()->filled('jenjang') || request()->filled('guru') || request()->filled('tahun_ajaran'))
                                    <a href="{{ route('guru-mapel.index') }}" class="btn btn-light border btn-action-trigger" title="Reset filter">
                                        <i class="fa-solid fa-rotate"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                </form>

                {{-- Chip filter aktif --}}
                @if(request()->filled('search') || request()->filled('jenjang') || request()->filled('guru') || request()->filled('tahun_ajaran'))
                <div class="d-flex flex-wrap gap-2 mt-3">

                    @if(request()->filled('search'))
                        <span class="active-filter-chip">
                            Cari: "{{ request('search') }}"
                            <a href="{{ route('guru-mapel.index', request()->except('search')) }}"><i class="fa-solid fa-xmark"></i></a>
                        </span>
                    @endif

                    @if(request()->filled('guru'))
                        <span class="active-filter-chip">
                            Guru: {{ optional($gurus->firstWhere('id', request('guru')))->nama }}
                            <a href="{{ route('guru-mapel.index', request()->except('guru')) }}"><i class="fa-solid fa-xmark"></i></a>
                        </span>
                    @endif

                    @if(request()->filled('jenjang'))
                        <span class="active-filter-chip">
                            Jenjang: {{ optional($jenjangs->firstWhere('id', request('jenjang')))->nama_jenjang }}
                            <a href="{{ route('guru-mapel.index', request()->except('jenjang')) }}"><i class="fa-solid fa-xmark"></i></a>
                        </span>
                    @endif

                    @if(request()->filled('tahun_ajaran'))
                        <span class="active-filter-chip">
                            Tahun: {{ optional($tahunAjarans->firstWhere('id', request('tahun_ajaran')))->nama_tahun }}
                            <a href="{{ route('guru-mapel.index', request()->except('tahun_ajaran')) }}"><i class="fa-solid fa-xmark"></i></a>
                        </span>
                    @endif

                </div>
                @endif
            </div>

            {{-- Table (Desktop & Tablet ke atas) --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Guru</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th width="80" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guruMapels as $row)
                        <tr>
                            <td>
                                <span class="text-secondary fw-semibold">
                                    {{ $guruMapels->firstItem() + $loop->index }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-guru bg-primary bg-opacity-10 text-primary">
                                        {{ strtoupper(substr($row->guru->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ $row->guru->nama }}</div>
                                        <small class="text-muted">{{ optional($row->guru->jenjang)->nama_jenjang ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($row->items as $assignment)
                                        <span class="mapel-badge">
                                            <i class="fa-solid fa-book"></i>
                                            {{ $assignment->mataPelajaran->nama_mapel }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $row->total_kelas }} Kelas</div>
                                <small class="text-muted">{{ $row->total_mapel }} Mata Pelajaran</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ optional($row->tahunAjaran)->nama_tahun }}</div>
                                <small class="text-muted">{{ ucfirst(optional($row->tahunAjaran)->semester) }}</small>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="dropdown-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('guru-mapel.show', $row->guru->id) }}">
                                                <i class="fa-solid fa-eye text-info" style="width: 16px;"></i>
                                                Lihat Detail
                                            </a>
                                        </li>
                                        @if($row->items->first())
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('guru-mapel.edit', $row->items->first()->id) }}">
                                                <i class="fa-solid fa-pen text-primary" style="width: 16px;"></i>
                                                Edit Penugasan
                                            </a>
                                        </li>
                                        @endif
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('guru-mapel.destroy', $row->guru->id) }}" method="POST" class="form-delete w-100">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 w-100 border-0 bg-transparent">
                                                    <i class="fa-solid fa-trash text-danger" style="width: 16px;"></i>
                                                    Hapus Semua Penugasan
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-folder-open fa-3x text-muted mb-3 opacity-50"></i>
                                    <h6 class="fw-bold text-secondary">Belum ada penugasan guru mapel</h6>
                                    <small class="text-muted">Silakan sesuaikan filter Anda atau tambahkan penugasan baru.</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Kartu Penugasan (Mobile) --}}
            <div class="d-md-none">
                @forelse($guruMapels as $row)
                <div class="gm-card-mobile">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-2" style="min-width: 0;">
                            <div class="avatar-guru bg-primary bg-opacity-10 text-primary">
                                {{ strtoupper(substr($row->guru->nama, 0, 1)) }}
                            </div>
                            <div style="min-width: 0;">
                                <div class="fw-bold text-dark gm-card-name">{{ $row->guru->nama }}</div>
                                <div class="text-muted gm-card-meta">
                                    {{ optional($row->guru->jenjang)->nama_jenjang ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="dropdown flex-shrink-0">
                            <button class="dropdown-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('guru-mapel.show', $row->guru->id) }}">
                                        <i class="fa-solid fa-eye text-info" style="width: 16px;"></i>
                                        Lihat Detail
                                    </a>
                                </li>
                                @if($row->items->first())
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('guru-mapel.edit', $row->items->first()->id) }}">
                                        <i class="fa-solid fa-pen text-primary" style="width: 16px;"></i>
                                        Edit Penugasan
                                    </a>
                                </li>
                                @endif
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ route('guru-mapel.destroy', $row->guru->id) }}" method="POST" class="form-delete w-100">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 w-100 border-0 bg-transparent">
                                            <i class="fa-solid fa-trash text-danger" style="width: 16px;"></i>
                                            Hapus Semua Penugasan
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-2">
                        <div class="text-muted gm-card-meta mb-1 fw-semibold">MATA PELAJARAN</div>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($row->items as $assignment)
                                <span class="mapel-badge">
                                    <i class="fa-solid fa-book"></i>
                                    {{ $assignment->mataPelajaran->nama_mapel }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-bold text-dark gm-card-meta">{{ $row->total_kelas }} Kelas &middot; {{ $row->total_mapel }} Mapel</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-semibold text-dark gm-card-meta">{{ optional($row->tahunAjaran)->nama_tahun }}</div>
                            <div class="text-muted" style="font-size: 11px;">{{ ucfirst(optional($row->tahunAjaran)->semester) }}</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="gm-card-empty-mobile">
                    <i class="fa-solid fa-folder-open fa-2x text-muted mb-3 opacity-50"></i>
                    <h6 class="fw-bold text-secondary mb-1">Belum ada penugasan guru mapel</h6>
                    <small class="text-muted">Silakan sesuaikan filter Anda atau tambahkan penugasan baru.</small>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($guruMapels->hasPages())
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pagination-container">
                <small class="text-muted mb-2 mb-md-0">
                    Menampilkan <strong>{{ $guruMapels->firstItem() }}</strong> - <strong>{{ $guruMapels->lastItem() }}</strong> dari <strong>{{ $guruMapels->total() }}</strong> guru
                </small>
                {{ $guruMapels->links('vendor.pagination.bootstrap-4') }}
            </div>
            @endif

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.form-delete').forEach(function(form){
    form.addEventListener('submit', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Hapus Penugasan?',
            text: 'Data guru mata pelajaran dan kelas terkait akan dihapus.',
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

<div class="modal fade" id="importModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <form action="{{ route('guru-mapel.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="mb-0">Import Guru Mapel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="text-muted small">
                        Format kolom: <code>nip_guru, nama_guru, jenjang, mata_pelajaran, kelas, tahun_ajaran, semester</code>.
                        Kolom <code>kelas</code> boleh diisi lebih dari satu, dipisah koma, contoh:
                        <em>"Kelas VII - VII A, Kelas VII - VII B"</em>.
                        Unduh <a href="{{ route('guru-mapel.template') }}">Template Excel</a> untuk contoh formatnya,
                        atau klik <strong>Export Excel</strong> untuk mengunduh data yang sudah ada (lalu edit dan upload ulang).
                    </p>

                    <label class="form-label">File Excel</label>
                    <input type="file" name="file" class="form-control form-control-custom" accept=".xlsx,.xls,.csv" required>
                    <small class="text-muted">Format: xlsx, xls, atau csv. Maksimal 5MB.</small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection