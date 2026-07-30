@extends('layouts.app')

@section('title', 'Data Siswa')

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
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.05);
        z-index: 10;
    }

    .page-header-bg {
        position: absolute;
        inset: 0;
        border-radius: 24px;
        overflow: hidden;
        pointer-events: none;
        z-index: 0;
    }

    .page-header-bg::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        right: -50px;
        top: -80px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, rgba(14, 165, 233, 0) 70%);
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

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
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
    }

    .btn-add {
        border-radius: 14px;
        padding: 12px 24px;
        font-weight: 600;
        white-space: nowrap;
    }

    .btn-add.btn-info {
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);
    }

    .btn-action-trigger {
        border-radius: 14px;
        height: 46px;
        padding: 0 20px;
        font-weight: 600;
    }

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

    /* Dropdown action (dipakai baik untuk toolbar header maupun aksi per baris/kartu) */
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
        min-width: 200px;
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
    .dropdown-menu-custom .dropdown-item:hover {
        background-color: #f8fafc;
    }
    .dropdown-menu-custom .dropdown-item.text-danger:hover {
        background-color: #fff1f2;
        color: #e11d48 !important;
    }

    /* ============================================
       KARTU SISWA UNTUK TAMPILAN MOBILE
       ============================================ */
    .siswa-card-mobile {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 14px;
        margin-bottom: 12px;
        transition: box-shadow 0.15s ease;
    }
    .siswa-card-mobile:active {
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
    }
    .siswa-card-mobile .siswa-avatar-wrap {
        width: 44px;
        height: 44px;
        flex-shrink: 0;
    }
    .siswa-card-mobile .siswa-avatar-wrap img,
    .siswa-card-mobile .siswa-avatar-wrap .siswa-avatar-fallback {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        object-fit: cover;
    }
    .siswa-card-mobile .siswa-avatar-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
    }
    .siswa-card-mobile .siswa-card-name {
        font-size: 14.5px;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }
    .siswa-card-mobile .siswa-card-meta {
        font-size: 12px;
    }
    .siswa-card-mobile hr {
        margin: 12px 0;
        opacity: 1;
        border-color: #f1f5f9;
    }
    .siswa-card-empty-mobile {
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

        /* Header */
        .page-header { padding: 22px 18px; border-radius: 20px; text-align: left; }
        .page-header-content.d-flex { flex-direction: column; align-items: stretch !important; gap: 18px !important; }
        .page-header h3 { font-size: 19px; margin-bottom: 4px; }
        .page-header p.small { font-size: 12.5px; }
        .page-header .badge { font-size: 10px !important; padding: 6px 12px !important; }

        .header-actions { width: 100%; display: flex; gap: 8px; }
        .header-actions .dropdown { flex: 0 0 auto; }
        .header-actions .dropdown .btn-add {
            width: 48px;
            height: 48px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header-actions .dropdown .btn-add i { margin: 0 !important; font-size: 15px; }
        .header-actions .dropdown .btn-add-label { display: none; }
        .header-actions a.btn-add {
            flex: 1;
            width: auto;
            justify-content: center;
            height: 48px;
        }

        /* Kartu Statistik: grid 2 kolom, ringkas */
        .row.g-3.mb-4 { margin-bottom: 18px !important; row-gap: 12px !important; }
        .row.g-3.mb-4 > [class*="col-"] { flex: 0 0 50%; max-width: 50%; }
        .stat-card {
            flex-direction: column;
            align-items: flex-start;
            padding: 14px;
            border-radius: 16px;
            gap: 8px;
        }
        .stat-icon { width: 36px; height: 36px; font-size: 15px; border-radius: 11px; }
        .stat-card h4 { font-size: 16px; margin-top: 4px !important; }
        .stat-card small { font-size: 9.5px; letter-spacing: 0.3px !important; }

        /* Kartu konten utama */
        .content-card { padding: 14px; border-radius: 20px; }

        /* Form filter & pencarian */
        form .row.g-3.mb-3 { row-gap: 10px; }
        .form-control-custom, .btn-action-trigger { width: 100%; }
        .col-lg-auto .d-flex.gap-2 { width: 100%; }
        .col-lg-auto .d-flex.gap-2 .btn-action-trigger { height: 46px; }
        .col-lg-auto .d-flex.gap-2 button[type="submit"] { flex: 1; }
        .col-lg-auto .d-flex.gap-2 a.btn-action-trigger { flex: 0 0 46px; width: 46px; padding: 0; display: flex; align-items: center; justify-content: center; }

        /* Alert & notifikasi */
        .alert.rounded-4 { padding: 14px !important; font-size: 13px; }

        /* Dropdown item lebih besar untuk sentuhan */
        .dropdown-menu-custom .dropdown-item { padding: 10px 12px; font-size: 13.5px; }
        .dropdown-action-btn { width: 38px; height: 38px; }

        /* Pagination */
        .pagination-container { justify-content: center !important; }
        .pagination { justify-content: center !important; flex-wrap: wrap; gap: 5px; }
        .pagination .page-item .page-link { padding: 8px 13px; font-size: 13px; border-radius: 10px !important; }

        /* Modal */
        .modal-dialog { margin: 14px; }
        .modal-header, .modal-body, .modal-footer { padding-left: 18px !important; padding-right: 18px !important; }
        .modal-title { font-size: 16px; }
    }

    @media (max-width: 400px) {
        .row.g-3.mb-4 > [class*="col-"] { flex: 0 0 100%; max-width: 100%; }
        .stat-card { flex-direction: row; align-items: center; }
    }
</style>

<div class="container-fluid py-2">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div class="page-header-bg"></div>
        <div class="page-header-content d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    PENGGUNA
                </span>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
                    Manajemen Data Siswa
                </h3>
                <p class="text-light opacity-75 mb-0 small">
                    Kelola data siswa dan penempatan kelasnya pada platform CBT.
                </p>
            </div>

            {{-- Toolbar aksi: 1 tombol utama (Tambah Siswa) + 1 dropdown untuk aksi sekunder --}}
            <div class="d-flex gap-2 header-actions">
                <div class="dropdown">
                    <button class="btn btn-light border btn-add dropdown-toggle d-inline-flex align-items-center"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi Lainnya">
                        <i class="fa-solid fa-ellipsis-vertical me-2"></i>
                        <span class="btn-add-label">Aksi Lainnya</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                               href="{{ route('siswa.export-kartu-pdf', request()->only(['search', 'jenjang', 'kelas'])) }}"
                               target="_blank">
                                <i class="fa-solid fa-id-card text-warning" style="width: 16px;"></i>
                                Cetak Kartu Ujian (PDF)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                               href="{{ route('siswa.export', request()->only(['search', 'jenjang', 'kelas'])) }}">
                                <i class="fa-solid fa-file-export text-secondary" style="width: 16px;"></i>
                                Export Data
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('siswa.template') }}">
                                <i class="fa-solid fa-download text-secondary" style="width: 16px;"></i>
                                Download Template
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <button type="button"
                                    class="dropdown-item d-flex align-items-center gap-2 w-100 border-0 bg-transparent"
                                    data-bs-toggle="modal" data-bs-target="#modalImportSiswa">
                                <i class="fa-solid fa-file-excel text-success" style="width: 16px;"></i>
                                Import Excel
                            </button>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('siswa.create') }}" class="btn btn-info text-white btn-add d-inline-flex align-items-center fw-semibold">
                    <i class="fa-solid fa-plus me-2"></i>
                    Tambah Siswa
                </a>
            </div>
        </div>
    </div>

    {{-- Widget Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL SISWA</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalSiswa }}</h4>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TAHUN AJARAN AKTIF</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $tahunAktif->nama_tahun ?? '-' }}</h4>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="stat-card">
                <div class="stat-icon {{ isset($tahunAktif) && $tahunAktif->semester == 'ganjil' ? 'bg-primary text-primary' : 'bg-success text-success' }} bg-opacity-10">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">SEMESTER AKTIF</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ isset($tahunAktif) ? ucfirst($tahunAktif->semester) : '-' }}</h4>
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
        <div class="fw-bold mb-2"><i class="fa-solid fa-circle-info me-2"></i>Baris dilewati:</div>
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
            <form method="GET" action="{{ route('siswa.index') }}">
                <div class="row g-3 mb-3 align-items-center">

                    <div class="col-lg-4">
                        <input
                            type="text"
                            name="search"
                            class="form-control form-control-custom"
                            placeholder="Cari nama, NIS, atau NISN..."
                            value="{{ request('search') }}">
                    </div>

                    @if(Auth::user()->role != 'admin_jenjang')
                        <div class="col-lg-3">
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
                        <select name="kelas" class="form-select form-control-custom">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                                    {{ optional($kelas->tingkat)->nama_tingkat }} - {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-auto">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark btn-action-trigger">
                                <i class="fa fa-search me-2"></i>
                                Filter
                            </button>

                            @if(request()->filled('search') || request()->filled('jenjang') || request()->filled('kelas'))
                                <a href="{{ route('siswa.index') }}" class="btn btn-light border btn-action-trigger" title="Reset Filter">
                                    <i class="fa-solid fa-rotate"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </form>

            <p class="text-muted small mb-3">
                <i class="fa-solid fa-circle-info me-1"></i>
                Data siswa yang ditampilkan menggunakan kelas aktif pada tahun ajaran &amp; semester berjalan.
            </p>

            {{-- Table (Desktop & Tablet ke atas) --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th width="70">No</th>
                            <th>Nama</th>
                            <th>NIS / NISN</th>
                            <th>Kelas</th>
                            <th width="150" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswas as $item)
                        <tr>
                            <td>
                                <span class="text-secondary fw-semibold">
                                    {{ $loop->iteration + ($siswas->firstItem() - 1) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}"
                                             alt="{{ $item->nama }}"
                                             class="rounded-circle"
                                             style="width: 38px; height: 38px; object-fit: cover; flex-shrink: 0;">
                                    @else
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                             style="width: 38px; height: 38px; flex-shrink: 0; font-size: 14px;">
                                            {{ strtoupper(substr($item->nama, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="fw-bold text-dark fs-6">{{ $item->nama }}</div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $item->nis }}</div>
                                <small class="text-muted">{{ $item->nisn ?? '-' }}</small>
                            </td>
                            <td>
                                @if($item->kelasAktif && $item->kelasAktif->kelas)
                                    <span class="badge bg-dark bg-opacity-10 text-dark px-2 py-2 rounded-3 fw-semibold">
                                        {{ optional($item->kelasAktif->kelas->tingkat)->nama_tingkat }} - {{ $item->kelasAktif->kelas->nama_kelas }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">Belum ada kelas</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="dropdown-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('siswa.show', $item->id) }}">
                                                <i class="fa-solid fa-eye text-info" style="width: 16px;"></i>
                                                Lihat Detail
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('siswa.edit', $item->id) }}">
                                                <i class="fa-solid fa-pen text-primary" style="width: 16px;"></i>
                                                Edit Data
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('siswa.kartu-pdf', $item->id) }}" target="_blank">
                                                <i class="fa-solid fa-id-card" style="width: 16px; color: #8b5cf6;"></i>
                                                Cetak Kartu Ujian
                                            </a>
                                        </li>
                                        <li>
                                            <button type="button"
                                                    class="dropdown-item d-flex align-items-center gap-2 btn-reset-modal"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalResetPassword"
                                                    data-id="{{ $item->id }}"
                                                    data-nama="{{ $item->nama }}"
                                                    data-nis="{{ $item->nis }}"
                                                    data-url="{{ route('siswa.reset-password', $item->id) }}">
                                                <i class="fa-solid fa-key text-warning" style="width: 16px;"></i>
                                                Reset Password
                                            </button>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="form-delete d-inline w-100">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 w-100 border-0 bg-transparent">
                                                    <i class="fa-solid fa-trash text-danger" style="width: 16px;"></i>
                                                    Hapus Siswa
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-user-graduate fa-3x text-muted mb-3 opacity-50"></i>
                                    <h6 class="fw-bold text-secondary">Belum ada data siswa</h6>
                                    <small class="text-muted">Silakan sesuaikan filter Anda atau tambahkan siswa baru.</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Kartu Siswa (Mobile) --}}
            <div class="d-md-none">
                @forelse($siswas as $item)
                <div class="siswa-card-mobile">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-2" style="min-width: 0;">
                            <div class="siswa-avatar-wrap">
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}">
                                @else
                                    <div class="siswa-avatar-fallback bg-primary bg-opacity-10 text-primary">
                                        {{ strtoupper(substr($item->nama, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div style="min-width: 0;">
                                <div class="fw-bold text-dark siswa-card-name">{{ $item->nama }}</div>
                                <div class="text-muted siswa-card-meta">
                                    NIS {{ $item->nis }}@if($item->nisn) &middot; NISN {{ $item->nisn }}@endif
                                </div>
                            </div>
                        </div>

                        <div class="dropdown flex-shrink-0">
                            <button class="dropdown-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('siswa.show', $item->id) }}">
                                        <i class="fa-solid fa-eye text-info" style="width: 16px;"></i>
                                        Lihat Detail
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('siswa.edit', $item->id) }}">
                                        <i class="fa-solid fa-pen text-primary" style="width: 16px;"></i>
                                        Edit Data
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('siswa.kartu-pdf', $item->id) }}" target="_blank">
                                        <i class="fa-solid fa-id-card" style="width: 16px; color: #8b5cf6;"></i>
                                        Cetak Kartu Ujian
                                    </a>
                                </li>
                                <li>
                                    <button type="button"
                                            class="dropdown-item d-flex align-items-center gap-2 btn-reset-modal"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalResetPassword"
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama }}"
                                            data-nis="{{ $item->nis }}"
                                            data-url="{{ route('siswa.reset-password', $item->id) }}">
                                        <i class="fa-solid fa-key text-warning" style="width: 16px;"></i>
                                        Reset Password
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="form-delete d-inline w-100">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 w-100 border-0 bg-transparent">
                                            <i class="fa-solid fa-trash text-danger" style="width: 16px;"></i>
                                            Hapus Siswa
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex align-items-center justify-content-between">
                        @if($item->kelasAktif && $item->kelasAktif->kelas)
                            <span class="badge bg-dark bg-opacity-10 text-dark px-2 py-2 rounded-3 fw-semibold">
                                {{ optional($item->kelasAktif->kelas->tingkat)->nama_tingkat }} - {{ $item->kelasAktif->kelas->nama_kelas }}
                            </span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">Belum ada kelas</span>
                        @endif
                        <span class="text-muted small fw-semibold">#{{ $loop->iteration + ($siswas->firstItem() - 1) }}</span>
                    </div>
                </div>
                @empty
                <div class="siswa-card-empty-mobile">
                    <i class="fa-solid fa-user-graduate fa-2x text-muted mb-3 opacity-50"></i>
                    <h6 class="fw-bold text-secondary mb-1">Belum ada data siswa</h6>
                    <small class="text-muted">Silakan sesuaikan filter Anda atau tambahkan siswa baru.</small>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-4 pagination-container">
                {{ $siswas->links('vendor.pagination.bootstrap-4') }}
            </div>

        </div>
    </div>
</div>

{{-- Modal Import Excel --}}
<div class="modal fade" id="modalImportSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-file-excel text-success me-2"></i>
                        Import Data Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">
                        Unduh <a href="{{ route('siswa.template') }}">template Excel</a> terlebih dahulu,
                        isi datanya, lalu unggah kembali di sini.
                    </p>
                    <input type="file" name="file" class="form-control form-control-custom" accept=".xlsx,.xls,.csv" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light border btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success text-white btn-submit">
                        <i class="fa-solid fa-upload me-2"></i>
                        Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Reset Password --}}
<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-labelledby="modalResetPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div>
                    <h5 class="modal-title fw-bold text-dark" id="modalResetPasswordLabel">Reset Password Siswa</h5>
                    <p class="text-muted small mb-0" id="resetSiswaMeta">Atur ulang password untuk siswa ini.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formResetPassword" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-body p-4">
                    <div class="alert alert-info bg-info bg-opacity-10 text-info border-0 rounded-3 mb-3 py-2 px-3 small">
                        <i class="fa-solid fa-circle-info me-1"></i> Gunakan tombol generate untuk membuat 6 digit kode acak secara cepat.
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-semibold mb-0 small">Password Baru</label>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" id="btnModalGenerate" style="font-size: 11px; font-weight: 600;">
                                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Generate 6 Digit
                            </button>
                        </div>
                        <div class="input-group">
                            <input type="text" name="password" id="modalPasswordInput" class="form-control form-control-custom" placeholder="Minimal 6 karakter" required style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                            <button type="button" class="btn btn-light border" id="btnModalCopy" title="Salin Password" style="border-radius: 0;">
                                <i class="fa-regular fa-copy text-secondary"></i>
                            </button>
                            <button type="button" class="btn btn-light border toggle-modal-pwd" title="Tampilkan / Sembunyikan Password" style="border-top-left-radius: 0; border-bottom-left-radius: 0; border-top-right-radius: 14px; border-bottom-right-radius: 14px;">
                                <i class="fa-solid fa-eye-slash text-secondary"></i>
                            </button>
                        </div>
                        <small class="text-muted mt-1" id="modalCopyNotif" style="display:none; color: #059669 !important; font-weight: 600;">
                            <i class="fa-solid fa-circle-check me-1"></i> Password 6 digit tersalin ke clipboard!
                        </small>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Konfirmasi Password Baru</label>
                        <input type="text" name="password_confirmation" id="modalPasswordConfirmInput" class="form-control form-control-custom" placeholder="Ulangi password baru" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light border px-4 rounded-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.form-delete').forEach(function(form){
    form.addEventListener('submit', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Hapus Data Siswa?',
            text: 'Akun login dan riwayat kelas siswa ini akan ikut terhapus secara permanen.',
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

// Logic Modal Reset Password Siswa
document.addEventListener('DOMContentLoaded', function () {
    const modalReset = document.getElementById('modalResetPassword');
    if (modalReset) {
        modalReset.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const nama = button.getAttribute('data-nama');
            const nis = button.getAttribute('data-nis');
            const url = button.getAttribute('data-url');

            const form = document.getElementById('formResetPassword');
            form.action = url;

            const meta = document.getElementById('resetSiswaMeta');
            meta.textContent = 'Atur ulang password untuk ' + nama + ' (NIS: ' + (nis || '-') + ')';

            // Auto generate 6 digit angka acak saat modal dibuka
            const code = Math.floor(100000 + Math.random() * 900000).toString();
            const pwdInput = document.getElementById('modalPasswordInput');
            const confirmInput = document.getElementById('modalPasswordConfirmInput');

            pwdInput.value = code;
            confirmInput.value = code;
            pwdInput.type = 'text';
            confirmInput.type = 'text';

            const copyNotif = document.getElementById('modalCopyNotif');
            if (copyNotif) copyNotif.style.display = 'none';
        });
    }

    const btnGenerate = document.getElementById('btnModalGenerate');
    if (btnGenerate) {
        btnGenerate.addEventListener('click', function () {
            const code = Math.floor(100000 + Math.random() * 900000).toString();
            const pwdInput = document.getElementById('modalPasswordInput');
            const confirmInput = document.getElementById('modalPasswordConfirmInput');

            pwdInput.value = code;
            confirmInput.value = code;
            pwdInput.type = 'text';
            confirmInput.type = 'text';
        });
    }

    const btnCopy = document.getElementById('btnModalCopy');
    if (btnCopy) {
        btnCopy.addEventListener('click', function () {
            const pwdInput = document.getElementById('modalPasswordInput');
            if (pwdInput && pwdInput.value) {
                navigator.clipboard.writeText(pwdInput.value).then(function () {
                    const copyNotif = document.getElementById('modalCopyNotif');
                    if (copyNotif) {
                        copyNotif.style.display = 'inline-block';
                        setTimeout(() => { copyNotif.style.display = 'none'; }, 2500);
                    }
                }).catch(function () {
                    pwdInput.select();
                    document.execCommand('copy');
                });
            }
        });
    }

    const toggleModalPwd = document.querySelector('.toggle-modal-pwd');
    if (toggleModalPwd) {
        toggleModalPwd.addEventListener('click', function () {
            const pwdInput = document.getElementById('modalPasswordInput');
            const confirmInput = document.getElementById('modalPasswordConfirmInput');
            const icon = this.querySelector('i');

            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                confirmInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pwdInput.type = 'password';
                confirmInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }
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

@endsection