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

    .page-header::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        right: -50px;
        top: -80px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, rgba(14, 165, 233, 0) 70%);
        pointer-events: none;
    }

    .page-header-content { position: relative; z-index: 1; }

    .btn-back {
        background: rgba(255,255,255,0.1);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 14px;
        padding: 10px 18px;
        font-weight: 600;
        white-space: nowrap;
    }

    .btn-back:hover { background: rgba(255,255,255,0.2); color: #fff; }

    .btn-publish-action {
        border-radius: 14px;
        padding: 10px 20px;
        font-weight: 600;
        border: none;
        white-space: nowrap;
    }

    .content-card {
        background: var(--surface-white);
        border-radius: 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.03);
        padding: 24px;
    }

    /* Sidebar info sticky di desktop supaya tetap terlihat saat scroll daftar soal */
    .info-sidebar {
        position: sticky;
        top: 20px;
    }

    .info-item {
        padding: 14px 0;
        border-bottom: 1px dashed var(--border-color);
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .info-item:last-child { border-bottom: none; }

    .info-item-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #f8fafc;
        color: var(--accent-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 14.5px;
        font-weight: 600;
        color: var(--secondary-dark);
        line-height: 1.4;
        word-break: break-word;
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

    .section-title .count-badge {
        margin-left: auto;
        background: #f8fafc;
        color: var(--text-muted);
        border: 1px solid var(--border-color);
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
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
        padding: 18px;
        margin-bottom: 14px;
        background: #fff;
        transition: box-shadow 0.2s, border-color 0.2s;
    }

    .soal-item:hover {
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.05);
        border-color: #dbeafe;
    }

    .soal-number {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: var(--primary-dark);
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .soal-teks {
        font-size: 14.5px;
        line-height: 1.6;
        color: var(--secondary-dark);
    }

    .jawaban-list {
        margin-top: 10px;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        overflow: hidden;
    }

    .jawaban-list li {
        padding: 9px 14px;
        font-size: 13.5px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .jawaban-list p,
    .bs-pernyataan p,
    .m-kiri p,
    .m-kanan p,
    .soal-teks p {
        display: inline;
        margin-bottom: 0;
    }

    .jawaban-list li:last-child { border-bottom: none; }

    .jawaban-list li.is-correct {
        background: #ecfdf5;
        color: #059669;
        font-weight: 600;
    }

    .jawaban-list li.is-wrong { color: var(--text-muted); }

    .soal-img,
    .soal-teks img {
        margin-top: 10px;
        margin-bottom: 10px;
        max-width: 220px;
        width: 100%;
        height: auto;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        display: block;
    }

    .rekap-jenis-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 12px 10px;
        text-align: center;
        border: 1px solid transparent;
        transition: all 0.2s;
    }

    .rekap-jenis-item:hover {
        border-color: #bae6fd;
        background: #f0f9ff;
    }

    .soal-empty-state {
        text-align: center;
        padding: 56px 16px;
    }

    /* ============================================
       RESPONSIVE: TABLET & MOBILE (<= 768px)
       ============================================ */
    @media (max-width: 768px) {
        .container-fluid.py-2 { padding-left: 12px; padding-right: 12px; }

        /* Header */
        .page-header { padding: 22px 18px; border-radius: 20px; }
        .page-header-content.d-flex.justify-content-between {
            flex-direction: column;
            align-items: stretch !important;
            gap: 16px;
        }
        .page-header h3 { font-size: 18px; margin-bottom: 4px; }
        .page-header p.small { font-size: 12.5px; }

        .page-header-content .d-flex.gap-2 {
            flex-direction: column;
            width: 100%;
        }
        .btn-publish-action, .btn-back {
            width: 100%;
            justify-content: center;
            display: flex;
            align-items: center;
        }
        .btn-publish-action { order: 1; }
        .btn-back { order: 2; }

        /* Layout kolom info & soal */
        .row.g-4 { row-gap: 16px !important; }

        .content-card { padding: 16px; border-radius: 20px; }

        .info-sidebar { position: static; }

        .info-item { padding: 12px 0; gap: 10px; }
        .info-item-icon { width: 30px; height: 30px; font-size: 12px; border-radius: 9px; }
        .info-label { font-size: 10px; }
        .info-value { font-size: 13.5px; }

        .section-title { font-size: 14px; margin-bottom: 12px; }

        .soal-item { padding: 14px; border-radius: 14px; margin-bottom: 12px; }
        .soal-teks { font-size: 13.5px; }
        .soal-number { width: 30px; height: 30px; font-size: 12px; }

        .jawaban-list li { font-size: 12.5px; padding: 8px 12px; }

        .soal-img,
        .soal-teks img { max-width: 100%; }

        .rekap-jenis-item { padding: 10px 8px; }

        .pagination-container { justify-content: center !important; }
        .pagination { justify-content: center !important; flex-wrap: wrap; }
    }

    /* Tabel Benar/Salah */
    .bs-table-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        font-size: 13.5px;
        border-bottom: 1px solid #f1f5f9;
    }
    .bs-table-row:last-child { border-bottom: none; }
    .bs-table-row .bs-pernyataan { flex: 1; color: var(--secondary-dark); }
    .bs-kunci-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 50px;
        font-size: 11px; font-weight: 700; flex-shrink: 0;
    }
    .bs-kunci-pill.benar { background: #ecfdf5; color: #059669; border: 1px solid rgba(5,150,105,0.15); }
    .bs-kunci-pill.salah { background: #fef2f2; color: #dc2626; border: 1px solid rgba(220,38,38,0.15); }

    /* Mencocokkan */
    .matching-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        font-size: 13.5px;
        border-bottom: 1px solid #f1f5f9;
    }
    .matching-row:last-child { border-bottom: none; }
    .matching-row .m-kiri { flex: 1; color: var(--secondary-dark); font-weight: 500; }
    .matching-row .m-kanan { flex: 1; color: #059669; font-weight: 600; }

    /* Isian */
    .isian-kunci-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        font-size: 13.5px;
        background: #ecfdf5;
        color: #059669;
        font-weight: 600;
    }
</style>

<div class="container-fluid py-2">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div class="page-header-content d-flex justify-content-between align-items-center flex-wrap gap-3">
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
            <div class="content-card  info-sidebar">

                <div class="mb-3">
                    @if($bankSoal->is_publish)
                        <span class="status-publish"><i class="fa-solid fa-circle-check"></i> Sudah Publish</span>
                    @else
                        <span class="status-draft"><i class="fa-solid fa-pen-to-square"></i> Masih Draft</span>
                    @endif
                </div>

                <div class="info-item">
                    <div class="info-item-icon"><i class="fa-solid fa-user-tie"></i></div>
                    <div>
                        <div class="info-label">Guru Pengampu</div>
                        <div class="info-value">{{ optional(optional($bankSoal->guruMapel)->guru)->nama ?? '-' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-item-icon"><i class="fa-solid fa-book"></i></div>
                    <div>
                        <div class="info-label">Mata Pelajaran</div>
                        <div class="info-value">{{ optional($bankSoal->mataPelajaran)->nama_mapel ?? '-' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-item-icon"><i class="fa-solid fa-school"></i></div>
                    <div>
                        <div class="info-label">Jenjang</div>
                        <div class="info-value">{{ optional($bankSoal->jenjang)->nama_jenjang ?? '-' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-item-icon"><i class="fa-solid fa-bullseye"></i></div>
                    <div>
                        <div class="info-label">Nilai KKM</div>
                        <div class="info-value">
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 13px;">
                                {{ $bankSoal->kkm ?? 75 }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-item-icon"><i class="fa-solid fa-align-left"></i></div>
                    <div>
                        <div class="info-label">Deskripsi</div>
                        <div class="info-value fw-normal">{{ $bankSoal->deskripsi ?? '-' }}</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-item-icon"><i class="fa-solid fa-list-ol"></i></div>
                    <div>
                        <div class="info-label">Total Soal</div>
                        <div class="info-value">{{ $bankSoal->soals->count() ?? $soals->total() }} soal</div>
                    </div>
                </div>

                @if(count($rekapJenis) > 0)
                <div class="info-item" style="border-bottom: none;">
                    <div class="w-100">
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
                    <span class="count-badge">{{ $soals->total() ?? $soals->count() }} soal</span>
                </div>

                @forelse($soals as $soal)
                    <div class="soal-item">
                        <div class="d-flex align-items-start gap-3">
                            <div class="soal-number">{{ $soal->urutan }}</div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                    <span class="jenis-badge">{{ \App\Models\Soal::jenisLabel($soal->jenis_soal) }}</span>
                                    <span class="text-muted small">Bobot: {{ $soal->bobot }}</span>
                                </div>
                                <div class="soal-teks mb-2">{!! $soal->teks_soal !!}</div>

                                @if(in_array($soal->jenis_soal, ['pilihan_ganda', 'pilihan_ganda_kompleks']))
                                    <ul class="list-unstyled jawaban-list mb-0">
                                        @foreach($soal->pilihanJawabans as $pilihan)
                                        <li class="{{ $pilihan->is_benar ? 'is-correct' : 'is-wrong' }}">
                                            @if($pilihan->is_benar)
                                                <i class="fa-solid fa-circle-check"></i>
                                            @else
                                                <i class="fa-regular fa-circle"></i>
                                            @endif
                                            <span><strong>{{ $pilihan->kode ? $pilihan->kode.'. ' : '' }}</strong>{!! $pilihan->teks_pilihan !!}</span>
                                        </li>
                                        @endforeach
                                    </ul>

                                @elseif($soal->jenis_soal == 'benar_salah')
                                    <div class="jawaban-list mb-0">
                                        @foreach($soal->pilihanJawabans as $pilihan)
                                        <div class="bs-table-row">
                                            <span class="bs-pernyataan"><strong>{{ $pilihan->kode ? $pilihan->kode.'. ' : '' }}</strong>{!! $pilihan->teks_pilihan !!}</span>
                                            <span class="bs-kunci-pill {{ $pilihan->is_benar ? 'benar' : 'salah' }}">
                                                <i class="fa-solid {{ $pilihan->is_benar ? 'fa-check' : 'fa-xmark' }}"></i>
                                                {{ $pilihan->is_benar ? 'Benar' : 'Salah' }}
                                            </span>
                                        </div>
                                        @endforeach
                                    </div>

                                @elseif($soal->jenis_soal == 'mencocokkan')
                                    <div class="jawaban-list mb-0">
                                        @foreach($soal->pilihanJawabans as $pilihan)
                                        <div class="matching-row">
                                            <span class="m-kiri">{!! $pilihan->teks_pilihan !!}</span>
                                            <i class="fa-solid fa-arrow-right-long text-muted"></i>
                                            <span class="m-kanan">{!! $pilihan->pasangan !!}</span>
                                        </div>
                                        @endforeach
                                    </div>

                                @elseif($soal->jenis_soal == 'isian')
                                    <div class="jawaban-list mb-0">
                                        <div class="isian-kunci-row">
                                            <i class="fa-solid fa-key"></i>
                                            Kunci: {!! optional($soal->pilihanJawabans->first())->teks_pilihan ?? '-' !!}
                                        </div>
                                    </div>

                                @elseif($soal->jenis_soal == 'essay')
                                    <div class="text-muted small fst-italic mt-2">
                                        <i class="fa-solid fa-circle-info me-1"></i>
                                        Soal tipe Essay — dinilai manual oleh guru
                                    </div>
                                @endif

                                @if($soal->gambar)
                                    <img src="{{ asset('storage/' . $soal->gambar) }}" alt="Gambar soal" class="soal-img">
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                <div class="soal-empty-state">
                    <i class="fa-solid fa-circle-question fa-3x text-muted mb-3 opacity-50"></i>
                    <h6 class="fw-bold text-secondary">Bank soal ini belum berisi soal apapun</h6>
                    <small class="text-muted">Guru pengampu perlu menambahkan soal sebelum bank soal ini bisa dipublikasikan.</small>
                </div>
                @endforelse

                @if($soals->hasPages())
                <div class="d-flex justify-content-end mt-3 pagination-container">
                    {{ $soals->links('vendor.pagination.bootstrap-4') }}
                </div>
                @endif

            </div>
        </div>

    </div>

</div>

@endsection