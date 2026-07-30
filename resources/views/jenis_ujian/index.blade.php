@extends('layouts.app')

@section('title','Jenis Ujian')

@section('content')

<style>
    /* Global Content Variable & Layout */
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --bg-body: #f8fafc;
    }

    /* Page Header Overhaul */
    .page-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        border-radius: 20px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
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

    /* Modern Card Layout */
    .content-card {
        background: var(--surface-white);
        border-radius: 20px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        padding: 20px;
        margin-top: 24px;
    }

    /* Refined Search Box */
    .search-box {
        border-radius: 12px;
        height: 48px;
        border: 1px solid var(--border-color);
        padding-left: 20px;
        font-size: 14px;
        transition: all 0.2s ease-in-out;
        background-color: var(--bg-body);
    }

    .search-box:focus {
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
        background-color: var(--surface-white);
        outline: none;
    }

    /* Modern Buttons */
    .btn-add {
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        letter-spacing: 0.3px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(14, 165, 233, 0.3);
    }

    .btn-search {
        border-radius: 12px;
        height: 48px;
        padding: 0 24px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 41, 59, 0.2);
    }

    /* Table Design Refresh */
    .table-responsive {
        border-radius: 16px;
        border: 1px solid var(--border-color);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        background-color: var(--bg-body);
        padding: 18px 16px;
        border-bottom: 2px solid var(--border-color);
        font-weight: 700;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 18px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #334155;
    }

    /* Table Row Hover Animation */
    .table tbody tr {
        transition: background-color 0.2s;
    }
    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Status Badges */
    .badge-status {
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .badge-active {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    .badge-nonactive {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    /* Modern Action Buttons */
    .action-icon-btn {
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .action-icon-btn:hover {
        transform: translateY(-2px);
    }

    .btn-icon-edit { background: #f0fdfa; color: #0d9488; }
    .btn-icon-edit:hover { background: #0d9488; color: white; }

    .btn-icon-delete { background: #fff5f5; color: #e11d48; }
    .btn-icon-delete:hover { background: #e11d48; color: white; }

    .btn-icon-status-off { background: #f0f9ff; color: #0284c7; }
    .btn-icon-status-off:hover { background: #0284c7; color: white; }
    
    .btn-icon-status-on { background: #fef3c7; color: #d97706; }
    .btn-icon-status-on:hover { background: #d97706; color: white; }

    /* Custom Modern Pagination Styling */
    .pagination {
        gap: 4px;
        margin-bottom: 0;
    }

    .page-item .page-link {
        border-radius: 10px !important;
        border: 1px solid transparent;
        color: var(--secondary-dark);
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.2s;
        background-color: var(--bg-body);
    }

    .page-item.active .page-link {
        background-color: var(--secondary-dark);
        border-color: var(--secondary-dark);
        color: white;
        box-shadow: 0 4px 10px rgba(30, 41, 59, 0.15);
    }

    .page-item .page-link:hover:not(.active) {
        background-color: #e2e8f0;
        color: var(--primary-dark);
    }

    /* --- CSS MEDIA QUERIES (RESPONSIVE VIEW) --- */
    @media (max-width: 768px) {
        .page-header {
            padding: 24px 20px;
            border-radius: 16px;
            text-align: center;
        }

        .page-header .d-flex {
            flex-direction: column;
            gap: 16px;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .content-card {
            padding: 16px;
            border-radius: 16px;
        }

        .search-box {
            margin-bottom: 0;
        }

        .btn-search {
            width: 100%;
        }

        /* Responsive Scrollable Table */
        .table tbody td {
            white-space: nowrap; /* Mencegah teks turun ke bawah dan menjaga tabel rapi saat discroll */
        }

        .table-responsive {
            border-radius: 12px;
            margin-top: 8px;
        }

        .action-icon-btn {
            width: 34px;
            height: 34px;
            margin-left: 4px;
        }

        .pagination-container {
            justify-content: center !important;
            margin-top: 24px !important;
        }
    }
</style>

<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    MASTER DATA
                </span>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
                    Jenis Ujian
                </h3>
                <p class="text-light opacity-75 mb-0 small">
                    Kelola seluruh jenis ujian yang digunakan pada sistem CBT.
                </p>
            </div>

            <a href="{{ route('jenis-ujian.create') }}" class="btn btn-info text-white btn-add d-inline-flex align-items-center">
                <i class="fa-solid fa-plus me-2"></i>
                Tambah Jenis Ujian
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm d-flex align-items-center p-3 mb-4">
        <i class="fa-solid fa-circle-check fs-5 me-2"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    {{-- Main Content Card --}}
    <div class="card content-card border-0">
        <div class="card-body p-0">
            
            {{-- Search Filter Section --}}
            <form method="GET">
                <div class="row g-3 mb-4 align-items-center">
                    <div class="col-12 col-md-5">
                        <input type="text" name="search" class="form-control search-box" placeholder="Cari berdasarkan nama atau kode..." value="{{ request('search') }}">
                    </div>
                    <div class="col-12 col-md-auto">
                        <button class="btn btn-dark btn-search d-inline-flex align-items-center justify-content-center">
                            <i class="fa fa-search me-2"></i> Cari
                        </button>
                    </div>
                </div>
            </form>

            {{-- Table View --}}
            <div class="table-responsive shadow-sm">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th width="60" class="text-center">No</th>
                            <th width="120">Kode</th>
                            <th>Nama Jenis Ujian</th>
                            <th>Deskripsi</th>
                            <th width="170">Dibuat</th>
                            <th width="130">Status</th>
                            <th width="160" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($jenisUjians as $item)
                    <tr>
                        <td class="text-center">
                            <span class="text-secondary fw-semibold">{{ $loop->iteration + ($jenisUjians->firstItem() - 1) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-dark px-3 py-2 rounded-pill fw-bold" style="font-size: 13px;">
                                {{ $item->kode }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->nama }}</div>
                        </td>
                        <td>
                            <span class="text-muted">{{ $item->deskripsi ?: '-' }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item->created_at->format('d M Y') }}</div>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($item->created_at)->setTimezone('Asia/Jakarta')->format('H:i') }} WIB
                            </small>
                        </td>
                        <td>
                            @if($item->aktif)
                                <span class="badge-status badge-active">
                                    <span class="spinner-grow spinner-grow-sm text-success" style="width: 6px; height: 6px;" role="status"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="badge-status badge-nonactive">
                                    <span class="badge bg-danger rounded-circle p-1" style="width: 6px; height: 6px; display:inline-block;"></span>
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex">
                                @if($item->aktif)
                                    <form action="{{ route('jenis-ujian.nonaktifkan',$item->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-icon-btn btn-icon-status-on" title="Nonaktifkan">
                                            <i class="fa-solid fa-toggle-on fs-5"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('jenis-ujian.aktifkan',$item->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-icon-btn btn-icon-status-off" title="Aktifkan">
                                            <i class="fa-solid fa-toggle-off fs-5"></i>
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('jenis-ujian.edit',$item->id) }}" class="action-icon-btn btn-icon-edit" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form action="{{ route('jenis-ujian.destroy',$item->id) }}" method="POST" class="form-delete d-inline m-0">
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
                        <td colspan="7">
                            <div class="text-center py-5">
                                <i class="fa-solid fa-folder-open fa-3x text-muted mb-3 opacity-25"></i>
                                <h6 class="fw-bold text-secondary">Belum ada data jenis ujian</h6>
                                <p class="text-muted small mb-0">Silakan tambahkan jenis ujian terlebih dahulu.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Custom Styled Pagination Wrap --}}
            <div class="d-flex justify-content-end mt-4 pagination-container">
                {{ $jenisUjians->links('vendor.pagination.bootstrap-4') }}
            </div>

        </div>
    </div>
</div>

{{-- SweetAlert Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.form-delete').forEach(function(form){
    form.addEventListener('submit',function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Hapus Data?',
            text: 'Data jenis ujian yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-4'
            }
        }).then((result)=>{
            if(result.isConfirmed){
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
    text: '{!! session('success') !!}',
    timer: 2200,
    showConfirmButton: false,
    customClass: { popup: 'rounded-4' }
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: '{!! session('error') !!}',
    customClass: { popup: 'rounded-4' }
});
</script>
@endif
@endsection