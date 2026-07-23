@extends('layouts.app')

@section('title', 'Peserta Ujian & Nilai - Guru Mapel')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1px solid #bae6fd;
        padding: 2rem 1.5rem;
        margin-bottom: 2rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.1), 0 2px 4px -1px rgba(14, 165, 233, 0.06);
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    .header-icon {
        background: #fff;
        color: #0ea5e9;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 1rem;
        font-size: 1.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #fff;
        color: #0369a1;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.8125rem;
        font-weight: 600;
        border: 1px solid #bae6fd;
        transition: all 0.2s;
    }
    .btn-back:hover {
        background: #0284c7;
        color: #fff;
        border-color: #0284c7;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    .page-description {
        color: #475569;
        font-size: 0.875rem;
        margin-bottom: 0;
    }

    /* Info Cards */
    .info-card {
        background: #fff;
        border: 1px solid #bae6fd;
        border-radius: 0.5rem;
        padding: 1.25rem;
        text-align: center;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    .info-card .number {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .info-card .label {
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Table Minimalist */
    .table-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    
    .table-minimalist {
        margin-bottom: 0;
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-minimalist thead th {
        background-color: #f8fafc;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    
    .table-minimalist tbody td {
        padding: 1rem 1.5rem;
        font-size: 0.875rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .table-minimalist tbody tr:last-child td {
        border-bottom: none;
    }

    .table-minimalist tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Badges & Actions */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 4px;
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
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        min-width: 50px;
    }
    .score-high   { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .score-mid    { background: #fef9c3; color: #a16207; border: 1px solid #fef08a; }
    .score-low    { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    
    .alert-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: #fee2e2;
        color: #b91c1c;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-koreksi {
        background: #f0f9ff;
        color: #0284c7;
        border: 1px solid #bae6fd;
    }
    .btn-koreksi:hover {
        background: #0284c7;
        border-color: #0284c7;
        color: #fff;
    }
    .btn-koreksi-warning {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        animation: pulse-red 2s infinite;
    }
    .btn-koreksi-warning:hover {
        background: #dc2626;
        color: #fff;
        animation: none;
    }

    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(220, 38, 38, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }
    
    .avatar-student {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: #e0f2fe;
        color: #0284c7;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid #bae6fd;
    }
</style>

<div class="container-fluid px-0 py-2">

    {{-- BACK BUTTON --}}
    <div class="mb-3">
        <a href="{{ route('dashboard-guru.nilai-siswa.index') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div class="header-icon">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <h1 class="page-title">
                {{ $ujian->nama_ujian }}
            </h1>
            <p class="page-description">
                <i class="fa-solid fa-book-open text-muted me-1"></i> {{ $ujian->nama_mapel }} &bull; 
                <i class="fa-regular fa-calendar text-muted me-1"></i> {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d M Y') }}
            </p>
        </div>
    </div>
    
    {{-- SESSION MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 mb-3 d-flex align-items-center gap-2" style="font-size: 14px;">
            <i class="fa-solid fa-circle-check text-success"></i> {{ session('success') }}
        </div>
    @endif
    
    {{-- INFO CARDS --}}
    @php
        $totalSelesai = $pesertas->where('status', 'selesai')->count();
        $totalBelumDikoreksi = $pesertas->where('belum_dikoreksi', '>', 0)->count();
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="info-card">
                <div class="number text-dark">{{ $pesertas->count() }}</div>
                <div class="label">Total Peserta</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="info-card" style="border-color: #bbf7d0; background: #f0fdf4;">
                <div class="number text-success">{{ $totalSelesai }}</div>
                <div class="label text-success">Selesai Mengerjakan</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="info-card" style="border-color: {{ $totalBelumDikoreksi > 0 ? '#fecaca' : '#bae6fd' }}; background: {{ $totalBelumDikoreksi > 0 ? '#fef2f2' : '#f0f9ff' }};">
                <div class="number {{ $totalBelumDikoreksi > 0 ? 'text-danger' : 'text-primary' }}">{{ $totalBelumDikoreksi }}</div>
                <div class="label {{ $totalBelumDikoreksi > 0 ? 'text-danger' : 'text-primary' }}">Butuh Koreksi Manual</div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="table-minimalist">
                <thead>
                    <tr>
                        <th style="width: 48px;">No</th>
                        <th>Nama Siswa</th>
                        {{-- <th>Status</th> --}}
                        <th>Waktu Selesai</th>
                        <th class="text-center">Nilai Akhir</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesertas as $i => $p)
                    <tr>
                        <td class="text-muted" style="font-size: 13px;">{{ $i + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-student">
                                    {{ strtoupper(substr($p->nama_siswa, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ $p->nama_siswa }}</div>
                                    <div class="text-muted" style="font-size: 11px;"><i class="fa-regular fa-id-badge me-1"></i> NIS: {{ $p->nis ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        {{-- <td>
                            @if($p->status === 'mengerjakan')
                                <span class="status-badge status-mengerjakan">
                                    <i class="fa-solid fa-circle-dot fa-beat" style="font-size: 8px;"></i> Mengerjakan
                                </span>
                            @elseif($p->status === 'selesai')
                                <span class="status-badge status-selesai">
                                    <i class="fa-solid fa-check"></i> Selesai
                                </span>
                            @else
                                <span class="status-badge status-belum">
                                    <i class="fa-regular fa-clock"></i> Belum
                                </span>
                            @endif
                        </td> --}}
                        <td class="text-muted" style="font-size: 12px;">
                            {{ $p->waktu_kumpul ? \Carbon\Carbon::parse($p->waktu_kumpul)->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="text-center">
                            @if($p->status === 'selesai')
                                @php 
                                    $nilai = (float) $p->nilai_akhir;
                                    $kkm = (float) ($ujian->kkm ?? 75);
                                @endphp
                                @if($nilai >= $kkm)
                                    <span class="score-badge score-high">{{ number_format($nilai, 0) }}</span>
                                @else
                                    <span class="score-badge score-low">{{ number_format($nilai, 0) }}</span>
                                @endif
                                
                                @if($p->belum_dikoreksi > 0)
                                    <div class="mt-1">
                                        <span class="alert-badge" title="{{ $p->belum_dikoreksi }} jawaban essay belum dinilai">
                                            <i class="fa-solid fa-triangle-exclamation"></i> Menunggu Koreksi
                                        </span>
                                    </div>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if($p->status === 'selesai')
                                @if($p->belum_dikoreksi > 0)
                                    <a href="{{ route('dashboard-guru.nilai-siswa.koreksi', ['ujian' => $ujian->id, 'siswa' => $p->siswa_id]) }}" 
                                       class="btn-action btn-koreksi-warning">
                                        <i class="fa-solid fa-highlighter"></i> Koreksi
                                    </a>
                                @else
                                    <a href="{{ route('dashboard-guru.nilai-siswa.koreksi', ['ujian' => $ujian->id, 'siswa' => $p->siswa_id]) }}" 
                                       class="btn-action btn-koreksi">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                @endif
                            @else
                                <span class="text-muted" style="font-size:11px; font-style:italic;">Belum Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users-slash fa-2x mb-3 d-block opacity-25"></i>
                                Belum ada siswa yang terdaftar untuk ujian ini.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
