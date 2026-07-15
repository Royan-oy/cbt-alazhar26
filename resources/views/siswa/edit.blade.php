@extends('layouts.app')

@section('title', 'Edit Siswa')

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

    .foto-preview-box {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px dashed var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        flex-shrink: 0;
    }

    .foto-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<div class="container-fluid py-2">

    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    PENGGUNA
                </span>
                <h3 class="fw-bold mb-1">Edit Siswa</h3>
                <p class="text-light opacity-75 mb-0 small">Perbarui data siswa. Kosongkan password jika tidak ingin mengubahnya.</p>
            </div>

            <a href="{{ route('siswa.index') }}" class="btn-back d-inline-flex align-items-center">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="card content-card">
        <div class="card-body p-4">

            <form method="POST" action="{{ route('siswa.update', $siswa->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Foto --}}
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="foto-preview-box" id="fotoPreviewBox">
                        @if($siswa->foto)
                            <img src="{{ asset('storage/' . $siswa->foto) }}" alt="{{ $siswa->nama }}">
                        @else
                            <i class="fa-solid fa-user text-muted fs-3"></i>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <label class="form-label fw-semibold">Foto (opsional)</label>
                        <input
                            type="file"
                            name="foto"
                            id="fotoInput"
                            accept="image/*"
                            class="form-control form-control-custom @error('foto') is-invalid @enderror">
                        @error('foto')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
                    </div>
                </div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama</label>
                        <input
                            type="text"
                            name="nama"
                            class="form-control form-control-custom @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $siswa->nama) }}"
                            autofocus>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">NIS</label>
                        <input
                            type="text"
                            name="nis"
                            class="form-control form-control-custom @error('nis') is-invalid @enderror"
                            value="{{ old('nis', $siswa->nis) }}">
                        @error('nis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">NISN</label>
                        <input
                            type="text"
                            name="nisn"
                            class="form-control form-control-custom @error('nisn') is-invalid @enderror"
                            value="{{ old('nisn', $siswa->nisn) }}">
                        @error('nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(Auth::user()->role != 'admin_jenjang')
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Jenjang</label>
                        <select id="jenjang" class="form-select form-control-custom">
                            <option value="">-- Pilih Jenjang --</option>
                            @foreach($jenjangs as $jenjang)
                                <option value="{{ $jenjang->id }}">{{ $jenjang->nama_jenjang }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kelas</label>
                        <select
                            name="kelas_id"
                            id="kelas"
                            class="form-select form-control-custom @error('kelas_id') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option
                                    value="{{ $kelas->id }}"
                                    data-jenjang="{{ optional($kelas->tingkat)->jenjang_id }}"
                                    {{ old('kelas_id', optional($siswaKelasAktif)->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                    {{ optional($kelas->tingkat)->nama_tingkat }} - {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Ini adalah kelas siswa untuk tahun ajaran yang dipilih di bawah.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tahun Ajaran</label>
                        <select
                            name="tahun_ajaran_id"
                            class="form-select form-control-custom @error('tahun_ajaran_id') is-invalid @enderror">
                            @foreach($tahunAjarans as $tahun)
                                <option
                                    value="{{ $tahun->id }}"
                                    {{ old('tahun_ajaran_id', optional($siswaKelasAktif)->tahun_ajaran_id ?? ($tahun->is_aktif ? $tahun->id : '')) == $tahun->id ? 'selected' : '' }}>
                                    {{ $tahun->nama_tahun }} - Semester {{ ucfirst($tahun->semester) }}
                                    @if($tahun->is_aktif) ⭐ Aktif @endif
                                </option>
                            @endforeach
                        </select>
                        @error('tahun_ajaran_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <div class="input-group">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control form-control-custom @error('password') is-invalid @enderror"
                                placeholder="Kosongkan jika tidak diubah"
                                style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                            <button
                                type="button"
                                class="btn btn-light border toggle-password"
                                data-target="password"
                                style="border-top-left-radius: 0; border-bottom-left-radius: 0; border-top-right-radius: 14px; border-bottom-right-radius: 14px;">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control form-control-custom"
                                placeholder="Ulangi password baru"
                                style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                            <button
                                type="button"
                                class="btn btn-light border toggle-password"
                                data-target="password_confirmation"
                                style="border-top-left-radius: 0; border-bottom-left-radius: 0; border-top-right-radius: 14px; border-bottom-right-radius: 14px;">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-info text-white btn-submit">
                        <i class="fa-solid fa-floppy-disk me-2"></i>
                        Perbarui
                    </button>

                    <a href="{{ route('siswa.index') }}" class="btn btn-light border btn-cancel">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
document.getElementById('fotoInput').addEventListener('change', function (e) {
    const file = e.target.files[0];
    const box = document.getElementById('fotoPreviewBox');

    if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
            box.innerHTML = '<img src="' + event.target.result + '" alt="Preview">';
        };
        reader.readAsDataURL(file);
    }
});

document.querySelectorAll('.toggle-password').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const targetId = btn.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = btn.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});
</script>

@endsection