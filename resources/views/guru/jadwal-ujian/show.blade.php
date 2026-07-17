@extends('layouts.app')

@section('content')
<style>
    .bg-soft-primary { background-color: #f0f9ff; }
    .bg-soft-success { background-color: #f0fdf4; }
    .bg-soft-warning { background-color: #fffbeb; }
    
    .text-primary-custom { color: #0284c7; }
    .text-success-custom { color: #16a34a; }
    .text-warning-custom { color: #d97706; }

    .btn-light-custom {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        color: #64748b;
        transition: all 0.3s ease;
    }
    .btn-light-custom:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }

    .modern-card {
        border: 1px solid rgba(0,0,0,0.03);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }
    
    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 20px;
    }

    .info-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
    }
</style>

<div class="container-fluid py-4 px-md-4">
    <!-- Header Page & Back Button -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <a href="{{ route('dashboard-guru.jadwal-ujian.index') }}" class="btn btn-light-custom rounded-pill px-3 py-2 mb-3 fw-medium" style="font-size: 13px;">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Daftar Ujian
            </a>
            <h3 class="fw-bolder text-dark mb-1">Detail Jadwal Ujian</h3>
            <p class="text-muted fw-medium mb-0" style="font-size: 14px;">Informasi lengkap terkait pelaksanaan ujian.</p>
        </div>
        
        <!-- Status Ujian (Bisa disesuaikan logikanya jika ada field status aktif/nonaktif di database) -->
        <div>
            <span class="badge bg-soft-success text-success-custom px-4 py-2 rounded-pill fw-bold fs-6 border border-success border-opacity-25">
                <i class="fa-solid fa-circle-check me-2"></i> Siap Dilaksanakan
            </span>
        </div>
    </div>

    <div class="row g-4">
        <!-- CARD INFO UTAMA -->
        <div class="col-12 col-lg-7">
            <div class="card modern-card bg-white h-100 p-2">
                <div class="card-body">
                    <h5 class="fw-bold mb-4 border-bottom pb-3">Informasi Akademik</h5>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box bg-soft-primary text-primary-custom me-3">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>
                        <div>
                            <div class="info-label">Nama Ujian</div>
                            <div class="info-value fs-5">{{ $ujian->nama_ujian }}</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box bg-soft-primary text-primary-custom me-3">
                            <i class="fa-solid fa-book-open-reader"></i>
                        </div>
                        <div>
                            <div class="info-label">Mata Pelajaran</div>
                            <div class="info-value">{{ $ujian->bankSoal->mataPelajaran->nama_mapel  }}</div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-soft-warning text-warning-custom me-3">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div>
                            <div class="info-label">Token Ujian</div>
                            <div class="info-value">
                                @if($ujian->token)
                                    <span class="font-monospace bg-light border px-2 py-1 rounded text-danger tracking-wide">{{ $ujian->token }}</span>
                                @else
                                    <span class="text-muted fst-italic fw-normal">Tidak menggunakan token</span>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- CARD INFO WAKTU PELAKSANAAN -->
        <div class="col-12 col-lg-5">
            <div class="card modern-card bg-white h-100 p-2">
                <div class="card-body">
                    <h5 class="fw-bold mb-4 border-bottom pb-3">Waktu Pelaksanaan</h5>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box bg-soft-primary text-primary-custom me-3">
                            <i class="fa-regular fa-calendar-check"></i>
                        </div>
                        <div>
                            <div class="info-label">Tanggal Ujian</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($ujian->tanggal)->translatedFormat('l, d F Y') }}</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="info-label">Waktu Mulai</div>
                                <div class="info-value text-primary-custom fs-5">
                                    <i class="fa-regular fa-clock me-1"></i> {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="info-label">Waktu Selesai</div>
                                <div class="info-value text-danger fs-5">
                                    <i class="fa-regular fa-clock me-1"></i> {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection