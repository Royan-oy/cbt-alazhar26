@extends('layouts.app')

@section('title', 'Data Kelas - Wali Kelas')

@section('content')
<style>
    :root {
        --accent-blue: #3b82f6;
        --border-color: #e2e8f0;
        --bg-hover: #f8fafc;
    }

    .page-header-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border: none;
        border-radius: 1.25rem;
        overflow: hidden;
        position: relative;
    }
    .page-header-card::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 280px; height: 280px;
        background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .stat-pill {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 0.75rem;
        padding: 0.75rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 140px;
    }
    .stat-pill .stat-icon {
        width: 40px; height: 40px;
        background: rgba(59,130,246,0.2);
        border-radius: 0.625rem;
        display: flex; align-items: center; justify-content: center;
        color: #60a5fa;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .search-box {
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
        padding: 0.65rem 1rem 0.65rem 2.75rem;
        font-size: 0.875rem;
        background: #ffffff;
        transition: border-color 0.2s, box-shadow 0.2s;
        width: 100%;
    }
    .search-box:focus {
        outline: none;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }
    .search-wrapper {
        position: relative;
    }
    .search-wrapper .search-icon {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
        pointer-events: none;
    }

    .students-table-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        overflow: hidden;
    }
    .students-table-card table thead th {
        background-color: #f8fafc;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }
    .students-table-card table tbody tr {
        transition: background-color 0.15s ease;
    }
    .students-table-card table tbody tr:hover {
        background-color: var(--bg-hover);
    }
    .students-table-card table tbody td {
        padding: 0.875rem 1rem;
        font-size: 0.875rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .avatar-student {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .badge-no {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px; height: 28px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        border-radius: 50%;
    }

    @media (max-width: 767.98px) {
        .page-header-card { padding: 1.5rem !important; }
        .stat-pill { min-width: 120px; }
    }
</style>

<div class="container-fluid px-0 py-2">

    {{-- PAGE HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-card p-4 p-md-5">
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                    <div class="flex-grow-1">
                        <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill mb-3 d-inline-flex align-items-center gap-1"
                              style="font-size: 11px; font-weight: 600;">
                            <i class="fa-solid fa-users me-1"></i>
                            Data Kelas — Wali Kelas
                        </span>
                        <h1 class="fw-bold text-white mb-1" style="font-size: 1.75rem; letter-spacing: -0.5px;">
                            Kelas {{ optional($kelas)->nama_kelas ?? '-' }}
                        </h1>
                        <p class="text-white text-opacity-60 mb-0" style="font-size: 13px;">
                            {{ optional($kelas)->nama_tingkat ?? '' }}
                            &bull; Tahun Ajaran {{ optional($waliKelas->tahunAjaran)->nama_tahun ?? '-' }}
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <div class="stat-pill">
                            <div class="stat-icon">
                                <i class="fa-solid fa-user-graduate"></i>
                            </div>
                            <div>
                                <div class="text-white fw-bold fs-5 lh-1">{{ $totalSiswa }}</div>
                                <div class="text-white text-opacity-50" style="font-size: 11px;">Total Siswa</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="row mb-3">
        <div class="col-12 col-md-5">
            <form method="GET" action="{{ route('dashboard-guru.wali-kelas.data-kelas') }}">
                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="search-box"
                        placeholder="Cari nama, NIS, atau NISN siswa..."
                        id="searchInput"
                    >
                </div>
            </form>
        </div>
    </div>

    {{-- SESSION MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 mb-3 d-flex align-items-center gap-2" style="font-size: 14px;">
            <i class="fa-solid fa-circle-check text-success"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-3 mb-3 d-flex align-items-center gap-2" style="font-size: 14px;">
            <i class="fa-solid fa-circle-exclamation text-danger"></i> {{ session('error') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="students-table-card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 48px;">No</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $index => $siswa)
                    <tr>
                        <td><span class="badge-no">{{ $siswas->firstItem() + $index }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-student">
                                    {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                                </div>
                                <span class="fw-semibold text-dark">{{ $siswa->nama }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $siswa->nis ?? '-' }}</td>
                        <td class="text-muted">{{ $siswa->nisn ?? '-' }}</td>
                        <td class="text-end">
                            <a href="{{ route('dashboard-guru.wali-kelas.data-kelas.show-siswa', $siswa->id) }}" class="btn btn-sm btn-light border" style="font-size: 12px; font-weight: 500; border-radius: 8px;">
                                <i class="fa-solid fa-eye me-1 text-primary"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users fa-2x mb-3 d-block opacity-25"></i>
                                @if($search)
                                    Tidak ada siswa yang cocok dengan pencarian "<strong>{{ $search }}</strong>".
                                @else
                                    Belum ada siswa yang terdaftar di kelas ini.
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($siswas->hasPages())
        <div class="px-4 py-3 border-top" style="border-color: #f1f5f9 !important;">
            {{ $siswas->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
