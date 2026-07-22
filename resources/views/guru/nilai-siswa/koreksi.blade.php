@extends('layouts.app')

@section('title', 'Koreksi Jawaban - Guru Mapel')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1px solid #bae6fd;
        padding: 2rem 1.5rem;
        margin-bottom: 2rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.1), 0 2px 4px -1px rgba(14, 165, 233, 0.06);
        position: relative;
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #fff;
        color: #0369a1;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.8125rem;
        font-weight: 600;
        border: 1px solid #bae6fd;
        transition: all 0.2s;
        margin-bottom: 1.5rem;
    }
    .btn-back:hover {
        background: #0284c7;
        color: #fff;
        border-color: #0284c7;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    .page-description {
        color: #475569;
        font-size: 0.875rem;
        margin-bottom: 0;
    }
    
    .info-card {
        background: #fff;
        border: 1px solid #bae6fd;
        border-radius: 0.5rem;
        padding: 1.25rem;
        text-align: center;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .info-card .number {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    .info-card .label {
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Questions & Grading Styles */
    .question-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }
    
    .question-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .question-no {
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .badge-no {
        background: #e0f2fe;
        color: #0284c7;
        width: 28px; height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 700;
    }
    
    .badge-bobot {
        background: #f1f5f9;
        color: #475569;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .question-body {
        padding: 1.5rem;
    }
    
    .soal-text {
        font-size: 0.95rem;
        color: #334155;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .soal-img {
        max-width: 100%;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }
    
    .answer-box {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        padding: 1rem 1.25rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .answer-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #0284c7;
        margin-bottom: 0.5rem;
        letter-spacing: 0.05em;
    }
    
    .answer-text {
        font-size: 0.95rem;
        color: #0f172a;
        white-space: pre-wrap;
        margin-bottom: 0;
    }

    .grading-box {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 1.25rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        align-items: center;
    }
    
    .grading-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.5rem;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .score-input {
        width: 100px;
        text-align: center;
        font-weight: 700;
        color: #0f172a;
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem;
        padding: 0.5rem;
        transition: all 0.2s;
    }
    .score-input:focus {
        outline: none;
        border-color: #0ea5e9;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
    }
    
    .status-radio-group {
        display: flex;
        gap: 0.75rem;
    }
    
    .custom-radio {
        display: none;
    }
    .custom-radio-label {
        padding: 0.375rem 1rem;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #64748b;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }
    .custom-radio:checked + .label-benar {
        border-color: #22c55e;
        background: #f0fdf4;
        color: #16a34a;
    }
    .custom-radio:checked + .label-salah {
        border-color: #ef4444;
        background: #fef2f2;
        color: #dc2626;
    }
    
    .btn-save {
        background: #0284c7;
        color: #fff;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        font-weight: 600;
        border: none;
        transition: background 0.2s;
        box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.2);
    }
    .btn-save:hover {
        background: #0369a1;
    }
    
    .sticky-action-bar {
        position: sticky;
        bottom: 1.5rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        border: 1px solid #bae6fd;
        border-radius: 0.75rem;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 15px -3px rgba(14, 165, 233, 0.1);
        z-index: 10;
    }

    /* PG Accordion Styles */
    .pg-option-item {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 13px;
        margin-bottom: 6px;
        border: 1px solid #e2e8f0;
        background-color: #fff;
    }
    .pg-option-item.correct-key {
        background-color: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
        font-weight: 600;
    }
    .pg-option-item.student-wrong {
        background-color: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
        font-weight: 600;
    }
    .accordion-button:not(.collapsed) {
        color: #0369a1;
        background-color: #f0f9ff;
        box-shadow: inset 0 -1px 0 #bae6fd;
    }
    .accordion-button:focus {
        box-shadow: none;
    }

    /* Tab Navigation */
    .nav-pills .nav-link {
        color: #64748b;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 999px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .nav-pills .nav-link:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .nav-pills .nav-link.active {
        background: #0284c7;
        color: #fff;
        box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.2);
        border-color: #0284c7;
    }

    /* Search Box */
    .search-box {
        position: relative;
        margin-bottom: 1rem;
    }
    .search-box .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.875rem;
        pointer-events: none;
    }
    .search-box input {
        width: 100%;
        padding: 0.625rem 1rem 0.625rem 2.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.8125rem;
        color: #334155;
        background: #fff;
        transition: all 0.2s;
    }
    .search-box input:focus {
        outline: none;
        border-color: #0ea5e9;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
    }
    .search-box input::placeholder {
        color: #94a3b8;
    }
    .search-no-result {
        display: none;
        text-align: center;
        padding: 2rem;
        color: #94a3b8;
        font-size: 0.875rem;
    }
</style>

<div class="container-fluid px-0 py-2">

    {{-- BACK BUTTON --}}
    <div class="mb-3">
        <a href="{{ route('dashboard-guru.nilai-siswa.show', $ujian->id) }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Peserta
        </a>
    </div>

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div class="header-icon">
            <i class="fa-solid fa-highlighter"></i>
        </div>
        <div>
            <h1 class="page-title">
                {{ $siswa->nama }}
            </h1>
            <p class="page-description">
                <i class="fa-solid fa-file-signature text-muted me-1"></i> Ujian: {{ $ujian->nama_ujian }} &bull; 
                <i class="fa-regular fa-id-badge text-muted me-1"></i> NIS: {{ $siswa->nis ?? '-' }}
            </p>
        </div>
    </div>
    
    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-3 mb-4">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
        </div>
    @endif

    {{-- HASIL RINGKAS --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="info-card">
                <div class="label">Skor Pilihan Ganda (Otomatis)</div>
                <div class="number text-success">{{ $skor_pg }} <span style="font-size: 0.875rem; font-weight: normal; color: #64748b;">/ ({{ $benar_pg }}/{{ $total_soal_pg }} Benar)</span></div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-7">
            <div class="info-card" style="border-color: #bae6fd; background: #f0f9ff;">
                <div class="label text-primary">Nilai Akhir Keseluruhan (Sementara)</div>
                <div class="number text-primary">{{ number_format($nilai->nilai_akhir, 2) }} <span style="font-size: 0.875rem; font-weight: normal; color: #0284c7;">/ 100</span></div>
            </div>
        </div>
    </div>

    {{-- TAB NAVIGATION --}}
    <div class="row mb-4">
        <div class="col-12 col-lg-10 mx-auto">
            <ul class="nav nav-pills gap-2 justify-content-center" id="koreksiTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="essay-tab" data-bs-toggle="pill" data-bs-target="#essay" type="button" role="tab" aria-controls="essay" aria-selected="true">
                        <i class="fa-solid fa-pen-ruler me-2"></i>Koreksi Uraian ({{ $jawabans->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pg-tab" data-bs-toggle="pill" data-bs-target="#pg" type="button" role="tab" aria-controls="pg" aria-selected="false">
                        <i class="fa-solid fa-list-check me-2"></i>Pilihan Ganda ({{ $jawabans_pg->count() }})
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="koreksiTabContent">
        
        {{-- TAB ESSAY --}}
        <div class="tab-pane fade show active" id="essay" role="tabpanel" aria-labelledby="essay-tab">
            @if($jawabans->isEmpty())
                <div class="card border-0 shadow-sm rounded-4 text-center py-5 col-lg-10 mx-auto">
                    <div class="card-body">
                        <i class="fa-solid fa-clipboard-check fa-3x text-muted opacity-25 mb-3 d-block"></i>
                        <h5 class="fw-bold text-dark mb-1">Tidak Ada Uraian</h5>
                        <p class="text-muted mb-0" style="font-size: 0.875rem;">Ujian ini tidak memiliki soal essay atau siswa tidak menjawabnya.</p>
                    </div>
                </div>
            @else
                <form method="POST" action="{{ route('dashboard-guru.nilai-siswa.store-koreksi', ['ujian' => $ujian->id, 'siswa' => $siswa->id]) }}">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-lg-10 mx-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-pen-ruler text-warning me-2"></i>Koreksi Manual Soal Uraian</h5>
                                <span class="text-muted" style="font-size: 12px;"><i class="fa-solid fa-info-circle me-1"></i> Klik soal untuk mengoreksi</span>
                            </div>

                            <div class="search-box">
                                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                                <input type="search" id="searchEssay" placeholder="Cari nomor soal atau kata kunci...">
                            </div>
                            <div class="search-no-result" id="noResultEssay">
                                <i class="fa-solid fa-search fa-2x mb-2 d-block opacity-25"></i>
                                Tidak ada soal yang cocok dengan pencarian.
                            </div>
                            
                            <div class="accordion" id="accordionEssay">
                                @foreach($jawabans as $j)
                                <div class="accordion-item border rounded-3 overflow-hidden shadow-sm mb-3" style="border-color: #e2e8f0 !important;">
                                    <h2 class="accordion-header" id="headingEssay{{ $j->jawaban_id }}">
                                        <button class="accordion-button collapsed bg-light fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEssay{{ $j->jawaban_id }}" aria-expanded="false" aria-controls="collapseEssay{{ $j->jawaban_id }}">
                                            <div class="d-flex align-items-center w-100 me-3">
                                                <span class="badge-no me-3">{{ $loop->iteration }}</span>
                                                <span>Soal {{ ucfirst($j->jenis_soal) }}</span>
                                                <div class="ms-auto d-flex align-items-center gap-3">
                                                    @if(isset($j->nilai_jawaban))
                                                        <span class="badge bg-success-subtle text-success border border-success border-opacity-25" style="font-size: 11px;"><i class="fa-solid fa-check me-1"></i>Sudah Dinilai</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25" style="font-size: 11px;"><i class="fa-solid fa-exclamation-circle me-1"></i>Belum Dinilai</span>
                                                    @endif
                                                    <span class="badge-bobot">Bobot: {{ $j->bobot }}</span>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapseEssay{{ $j->jawaban_id }}" class="accordion-collapse collapse" aria-labelledby="headingEssay{{ $j->jawaban_id }}">
                                        <div class="accordion-body bg-white p-4">
                                            {{-- Teks Soal & Gambar --}}
                                            <div class="soal-text">
                                                {!! $j->teks_soal !!}
                                            </div>
                                            @if($j->gambar)
                                                <img src="{{ asset('storage/'.$j->gambar) }}" class="soal-img" alt="Gambar Soal">
                                            @endif
                                            
                                            {{-- Jawaban Siswa --}}
                                            <div class="answer-box">
                                                <div class="answer-label"><i class="fa-solid fa-pen-nib me-1"></i> Jawaban Siswa:</div>
                                                <div class="answer-text">{{ $j->jawaban_text ?? '(Siswa tidak memberikan jawaban)' }}</div>
                                            </div>
                                            
                                            {{-- Form Penilaian --}}
                                            <div class="grading-box">
                                                <div>
                                                    <label class="grading-title">Berikan Nilai (0 - {{ $j->bobot }})</label>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input type="number" name="koreksi[{{ $j->jawaban_id }}][nilai]" 
                                                               class="score-input" 
                                                               value="{{ $j->nilai_jawaban ?? 0 }}" 
                                                               min="0" max="{{ $j->bobot }}" step="0.1" required>
                                                        <span class="text-muted fw-semibold">/ {{ $j->bobot }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="ms-md-auto">
                                                    <label class="grading-title">Status Keabsahan</label>
                                                    <div class="status-radio-group">
                                                        <label>
                                                            <input type="radio" name="koreksi[{{ $j->jawaban_id }}][is_benar]" value="1" class="custom-radio" {{ $j->is_benar === 1 ? 'checked' : '' }} required>
                                                            <span class="custom-radio-label label-benar"><i class="fa-solid fa-check"></i> Benar</span>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="koreksi[{{ $j->jawaban_id }}][is_benar]" value="0" class="custom-radio" {{ $j->is_benar === 0 ? 'checked' : '' }} required>
                                                            <span class="custom-radio-label label-salah"><i class="fa-solid fa-xmark"></i> Salah</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="sticky-action-bar col-12 col-lg-10 mx-auto mt-4">
                        <div class="d-flex flex-column">
                            <span class="fw-bold text-dark mb-1">Selesai Mengoreksi?</span>
                            <span class="text-muted" style="font-size: 12px;">Pastikan semua nilai dan status jawaban sudah diisi.</span>
                        </div>
                        <button type="submit" class="btn-save">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Nilai Akhir
                        </button>
                    </div>
                </form>
            @endif
        </div>

        {{-- TAB PG --}}
        <div class="tab-pane fade" id="pg" role="tabpanel" aria-labelledby="pg-tab">
            @if($jawabans_pg->isEmpty())
                <div class="card border-0 shadow-sm rounded-4 text-center py-5 col-lg-10 mx-auto">
                    <div class="card-body">
                        <i class="fa-solid fa-clipboard-check fa-3x text-muted opacity-25 mb-3 d-block"></i>
                        <h5 class="fw-bold text-dark mb-1">Tidak Ada Pilihan Ganda</h5>
                        <p class="text-muted mb-0" style="font-size: 0.875rem;">Ujian ini tidak memiliki soal pilihan ganda.</p>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12 col-lg-10 mx-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-list-check text-primary me-2"></i>Rincian Pilihan Ganda</h5>
                            <span class="badge bg-success fw-normal" style="font-size: 12px;">Skor PG: {{ $skor_pg }} Poin</span>
                        </div>

                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass search-icon"></i>
                            <input type="search" id="searchPG" placeholder="Cari nomor soal atau kata kunci...">
                        </div>
                        <div class="search-no-result" id="noResultPG">
                            <i class="fa-solid fa-search fa-2x mb-2 d-block opacity-25"></i>
                            Tidak ada soal yang cocok dengan pencarian.
                        </div>
                        
                        <div class="accordion" id="accordionPG">
                            @foreach($jawabans_pg as $pg)
                            <div class="accordion-item border rounded-3 overflow-hidden shadow-sm mb-3" style="border-color: #e2e8f0 !important;">
                                <h2 class="accordion-header" id="headingPG{{ $pg->soal_id }}">
                                    <button class="accordion-button collapsed bg-light fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePG{{ $pg->soal_id }}" aria-expanded="false" aria-controls="collapsePG{{ $pg->soal_id }}">
                                        <div class="d-flex align-items-center w-100 me-3">
                                            <span class="badge-no me-3">{{ $pg->urutan }}</span>
                                            <span>Soal Pilihan Ganda</span>
                                            <div class="ms-auto d-flex align-items-center gap-3">
                                                @if($pg->is_benar === 1)
                                                    <span class="badge bg-success-subtle text-success border border-success border-opacity-25" style="font-size: 11px;">
                                                        <i class="fa-solid fa-check me-1"></i> Benar (+{{ $pg->nilai_jawaban }})
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25" style="font-size: 11px;">
                                                        <i class="fa-solid fa-xmark me-1"></i> Salah (0 Poin)
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapsePG{{ $pg->soal_id }}" class="accordion-collapse collapse" aria-labelledby="headingPG{{ $pg->soal_id }}">
                                    <div class="accordion-body bg-white p-4">
                                        <div class="fw-medium text-dark mb-2">
                                            {!! $pg->teks_soal !!}
                                            @if($pg->gambar)
                                                <div class="mt-2"><img src="{{ asset('storage/'.$pg->gambar) }}" class="soal-img" alt="Gambar Soal"></div>
                                            @endif
                                        </div>
                                        
                                        <div class="row g-2 mt-3">
                                            @if(isset($opsi_pg[$pg->soal_id]))
                                                @foreach($opsi_pg[$pg->soal_id] as $opsi)
                                                    @php
                                                        $isSiswaJawaban = ($pg->pilihan_jawaban_id == $opsi->id);
                                                        $isKunciBenar = ($opsi->is_benar == 1);
                                                        
                                                        $class = 'pg-option-item';
                                                        $icon = '';
                                                        $badge = '';
                                                        
                                                        if ($isKunciBenar && $isSiswaJawaban) {
                                                            $class .= ' correct-key';
                                                            $icon = '<i class="fa-solid fa-circle-check me-1"></i>';
                                                            $badge = '<span class="badge bg-success text-white ms-2">Jawaban Siswa & Kunci Benar</span>';
                                                        } elseif ($isKunciBenar && !$isSiswaJawaban) {
                                                            $class .= ' correct-key';
                                                            $icon = '<i class="fa-solid fa-circle-check me-1"></i>';
                                                            $badge = '<span class="badge bg-success text-white ms-2">Kunci Jawaban Benar</span>';
                                                        } elseif (!$isKunciBenar && $isSiswaJawaban) {
                                                            $class .= ' student-wrong';
                                                            $icon = '<i class="fa-solid fa-circle-xmark me-1"></i>';
                                                            $badge = '<span class="badge bg-danger text-white ms-2">Jawaban Siswa (Salah)</span>';
                                                        }
                                                    @endphp
                                                    <div class="col-md-6">
                                                        <div class="{!! $class !!}">
                                                            {!! $icon !!} {!! $opsi->teks_pilihan !!} {!! $badge !!}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupSearch(inputId, accordionId, noResultId) {
        const input = document.getElementById(inputId);
        const noResult = document.getElementById(noResultId);
        if (!input) return;

        input.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const accordion = document.getElementById(accordionId);
            if (!accordion) return;

            const items = accordion.querySelectorAll('.accordion-item');
            let visibleCount = 0;

            items.forEach(function(item) {
                const text = item.textContent.toLowerCase();
                if (query === '' || text.includes(query)) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (noResult) {
                noResult.style.display = (visibleCount === 0 && query !== '') ? 'block' : 'none';
            }
        });
    }

    setupSearch('searchEssay', 'accordionEssay', 'noResultEssay');
    setupSearch('searchPG', 'accordionPG', 'noResultPG');
});
</script>
@endsection
