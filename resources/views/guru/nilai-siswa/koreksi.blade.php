@extends('layouts.app')

@section('title', 'Koreksi Jawaban - Guru Mapel')

@section('content')
<style>
    .page-header-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: none;
        border-radius: 1.25rem;
        overflow: hidden;
        position: relative;
    }
    .page-header-card::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 280px; height: 280px;
        background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
        transition: background 0.2s;
    }
    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }
    
    .question-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
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
        background: #6366f1;
        color: #fff;
        width: 28px; height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }
    
    .badge-bobot {
        background: #e2e8f0;
        color: #475569;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .question-body {
        padding: 1.5rem;
    }
    
    .soal-text {
        font-size: 1rem;
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
        background: #f1f5f9;
        border-left: 4px solid #6366f1;
        padding: 1rem 1.25rem;
        border-radius: 0 0.5rem 0.5rem 0;
        margin-bottom: 1.5rem;
    }
    
    .answer-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6366f1;
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
        border-radius: 0.75rem;
        padding: 1.25rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        align-items: center;
    }
    
    .grading-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .score-input {
        width: 100px;
        text-align: center;
        font-weight: 700;
        color: #0f172a;
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.5rem;
        transition: border-color 0.2s;
    }
    .score-input:focus {
        outline: none;
        border-color: #6366f1;
    }
    
    .status-radio-group {
        display: flex;
        gap: 1rem;
    }
    
    .custom-radio {
        display: none;
    }
    .custom-radio-label {
        padding: 0.5rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .custom-radio:checked + .label-benar {
        border-color: #16a34a;
        background: #f0fdf4;
        color: #16a34a;
    }
    .custom-radio:checked + .label-salah {
        border-color: #dc2626;
        background: #fef2f2;
        color: #dc2626;
    }
    
    .btn-save {
        background: #16a34a;
        color: #fff;
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        font-weight: 600;
        border: none;
        transition: background 0.2s;
        box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.2);
    }
    .btn-save:hover {
        background: #15803d;
    }
    
    .sticky-action-bar {
        position: sticky;
        bottom: 1.5rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }

    /* Styles for PG Accordion matching the general theme */
    .pg-option-item {
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 13px;
        margin-bottom: 6px;
        border: 1px solid #e2e8f0;
        background-color: #fff;
    }
    .pg-option-item.correct-key {
        background-color: #ecfdf5;
        border-color: #6ee7b7;
        color: #065f46;
        font-weight: 600;
    }
    .pg-option-item.student-wrong {
        background-color: #fef2f2;
        border-color: #fca5a5;
        color: #991b1b;
        font-weight: 600;
    }
    .accordion-button:not(.collapsed) {
        color: #0f172a;
        background-color: #f8fafc;
        box-shadow: inset 0 -1px 0 rgba(0,0,0,.125);
    }
</style>

<div class="container-fluid px-0 py-2">

    {{-- PAGE HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-card p-4 p-md-5">
                <div class="mb-4">
                    <a href="{{ route('dashboard-guru.nilai-siswa.show', $ujian->id) }}" class="btn-back">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Peserta
                    </a>
                </div>
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                    <div class="flex-grow-1">
                        <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill mb-3 d-inline-flex align-items-center gap-1"
                              style="font-size: 11px; font-weight: 600;">
                            <i class="fa-solid fa-highlighter me-1"></i>
                            Koreksi Jawaban
                        </span>
                        <h1 class="fw-bold text-white mb-1" style="font-size: 1.75rem; letter-spacing: -0.5px;">
                            {{ $siswa->nama }}
                        </h1>
                        <p class="text-white text-opacity-60 mb-0" style="font-size: 13px;">
                            Ujian: {{ $ujian->nama_ujian }} &bull; NIS: {{ $siswa->nis ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
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
            <div class="bg-white border rounded-3 p-3 text-center d-flex flex-column justify-content-center h-100" style="border-color: #e2e8f0 !important;">
                <div class="text-muted mb-1" style="font-size: 12px;">Skor Pilihan Ganda (Otomatis)</div>
                <div class="fw-bold text-success fs-3">{{ $skor_pg }} <span class="fs-6 fw-normal text-muted">/ ({{ $benar_pg }}/{{ $total_soal_pg }} Benar)</span></div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-7">
            <div class="bg-white border rounded-3 p-3 text-center d-flex flex-column justify-content-center h-100" style="border-color: #e2e8f0 !important;">
                <div class="text-muted mb-1" style="font-size: 12px;">Nilai Akhir Keseluruhan (Sementara)</div>
                <div class="fw-bold text-primary fs-2">{{ number_format($nilai->nilai_akhir, 2) }} <span class="fs-6 text-muted fw-normal">/ 100</span></div>
            </div>
        </div>
    </div>

    {{-- ACCORDION PILIHAN GANDA --}}
    @if($jawabans_pg->isNotEmpty())
    <div class="row mb-4">
        <div class="col-12 col-lg-10 mx-auto">
            <div class="accordion" id="accordionPG">
                <div class="accordion-item border rounded-3 overflow-hidden shadow-sm" style="border-color: #e2e8f0 !important;">
                    <h2 class="accordion-header" id="headingPG">
                        <button class="accordion-button bg-light fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePG" aria-expanded="true" aria-controls="collapsePG">
                            <i class="fa-solid fa-list-check text-primary me-2"></i> Rincian Pilihan Ganda (Auto-Scored) &mdash; {{ $total_soal_pg }} Soal
                            <span class="badge bg-success ms-3 fw-normal" style="font-size: 11px;">Skor PG: {{ $skor_pg }} Poin</span>
                        </button>
                    </h2>
                    <div id="collapsePG" class="accordion-collapse collapse show" aria-labelledby="headingPG" data-bs-parent="#accordionPG">
                        <div class="accordion-body bg-white p-4">
                            
                            @foreach($jawabans_pg as $pg)
                            <div class="p-3 mb-3 border rounded-3 bg-light bg-opacity-50" style="border-color: #e2e8f0 !important;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-primary">Soal No. {{ $pg->urutan }} (Pilihan Ganda)</span>
                                    @if($pg->is_benar === 1)
                                        <span class="badge bg-success-subtle text-success border border-success border-opacity-25 fw-bold">
                                            <i class="fa-solid fa-check me-1"></i> Benar (+{{ $pg->nilai_jawaban }} Poin)
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 fw-bold">
                                            <i class="fa-solid fa-xmark me-1"></i> Salah (0 Poin)
                                        </span>
                                    @endif
                                </div>
                                <div class="fw-medium text-dark mb-2">
                                    {!! $pg->teks_soal !!}
                                    @if($pg->gambar)
                                        <div class="mt-2"><img src="{{ asset('storage/'.$pg->gambar) }}" class="soal-img" alt="Gambar Soal"></div>
                                    @endif
                                </div>
                                
                                <div class="row g-2 mt-1">
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
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- KOREKSI MANUAL ESSAY --}}
    @if($jawabans->isEmpty())
        @if($jawabans_pg->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <i class="fa-solid fa-clipboard-check fa-3x text-muted opacity-25 mb-3 d-block"></i>
                <h5 class="fw-bold text-dark mb-1">Data Kosong</h5>
                <p class="text-muted mb-0" style="font-size: 0.875rem;">
                    Tidak ada jawaban yang direkam untuk siswa ini.
                </p>
            </div>
        </div>
        @else
        <div class="alert alert-info border-0 rounded-3 text-center py-4 col-lg-10 mx-auto">
            <i class="fa-solid fa-info-circle fa-2x mb-2"></i>
            <h6 class="fw-bold">Hanya Soal Pilihan Ganda</h6>
            <p class="mb-0 small">Ujian ini tidak memiliki soal essay. Semua nilai sudah dikalkulasi secara otomatis oleh sistem.</p>
        </div>
        @endif
    @else
        <form method="POST" action="{{ route('dashboard-guru.nilai-siswa.store-koreksi', ['ujian' => $ujian->id, 'siswa' => $siswa->id]) }}">
            @csrf
            
            <div class="row">
                <div class="col-12 col-lg-10 mx-auto">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-pen-ruler text-warning me-2"></i>Koreksi Manual Soal Uraian</h5>
                    @foreach($jawabans as $j)
                    <div class="question-card">
                        <div class="question-header">
                            <div class="question-no">
                                <span class="badge-no">{{ $loop->iteration }}</span>
                                Soal {{ ucfirst($j->jenis_soal) }}
                            </div>
                            <div class="badge-bobot">
                                Bobot Maks: {{ $j->bobot }}
                            </div>
                        </div>
                        
                        <div class="question-body">
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
                    @endforeach
                </div>
            </div>
            
            <div class="sticky-action-bar col-12 col-lg-10 mx-auto">
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
@endsection
