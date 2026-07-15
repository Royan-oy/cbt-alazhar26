@extends('layouts.app')

@section('title', 'Edit Jenjang')

@section('content')

<style>
:root {
    --primary-color: #4f46e5;
    --primary-hover: #4338ca;
    --bg-main: #f8fafc;
    --text-dark: #0f172a;
    --text-muted: #64748b;
    --border-color: #e2e8f0;
}

body {
    background: var(--bg-main);
    color: var(--text-dark);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* CONTAINER CARD & HEADER */
.custom-card {
    border: 1px solid var(--border-color);
    border-radius: 24px;
    background: white;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.03), 0 8px 16px -6px rgba(15, 23, 42, 0.02);
    overflow: hidden;
}

.card-header-modern {
    background: white;
    border-bottom: 1px solid #f1f5f9;
    padding: 32px 32px 20px 32px;
}

.card-header-modern h4 {
    font-weight: 800;
    color: var(--text-dark);
    letter-spacing: -0.5px;
}

/* FORM STYLING */
.form-label-custom {
    font-size: 13px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-control-custom {
    border-radius: 12px;
    padding: 14px 16px;
    border: 1px solid var(--border-color);
    font-size: 15px;
    transition: all 0.25s ease;
    color: var(--text-dark);
}

.form-control-custom:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    background-color: white;
}

.form-control-custom:disabled, .form-control-custom[readonly] {
    background-color: #f8fafc;
    color: #94a3b8;
    border-style: dashed;
}

/* BUTTONS */
.btn-premium-save {
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
}

.btn-premium-save:hover {
    background: var(--primary-hover);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(79, 70, 229, 0.25);
}

.btn-light-custom {
    background: #f1f5f9;
    color: #475569;
    border: none;
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-light-custom:hover {
    background: #e2e8f0;
    color: #1e293b;
}

/* =======================================================
   MEDIA QUERIES (RESPONSIVE VIEW UNTUK HP)
   ======================================================= */
@media (max-width: 575.98px) {
    .card-header-modern {
        padding: 24px 20px 16px 20px;
    }
    
    .card-body-custom {
        padding: 20px !important;
    }
    
    .action-group-btn {
        flex-direction: column-reverse;
        gap: 12px;
    }
    
    .action-group-btn .btn {
        width: 100%;
        text-align: center;
        padding: 14px;
    }
}
</style>

<div class="container-fluid px-md-4 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-7">
            
            <div class="card custom-card">
                
                <div class="card-header-modern">
                    <h4 class="fw-bold mb-1">Edit Jenjang</h4>
                    <p class="text-muted mb-0 small">Perbarui data atau penyesuaian informasi tingkat jenjang sekolah.</p>
                </div>

                <div class="card-body card-body-custom p-5">
                    <form action="{{ route('jenjang.update', $jenjang->slug) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label form-label-custom mb-2">Nama Jenjang</label>
                            <input 
                                type="text" 
                                name="nama_jenjang" 
                                class="form-control form-control-custom @error('nama_jenjang') is-invalid @enderror" 
                                value="{{ old('nama_jenjang', $jenjang->nama_jenjang) }}" 
                                placeholder="Masukkan nama jenjang baru">

                            @error('nama_jenjang')
                            <div class="invalid-feedback mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="form-label form-label-custom mb-2">Slug Sistem</label>
                            <input 
                                type="text" 
                                class="form-control form-control-custom" 
                                value="{{ $jenjang->slug }}" 
                                readonly>
                            
                            <div class="p-3 bg-light rounded-3 d-flex align-items-start mt-2 border">
                                <i class="fa-solid fa-circle-info mt-1 text-primary me-2"></i>
                                <small class="text-muted" style="font-size: 12px; margin-left:6px;">
                                    Slug bersifat permanen untuk menjaga integritas data relasi tautan di database.
                                </small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center action-group-btn">
                            <a href="{{ route('jenjang.index') }}" class="btn btn-light-custom">
                                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                            </a>

                            <button type="submit" class="btn btn-premium-save">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection