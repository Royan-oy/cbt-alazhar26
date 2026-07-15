@extends('layouts.app')

@section('title', 'Kontrol Jenjang')

@section('content')

<style>
:root {
    --primary-color: #4f46e5;
    --primary-hover: #4338ca;
    --bg-main: #f8fafc;
    --text-dark: #0f172a;
    --text-muted: #64748b;
    --border-color: #e2e8f0;
}

body {
    background: var(--bg-main);
    color: var(--text-dark);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* HEADER SECTION */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    gap: 15px;
}

.page-title h3 {
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 6px;
    letter-spacing: -0.5px;
}

.page-title p {
    color: var(--text-muted);
    font-size: 14px;
    margin: 0;
}

/* PREMIUM UTILITIES */
.btn-premium {
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
}

.btn-premium:hover, .btn-premium:focus {
    background: var(--primary-hover);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(79, 70, 229, 0.25);
}

/* STATISTIC CARD */
.stat-card {
    border: 1px solid var(--border-color);
    border-radius: 20px;
    background: white;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 4px rgba(15, 23, 42, 0.01);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(15, 23, 42, 0.04);
    border-color: #cbd5e1;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    background: rgba(79, 70, 229, 0.08);
    color: var(--primary-color);
}

.stat-value {
    font-size: 32px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1.2;
}

.stat-title {
    color: var(--text-muted);
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* DATA CARD & TABLE */
.custom-card {
    border: 1px solid var(--border-color);
    border-radius: 20px;
    background: white;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
    overflow: hidden;
}

.search-box {
    position: relative;
}

.search-box i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 14px;
}

.search-box input {
    padding-left: 46px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    font-size: 14px;
    background-color: #f8fafc;
    transition: all 0.2s;
}

.search-box input:focus {
    background-color: white;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background: #f8fafc;
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 16px 24px;
    border-bottom: 1px solid var(--border-color);
}

.table tbody td {
    padding: 18px 24px;
    color: #334155;
    font-size: 14px;
    border-bottom: 1px solid #f1f5f9;
}

.table tbody tr {
    transition: background-color 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8fafc;
}

/* BADGES & BUTTONS */
.slug-badge {
    background: #f0fdf4;
    color: #16a34a;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    border: 1px solid #dcfce7;
}

.action-btn {
    width: 38px;
    height: 38px;
    border: none;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.2s;
    margin: 0 2px;
}

.btn-edit-custom {
    background: #fef3c7;
    color: #d97706;
}
.btn-edit-custom:hover {
    background: #fde68a;
    color: #b45309;
}

.btn-delete-custom {
    background: #fee2e2;
    color: #dc2626;
}
.btn-delete-custom:hover {
    background: #fecaca;
    color: #b91c1c;
}

/* MODAL MODERNIZATION */
.modal-content {
    border-radius: 24px;
    border: none;
}
.form-control-lg {
    border-radius: 12px;
    font-size: 15px;
    padding: 12px 16px;
    border: 1px solid var(--border-color);
}
.form-control-lg:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

/* =======================================================
   MEDIA QUERIES (RESPONSIVE VIEW UNTUK HP & TABLET)
   ======================================================= */
@media (max-width: 767.98px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 20px;
    }
    
    .btn-premium {
        width: 100%;
        text-align: center;
        padding: 14px;
    }
    
    .stat-card {
        padding: 16px !important;
    }
    
    .stat-value {
        font-size: 26px;
    }

    .custom-card .card-body {
        padding: 16px;
    }
    
    .table thead th {
        padding: 12px 16px;
    }
    
    .table tbody td {
        padding: 14px 16px;
    }
    
    /* Membuat scroll table horizontal terasa lebih natural di HP */
    .table-responsive {
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }
}
</style>

<div class="container-fluid px-md-4 py-4">

    <div class="page-header">
        <div class="page-title">
            <h3>Kontrol Jenjang</h3>
            <p>Kelola seluruh tingkatan jenjang sekolah pada ekosistem CBT Anda.</p>
        </div>
        <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#modalTambahJenjang">
            <i class="fa-solid fa-plus me-2"></i> Tambah Jenjang
        </button>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-md-4">
            <div class="stat-card p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-title">Total Jenjang Terdaftar</div>
                        <div class="stat-value">{{ $jenjangs->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card custom-card">
        <div class="card-body p-4">
            
            <div class="row mb-4">

                <div class="col-lg-5">

                    <form action="{{ route('jenjang.index') }}" method="GET">

                        <div class="search-box">

                            <i class="fa fa-search"></i>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Cari nama jenjang...">

                        </div>

                    </form>

                </div>

            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Jenjang</th>
                            <th>Slug Sistem</th>
                            <th>Tanggal Dibuat</th>
                            <th width="140" class="text-center">Aksi Terintegrasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jenjangs as $item)
                        <tr>
                            <td class="text-muted fw-medium">{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ $item->nama_jenjang }}</span>
                            </td>
                            <td>
                                <span class="slug-badge">
                                    <code>{{ $item->slug }}</code>
                                </span>
                            </td>
                            <td class="text-secondary">
                                {{ $item->created_at->format('d M Y') }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('jenjang.edit', $item->slug) }}"
                                class="btn action-btn btn-edit-custom"
                                data-bs-toggle="tooltip"
                                title="Edit Jenjang">

                                    <i class="fa fa-edit"></i>

                                </a>
                                <form action="{{ route('jenjang.destroy', $item->slug) }}"
                                    method="POST"
                                    class="d-inline form-delete">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn action-btn btn-delete-custom"
                                            title="Hapus Jenjang">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="text-center py-5 my-3">
                                    <div class="mb-3 text-muted" style="opacity: 0.6;">
                                        <i class="fa-solid fa-folder-open fa-3x"></i>
                                    </div>
                                    <h5 class="fw-bold text-secondary">Belum ada data jenjang</h5>
                                    <p class="text-muted text-sm max-w-xs mx-auto">
                                        Silakan tambahkan kategori jenjang sekolah baru menggunakan tombol di atas.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($jenjangs->count())
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">Menampilkan data tersimpan</small>
                <div>
                    {{ $jenjangs->withQueryString()->links() }}
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahJenjang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 pt-4 px-4 pb-2">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Tambah Jenjang Baru</h5>
                    <p class="text-muted text-sm mb-0">Sistem otomatis memformat slug berdasarkan nama.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('jenjang.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 pt-3 pb-2">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-sm text-secondary mb-2">Nama Jenjang</label>
                        <input 
                            type="text" 
                            name="nama_jenjang" 
                            class="form-control form-control-lg @error('nama_jenjang') is-invalid @enderror" 
                            placeholder="Misal: SD, SMP, SMA, SMK"
                            required>
                        
                        @error('nama_jenjang')
                        <div class="invalid-feedback mt-2">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="p-3 bg-light rounded-3 d-flex align-items-start border">
                        <i class="fa-solid fa-circle-info mt-1 text-primary me-2ff"></i>
                        <small class="text-muted style-sm" style="font-size: 12px; margin-left:6px;">
                            <strong>Catatan:</strong> Pastikan singkatan atau nama unik agar mempermudah pemetaan kelas nantinya.
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4 pt-3">
                    <button type="button" class="btn btn-link text-decoration-none text-secondary fw-semibold m-0 me-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-premium px-4 m-0">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function(){
    var modal = new bootstrap.Modal(document.getElementById('modalTambahJenjang'));
    modal.show();
});
</script>
@endif

@if(session('success'))

<script>
document.addEventListener('DOMContentLoaded', function () {

    Swal.fire({

        icon: 'success',

        title: 'Berhasil',

        text: "{{ session('success') }}",

        confirmButtonColor: '#4f46e5',

        confirmButtonText: 'OK',

        timer: 2500,
        timerProgressBar: true

    });

});
</script>

@endif

<script>
document.querySelectorAll('.form-delete').forEach(function(form){

    form.addEventListener('submit', function(e){

        e.preventDefault();

        Swal.fire({

            title: 'Hapus Jenjang?',

            text: 'Data yang sudah dihapus tidak dapat dikembalikan.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#dc3545',

            cancelButtonColor: '#6c757d',

            confirmButtonText: 'Ya, Hapus',

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

<script>
let timer;

const search = document.querySelector('input[name="search"]');

search.addEventListener('keyup', function(){

    clearTimeout(timer);

    timer = setTimeout(() => {

        this.form.submit();

    }, 500);

});
</script>
@endsection