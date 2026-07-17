@extends('layouts.app')

@section('content')
<style>
    /* --- CUSTOM STYLES FOR MODERN UI --- */
    .bg-soft-primary {
        background-color: #f0f9ff;
    }
    .text-primary-custom {
        color: #0284c7;
    }
    .btn-primary-custom {
        background: linear-gradient(135deg, #38bdf8, #0284c7);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: white;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
    }
    .btn-light-custom {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        transition: all 0.3s ease;
    }
    .btn-light-custom:hover {
        background-color: #e2e8f0;
        color: #334155;
    }
    .modern-card {
        border: 1px solid rgba(0,0,0,0.03);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }
    .card-top-accent {
        height: 4px;
        width: 100%;
        background: linear-gradient(90deg, #38bdf8, #0284c7);
    }
    .date-box {
        width: 60px;
        height: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-radius: 12px;
    }
    .form-control-modern {
        border-radius: 10px;
        padding: 0.6rem 1rem;
        border: 1px solid #cbd5e1;
        background-color: #f8fafc;
    }
    .form-control-modern:focus {
        background-color: #ffffff;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
    }
    .input-group-text-modern {
        background-color: #f8fafc;
        border: 1px solid #cbd5e1;
        border-right: none;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }
</style>

<div class="container-fluid py-4 px-md-4">
    
    <!-- HEADER SECTION -->
    <div class="mb-4">
        <h3 class="fw-bolder text-dark mb-1">Jadwal Ujian</h3>
        <p class="text-muted fw-medium" style="font-size: 14px;">Pantau dan kelola jadwal ujian siswa dengan mudah dan cepat.</p>
    </div>

    <!-- FITUR PENCARIAN & FILTER -->
    <div class="card border-0 shadow-sm mb-4 rounded-4" style="background-color: #ffffff;">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('dashboard-guru.jadwal-ujian.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <!-- Cari Nama -->
                    <div class="col-12 col-md-5">
                        <label for="search" class="form-label text-muted fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.8px;">CARI NAMA UJIAN</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-modern">
                                <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            </span>
                            <input type="search" class="form-control form-control-modern border-start-0 ps-0" id="search" name="search" placeholder="Contoh: PTS Ganjil..." value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    <!-- Filter Tanggal -->
                    <div class="col-12 col-md-4">
                        <label for="tanggal" class="form-label text-muted fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.8px;">FILTER TANGGAL</label>
                        <input type="date" class="form-control form-control-modern" id="tanggal" name="tanggal" value="{{ request('tanggal') }}">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="col-12 col-md-3">
                        <div class="d-grid gap-2 d-flex flex-column flex-sm-row">
                            <button type="submit" class="btn btn-primary-custom w-100 rounded-3 py-2 fw-semibold">
                                <i class="fa-solid fa-filter me-1"></i> Terapkan
                            </button>
                            @if(request('search') || request('tanggal'))
                                <a href="{{ route('dashboard-guru.jadwal-ujian.index') }}" class="btn btn-light-custom w-100 rounded-3 py-2 fw-semibold">
                                    <i class="fa-solid fa-rotate-right me-1"></i> Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- DAFTAR JADWAL UJIAN (CARD) -->
    <div class="row g-4">
        @forelse($jadwalUjian as $ujian)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card h-100 modern-card bg-white">
                    <div class="card-top-accent"></div>
                    <div class="card-body p-4 d-flex flex-column">
                        
                        <!-- Header Card: Badge Waktu -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-soft-primary text-primary-custom px-3 py-2 rounded-pill fw-semibold" style="font-size: 12px;">
                                <i class="fa-regular fa-clock me-1"></i> 
                                {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }} WIB
                            </span>
                        </div>
                        
                        <!-- Info Utama -->
                        <h5 class="fw-bolder text-dark mb-2 text-truncate" title="{{ $ujian->nama_ujian }}">{{ $ujian->nama_ujian }}</h5>
                        <p class="text-muted fw-medium mb-4 fs-6">
                            <i class="fa-solid fa-book-open text-primary-custom me-2 opacity-75"></i> 
                            {{ $ujian->bankSoal->mataPelajaran->nama_mapel }}
                        </p>

                        <div class="mt-auto">
                            <!-- Box Tanggal Modern -->
                            <div class="d-flex align-items-center bg-light rounded-4 p-3 mb-3 border" style="border-color: #f1f5f9 !important;">
                                <div class="date-box bg-white shadow-sm me-3">
                                    <span class="d-block text-primary-custom fw-black fs-4 lh-1">{{ \Carbon\Carbon::parse($ujian->tanggal)->format('d') }}</span>
                                    <span class="d-block text-muted mt-1" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">{{ \Carbon\Carbon::parse($ujian->tanggal)->format('M') }}</span>
                                </div>
                                <div>
                                    <span class="d-block fw-bold text-dark mb-1" style="font-size: 13px;">Pelaksanaan</span>
                                    <span class="text-muted fw-medium" style="font-size: 13px;">{{ \Carbon\Carbon::parse($ujian->tanggal)->translatedFormat('l, d F Y') }}</span>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <!-- Tombol Aksi -->
<a href="{{ route('dashboard-guru.jadwal-ujian.show', $ujian->id) }}" class="btn btn-light-custom w-100 rounded-3 py-2 fw-bold text-primary-custom">
    Lihat Detail <i class="fa-solid fa-arrow-right ms-2 fs-7"></i>
</a>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 text-center p-5" style="background-color: #f8fafc;">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fa-regular fa-calendar-xmark text-muted" style="font-size: 32px;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bolder text-dark mb-2">Belum Ada Jadwal Ujian</h5>
                    <p class="text-muted mb-0 mx-auto" style="max-width: 400px; font-size: 14px;">Saat ini tidak ada jadwal ujian yang tersedia atau tidak ada data yang cocok dengan pencarian Anda.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- PAGINASI -->
    @if($jadwalUjian->hasPages())
    <div class="mt-5 d-flex justify-content-center">
        {{ $jadwalUjian->links() }}
    </div>
    @endif

</div>
@endsection