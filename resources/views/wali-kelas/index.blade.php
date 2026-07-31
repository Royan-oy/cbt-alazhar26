@extends('layouts.app')

@section('title', 'Wali Kelas')

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

    /* Wrapper khusus untuk clip lingkaran dekorasi, terpisah dari overflow header
       supaya dropdown "Aksi Lainnya" tidak ikut kepotong */
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
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
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
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
        white-space: nowrap;
    }

    .btn-action-trigger {
        border-radius: 14px;
        height: 46px;
        padding: 0 20px;
        font-weight: 600;
    }

    /* Dropdown "Aksi Lainnya" di header */
    .dropdown-action-btn {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        border: 1px solid rgba(255,255,255,0.2);
        background-color: rgba(255,255,255,0.1);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .dropdown-action-btn:hover, .dropdown-action-btn:focus {
        background-color: rgba(255,255,255,0.2);
        color: #fff;
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

    .table-responsive { border-radius: 16px; overflow: hidden; }

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

    .action-icon-btn {
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 4px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .action-icon-btn:hover { transform: translateY(-2px); }

    .btn-icon-edit { background: #f0fdfa; color: #0d9488; }
    .btn-icon-edit:hover { background: #0d9488; color: white; }

    .btn-icon-delete { background: #fff5f5; color: #e11d48; }
    .btn-icon-delete:hover { background: #e11d48; color: white; }

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

    @media (max-width: 768px) {
        .container-fluid.py-2 { padding-left: 12px; padding-right: 12px; }

        /* Header */
        .page-header { padding: 22px 18px; border-radius: 20px; text-align: left; }
        .page-header-content.d-flex.justify-content-between {
            flex-direction: column;
            align-items: stretch !important;
            gap: 18px !important;
        }
        .page-header h3 { font-size: 19px; margin-bottom: 4px; }
        .page-header p.small { font-size: 12.5px; }

        .header-actions { width: 100%; display: flex; gap: 8px; }
        .header-actions .dropdown-action-btn { flex: 0 0 auto; }
        .header-actions a.btn-add { flex: 1; width: auto; justify-content: center; height: 46px; }

        .content-card { padding: 4px; border-radius: 18px; }

        /* Filter form */
        .row.g-3.mb-4.align-items-center { row-gap: 10px !important; }
        .col-lg-4, .col-lg-3, .col-lg-auto { width: 100%; }
        .col-lg-auto .d-flex { width: 100%; }
        .col-lg-auto .d-flex .btn-action-trigger:first-child { flex: 1; }

        .pagination { justify-content: center !important; flex-wrap: wrap; }

        #importModal .modal-dialog { margin: 14px; }
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
    }

    /* Indikator visual bahwa tabel bisa digeser (gradient tipis di kanan) */
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

    @media (max-width: 768px) {
        .table-scroll-wrap .table {
            min-width: 640px;
            margin-bottom: 0;
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

        .avatar-guru { width: 32px; height: 32px; font-size: 12px; }

        /* Kolom "No" menempel di kiri */
        .table-scroll-wrap .table th:first-child,
        .table-scroll-wrap .table td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
            background: #fff;
            box-shadow: 2px 0 6px rgba(15, 23, 42, 0.04);
        }

        .table-scroll-wrap .table thead th:first-child { background: #f8fafc; }

        /* Kolom "Aksi" menempel di kanan */
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

        .action-icon-btn { width: 36px; height: 36px; margin-left: 2px; }
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
                    Wali Kelas
                </h3>
                <p class="text-light opacity-75 mb-0 small">
                    Kelola penugasan guru sebagai wali kelas per tahun ajaran.
                </p>
            </div>

            {{-- Toolbar aksi: dropdown untuk aksi sekunder + 1 tombol utama --}}
            <div class="d-flex gap-2 header-actions">
                <div class="dropdown">
                    <button class="dropdown-action-btn dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false" title="Aksi Lainnya">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('wali-kelas.template') }}">
                                <i class="fa-solid fa-file-arrow-down text-secondary" style="width: 16px;"></i>
                                Download Template
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                               href="{{ route('wali-kelas.export', request()->only(['search', 'jenjang', 'tahun_ajaran'])) }}">
                                <i class="fa-solid fa-file-excel text-success" style="width: 16px;"></i>
                                Export Excel
                            </a>
                        </li>
                        <li>
                            <button type="button"
                                    class="dropdown-item d-flex align-items-center gap-2 w-100 border-0 bg-transparent"
                                    data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="fa-solid fa-file-import text-warning" style="width: 16px;"></i>
                                Import Excel
                            </button>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('wali-kelas.create') }}" class="btn btn-info text-white btn-add d-inline-flex align-items-center fw-semibold">
                    <i class="fa-solid fa-plus me-2"></i>
                    Tambah Wali Kelas
                </a>
            </div>
        </div>
    </div>

    {{-- Widget Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <div>
                    <small class="text-muted d-block uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL WALI KELAS</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalWaliKelas }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="card content-card">
        @if(session('import_errors') && count(session('import_errors')))

        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">

            <h5 class="fw-bold mb-3">

                <i class="fa fa-circle-exclamation me-2"></i>

                Detail Error Import

            </h5>

            <div class="table-responsive">

                <table class="table table-sm align-middle">

                    <thead>

                        <tr>

                            <th width="80">Baris</th>

                            <th>Keterangan</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach(session('import_errors') as $item)

                        <tr>

                            <td>

                                <span class="badge bg-danger">

                                    {{ $item['baris'] }}

                                </span>

                            </td>

                            <td>

                                {{ $item['pesan'] }}

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        @endif
        <div class="card-body">

            {{-- Filter & Search Form --}}
            <form method="GET" action="{{ route('wali-kelas.index') }}">
                <div class="row g-3 mb-4 align-items-center">

                    <div class="col-lg-4">
                        <input
                            type="text"
                            name="search"
                            class="form-control form-control-custom"
                            placeholder="Cari nama guru atau kelas..."
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
                        <select name="tahun_ajaran" class="form-select form-control-custom">
                            <option value="">
                                {{ $tahunAktif
                                    ? 'Tahun Aktif : '.$tahunAktif->nama_tahun.' - '.ucfirst($tahunAktif->semester)
                                    : '-- Semua Tahun Ajaran --'
                                }}
                            </option>
                            @php
                                $selectedTahun = request('tahun_ajaran') ?? optional($tahunAktif)->id;
                            @endphp

                            @foreach($tahunAjarans as $tahun)
                            <option
                                value="{{ $tahun->id }}"
                                {{ $selectedTahun == $tahun->id ? 'selected' : '' }}>

                                {{ $tahun->nama_tahun }} - {{ ucfirst($tahun->semester) }}

                                @if($tahun->is_aktif)
                                    ⭐ Aktif
                                @endif

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

                            @if(request()->filled('search') || request()->filled('jenjang') || request()->filled('tahun_ajaran'))
                                <a href="{{ route('wali-kelas.index') }}" class="btn btn-light border btn-action-trigger">
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
                            <th>Guru</th>
                            <th>Kelas</th>
                            <th>Tahun Ajaran</th>
                            <th width="120" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($waliKelas as $item)
                        <tr>
                            <td>
                                <span class="text-secondary fw-semibold">
                                    {{ $waliKelas->firstItem() + $loop->index }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-guru bg-primary bg-opacity-10 text-primary">
                                        {{ strtoupper(substr(optional($item->guru)->nama ?? '-', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ optional($item->guru)->nama ?? '-' }}</div>
                                        <small class="text-muted">{{ optional(optional($item->guru)->jenjang)->nama_jenjang ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-dark bg-opacity-10 text-dark px-2 py-1.5 rounded-3 fw-semibold">
                                    <i class="fa-solid fa-door-open me-1"></i>
                                    {{ optional($item->kelas)->nama_kelas ?? '-' }}
                                </span>
                                <div class="text-muted small mt-1">{{ optional(optional($item->kelas)->tingkat)->nama_tingkat ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ optional($item->tahunAjaran)->nama_tahun ?? '-' }}</div>
                                <small class="text-muted">{{ ucfirst(optional($item->tahunAjaran)->semester ?? '-') }}</small>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex">

                                    <a href="{{ route('wali-kelas.edit', $item->id) }}"
                                        class="action-icon-btn btn-icon-edit"
                                        title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form action="{{ route('wali-kelas.destroy', $item->id) }}"
                                        method="POST"
                                        class="form-delete d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="action-icon-btn btn-icon-delete"
                                            title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-users-gear fa-3x text-muted mb-3 opacity-50"></i>
                                    <h6 class="fw-bold text-secondary">Belum ada data wali kelas</h6>
                                    <small class="text-muted">Silakan sesuaikan filter Anda atau tambahkan wali kelas baru.</small>
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
                {{ $waliKelas->links('vendor.pagination.bootstrap-4') }}
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
            title: 'Hapus Wali Kelas?',
            text: 'Penugasan guru sebagai wali kelas ini akan dihapus.',
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

@if(session('import_success') !== null)

<script>

Swal.fire({

    icon:'success',

    title:'Import Selesai',

    html:`

        <div style="text-align:left">

            <table class="table table-bordered">

                <tr>

                    <th>Berhasil</th>

                    <td><b>{{ session('import_success') }}</b></td>

                </tr>

                <tr>

                    <th>Skip</th>

                    <td><b>{{ session('import_skipped') }}</b></td>

                </tr>

                <tr>

                    <th>Gagal</th>

                    <td><b>{{ session('import_failed') }}</b></td>

                </tr>

            </table>

        </div>

    `,

    confirmButtonColor:'#0f172a'

});

</script>

@endif

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form
            id="formImport"
            action="{{ route('wali-kelas.import') }}"
            method="POST"
            enctype="multipart/form-data"
            class="modal-content rounded-4 border-0 shadow">

            @csrf

            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-file-import me-2"></i>
                    Import Wali Kelas
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div class="alert alert-info rounded-3">

                    <i class="fa-solid fa-circle-info me-2"></i>

                    Gunakan file hasil export atau template yang telah disediakan.

                </div>

                <label class="form-label fw-semibold">

                    File Excel

                </label>

                <input
                    type="file"
                    name="file"
                    class="form-control"
                    accept=".xlsx,.xls,.csv"
                    required>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal">

                    Batal

                </button>

                <button
                    class="btn btn-warning">

                    <i class="fa-solid fa-upload me-2"></i>

                    Import Data

                </button>

            </div>

        </form>
    </div>
</div>

<script>
document.getElementById('formImport').addEventListener('submit', function(){

    Swal.fire({
        title: 'Mengimport Data...',
        html: 'Mohon tunggu, sistem sedang memproses file Excel.',
        allowOutsideClick:false,
        allowEscapeKey:false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

});
</script>
@endsection