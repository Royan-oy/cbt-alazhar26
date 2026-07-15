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

    .btn-icon-info { background: #eff6ff; color: #2563eb; }
    .btn-icon-info:hover { background: #2563eb; color: white; }

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
        .page-header .d-flex { flex-direction: column; gap: 20px; }
        .btn-add { width: 100%; justify-content: center; }
        .form-control-custom, .btn-action-trigger { width: 100%; margin-bottom: 8px; }
        .content-card { padding: 4px; border-radius: 18px; }

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
        .table-responsive td:nth-of-type(2):before { content: "Nama"; }
        .table-responsive td:nth-of-type(3):before { content: "NIP"; }
        .table-responsive td:nth-of-type(4):before { content: "Jenjang"; }
        .table-responsive td:nth-of-type(5):before { content: "Aksi"; }

        .pagination { justify-content: center !important; }
    }

    /* ===========================
    PREMIUM ACTION BUTTON
    =========================== */

    .action-buttons{
        display:flex;
        gap:12px;
        flex-wrap:wrap;
    }

    .btn-action{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;

        border:none;
        border-radius:14px;

        padding:11px 20px;

        font-weight:600;
        font-size:.92rem;

        transition:.25s ease;

        text-decoration:none;

        box-shadow:0 8px 18px rgba(15,23,42,.08);
    }

    .btn-action i{
        font-size:15px;
    }

    .btn-action:hover{

        transform:translateY(-3px);

        text-decoration:none;

        box-shadow:0 16px 28px rgba(15,23,42,.16);

    }

    /* Download */

    .btn-template{

        background:#fff;

        color:#334155;

        border:1px solid #e2e8f0;

    }

    .btn-template:hover{

        background:#f8fafc;

        color:#0f172a;

    }

    /* Import */

    .btn-import{

        background:linear-gradient(135deg,#16a34a,#15803d);

        color:#fff;

    }

    .btn-import:hover{

        color:#fff;

        background:linear-gradient(135deg,#15803d,#166534);

    }

    /* Tambah */

    .btn-add-guru{

        background:linear-gradient(135deg,#2563eb,#1d4ed8);

        color:#fff;

    }

    .btn-add-guru:hover{

        color:#fff;

        background:linear-gradient(135deg,#1d4ed8,#1e40af);

    }

    @media(max-width:768px){

        .action-buttons{

            width:100%;

        }

        .btn-action{

            width:100%;

        }

    }

    /* ==========================================
    PREMIUM IMPORT MODAL
    ========================================== */

    #importGuruModal .modal-dialog{
        max-width:700px;
    }

    #importGuruModal .modal-content{
        border:none;
        border-radius:24px;
        overflow:hidden;
        box-shadow:0 25px 60px rgba(15,23,42,.18);
    }

    #importGuruModal .modal-header{

        background:linear-gradient(135deg,#15803d,#16a34a);

        border:none;

        padding:22px 28px;

    }

    #importGuruModal .modal-title{

        font-size:1.15rem;

        font-weight:700;

        display:flex;

        align-items:center;

        gap:10px;

    }

    #importGuruModal .modal-title i{

        width:42px;
        height:42px;

        border-radius:12px;

        display:flex;
        align-items:center;
        justify-content:center;

        background:rgba(255,255,255,.15);

        font-size:18px;

    }

    #importGuruModal .btn-close{

        opacity:1;

    }

    #importGuruModal .modal-body{

        padding:30px;

        background:#f8fafc;

    }

    /* Alert */

    .import-alert{

        background:#eef9f1;

        border:1px solid #b7e4c7;

        border-radius:18px;

        padding:20px;

    }

    .import-alert strong{

        color:#166534;

        font-size:15px;

    }

    .import-alert ul{

        margin-top:12px;

        padding-left:18px;

    }

    .import-alert li{

        color:#475569;

        margin-bottom:8px;

    }

    /* Upload Area */

    .upload-box{

        margin-top:22px;

        border:2px dashed #16a34a;

        border-radius:18px;

        background:white;

        padding:35px;

        text-align:center;

        transition:.3s;

    }

    .upload-box:hover{

        background:#f0fdf4;

        border-color:#15803d;

    }

    .upload-box i{

        font-size:55px;

        color:#16a34a;

        margin-bottom:15px;

    }

    .upload-box h6{

        font-weight:700;

        margin-bottom:8px;

    }

    .upload-box p{

        color:#64748b;

        font-size:14px;

        margin-bottom:18px;

    }

    .upload-box input{

        max-width:360px;

        margin:auto;

    }

    /* Footer */

    #importGuruModal .modal-footer{

        background:#fff;

        border-top:1px solid #edf2f7;

        padding:20px 28px;

    }

    /* Button */

    .btn-modal-cancel{

        border-radius:12px;

        padding:10px 22px;

        font-weight:600;

    }

    .btn-modal-import{

        border:none;

        border-radius:12px;

        padding:10px 22px;

        font-weight:600;

        background:linear-gradient(135deg,#16a34a,#15803d);

        color:white;

        transition:.25s;

    }

    .btn-modal-import:hover{

        transform:translateY(-2px);

        box-shadow:0 10px 25px rgba(22,163,74,.35);

        color:white;

    }

    @media(max-width:768px){

        #importGuruModal .modal-body{

            padding:20px;

        }

        .upload-box{

            padding:25px 18px;

        }

    }
</style>

<div class="container-fluid py-2">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
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

            <div class="action-buttons">

                <a href="{{ route('guru.template') }}"
                class="btn-action btn-template">

                    <i class="fa-solid fa-download"></i>

                    Download Template

                </a>

                <button
                    type="button"
                    class="btn-action btn-import"
                    data-bs-toggle="modal"
                    data-bs-target="#importGuruModal">

                    <i class="fa-solid fa-file-excel"></i>

                    Import Excel

                </button>

                <a href="{{ route('guru.create') }}"
                class="btn-action btn-add-guru">

                    <i class="fa-solid fa-plus"></i>

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
                    <small class="text-muted d-block uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL GURU</small>
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
                                <a href="{{ route('guru.index') }}" class="btn btn-light border btn-action-trigger">
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
                                <span class="badge bg-dark bg-opacity-10 text-dark px-2 py-1.5 rounded-3 fw-semibold">
                                    {{ optional($item->jenjang)->nama_jenjang ?? '-' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex">

                                    <a href="{{ route('guru.show', $item->id) }}"
                                        class="action-icon-btn btn-icon-info"
                                        title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <a href="{{ route('guru.edit', $item->id) }}"
                                        class="action-icon-btn btn-icon-edit"
                                        title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form action="{{ route('guru.destroy', $item->id) }}"
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
<div class="modal fade"
     id="importGuruModal"
     tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content border-0 rounded-4 shadow">

            <form
                action="{{ route('guru.import') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="modal-header bg-success text-white">

                    <h5 class="modal-title">

                        <i class="fa-solid fa-file-excel me-2"></i>

                        Import Data Guru

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

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

                        <p>
                            Klik tombol di bawah untuk memilih file Excel yang akan diimport.
                        </p>

                        <input
                            type="file"
                            name="file"
                            class="form-control"
                            accept=".xlsx,.xls"
                            required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light btn-modal-cancel"
                        data-bs-dismiss="modal">

                        <i class="fa-solid fa-xmark me-2"></i>

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn-modal-import">

                        <i class="fa-solid fa-upload me-2"></i>

                        Import Data Guru

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
@endsection