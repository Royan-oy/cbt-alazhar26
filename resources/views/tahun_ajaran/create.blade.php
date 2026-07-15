@extends('layouts.app')

@section('title', 'Tambah Tahun Ajaran')

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

/* HEADER STYLE */
.page-header-premium {
    background: linear-gradient(135deg, #0f172a, #1e293b);
    border-radius: 20px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1);
}

.page-header-premium::after {
    content: '';
    position: absolute;
    width: 280px;
    height: 280px;
    right: -60px;
    top: -60px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.15), transparent 70%);
}

/* CARDS COMMON */
.form-card-modern {
    border: 1px solid var(--border-color);
    border-radius: 20px;
    background: white;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
}

/* FORM UTILITIES */
.form-label-premium {
    font-size: 13px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-control-premium,
.form-select-premium {
    height: 50px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    font-size: 14px;
    padding-left: 16px;
    color: var(--text-dark);
    transition: all 0.25s ease;
}

.form-control-premium:focus,
.form-select-premium:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    background-color: white;
}

/* UTILITY COMPONENT */
.info-box-premium {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 14px;
}

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
   MEDIA QUERIES (RESPONSIVE VIEW UNTUK HP & TABLET)
   ======================================================= */
@media (max-width: 991.98px) {
    .sidebar-info-space {
        margin-top: 24px;
    }
}

@media (max-width: 575.98px) {
    .page-header-premium {
        padding: 24px 20px !important;
    }
    
    .page-header-premium h3 {
        font-size: 20px;
    }

    .card-body-custom {
        padding: 20px !important;
    }

    .action-button-group {
        flex-direction: column-reverse;
        gap: 12px;
    }

    .action-button-group .btn {
        width: 100%;
        text-align: center;
        padding: 14px;
    }
}
</style>

<div class="container-fluid px-md-4 py-4">

    <div class="page-header-premium p-5 mb-4">
        <h3 class="fw-bold mb-2">
            <i class="fa-solid fa-calendar-plus me-2 text-indigo-400"></i>
            Tambah Tahun Ajaran
        </h3>
        <p class="mb-0 text-white-50 small max-w-xl">
            Inisialisasi konfigurasi matriks masa bakti akademik dan semester acuan pengerjaan ujian pada ekosistem CBT.
        </p>
    </div>

    <form action="{{ route('tahun-ajaran.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card form-card-modern">
                    <div class="card-body card-body-custom p-4">
                        
                        <div class="mb-4">
                            <label class="form-label-premium mb-2">Tahun Ajaran</label>
                            <input
                                type="text"
                                name="nama_tahun"
                                value="{{ old('nama_tahun') }}"
                                class="form-control form-control-premium @error('nama_tahun') is-invalid @enderror"
                                placeholder="Contoh: 2026/2027"
                                required>

                            @error('nama_tahun')
                                <div class="invalid-feedback mt-2">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="mt-2 text-muted" style="font-size: 12px;">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> Wajib mengikuti format penulisan dual tahun seperti <strong>2026/2027</strong>.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-premium mb-2">Semester Berjalan</label>
                            <select
                                name="semester"
                                class="form-select form-select-premium @error('semester') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Semester --</option>
                                <option value="ganjil" {{ old('semester') == 'ganjil' ? 'selected' : '' }}>
                                    Semester Ganjil (Odd Semester)
                                </option>
                                <option value="genap" {{ old('semester') == 'genap' ? 'selected' : '' }}>
                                    Semester Genap (Even Semester)
                                </option>
                            </select>

                            @error('semester')
                                <div class="invalid-feedback mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="alert alert-light border rounded-3 p-3 mb-4 d-flex align-items-start">
                            <i class="fa-solid fa-circle-info text-primary mt-1 me-2"></i>
                            <span class="text-muted small" style="margin-left: 4px;">
                                Secara default, record baru akan disimpan dengan status <strong>Tidak Aktif</strong>. 
                                Anda dapat mengaktifkannya melalui tombol validasi di menu utama kapan saja diperlukan.
                            </span>
                        </div>

                        <div class="d-flex gap-2 action-button-group pt-2">
                            <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-light-custom">
                                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-premium-save px-4 ms-sm-auto">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Record
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 sidebar-info-space">
                <div class="card form-card-modern border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center">
                            <i class="fa-solid fa-shield-halved text-warning me-2"></i>
                            Aturan Validasi Sistem
                        </h6>
                        
                        <div class="info-box-premium p-3">
                            <ul class="mb-0 ps-3 text-muted small" style="line-height: 1.6;">
                                <li class="mb-2">
                                    Satu siklus tahun ajaran wajib dipisahkan ke dalam 2 entitas entri terpisah (<strong>Ganjil</strong> & <strong>Genap</strong>).
                                </li>
                                <li class="mb-2">
                                    Sistem memproteksi duplikasi kombinasi. Tidak diizinkan membuat data <strong>Tahun Ajaran + Semester</strong> yang sama.
                                </li>
                                <li class="mb-2">
                                    Hanya diperbolehkan ada <strong>satu periode aktif</strong> yang berjalan secara real-time demi integritas pengerjaan kuis siswa.
                                </li>
                                <li>
                                    Pengaktifan berkas tahun ajaran baru otomatis menonaktifkan status tahun ajaran lama.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

@endsection