@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')

<style>
    /* Global Content Variable & Layout */
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-orange: #f59e0b;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    /* Page Header Overhaul */
    .page-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        border-radius: 24px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .page-header::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        right: -50px;
        top: -80px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, rgba(245, 158, 11, 0) 70%);
        pointer-events: none;
    }

    /* Modern Card Layout */
    .form-card {
        background: var(--surface-white);
        border-radius: 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.03);
        padding: 16px;
        margin-top: 24px;
    }

    /* Refined Form Inputs & Selects */
    .form-label-custom {
        font-size: 14px;
        color: var(--secondary-dark);
        margin-bottom: 8px;
    }

    .form-control-custom {
        border-radius: 14px;
        height: 50px;
        border: 1px solid var(--border-color);
        padding-left: 18px;
        font-size: 14px;
        transition: all 0.2s ease-in-out;
        background-color: #f8fafc;
    }

    .form-control-custom:focus {
        background-color: #fff;
        border-color: var(--accent-orange);
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.12);
    }

    /* Custom select style adjustment */
    select.form-control-custom {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 18px center;
        background-size: 14px;
        padding-right: 45px;
    }

    /* Action Buttons styling */
    .btn-custom-action {
        border-radius: 14px;
        padding: 12px 26px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
    }

    .btn-back-style {
        background-color: #f1f5f9;
        color: var(--text-muted);
        border: 1px solid var(--border-color);
    }

    .btn-back-style:hover {
        background-color: #e2e8f0;
        color: var(--primary-dark);
    }

    .btn-save-style {
        background-color: var(--accent-orange);
        color: var(--primary-dark);
        border: none;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
    }

    .btn-save-style:hover {
        background-color: #d97706;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(217, 119, 6, 0.35);
    }

    /* Custom Validation Styling */
    .form-control-custom.is-invalid {
        border-color: #ef4444;
        background-color: #fef2f2;
    }
    
    .form-control-custom.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.12);
    }

    .invalid-feedback-custom {
        font-size: 12px;
        color: #ef4444;
        margin-top: 6px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* --- CSS MEDIA QUERIES (RESPONSIVE VIEW) --- */
    @media (max-width: 768px) {
        .page-header {
            padding: 24px;
            border-radius: 18px;
            text-align: center;
        }

        .form-card {
            padding: 8px;
            border-radius: 18px;
        }

        .card-body {
            padding: 16px !important;
        }

        /* Stack buttons vertically on mobile */
        .btn-group-mobile {
            flex-direction: column-reverse;
            gap: 12px !important;
        }

        .btn-custom-action {
            width: 100%;
            justify-content: center;
            height: 48px;
            display: inline-flex;
            align-items: center;
        }
    }
</style>

<div class="container-fluid py-2">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div>
            <span class="badge bg-warning bg-opacity-25 text-warning px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                MASTER DATA
            </span>
            <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
                Edit Data Kelas
            </h3>
            <p class="text-light opacity-75 mb-0 small">
                Perbarui rincian informasi tingkat jenjang dan alokasi nama kelas siswa di bawah ini.
            </p>
        </div>
    </div>

    {{-- Form Content Card --}}
    <div class="card form-card">
        <div class="card-body p-4">

            {{-- Error Banner Bawaan (Jika Dibutuhkan Global Peringatan) --}}
            @if ($errors->any())
                <div class="alert alert-danger rounded-4 border-0 p-3 mb-4 small d-flex align-items-start gap-2">
                    <i class="fa-solid fa-triangle-exclamation fs-5 mt-0.5"></i>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Input: Tingkat --}}
                <div class="mb-4">
                    <label class="form-label form-label-custom fw-semibold">
                        Tingkat <span class="text-danger">*</span>
                    </label>

                    <select name="tingkat_id" class="form-select form-control-custom @error('tingkat_id') is-invalid @enderror" required>
                        @foreach($tingkats as $tingkat)
                            <option value="{{ $tingkat->id }}" {{ old('tingkat_id', $kelas->tingkat_id) == $tingkat->id ? 'selected' : '' }}>
                                @if($tingkat->relationLoaded('jenjang') && $tingkat->jenjang)
                                    {{ $tingkat->jenjang->nama_jenjang }} - 
                                @endif
                                {{ $tingkat->nama_tingkat }}
                            </option>
                        @endforeach
                    </select>

                    @error('tingkat_id')
                        <div class="invalid-feedback-custom">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Input: Nama Kelas --}}
                <div class="mb-5">
                    <label class="form-label form-label-custom fw-semibold">
                        Nama Kelas <span class="text-danger">*</span>
                    </label>

                    <input 
                        type="text" 
                        name="nama_kelas" 
                        value="{{ old('nama_kelas', $kelas->nama_kelas) }}" 
                        class="form-control form-control-custom @error('nama_kelas') is-invalid @enderror" 
                        placeholder="Contoh: A, B, atau C"
                        autocomplete="off"
                        required>
                    
                    <div class="d-flex align-items-center gap-1.5 mt-2 text-muted" style="font-size: 12px;">
                        <i class="fa-solid fa-circle-info opacity-75"></i>
                        <span>Contoh penulisan: A, B, C, Abu Bakar, Umar, dll.</span>
                    </div>

                    @error('nama_kelas')
                        <div class="invalid-feedback-custom">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Form Actions Section --}}
                <div class="d-flex justify-content-end gap-3 btn-group-mobile">
                    <a href="{{ route('kelas.index') }}" class="btn btn-custom-action btn-back-style d-inline-flex align-items-center">
                        <i class="fa-solid fa-arrow-left me-2"></i> Batal
                    </a>

                    <button type="submit" class="btn btn-custom-action btn-save-style d-inline-flex align-items-center">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection