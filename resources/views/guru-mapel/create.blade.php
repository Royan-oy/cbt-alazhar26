@extends('layouts.app')

@section('title', 'Tambah Guru Mapel')

@section('content')

<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --border-color: #e2e8f0;
        --text-muted: #64748b;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        border-radius: 24px;
        padding: 32px;
        color: white;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
    }

    .content-card {
        background: #fff;
        border-radius: 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.03);
        padding: 8px;
    }

    .form-control-custom {
        border-radius: 14px;
        height: 46px;
        border: 1px solid var(--border-color);
        padding-left: 16px;
        font-size: 14px;
        background-color: #f8fafc;
    }

    .form-control-custom:focus {
        background-color: #fff;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
    }

    .btn-submit, .btn-cancel {
        border-radius: 14px;
        height: 46px;
        padding: 0 24px;
        font-weight: 600;
    }

    .btn-back {
        background: rgba(255,255,255,0.1);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 14px;
        padding: 10px 18px;
        font-weight: 600;
    }

    .btn-back:hover { background: rgba(255,255,255,0.2); color: #fff; }

    .kelas-chip {
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 14px 16px;
        height: 100%;
        transition: all 0.2s;
        background-color: #f8fafc;
    }

    .kelas-chip:has(input:checked) {
        border-color: var(--accent-blue);
        background-color: rgba(14, 165, 233, 0.06);
    }

    .form-check-input:checked {
        background-color: var(--accent-blue);
        border-color: var(--accent-blue);
    }

    .section-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 12px;
    }

    /* ===============================
    Penugasan Repeater
    ==================================*/

    .penugasan-block {
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 18px;
        background: #f8fafc;
        position: relative;
    }

    .penugasan-block .penugasan-title {
        font-weight: 700;
        font-size: 15px;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .penugasan-block .penugasan-title .badge-num {
        background: var(--accent-blue);
        color: #fff;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .btn-remove-penugasan {
        border: none;
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
        font-weight: 600;
        font-size: 13px;
        border-radius: 10px;
        padding: 6px 12px;
    }

    .btn-remove-penugasan:hover {
        background: rgba(239, 68, 68, 0.15);
    }

    .btn-tambah-penugasan {
        border: 2px dashed var(--accent-blue);
        background: rgba(14, 165, 233, 0.05);
        color: var(--accent-blue);
        font-weight: 700;
        border-radius: 16px;
        padding: 14px;
        width: 100%;
    }

    .btn-tambah-penugasan:hover {
        background: rgba(14, 165, 233, 0.1);
    }

    .kelas-item.d-none-jenjang {
        display: none !important;
    }
</style>

<div class="container-fluid py-2">

    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    PENGGUNA
                </span>
                <h3 class="fw-bold mb-1">Tambah Penugasan Guru Mapel</h3>
                <p class="text-light opacity-75 mb-0 small">Tetapkan guru untuk mengajar satu atau lebih mata pelajaran di kelas tertentu.</p>
            </div>

            <a href="{{ route('guru-mapel.index') }}" class="btn-back d-inline-flex align-items-center">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="card content-card">
        <div class="card-body p-4">

            @if($errors->any())
            <div class="alert alert-danger rounded-4 border-0 mb-4">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('guru-mapel.store') }}" method="POST" id="formGuruMapel">
                @csrf

                <div class="row g-3 mb-2">

                    {{-- Jenjang (Super Admin saja, sebagai filter bantuan) --}}
                    @if(Auth::user()->role == 'super_admin')
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jenjang</label>
                        <select id="jenjang" class="form-select form-control-custom">
                            <option value="">-- Pilih Jenjang --</option>
                            @foreach($jenjangs as $jenjang)
                                <option value="{{ $jenjang->id }}">
                                    {{ $jenjang->nama_jenjang }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih jenjang dulu untuk menyaring pilihan guru, mapel, dan kelas.</small>
                    </div>
                    <div class="col-md-6"></div>
                    @endif

                    {{-- Guru --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Guru</label>
                        <select
                            name="guru_id"
                            id="guru"
                            class="form-select form-control-custom @error('guru_id') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $guru)
                                <option
                                    value="{{ $guru->id }}"
                                    data-jenjang="{{ $guru->jenjang_id }}"
                                    {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('guru_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tahun Ajaran --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tahun Ajaran</label>
                        <select
                            name="tahun_ajaran_id"
                            class="form-select form-control-custom @error('tahun_ajaran_id') is-invalid @enderror"
                            required>
                            @foreach($tahunAjarans as $tahun)
                                <option
                                    value="{{ $tahun->id }}"
                                    {{ old('tahun_ajaran_id', $tahun->is_aktif ? $tahun->id : '') == $tahun->id ? 'selected' : '' }}>
                                    {{ $tahun->nama_tahun }} - Semester {{ ucfirst($tahun->semester) }}
                                    @if($tahun->is_aktif) ⭐ (Aktif) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('tahun_ajaran_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="form-label fw-semibold mb-0">Daftar Penugasan Mata Pelajaran &amp; Kelas</label>
                </div>

                @error('penugasan')
                    <div class="alert alert-danger rounded-4 border-0 mb-3 py-2">{{ $message }}</div>
                @enderror

                {{-- Container tempat blok penugasan dirender --}}
                <div id="penugasanContainer">
                    @php
                        $oldPenugasan = old('penugasan');
                        if (!$oldPenugasan || !is_array($oldPenugasan) || count($oldPenugasan) === 0) {
                            $oldPenugasan = [
                                ['mata_pelajaran_id' => '', 'kelas_id' => []],
                            ];
                        }
                    @endphp

                    @foreach($oldPenugasan as $i => $item)
                        <div class="penugasan-block" data-index="{{ $i }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="penugasan-title">
                                    <span class="badge-num penugasan-number">{{ $i + 1 }}</span>
                                    Penugasan
                                </div>
                                <button type="button" class="btn-remove-penugasan">
                                    <i class="fa-solid fa-trash-can me-1"></i> Hapus Penugasan
                                </button>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Mata Pelajaran</label>
                                <select
                                    name="penugasan[{{ $i }}][mata_pelajaran_id]"
                                    class="form-select form-control-custom mapel-select"
                                    required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach($mataPelajarans as $mapel)
                                        <option
                                            value="{{ $mapel->id }}"
                                            data-jenjang="{{ $mapel->jenjang_id }}"
                                            {{ (string) ($item['mata_pelajaran_id'] ?? '') === (string) $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label fw-semibold small">Kelas yang Diajar</label>
                                <div class="row g-2">
                                    @foreach($kelasList as $kelas)
                                        <div
                                            class="col-md-4 kelas-item"
                                            data-jenjang="{{ $kelas->tingkat->jenjang_id }}">
                                            <div class="kelas-chip">
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        name="penugasan[{{ $i }}][kelas_id][]"
                                                        value="{{ $kelas->id }}"
                                                        id="kelas_{{ $i }}_{{ $kelas->id }}"
                                                        {{ in_array($kelas->id, $item['kelas_id'] ?? []) ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold" for="kelas_{{ $i }}_{{ $kelas->id }}">
                                                        {{ $kelas->tingkat->nama_tingkat }} - {{ $kelas->nama_kelas }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="btnTambah" class="btn-tambah-penugasan mb-4">
                    <i class="fa-solid fa-plus me-2"></i> Tambah Mata Pelajaran
                </button>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-info text-white btn-submit">
                        <i class="fa-solid fa-floppy-disk me-2"></i>
                        Simpan Penugasan
                    </button>

                    <a href="{{ route('guru-mapel.index') }}" class="btn btn-light border btn-cancel">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- Template kosong untuk blok penugasan baru (dipakai oleh JS, tidak dirender browser) --}}
<template id="penugasanTemplate">
    <div class="penugasan-block" data-index="__INDEX__">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="penugasan-title">
                <span class="badge-num penugasan-number">__INDEX__</span>
                Penugasan
            </div>
            <button type="button" class="btn-remove-penugasan">
                <i class="fa-solid fa-trash-can me-1"></i> Hapus Penugasan
            </button>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small">Mata Pelajaran</label>
            <select
                name="penugasan[__INDEX__][mata_pelajaran_id]"
                class="form-select form-control-custom mapel-select"
                required>
                <option value="">-- Pilih Mata Pelajaran --</option>
                @foreach($mataPelajarans as $mapel)
                    <option value="{{ $mapel->id }}" data-jenjang="{{ $mapel->jenjang_id }}">
                        {{ $mapel->nama_mapel }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label fw-semibold small">Kelas yang Diajar</label>
            <div class="row g-2">
                @foreach($kelasList as $kelas)
                    <div class="col-md-4 kelas-item" data-jenjang="{{ $kelas->tingkat->jenjang_id }}">
                        <div class="kelas-chip">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="penugasan[__INDEX__][kelas_id][]"
                                    value="{{ $kelas->id }}"
                                    id="kelas___INDEX___{{ $kelas->id }}">
                                <label class="form-check-label fw-semibold" for="kelas___INDEX___{{ $kelas->id }}">
                                    {{ $kelas->tingkat->nama_tingkat }} - {{ $kelas->nama_kelas }}
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const container    = document.getElementById('penugasanContainer');
    const templateHtml  = document.getElementById('penugasanTemplate').innerHTML;
    const btnTambah      = document.getElementById('btnTambah');
    const jenjangSelect  = document.getElementById('jenjang'); // null jika bukan super_admin
    const guruSelect     = document.getElementById('guru');

    let index = container.querySelectorAll('.penugasan-block').length;

    /* =========================================================
       Renumber label "Penugasan #x"
    ========================================================= */
    function renumber() {
        container.querySelectorAll('.penugasan-block').forEach(function (block, i) {
            block.querySelector('.penugasan-number').textContent = i + 1;
        });
    }

    /* =========================================================
       Filter select Guru berdasarkan jenjang terpilih
    ========================================================= */
    function filterGuru(jenjangId) {
        if (!guruSelect) return;
        guruSelect.selectedIndex = 0;
        [...guruSelect.options].forEach(function (option) {
            if (option.value === '') { option.hidden = false; return; }
            option.hidden = jenjangId === '' ? true : option.dataset.jenjang !== jenjangId;
        });
    }

    /* =========================================================
       Filter select Mapel + checkbox Kelas dalam 1 blok penugasan
    ========================================================= */
    function applyJenjangFilter(block, jenjangId) {

        const mapelSelect = block.querySelector('.mapel-select');
        if (mapelSelect) {
            [...mapelSelect.options].forEach(function (option) {
                if (option.value === '') { option.hidden = false; return; }
                option.hidden = jenjangId === '' ? true : option.dataset.jenjang !== jenjangId;
            });
            if (mapelSelect.selectedOptions[0] && mapelSelect.selectedOptions[0].hidden) {
                mapelSelect.value = '';
            }
        }

        block.querySelectorAll('.kelas-item').forEach(function (item) {
            const checkbox = item.querySelector('input');
            if (jenjangId === '') {
                item.style.display = 'none';
                checkbox.checked = false;
            } else if (item.dataset.jenjang === jenjangId) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
                checkbox.checked = false;
            }
        });
    }

    function applyJenjangFilterToAllBlocks(jenjangId) {
        container.querySelectorAll('.penugasan-block').forEach(function (block) {
            applyJenjangFilter(block, jenjangId);
        });
    }

    /* =========================================================
       Tombol hapus penugasan
    ========================================================= */
    function bindRemove(block) {
        const btn = block.querySelector('.btn-remove-penugasan');
        btn.addEventListener('click', function () {
            if (container.querySelectorAll('.penugasan-block').length <= 1) {
                alert('Minimal harus ada 1 penugasan mata pelajaran.');
                return;
            }
            block.remove();
            renumber();
        });
    }

    /* =========================================================
       Tambah blok penugasan baru dari template
    ========================================================= */
    function addPenugasan() {
        const html = templateHtml.split('__INDEX__').join(index);
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html.trim();
        const block = wrapper.firstElementChild;

        container.appendChild(block);
        bindRemove(block);
        applyJenjangFilter(block, jenjangSelect ? jenjangSelect.value : '');
        renumber();
        index++;
    }

    /* =========================================================
       Inisialisasi
    ========================================================= */
    container.querySelectorAll('.penugasan-block').forEach(bindRemove);
    renumber();

    btnTambah.addEventListener('click', addPenugasan);

    if (jenjangSelect) {
        filterGuru('');
        applyJenjangFilterToAllBlocks('');

        jenjangSelect.addEventListener('change', function () {
            filterGuru(this.value);
            applyJenjangFilterToAllBlocks(this.value);
        });
    }

    /* =========================================================
       Validasi ringan sebelum submit: pastikan tiap blok
       punya mapel terpilih & minimal 1 kelas dicentang
    ========================================================= */
    document.getElementById('formGuruMapel').addEventListener('submit', function (e) {
        let valid = true;
        let firstInvalidBlock = null;

        container.querySelectorAll('.penugasan-block').forEach(function (block) {
            const mapelSelect = block.querySelector('.mapel-select');
            const anyKelasChecked = block.querySelectorAll('input[type="checkbox"]:checked').length > 0;

            if (!mapelSelect.value || !anyKelasChecked) {
                valid = false;
                if (!firstInvalidBlock) firstInvalidBlock = block;
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('Setiap penugasan wajib memilih Mata Pelajaran dan minimal 1 Kelas.');
            if (firstInvalidBlock) {
                firstInvalidBlock.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

});
</script>

@endsection