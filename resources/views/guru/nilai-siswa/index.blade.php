@extends('layouts.app')

@section('title', 'Nilai Siswa - Guru Mapel')

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

    .ujian-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .ujian-card:hover {
        border-color: #6366f1;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        transform: translateY(-2px);
    }
    
    .ujian-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #e0e7ff;
        color: #4338ca;
        margin-bottom: 1rem;
    }

    .ujian-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    
    .ujian-meta {
        font-size: 0.8125rem;
        color: #64748b;
        margin-bottom: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .ujian-meta i {
        width: 16px;
        text-align: center;
        color: #94a3b8;
    }

    .peserta-count {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f8fafc;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        margin-top: auto;
        margin-bottom: 1rem;
    }
    .peserta-count .icon {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: #e2e8f0;
        display: flex; align-items: center; justify-content: center;
        color: #475569;
    }
    .peserta-count .text {
        font-size: 0.875rem;
        font-weight: 600;
        color: #334155;
    }

    .btn-lihat {
        background: #fff;
        border: 1px solid #6366f1;
        color: #6366f1;
        width: 100%;
        padding: 0.5rem;
        border-radius: 0.625rem;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    .btn-lihat:hover {
        background: #6366f1;
        color: #fff;
    }
</style>

<div class="container-fluid px-0 py-2">

    {{-- PAGE HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-card p-4 p-md-5">
                <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill mb-3 d-inline-flex align-items-center gap-1"
                      style="font-size: 11px; font-weight: 600;">
                    <i class="fa-solid fa-square-poll-vertical me-1"></i>
                    Manajemen Nilai & Ujian
                </span>
                <h1 class="fw-bold text-white mb-1" style="font-size: 1.75rem; letter-spacing: -0.5px;">
                    Daftar Ujian Anda
                </h1>
                <p class="text-white text-opacity-60 mb-0" style="font-size: 13px;">
                    Pilih ujian untuk melihat daftar peserta, nilai akhir, dan melakukan koreksi manual pada soal tipe essay atau isian.
                </p>
            </div>
        </div>
    </div>

    {{-- LIST UJIAN --}}
    @if($ujians->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <i class="fa-solid fa-folder-open fa-3x text-muted opacity-25 mb-3 d-block"></i>
                <h5 class="fw-bold text-dark mb-1">Belum Ada Ujian</h5>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">
                    Anda belum membuat atau memiliki ujian yang diselenggarakan dari Bank Soal Anda.
                </p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($ujians as $ujian)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="ujian-card">
                    <div>
                        <span class="ujian-badge">{{ $ujian->nama_jenis_ujian }}</span>
                        <h3 class="ujian-title">{{ $ujian->nama_ujian }}</h3>
                        
                        <div class="ujian-meta">
                            <span><i class="fa-solid fa-book"></i> {{ $ujian->nama_mapel }}</span>
                            <span><i class="fa-solid fa-calendar-alt"></i> {{ $ujian->nama_tahun }}</span>
                            <span>
                                <i class="fa-regular fa-clock"></i> 
                                {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d M Y') }}
                                <span class="opacity-50 mx-1">|</span>
                                {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="peserta-count">
                        <div class="icon"><i class="fa-solid fa-users"></i></div>
                        <div class="text">{{ $ujian->peserta_count }} Peserta Ujian</div>
                    </div>
                    
                    <a href="{{ route('dashboard-guru.nilai-siswa.show', $ujian->id) }}" class="btn btn-lihat text-center text-decoration-none">
                        Lihat Nilai & Peserta
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($ujians->hasPages())
        <div class="mt-4">
            {{ $ujians->links() }}
        </div>
        @endif
    @endif

</div>
@endsection
