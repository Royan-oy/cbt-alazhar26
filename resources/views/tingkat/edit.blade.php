@extends('layouts.app')

@section('title','Ubah Tingkat')

@section('content')

<style>

.page-header{
    background:linear-gradient(135deg,#0f172a,#1e293b);
    border-radius:22px;
    padding:32px;
    color:#fff;
    position:relative;
    overflow:hidden;
    margin-bottom:25px;
}

.page-header::after{
    content:'';
    position:absolute;
    width:280px;
    height:280px;
    border-radius:50%;
    background:rgba(56,189,248,.08);
    right:-70px;
    top:-80px;
    pointer-events:none;
}

.page-header>*{
    position:relative;
    z-index:2;
}

.content-card{
    border:none;
    border-radius:22px;
    box-shadow:0 12px 35px rgba(15,23,42,.06);
    overflow:hidden;
}

.form-label{
    font-weight:700;
    color:#334155;
    margin-bottom:8px;
}

.form-control,
.form-select{
    height:52px;
    border-radius:14px;
    border:1px solid #dbe4ee;
    box-shadow:none;
    transition:.25s;
}

.form-control:focus,
.form-select:focus{
    border-color:#0ea5e9;
    box-shadow:0 0 0 .18rem rgba(14,165,233,.12);
}

.required{
    color:#ef4444;
}

.btn-save{
    border-radius:14px;
    padding:12px 24px;
    font-weight:600;
}

.btn-back{
    border-radius:14px;
    padding:12px 24px;
    font-weight:600;
}

.info-box{
    background:#f8fafc;
    border:1px dashed #cbd5e1;
    border-radius:16px;
    padding:20px;
}

.info-box i{
    font-size:40px;
    color:#0ea5e9;
}

.badge-status{
    background:#ecfeff;
    color:#0891b2;
    font-weight:600;
    border-radius:30px;
    padding:8px 16px;
}

@media(max-width:768px){

    .page-header{
        padding:24px;
    }

    .page-header h3{
        font-size:24px;
    }

}

</style>

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="page-header">

        <span class="badge bg-warning bg-opacity-25 text-warning mb-3">
            Master Akademik
        </span>

        <h3 class="fw-bold mb-2">

            Ubah Tingkat

        </h3>

        <p class="mb-0 text-light opacity-75">

            Perbarui informasi tingkat sesuai kebutuhan sistem CBT.

        </p>

    </div>

    <form action="{{ route('tingkat.update',$tingkat->id) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="row">

            {{-- FORM --}}
            <div class="col-lg-8">

                <div class="card content-card">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">

                            <i class="fa-solid fa-pen-to-square text-primary me-2"></i>

                            Informasi Tingkat

                        </h5>

                        {{-- Jenjang --}}
                        <div class="mb-4">

                            <label class="form-label">

                                Jenjang

                                <span class="required">*</span>

                            </label>

                            <select
                                name="jenjang_id"
                                class="form-select @error('jenjang_id') is-invalid @enderror"
                                {{ Auth::user()->role == 'admin_jenjang' ? 'disabled' : '' }}>

                                @foreach($jenjangs as $jenjang)

                                    <option
                                        value="{{ $jenjang->id }}"
                                        {{ old('jenjang_id',$tingkat->jenjang_id)==$jenjang->id ? 'selected' : '' }}>

                                        {{ $jenjang->nama_jenjang }}

                                    </option>

                                @endforeach

                            </select>

                            @if(Auth::user()->role == 'admin_jenjang')

                                <input
                                    type="hidden"
                                    name="jenjang_id"
                                    value="{{ $tingkat->jenjang_id }}">

                            @endif

                            @error('jenjang_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Nama Tingkat --}}
                        <div class="mb-4">

                            <label class="form-label">

                                Nama Tingkat

                                <span class="required">*</span>

                            </label>

                            <input
                                type="text"
                                name="nama_tingkat"
                                class="form-control @error('nama_tingkat') is-invalid @enderror"
                                value="{{ old('nama_tingkat',$tingkat->nama_tingkat) }}"
                                placeholder="Contoh : Kelas VII">

                            @error('nama_tingkat')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                            <div class="form-text">

                                Contoh:
                                Kelas 1,
                                Kelas VII,
                                Kelas X,
                                Kelas XII.

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- INFORMASI --}}
            <div class="col-lg-4 mt-4 mt-lg-0">

                <div class="card content-card">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">

                            <i class="fa-solid fa-circle-info text-info me-2"></i>

                            Detail Data

                        </h5>

                        <div class="info-box text-center">

                            <i class="fa-solid fa-layer-group mb-3"></i>

                            <h6 class="fw-bold">

                                Data Saat Ini

                            </h6>

                            <hr>

                            <p class="mb-2">

                                <strong>Jenjang</strong>

                            </p>

                            <span class="badge-status">

                                {{ $tingkat->jenjang->nama_jenjang }}

                            </span>

                            <p class="mt-4 mb-2">

                                <strong>Tingkat</strong>

                            </p>

                            <h5 class="fw-bold text-primary">

                                {{ $tingkat->nama_tingkat }}

                            </h5>

                            <hr>

                            <small class="text-muted">

                                Dibuat pada

                                <br>

                                {{ $tingkat->created_at->locale('id')->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB

                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- BUTTON --}}
        <div class="d-flex justify-content-end gap-2 mt-4">

            <a href="{{ route('tingkat.index') }}"
               class="btn btn-light border btn-back">

                <i class="fa-solid fa-arrow-left me-2"></i>

                Kembali

            </a>

            <button
                type="submit"
                class="btn btn-warning text-dark btn-save">

                <i class="fa-solid fa-floppy-disk me-2"></i>

                Simpan Perubahan

            </button>

        </div>

    </form>

</div>

@endsection