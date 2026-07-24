@extends('layouts.app')

@section('title', 'Konfirmasi Ujian')

@section('content')
<style>
    :root {
        --sb-bg: #0f172a;
        --sb-card: #1e293b;

        --primary: #0ea5e9;
        --primary-dark: #0284c7;
        --primary-light: #eff8ff;
        --accent-violet: #818cf8;
        --accent-violet-light: #eef1ff;

        --ink-900: #0f172a;
        --ink-700: #334155;
        --ink-500: #64748b;
        --surface: #ffffff;
        --border: #e5e9f2;
        --card-shadow: 0 10px 30px -8px rgba(15,23,42,.05), 0 4px 12px -6px rgba(15,23,42,.04);
    }

    .setup-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 22px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .setup-header {
        background: linear-gradient(135deg, var(--sb-bg) 0%, var(--sb-card) 60%, #1e293b 100%);
        padding: 32px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .setup-header::after {
        content: '';
        position: absolute;
        top: -50%; right: -8%;
        width: 260px; height: 260px;
        background: radial-gradient(circle, rgba(56,189,248,.22) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .setup-header .d-flex { position: relative; }

    .setup-header .badge {
        background: rgba(56,189,248,.18) !important;
        color: #7dd3fc !important;
        font-weight: 700;
        letter-spacing: .3px;
    }

    .setup-header h3 { font-size: 19px; }

    .meta-box {
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 15px;
        padding: 18px;
        transition: all .2s ease;
    }

    .token-container {
        max-width: 380px;
        margin: 0 auto;
    }

    .token-input {
        letter-spacing: 7px;
        text-transform: uppercase;
        font-weight: 700;
        font-size: 1.6rem;
        text-align: center;
        border: 2px solid var(--border);
        border-radius: 15px;
        color: var(--ink-900);
        background: #f8fafc;
        transition: all .2s ease;
    }

    .token-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(14,165,233,.14);
        background: #fff;
        outline: none;
    }

    .btn-start {
        background: linear-gradient(135deg, var(--sb-bg), var(--primary-dark));
        color: #fff;
        border: none;
        border-radius: 15px;
        padding: 15px 30px;
        font-weight: 700;
        transition: all .2s ease;
        box-shadow: 0 8px 18px -6px rgba(2,132,199,.4);
    }

    .btn-start:hover {
        background: linear-gradient(135deg, var(--sb-card), var(--primary));
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px -6px rgba(2,132,199,.5);
    }

    .btn-back-setup {
        background: #f1f5f9;
        color: var(--ink-700);
        border: 1px solid var(--border);
        border-radius: 15px;
        padding: 15px 30px;
        font-weight: 700;
        transition: all .2s ease;
    }

    .btn-back-setup:hover {
        background: #e2e8f0;
        color: var(--ink-900);
    }

    .alert-token-info {
        background: var(--accent-violet-light);
        color: #4338ca;
        border-radius: 15px;
    }

    /* =========================================================
       RESPONSIVE — TABLET
       ========================================================= */
    @media (max-width: 767.98px) {
        .container.py-4 { padding-left: 12px !important; padding-right: 12px !important; padding-top: 16px !important; }

        .setup-card { border-radius: 18px; }

        .setup-header { padding: 22px; }
        .setup-header .p-3.rounded-4 { padding: 10px !important; }
        .setup-header h3 { font-size: 16px; }
        .setup-header .badge { font-size: 10px !important; }

        .p-4.p-md-5 { padding: 20px !important; }

        .meta-box { padding: 14px; border-radius: 13px; }
        .meta-box h6 { font-size: 13px; }

        .token-container label { font-size: 14px !important; }
        .token-input {
            font-size: 1.3rem;
            letter-spacing: 5px;
            padding: 14px !important;
            border-radius: 13px;
        }

        .btn-start, .btn-back-setup {
            padding: 13px 22px;
            font-size: 13.5px;
            border-radius: 13px;
        }
    }

    @media (max-width: 400px) {
        .token-input { font-size: 1.1rem; letter-spacing: 3px; }
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="setup-card">
                {{-- Header --}}
                <div class="setup-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white bg-opacity-10 p-3 rounded-4">
                            <i class="fa-solid fa-file-signature fa-2x text-white"></i>
                        </div>
                        <div>
                            <span class="badge mb-2" style="background: rgba(14, 165, 233, 0.2); color: var(--accent-blue);">
                                {{ optional($ujian->jenisUjian)->nama ?? 'Evaluasi' }}
                            </span>
                            <h3 class="fw-bold mb-0">{{ $ujian->nama_ujian }}</h3>
                        </div>
                    </div>
                </div>

                <div class="p-4 p-md-5">
                    
                    {{-- Alert Error Global dari session --}}
                    @if(session('error'))
                        <div class="alert alert-danger rounded-4 border-0 mb-4 p-3 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    {{-- Detail Ujian --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="meta-box h-100">
                                <small class="text-uppercase fw-bold d-block mb-1" style="font-size: 10px; color: var(--text-muted);">Mata Pelajaran</small>
                                <h6 class="fw-bold mb-0" style="color: var(--primary-dark);">
                                    <i class="fa-solid fa-book me-2" style="color: var(--primary);"></i>
                                    {{ optional(optional($ujian->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}
                                </h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="meta-box h-100">
                                <small class="text-uppercase fw-bold d-block mb-1" style="font-size: 10px; color: var(--text-muted);">Jumlah Soal</small>
                                <h6 class="fw-bold mb-0" style="color: var(--primary-dark);">
                                    <i class="fa-solid fa-list-check me-2" style="color: var(--accent-violet);"></i>
                                    {{ $totalSoal }} Butir Soal
                                </h6>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4" style="border-color: var(--border-color);">

                    {{-- Form Validasi Token --}}
                    <form action="{{ route('dashboard-siswa.ujian.proses-masuk', $ujian->id) }}" method="POST">
                        @csrf
                        
                        {{-- Logika: Tampilkan input token hanya jika token_aktif bernilai true --}}
                        @if($ujian->token_aktif)
                            <div class="token-container text-center mb-5">
                                <label for="token" class="form-label fw-bold mb-2 fs-5" style="color: var(--primary-dark);">
                                    <i class="fa-solid fa-key me-2 text-warning"></i> Masukkan Token Ujian
                                </label>
                                <input type="text" 
                                       name="token" 
                                       id="token" 
                                       class="form-control token-input @error('token') is-invalid @enderror" 
                                       placeholder="------" 
                                       maxlength="6" 
                                       value="{{ old('token') }}"
                                       required 
                                       autocomplete="off"
                                       autofocus>
                                
                                @error('token')
                                    <div class="invalid-feedback fw-semibold mt-2 fs-6">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text mt-2" style="color: var(--text-muted);">
                                    Minta token kepada guru / pengawas untuk dapat membuka lembar ujian.
                                </div>
                            </div>
                        @else
                            <div class="alert alert-token-info border-0 mb-5 p-3 text-center">
                                <i class="fa-solid fa-circle-info me-2"></i> Ujian ini dapat langsung diakses tanpa menggunakan token pengawas.
                            </div>
                        @endif

                        {{-- Tombol Aksi --}}
                        <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-center">
                            <a href="{{ route('dashboard-siswa.ujian-hari-ini') }}" class="btn btn-light px-4 py-3 rounded-4 fw-semibold text-secondary w-100 w-md-auto">
                                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                            </a>
                            <button type="submit" class="btn-start w-100 w-md-auto">
                                Verifikasi & Mulai Ujian <i class="fa-solid fa-circle-play ms-2"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection