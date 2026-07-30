@extends('layouts.app')

@section('title','Ubah Jenis Ujian')

@section('content')

<style>
    /* Global Variables */
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --accent-warning: #f59e0b;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --bg-body: #f8fafc;
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        border-radius: 20px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
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
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0) 70%);
        pointer-events: none;
    }

    /* Form Card */
    .form-card {
        background: var(--surface-white);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }

    /* Typography & Labels */
    .form-label {
        font-weight: 600;
        color: var(--secondary-dark);
        font-size: 14px;
        margin-bottom: 8px;
    }

    /* Form Controls */
    .form-control {
        border-radius: 12px;
        height: 48px;
        border: 1px solid var(--border-color);
        background-color: var(--bg-body);
        padding: 10px 16px;
        transition: all 0.2s ease-in-out;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: var(--accent-warning);
        background-color: var(--surface-white);
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
        outline: none;
    }

    textarea.form-control {
        height: 120px;
        resize: vertical;
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    /* Action Buttons */
    .btn-action {
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 600;
        letter-spacing: 0.3px;
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
        transform: translateY(-2px);
    }

    .btn-save {
        background-color: var(--accent-warning);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
    }

    .btn-save:hover {
        background-color: #d97706;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.3);
    }

    /* Input Uppercase specifically for Kode */
    .input-kode {
        text-transform: uppercase;
    }
    
    .input-kode::placeholder {
        text-transform: none;
    }

    /* --- RESPONSIVE MEDIA QUERIES --- */
    @media (max-width: 768px) {
        .page-header {
            padding: 24px 20px;
            border-radius: 16px;
            text-align: center;
        }

        .form-card {
            border-radius: 16px;
        }
        
        .card-body {
            padding: 20px !important;
        }

        /* Stack buttons full width on mobile */
        .action-buttons-container {
            flex-direction: column-reverse; /* Tombol Kembali di bawah, Simpan di atas */
            gap: 12px !important;
        }

        .btn-action {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="page-header mb-4">
        <span class="badge bg-warning bg-opacity-25 text-warning px-3 py-2 rounded-pill mb-3 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
            MASTER DATA &bull; EDIT
        </span>
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
            Ubah Jenis Ujian
        </h3>
        <p class="text-light opacity-75 mb-0 small">
            Perbarui informasi jenis ujian yang digunakan pada sistem CBT.
        </p>
    </div>

    {{-- Form Card --}}
    <div class="card form-card border-0">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('jenis-ujian.update', $jenisUjian->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label class="form-label">
                            Kode Jenis Ujian <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="kode"
                            maxlength="20"
                            class="form-control input-kode @error('kode') is-invalid @enderror"
                            value="{{ old('kode', $jenisUjian->kode) }}"
                            placeholder="Contoh: PTS">

                        @error('kode')
                            <div class="invalid-feedback fw-medium mt-2">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
                            </div>
                        @enderror
                        
                        <small class="text-muted mt-2 d-block" style="font-size: 12px;">
                            Maksimal 20 karakter.
                        </small>
                    </div>

                    <div class="col-md-8 mb-4">
                        <label class="form-label">
                            Nama Jenis Ujian <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama"
                            class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $jenisUjian->nama) }}"
                            placeholder="Contoh: Penilaian Tengah Semester">

                        @error('nama')
                            <div class="invalid-feedback fw-medium mt-2">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">
                        Deskripsi
                    </label>
                    <textarea
                        name="deskripsi"
                        class="form-control @error('deskripsi') is-invalid @enderror"
                        placeholder="Masukkan deskripsi jenis ujian (Opsional)">{{ old('deskripsi', $jenisUjian->deskripsi) }}</textarea>

                    @error('deskripsi')
                        <div class="invalid-feedback fw-medium mt-2">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <hr class="border-light mb-4">

                <div class="d-flex justify-content-end gap-3 action-buttons-container">
                    <a href="{{ route('jenis-ujian.index') }}" class="btn btn-action btn-back">
                        <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                    </a>

                    <button type="submit" class="btn btn-action btn-save">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection