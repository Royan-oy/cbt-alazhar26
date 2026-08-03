@extends('layouts.app')

@section('title', 'Koreksi Jawaban - Guru Mapel')

@section('content')
<style>
    #koreksi-wrapper {
        --paper: #f8fafc;
        --paper-card: #ffffff;
        --ink: #0f172a;
        --ink-soft: #64748b;
        --ink-faint: #94a3b8;
        --rule: #e2e8f0;
        --rule-strong: #cbd5e1;
        --red-pen: #F8285A;
        --red-pen-dark: #dc2626;
        --red-pen-soft: #fef2f2;
        --green-ink: #17C653;
        --green-soft: #f0fdf4;
        --gold: #F6C000;
        --gold-soft: #fffbeb;
        --accent-blue: #1B84FF;
        --font-sans: inherit;

        font-family: var(--font-sans);
        color: var(--ink);
        background: var(--paper);
        margin: -1.5rem -1.5rem 0 -1.5rem;
        padding: 2rem 1.5rem 3rem;
    }
    #koreksi-wrapper * { box-sizing: border-box; }
    #koreksi-wrapper .inner { max-width: 1000px; margin: 0 auto; }

    /* ---------- Back link ---------- */
    .kx-back {
        display: inline-flex; align-items: center; gap: 0.5rem;
        color: var(--ink-soft); text-decoration: none;
        font-size: 0.875rem; font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 2px;
        transition: color 0.15s;
    }
    .kx-back:hover { color: var(--accent-blue); }

    /* ---------- Header: exam slip ---------- */
    .kx-header {
        background: var(--paper-card);
        border: 1px solid var(--rule);
        border-radius: 12px;
        padding: 1.75rem 2rem;
        margin-bottom: 1.5rem;
        position: relative;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    .kx-header::before {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0; width: 6px;
        background: var(--accent-blue);
        border-radius: 12px 0 0 12px;
    }
    .kx-header-top { display: flex; align-items: center; gap: 1.25rem; }
    .kx-stamp-icon {
        width: 54px; height: 54px; border-radius: 50%;
        border: 2px solid var(--accent-blue);
        color: var(--accent-blue);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.35rem; flex-shrink: 0;
        transform: rotate(-6deg);
    }
    .kx-eyebrow {
        font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em;
        text-transform: uppercase; color: var(--ink-soft);
        margin-bottom: 0.15rem;
    }
    .kx-student-name {
        font-size: 1.75rem; font-weight: 700; color: var(--ink);
        margin: 0;
    }
    .kx-header-meta {
        margin-top: 1rem; padding-top: 1rem;
        border-top: 1px dashed var(--rule);
        font-size: 0.875rem; color: var(--ink-soft);
        display: flex; flex-wrap: wrap; gap: 0.5rem 0.5rem;
    }
    .kx-header-meta .dot { color: var(--ink-faint); }
    .kx-header-meta strong { color: var(--ink); font-weight: 600; }

    /* ---------- Ledger (scoreboard) ---------- */
    .kx-ledger {
        background: var(--paper-card);
        border: 1px solid var(--rule);
        border-radius: 12px;
        margin-bottom: 1.75rem;
        display: flex;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    .kx-ledger-item { flex: 1; padding: 1.25rem 1.5rem; text-align: center; }
    .kx-ledger-item + .kx-ledger-item { border-left: 1px dashed var(--rule); }
    .kx-ledger-label {
        font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em;
        text-transform: uppercase; color: var(--ink-soft); margin-bottom: 0.4rem;
    }
    .kx-ledger-value {
        font-size: 1.65rem; font-weight: 700; color: var(--ink);
    }
    .kx-ledger-sub { font-size: 0.85rem; font-weight: 600; color: var(--ink-soft); }
    .kx-ledger-item.is-highlight { background: var(--green-soft); }
    .kx-ledger-item.is-highlight .kx-ledger-value { color: var(--green-ink); }
    .kx-ledger-item.is-alert { background: var(--red-pen-soft); }
    .kx-ledger-item.is-alert .kx-ledger-value,
    .kx-ledger-item.is-alert .kx-ledger-label { color: var(--red-pen); }

    /* ---------- Folder tabs ---------- */
    .kx-tabs { display: flex; gap: 0.5rem; margin-bottom: 0; padding: 0 0.25rem; border: 0; }
    .kx-tab {
        font-weight: 700; font-size: 0.875rem;
        color: var(--ink-soft); background: #e2e8f0;
        border: 1px solid var(--rule); border-bottom: none;
        border-radius: 10px 10px 0 0;
        padding: 0.75rem 1.35rem 0.65rem;
        display: inline-flex; align-items: center; gap: 0.5rem;
        cursor: pointer; position: relative; top: 1px;
        transition: all 0.2s;
    }
    .kx-tab .count {
        background: #cbd5e1; color: var(--ink);
        font-size: 0.75rem; font-weight: 700; padding: 0.1rem 0.5rem; border-radius: 999px;
    }
    .kx-tab.active {
        background: var(--paper-card); color: var(--accent-blue);
        border-color: var(--rule-strong); border-bottom: 1px solid var(--paper-card);
        box-shadow: 0 -3px 0 var(--accent-blue) inset;
    }
    .kx-tab.active .count { background: #e0f2fe; color: var(--accent-blue); }
    .kx-tab-panel-body {
        background: var(--paper-card);
        border: 1px solid var(--rule-strong);
        border-radius: 0 12px 12px 12px;
        padding: 1.75rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    /* ---------- Search ---------- */
    .kx-search { position: relative; margin-bottom: 1.25rem; }
    .kx-search i {
        position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
        color: var(--ink-soft); font-size: 0.875rem; pointer-events: none;
    }
    .kx-search input {
        width: 100%; padding: 0.7rem 1rem 0.7rem 2.5rem;
        border: 1px solid var(--rule); border-radius: 10px;
        font-size: 0.875rem; color: var(--ink);
        background: var(--paper);
    }
    .kx-search input:focus { outline: none; border-color: var(--accent-blue); box-shadow: 0 0 0 3px rgba(27, 132, 255, 0.15); }
    .kx-search-no-result {
        display: none; text-align: center; padding: 2.5rem 1rem; color: var(--ink-soft); font-size: 0.875rem;
    }

    /* ---------- Question cards ---------- */
    .kx-list-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
    .kx-list-title {
        font-size: 1.2rem; font-weight: 700; color: var(--ink); margin: 0;
    }
    .kx-list-hint { font-size: 0.8rem; color: var(--ink-soft); font-weight: 500; }

    .kx-card {
        border: 1px solid var(--rule); border-radius: 10px;
        margin-bottom: 1rem; overflow: hidden; background: var(--paper-card);
    }
    .kx-card-head {
        display: flex; align-items: center; gap: 1rem;
        padding: 1rem 1.35rem; cursor: pointer; background: #ffffff;
        border: none; width: 100%; text-align: left;
        transition: background 0.15s;
    }
    .kx-card-head[aria-expanded="true"] { background: #f8fafc; }
    .kx-num {
        width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
        background: var(--ink); color: #ffffff;
        font-weight: 700; font-size: 0.875rem;
        display: flex; align-items: center; justify-content: center;
    }
    .kx-card-label { font-weight: 700; font-size: 0.95rem; color: var(--ink); }
    .kx-card-badges { margin-left: auto; display: flex; align-items: center; gap: 0.6rem; }
    .kx-card-chevron { color: var(--ink-soft); transition: transform 0.2s; }
    .kx-card-head[aria-expanded="true"] .kx-card-chevron { transform: rotate(180deg); }

    .kx-weight {
        font-size: 0.75rem; font-weight: 700; color: var(--ink-soft);
        background: #f1f5f9; border: 1px solid var(--rule);
        padding: 0.25rem 0.65rem; border-radius: 6px; white-space: nowrap;
    }

    /* Solid stamp badges */
    .kx-stamp {
        display: inline-flex; align-items: center; gap: 0.35rem;
        font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em;
        padding: 0.25rem 0.65rem; border-radius: 6px; white-space: nowrap; color: #ffffff;
    }
    .kx-stamp.ok { background: var(--green-ink); }
    .kx-stamp.no { background: var(--red-pen); }
    .kx-stamp.pending { background: var(--gold); }

    .kx-card-body { padding: 1.5rem 1.75rem; border-top: 1px solid var(--rule); }
    .kx-soal-text {
        font-size: 1rem; line-height: 1.6; color: var(--ink); font-weight: 500;
        margin-bottom: 1.25rem;
    }
    .kx-soal-img { max-width: 100%; border-radius: 8px; border: 1px solid var(--rule); margin-bottom: 1.25rem; }

    .kx-answer {
        background: var(--paper); border-left: 4px solid var(--accent-blue);
        border-radius: 0 8px 8px 0; padding: 1rem 1.25rem; margin-bottom: 1.5rem;
    }
    .kx-answer-label {
        font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
        color: var(--accent-blue); margin-bottom: 0.4rem;
    }
    .kx-answer-text { font-size: 0.95rem; color: var(--ink); white-space: pre-wrap; font-weight: 500; }

    /* PG options */
    .kx-opt {
        padding: 0.75rem 1rem; border: 1px solid var(--rule); border-radius: 8px;
        font-size: 0.875rem; margin-bottom: 0.5rem; background: var(--paper-card);
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; font-weight: 500;
    }
    .kx-opt.correct-key { background: var(--green-soft); border-color: var(--green-ink); color: var(--green-ink); font-weight: 700; }
    .kx-opt.student-wrong { background: var(--red-pen-soft); border-color: var(--red-pen); color: var(--red-pen); font-weight: 700; }
    .kx-opt-tag {
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase; padding: 0.15rem 0.5rem;
        border-radius: 4px; background: rgba(0,0,0,0.06); margin-left: auto;
    }

    /* Grading strip */
    .kx-grading {
        border-top: 1px dashed var(--rule); margin-top: 1.5rem; padding-top: 1.25rem;
        display: flex; flex-wrap: wrap; align-items: center; gap: 1.75rem;
    }
    .kx-grading-label {
        font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
        color: var(--ink-soft); display: block; margin-bottom: 0.5rem;
    }
    .kx-score-circle {
        width: 88px; text-align: center;
        font-size: 1.15rem; font-weight: 700; color: var(--ink);
        border: 2px solid var(--accent-blue); border-radius: 8px;
        padding: 0.4rem 0.5rem; background: var(--paper-card);
    }
    .kx-score-circle:focus { outline: none; box-shadow: 0 0 0 3px rgba(27, 132, 255, 0.15); }
    .kx-score-wrap { display: flex; align-items: center; gap: 0.6rem; }
    .kx-score-max { font-size: 0.875rem; color: var(--ink-soft); font-weight: 600; }

    .kx-status-group { display: flex; gap: 0.6rem; }
    .kx-radio { display: none; }
    .kx-radio-label {
        padding: 0.45rem 1.1rem; border: 1.5px solid var(--rule); border-radius: 8px;
        cursor: pointer; font-size: 0.85rem; font-weight: 700; color: var(--ink-soft);
        display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.2s;
    }
    .kx-radio:checked + .kx-radio-label.benar { border-color: var(--green-ink); background: var(--green-ink); color: #ffffff; }
    .kx-radio:checked + .kx-radio-label.salah { border-color: var(--red-pen); background: var(--red-pen); color: #ffffff; }

    .kx-grading-actions { margin-left: auto; display: flex; align-items: center; gap: 0.9rem; }
    .kx-saved-mark {
        color: var(--green-ink); font-size: 0.85rem; font-weight: 700; display: none; align-items: center; gap: 0.3rem;
    }
    .kx-saved-mark.show { display: inline-flex; }
    .kx-btn-save {
        font-weight: 700; font-size: 0.875rem;
        background: var(--accent-blue); color: #fff; border: none;
        padding: 0.6rem 1.35rem; border-radius: 99px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 0.45rem;
        transition: all 0.2s; box-shadow: 0 2px 4px rgba(27, 132, 255, 0.15);
    }
    .kx-btn-save:hover { background: #156ce6; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(27, 132, 255, 0.25); }
    .kx-btn-save:disabled { opacity: 0.65; cursor: default; }

    /* Empty state */
    .kx-empty { text-align: center; padding: 3.5rem 1rem; color: var(--ink-soft); }
    .kx-empty i { font-size: 2.5rem; opacity: 0.35; margin-bottom: 0.75rem; display: block; }
    .kx-empty h5 { color: var(--ink); font-weight: 700; margin-bottom: 0.25rem; }
    .kx-empty p { font-size: 0.875rem; margin: 0; }

    /* Sticky footer */
    .kx-sticky {
        position: sticky; bottom: 1.25rem;
        background: #ffffff;
        border: 1px solid var(--rule); border-radius: 12px;
        padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    }
    .kx-sticky-text strong { font-size: 1rem; color: var(--ink); display: block; font-weight: 700; }
    .kx-sticky-text span { font-size: 0.8rem; color: var(--ink-soft); }
    .kx-sticky a {
        font-weight: 700; font-size: 0.875rem; color: #fff; background: var(--ink);
        padding: 0.65rem 1.35rem; border-radius: 99px; text-decoration: none; white-space: nowrap;
        transition: background 0.2s;
    }
    .kx-sticky a:hover { background: var(--accent-blue); }

    @media (max-width: 640px) {
        .kx-ledger { flex-direction: column; }
        .kx-ledger-item + .kx-ledger-item { border-left: none; border-top: 1px dashed var(--rule); }
        .kx-grading-actions { margin-left: 0; width: 100%; justify-content: space-between; }
        .kx-sticky { flex-direction: column; align-items: flex-start; }
    }
</style>

<div id="koreksi-wrapper">
<div class="inner">

    {{-- BACK LINK & PDF BUTTON --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <a href="{{ route('dashboard-guru.nilai-siswa.show', $ujian->id) }}" class="kx-back mb-0">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Peserta
        </a>
        <a href="{{ route('dashboard-guru.nilai-siswa.koreksi.export-pdf', ['ujian' => $ujian->id, 'siswa' => $siswa->id]) }}" target="_blank" class="btn btn-sm rounded-pill px-3 fw-bold d-inline-flex align-items-center gap-1.5" style="background-color: #ef4444; color: #ffffff; text-decoration: none; font-size: 0.85rem; padding: 0.45rem 1rem;">
            <i class="fa-solid fa-file-pdf me-1"></i> Cetak Transkrip PDF
        </a>
    </div>

    {{-- HEADER: exam slip --}}
    <div class="kx-header">
        <div class="kx-header-top">
            <div class="kx-stamp-icon"><i class="fa-solid fa-highlighter"></i></div>
            <div>
                <div class="kx-eyebrow">Lembar Koreksi Ujian</div>
                <h1 class="kx-student-name">{{ $siswa->nama }}</h1>
            </div>
        </div>
        <div class="kx-header-meta">
            <span><strong>{{ $ujian->nama_ujian }}</strong></span>
            <span class="dot">&bull;</span>
            <span>KKM <strong>{{ $ujian->kkm ?? 75 }}</strong></span>
            <span class="dot">&bull;</span>
            <span>NIS <strong>{{ $siswa->nis ?? '-' }}</strong></span>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-3 mb-4">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
        </div>
    @endif

    {{-- LEDGER --}}
    <div class="kx-ledger">
        <div class="kx-ledger-item">
            <div class="kx-ledger-label"><i class="fa-solid fa-calculator me-1" style="color: var(--accent-blue);"></i>Total Skor Otomatis</div>
            <div class="kx-ledger-value">{{ number_format($skor_pg, 1) }} <span class="kx-ledger-sub">({{ $total_soal_pg }} soal)</span></div>
        </div>
        <div class="kx-ledger-item is-highlight">
            <div class="kx-ledger-label">Nilai Akhir Sementara</div>
            <div class="kx-ledger-value" id="nilai-akhir-display">{{ number_format($nilai_sementara ?? $nilai->nilai_akhir, 2) }} <span class="kx-ledger-sub">/ 100</span></div>
        </div>
        <div class="kx-ledger-item {{ $nilai->violation_count > 0 ? 'is-alert' : '' }}">
            <div class="kx-ledger-label"><i class="fa-solid fa-triangle-exclamation me-1"></i>Pelanggaran Ujian</div>
            <div class="kx-ledger-value mb-1">{{ $nilai->violation_count }} <span class="kx-ledger-sub">kali</span></div>
            <form onsubmit="simpanPotonganPelanggaran(event)" class="d-flex align-items-center justify-content-center gap-1 mt-1">
                <input type="number" min="0" max="100" step="0.5" id="potonganPelanggaranInput" name="potongan_pelanggaran" class="form-control form-control-sm text-center fw-bold" style="max-width: 90px; border-radius: 8px; font-size:12px;" value="{{ old('potongan_pelanggaran', $nilai->potongan_pelanggaran ?? 0) }}" placeholder="Potong Poin">
                <button type="submit" class="btn btn-sm btn-danger fw-bold rounded-3 py-1 px-2" id="btnSavePotongan" title="Simpan Potongan Nilai">
                    <i class="fa-solid fa-save"></i>
                </button>
            </form>
            <div class="small text-danger fw-semibold mt-1" id="potonganNotice" style="font-size: 11px;">
                @if(($nilai->potongan_pelanggaran ?? 0) > 0)
                    Dipotong -{{ $nilai->potongan_pelanggaran }} poin
                @else
                    Belum ada potongan
                @endif
            </div>
        </div>
    </div>

    {{-- FOLDER TABS --}}
    <ul class="kx-tabs" id="koreksiTab" role="tablist" style="list-style:none; margin:0;">
        <li role="presentation" style="display:contents;">
            <button class="kx-tab active" id="essay-tab" data-bs-toggle="pill" data-bs-target="#essay" type="button" role="tab" aria-controls="essay" aria-selected="true">
                <i class="fa-solid fa-pen-ruler"></i> Koreksi Manual &amp; Upah Nulis <span class="count">{{ $jawabans->count() }}</span>
            </button>
        </li>
        <li role="presentation" style="display:contents;">
            <button class="kx-tab" id="pg-tab" data-bs-toggle="pill" data-bs-target="#pg" type="button" role="tab" aria-controls="pg" aria-selected="false">
                <i class="fa-solid fa-list-check"></i> Rincian Koreksi Otomatis <span class="count">{{ $jawabans_pg->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="koreksiTabContent">

        {{-- TAB ESSAY --}}
        <div class="tab-pane fade show active" id="essay" role="tabpanel" aria-labelledby="essay-tab">
            <div class="kx-tab-panel-body">
                @if($jawabans->isEmpty())
                    <div class="kx-empty">
                        <i class="fa-solid fa-clipboard-check"></i>
                        <h5>Tidak Ada Uraian</h5>
                        <p>Ujian ini tidak memiliki soal essay atau siswa tidak menjawabnya.</p>
                    </div>
                @else
                    <div class="kx-list-header">
                        <h2 class="kx-list-title">Koreksi Manual Soal Uraian</h2>
                        <span class="kx-list-hint"><i class="fa-solid fa-info-circle me-1"></i>Klik soal untuk mengoreksi</span>
                    </div>

                    <div class="kx-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" id="searchEssay" placeholder="Cari nomor soal atau kata kunci...">
                    </div>
                    <div class="kx-search-no-result" id="noResultEssay">
                        <i class="fa-solid fa-search fa-2x mb-2 d-block opacity-25"></i>
                        Tidak ada soal yang cocok dengan pencarian.
                    </div>

                    <div id="accordionEssay">
                        @foreach($jawabans as $j)
                        @php
                            $isIsianBenarOtomatis = ($j->jenis_soal === 'isian' && ($j->is_benar == 1 || $j->is_benar === true));
                        @endphp
                        <div class="kx-card accordion-item">
                            <button class="kx-card-head" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEssay{{ $j->jawaban_id }}" aria-expanded="false" aria-controls="collapseEssay{{ $j->jawaban_id }}" id="headingEssay{{ $j->jawaban_id }}">
                                <span class="kx-num">{{ $loop->iteration }}</span>
                                <span class="kx-card-label">Soal {{ ucfirst(str_replace('_', ' ', $j->jenis_soal)) }}</span>
                                <div class="kx-card-badges">
                                    @if($isIsianBenarOtomatis)
                                        <span class="kx-stamp ok status-badge" style="background:#dcfce7; color:#15803d; border:1px solid #86efac;"><i class="fa-solid fa-circle-check"></i>Benar Otomatis ({{ $j->nilai_jawaban }} Poin)</span>
                                    @elseif($j->is_benar === null)
                                        <span class="kx-stamp pending status-badge"><i class="fa-solid fa-clock"></i>Belum Dinilai</span>
                                    @elseif($j->jenis_soal === 'isian')
                                        @if($j->nilai_jawaban > 0)
                                            <span class="kx-stamp ok status-badge" style="background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;"><i class="fa-solid fa-hand-holding-dollar"></i>Upah Nulis ({{ $j->nilai_jawaban }} Poin)</span>
                                        @else
                                            <span class="kx-stamp pending status-badge" style="background:#fef3c7; color:#b45309; border:1px solid #fde68a;"><i class="fa-solid fa-pen-nib"></i>Tinjau Upah Nulis</span>
                                        @endif
                                    @else
                                        <span class="kx-stamp ok status-badge"><i class="fa-solid fa-check"></i>Dinilai ({{ $j->nilai_jawaban }} Poin)</span>
                                    @endif
                                    <span class="kx-weight">Bobot {{ $j->bobot }}</span>
                                </div>
                                <i class="fa-solid fa-chevron-down kx-card-chevron"></i>
                            </button>
                            <div id="collapseEssay{{ $j->jawaban_id }}" class="accordion-collapse collapse" aria-labelledby="headingEssay{{ $j->jawaban_id }}">
                                <div class="kx-card-body">
                                    <div class="kx-soal-text">{!! $j->teks_soal !!}</div>
                                    @if($j->gambar)
                                        <img src="{{ asset('storage/'.$j->gambar) }}" class="kx-soal-img" alt="Gambar Soal">
                                    @endif

                                    <div class="kx-answer mb-3">
                                        <div class="kx-answer-label"><i class="fa-solid fa-pen-nib me-1"></i>Jawaban Siswa</div>
                                        <div class="kx-answer-text fw-bold text-dark">{{ $j->jawaban_text ?? '(Siswa tidak memberikan jawaban)' }}</div>
                                    </div>

                                    @if($isIsianBenarOtomatis)
                                        <div class="alert alert-success border-0 rounded-3 p-3 mb-3 small">
                                            <i class="fa-solid fa-circle-check text-success me-1"></i>
                                            Jawaban siswa cocok dengan Kunci Jawaban Otomatis. Siswa mendapatkan nilai maksimal <strong>{{ $j->bobot }} / {{ $j->bobot }}</strong>. Namun, Anda tetap dapat mengubah nilainya jika diperlukan.
                                        </div>
                                    @endif

                                    @if($j->jenis_soal === 'isian' && !$isIsianBenarOtomatis)
                                        <div class="alert alert-warning border-0 rounded-3 p-2 px-3 mb-3 small">
                                            <i class="fa-solid fa-hand-holding-dollar text-warning me-1"></i>
                                            <strong>Fitur Upah Nulis:</strong> Jawaban siswa tidak 100% cocok dengan kunci otomatis. Jika jawaban siswa hampir benar/ada typo, Anda dapat memberikan nilai parsial <strong>"Upah Nulis"</strong> (0 s/d {{ $j->bobot - 1 }}).
                                        </div>
                                    @endif

                                    <form class="form-koreksi" onsubmit="simpanKoreksi(event, {{ $j->jawaban_id }})">
                                        <div class="kx-grading">
                                            <div>
                                                <label class="kx-grading-label">Nilai (0 - {{ $j->bobot }})</label>
                                                <div class="kx-score-wrap">
                                                    <input type="number" name="koreksi[{{ $j->jawaban_id }}][nilai]"
                                                           class="kx-score-circle"
                                                           value="{{ $j->nilai_jawaban ?? 0 }}"
                                                           min="0" max="{{ $j->bobot }}" step="0.1" required>
                                                    <span class="kx-score-max">/ {{ $j->bobot }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="kx-grading-label">Status Keabsahan</label>
                                                <div class="kx-status-group">
                                                    <input type="radio" id="benar-{{ $j->jawaban_id }}" name="koreksi[{{ $j->jawaban_id }}][is_benar]" value="1" class="kx-radio" {{ $j->is_benar == 1 ? 'checked' : '' }} required>
                                                    <label for="benar-{{ $j->jawaban_id }}" class="kx-radio-label benar"><i class="fa-solid fa-check"></i>Benar</label>
                                                    <input type="radio" id="salah-{{ $j->jawaban_id }}" name="koreksi[{{ $j->jawaban_id }}][is_benar]" value="0" class="kx-radio" {{ ($j->is_benar == 0 && $j->is_benar !== null) ? 'checked' : '' }} required>
                                                    <label for="salah-{{ $j->jawaban_id }}" class="kx-radio-label salah"><i class="fa-solid fa-xmark"></i>Salah</label>
                                                </div>
                                            </div>
                                            <div class="kx-grading-actions">
                                                <span class="kx-saved-mark" id="save-indicator-{{ $j->jawaban_id }}">
                                                    <i class="fa-solid fa-check-circle"></i> Tersimpan
                                                </span>
                                                <button type="submit" class="kx-btn-save" id="btn-simpan-{{ $j->jawaban_id }}">
                                                    <i class="fa-solid fa-save"></i> Simpan Penilaian
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- TAB 2: RINCIAN KOREKSI OTOMATIS (PG, PGK, BENAR/SALAH, MENCOCOKKAN) --}}
        <div class="tab-pane fade" id="pg" role="tabpanel" aria-labelledby="pg-tab">
            <div class="kx-tab-panel-body">
                @if($jawabans_pg->isEmpty())
                    <div class="kx-empty">
                        <i class="fa-solid fa-clipboard-check"></i>
                        <h5>Tidak Ada Soal Koreksi Otomatis</h5>
                        <p>Ujian ini tidak memiliki soal Pilihan Ganda, PG Kompleks, Benar/Salah, atau Mencocokkan.</p>
                    </div>
                @else
                    <div class="kx-list-header">
                        <h2 class="kx-list-title">Rincian Koreksi Otomatis (PG, PGK, B/S, Mencocokkan)</h2>
                        <span class="kx-weight" style="color:var(--green-ink); background:var(--green-soft); border-color:#B9D8C7;">Total Skor Otomatis: {{ $skor_pg }} Poin</span>
                    </div>

                    <div class="kx-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" id="searchPG" placeholder="Cari nomor soal atau kata kunci...">
                    </div>
                    <div class="kx-search-no-result" id="noResultPG">
                        <i class="fa-solid fa-search fa-2x mb-2 d-block opacity-25"></i>
                        Tidak ada soal yang cocok dengan pencarian.
                    </div>

                    <div id="accordionPG">
                        @foreach($jawabans_pg as $pg)
                        <div class="kx-card accordion-item">
                            <button class="kx-card-head" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePG{{ $pg->jawaban_id }}" aria-expanded="false" aria-controls="collapsePG{{ $pg->jawaban_id }}" id="headingPG{{ $pg->jawaban_id }}">
                                <span class="kx-num">{{ $pg->urutan }}</span>
                                <span class="kx-card-label">Soal {{ ucfirst(str_replace('_', ' ', $pg->jenis_soal)) }}</span>
                                <div class="kx-card-badges">
                                    @if($pg->is_benar == 1 && $pg->is_benar !== null)
                                        <span class="kx-stamp ok status-badge"><i class="fa-solid fa-check"></i>Benar (+{{ $pg->nilai_jawaban }})</span>
                                    @elseif($pg->nilai_jawaban > 0)
                                        <span class="kx-stamp ok status-badge" style="background:#e0f2fe; color:#0369a1; border-color:#bae6fd;"><i class="fa-solid fa-star-half-stroke"></i>Parsial (+{{ $pg->nilai_jawaban }})</span>
                                    @elseif($pg->is_benar !== null)
                                        <span class="kx-stamp no status-badge"><i class="fa-solid fa-xmark"></i>Salah (0)</span>
                                    @else
                                        <span class="kx-stamp pending status-badge"><i class="fa-solid fa-clock"></i>Belum</span>
                                    @endif
                                    <span class="kx-weight">Bobot {{ $pg->bobot }}</span>
                                </div>
                                <i class="fa-solid fa-chevron-down kx-card-chevron"></i>
                            </button>
                            <div id="collapsePG{{ $pg->jawaban_id }}" class="accordion-collapse collapse" aria-labelledby="headingPG{{ $pg->jawaban_id }}">
                                <div class="kx-card-body">
                                    <div class="kx-soal-text mb-3">
                                        {!! $pg->teks_soal !!}
                                        @if($pg->gambar)
                                            <img src="{{ asset('storage/'.$pg->gambar) }}" class="kx-soal-img d-block mt-2" alt="Gambar Soal">
                                        @endif
                                    </div>

                                    {{-- 1. PILIHAN GANDA --}}
                                    @if($pg->jenis_soal == 'pilihan_ganda')
                                        <div class="row g-2">
                                            @if(isset($opsi_pg[$pg->soal_id]))
                                                @foreach($opsi_pg[$pg->soal_id] as $opsi)
                                                    @php
                                                        $isSiswaJawaban = ($pg->pilihan_jawaban_id == $opsi->id);
                                                        $isKunciBenar = ($opsi->is_benar == 1);
                                                        $optClass = 'kx-opt';
                                                        $optIcon = '';
                                                        $optTag = '';
                                                        if ($isKunciBenar && $isSiswaJawaban) {
                                                            $optClass .= ' correct-key';
                                                            $optIcon = '<i class="fa-solid fa-circle-check me-1"></i>';
                                                            $optTag = '<span class="kx-opt-tag">Jawaban Siswa &amp; Kunci</span>';
                                                        } elseif ($isKunciBenar && !$isSiswaJawaban) {
                                                            $optClass .= ' correct-key';
                                                            $optIcon = '<i class="fa-solid fa-circle-check me-1"></i>';
                                                            $optTag = '<span class="kx-opt-tag">Kunci Jawaban</span>';
                                                        } elseif (!$isKunciBenar && $isSiswaJawaban) {
                                                            $optClass .= ' student-wrong';
                                                            $optIcon = '<i class="fa-solid fa-circle-xmark me-1"></i>';
                                                            $optTag = '<span class="kx-opt-tag">Jawaban Siswa</span>';
                                                        }
                                                    @endphp
                                                    <div class="col-md-6">
                                                        <div class="{!! $optClass !!}">
                                                            {!! $optIcon !!} {!! $opsi->teks_pilihan !!} {!! $optTag !!}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                    {{-- 2. PILIHAN GANDA KOMPLEKS --}}
                                    @elseif($pg->jenis_soal == 'pilihan_ganda_kompleks')
                                        @php
                                            $siswaChoices = is_array($pg->jawaban_json) ? array_map('intval', $pg->jawaban_json) : [];
                                        @endphp
                                        <div class="row g-2">
                                            @if(isset($opsi_pg[$pg->soal_id]))
                                                @foreach($opsi_pg[$pg->soal_id] as $opsi)
                                                    @php
                                                        $isPilih = in_array((int)$opsi->id, $siswaChoices, true);
                                                        $isKunci = ($opsi->is_benar == 1);
                                                        $optClass = 'kx-opt';
                                                        $optTag = '';
                                                        if ($isKunci && $isPilih) {
                                                            $optClass .= ' correct-key';
                                                            $optTag = '<span class="kx-opt-tag"><i class="fa-solid fa-check me-1"></i>Dipilih &amp; Kunci Benar</span>';
                                                        } elseif ($isKunci && !$isPilih) {
                                                            $optClass .= ' correct-key';
                                                            $optTag = '<span class="kx-opt-tag">Kunci Benar (Tidak Dipilih)</span>';
                                                        } elseif (!$isKunci && $isPilih) {
                                                            $optClass .= ' student-wrong';
                                                            $optTag = '<span class="kx-opt-tag"><i class="fa-solid fa-xmark me-1"></i>Dipilih (Salah)</span>';
                                                        }
                                                    @endphp
                                                    <div class="col-md-6">
                                                        <div class="{!! $optClass !!}">
                                                            {!! $opsi->teks_pilihan !!} {!! $optTag !!}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                    {{-- 3. BENAR / SALAH --}}
                                    @elseif($pg->jenis_soal == 'benar_salah')
                                        @php
                                            $siswaChoicesBS = is_array($pg->jawaban_json) ? $pg->jawaban_json : [];
                                        @endphp
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered align-middle small bg-white mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Pernyataan</th>
                                                        <th class="text-center">Kunci Benar</th>
                                                        <th class="text-center">Jawaban Siswa</th>
                                                        <th class="text-center">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(isset($opsi_pg[$pg->soal_id]))
                                                        @foreach($opsi_pg[$pg->soal_id] as $pj)
                                                            @php
                                                                $keyBS = 'stmt_' . $pj->urutan;
                                                                $ansSiswa = strtolower(trim((string) ($siswaChoicesBS[$keyBS] ?? $siswaChoicesBS[$pj->id] ?? '')));
                                                                $kunciStr = $pj->is_benar ? 'benar' : 'salah';
                                                                $isMatch = ($ansSiswa === $kunciStr);
                                                            @endphp
                                                            <tr>
                                                                <td>{!! $pj->teks_pilihan !!}</td>
                                                                <td class="text-center fw-bold text-success">{{ ucfirst($kunciStr) }}</td>
                                                                <td class="text-center fw-bold {{ $isMatch ? 'text-success' : 'text-danger' }}">{{ $ansSiswa ? ucfirst($ansSiswa) : '(Kosong)' }}</td>
                                                                <td class="text-center">
                                                                    @if($isMatch)
                                                                        <span class="badge bg-success"><i class="fa-solid fa-check"></i></span>
                                                                    @else
                                                                        <span class="badge bg-danger"><i class="fa-solid fa-xmark"></i></span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>

                                    {{-- 4. MENCOCOKKAN --}}
                                    @elseif($pg->jenis_soal == 'mencocokkan')
                                        @php
                                            $siswaChoicesM = is_array($pg->jawaban_json) ? $pg->jawaban_json : [];
                                        @endphp
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered align-middle small bg-white mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Item Kiri (Soal)</th>
                                                        <th>Pasangan Kunci Benar</th>
                                                        <th>Pasangan Jawaban Siswa</th>
                                                        <th class="text-center">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(isset($opsi_pg[$pg->soal_id]))
                                                        @foreach($opsi_pg[$pg->soal_id] as $pj)
                                                            @php
                                                                $itemKiri = $pj->teks_pilihan;
                                                                $pasanganKunci = $pj->pasangan;
                                                                $pasanganSiswa = $siswaChoicesM[$itemKiri] ?? $siswaChoicesM[$pj->id] ?? '';
                                                                $isMatch = (strtolower(trim($pasanganSiswa)) === strtolower(trim($pasanganKunci)));
                                                            @endphp
                                                            <tr>
                                                                <td class="fw-semibold">{!! $itemKiri !!}</td>
                                                                <td class="fw-bold text-success">{{ $pasanganKunci }}</td>
                                                                <td class="fw-bold {{ $isMatch ? 'text-success' : 'text-danger' }}">{{ $pasanganSiswa ? $pasanganSiswa : '(Belum Dijodohkan)' }}</td>
                                                                <td class="text-center">
                                                                    @if($isMatch)
                                                                        <span class="badge bg-success"><i class="fa-solid fa-check"></i></span>
                                                                    @else
                                                                        <span class="badge bg-danger"><i class="fa-solid fa-xmark"></i></span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- STICKY FOOTER --}}
    {{-- <div class="kx-sticky">
        <div class="kx-sticky-text">
            <strong>Selesai mengoreksi?</strong>
            <span>Nilai tersimpan otomatis setiap kali Anda menekan tombol Simpan pada masing-masing soal.</span>
        </div>
        <a href="{{ route('dashboard-guru.nilai-siswa.show', $ujian->id) }}">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Daftar Siswa
        </a>
    </div> --}}

</div>
</div>

<script>
function simpanKoreksi(event, jawabanId) {
    event.preventDefault();

    const form = event.target;
    const btn = document.getElementById('btn-simpan-' + jawabanId);
    const indicator = document.getElementById('save-indicator-' + jawabanId);
    const heading = document.getElementById('headingEssay' + jawabanId);
    const statusBadge = heading ? heading.querySelector('.status-badge') : null;

    const originalBtnText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
    btn.disabled = true;
    indicator.classList.remove('show');

    const formData = new FormData(form);

    fetch("{{ route('dashboard-guru.nilai-siswa.store-koreksi', ['ujian' => $ujian->id, 'siswa' => $siswa->id]) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalBtnText;
        btn.disabled = false;

        if (data.success) {
            indicator.classList.add('show');

            if (data.nilai_akhir !== undefined) {
                const scoreDisplay = document.getElementById('nilai-akhir-display');
                if (scoreDisplay) {
                    scoreDisplay.innerHTML = parseFloat(data.nilai_akhir).toFixed(2) + ' <span class="kx-ledger-sub">/ 100</span>';
                }
            }

            if (statusBadge) {
                const inputNilai = parseFloat(form.querySelector('input[type="number"]').value);
                const isBenarRadio = form.querySelector('input[type="radio"]:checked');
                const isBenarVal = isBenarRadio ? isBenarRadio.value : null;
                const isIsian = heading ? heading.querySelector('.kx-card-label').textContent.includes('Isian') : false;

                // Reset inline styles if any
                statusBadge.style.background = '';
                statusBadge.style.color = '';
                statusBadge.style.borderColor = '';

                if (isBenarVal == "1") {
                    statusBadge.className = 'kx-stamp ok status-badge';
                    statusBadge.innerHTML = '<i class="fa-solid fa-check"></i>Dinilai (' + inputNilai + ' Poin)';
                } else if (isIsian && inputNilai > 0) {
                    statusBadge.className = 'kx-stamp ok status-badge';
                    statusBadge.style.background = '#e0f2fe';
                    statusBadge.style.color = '#0369a1';
                    statusBadge.style.borderColor = '#bae6fd';
                    statusBadge.innerHTML = '<i class="fa-solid fa-hand-holding-dollar"></i>Upah Nulis (' + inputNilai + ' Poin)';
                } else if (isIsian) {
                    statusBadge.className = 'kx-stamp pending status-badge';
                    statusBadge.style.background = '#fef3c7';
                    statusBadge.style.color = '#b45309';
                    statusBadge.style.borderColor = '#fde68a';
                    statusBadge.innerHTML = '<i class="fa-solid fa-pen-nib"></i>Tinjau Upah Nulis';
                } else {
                    statusBadge.className = 'kx-stamp ok status-badge';
                    statusBadge.innerHTML = '<i class="fa-solid fa-check"></i>Dinilai (' + inputNilai + ' Poin)';
                }
            }

            setTimeout(() => {
                indicator.classList.remove('show');
            }, 3000);
        } else {
            alert('Gagal menyimpan: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan koneksi saat menyimpan penilaian.');
        btn.innerHTML = originalBtnText;
        btn.disabled = false;
    });
}

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

    // Sync aria-expanded + chevron for our custom collapse headers
    document.querySelectorAll('.kx-card-head').forEach(function(btn) {
        const targetId = btn.getAttribute('data-bs-target');
        const target = document.querySelector(targetId);
        if (!target) return;
        target.addEventListener('show.bs.collapse', () => btn.setAttribute('aria-expanded', 'true'));
        target.addEventListener('hide.bs.collapse', () => btn.setAttribute('aria-expanded', 'false'));
    });

    // Sync active state on folder tabs
    document.querySelectorAll('#koreksiTab .kx-tab').forEach(function(tabBtn) {
        tabBtn.addEventListener('shown.bs.tab', function() {
            document.querySelectorAll('#koreksiTab .kx-tab').forEach(b => b.classList.remove('active'));
            tabBtn.classList.add('active');
        });
    });
});

function simpanPotonganPelanggaran(event) {
    event.preventDefault();
    const btn = document.getElementById('btnSavePotongan');
    const input = document.getElementById('potonganPelanggaranInput');
    const notice = document.getElementById('potonganNotice');
    const origHtml = btn.innerHTML;

    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
    btn.disabled = true;

    const formData = new FormData();
    formData.append('potongan_pelanggaran', input.value);

    fetch("{{ route('dashboard-guru.nilai-siswa.store-koreksi', ['ujian' => $ujian->id, 'siswa' => $siswa->id]) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = origHtml;
        btn.disabled = false;
        if (data.success) {
            if (data.nilai_akhir !== undefined) {
                const scoreDisplay = document.getElementById('nilai-akhir-display');
                if (scoreDisplay) {
                    scoreDisplay.innerHTML = parseFloat(data.nilai_akhir).toFixed(2) + ' <span class="kx-ledger-sub">/ 100</span>';
                }
            }
            let pot = parseFloat(input.value);
            notice.textContent = pot > 0 ? `Dipotong -${pot} poin` : 'Belum ada potongan';
        } else {
            alert('Gagal menyimpan potongan: ' + data.message);
        }
    })
    .catch(err => {
        btn.innerHTML = origHtml;
        btn.disabled = false;
        console.error(err);
    });
}
</script>
@endsection