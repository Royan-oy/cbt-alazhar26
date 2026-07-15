@extends('layouts.app')

@section('title', 'Detail Kelas')

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
        transition: transform 0.2s;
    }

    .stat-card:hover { transform: translateY(-2px); }

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
        transition: all 0.2s;
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

    .btn-back {
        background: rgba(255,255,255,0.1);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 14px;
        padding: 10px 18px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.2);
        color: #fff;
    }

    @media (max-width: 768px) {
        .page-header { padding: 24px; border-radius: 18px; text-align: center; }
        .page-header .d-flex { flex-direction: column; gap: 16px; }

        .table-responsive table,
        .table-responsive thead,
        .table-responsive tbody,
        .table-responsive th,
        .table-responsive td,
        .table-responsive tr { display: block; }

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
        .table-responsive td:nth-of-type(2):before { content: "Nama Siswa"; }
        .table-responsive td:nth-of-type(3):before { content: "NIS"; }
        .table-responsive td:nth-of-type(4):before { content: "NISN"; }

        .pagination { justify-content: center !important; }
    }
</style>

<div class="container-fluid py-2">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    DETAIL KELAS
                </span>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
                    {{ $kelas->nama_kelas }}
                </h3>
                <p class="text-light opacity-75 mb-0 small">
                    {{ $kelas->tingkat->jenjang->nama_jenjang ?? '-' }} &middot; Tingkat {{ $kelas->tingkat->nama_tingkat ?? '-' }}
                    @if($tahunAjaranAktif)
                        &middot; Tahun Ajaran {{ $tahunAjaranAktif->nama_tahun }} ({{ ucfirst($tahunAjaranAktif->semester) }})
                    @endif
                </p>
            </div>

            <a href="{{ route('kelas.index') }}" class="btn-back d-inline-flex align-items-center">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Kembali
            </a>
        </div>
    </div>

    {{-- Widget Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <div>
                    <small class="text-muted d-block uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL SISWA</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $totalSiswa }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <small class="text-muted d-block uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">TINGKAT</small>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $kelas->tingkat->nama_tingkat ?? '-' }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="card content-card">
        <div class="card-body">

            {{-- Search Form --}}
            <form method="GET" action="{{ route('kelas.show', $kelas->id) }}">
                <div class="row g-3 mb-4 align-items-center">
                    <div class="col-lg-4">
                        <input
                            type="text"
                            name="search"
                            class="form-control form-control-custom"
                            placeholder="Cari nama siswa..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-auto">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark btn-action-trigger">
                                <i class="fa fa-search me-2"></i>
                                Cari
                            </button>

                            @if(request()->filled('search'))
                                <a href="{{ route('kelas.show', $kelas->id) }}" class="btn btn-light border btn-action-trigger">
                                    <i class="fa-solid fa-rotate"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

            {{-- Table Siswa --}}
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th width="70">No</th>
                            <th>Nama Siswa</th>
                            <th>NIS</th>
                            <th>NISN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaKelas as $item)
                        <tr>
                            <td>
                                <span class="text-secondary fw-semibold">
                                    {{ $loop->iteration + ($siswaKelas->firstItem() - 1) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($item->siswa && $item->siswa->foto)
                                        <img src="{{ asset('storage/' . $item->siswa->foto) }}"
                                            alt="{{ $item->siswa->nama }}"
                                            class="rounded-circle"
                                            style="width: 38px; height: 38px; object-fit: cover; flex-shrink: 0;">
                                    @else
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 38px; height: 38px; flex-shrink: 0; font-size: 14px;">
                                            {{ $item->siswa ? strtoupper(substr($item->siswa->nama, 0, 1)) : '?' }}
                                        </div>
                                    @endif
                                    <div class="fw-bold text-dark fs-6">{{ $item->siswa->nama ?? '-' }}</div>
                                </div>
                            </td>
                            <td>{{ $item->siswa->nis ?? '-' }}</td>
                            <td>{{ $item->siswa->nisn ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-user-slash fa-3x text-muted mb-3 opacity-50"></i>
                                    <h6 class="fw-bold text-secondary">Belum ada siswa di kelas ini</h6>
                                    <small class="text-muted">Siswa akan muncul di sini setelah ditempatkan pada kelas.</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-4 pagination-container">
                {{ $siswaKelas->links('vendor.pagination.bootstrap-4') }}
            </div>

        </div>
    </div>
</div>

@endsection