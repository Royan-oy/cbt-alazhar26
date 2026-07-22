@extends('layouts.app')

@section('title', 'Nilai Siswa - Guru Mapel')

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
    
    .badge-jenis {
        background-color: #e0f2fe;
        color: #0369a1;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .btn-action-minimalist {
        color: #0284c7;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    .btn-action-minimalist:hover {
        background: #0284c7;
        border-color: #0284c7;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.2);
    }
    
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
</style>

<div class="container-fluid px-0 py-2">

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div class="header-icon">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <div>
            <h1 class="page-title">Daftar Ujian</h1>
            <p class="page-description">
                Kelola nilai dan koreksi jawaban manual untuk setiap sesi ujian siswa.
            </p>
        </div>
    </div>

    {{-- LIST UJIAN --}}
    @if($ujians->isEmpty())
        <div class="table-card empty-state">
            <i class="fa-solid fa-folder-open fa-3x text-muted opacity-25 mb-3 d-block"></i>
            <h5 class="fw-bold text-dark mb-1">Belum Ada Ujian</h5>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                Anda belum membuat atau memiliki ujian yang diselenggarakan dari Bank Soal Anda.
            </p>
        </div>
    @else
        <div class="table-card">
            <div class="table-responsive">
                <table class="table-minimalist">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Ujian</th>
                            <th>Mata Pelajaran</th>
                            <th>Waktu Pelaksanaan</th>
                            <th class="text-center">Peserta</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ujians as $index => $ujian)
                        <tr>
                            <td class="text-muted">{{ ($ujians->currentPage() - 1) * $ujians->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold text-dark mb-1">{{ $ujian->nama_ujian }}</div>
                                <span class="badge-jenis"><i class="fa-solid fa-tag me-1"></i> {{ $ujian->nama_jenis_ujian }}</span>
                            </td>
                            <td>
                                <div><i class="fa-solid fa-book-open text-muted me-1"></i> {{ $ujian->nama_mapel }}</div>
                                <small class="text-muted">{{ $ujian->nama_tahun }}</small>
                            </td>
                            <td>
                                <div><i class="fa-regular fa-calendar text-muted me-1"></i> {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d M Y') }}</div>
                                <small class="text-muted">
                                    {{-- <i class="fa-regular fa-clock me-1"></i>  --}} {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="fw-semibold"><i class="fa-solid fa-users text-muted me-1"></i> {{ $ujian->peserta_count }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('dashboard-guru.nilai-siswa.show', $ujian->id) }}" class="btn-action-minimalist">
                                    <i class="fa-solid fa-arrow-right"></i> Kelola Nilai
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($ujians->hasPages())
        <div class="mt-4">
            {{ $ujians->links() }}
        </div>
        @endif
    @endif

</div>
@endsection
