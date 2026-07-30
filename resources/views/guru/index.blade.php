@extends('layouts.app')

@section('title', 'Data Guru')

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

    /* Dropdown action (dipakai untuk toolbar header maupun aksi per baris/kartu) */
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
       KARTU GURU UNTUK TAMPILAN MOBILE
       ============================================ */
    .guru-card-mobile {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 14px;
        margin-bottom: 12px;
    }
    .guru-card-mobile .guru-avatar-wrap {
        width: 44px;
        height: 44px;
        flex-shrink: 0;
    }
    .guru-card-mobile .guru-avatar-wrap img,
    .guru-card-mobile .guru-avatar-wrap .guru-avatar-fallback {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        object-fit: cover;
    }
    .guru-card-mobile .guru-avatar-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
    }
    .guru-card-mobile .guru-card-name {
        font-size: 14.5px;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }
    .guru-card-mobile .guru-card-meta {
        font-size: 12px;
    }
    .guru-card-mobile hr {
        margin: 12px 0;
        opacity: 1;
        border-color: #f1f5f9;
    }
    .guru-card-empty-mobile {
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

        /* Kartu statistik */
        .row.g-3.mb-4 { margin-bottom: 18px !important; row-gap: 12px !important; }
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
        form .row.g-3.mb-4 { row-gap: 10px; }
        .form-control-custom, .btn-action-trigger { width: 100%; }
        .col-lg-auto .d-flex.gap-2 { width: 100%; }
        .col-lg-auto .d-flex.gap-2 .btn-action-trigger { height: 46px; }
        .col-lg-auto .d-flex.gap-2 button[type="submit"] { flex: 1; }
        .col-lg-auto .d-flex.gap-2 a.btn-action-trigger { flex: 0 0 46px; width: 46px; padding: 0; display: flex; align-items: center; justify-content: center; }

        /* Alert */
        .alert.rounded-4 { padding: 14px !important; font-size: 13px; }

        /* Dropdown lebih besar untuk sentuhan */
        .dropdown-menu-custom .dropdown-item { padding: 10px 12px; font-size: 13.5px; }
        .dropdown-action-btn { width: 38px; height: 38px; }

        /* Pagination */
        .pagination-container { justify-content: center !important; }
        .pagination { justify-content: center !important; flex-wrap: wrap; gap: 5px; }
        .pagination .page-item .page-link { padding: 8px 13px; font-size: 13px; border-radius: 10px !important; }

        /* Modal Import */
        #importGuruModal .modal-dialog { margin: 14px; }
        #importGuruModal .modal-header { padding: 18px 20px; }
        #importGuruModal .modal-title { font-size: 15px; }
        #importGuruModal .modal-footer { flex-direction: column-reverse; gap: 8px; }
        #importGuruModal .modal-footer .btn-modal-cancel,
        #importGuruModal .modal-footer .btn-modal-import { width: 100%; justify-content: center; display: flex; align-items: center; }
    }

    /* ==========================================
       MODAL IMPORT GURU
       ========================================== */
    #importGuruModal .modal-dialog { max-width: 700px; }

    #importGuruModal .modal-content {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(15, 23, 42, .18);
    }

    #importGuruModal .modal-header {
        background: linear-gradient(135deg, #15803d, #16a34a);
        border: none;
        padding: 22px 28px;
    }

    #importGuruModal .modal-title {
        font-size: 1.15rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #importGuruModal .modal-title i {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.15);
        font-size: 18px;
    }

    #importGuruModal .btn-close { opacity: 1; }

    #importGuruModal .modal-body { padding: 30px; background: #f8fafc; }

    .import-alert {
        background: #eef9f1;
        border: 1px solid #b7e4c7;
        border-radius: 18px;
        padding: 20px;
    }

    .import-alert strong { color: #166534; font-size: 15px; }
    .import-alert ul { margin-top: 12px; padding-left: 18px; }
    .import-alert li { color: #475569; margin-bottom: 8px; }

    .upload-box {
        margin-top: 22px;
        border: 2px dashed #16a34a;
        border-radius: 18px;
        background: white;
        padding: 35px;
        text-align: center;
        transition: .3s;
    }

    .upload-box:hover { background: #f0fdf4; border-color: #15803d; }
    .upload-box i { font-size: 55px; color: #16a34a; margin-bottom: 15px; }
    .upload-box h6 { font-weight: 700; margin-bottom: 8px; }
    .upload-box p { color: #64748b; font-size: 14px; margin-bottom: 18px; }
    .upload-box input { max-width: 360px; margin: auto; }

    #importGuruModal .modal-footer {
        background: #fff;
        border-top: 1px solid #edf2f7;
        padding: 20px 28px;
    }

    .btn-modal-cancel { border-radius: 12px; padding: 10px 22px; font-weight: 600; }

    .btn-modal-import {
        border: none;
        border-radius: 12px;
        padding: 10px 22px;
        font-weight: 600;
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: white;
        transition: .25s;
    }

    .btn-modal-import:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(22,163,74,.35);
        color: white;
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
                    Manajemen Data Guru
                </h3>
                <p class="text-light opacity-75 mb-0 small">
                    Kelola data guru pengampu mata pelajaran pada platform CBT.
                </p>
            </div>

            {{-- Toolbar aksi: 1 tombol utama (Tambah Guru) + 1 dropdown untuk aksi sekunder --}}
            <div class="d-flex gap-2 header-actions">
                <div class="dropdown">
                    <button class="btn btn-light border btn-add dropdown-toggle d-inline-flex align-items-center"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi Lainnya">
                        <i class="fa-solid fa-ellipsis-vertical me-2"></i>
                        <span class="btn-add-label">Aksi Lainnya</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('guru.template') }}">
                                <i class="fa-solid fa-download text-secondary" style="width: 16px;"></i>
                                Download Template
                            </a>
                        </li>
                        <li>
                            <button type="button"
                                    class="dropdown-item d-flex align-items-center gap-2 w-100 border-0 bg-transparent"
                                    data-bs-toggle="modal" data-bs-target="#importGuruModal">
                                <i class="fa-solid fa-file-excel text-success" style="width: 16px;"></i>
                                Import Excel
                            </button>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('guru.create') }}" class="btn btn-info text-white btn-add d-inline-flex align-items-center fw-semibold">
                    <i class="fa-solid fa-plus me-2"></i>
                    Tambah Guru
                </a>
            </div>
        </div>
    </div>

    {{-- Widget Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL GURU</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalGuru }}</h4>
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
            <form method="GET" action="{{ route('guru.index') }}">
                <div class="row g-3 mb-4 align-items-center">

                    <div class="col-lg-4">
                        <input
                            type="text"
                            name="search"
                            class="form-control form-control-custom"
                            placeholder="Cari nama atau NIP..."
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

                    <div class="col-lg-auto">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark btn-action-trigger">
                                <i class="fa fa-search me-2"></i>
                                Filter
                            </button>

                            @if(request()->filled('search') || request()->filled('jenjang'))
                                <a href="{{ route('guru.index') }}" class="btn btn-light border btn-action-trigger" title="Reset Filter">
                                    <i class="fa-solid fa-rotate"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </form>

            {{-- Table (Desktop & Tablet ke atas) --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th width="70">No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Jenjang</th>
                            <th width="150" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gurus as $item)
                        <tr>
                            <td>
                                <span class="text-secondary fw-semibold">
                                    {{ $loop->iteration + ($gurus->firstItem() - 1) }}
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
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ $item->nama }}</div>
                                        <small class="text-muted">{{ optional($item->user)->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->nip }}</td>
                            <td>
                                <span class="badge bg-dark bg-opacity-10 text-dark px-2 py-2 rounded-3 fw-semibold">
                                    {{ optional($item->jenjang)->nama_jenjang ?? '-' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="dropdown-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('guru.show', $item->id) }}">
                                                <i class="fa-solid fa-eye text-info" style="width: 16px;"></i>
                                                Lihat Detail
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('guru.edit', $item->id) }}">
                                                <i class="fa-solid fa-pen text-primary" style="width: 16px;"></i>
                                                Edit Data
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('guru.destroy', $item->id) }}" method="POST" class="form-delete d-inline w-100">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 w-100 border-0 bg-transparent">
                                                    <i class="fa-solid fa-trash text-danger" style="width: 16px;"></i>
                                                    Hapus Guru
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
                                    <i class="fa-solid fa-chalkboard-user fa-3x text-muted mb-3 opacity-50"></i>
                                    <h6 class="fw-bold text-secondary">Belum ada data guru</h6>
                                    <small class="text-muted">Silakan sesuaikan filter Anda atau tambahkan guru baru.</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Kartu Guru (Mobile) --}}
            <div class="d-md-none">
                @forelse($gurus as $item)
                <div class="guru-card-mobile">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-2" style="min-width: 0;">
                            <div class="guru-avatar-wrap">
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}">
                                @else
                                    <div class="guru-avatar-fallback bg-primary bg-opacity-10 text-primary">
                                        {{ strtoupper(substr($item->nama, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div style="min-width: 0;">
                                <div class="fw-bold text-dark guru-card-name">{{ $item->nama }}</div>
                                <div class="text-muted guru-card-meta">
                                    NIP {{ $item->nip ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="dropdown flex-shrink-0">
                            <button class="dropdown-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('guru.show', $item->id) }}">
                                        <i class="fa-solid fa-eye text-info" style="width: 16px;"></i>
                                        Lihat Detail
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('guru.edit', $item->id) }}">
                                        <i class="fa-solid fa-pen text-primary" style="width: 16px;"></i>
                                        Edit Data
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ route('guru.destroy', $item->id) }}" method="POST" class="form-delete d-inline w-100">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 w-100 border-0 bg-transparent">
                                            <i class="fa-solid fa-trash text-danger" style="width: 16px;"></i>
                                            Hapus Guru
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge bg-dark bg-opacity-10 text-dark px-2 py-2 rounded-3 fw-semibold">
                            {{ optional($item->jenjang)->nama_jenjang ?? '-' }}
                        </span>
                        <span class="text-muted small fw-semibold">#{{ $loop->iteration + ($gurus->firstItem() - 1) }}</span>
                    </div>
                </div>
                @empty
                <div class="guru-card-empty-mobile">
                    <i class="fa-solid fa-chalkboard-user fa-2x text-muted mb-3 opacity-50"></i>
                    <h6 class="fw-bold text-secondary mb-1">Belum ada data guru</h6>
                    <small class="text-muted">Silakan sesuaikan filter Anda atau tambahkan guru baru.</small>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-4 pagination-container">
                {{ $gurus->links('vendor.pagination.bootstrap-4') }}
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
            title: 'Hapus Data Guru?',
            text: 'Akun login guru ini akan ikut terhapus secara permanen.',
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

<!-- Modal Import Guru -->
<div class="modal fade" id="importGuruModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('guru.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-file-excel me-2"></i>
                        Import Data Guru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="import-alert">
                        <strong>
                            <i class="fa-solid fa-circle-info me-2"></i>
                            Petunjuk Import
                        </strong>
                        <ul class="mb-0">
                            <li>Download template Excel terlebih dahulu.</li>
                            <li>Jangan mengubah nama kolom.</li>
                            <li>Pastikan NIP dan Email tidak duplikat.</li>
                            <li>Pastikan Jenjang sudah tersedia di sistem.</li>
                            <li>Format file harus <strong>.xlsx</strong> atau <strong>.xls</strong>.</li>
                        </ul>
                    </div>

                    <div class="upload-box">
                        <i class="fa-solid fa-file-excel"></i>
                        <h6>Upload File Excel Guru</h6>
                        <p>Klik tombol di bawah untuk memilih file Excel yang akan diimport.</p>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-modal-cancel" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-2"></i>
                        Batal
                    </button>

                    <button type="submit" class="btn-modal-import">
                        <i class="fa-solid fa-upload me-2"></i>
                        Import Data Guru
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection