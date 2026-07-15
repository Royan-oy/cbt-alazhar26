@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

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

    .form-control-custom:disabled {
        background-color: #f1f5f9;
        color: var(--text-muted, #64748b);
        cursor: not-allowed;
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
</style>

<div class="container-fluid py-2">

    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    MASTER DATA
                </span>
                <h3 class="fw-bold mb-1">Tambah Mata Pelajaran</h3>
                <p class="text-light opacity-75 mb-0 small">Tambahkan mata pelajaran baru ke dalam sistem.</p>
            </div>

            <a href="{{ route('mata-pelajaran.index') }}" class="btn-back d-inline-flex align-items-center">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="card content-card">
        <div class="card-body p-4">

            <form method="POST" action="{{ route('mata-pelajaran.store') }}">
                @csrf

                {{-- Jenjang --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Jenjang</label>

                    @if(Auth::user()->role != 'admin_jenjang')
                        <select
                            name="jenjang_id"
                            class="form-select form-control-custom @error('jenjang_id') is-invalid @enderror">
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
                    @else
                        <input
                            type="text"
                            class="form-control form-control-custom"
                            value="{{ optional(optional(Auth::user()->admin)->jenjang)->nama_jenjang ?? '-' }}"
                            disabled>
                        <small class="text-muted">Mata pelajaran otomatis ditambahkan ke jenjang Anda.</small>
                    @endif
                </div>

                {{-- Nama Mapel --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Nama Mata Pelajaran</label>
                    <input
                        type="text"
                        name="nama_mapel"
                        class="form-control form-control-custom @error('nama_mapel') is-invalid @enderror"
                        placeholder="Contoh: Matematika"
                        value="{{ old('nama_mapel') }}"
                        autofocus>

                    @error('nama_mapel')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-info text-white btn-submit">
                        <i class="fa-solid fa-floppy-disk me-2"></i>
                        Simpan
                    </button>

                    <a href="{{ route('mata-pelajaran.index') }}" class="btn btn-light border btn-cancel">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection