@extends('layouts.app')

@section('title', 'Edit Jadwal Ujian')

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
        background-color: #f8fafc;
    }

    .kelas-chip:has(input:checked) {
        border-color: var(--accent-blue);
        background-color: rgba(14, 165, 233, 0.06);
    }

    .toggle-option {
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 14px 16px;
        background-color: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .section-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 12px;
        margin-top: 8px;
    }

    .form-check-input:checked {
        background-color: var(--accent-blue);
        border-color: var(--accent-blue);
    }
</style>

<div class="container-fluid py-2">

    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    UJIAN
                </span>
                <h3 class="fw-bold mb-1">Edit Jadwal Ujian</h3>
                <p class="text-light opacity-75 mb-0 small">Token tidak berubah saat jadwal diedit.</p>
            </div>

            <a href="{{ route('ujian.index') }}" class="btn-back d-inline-flex align-items-center">
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

            @if($ujian->token_aktif)
            <div class="alert alert-warning rounded-4 border-0 mb-4">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                Token ujian ini sedang <strong>aktif</strong> (kemungkinan sedang berjalan). Nonaktifkan token dulu dari halaman detail sebelum mengubah jadwal ini.
            </div>
            @endif

            @php
                $jenjangAktif = optional($ujian->bankSoal)->jenjang_id;
                $kelasTerpilih = $ujian->kelas->pluck('id')->toArray();
            @endphp

            <form action="{{ route('ujian.update', $ujian->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Ujian</label>
                        <input
                            type="text"
                            name="nama_ujian"
                            class="form-control form-control-custom @error('nama_ujian') is-invalid @enderror"
                            value="{{ old('nama_ujian', $ujian->nama_ujian) }}">
                        @error('nama_ujian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(Auth::user()->role == 'super_admin')
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jenjang</label>
                        <select id="jenjang" class="form-select form-control-custom">
                            <option value="">-- Pilih Jenjang --</option>
                            @foreach($jenjangs as $jenjang)
                                <option value="{{ $jenjang->id }}" {{ $jenjangAktif == $jenjang->id ? 'selected' : '' }}>
                                    {{ $jenjang->nama_jenjang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-{{ Auth::user()->role == 'super_admin' ? '4' : '6' }}">
                        <label class="form-label fw-semibold">Bank Soal</label>
                        <select
                            name="bank_soal_id"
                            id="bankSoal"
                            class="form-select form-control-custom @error('bank_soal_id') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Bank Soal (Publish) --</option>
                            @foreach($bankSoals as $bs)
                                <option
                                    value="{{ $bs->id }}"
                                    data-jenjang="{{ $bs->jenjang_id }}"
                                    {{ old('bank_soal_id', $ujian->bank_soal_id) == $bs->id ? 'selected' : '' }}>
                                    {{ $bs->nama_bank_soal }} ({{ optional($bs->mataPelajaran)->nama_mapel }})
                                </option>
                            @endforeach
                        </select>
                        @error('bank_soal_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-{{ Auth::user()->role == 'super_admin' ? '4' : '6' }}">
                        <label class="form-label fw-semibold">Jenis Ujian</label>
                        <select
                            name="jenis_ujian_id"
                            class="form-select form-control-custom @error('jenis_ujian_id') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Jenis Ujian --</option>
                            @foreach($jenisUjians as $jenis)
                                <option value="{{ $jenis->id }}" {{ old('jenis_ujian_id', $ujian->jenis_ujian_id) == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_ujian_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tahun Ajaran</label>
                        <select
                            name="tahun_ajaran_id"
                            class="form-select form-control-custom @error('tahun_ajaran_id') is-invalid @enderror"
                            required>
                            @foreach($tahunAjarans as $tahun)
                                <option
                                    value="{{ $tahun->id }}"
                                    {{ old('tahun_ajaran_id', $ujian->tahun_ajaran_id) == $tahun->id ? 'selected' : '' }}>
                                    {{ $tahun->nama_tahun }} - {{ ucfirst($tahun->semester) }}
                                    @if($tahun->is_aktif) ⭐ @endif
                                </option>
                            @endforeach
                        </select>
                        @error('tahun_ajaran_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Waktu Mulai</label>
                        <input
                            type="datetime-local"
                            name="waktu_mulai"
                            class="form-control form-control-custom @error('waktu_mulai') is-invalid @enderror"
                            value="{{ old('waktu_mulai', $ujian->waktu_mulai ? $ujian->waktu_mulai->format('Y-m-d\TH:i') : '') }}">
                        @error('waktu_mulai')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Waktu Selesai</label>
                        <input
                            type="datetime-local"
                            name="waktu_selesai"
                            class="form-control form-control-custom @error('waktu_selesai') is-invalid @enderror"
                            value="{{ old('waktu_selesai', $ujian->waktu_selesai ? $ujian->waktu_selesai->format('Y-m-d\TH:i') : '') }}">
                        @error('waktu_selesai')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Durasi Minimal Pengerjaan (menit)</label>
                        <input
                            type="number"
                            name="durasi_minimal"
                            min="1"
                            class="form-control form-control-custom @error('durasi_minimal') is-invalid @enderror"
                            value="{{ old('durasi_minimal', $ujian->durasi_minimal) }}">
                        @error('durasi_minimal')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="section-label">Kelas Peserta</div>

                        <div class="row g-2">
                            @foreach($kelasList as $kelas)
                                <div
                                    class="col-md-3 kelas-item"
                                    data-jenjang="{{ optional($kelas->tingkat)->jenjang_id }}">

                                    <div class="kelas-chip">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="kelas_id[]"
                                                value="{{ $kelas->id }}"
                                                id="kelas{{ $kelas->id }}"
                                                {{ in_array($kelas->id, old('kelas_id', $kelasTerpilih)) ? 'checked' : '' }}>

                                            <label class="form-check-label fw-semibold" for="kelas{{ $kelas->id }}">
                                                {{ optional($kelas->tingkat)->nama_tingkat }} - {{ $kelas->nama_kelas }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @error('kelas_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="section-label">Pengaturan Ujian</div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="toggle-option">
                                    <div>
                                        <div class="fw-semibold">Acak Urutan Soal</div>
                                        <small class="text-muted">Tiap siswa dapat urutan soal berbeda</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="acak_soal" value="1" {{ old('acak_soal', $ujian->acak_soal) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="toggle-option">
                                    <div>
                                        <div class="fw-semibold">Acak Urutan Jawaban</div>
                                        <small class="text-muted">Posisi pilihan A/B/C/D diacak</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="acak_jawaban" value="1" {{ old('acak_jawaban', $ujian->acak_jawaban) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="toggle-option">
                                    <div>
                                        <div class="fw-semibold">Tampilkan Nilai Langsung</div>
                                        <small class="text-muted">Siswa lihat skor setelah submit</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="tampilkan_nilai" value="1" {{ old('tampilkan_nilai', $ujian->tampilkan_nilai) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="toggle-option">
                                    <div>
                                        <div class="fw-semibold">Tampilkan Pembahasan</div>
                                        <small class="text-muted">Siswa lihat kunci & pembahasan</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="tampilkan_pembahasan" value="1" {{ old('tampilkan_pembahasan', $ujian->tampilkan_pembahasan) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-info text-white btn-submit" {{ $ujian->token_aktif ? 'disabled' : '' }}>
                        <i class="fa-solid fa-floppy-disk me-2"></i>
                        Perbarui
                    </button>

                    <a href="{{ route('ujian.index') }}" class="btn btn-light border btn-cancel">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

@if(Auth::user()->role == 'super_admin')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const jenjang = document.getElementById('jenjang');

    filter(jenjang.value);

    jenjang.addEventListener('change', function () {
        filter(this.value);
    });

    function filter(id) {

        filterSelect('bankSoal', id);

        document.querySelectorAll('.kelas-item').forEach(function (item) {

            const checkbox = item.querySelector('input');

            if (item.dataset.jenjang === id) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
                checkbox.checked = false;
            }
        });
    }

    function filterSelect(id, jenjang) {

        const select = document.getElementById(id);

        [...select.options].forEach(function (option) {

            if (option.value === '') return;

            option.hidden = option.dataset.jenjang !== jenjang;
        });
    }

});
</script>
@endif

@endsection