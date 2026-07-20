@extends('layouts.app')

@section('title', 'Peserta Ujian & Nilai - Guru Mapel')

@section('content')
<style>
    .page-header-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
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
        background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
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

    .data-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        overflow: hidden;
    }
    
    .data-table th {
        background-color: #f8fafc;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    
    .data-table td {
        padding: 0.875rem 1rem;
        font-size: 0.875rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .data-table tbody tr:hover {
        background-color: #f8fafc;
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
    
    .alert-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: #fee2e2;
        color: #b91c1c;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-koreksi {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }
    .btn-koreksi:hover {
        background: #2563eb;
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
        background: linear-gradient(135deg, #6366f1, #4338ca);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>

<div class="container-fluid px-0 py-2">

    {{-- PAGE HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-card p-4 p-md-5">
                <div class="mb-4">
                    <a href="{{ route('dashboard-guru.nilai-siswa.index') }}" class="btn-back">
                        <i class="fa-solid fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                    <div class="flex-grow-1">
                        <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill mb-3 d-inline-flex align-items-center gap-1"
                              style="font-size: 11px; font-weight: 600;">
                            <i class="fa-solid fa-users me-1"></i>
                            Daftar Peserta & Nilai
                        </span>
                        <h1 class="fw-bold text-white mb-1" style="font-size: 1.75rem; letter-spacing: -0.5px;">
                            {{ $ujian->nama_ujian }}
                        </h1>
                        <p class="text-white text-opacity-60 mb-0" style="font-size: 13px;">
                            Mata Pelajaran: {{ $ujian->nama_mapel }} &bull; Pelaksanaan: {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>
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
            <div class="bg-white border rounded-3 p-3 text-center" style="border-color: #e2e8f0 !important;">
                <div class="fw-bold text-dark fs-4">{{ $pesertas->count() }}</div>
                <div class="text-muted" style="font-size: 12px;">Total Peserta</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="bg-white border rounded-3 p-3 text-center" style="border-color: #e2e8f0 !important;">
                <div class="fw-bold text-success fs-4">{{ $totalSelesai }}</div>
                <div class="text-muted" style="font-size: 12px;">Selesai Mengerjakan</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="bg-white border rounded-3 p-3 text-center" style="border-color: #e2e8f0 !important;">
                <div class="fw-bold {{ $totalBelumDikoreksi > 0 ? 'text-danger' : 'text-primary' }} fs-4">{{ $totalBelumDikoreksi }}</div>
                <div class="text-muted" style="font-size: 12px;">Butuh Koreksi Manual</div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="data-card">
        <div class="table-responsive">
            <table class="table mb-0 data-table">
                <thead>
                    <tr>
                        <th style="width: 48px;">No</th>
                        <th>Nama Siswa</th>
                        <th>Status</th>
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
                                    <div class="text-muted" style="font-size: 11px;">NIS: {{ $p->nis ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
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
                        </td>
                        <td class="text-muted" style="font-size: 12px;">
                            {{ $p->waktu_kumpul ? \Carbon\Carbon::parse($p->waktu_kumpul)->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="text-center">
                            @if($p->status === 'selesai')
                                @php $nilai = (float) $p->nilai_akhir; @endphp
                                @if($nilai >= 80)
                                    <span class="score-badge score-high">{{ number_format($nilai, 0) }}</span>
                                @elseif($nilai >= 60)
                                    <span class="score-badge score-mid">{{ number_format($nilai, 0) }}</span>
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
