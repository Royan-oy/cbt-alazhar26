@extends('layouts.app')
@section('title', 'Tambah Bank Soal')

@section('content')

<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    /* ===== PAGE HEADER ===== */
    .page-header-create {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        border-radius: 24px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .page-header-create::after {
        content: '';
        position: absolute;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        right: -60px;
        top: -90px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.18) 0%, rgba(14, 165, 233, 0) 70%);
        pointer-events: none;
    }

    .breadcrumb-nav a {
        color: #94a3b8;
        text-decoration: none;
        font-size: 13px;
        transition: color 0.2s;
    }

    .breadcrumb-nav a:hover { color: #e2e8f0; }
    .breadcrumb-nav span { color: #64748b; font-size: 13px; }

    /* ===== FORM CARD ===== */
    .form-card {
        background: var(--surface-white);
        border-radius: 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }

    .form-card-header {
        padding: 24px 28px;
        border-bottom: 1px solid var(--border-color);
        background: #f8fafc;
    }

    .form-card-header h5 {
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 4px;
        font-size: 1.05rem;
    }

    .form-card-header p {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 0;
    }

    .form-card-body {
        padding: 28px;
    }

    /* ===== FORM CONTROLS ===== */
    .form-group {
        margin-bottom: 24px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label-custom {
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 14px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label-custom .required-dot {
        width: 6px;
        height: 6px;
        background: #ef4444;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .form-label-custom .label-hint {
        font-weight: 400;
        color: var(--text-muted);
        font-size: 12px;
        margin-left: auto;
    }

    .form-control-modern,
    .form-select-modern {
        border-radius: 14px;
        border: 1.5px solid var(--border-color);
        padding: 12px 16px;
        font-size: 14px;
        background-color: #f8fafc;
        transition: all 0.2s ease;
        color: var(--primary-dark);
        width: 100%;
    }

    .form-control-modern:focus,
    .form-select-modern:focus {
        background-color: #fff;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
        outline: none;
    }

    .form-control-modern::placeholder {
        color: #94a3b8;
    }

    textarea.form-control-modern {
        resize: vertical;
        min-height: 100px;
    }

    .form-select-modern {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M2.22 4.47a.75.75 0 011.06 0L6 7.19l2.72-2.72a.75.75 0 011.06 1.06l-3.25 3.25a.75.75 0 01-1.06 0L2.22 5.53a.75.75 0 010-1.06z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 12px;
        padding-right: 40px;
    }

    /* ===== ERROR DISPLAY ===== */
    .field-error {
        color: #ef4444;
        font-size: 12px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .is-invalid {
        border-color: #fca5a5 !important;
        background-color: #fef2f2 !important;
    }

    .is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important;
        border-color: #ef4444 !important;
    }

    /* ===== FORM FOOTER ===== */
    .form-footer {
        padding: 20px 28px;
        border-top: 1px solid var(--border-color);
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        color: var(--text-muted);
        border: 1.5px solid var(--border-color);
        border-radius: 14px;
        padding: 11px 22px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .btn-back:hover {
        color: var(--primary-dark);
        border-color: #cbd5e1;
        background: #f1f5f9;
        text-decoration: none;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
        border: none;
        border-radius: 14px;
        padding: 12px 28px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: 0.25s ease;
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.25);
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(14, 165, 233, 0.35);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    /* ===== INFO TIP ===== */
    .info-tip {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        padding: 14px 18px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
        margin-bottom: 24px;
    }

    .info-tip i {
        color: #3b82f6;
        font-size: 16px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .info-tip p {
        font-size: 13px;
        color: #1e40af;
        margin: 0;
        line-height: 1.5;
    }

    /* ===== MOBILE RESPONSIVE ===== */
    @media (max-width: 767.98px) {
        .page-header-create {
            padding: 24px 20px;
            border-radius: 18px;
        }

        .form-card {
            border-radius: 18px;
        }

        .form-card-header {
            padding: 20px;
        }

        .form-card-body {
            padding: 20px;
        }

        .form-footer {
            padding: 16px 20px;
            flex-direction: column-reverse;
        }

        .btn-back,
        .btn-submit {
            width: 100%;
            justify-content: center;
        }

        .form-control-modern,
        .form-select-modern {
            padding: 11px 14px;
        }
    }
</style>

<div class="container-fluid py-2">

    {{-- Page Header --}}
    <div class="page-header-create mb-4">
        <div class="breadcrumb-nav mb-3">
            <a href="{{ route('dashboard-guru.bank-soal.index') }}">
                <i class="fa-solid fa-folder-open me-1"></i> Bank Soal
            </a>
            <span class="mx-2">/</span>
            <span class="text-white">Tambah Baru</span>
        </div>
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
            Tambah Bank Soal Baru
        </h3>
        <p class="text-light opacity-75 mb-0 small">
            Isi formulir di bawah untuk membuat bank soal baru.
        </p>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="alert alert-danger rounded-4 border-0 shadow-sm p-3 mb-4">
        <div class="d-flex align-items-center mb-2">
            <i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>
            <strong>Terdapat kesalahan pada input:</strong>
        </div>
        <ul class="mb-0 ps-4" style="font-size: 13px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="form-card">
        <div class="form-card-header">
            <h5><i class="fa-solid fa-file-circle-plus me-2 text-primary"></i>Informasi Bank Soal</h5>
            <p>Lengkapi data bank soal yang ingin Anda buat. Kolom bertanda <span style="color: #ef4444;">●</span> wajib diisi.</p>
        </div>

        <form action="{{ route('dashboard-guru.bank-soal.store') }}" method="POST" id="formCreateBankSoal">
            @csrf

            <div class="form-card-body">

                {{-- Info Tip --}}
                <div class="info-tip">
                    <i class="fa-solid fa-lightbulb"></i>
                    <p>Bank soal akan dibuat dengan status <strong>Draft</strong> secara default. Anda dapat mempublikasikannya nanti dari halaman detail.</p>
                </div>

                {{-- Nama Bank Soal --}}
                <div class="form-group">
                    <label class="form-label-custom">
                        <span class="required-dot"></span>
                        Nama Bank Soal
                    </label>
                    <input type="text"
                           name="nama_bank_soal"
                           class="form-control-modern @error('nama_bank_soal') is-invalid @enderror"
                           placeholder="Contoh: Soal UTS Matematika Kelas 10"
                           value="{{ old('nama_bank_soal') }}"
                           required
                           autofocus>
                    @error('nama_bank_soal')
                        <div class="field-error">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Mata Pelajaran (guru_mapel) & Jenjang - 2 Kolom --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label-custom">
                                <span class="required-dot"></span>
                                Mata Pelajaran
                            </label>
                            <select name="guru_mapel_id"
                                    class="form-select-modern @error('guru_mapel_id') is-invalid @enderror"
                                    required>
                                <option value="">— Pilih Mata Pelajaran —</option>
                                @foreach($guruMapels as $gm)
                                    <option value="{{ $gm->id }}" {{ old('guru_mapel_id') == $gm->id ? 'selected' : '' }}>
                                        {{ $gm->mataPelajaran->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                            @error('guru_mapel_id')
                                <div class="field-error">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </div>
                            @enderror
                            @if($guruMapels->isEmpty())
                                <div class="field-error">
                                    <i class="fa-solid fa-circle-exclamation"></i> Anda belum ditugaskan mata pelajaran apapun. Hubungi admin jenjang.
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label-custom">
                                Jenjang
                            </label>
                            <input type="text"
                                   class="form-control-modern"
                                   value="{{ $jenjang->nama_jenjang ?? '-' }}"
                                   disabled>
                            {{-- Read-only, diambil dari data guru yang login. Tidak dikirim ke server (bukan input yang bernilai untuk form ini) --}}
                        </div>
                    </div>
                </div>

                {{-- Nilai KKM --}}
                <div class="form-group mt-3">
                    <label class="form-label-custom">
                        <span class="required-dot"></span>
                        Nilai KKM (Kriteria Ketuntasan Minimal)
                        <span class="label-hint">Dapat diubah (Skala 0 - 100)</span>
                    </label>
                    <input type="number"
                           name="kkm"
                           class="form-control-modern @error('kkm') is-invalid @enderror"
                           placeholder="75"
                           value="{{ old('kkm', 75) }}"
                           min="0"
                           max="100"
                           required>
                    @error('kkm')
                        <div class="field-error">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="form-group mt-3">
                    <label class="form-label-custom">
                        Deskripsi
                        <span class="label-hint">Opsional</span>
                    </label>
                    <textarea name="deskripsi"
                              class="form-control-modern @error('deskripsi') is-invalid @enderror"
                              rows="4"
                              placeholder="Tambahkan deskripsi atau catatan tentang bank soal ini...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="field-error">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>


            </div>

            {{-- Form Footer --}}
            <div class="form-footer">
                <a href="{{ route('dashboard-guru.bank-soal.index') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-check"></i>
                    Simpan Bank Soal
                </button>
            </div>
        </form>
    </div>

</div>

@endsection