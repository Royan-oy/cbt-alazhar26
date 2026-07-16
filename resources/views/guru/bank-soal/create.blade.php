@extends('layouts.app')
@section('title', 'Tambah Soal')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="page-header mb-4">
        <h4 class="fw-bold">Tambah Butir Soal</h4>
        <p class="mb-0 opacity-75">Kelola soal untuk bank soal ini.</p>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <form action="" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Jenis Soal</label>
                    <select name="jenis_soal" id="jenis_soal" class="form-select" onchange="toggleSoalFields()" required>
                        <option value="pilihan_ganda">Pilihan Ganda</option>
                        <option value="essay">Essay</option>
                        <option value="isian">Isian Singkat</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Teks Pertanyaan</label>
                    <textarea name="teks_soal" class="form-control" rows="5" required>{{ old('teks_soal') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Bobot Nilai</label>
                    <input type="number" name="bobot" class="form-control" value="{{ old('bobot', 1) }}" required>
                </div>

                {{-- Field Dinamis Pilihan Ganda --}}
                <div id="pg-fields">
                    <h6 class="fw-bold mt-4 mb-3">Pilihan Jawaban (Pilih Kunci Jawaban)</h6>
                    @foreach(['A', 'B', 'C', 'D', 'E'] as $opsi)
                    <div class="input-group mb-2">
                        <div class="input-group-text">
                            <input type="radio" name="kunci_jawaban" value="{{ $opsi }}" {{ $loop->first ? 'checked' : '' }}>
                        </div>
                        <span class="input-group-text">{{ $opsi }}</span>
                        <input type="text" name="teks_pilihan_{{ $opsi }}" class="form-control" placeholder="Teks pilihan {{ $opsi }}">
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Soal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleSoalFields() {
    const jenis = document.getElementById('jenis_soal').value;
    const pgFields = document.getElementById('pg-fields');
    
    if (jenis === 'pilihan_ganda') {
        pgFields.style.display = 'block';
    } else {
        pgFields.style.display = 'none';
    }
}
</script>
@endsection