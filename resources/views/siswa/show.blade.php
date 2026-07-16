@extends('layouts.app')

@section('title', 'Detail Siswa')

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

    .btn-back {
        background: rgba(255,255,255,0.1);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 14px;
        padding: 10px 18px;
        font-weight: 600;
    }

    .btn-back:hover { background: rgba(255,255,255,0.2); color: #fff; }

    .btn-edit-profile {
        border-radius: 14px;
        padding: 10px 20px;
        font-weight: 600;
    }

    .content-card {
        background: var(--surface-white);
        border-radius: 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.03);
        padding: 24px;
    }

    .profile-photo {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #f8fafc;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.08);
        flex-shrink: 0;
    }

    .profile-photo-fallback {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.08);
    }

    .info-item {
        padding: 14px 0;
        border-bottom: 1px dashed var(--border-color);
    }

    .info-item:last-child { border-bottom: none; }

    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--secondary-dark);
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--secondary-dark);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-responsive { border-radius: 16px; overflow: hidden; }

    .table thead th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        background-color: #f8fafc;
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-color);
        font-weight: 700;
    }

    .table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .page-header { padding: 24px; border-radius: 18px; text-align: center; }
        .page-header .d-flex { flex-direction: column; gap: 16px; }
        .content-card { padding: 16px; }
    }
</style>

<div class="container-fluid py-2">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    DETAIL SISWA
                </span>
                <h3 class="fw-bold mb-1">{{ $siswa->nama }}</h3>
                <p class="text-light opacity-75 mb-0 small">
                    NIS {{ $siswa->nis }}
                    @if($siswa->nisn) &middot; NISN {{ $siswa->nisn }} @endif
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-info text-white btn-edit-profile d-inline-flex align-items-center">
                    <i class="fa-solid fa-pen me-2"></i>
                    Edit
                </a>
                <a href="{{ route('siswa.index') }}" class="btn-back d-inline-flex align-items-center">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Kolom Kiri: Biodata --}}
        <div class="col-lg-4">
            <div class="content-card h-100">

                <div class="d-flex flex-column align-items-center text-center mb-4">
                    @if($siswa->foto)
                        <img src="{{ asset('storage/' . $siswa->foto) }}" alt="{{ $siswa->nama }}" class="profile-photo mb-3">
                    @else
                        <div class="profile-photo-fallback mb-3">
                            {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                        </div>
                    @endif
                    <h5 class="fw-bold mb-0">{{ $siswa->nama }}</h5>
                    <small class="text-muted">NIS {{ $siswa->nis }}</small>
                </div>

                <div class="info-item">
                    <div class="info-label">NIS</div>
                    <div class="info-value">{{ $siswa->nis }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">NISN</div>
                    <div class="info-value">{{ $siswa->nisn ?? '-' }}</div>
                </div>

                @php
                    $kelasSekarang = $siswa->siswaKelas->first(function ($sk) {
                        return optional($sk->tahunAjaran)->is_aktif;
                    });
                @endphp

                <div class="info-item">
                    <div class="info-label">Kelas Saat Ini</div>
                    <div class="info-value">
                        @if($kelasSekarang && $kelasSekarang->kelas)
                            {{ optional($kelasSekarang->kelas->tingkat)->nama_tingkat }} - {{ $kelasSekarang->kelas->nama_kelas }}
                        @else
                            -
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- Kolom Kanan: Riwayat Kelas --}}
        <div class="col-lg-8">
            <div class="content-card">
                <div class="section-title">
                    <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                    Riwayat Penempatan Kelas
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Tahun Ajaran</th>
                                <th>Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa->siswaKelas->sortByDesc('tahun_ajaran_id') as $sk)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">{{ optional($sk->tahunAjaran)->nama_tahun ?? '-' }}</div>
                                    <small class="text-muted">
                                        {{ ucfirst(optional($sk->tahunAjaran)->semester ?? '-') }}
                                        @if(optional($sk->tahunAjaran)->is_aktif)
                                            <span class="badge bg-primary bg-opacity-10 text-primary ms-1">Aktif</span>
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    @if($sk->kelas)
                                        {{ optional($sk->kelas->tingkat)->nama_tingkat }} - {{ $sk->kelas->nama_kelas }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2">
                                    <div class="text-center py-4">
                                        <i class="fa-solid fa-door-open fa-2x text-muted mb-2 opacity-50"></i>
                                        <p class="text-muted small mb-0">Belum ada riwayat penempatan kelas.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection