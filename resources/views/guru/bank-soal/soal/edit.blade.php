@extends('layouts.app')
@section('title', 'Edit Soal')

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

    .breadcrumb-nav a { color: #94a3b8; text-decoration: none; font-size: 13px; transition: color 0.2s; }
    .breadcrumb-nav a:hover { color: #e2e8f0; }
    .breadcrumb-nav span { color: #64748b; font-size: 13px; }

    .form-card {
        background: var(--surface-white);
        border-radius: 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }

    .form-card-header { padding: 24px 28px; border-bottom: 1px solid var(--border-color); background: #f8fafc; }
    .form-card-header h5 { font-weight: 700; color: var(--primary-dark); margin-bottom: 4px; font-size: 1.05rem; }
    .form-card-header p { font-size: 13px; color: var(--text-muted); margin-bottom: 0; }
    .form-card-body { padding: 28px; }

    .form-group { margin-bottom: 24px; }
    .form-group:last-child { margin-bottom: 0; }

    .form-label-custom {
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 14px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .form-label-custom .required-dot { width: 6px; height: 6px; background: #ef4444; border-radius: 50%; flex-shrink: 0; }

    .form-control-modern, .form-select-modern {
        border-radius: 14px;
        border: 1.5px solid var(--border-color);
        padding: 12px 16px;
        font-size: 14px;
        background-color: #f8fafc;
        transition: all 0.2s ease;
        color: var(--primary-dark);
        width: 100%;
    }

    .form-control-modern:focus, .form-select-modern:focus {
        background-color: #fff;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
        outline: none;
    }

    .form-control-modern::placeholder { color: #94a3b8; }
    textarea.form-control-modern { resize: vertical; min-height: 120px; }

    .form-select-modern {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M2.22 4.47a.75.75 0 011.06 0L6 7.19l2.72-2.72a.75.75 0 011.06 1.06l-3.25 3.25a.75.75 0 01-1.06 0L2.22 5.53a.75.75 0 010-1.06z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 12px;
        padding-right: 40px;
    }

    .field-error { color: #ef4444; font-size: 12px; margin-top: 6px; display: flex; align-items: center; gap: 4px; }
    .is-invalid { border-color: #fca5a5 !important; background-color: #fef2f2 !important; }

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
    .btn-back:hover { color: var(--primary-dark); border-color: #cbd5e1; background: #f1f5f9; text-decoration: none; }

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
    .btn-submit:hover { background: linear-gradient(135deg, #0284c7, #0369a1); transform: translateY(-2px); box-shadow: 0 14px 28px rgba(14, 165, 233, 0.35); }

    /* ===== PILIHAN JAWABAN ===== */
    #pilihanJawabanSection {
        display: none;
        border: 1.5px dashed #bfdbfe;
        background: #f8fbff;
        border-radius: 16px;
        padding: 18px;
        margin-top: 8px;
    }
    #pilihanJawabanSection.active { display: block; }

    .pilihan-hint {
        font-size: 12px;
        color: #3b82f6;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .opsi-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        background: #fff;
        border: 1.5px solid var(--border-color);
        border-radius: 12px;
        padding: 8px 12px;
    }

    .opsi-radio-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        flex-shrink: 0;
    }

    .opsi-radio-wrap input[type="radio"] { width: 18px; height: 18px; accent-color: #16a34a; cursor: pointer; }

    .opsi-kode-badge {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
        font-weight: 700;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .opsi-row input[type="text"] {
        border: none;
        background: transparent;
        flex: 1;
        font-size: 13.5px;
        padding: 6px 4px;
        outline: none;
        min-width: 0;
    }

    .btn-remove-opsi {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: none;
        background: #fff1f2;
        color: #e11d48;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        cursor: pointer;
        transition: 0.2s;
        font-size: 12px;
    }
    .btn-remove-opsi:hover:not(:disabled) { background: #e11d48; color: #fff; }
    .btn-remove-opsi:disabled { opacity: 0.35; cursor: not-allowed; }

    .btn-add-opsi {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        border: 1.5px dashed #93c5fd;
        color: #2563eb;
        border-radius: 12px;
        padding: 9px 16px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 4px;
    }
    .btn-add-opsi:hover { background: #eff6ff; border-color: #2563eb; }

    @media (max-width: 767.98px) {
        .page-header-create { padding: 24px 20px; border-radius: 18px; }
        .form-card { border-radius: 18px; }
        .form-card-header { padding: 20px; }
        .form-card-body { padding: 20px; }
        .form-footer { padding: 16px 20px; flex-direction: column-reverse; }
        .btn-back, .btn-submit { width: 100%; justify-content: center; }
        .opsi-row { padding: 6px 8px; }
    }
</style>

<div class="container-fluid py-2">

    {{-- Page Header --}}
    <div class="page-header-create mb-4">
        <div class="breadcrumb-nav mb-3">
            <a href="{{ route('dashboard-guru.bank-soal.index') }}">Bank Soal</a>
            <span class="mx-2">/</span>
            <a href="{{ route('dashboard-guru.bank-soal.soal.index', $bank_soal->id) }}">{{ Str::limit($bank_soal->nama_bank_soal, 25) }}</a>
            <span class="mx-2">/</span>
            <span class="text-white">Edit Soal</span>
        </div>
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Edit Soal</h3>
        <p class="text-light opacity-75 mb-0 small">Perbarui soal nomor {{ $soal->urutan }} pada bank soal ini.</p>
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

    <div class="form-card">
        <div class="form-card-header">
            <h5><i class="fa-solid fa-circle-question me-2 text-primary"></i>Informasi Soal</h5>
            <p>Kolom bertanda <span style="color: #ef4444;">●</span> wajib diisi.</p>
        </div>

        <form action="{{ route('dashboard-guru.bank-soal.soal.update', [$bank_soal->id, $soal->id]) }}" method="POST" id="formSoal">
            @csrf
            @method('PUT')

            <div class="form-card-body">

                {{-- Jenis Soal --}}
                <div class="form-group">
                    <label class="form-label-custom"><span class="required-dot"></span> Jenis Soal</label>
                    <select name="jenis_soal" id="jenisSoal" class="form-select-modern @error('jenis_soal') is-invalid @enderror" required>
                        <option value="">— Pilih Jenis Soal —</option>
                        <option value="pilihan_ganda" {{ old('jenis_soal', $soal->jenis_soal) == 'pilihan_ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                        <option value="essay" {{ old('jenis_soal', $soal->jenis_soal) == 'essay' ? 'selected' : '' }}>Essay</option>
                        <option value="isian" {{ old('jenis_soal', $soal->jenis_soal) == 'isian' ? 'selected' : '' }}>Isian Singkat</option>
                    </select>
                    @error('jenis_soal')
                        <div class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Bobot --}}
                <div class="form-group">
                    <label class="form-label-custom">
                        <span class="required-dot"></span> Bobot Nilai
                    </label>
                    <input type="number" name="bobot" min="1"
                           class="form-control-modern @error('bobot') is-invalid @enderror"
                           placeholder="Contoh: 10"
                           value="{{ old('bobot', $soal->bobot) }}" required>
                    @error('bobot')
                        <div class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Teks Soal --}}
                <div class="form-group">
                    <label class="form-label-custom"><span class="required-dot"></span> Teks Soal</label>
                    <textarea name="teks_soal" class="form-control-modern @error('teks_soal') is-invalid @enderror"
                              placeholder="Tulis pertanyaan di sini...">{{ old('teks_soal', $soal->teks_soal) }}</textarea>
                    @error('teks_soal')
                        <div class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Pilihan Jawaban (khusus Pilihan Ganda) --}}
                <div class="form-group mb-0">
                    <label class="form-label-custom"><span class="required-dot"></span> Opsi Jawaban</label>

                    <div id="pilihanJawabanSection">
                        <div class="pilihan-hint">
                            <i class="fa-solid fa-circle-info"></i>
                            Klik radio di samping opsi untuk menandai jawaban yang benar. Minimal 2 opsi.
                        </div>

                        @php
                            $pilihanCollection = $soal->pilihanJawabans; // sudah diurutkan berdasarkan 'urutan' dari controller
                            $existingOpsi = old('teks_pilihan', $pilihanCollection->pluck('teks_pilihan')->toArray());
                            $benarIndex = $pilihanCollection->search(fn ($p) => $p->is_benar);
                            $existingBenar = old('jawaban_benar', $benarIndex !== false ? $benarIndex : 0);
                            if (empty($existingOpsi)) {
                                $existingOpsi = ['', ''];
                            }
                        @endphp
                        <div id="opsiContainer">
                            @foreach ($existingOpsi as $i => $teks)
                                <div class="opsi-row">
                                    <div class="opsi-radio-wrap">
                                        <input type="radio" name="jawaban_benar" value="{{ $i }}" {{ (string) $existingBenar === (string) $i ? 'checked' : '' }}>
                                    </div>
                                    <span class="opsi-kode-badge opsi-kode">{{ chr(65 + $i) }}</span>
                                    <input type="text" name="teks_pilihan[]" value="{{ $teks }}" placeholder="Teks opsi {{ chr(65 + $i) }}...">
                                    <button type="button" class="btn-remove-opsi" onclick="removeOpsiRow(this)" {{ count($existingOpsi) <= 2 ? 'disabled' : '' }} title="Hapus opsi">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn-add-opsi" id="btnAddOpsi" onclick="addOpsiRow()">
                            <i class="fa-solid fa-plus"></i> Tambah Opsi
                        </button>

                        @error('teks_pilihan')
                            <div class="field-error mt-2"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                        @enderror
                        @error('jawaban_benar')
                            <div class="field-error mt-2"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="form-footer">
                <a href="{{ route('dashboard-guru.bank-soal.soal.index', $bank_soal->id) }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-check"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const MAX_OPSI = 8;

    function toggleJenisSoal() {
        const jenis = document.getElementById('jenisSoal').value;
        const section = document.getElementById('pilihanJawabanSection');
        section.classList.toggle('active', jenis === 'pilihan_ganda');
    }

    function renumberOpsi() {
        const rows = document.querySelectorAll('.opsi-row');
        rows.forEach((row, idx) => {
            const radio = row.querySelector('input[type="radio"]');
            radio.value = idx;
            const kodeBadge = row.querySelector('.opsi-kode');
            kodeBadge.textContent = String.fromCharCode(65 + idx);
        });
        const removeBtns = document.querySelectorAll('.btn-remove-opsi');
        const disableRemove = rows.length <= 2;
        removeBtns.forEach(btn => btn.disabled = disableRemove);

        document.getElementById('btnAddOpsi').style.display = rows.length >= MAX_OPSI ? 'none' : 'inline-flex';
    }

    function addOpsiRow() {
        const rows = document.querySelectorAll('.opsi-row');
        if (rows.length >= MAX_OPSI) return;

        const template = rows[0].cloneNode(true);
        template.querySelector('input[type="text"]').value = '';
        template.querySelector('input[type="radio"]').checked = false;
        document.getElementById('opsiContainer').appendChild(template);
        renumberOpsi();
    }

    function removeOpsiRow(btn) {
        const rows = document.querySelectorAll('.opsi-row');
        if (rows.length <= 2) return;
        btn.closest('.opsi-row').remove();
        renumberOpsi();
    }

    document.getElementById('jenisSoal').addEventListener('change', toggleJenisSoal);
    document.addEventListener('DOMContentLoaded', function () {
        toggleJenisSoal();
        renumberOpsi();
    });
</script>

@endsection