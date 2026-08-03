@extends('layouts.app')
@section('title', 'Tambah Soal')

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

    /* ===== SEKSI DINAMIS OPSI ===== */
    .dynamic-section {
        display: none;
        border: 1.5px dashed #bfdbfe;
        background: #f8fbff;
        border-radius: 16px;
        padding: 20px;
        margin-top: 8px;
    }

    .pilihan-hint {
        font-size: 12.5px;
        color: #0284c7;
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

    .opsi-check-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        flex-shrink: 0;
    }

    .opsi-check-wrap input[type="radio"], .opsi-check-wrap input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #0ea5e9;
        cursor: pointer;
    }

    .opsi-kode-badge {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #eff6ff;
        color: #0284c7;
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
        color: #0284c7;
        border-radius: 12px;
        padding: 9px 16px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 8px;
    }
    .btn-add-opsi:hover { background: #eff6ff; border-color: #0284c7; }

    /* UPLOAD GAMBAR */
    .upload-container { width: 100%; margin-top: 8px; }
    .upload-box {
        border: 2px dashed var(--border-color);
        border-radius: 16px;
        background-color: #f8fafc;
        padding: 24px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }
    .upload-box:hover { border-color: var(--accent-blue); background-color: #f0f9ff; }
    .upload-box.has-file { padding: 0; border-style: solid; background-color: #fff; }
    .upload-content { pointer-events: none; }
    .upload-icon { font-size: 28px; color: #94a3b8; margin-bottom: 8px; }
    .upload-text { font-size: 13.5px; color: var(--primary-dark); font-weight: 600; }
    .upload-hint { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }
    .image-preview-wrapper { display: none; position: relative; padding: 12px; }
    .image-preview { max-height: 200px; border-radius: 12px; object-fit: contain; }
    .btn-remove-image {
        margin-top: 10px; background: #e11d48; color: white; border: none; padding: 6px 14px;
        border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer;
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
            <span class="text-white">Tambah Soal</span>
        </div>
        <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Tambah Soal Baru</h3>
        <p class="text-light opacity-75 mb-0 small">Isi formulir di bawah untuk menambahkan soal ke bank soal ini.</p>
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

        <form action="{{ route('dashboard-guru.bank-soal.soal.store', $bank_soal->id) }}" method="POST" id="formSoal" enctype="multipart/form-data">
            @csrf

            <div class="form-card-body">

                {{-- Jenis Soal --}}
                <div class="form-group">
                    <label class="form-label-custom"><span class="required-dot"></span> Jenis Soal</label>
                    <select name="jenis_soal" id="jenisSoal" class="form-select-modern @error('jenis_soal') is-invalid @enderror" required>
                        <option value="">— Pilih Jenis Soal —</option>
                        <option value="pilihan_ganda" {{ old('jenis_soal') == 'pilihan_ganda' ? 'selected' : '' }}>1. Pilihan Ganda (Single Choice)</option>
                        <option value="pilihan_ganda_kompleks" {{ old('jenis_soal') == 'pilihan_ganda_kompleks' ? 'selected' : '' }}>2. Pilihan Ganda Kompleks (Multi Select)</option>
                        <option value="benar_salah" {{ old('jenis_soal') == 'benar_salah' ? 'selected' : '' }}>3. Benar / Salah (Matrix)</option>
                        <option value="mencocokkan" {{ old('jenis_soal') == 'mencocokkan' ? 'selected' : '' }}>4. Mencocokkan / Menjodohkan</option>
                        <option value="isian" {{ old('jenis_soal') == 'isian' ? 'selected' : '' }}>5. Isian Singkat</option>
                        <option value="essay" {{ old('jenis_soal') == 'essay' ? 'selected' : '' }}>6. Uraian / Essay</option>
                    </select>
                    @error('jenis_soal')
                        <div class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Bobot --}}
                <div class="form-group">
                    <label class="form-label-custom"><span class="required-dot"></span> Bobot Nilai</label>
                    <input type="number" name="bobot" min="1" class="form-control-modern @error('bobot') is-invalid @enderror" placeholder="Contoh: 10" value="{{ old('bobot', 10) }}" required>
                    @error('bobot')
                        <div class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Teks Soal --}}
                <div class="form-group">
                    <label class="form-label-custom"><span class="required-dot"></span> Teks Soal / Pertanyaan</label>
                    <textarea name="teks_soal" class="form-control-modern @error('teks_soal') is-invalid @enderror" placeholder="Tuliskan teks pertanyaan di sini...">{{ old('teks_soal') }}</textarea>
                    @error('teks_soal')
                        <div class="field-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Gambar Pendukung --}}
                <div class="form-group">
                    <label class="form-label-custom">Gambar Pendukung (Opsional)</label>
                    <div class="upload-container">
                        <input type="file" name="gambar" id="gambarInput" class="d-none" accept="image/jpeg, image/png, image/jpg, image/gif">
                        <div class="upload-box" id="uploadBox" onclick="document.getElementById('gambarInput').click()">
                            <div class="upload-content" id="uploadContent">
                                <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                                <div class="upload-text">Klik atau seret gambar ke area ini</div>
                                <div class="upload-hint">Maksimal ukuran 2MB (JPG, PNG, GIF)</div>
                            </div>
                            <div class="image-preview-wrapper" id="previewWrapper">
                                <img src="" id="imagePreview" class="image-preview" alt="Preview Gambar">
                                <button type="button" class="btn-remove-image" onclick="removeImage(event)">
                                    <i class="fa-solid fa-trash-can"></i> Hapus Gambar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========================================================= --}}
                {{-- DYNAMIC SECTIONS PER JENIS SOAL --}}
                {{-- ========================================================= --}}

                {{-- 1. PILIHAN GANDA --}}
                <div class="dynamic-section" id="sec-pilihan_ganda">
                    <label class="form-label-custom"><span class="required-dot"></span> Opsi Jawaban Pilihan Ganda</label>
                    <div class="pilihan-hint"><i class="fa-solid fa-circle-info"></i> Pilih radio di sebelah kiri untuk menandai Kunci Jawaban Benar (1 opsi). Minimal 2 opsi, Maksimal 8 opsi.</div>
                    <div id="pgContainer">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="opsi-row pg-row">
                                <div class="opsi-check-wrap">
                                    <input type="radio" name="jawaban_benar" value="{{ $i }}" {{ old('jawaban_benar') == $i ? 'checked' : '' }}>
                                </div>
                                <span class="opsi-kode-badge pg-kode">{{ chr(65 + $i) }}</span>
                                <input type="text" name="teks_pilihan[]" value="{{ old('teks_pilihan.' . $i) }}" placeholder="Teks opsi {{ chr(65 + $i) }}...">
                                <button type="button" class="btn-remove-opsi" onclick="removePgRow(this)"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        @endfor
                    </div>
                    <button type="button" class="btn-add-opsi" id="btnAddPg" onclick="addPgRow()">
                        <i class="fa-solid fa-plus"></i> Tambah Opsi PG
                    </button>
                </div>

                {{-- 2. PILIHAN GANDA KOMPLEKS --}}
                <div class="dynamic-section" id="sec-pilihan_ganda_kompleks">
                    <label class="form-label-custom"><span class="required-dot"></span> Opsi Jawaban PG Kompleks</label>
                    <div class="pilihan-hint"><i class="fa-solid fa-circle-info"></i> Centang checkbox di sebelah kiri untuk menandai SEMUA Kunci Jawaban Benar (bisa >1 opsi). Minimal 2 opsi.</div>
                    <div id="pgkContainer">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="opsi-row pgk-row">
                                <div class="opsi-check-wrap">
                                    <input type="checkbox" name="jawaban_benar_kompleks[]" value="{{ $i }}" class="pgk-checkbox">
                                </div>
                                <span class="opsi-kode-badge pgk-kode">{{ chr(65 + $i) }}</span>
                                <input type="text" name="teks_pilihan[]" value="{{ old('teks_pilihan.' . $i) }}" placeholder="Teks opsi {{ chr(65 + $i) }}...">
                                <button type="button" class="btn-remove-opsi" onclick="removePgkRow(this)"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        @endfor
                    </div>
                    <button type="button" class="btn-add-opsi" id="btnAddPgk" onclick="addPgkRow()">
                        <i class="fa-solid fa-plus"></i> Tambah Opsi PG Kompleks
                    </button>
                </div>

                {{-- 3. BENAR / SALAH --}}
                <div class="dynamic-section" id="sec-benar_salah">
                    <label class="form-label-custom"><span class="required-dot"></span> Tabel Pernyataan Benar / Salah</label>
                    <div class="pilihan-hint"><i class="fa-solid fa-circle-info"></i> Masukkan teks pernyataan dan pilih kuncinya (Benar atau Salah) di tiap baris. Minimal 1 baris.</div>
                    <div id="bsContainer">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="opsi-row bs-row">
                                <span class="opsi-kode-badge bs-kode">{{ $i + 1 }}</span>
                                <input type="text" name="teks_pernyataan[]" placeholder="Pernyataan {{ $i + 1 }}..." value="{{ old('teks_pernyataan.'.$i) }}">
                                <select name="kunci_bs[]" class="form-select form-select-sm" style="max-width: 140px; border-radius: 8px;">
                                    <option value="benar">Benar</option>
                                    <option value="salah">Salah</option>
                                </select>
                                <button type="button" class="btn-remove-opsi" onclick="removeBsRow(this)"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        @endfor
                    </div>
                    <button type="button" class="btn-add-opsi" id="btnAddBs" onclick="addBsRow()">
                        <i class="fa-solid fa-plus"></i> Tambah Pernyataan
                    </button>
                </div>

                {{-- 4. MENCOCOKKAN --}}
                <div class="dynamic-section" id="sec-mencocokkan">
                    <label class="form-label-custom"><span class="required-dot"></span> Pasangan Mencocokkan / Menjodohkan</label>
                    <div class="pilihan-hint"><i class="fa-solid fa-circle-info"></i> Masukkan Item Kiri (Soal) dan Pasangan Kanan (Kunci Jawaban). Minimal 1 pasangan.</div>
                    <div id="matchingContainer">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="opsi-row matching-row">
                                <span class="opsi-kode-badge matching-kode">{{ $i + 1 }}</span>
                                <input type="text" name="item_kiri[]" placeholder="Item Kiri {{ $i + 1 }}...">
                                <span class="fw-bold text-primary px-1">➔</span>
                                <input type="text" name="item_kanan[]" placeholder="Pasangan Kanan {{ $i + 1 }}...">
                                <button type="button" class="btn-remove-opsi" onclick="removeMatchingRow(this)"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        @endfor
                    </div>
                    <button type="button" class="btn-add-opsi" id="btnAddMatching" onclick="addMatchingRow()">
                        <i class="fa-solid fa-plus"></i> Tambah Pasangan
                    </button>
                </div>

                {{-- 5. ISIAN SINGKAT --}}
                <div class="dynamic-section" id="sec-isian">
                    <label class="form-label-custom"><span class="required-dot"></span> Kunci Jawaban Isian Singkat</label>
                    <div class="pilihan-hint"><i class="fa-solid fa-circle-info"></i> Masukkan kunci jawaban singkat. Jika ada variasi ejaan kunci, pisahkan dengan titik koma <code>;</code> (contoh: <code>Soekarno; Sukarno; Ir. Soekarno</code>).</div>
                    <input type="text" name="kunci_isian" class="form-control-modern" placeholder="Tulis kunci jawaban..." value="{{ old('kunci_isian') }}">
                </div>

                {{-- 6. ESSAY --}}
                <div class="dynamic-section" id="sec-essay">
                    <div class="alert alert-info border-0 rounded-3 mb-0">
                        <i class="fa-solid fa-circle-info me-1"></i> Soal Uraian / Essay tidak membutuhkan opsi jawaban. Jawaban siswa akan dikoreksi manual oleh Guru Mapel pada modul Koreksi Nilai.
                    </div>
                </div>

            </div>

            <div class="form-footer">
                <a href="{{ route('dashboard-guru.bank-soal.soal.index', $bank_soal->id) }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-check"></i> Simpan Soal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const MAX_OPSI_PG = 8;
    const MAX_OPSI_BS = 10;
    const MAX_OPSI_MATCHING = 10;

    function toggleJenisSoal() {

        const jenis = document.getElementById('jenisSoal').value;

        document.querySelectorAll('.dynamic-section').forEach(section => {

            const aktif = section.id === 'sec-' + jenis;

            section.style.display = aktif ? 'block' : 'none';

            section.querySelectorAll('input, textarea, select').forEach(el => {

                el.disabled = !aktif;

            });

        });

    }

    /* --- 1. DYNAMIC PG --- */
    function renumberPg() {
        const rows = document.querySelectorAll('.pg-row');
        rows.forEach((row, idx) => {
            row.querySelector('input[type="radio"]').value = idx;
            row.querySelector('.pg-kode').textContent = String.fromCharCode(65 + idx);
            row.querySelector('input[type="text"]').placeholder = `Teks opsi ${String.fromCharCode(65 + idx)}...`;
        });
        const removeBtns = document.querySelectorAll('#pgContainer .btn-remove-opsi');
        removeBtns.forEach(btn => btn.disabled = (rows.length <= 2));
        document.getElementById('btnAddPg').style.display = (rows.length >= MAX_OPSI_PG) ? 'none' : 'inline-flex';
    }

    function addPgRow() {
        const rows = document.querySelectorAll('.pg-row');
        if (rows.length >= MAX_OPSI_PG) return;
        const clone = rows[0].cloneNode(true);
        clone.querySelector('input[type="text"]').value = '';
        clone.querySelector('input[type="radio"]').checked = false;
        document.getElementById('pgContainer').appendChild(clone);
        renumberPg();
    }

    function removePgRow(btn) {
        const rows = document.querySelectorAll('.pg-row');
        if (rows.length <= 2) return;
        btn.closest('.pg-row').remove();
        renumberPg();
    }

    /* --- 2. DYNAMIC PG KOMPLEKS --- */
    function renumberPgk() {
        const rows = document.querySelectorAll('.pgk-row');
        rows.forEach((row, idx) => {
            row.querySelector('.pgk-checkbox').value = idx;
            row.querySelector('.pgk-kode').textContent = String.fromCharCode(65 + idx);
            row.querySelector('input[type="text"]').placeholder = `Teks opsi ${String.fromCharCode(65 + idx)}...`;
        });
        const removeBtns = document.querySelectorAll('#pgkContainer .btn-remove-opsi');
        removeBtns.forEach(btn => btn.disabled = (rows.length <= 2));
        document.getElementById('btnAddPgk').style.display = (rows.length >= MAX_OPSI_PG) ? 'none' : 'inline-flex';
    }

    function addPgkRow() {
        const rows = document.querySelectorAll('.pgk-row');
        if (rows.length >= MAX_OPSI_PG) return;
        const clone = rows[0].cloneNode(true);
        clone.querySelector('input[type="text"]').value = '';
        clone.querySelector('.pgk-checkbox').checked = false;
        document.getElementById('pgkContainer').appendChild(clone);
        renumberPgk();
    }

    function removePgkRow(btn) {
        const rows = document.querySelectorAll('.pgk-row');
        if (rows.length <= 2) return;
        btn.closest('.pgk-row').remove();
        renumberPgk();
    }

    /* --- 3. DYNAMIC BENAR / SALAH --- */
    function renumberBs() {
        const rows = document.querySelectorAll('.bs-row');
        rows.forEach((row, idx) => {
            row.querySelector('.bs-kode').textContent = idx + 1;
            row.querySelector('input[type="text"]').placeholder = `Pernyataan ${idx + 1}...`;
        });
        const removeBtns = document.querySelectorAll('#bsContainer .btn-remove-opsi');
        removeBtns.forEach(btn => btn.disabled = (rows.length <= 1));
        document.getElementById('btnAddBs').style.display = (rows.length >= MAX_OPSI_BS) ? 'none' : 'inline-flex';
    }

    function addBsRow() {
        const rows = document.querySelectorAll('.bs-row');
        if (rows.length >= MAX_OPSI_BS) return;
        const clone = rows[0].cloneNode(true);
        clone.querySelector('input[type="text"]').value = '';
        clone.querySelector('select').value = 'benar';
        document.getElementById('bsContainer').appendChild(clone);
        renumberBs();
    }

    function removeBsRow(btn) {
        const rows = document.querySelectorAll('.bs-row');
        if (rows.length <= 1) return;
        btn.closest('.bs-row').remove();
        renumberBs();
    }

    /* --- 4. DYNAMIC MENCOCOKKAN --- */
    function renumberMatching() {
        const rows = document.querySelectorAll('.matching-row');
        rows.forEach((row, idx) => {
            row.querySelector('.matching-kode').textContent = idx + 1;
            const inputs = row.querySelectorAll('input[type="text"]');
            inputs[0].placeholder = `Item Kiri ${idx + 1}...`;
            inputs[1].placeholder = `Pasangan Kanan ${idx + 1}...`;
        });
        const removeBtns = document.querySelectorAll('#matchingContainer .btn-remove-opsi');
        removeBtns.forEach(btn => btn.disabled = (rows.length <= 1));
        document.getElementById('btnAddMatching').style.display = (rows.length >= MAX_OPSI_MATCHING) ? 'none' : 'inline-flex';
    }

    function addMatchingRow() {
        const rows = document.querySelectorAll('.matching-row');
        if (rows.length >= MAX_OPSI_MATCHING) return;
        const clone = rows[0].cloneNode(true);
        const inputs = clone.querySelectorAll('input[type="text"]');
        inputs[0].value = '';
        inputs[1].value = '';
        document.getElementById('matchingContainer').appendChild(clone);
        renumberMatching();
    }

    function removeMatchingRow(btn) {
        const rows = document.querySelectorAll('.matching-row');
        if (rows.length <= 1) return;
        btn.closest('.matching-row').remove();
        renumberMatching();
    }

    document.getElementById('jenisSoal').addEventListener('change', toggleJenisSoal);
    document.addEventListener('DOMContentLoaded', function() {
        toggleJenisSoal();
        renumberPg();
        renumberPgk();
        renumberBs();
        renumberMatching();
    });

    // Image Upload Script
    const gambarInput = document.getElementById('gambarInput');
    const uploadBox = document.getElementById('uploadBox');
    const uploadContent = document.getElementById('uploadContent');
    const previewWrapper = document.getElementById('previewWrapper');
    const imagePreview = document.getElementById('imagePreview');

    if (gambarInput) {
        gambarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    uploadContent.style.display = 'none';
                    previewWrapper.style.display = 'block';
                    uploadBox.classList.add('has-file');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    function removeImage(event) {
        event.stopPropagation();
        gambarInput.value = '';
        imagePreview.src = '';
        uploadContent.style.display = 'block';
        previewWrapper.style.display = 'none';
        uploadBox.classList.remove('has-file');
    }
</script>

@endsection