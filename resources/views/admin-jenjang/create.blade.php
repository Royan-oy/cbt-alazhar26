@extends('layouts.app')

@section('title', 'Tambah Admin Jenjang')

@section('content')

<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --border-color: #e2e8f0;
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

    .toggle-password {
        cursor: pointer;
    }
</style>

<div class="container-fluid py-2">

    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    PENGGUNA
                </span>
                <h3 class="fw-bold mb-1">Tambah Admin Jenjang</h3>
                <p class="text-light opacity-75 mb-0 small">Buat akun admin baru untuk mengelola jenjang tertentu.</p>
            </div>

            <a href="{{ route('admin-jenjang.index') }}" class="btn-back d-inline-flex align-items-center">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="card content-card">
        <div class="card-body p-4">

            <form method="POST" action="{{ route('admin-jenjang.store') }}">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama</label>
                        <input
                            type="text"
                            name="nama"
                            class="form-control form-control-custom @error('nama') is-invalid @enderror"
                            placeholder="Contoh: Admin SMP"
                            value="{{ old('nama') }}"
                            autofocus>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jenjang</label>
                        <select name="jenjang_id" class="form-select form-control-custom @error('jenjang_id') is-invalid @enderror">
                            <option value="">-- Pilih Jenjang --</option>
                            @foreach($jenjangs as $jenjang)
                                <option value="{{ $jenjang->id }}" {{ old('jenjang_id') == $jenjang->id ? 'selected' : '' }}>
                                    {{ $jenjang->nama_jenjang }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenjang_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control form-control-custom @error('email') is-invalid @enderror"
                            placeholder="admin@sekolah.com"
                            value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6"></div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control form-control-custom @error('password') is-invalid @enderror"
                                placeholder="Minimal 6 karakter"
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
                        <label class="form-label fw-semibold">Konfirmasi Password</label>
                        <div class="input-group">
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control form-control-custom"
                                placeholder="Ulangi password"
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
                        Simpan
                    </button>

                    <a href="{{ route('admin-jenjang.index') }}" class="btn btn-light border btn-cancel">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
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