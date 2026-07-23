@extends('layouts.app')

@section('title', 'Detail Bank Soal')

@section('content')

<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        border-radius: 24px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.05);
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

    .btn-publish-action {
        border-radius: 14px;
        padding: 10px 20px;
        font-weight: 600;
        border: none;
    }

    .content-card {
        background: var(--surface-white);
        border-radius: 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.03);
        padding: 24px;
    }

    .info-item {
        padding: 14px 0;
        border-bottom: 1px dashed var(--border-color);
    }

    .info-item:last-child { border-bottom: none; }

    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--secondary-dark);
    }

    .status-publish {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid rgba(5, 150, 105, 0.15);
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-draft {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid rgba(217, 119, 6, 0.15);
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--secondary-dark);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .jenis-badge {
        background: #f0f9ff;
        color: #0284c7;
        border: 1px solid rgba(2, 132, 199, 0.15);
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 8px;
        display: inline-block;
    }

    .soal-item {
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
    }

    .soal-number {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: #f8fafc;
        color: var(--text-muted);
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .rekap-jenis-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 10px 14px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .page-header { padding: 24px; border-radius: 18px; text-align: center; }
        .page-header .d-flex { flex-direction: column; gap: 16px; }
        .content-card { padding: 16px; }
    }
</style>

<div class="container-fluid py-2">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    DETAIL BANK SOAL
                </span>
                <h3 class="fw-bold mb-1">{{ $bankSoal->nama_bank_soal }}</h3>
                <p class="text-light opacity-75 mb-0 small">
                    {{ optional($bankSoal->mataPelajaran)->nama_mapel ?? '-' }}
                    &middot; {{ optional($bankSoal->jenjang)->nama_jenjang ?? '-' }}
                </p>
            </div>

            <div class="d-flex gap-2">
                <form action="{{ route('bank-soal.toggle-publish', $bankSoal->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn {{ $bankSoal->is_publish ? 'btn-warning' : 'btn-success' }} text-white btn-publish-action">
                        <i class="fa-solid {{ $bankSoal->is_publish ? 'fa-eye-slash' : 'fa-circle-check' }} me-2"></i>
                        {{ $bankSoal->is_publish ? 'Tarik ke Draft' : 'Publikasikan' }}
                    </button>
                </form>

                <a href="{{ route('bank-soal.index') }}" class="btn-back d-inline-flex align-items-center">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm d-flex align-items-center p-3 mb-4">
        <i class="fa-solid fa-circle-check fs-5 me-2"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm d-flex align-items-center p-3 mb-4">
        <i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    <div class="row g-4">

        {{-- Kolom Kiri: Info --}}
        <div class="col-lg-4">
            <div class="content-card h-100">

                <div class="mb-3">
                    @if($bankSoal->is_publish)
                        <span class="status-publish"><i class="fa-solid fa-circle-check"></i> Sudah Publish</span>
                    @else
                        <span class="status-draft"><i class="fa-solid fa-pen-to-square"></i> Masih Draft</span>
                    @endif
                </div>

                <div class="info-item">
                    <div class="info-label">Guru Pengampu</div>
                    <div class="info-value">{{ optional(optional($bankSoal->guruMapel)->guru)->nama ?? '-' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Mata Pelajaran</div>
                    <div class="info-value">{{ optional($bankSoal->mataPelajaran)->nama_mapel ?? '-' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Jenjang</div>
                    <div class="info-value">{{ optional($bankSoal->jenjang)->nama_jenjang ?? '-' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Nilai KKM</div>
                    <div class="info-value">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 13px;">
                            {{ $bankSoal->kkm ?? 75 }}
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Deskripsi</div>
                    <div class="info-value fw-normal">{{ $bankSoal->deskripsi ?? '-' }}</div>
                </div>


                <div class="info-item">
                    <div class="info-label">Total Soal</div>
                    <div class="info-value">{{ $bankSoal->soals->count() ?? $soals->total() }} soal</div>
                </div>

                @if(count($rekapJenis) > 0)
                <div class="info-item">
                    <div class="info-label mb-2">Rekap per Jenis Soal</div>
                    <div class="row g-2">
                        @foreach($rekapJenis as $jenis => $jumlah)
                        <div class="col-6">
                            <div class="rekap-jenis-item">
                                <div class="fw-bold text-dark">{{ $jumlah }}</div>
                                <small class="text-muted">{{ \App\Models\Soal::jenisLabel($jenis) }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- Kolom Kanan: Daftar Soal (read-only) --}}
        <div class="col-lg-8">
            <div class="content-card">
                <div class="section-title">
                    <i class="fa-solid fa-list-ol text-primary"></i>
                    Daftar Soal
                </div>

                @forelse($soals as $soal)
                    <div class="soal-item">
                        <div class="d-flex align-items-start gap-3">
                            <div class="soal-number">{{ $soal->urutan }}</div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                    <span class="jenis-badge">{{ \App\Models\Soal::jenisLabel($soal->jenis_soal) }}</span>
                                    <span class="text-muted small">Bobot: {{ $soal->bobot }}</span>
                                </div>
                                <div class="text-dark mb-2">{{ \Illuminate\Support\Str::limit(strip_tags($soal->teks_soal), 200) }}</div>

                                @if(in_array($soal->jenis_soal, ['pilihan_ganda', 'pilihan_ganda_kompleks', 'benar_salah']))
                                    <ul class="list-unstyled mt-2 mb-0">
                                        @foreach($soal->pilihanJawabans as $pilihan)
                                        <li class="d-flex align-items-center gap-2 py-1 {{ $pilihan->is_benar ? 'text-success fw-semibold' : 'text-muted' }}">
                                            @if($pilihan->is_benar)
                                                <i class="fa-solid fa-circle-check"></i>
                                            @else
                                                <i class="fa-regular fa-circle"></i>
                                            @endif
                                            {{ $pilihan->kode ? $pilihan->kode.'. ' : '' }}{{ $pilihan->teks_pilihan }}
                                        </li>
                                        @endforeach
                                    </ul>

                                @elseif($soal->jenis_soal == 'menjodohkan')
                                    <ul class="list-unstyled mt-2 mb-0">
                                        @foreach($soal->pilihanJawabans as $pilihan)
                                        <li class="py-1">
                                            <span class="fw-semibold">{{ $pilihan->teks_pilihan }}</span>
                                            <i class="fa-solid fa-arrow-right-long mx-2 text-muted"></i>
                                            <span>{{ $pilihan->pasangan }}</span>
                                        </li>
                                        @endforeach
                                    </ul>

                                @elseif($soal->jenis_soal == 'mengurutkan')
                                    <ol class="mt-2 mb-0">
                                        @foreach($soal->pilihanJawabans->sortBy('urutan') as $pilihan)
                                        <li>{{ $pilihan->teks_pilihan }}</li>
                                        @endforeach
                                    </ol>

                                @elseif(in_array($soal->jenis_soal, ['essay', 'isian']))
                                    <div class="text-muted small fst-italic mt-2">
                                        <i class="fa-solid fa-circle-info me-1"></i>
                                        Soal tipe {{ \App\Models\Soal::jenisLabel($soal->jenis_soal) }} — dinilai manual oleh guru
                                    </div>
                                @endif

                                @if($soal->gambar)
                                    <img src="{{ asset('storage/' . $soal->gambar) }}" alt="Gambar soal" class="mt-2 rounded-3" style="max-width: 200px;">
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                <div class="text-center py-5">
                    <i class="fa-solid fa-circle-question fa-3x text-muted mb-3 opacity-50"></i>
                    <h6 class="fw-bold text-secondary">Bank soal ini belum berisi soal apapun</h6>
                    <small class="text-muted">Guru pengampu perlu menambahkan soal sebelum bank soal ini bisa dipublikasikan.</small>
                </div>
                @endforelse

                @if($soals->hasPages())
                <div class="d-flex justify-content-end mt-3">
                    {{ $soals->links('vendor.pagination.bootstrap-4') }}
                </div>
                @endif

            </div>
        </div>

    </div>

</div>

@endsection