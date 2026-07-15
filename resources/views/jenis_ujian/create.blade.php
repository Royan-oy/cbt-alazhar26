@extends('layouts.app')

@section('title','Tambah Jenis Ujian')

@section('content')

<style>

.page-header{
    background:linear-gradient(135deg,#0f172a,#1e293b);
    color:white;
    border-radius:20px;
    padding:30px;
}

.form-card{
    background:white;
    border:none;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(15,23,42,.06);
}

.form-label{
    font-weight:600;
    color:#334155;
}

.form-control{
    border-radius:12px;
    height:48px;
}

textarea.form-control{
    height:120px;
}

.btn-save{
    border-radius:12px;
    padding:12px 28px;
    font-weight:600;
}

.btn-back{
    border-radius:12px;
    padding:12px 28px;
    font-weight:600;
}

</style>

<div class="container-fluid">

    <div class="page-header mb-4">

        <span class="badge bg-info bg-opacity-25 text-info mb-3">

            Master Data

        </span>

        <h3 class="fw-bold mb-2">

            Tambah Jenis Ujian

        </h3>

        <p class="mb-0 text-light opacity-75">

            Tambahkan jenis ujian baru yang akan digunakan pada sistem CBT.

        </p>

    </div>

    <div class="card form-card">

        <div class="card-body p-4">

            <form action="{{ route('jenis-ujian.store') }}" method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-4 mb-4">

                        <label class="form-label">
                            Kode Jenis Ujian
                        </label>

                        <input
                            type="text"
                            name="kode"
                            value="{{ old('kode') }}"
                            class="form-control @error('kode') is-invalid @enderror"
                            placeholder="Contoh : PTS">

                        @error('kode')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="text-muted">
                            Maksimal 20 karakter.
                        </small>

                    </div>

                    <div class="col-md-8 mb-4">

                        <label class="form-label">
                            Nama Jenis Ujian
                        </label>

                        <input
                            type="text"
                            name="nama"
                            value="{{ old('nama') }}"
                            class="form-control @error('nama') is-invalid @enderror"
                            placeholder="Contoh : Penilaian Tengah Semester">

                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="mb-4">

                    <label class="form-label">

                        Deskripsi

                    </label>

                    <textarea
                        name="deskripsi"
                        rows="4"
                        class="form-control @error('deskripsi') is-invalid @enderror"
                        placeholder="Deskripsi (Opsional)">{{ old('deskripsi') }}</textarea>

                    @error('deskripsi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="alert alert-warning rounded-4">

                    <i class="fa-solid fa-circle-info me-2"></i>

                    Jenis ujian yang ditambahkan akan berstatus
                    <strong>Tidak Aktif</strong>. Status dapat diubah dari halaman daftar jenis ujian.

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('jenis-ujian.index') }}"
                    class="btn btn-light">

                        <i class="fa-solid fa-arrow-left me-2"></i>

                        Kembali

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fa-solid fa-floppy-disk me-2"></i>

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection