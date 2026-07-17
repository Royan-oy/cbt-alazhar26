@extends('layouts.app')

@section('title', 'Konfirmasi Ujian')

@section('content')
<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --card-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.04), 0 4px 12px -5px rgba(15, 23, 42, 0.04);
    }

    .setup-card {
        background: var(--surface-white);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .setup-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        padding: 35px;
        color: var(--surface-white);
    }

    .meta-box {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px;
    }

    .token-container {
        max-width: 400px;
        margin: 0 auto;
    }

    .token-input {
        letter-spacing: 8px;
        text-transform: uppercase;
        font-weight: 700;
        font-size: 1.75rem;
        text-align: center;
        border: 2px solid var(--border-color);
        border-radius: 16px;
        color: var(--primary-dark);
        transition: all 0.3s;
    }

    .token-input:focus {
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
        outline: none;
    }

    .btn-start {
        background: var(--accent-blue);
        color: var(--surface-white);
        border: none;
        border-radius: 16px;
        padding: 16px 32px;
        font-weight: 600;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
    }

    .btn-start:hover {
        background: #0284c7;
        color: var(--surface-white);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(14, 165, 233, 0.3);
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
                                    <i class="fa-solid fa-book text-primary me-2"></i>
                                    {{ optional(optional($ujian->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}
                                </h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="meta-box h-100">
                                <small class="text-uppercase fw-bold d-block mb-1" style="font-size: 10px; color: var(--text-muted);">Jumlah Soal</small>
                                <h6 class="fw-bold mb-0" style="color: var(--primary-dark);">
                                    <i class="fa-solid fa-list-check text-success me-2"></i>
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
                                       autocomplete="off">
                                
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
                            <div class="alert alert-info rounded-4 border-0 mb-5 p-3 text-center">
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