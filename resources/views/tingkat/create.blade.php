@extends('layouts.app')

@section('title','Tambah Tingkat')

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

textarea.form-control{
    height:auto;
}

.required{
    color:#ef4444;
}

.form-text{
    font-size:12px;
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
    padding:18px;
}

.info-box i{
    font-size:35px;
    color:#0ea5e9;
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

        <span class="badge bg-info bg-opacity-25 text-info mb-3">
            Master Akademik
        </span>

        <h3 class="fw-bold mb-2">
            Tambah Tingkat
        </h3>

        <p class="mb-0 text-light opacity-75">
            Tambahkan tingkat baru sesuai jenjang pendidikan yang tersedia pada sistem CBT.
        </p>

    </div>

    <form action="{{ route('tingkat.store') }}" method="POST">

        @csrf

        <div class="row">

            <div class="col-lg-8">

                <div class="card content-card">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">

                            <i class="fa-solid fa-school text-primary me-2"></i>

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
                                        {{ old('jenjang_id',$jenjangs->count()==1 ? $jenjang->id : '') == $jenjang->id ? 'selected' : '' }}>

                                        {{ $jenjang->nama_jenjang }}

                                    </option>

                                @endforeach

                            </select>

                            @if(Auth::user()->role == 'admin_jenjang')

                                <input
                                    type="hidden"
                                    name="jenjang_id"
                                    value="{{ $jenjangs->first()->id }}">

                            @endif

                            @error('jenjang_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- Tingkat --}}
                        <div class="mb-4">

                            <label class="form-label">

                                Nama Tingkat
                                <span class="required">*</span>

                            </label>

                            <input
                                type="text"
                                name="nama_tingkat"
                                value="{{ old('nama_tingkat') }}"
                                class="form-control @error('nama_tingkat') is-invalid @enderror"
                                placeholder="Contoh : Kelas 1">

                            @error('nama_tingkat')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                            @enderror

                            <div class="form-text">

                                Contoh:
                                Kelas 1,
                                Kelas 2,
                                Kelas VII,
                                Kelas X.

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-4 mt-4 mt-lg-0">

                <div class="card content-card">

                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-4">

                            <i class="fa-solid fa-circle-info text-info me-2"></i>

                            Informasi

                        </h5>

                        <div class="info-box text-center">

                            <i class="fa-solid fa-layer-group mb-3"></i>

                            <h6 class="fw-bold">

                                Tingkat Akademik

                            </h6>

                            <small class="text-muted d-block mt-2">

                                Tingkat digunakan sebagai dasar pembentukan kelas.

                                <br><br>

                                Contoh:

                                <br>

                                • Kelas 1

                                <br>

                                • Kelas VII

                                <br>

                                • Kelas X

                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">

            <a href="{{ route('tingkat.index') }}"
                class="btn btn-light btn-back border">

                <i class="fa-solid fa-arrow-left me-2"></i>

                Kembali

            </a>

            <button
                type="submit"
                class="btn btn-primary btn-save">

                <i class="fa-solid fa-floppy-disk me-2"></i>

                Simpan Tingkat

            </button>

        </div>

    </form>

</div>

@endsection