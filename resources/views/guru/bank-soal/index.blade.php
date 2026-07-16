@extends('layouts.app')
@section('title', 'Bank Soal')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold text-white">Daftar Bank Soal</h4>
            <p class="mb-0 opacity-75">Kelola bank soal untuk mata pelajaran Anda.</p>
        </div>
        <a href="{{ route('dashboard-guru.bank-soal.create') }}" class="btn btn-light fw-bold rounded-3">
            <i class="fa-solid fa-plus me-2"></i> Tambah Bank Soal
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Bank Soal</th>
                            <th>Mapel</th>
                            <th>Jenjang</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bankSoals as $index => $bs)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $bs->nama_bank_soal }}</td>
                            <td>{{ $bs->mataPelajaran->nama_mapel ?? '-' }}</td>
                            <td>{{ $bs->jenjang->nama_jenjang ?? '-' }}</td>
                            <td>
                                @if($bs->is_publish)
                                    <span class="badge bg-success">Publik</span>
                                @else
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('dashboard-guru.bank-soal.show', $bs->id) }}" class="btn btn-sm btn-info text-white me-1">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <form action="{{ route('dashboard-guru.bank-soal.destroy', $bs->id) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada bank soal dibuat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 untuk Konfirmasi Hapus --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.form-delete').forEach(function(form){
    form.addEventListener('submit', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Hapus Bank Soal?',
            text: 'Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection