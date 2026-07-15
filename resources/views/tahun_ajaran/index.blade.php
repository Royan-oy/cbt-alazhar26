@extends('layouts.app')

@section('title', 'Tahun Ajaran')

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
.page-header-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
    gap: 16px;
}

.page-title h4 {
    font-weight: 800;
    color: var(--text-dark);
    letter-spacing: -0.5px;
}

/* CARDS COMMON */
.custom-dashboard-card {
    border: 1px solid var(--border-color);
    border-radius: 20px;
    background: white;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    overflow: hidden;
}

/* BANNER SEMESTER AKTIF */
.active-status-banner {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-left: 5px solid #10b981 !important;
}

.info-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--text-muted);
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.info-value {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

/* PREMIUM UTILITIES & BADGES */
.btn-premium-action {
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 12px 22px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
}

.btn-premium-action:hover {
    background: var(--primary-hover);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(79, 70, 229, 0.25);
}

.custom-badge {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
}

.badge-active-status {
    background-color: #dcfce7;
    color: #15803d;
}

.badge-inactive-status {
    background-color: #f1f5f9;
    color: #64748b;
}

/* TABLE MODERNIZATION */
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
    padding: 16px 24px;
    font-size: 14px;
    border-bottom: 1px solid #f1f5f9;
}

/* ACTION BUTTONS */
.action-icon-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    border: none;
    transition: all 0.2s;
    margin-right: 4px;
}

.btn-icon-activate { background: #e0f2fe; color: #0369a1; }
.btn-icon-activate:hover { background: #bae6fd; }

.btn-icon-edit { background: #fef3c7; color: #b45309; }
.btn-icon-edit:hover { background: #fde68a; }

.btn-icon-delete { background: #fee2e2; color: #b91c1c; }
.btn-icon-delete:hover { background: #fecaca; }

/* =======================================================
   MEDIA QUERIES (RESPONSIVE VIEW UNTUK HP & TABLET)
   ======================================================= */
@media (max-width: 767.98px) {
    .page-header-wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
    }

    .btn-premium-action {
        width: 100%;
        text-align: center;
    }

    .active-status-banner .row > div {
        margin-bottom: 12px;
    }
    
    .active-status-banner .row > div:last-child {
        margin-bottom: 0;
    }

    .table thead th, .table tbody td {
        padding: 12px 16px;
    }
    
    .table-responsive {
        border: 1px solid var(--border-color);
        border-radius: 12px;
    }
}

.btn-icon-warning{
    background: rgba(245,158,11,.12);
    color:#f59e0b;
}

.btn-icon-warning:hover{
    background:#f59e0b;
    color:#fff;
}
</style>

<div class="container-fluid px-md-4 py-4">

    <div class="page-header-wrapper">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fa-solid fa-calendar-days text-primary me-2"></i>
                Tahun Ajaran
            </h4>
            <p class="text-muted mb-0 small">
                Konfigurasi periode masa bakti dan semester aktif untuk regulasi ujian CBT.
            </p>
        </div>
        <a href="{{ route('tahun-ajaran.create') }}" class="btn btn-premium-action">
            <i class="fa-solid fa-plus me-2"></i> Tambah Tahun Ajaran
        </a>
    </div>

    @php
        $aktif = $tahunAjaran->where('is_aktif', true)->first();
    @endphp

    <div class="card custom-dashboard-card active-status-banner mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center" style="font-size: 14px;">
                <span class="spinner-grow spinner-grow-sm text-success me-2" role="status" style="width: 10px; height: 10px;"></span>
                Periode Aktif Saat Ini
            </h6>

            @if($aktif)
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="info-label">Tahun Ajaran</div>
                        <p class="info-value text-primary">{{ $aktif->nama_tahun }}</p>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Semester Berjalan</div>
                        <p class="info-value">
                            <span class="badge bg-primary px-3 py-2 rounded-3 text-xs">
                                {{ strtoupper($aktif->semester) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">Status Integrasi</div>
                        <p class="info-value">
                            <span class="custom-badge badge-active-status">
                                <i class="fa-solid fa-circle-check me-1"></i> ONLINE & AKTIF
                            </span>
                        </p>
                    </div>
                </div>
            @else
                <div class="alert alert-warning border-0 mb-0 d-flex align-items-center rounded-3">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    <span class="small fw-medium">Belum ada periode instruksi semester yang diaktifkan di sistem.</span>
                </div>
            @endif
        </div>
    </div>

    @if(session('error'))
    <div class="card border-0 shadow-sm mb-4"
        style="border-left:4px solid #dc3545 !important;">
        <div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="me-3 text-danger fs-4">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="fw-bold text-danger">
                        Gagal Mengaktifkan Tahun Ajaran
                    </div>

                    <small class="text-muted">
                        {{ session('error') }}
                    </small>
                </div>

                <button class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="card border-0 shadow-sm mb-4"
        style="border-left:4px solid #198754 !important;">
        <div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="me-3 text-success fs-4">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="fw-bold text-success">
                        Berhasil
                    </div>

                    <small class="text-muted">
                        {{ session('success') }}
                    </small>
                </div>

                <button class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="card custom-dashboard-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Tahun Ajaran</th>
                            <th>Semester</th>
                            <th>Status Registrasi</th>
                            <th>Tanggal Input</th>
                            <th class="text-end">Opsi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tahunAjaran as $item)
                        <tr style="{{ $item->is_aktif ? 'background-color: #f8fafc;' : '' }}">
                            <td class="fw-bold text-dark">
                                {{ $item->nama_tahun }}
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border px-2 py-1.5 font-monospace">
                                    {{ ucfirst($item->semester) }}
                                </span>
                            </td>
                            <td>
                                @if($item->is_aktif)
                                    <span class="custom-badge badge-active-status">
                                        Aktif
                                    </span>
                                @else
                                    <span class="custom-badge badge-inactive-status">
                                        Non-Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $item->created_at->format('d M Y') }}
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex align-items-center gap-1">

                                    @if($item->is_aktif)

                                        <form action="{{ route('tahun-ajaran.nonaktifkan', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="action-icon-btn btn-icon-warning"
                                                    title="Nonaktifkan Tahun Ajaran">
                                                <i class="fa-solid fa-toggle-on"></i>
                                            </button>
                                        </form>

                                    @else

                                        <form action="{{ route('tahun-ajaran.aktifkan', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="action-icon-btn btn-icon-activate"
                                                    title="Aktifkan Tahun Ajaran">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>

                                    @endif

                                    <a href="{{ route('tahun-ajaran.edit', $item->id) }}"
                                    class="action-icon-btn btn-icon-edit"
                                    title="Ubah Data">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form action="{{ route('tahun-ajaran.destroy', $item->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="action-icon-btn btn-icon-delete"
                                                title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted mb-2">
                                    <i class="fa-solid fa-calendar-xmark fa-2x"></i>
                                </div>
                                <h6 class="fw-bold text-secondary mb-1">Database Kosong</h6>
                                <p class="text-muted text-xs mb-0">Belum ada log instansiasi data Tahun Ajaran tersimpan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection