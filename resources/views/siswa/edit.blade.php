@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')

<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --accent-blue-dark: #0284c7;
        --accent-blue-soft: #f0f9ff;
        --accent-blue-border: #bae6fd;
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

    /* ===== Section layout ===== */
    .form-section {
        margin-bottom: 28px;
    }

    .form-section:last-of-type {
        margin-bottom: 0;
    }

    .section-eyebrow {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
    }

    .section-eyebrow .badge-step {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--primary-dark);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .section-eyebrow .section-title {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--primary-dark);
        letter-spacing: 0.2px;
    }

    .section-eyebrow::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border-color);
    }

    /* ===== Password box (redesigned) ===== */
    .password-box {
        background: var(--accent-blue-soft);
        border: 1px solid var(--accent-blue-border);
        border-radius: 18px;
        padding: 20px;
    }

    .password-box-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .password-box-header .password-title {
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 2px;
        display: block;
    }

    .password-box-header .password-subtitle {
        font-size: 12.5px;
        color: var(--text-muted);
    }

    .btn-generate {
        background: var(--accent-blue);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 9px 16px;
        font-size: 12.5px;
        font-weight: 700;
        white-space: nowrap;
        transition: background 0.15s ease;
    }

    .btn-generate:hover {
        background: var(--accent-blue-dark);
        color: #fff;
    }

    .btn-generate:active {
        transform: scale(0.98);
    }

    /* input-group without inline-style radius hacks */
    .input-group-custom .form-control-custom {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        background-color: #fff;
    }

    .input-group-custom .btn-icon {
        border: 1px solid var(--border-color);
        border-left: 0;
        border-radius: 0;
        background: #fff;
        color: var(--text-muted);
        width: 42px;
    }

    .input-group-custom .btn-icon:last-child {
        border-top-right-radius: 14px;
        border-bottom-right-radius: 14px;
    }

    .input-group-custom .btn-icon:hover {
        background: #f1f5f9;
        color: var(--primary-dark);
    }

    .password-hint {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    #copyNotification {
        font-size: 12px;
        color: #059669;
        font-weight: 600;
        display: none;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
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

                {{-- ===== 1. Identitas Siswa ===== --}}
                <div class="form-section">
                    <div class="section-eyebrow">
                        <span class="badge-step">1</span>
                        <span class="section-title">Identitas Siswa</span>
                    </div>

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
                    </div>
                </div>

                {{-- ===== 2. Penempatan Kelas ===== --}}
                <div class="form-section">
                    <div class="section-eyebrow">
                        <span class="badge-step">2</span>
                        <span class="section-title">Penempatan Kelas</span>
                    </div>

                    <div class="row g-3">
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
                    </div>
                </div>

                {{-- ===== 3. Keamanan Akun (Password) ===== --}}
                <div class="form-section">
                    <div class="section-eyebrow">
                        <span class="badge-step">3</span>
                        <span class="section-title">Keamanan Akun</span>
                    </div>

                    <div class="password-box">
                        <div class="password-box-header">
                            <div>
                                <span class="password-title">Password Baru</span>
                                <span class="password-subtitle">Kosongkan jika tidak ingin mengubah password siswa.</span>
                            </div>
                            <button type="button" class="btn-generate" id="btnGeneratePassword">
                                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Generate Kode 6 Digit
                            </button>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Password Baru</label>
                                <div class="input-group input-group-custom">
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control form-control-custom @error('password') is-invalid @enderror"
                                        placeholder="Kosongkan jika tidak diubah">
                                    <button
                                        type="button"
                                        class="btn btn-icon"
                                        id="btnCopyPassword"
                                        title="Salin Password"
                                        aria-label="Salin password">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-icon toggle-password"
                                        data-target="password"
                                        title="Tampilkan / Sembunyikan Password"
                                        aria-label="Tampilkan atau sembunyikan password">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div id="copyNotification">
                                    <i class="fa-solid fa-circle-check"></i> Password tersalin ke clipboard!
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                <div class="input-group input-group-custom">
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        class="form-control form-control-custom"
                                        placeholder="Ulangi password baru">
                                    <button
                                        type="button"
                                        class="btn btn-icon toggle-password"
                                        data-target="password_confirmation"
                                        title="Tampilkan / Sembunyikan Password"
                                        aria-label="Tampilkan atau sembunyikan konfirmasi password">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                                <p class="password-hint mb-0">
                                    <i class="fa-solid fa-circle-info"></i> Kosongkan tombol generate jika ingin membuat password manual.
                                </p>
                            </div>
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

// Fitur Auto Generate Password 6 Digit Angka
const btnGenerate = document.getElementById('btnGeneratePassword');
if (btnGenerate) {
    btnGenerate.addEventListener('click', function () {
        const randomCode = Math.floor(100000 + Math.random() * 900000).toString();
        const pwdInput = document.getElementById('password');
        const pwdConfirmInput = document.getElementById('password_confirmation');

        if (pwdInput && pwdConfirmInput) {
            pwdInput.value = randomCode;
            pwdConfirmInput.value = randomCode;

            // Buka tampilan password ke mode text
            pwdInput.type = 'text';
            pwdConfirmInput.type = 'text';

            document.querySelectorAll('.toggle-password').forEach(function (btn) {
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        }
    });
}

// Fitur Copy Password to Clipboard
const btnCopy = document.getElementById('btnCopyPassword');
if (btnCopy) {
    btnCopy.addEventListener('click', function () {
        const pwdInput = document.getElementById('password');
        if (pwdInput && pwdInput.value) {
            navigator.clipboard.writeText(pwdInput.value).then(function () {
                const notif = document.getElementById('copyNotification');
                if (notif) {
                    notif.style.display = 'flex';
                    setTimeout(function () {
                        notif.style.display = 'none';
                    }, 2500);
                }
            }).catch(function () {
                pwdInput.select();
                document.execCommand('copy');
            });
        }
    });
}

@if(Auth::user()->role != 'admin_jenjang')
document.addEventListener('DOMContentLoaded', function () {
    const jenjang = document.getElementById('jenjang');
    const kelas = document.getElementById('kelas');

    filter('');

    jenjang.addEventListener('change', function () {
        filter(this.value);
    });

    function filter(jenjangId) {
        kelas.selectedIndex = 0;

        [...kelas.options].forEach(function (option) {
            if (option.value === '') {
                option.hidden = false;
                return;
            }
            option.hidden = (jenjangId === '') ? true : option.dataset.jenjang !== jenjangId;
        });
    }
});
@endif
</script>

@endsection