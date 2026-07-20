@extends('layouts.app')

@section('title', 'Detail Siswa - Wali Kelas')

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

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
        transition: background 0.2s;
    }
    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .profile-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        overflow: hidden;
    }
    
    .profile-header {
        background: #f8fafc;
        border-bottom: 1px solid var(--border-color);
        padding: 2rem;
        text-align: center;
    }

    .avatar-large {
        width: 100px; height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #fff;
        font-size: 32px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .avatar-img-large {
        width: 100px; height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .info-list li {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
    }
    
    .info-list li:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #0f172a;
    }

    .history-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        overflow: hidden;
        height: 100%;
    }
    
    .history-card .card-header {
        background: #fff;
        border-bottom: 1px solid var(--border-color);
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        color: #0f172a;
    }

    .history-table th {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        background: #f8fafc;
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .history-table td {
        padding: 0.875rem 1rem;
        font-size: 0.875rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-belum   { background: #f1f5f9; color: #64748b; }
    .status-mengerjakan { background: #fef9c3; color: #854d0e; }
    .status-selesai { background: #dcfce7; color: #166534; }
    
    .score-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        min-width: 50px;
    }
    .score-high   { background: #dcfce7; color: #15803d; }
    .score-mid    { background: #fef9c3; color: #a16207; }
    .score-low    { background: #fee2e2; color: #b91c1c; }

    @media (max-width: 767.98px) {
        .page-header-card { padding: 1.5rem !important; }
    }
</style>

<div class="container-fluid px-0 py-2">

    {{-- PAGE HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-card p-4 p-md-5">
                <div class="mb-4">
                    <a href="{{ route('dashboard-guru.wali-kelas.data-kelas') }}" class="btn-back">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Kelas
                    </a>
                </div>
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                    <div class="flex-grow-1">
                        <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill mb-3 d-inline-flex align-items-center gap-1"
                              style="font-size: 11px; font-weight: 600;">
                            <i class="fa-solid fa-user me-1"></i>
                            Detail Siswa
                        </span>
                        <h1 class="fw-bold text-white mb-1" style="font-size: 1.75rem; letter-spacing: -0.5px;">
                            {{ $siswa->nama }}
                        </h1>
                        <p class="text-white text-opacity-60 mb-0" style="font-size: 13px;">
                            Kelas {{ optional($kelas)->nama_kelas ?? '-' }}
                            &bull; {{ optional($kelas)->nama_tingkat ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- PROFIL SISWA --}}
        <div class="col-12 col-lg-4">
            <div class="profile-card">
                <div class="profile-header">
                    @if($siswa->foto)
                        <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto {{ $siswa->nama }}" class="avatar-img-large">
                    @else
                        <div class="avatar-large mx-auto">
                            {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                        </div>
                    @endif
                    <h5 class="fw-bold text-dark mb-1">{{ $siswa->nama }}</h5>
                    <p class="text-muted mb-0" style="font-size: 0.875rem;">Siswa Kelas {{ optional($kelas)->nama_kelas ?? '-' }}</p>
                </div>
                <div class="card-body p-0">
                    <ul class="info-list">
                        <li>
                            <span class="info-label">Nama Lengkap</span>
                            <span class="info-value">{{ $siswa->nama }}</span>
                        </li>
                        <li>
                            <span class="info-label">Nomor Induk Siswa (NIS)</span>
                            <span class="info-value">{{ $siswa->nis ?? '-' }}</span>
                        </li>
                        <li>
                            <span class="info-label">NISN</span>
                            <span class="info-value">{{ $siswa->nisn ?? '-' }}</span>
                        </li>
                        <li>
                            <span class="info-label">Kelas Saat Ini</span>
                            <span class="info-value">{{ optional($kelas)->nama_tingkat ?? '' }} {{ optional($kelas)->nama_kelas ?? '-' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- RIWAYAT UJIAN --}}
        <div class="col-12 col-lg-8">
            <div class="history-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>Riwayat Ujian</span>
                    <span class="badge bg-primary rounded-pill">{{ $riwayatUjian->count() }} Ujian</span>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 history-table">
                        <thead>
                            <tr>
                                <th>Nama Ujian</th>
                                <th>Mata Pelajaran</th>
                                <th>Waktu Pengerjaan</th>
                                <th>Status</th>
                                <th class="text-center">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatUjian as $ujian)
                            <tr>
                                <td class="fw-medium text-dark">{{ $ujian->nama_ujian }}</td>
                                <td>{{ $ujian->nama_mapel }}</td>
                                <td class="text-muted" style="font-size: 0.8125rem;">
                                    {{ $ujian->waktu_mulai_kerja ? \Carbon\Carbon::parse($ujian->waktu_mulai_kerja)->format('d M Y, H:i') : '-' }}
                                </td>
                                <td>
                                    @if($ujian->status === 'mengerjakan')
                                        <span class="status-badge status-mengerjakan">
                                            <i class="fa-solid fa-circle-dot fa-beat" style="font-size: 8px;"></i> Mengerjakan
                                        </span>
                                    @elseif($ujian->status === 'selesai')
                                        <span class="status-badge status-selesai">
                                            <i class="fa-solid fa-check"></i> Selesai
                                        </span>
                                    @else
                                        <span class="status-badge status-belum">
                                            <i class="fa-regular fa-clock"></i> Belum Mulai
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($ujian->status === 'selesai')
                                        @php $nilai = (float) $ujian->nilai_akhir; @endphp
                                        @if($nilai >= 80)
                                            <span class="score-badge score-high">{{ number_format($nilai, 0) }}</span>
                                        @elseif($nilai >= 60)
                                            <span class="score-badge score-mid">{{ number_format($nilai, 0) }}</span>
                                        @else
                                            <span class="score-badge score-low">{{ number_format($nilai, 0) }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-folder-open fa-2x mb-3 d-block opacity-25"></i>
                                    Belum ada riwayat ujian untuk siswa ini.
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
