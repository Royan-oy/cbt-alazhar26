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

    .btn-icon-info { background: #eff6ff; color: #2563eb; }
    .btn-icon-info:hover { background: #2563eb; color: white; }

    .btn-icon-publish { background: #ecfdf5; color: #059669; }
    .btn-icon-publish:hover { background: #059669; color: white; }

    .btn-icon-unpublish { background: #fffbeb; color: #d97706; }
    .btn-icon-unpublish:hover { background: #d97706; color: white; }

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
        .page-header { padding: 24px; border-radius: 18px; text-align: center; }
        .content-card { padding: 4px; border-radius: 18px; }
        .form-control-custom, .btn-action-trigger { width: 100%; margin-bottom: 8px; }

        .table-responsive table, .table-responsive thead, .table-responsive tbody,
        .table-responsive th, .table-responsive td, .table-responsive tr { display: block; }

        .table-responsive thead tr { position: absolute; top: -9999px; left: -9999px; }

        .table-responsive tr {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            margin-bottom: 16px;
            padding: 12px;
            background: #fff;
        }

        .table-responsive td {
            border: none;
            border-bottom: 1px dashed #f1f5f9;
            position: relative;
            padding-left: 45% !important;
            text-align: right !important;
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .table-responsive td:before {
            position: absolute;
            left: 12px;
            width: 40%;
            white-space: nowrap;
            text-align: left;
            font-weight: 700;
            color: var(--text-muted);
            font-size: 11px;
            text-transform: uppercase;
        }

        .table-responsive td:nth-of-type(1):before { content: "No"; }
        .table-responsive td:nth-of-type(2):before { content: "Bank Soal"; }
        .table-responsive td:nth-of-type(3):before { content: "Guru"; }
        .table-responsive td:nth-of-type(4):before { content: "Jml Soal"; }
        .table-responsive td:nth-of-type(5):before { content: "Status"; }
        .table-responsive td:nth-of-type(6):before { content: "Aksi"; }

        .pagination { justify-content: center !important; }
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
                            <th width="150" class="text-end">Aksi</th>
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
                                <div class="d-inline-flex">

                                    <a href="{{ route('bank-soal.show', $item->id) }}"
                                        class="action-icon-btn btn-icon-info"
                                        title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <form action="{{ route('bank-soal.toggle-publish', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="action-icon-btn {{ $item->is_publish ? 'btn-icon-unpublish' : 'btn-icon-publish' }}"
                                            title="{{ $item->is_publish ? 'Tarik ke Draft' : 'Publikasikan' }}">
                                            <i class="fa-solid {{ $item->is_publish ? 'fa-eye-slash' : 'fa-circle-check' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('bank-soal.destroy', $item->id) }}" method="POST" class="form-delete d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon-btn btn-icon-delete" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
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