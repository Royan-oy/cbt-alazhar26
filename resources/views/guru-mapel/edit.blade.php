@extends('layouts.app')

@section('title', 'Edit Guru Mapel')

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
</style>

<div class="container-fluid py-2">

    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    PENGGUNA
                </span>
                <h3 class="fw-bold mb-1">Edit Penugasan Guru Mapel</h3>
                <p class="text-light opacity-75 mb-0 small">Perbarui data penugasan guru mengajar.</p>
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

            @php
                $jenjangAktif = optional($guruMapel->guru)->jenjang_id;
                $kelasTerpilih = $guruMapel->kelas->pluck('id')->toArray();
            @endphp

            <form action="{{ route('guru-mapel.update', $guruMapel->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Jenjang (Super Admin saja) --}}
                    @if(Auth::user()->role == 'super_admin')
                    <div class="col-md-6">
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
                                    {{ old('guru_id', $guruMapel->guru_id) == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('guru_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mata Pelajaran --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mata Pelajaran</label>
                        <select
                            name="mata_pelajaran_id"
                            id="mapel"
                            class="form-select form-control-custom @error('mata_pelajaran_id') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($mataPelajarans as $mapel)
                                <option
                                    value="{{ $mapel->id }}"
                                    data-jenjang="{{ $mapel->jenjang_id }}"
                                    {{ old('mata_pelajaran_id', $guruMapel->mata_pelajaran_id) == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                        @error('mata_pelajaran_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kelas --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Kelas yang Diajar</label>

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
                                                name="kelas_id[]"
                                                value="{{ $kelas->id }}"
                                                id="kelas{{ $kelas->id }}"
                                                {{ in_array($kelas->id, old('kelas_id', $kelasTerpilih)) ? 'checked' : '' }}>

                                            <label class="form-check-label fw-semibold" for="kelas{{ $kelas->id }}">
                                                {{ $kelas->tingkat->nama_tingkat }} - {{ $kelas->nama_kelas }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @error('kelas_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <small class="text-muted d-block mt-2">Centang kelas yang diampu guru.</small>
                    </div>

                    {{-- Tahun Ajaran --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tahun Ajaran</label>
                        <select
                            name="tahun_ajaran_id"
                            class="form-select form-control-custom @error('tahun_ajaran_id') is-invalid @enderror">
                            @foreach($tahunAjarans as $tahun)
                                <option
                                    value="{{ $tahun->id }}"
                                    {{ old('tahun_ajaran_id', $guruMapel->tahun_ajaran_id) == $tahun->id ? 'selected' : '' }}>
                                    {{ $tahun->nama_tahun }} - Semester {{ ucfirst($tahun->semester) }}
                                    @if($tahun->is_aktif) ⭐ Aktif @endif
                                </option>
                            @endforeach
                        </select>
                        @error('tahun_ajaran_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-info text-white btn-submit">
                        <i class="fa-solid fa-floppy-disk me-2"></i>
                        Perbarui Penugasan
                    </button>

                    <a href="{{ route('guru-mapel.index') }}" class="btn btn-light border btn-cancel">
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

        filterSelect('guru', id);
        filterSelect('mapel', id);

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