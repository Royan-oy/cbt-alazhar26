@extends('layouts.app')

@section('title','Tambah Tingkat')

@section('content')

<style>
    /* Global Variables */
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --surface-white: #ffffff;
        --border-color: #e2e8f0;
        --bg-body: #f8fafc;
    }

    body {
        background-color: var(--bg-body);
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        border-radius: 16px;
        padding: 24px 32px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
    }

    .page-header::after {
        content: '';
        position: absolute;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.15) 0%, rgba(56, 189, 248, 0) 70%);
        right: -40px;
        top: -60px;
        pointer-events: none;
    }

    .page-header > * {
        position: relative;
        z-index: 2;
    }

    /* Cards */
    .content-card {
        border: 1px solid var(--border-color);
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
        background: var(--surface-white);
        height: 100%; /* Agar tinggi card sejajar */
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        color: var(--secondary-dark);
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control,
    .form-select {
        height: 44px; /* Diperkecil agar lebih padat */
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        box-shadow: none;
        transition: all 0.2s ease;
        font-size: 14px;
        background-color: var(--bg-body);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--accent-blue);
        background-color: var(--surface-white);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.12);
    }

    .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-text {
        font-size: 12px;
        color: #64748b;
        margin-top: 6px;
    }

    /* Buttons */
    .btn-action {
        border-radius: 10px;
        padding: 10px 22px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-back {
        background-color: #f1f5f9;
        color: var(--secondary-dark);
        border: 1px solid transparent;
    }

    .btn-back:hover {
        background-color: #e2e8f0;
        color: var(--primary-dark);
        transform: translateY(-1px);
    }

    .btn-save {
        background-color: #2563eb;
        color: white;
        border: none;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
    }

    .btn-save:hover {
        background-color: #1d4ed8;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
    }

    /* Info Box */
    .info-box {
        background: #f0f9ff;
        border: 1px dashed #bae6fd;
        border-radius: 12px;
        padding: 20px;
    }

    .info-box i.icon-main {
        font-size: 32px;
        color: var(--accent-blue);
        margin-bottom: 12px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .page-header {
            padding: 20px;
            text-align: center;
        }
        
        .card-body {
            padding: 20px !important;
        }

        .action-buttons-container {
            flex-direction: column-reverse;
            gap: 10px !important;
        }

        .btn-action {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="page-header">
        <span class="badge bg-info bg-opacity-25 text-info mb-3 px-2 py-1 rounded fw-semibold" style="font-size: 11px;">
            Master Akademik
        </span>
        <h4 class="fw-bold mb-1" style="letter-spacing: -0.3px;">
            Tambah Tingkat
        </h4>
        <p class="mb-0 text-light opacity-75" style="font-size: 13px;">
            Tambahkan tingkat baru sesuai jenjang pendidikan yang tersedia pada sistem CBT.
        </p>
    </div>

    <form action="{{ route('tingkat.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            
            {{-- Form Column --}}
            <div class="col-lg-8">
                <div class="card content-card">
                    <div class="card-body p-4 p-md-5">
                        
                        <h6 class="fw-bold mb-4 text-dark border-bottom pb-3">
                            <i class="fa-solid fa-school text-primary me-2"></i>
                            Informasi Tingkat
                        </h6>

                        {{-- Jenjang --}}
                        <div class="mb-4">
                            <label class="form-label">
                                Jenjang <span class="required">*</span>
                            </label>

                            <select
                                name="jenjang_id"
                                class="form-select @error('jenjang_id') is-invalid @enderror"
                                {{ Auth::user()->role == 'admin_jenjang' ? 'disabled' : '' }}>
                                <option value="">-- Pilih Jenjang --</option>
                                @foreach($jenjangs as $jenjang)
                                    <option
                                        value="{{ $jenjang->id }}"
                                        {{ old('jenjang_id', $jenjangs->count() == 1 ? $jenjang->id : '') == $jenjang->id ? 'selected' : '' }}>
                                        {{ $jenjang->nama_jenjang }}
                                    </option>
                                @endforeach
                            </select>

                            @if(Auth::user()->role == 'admin_jenjang')
                                <input
                                    type="hidden"
                                    name="jenjang_id"
                                    value="{{ $jenjangs->first()->id }}">
                            @endif

                            @error('jenjang_id')
                                <div class="invalid-feedback fw-medium mt-2">
                                    <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Nama Tingkat --}}
                        <div class="mb-4">
                            <label class="form-label">
                                Nama Tingkat <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nama_tingkat"
                                value="{{ old('nama_tingkat') }}"
                                class="form-control @error('nama_tingkat') is-invalid @enderror"
                                placeholder="Contoh: Kelas 1">

                            @error('nama_tingkat')
                                <div class="invalid-feedback fw-medium mt-2">
                                    <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                Contoh penulisan: Kelas 1, Kelas 2, Kelas VII, Kelas X.
                            </div>
                        </div>

                        <hr class="border-light mt-5 mb-4">

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-end gap-3 action-buttons-container">
                            <a href="{{ route('tingkat.index') }}" class="btn btn-action btn-back">
                                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                            </a>

                            <button type="submit" class="btn btn-action btn-save">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Tingkat
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Info Column --}}
            <div class="col-lg-4">
                <div class="card content-card">
                    <div class="card-body p-4">
                        
                        <h6 class="fw-bold mb-4 text-dark border-bottom pb-3">
                            <i class="fa-solid fa-circle-info text-info me-2"></i>
                            Panduan
                        </h6>

                        <div class="info-box text-center mt-2">
                            <i class="fa-solid fa-layer-group icon-main"></i>
                            <h6 class="fw-bold text-dark mb-2">Tingkat Akademik</h6>
                            
                            <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.6;">
                                Tingkat digunakan sebagai dasar pembentukan kelas pada sistem. Pastikan Anda memilih Jenjang yang tepat sebelum menginput nama tingkat.
                            </p>
                            
                            <div class="mt-3 text-start bg-white p-3 rounded-3 border">
                                <span class="d-block fw-semibold text-dark mb-2" style="font-size: 12px;">Format yang disarankan:</span>
                                <ul class="text-muted mb-0 ps-3" style="font-size: 12px; line-height: 1.8;">
                                    <li>Kelas 1 (Untuk SD/MI)</li>
                                    <li>Kelas VII (Untuk SMP/MTs)</li>
                                    <li>Kelas X (Untuk SMA/SMK)</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

@endsection