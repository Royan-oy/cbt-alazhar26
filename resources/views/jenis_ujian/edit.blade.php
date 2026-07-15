@extends('layouts.app')

@section('title','Ubah Jenis Ujian')

@section('content')

<style>

.page-header{
    background:linear-gradient(135deg,#0f172a,#1e293b);
    border-radius:20px;
    padding:28px;
    color:white;
    position:relative;
    overflow:hidden;
}

.page-header::after{
    content:'';
    position:absolute;
    width:260px;
    height:260px;
    right:-70px;
    top:-70px;
    border-radius:50%;
    background:rgba(56,189,248,.08);
}

.form-card{
    background:#fff;
    border:none;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(15,23,42,.05);
}

.form-label{
    font-weight:600;
    color:#334155;
}

.form-control{
    border-radius:12px;
    min-height:46px;
}

textarea.form-control{
    min-height:120px;
}

.btn-save{
    border-radius:12px;
    padding:10px 22px;
    font-weight:600;
}

.btn-back{
    border-radius:12px;
    padding:10px 22px;
    font-weight:600;
}

</style>

<div class="container-fluid">

    <div class="page-header mb-4">

        <span class="badge bg-warning text-dark mb-3">
            Master Data
        </span>

        <h3 class="fw-bold mb-2">
            Ubah Jenis Ujian
        </h3>

        <p class="opacity-75 mb-0">
            Perbarui informasi jenis ujian yang digunakan pada sistem CBT.
        </p>

    </div>

    <div class="card form-card">

        <div class="card-body p-4">

            <form action="{{ route('jenis-ujian.update',$jenisUjian->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Kode Jenis Ujian
                    </label>

                    <input
                        type="text"
                        name="kode"
                        maxlength="20"
                        class="form-control @error('kode') is-invalid @enderror"
                        value="{{ old('kode',$jenisUjian->kode) }}"
                        style="text-transform:uppercase">

                    @error('kode')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Nama Jenis Ujian
                    </label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama',$jenisUjian->nama) }}">

                    @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Deskripsi
                    </label>

                    <textarea
                        name="deskripsi"
                        class="form-control @error('deskripsi') is-invalid @enderror"
                        placeholder="Masukkan deskripsi jenis ujian (opsional)">{{ old('deskripsi',$jenisUjian->deskripsi) }}</textarea>

                    @error('deskripsi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('jenis-ujian.index') }}"
                       class="btn btn-light btn-back">

                        <i class="fa-solid fa-arrow-left me-2"></i>

                        Kembali

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary btn-save">

                        <i class="fa-solid fa-floppy-disk me-2"></i>

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection