@extends('layouts.app')

@section('title', 'Bank Soal')

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
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
    }

    .btn-action-trigger {
        border-radius: 14px;
        height: 46px;
        padding: 0 20px;
        font-weight: 600;
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

    .status-publish {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid rgba(5, 150, 105, 0.15);
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .status-draft {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid rgba(217, 119, 6, 0.15);
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
        min-width: 200px;
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

    .dropdown-action-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 6px 4px;
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

    /* ============================================
       RESPONSIVE: TABLET & MOBILE (<= 768px)
       ============================================ */
    @media (max-width: 768px) {
        .container-fluid.py-2 { padding-left: 12px; padding-right: 12px; }

        .page-header { padding: 22px 18px; border-radius: 20px; }
        .page-header h3 { font-size: 19px; margin-bottom: 4px; }
        .page-header p.small { font-size: 12.5px; }

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
            min-width: 760px;
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

        .action-icon-btn { width: 36px; height: 36px; }

        .pagination { justify-content: center !important; flex-wrap: wrap; }
    }
</style>

<div class="container-fluid py-2">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    UJIAN
                </span>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
                    Bank Soal
                </h3>
                <p class="text-light opacity-75 mb-0 small">
                    Review dan kelola publikasi bank soal dari semua guru.
                </p>
            </div>
        </div>
    </div>

    {{-- Widget Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <div>
                    <small class="text-muted d-block uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL BANK SOAL</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalBankSoal }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <small class="text-muted d-block uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">SUDAH PUBLISH</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalPublish }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <small class="text-muted d-block uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">MASIH DRAFT</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalDraft }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="fa-solid fa-list-ol"></i>
                </div>
                <div>
                    <small class="text-muted d-block uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL SOAL</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalSoal }}</h4>
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
            <form method="GET" action="{{ route('bank-soal.index') }}">
                <div class="row g-3 mb-4 align-items-center">

                    <div class="col-lg-3">
                        <input
                            type="text"
                            name="search"
                            class="form-control form-control-custom"
                            placeholder="Cari nama bank soal / guru..."
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
                        <select name="mapel" class="form-select form-control-custom">
                            <option value="">-- Semua Mapel --</option>
                            @foreach($mataPelajarans as $mapel)
                                <option value="{{ $mapel->id }}" {{ request('mapel') == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <select name="status" class="form-select form-control-custom">
                            <option value="">-- Semua Status --</option>
                            <option value="publish" {{ request('status') == 'publish' ? 'selected' : '' }}>Sudah Publish</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Masih Draft</option>
                        </select>
                    </div>

                    <div class="col-lg-auto">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark btn-action-trigger">
                                <i class="fa fa-search me-2"></i>
                                Filter
                            </button>

                            @if(request()->filled('search') || request()->filled('jenjang') || request()->filled('mapel') || request()->filled('status'))
                                <a href="{{ route('bank-soal.index') }}" class="btn btn-light border btn-action-trigger">
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
                            <th>Bank Soal</th>
                            <th>Guru</th>
                            <th width="100" class="text-center">Jml Soal</th>
                            <th width="80" class="text-center">KKM</th>
                            <th width="130">Status</th>
                            <th width="70" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bankSoals as $item)
                        <tr>
                            <td>
                                <span class="text-secondary fw-semibold">
                                    {{ $loop->iteration + ($bankSoals->firstItem() - 1) }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $item->nama_bank_soal }}</div>
                                <small class="text-muted">
                                    {{ optional($item->mataPelajaran)->nama_mapel ?? '-' }}
                                    &middot; {{ optional($item->jenjang)->nama_jenjang ?? '-' }}
                                </small>
                            </td>
                            <td>
                                {{ optional(optional($item->guruMapel)->guru)->nama ?? '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-dark bg-opacity-10 text-dark px-2 py-1.5 rounded-3 fw-semibold">
                                    {{ $item->soals_count }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 12px;">
                                    {{ $item->kkm ?? 75 }}
                                </span>
                            </td>
                            <td>
                                @if($item->is_publish)
                                    <span class="status-publish"><i class="fa-solid fa-circle-check"></i> Publish</span>
                                @else
                                    <span class="status-draft"><i class="fa-solid fa-pen-to-square"></i> Draft</span>
                                @endif
                            </td>

                            <td class="text-end">
                                <div class="dropdown-action-wrap">
                                    <button type="button" class="action-icon-btn btn-icon-more dropdown-action-toggle" title="Menu Aksi">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <div class="dropdown-action-menu">
                                        <a href="{{ route('bank-soal.show', $item->id) }}" class="dropdown-action-item">
                                            <i class="fa-solid fa-eye text-primary"></i>
                                            Lihat Detail
                                        </a>

                                        <form action="{{ route('bank-soal.toggle-publish', $item->id) }}" method="POST" class="w-100 m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-action-item">
                                                <i class="fa-solid {{ $item->is_publish ? 'fa-eye-slash text-warning' : 'fa-circle-check text-success' }}"></i>
                                                {{ $item->is_publish ? 'Tarik ke Draft' : 'Publikasikan' }}
                                            </button>
                                        </form>

                                        <div class="dropdown-action-divider"></div>

                                        <form action="{{ route('bank-soal.destroy', $item->id) }}" method="POST" class="form-delete w-100 m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-action-item text-danger">
                                                <i class="fa-solid fa-trash"></i>
                                                Hapus Bank Soal
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-folder-open fa-3x text-muted mb-3 opacity-50"></i>
                                    <h6 class="fw-bold text-secondary">Belum ada bank soal</h6>
                                    <small class="text-muted">Bank soal akan muncul di sini setelah guru membuatnya.</small>
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
                {{ $bankSoals->links('vendor.pagination.bootstrap-4') }}
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
            title: 'Hapus Bank Soal?',
            text: 'Seluruh soal di dalam bank soal ini akan ikut terhapus permanen.',
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
        const menuWidth = menu.offsetWidth || 200;

        // Buka ke kiri kalau kepepet di tepi kanan layar
        let left = rect.right - menuWidth;
        if (left < 8) left = 8;

        let top = rect.bottom + 6;
        // Kalau menu bakal keluar dari layar bawah, buka ke atas tombol
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

    // Tutup menu saat klik di luar, saat scroll, atau saat resize
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

    // Klik di dalam menu tidak boleh langsung menutup sebelum aksi (link/submit) berjalan
    document.querySelectorAll('.dropdown-action-menu').forEach(function (menu) {
        menu.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });
})();
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