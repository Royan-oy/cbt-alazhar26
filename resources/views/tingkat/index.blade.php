@extends('layouts.app')

@section('title', 'Data Tingkat')

@section('content')

<style>
    /* Global Layout */
    body {
        background-color: #f8fafc;
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        border-radius: 16px;
        padding: 24px 30px;
        position: relative;
        overflow: hidden;
        color: #fff;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
    }

    .page-header::after {
        content: '';
        position: absolute;
        right: -30px;
        top: -50px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.15) 0%, rgba(56, 189, 248, 0) 70%);
        pointer-events: none;
    }

    .page-header > * {
        position: relative;
        z-index: 2;
    }
    
    .page-header h4 {
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    /* Stat Card */
    .stat-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.04);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        background: #f0fdf4;
        color: #16a34a;
    }

    /* Main Content Card */
    .content-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
        background: #ffffff;
    }

    /* Inputs & Buttons - More compact height */
    .search-box, .form-select, .btn-search, .btn-reset {
        height: 40px;
        border-radius: 10px;
        font-size: 0.9rem;
    }

    .search-box, .form-select {
        border: 1px solid #cbd5e1;
        padding-left: 14px;
        transition: all 0.2s ease;
    }

    .search-box:focus, .form-select:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.12);
    }

    /* Table Styling */
    .table thead th {
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        color: #64748b;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        font-weight: 700;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        border-color: #f1f5f9;
        color: #334155;
        font-size: 0.9rem;
    }

    /* Badges */
    .badge-jenjang {
        background: #f0f9ff;
        color: #0369a1;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid #e0f2fe;
    }

    /* Action Buttons */
    .action-icon-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 8px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        transition: all 0.2s;
        margin-left: 4px;
    }

    .btn-icon-edit { background: #f1f5f9; color: #475569; }
    .btn-icon-edit:hover { background: #0ea5e9; color: white; transform: scale(1.05); }

    .btn-icon-delete { background: #fff5f5; color: #e11d48; }
    .btn-icon-delete:hover { background: #e11d48; color: white; transform: scale(1.05); }

    .btn-add {
        border-radius: 10px;
        padding: 8px 18px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(14, 165, 233, 0.15);
    }

    /* Empty State */
    .empty-state {
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 48px;
        color: #cbd5e1;
    }

    /* Pagination */
    .pagination-container .pagination { gap: 4px; margin-bottom: 0; }
    .pagination-container .page-link {
        border: none;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.85rem;
        color: #475569;
        font-weight: 600;
    }
    .pagination-container .page-link:hover, .pagination-container .page-item.active .page-link {
        background: #2563eb; color: #fff;
    }

    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .page-header {
            padding: 20px;
            text-align: center;
        }
        .page-header .d-flex {
            flex-direction: column !important;
            gap: 16px;
        }
        .page-header .btn-add { width: 100%; }
        .table { min-width: 600px; }
        .action-icon-btn { width: 36px; height: 36px; }
        .pagination-container { width: 100%; display: flex; justify-content: center; }
    }
</style>

<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info mb-2 px-2 py-1 rounded fw-semibold" style="font-size: 11px;">
                    Master Akademik
                </span>
                <h4 class="mb-1">Data Tingkat</h4>
                <p class="text-light opacity-75 mb-0" style="font-size: 0.85rem;">
                    Kelola seluruh tingkat berdasarkan jenjang pendidikan.
                </p>
            </div>
            <a href="{{ route('tingkat.create') }}" class="btn btn-info text-white btn-add d-inline-flex align-items-center justify-content-center">
                <i class="fa-solid fa-plus me-2" style="font-size: 12px;"></i>
                <span>Tambah Tingkat</span>
            </a>
        </div>
    </div>

    {{-- Stat Card --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-4 col-sm-6">
            <div class="stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fa-solid fa-school"></i>
                    </div>
                    <div class="ms-3">
                        <small class="text-muted d-block fw-semibold mb-1" style="font-size: 10px; letter-spacing: 0.5px;">
                            TOTAL TINGKAT
                        </small>
                        <h4 class="mb-0 fw-bold text-dark">
                            {{ $tingkats->total() }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="card content-card border-0">
        <div class="card-body p-3 p-md-4">
            
            {{-- Search & Filter --}}
            <form method="GET" class="mb-3">
                <div class="row g-2">
                    <div class="col-lg-4 col-md-4">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control search-box" placeholder="Cari nama tingkat...">
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <select name="jenjang" class="form-select">
                            <option value="">Semua Jenjang</option>
                            @foreach($jenjangs as $jenjang)
                                <option value="{{ $jenjang->id }}" {{ request('jenjang') == $jenjang->id ? 'selected' : '' }}>
                                    {{ $jenjang->nama_jenjang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-2 col-6">
                        <button class="btn btn-primary w-100 btn-search fw-semibold d-flex align-items-center justify-content-center" style="background: #2563eb; border: none;">
                            <i class="fa fa-search me-2" style="font-size: 12px;"></i> Cari
                        </button>
                    </div>
                    <div class="col-lg-1 col-md-2 col-6">
                        <a href="{{ route('tingkat.index') }}" class="btn btn-light w-100 btn-reset fw-semibold d-flex align-items-center justify-content-center" style="border: 1px solid #e2e8f0;">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- Table Data --}}
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="20%">Jenjang</th>
                            <th width="35%">Nama Tingkat</th>
                            <th width="25%">Dibuat</th>
                            <th width="15%" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tingkats as $item)
                            <tr>
                                <td class="text-center fw-semibold text-muted" style="font-size: 0.85rem;">
                                    {{ $loop->iteration + ($tingkats->firstItem() - 1) }}
                                </td>
                                <td>
                                    <span class="badge-jenjang">
                                        {{ $item->jenjang->nama_jenjang }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $item->nama_tingkat }}</span>
                                </td>
                                <td>
                                    <div class="fw-medium" style="font-size: 0.85rem;">{{ $item->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}</div>
                                    <small class="text-muted d-block" style="font-size: 11px;">
                                        <i class="fa-regular fa-clock me-1"></i>{{ $item->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                    </small>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('tingkat.edit', $item->id) }}" class="action-icon-btn btn-icon-edit" title="Ubah Data">
                                        <i class="fa-solid fa-pen" style="font-size: 12px;"></i>
                                    </a>
                                    <form action="{{ route('tingkat.destroy', $item->id) }}" method="POST" class="delete-form d-inline-block m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon-btn btn-icon-delete" title="Hapus Data">
                                            <i class="fa-solid fa-trash" style="font-size: 12px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state text-center">
                                        <i class="fa-solid fa-folder-open mb-3"></i>
                                        <h6 class="fw-bold text-dark mb-1">Belum ada data tingkat</h6>
                                        <p class="text-muted mx-auto mb-0" style="font-size: 0.85rem; max-width: 300px;">
                                            Silakan tambahkan data tingkat baru melalui tombol 'Tambah Tingkat' di atas.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Info --}}
            <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="text-muted" style="font-size: 0.85rem;">
                    Menampilkan <strong class="text-dark">{{ $tingkats->firstItem() ?? 0 }}</strong> - <strong class="text-dark">{{ $tingkats->lastItem() ?? 0 }}</strong> 
                    dari <strong class="text-dark">{{ number_format($tingkats->total()) }}</strong> data
                </div>
                <div class="pagination-container">
                    {{ $tingkats->withQueryString()->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>

        </div>
    </div>
</div>

{{-- SweetAlert Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Delete Confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Tingkat?',
                text: 'Data yang dihapus tidak dapat dikembalikan lagi.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'rounded-2 px-3 py-2',
                    cancelButton: 'rounded-2 px-3 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Session Notifications
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            confirmButtonColor: '#0ea5e9',
            timer: 2500,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ session('error') }}",
            confirmButtonColor: '#dc3545'
        });
    @endif
</script>
@endsection