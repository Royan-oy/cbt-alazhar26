@extends('layouts.app')

@section('title', 'Data Tingkat')

@section('content')

<style>
    /* Global Card & Layout Refinement */
    body {
        background-color: #f8fafc;
    }

    /* Page Header Modern Dashboard Style */
    .page-header {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        border-radius: 20px;
        padding: 35px;
        position: relative;
        overflow: hidden;
        color: #fff;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.1);
    }

    .page-header::after {
        content: '';
        position: absolute;
        right: -50px;
        top: -50px;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.15) 0%, rgba(56, 189, 248, 0) 70%);
    }

    .page-header::after{
        pointer-events:none;
    }

    .page-header{
        position:relative;
    }

    .page-header > *{
        position:relative;
        z-index:2;
    }
    
    .page-header h3 {
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    /* Modern Minimalist Stat Card */
    .stat-card {
        border: 1px solid rgba(241, 245, 249, 0.8);
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background: #f0fdf4;
        color: #16a34a;
    }

    /* Main Content Card */
    .content-card {
        border: none;
        border-radius: 24px;
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.04);
        background: #ffffff;
    }

    /* Enhanced Inputs */
    .search-box, .form-select {
        height: 48px;
        border-radius: 14px;
        border: 1.5px solid #e2e8f0;
        padding-left: 16px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .search-box:focus, .form-select:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
    }

    /* Table Styling */
    .table-responsive {
        border-radius: 16px;
    }

    .table thead th {
        background-color: #f8fafc;
        border-bottom: 2px solid #f1f5f9;
        color: #64748b;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 16px 20px;
        font-weight: 700;
    }

    .table tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        border-color: #f1f5f9;
        color: #334155;
        font-size: 0.95rem;
    }

    /* Elegant Custom Badges */
    .badge-jenjang {
        background: #f0f9ff;
        color: #0369a1;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        border: 1px solid #e0f2fe;
    }

    /* Smooth Action Buttons */
    .action-icon-btn {
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        margin-left: 8px;
    }

    .btn-icon-edit {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-icon-edit:hover {
        background: #0ea5e9;
        color: white;
        transform: scale(1.05);
    }

    .btn-icon-delete {
        background: #fff5f5;
        color: #e11d48;
    }

    .btn-icon-delete:hover {
        background: #e11d48;
        color: white;
        transform: scale(1.05);
    }

    /* Custom Button Layout */
    .btn-add {
        border-radius: 14px;
        padding: 12px 24px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
        transition: all 0.2s;
    }
    
    .btn-add:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(14, 165, 233, 0.3);
    }

    /* Empty State Minimalist */
    .empty-state {
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 64px;
        background: linear-gradient(135deg, #cbd5e1, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .empty-state h5 {
        margin-top: 24px;
        font-weight: 700;
        color: #334155;
    }

    /* ================= Pagination ================= */

    .pagination-container .pagination{
        margin-bottom:0;
        gap:6px;
    }

    .pagination-container .page-link{
        border:none;
        border-radius:10px;
        padding:8px 14px;
        color:#475569;
        background:#ffffff;
        font-weight:600;
        box-shadow:0 2px 8px rgba(15,23,42,.05);
        transition:.25s;
    }

    .pagination-container .page-link:hover{
        background:#2563eb;
        color:#fff;
    }

    .pagination-container .page-item.active .page-link{
        background:#2563eb;
        border-color:#2563eb;
        color:#fff;
    }

    .pagination-container .page-item.disabled .page-link{
        background:#f8fafc;
        color:#94a3b8;
    }

    @media(max-width:768px){

        .pagination-container{
            width:100%;
            display:flex;
            justify-content:center;
        }

    }

    /* --- RESPONSIVE MEDIA QUERIES (HP & Tablet) --- */
    @media (max-width: 991.98px) {
        .search-action {
            margin-top: 0px;
        }
    }

    @media (max-width: 767.98px) {
        .page-header {
            padding: 24px;
            text-align: center;
        }

        .page-header .d-flex {
            flex-direction: column !important;
            gap: 20px;
        }

        .page-header .btn-add {
            width: 100%;
            justify-content: center;
        }

        .stat-card {
            margin-bottom: 16px;
        }

        .table {
            min-width: 750px; /* Menjaga tabel tetap rapi dengan scroll horizontal di HP */
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        .action-icon-btn {
            width: 44px; /* Sedikit lebih besar di HP agar mudah ditekan jari */
            height: 44px;
        }
    }
</style>

<div class="container-fluid py-4">

    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info mb-2 px-3 py-2 rounded-pill fw-semibold">
                    Master Akademik
                </span>
                <h3 class="mb-1">Data Tingkat</h3>
                <p class="text-light opacity-75 mb-0 small">
                    Kelola seluruh tingkat berdasarkan jenjang pendidikan.
                </p>
            </div>
            <a href="{{ route('tingkat.create') }}"
   class="btn btn-info text-white btn-add d-inline-flex align-items-center justify-content-center">

    <i class="fa-solid fa-plus me-2"></i>
    <span>Tambah Tingkat</span>

</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fa-solid fa-school"></i>
                    </div>
                    <div class="ms-3">
                        <small class="text-muted d-block fw-semibold tracking-wider" style="font-size: 11px;">
                            TOTAL TINGKAT
                        </small>
                        <h3 class="mb-0 fw-bold text-dark">
                            {{ $tingkats->total() }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card content-card">
        <div class="card-body p-4">
            
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-lg-4 col-md-6">
                        <div class="input-group">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control search-box" placeholder="Cari nama tingkat...">
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <select name="jenjang" class="form-select">
                            <option value="">Semua Jenjang</option>
                            @foreach($jenjangs as $jenjang)
                                <option value="{{ $jenjang->id }}" {{ request('jenjang') == $jenjang->id ? 'selected' : '' }}>
                                    {{ $jenjang->nama_jenjang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <button class="btn btn-primary w-100 rounded-3 fw-semibold d-flex align-items-center justify-content-center" style="height: 48px; background: #2563eb; border: none;">
                            <i class="fa fa-search me-2"></i> Cari
                        </button>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <a href="{{ route('tingkat.index') }}" class="btn btn-light w-100 rounded-3 fw-semibold d-flex align-items-center justify-content-center" style="height: 48px; border: 1px solid #e2e8f0; background: #fff;">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            @if(session('success'))
                <div class="alert alert-success rounded-4 border-0 p-3 mb-4 d-flex align-items-center bg-opacity-10 bg-success text-success">
                    <i class="fa-solid fa-circle-check me-2 fs-5"></i>
                    <div class="fw-semibold">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger rounded-4 border-0 p-3 mb-4 d-flex align-items-center bg-opacity-10 bg-danger text-danger">
                    <i class="fa-solid fa-circle-xmark me-2 fs-5"></i>
                    <div class="fw-semibold">{{ session('error') }}</div>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="80" class="text-center">No</th>
                            <th>Jenjang</th>
                            <th>Nama Tingkat</th>
                            <th>Dibuat</th>
                            <th class="text-end" width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tingkats as $item)
                            <tr>
                                <td class="text-center fw-semibold text-muted">
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
                                    <div class="fw-semibold">{{ $item->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}</div>
                                    <small class="text-muted d-block mt-0.5">
                                        <i class="fa-regular fa-clock me-1" style="font-size: 11px;"></i>{{ $item->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                    </small>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex justify-content-end w-100">
                                        <a href="{{ route('tingkat.edit', $item->id) }}" class="action-icon-btn btn-icon-edit" title="Ubah Data">
                                            <i class="fa-solid fa-pen" style="font-size: 14px;"></i>
                                        </a>
                                        <form action="{{ route('tingkat.destroy', $item->id) }}"
                                            method="POST"
                                            class="delete-form m-0">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="action-icon-btn btn-icon-delete"
                                                    title="Hapus Data">

                                                <i class="fa-solid fa-trash" style="font-size:14px;"></i>

                                            </button>

                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state text-center">
                                        <i class="fa-solid fa-folder-open mb-3"></i>
                                        <h5>Belum ada data tingkat</h5>
                                        <p class="text-muted mx-auto" style="max-width: 360px; font-size: 0.9rem;">
                                            Silakan tambahkan data tingkat baru melalui tombol 'Tambah Tingkat' di atas.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                <div class="small text-muted fw-semibold">
                    Menampilkan
                    <strong>{{ $tingkats->firstItem() ?? 0 }}</strong>
                    -
                    <strong>{{ $tingkats->lastItem() ?? 0 }}</strong>
                    dari
                    <strong>{{ number_format($tingkats->total()) }}</strong>
                    data tingkat
                </div>

                <div class="pagination-container">
                    {{ $tingkats->withQueryString()->links('vendor.pagination.bootstrap-4') }}
                </div>

            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.delete-form').forEach(form => {

    form.addEventListener('submit', function(e){

        e.preventDefault();

        Swal.fire({

            title: 'Hapus Tingkat?',
            text: 'Data yang dihapus tidak dapat dikembalikan lagi.',
            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',

            confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',

            reverseButtons: true

        }).then((result) => {

            if(result.isConfirmed){
                form.submit();
            }

        });

    });

});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: "{{ session('success') }}",
    confirmButtonColor: '#0d6efd'
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: "{{ session('error') }}",
    confirmButtonColor: '#dc3545'
});
</script>
@endif
@endsection