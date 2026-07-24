@extends('layouts.app')

@section('title', 'Nilai Siswa - Guru Mapel')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap');

    :root {
        --ink-900: #0f172a;
        --ink-700: #4a5568;
        --ink-600: #64748b;
        --ink-400: #94a3b8;
        --paper-0:  #ffffff;
        --paper-50: #f8fafc;
        --paper-100: #e2e8f0;
        --rule: #e2e8f0;
        --accent-blue: #1b84ff;
        --accent-blue-soft: #f0f6ff;
        --grade-accent: #1b84ff;
        --stamp-gold: #0284c7;
        --stamp-gold-soft: #f0f6ff;
        --ink-green: #17c653;
        --ink-green-soft: #f0fdf4;
    }

    .ujian-shell {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        background: var(--paper-50);
        border-radius: 28px;
        padding: 2px;
    }

    /* ===== LETTERHEAD ===== */
    .exam-letterhead {
        position: relative;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        border-radius: 26px;
        padding: 2.25rem 2rem 2.75rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        overflow: hidden;
    }

    .exam-letterhead::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 92% -10%, rgba(27, 132, 255, 0.25) 0%, transparent 55%),
            repeating-linear-gradient(135deg, rgba(255,255,255,0.025) 0 2px, transparent 2px 14px);
        pointer-events: none;
    }

    .seal {
        position: relative;
        z-index: 1;
        flex-shrink: 0;
        width: 62px;
        height: 62px;
        border-radius: 50%;
        border: 2px dashed rgba(56, 189, 248, 0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #38bdf8;
        font-size: 1.4rem;
        background: rgba(255, 255, 255, 0.04);
    }

    .letterhead-text {
        position: relative;
        z-index: 1;
    }

    .letterhead-text .eyebrow {
        display: block;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #38bdf8;
        opacity: 0.9;
        margin-bottom: 0.4rem;
    }

    .letterhead-text h1 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: 2rem;
        color: #ffffff;
        margin: 0 0 0.3rem;
        line-height: 1.1;
    }

    .letterhead-text p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
        margin: 0;
    }

    /* perforated tear-line beneath the letterhead */
    .perf-edge {
        height: 18px;
        background-size: 26px 18px;
        background-position: 13px -9px;
        margin-bottom: 1.75rem;
    }

    /* ===== TICKET LIST ===== */
    .ticket-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        padding: 0 0.25rem 0.25rem;
    }

    .ticket-row {
        display: flex;
        text-decoration: none;
        background: var(--paper-0);
        border: 1px solid var(--paper-100);
        border-radius: 16px;
        overflow: hidden;
        transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
    }

    .ticket-row:hover {
        border-color: var(--accent-blue);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        transform: translateY(-2px);
    }

    .ticket-row:focus-visible {
        outline: 2px solid var(--accent-blue);
        outline-offset: 2px;
    }

    .ticket-stub {
        flex-shrink: 0;
        width: 92px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: var(--paper-50);
        border-right: 2px dashed var(--rule);
        padding: 1rem 0.5rem;
    }

    .stub-no {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--ink-400);
        letter-spacing: 0.05em;
        margin-bottom: 0.35rem;
    }

    .stub-day {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: 1.7rem;
        color: var(--ink-900);
        line-height: 1;
    }

    .stub-month {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        color: var(--accent-blue);
        text-transform: uppercase;
        margin-top: 0.15rem;
    }

    .ticket-body {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.1rem 1.4rem;
        min-width: 0;
    }

    .ticket-main {
        min-width: 0;
    }

    .ticket-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #0284c7;
        background: #f0f6ff;
        border: 1px solid #bae6fd;
        padding: 0.2rem 0.55rem;
        border-radius: 5px;
        margin-bottom: 0.55rem;
    }

    .ticket-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--ink-900);
        margin: 0 0 0.55rem;
        line-height: 1.25;
    }

    .ticket-meta {
        display: flex;
        flex-wrap: wrap;
        row-gap: 0.4rem;
        column-gap: 1.25rem;
        font-size: 0.82rem;
        color: var(--ink-600);
    }

    .ticket-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
    }

    .ticket-meta i {
        color: var(--ink-400);
        font-size: 0.78rem;
    }

    .ticket-meta .peserta-count {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        color: #15803d;
        background: var(--ink-green-soft);
        padding: 0.15rem 0.5rem;
        border-radius: 999px;
    }

    .ticket-action {
        flex-shrink: 0;
    }

    .btn-grade {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ink-900);
        background: transparent;
        border: 1.5px solid var(--ink-900);
        padding: 0.55rem 1.1rem;
        border-radius: 999px;
        transition: background 0.18s ease, color 0.18s ease, gap 0.18s ease, border-color 0.18s ease;
    }

    .ticket-row:hover .btn-grade {
        background: var(--accent-blue);
        border-color: var(--accent-blue);
        color: #ffffff;
        gap: 0.7rem;
    }

    /* ===== EMPTY STATE ===== */
    .ticket-empty {
        margin: 0 0.25rem;
        border: 2px dashed var(--rule);
        border-radius: 16px;
        padding: 3.5rem 2rem;
        text-align: center;
        background: var(--paper-0);
    }

    .ticket-empty .seal-lg {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        border: 2px dashed var(--ink-400);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: var(--ink-400);
        font-size: 1.3rem;
    }

    .ticket-empty h5 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        color: var(--ink-900);
        margin-bottom: 0.4rem;
    }

    .ticket-empty p {
        color: var(--ink-600);
        font-size: 0.88rem;
        max-width: 380px;
        margin: 0 auto;
    }

    /* ===== PAGINATION (Bootstrap default markup) ===== */
    .ujian-pagination {
        padding: 1.25rem 0.25rem 0.5rem;
    }
    .ujian-pagination .pagination { margin: 0; justify-content: center; }
    .ujian-pagination .page-link {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.82rem;
        color: var(--ink-700);
        border: 1px solid var(--paper-100);
        margin: 0 3px;
        border-radius: 8px !important;
    }
    .ujian-pagination .page-item.active .page-link {
        background: var(--ink-900);
        border-color: var(--ink-900);
        color: var(--paper-0);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 640px) {
        .exam-letterhead {
            padding: 1.75rem 1.25rem 2.25rem;
        }
        .letterhead-text h1 { font-size: 1.5rem; }

        .ticket-row {
            flex-direction: column;
        }
        .ticket-stub {
            width: 100%;
            flex-direction: row;
            gap: 0.6rem;
            justify-content: flex-start;
            border-right: none;
            border-bottom: 2px dashed var(--rule);
            padding: 0.65rem 1.1rem;
        }
        .stub-no { margin-bottom: 0; }
        .stub-day { font-size: 1.15rem; }
        .stub-month { margin-top: 0; }
        .ticket-body {
            flex-direction: column;
            align-items: stretch;
            gap: 0.9rem;
        }
        .ticket-action .btn-grade {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="ujian-shell">

    {{-- LETTERHEAD --}}
    <div class="exam-letterhead">
        <div class="seal"><i class="fa-solid fa-graduation-cap"></i></div>
        <div class="letterhead-text">
            <span class="eyebrow">Ruang Guru &middot; Koreksi &amp; Penilaian</span>
            <h1>Daftar Ujian</h1>
            <p>Pilih sesi ujian untuk mengelola nilai dan mengoreksi jawaban siswa.</p>
        </div>
    </div>
    <div class="perf-edge"></div>

    {{-- LIST UJIAN --}}
    @if($ujians->isEmpty())
        <div class="ticket-empty">
            <div class="seal-lg"><i class="fa-solid fa-folder-open"></i></div>
            <h5>Belum Ada Ujian</h5>
            <p>Anda belum membuat atau memiliki ujian yang diselenggarakan dari Bank Soal Anda.</p>
        </div>
    @else
        <div class="ticket-list">
            @foreach($ujians as $index => $ujian)
                @php
                    $mulai = \Carbon\Carbon::parse($ujian->waktu_mulai);
                    $selesai = \Carbon\Carbon::parse($ujian->waktu_selesai);
                    $nomorTiket = str_pad(($ujians->currentPage() - 1) * $ujians->perPage() + $loop->iteration, 3, '0', STR_PAD_LEFT);
                @endphp
                <a href="{{ route('dashboard-guru.nilai-siswa.show', $ujian->id) }}" class="ticket-row">
                    <div class="ticket-stub">
                        <span class="stub-no">NO. {{ $nomorTiket }}</span>
                        <span class="stub-day">{{ $mulai->format('d') }}</span>
                        <span class="stub-month">{{ $mulai->translatedFormat('M') }}</span>
                    </div>
                    <div class="ticket-body">
                        <div class="ticket-main">
                            <span class="ticket-tag"><i class="fa-solid fa-tag"></i> {{ $ujian->nama_jenis_ujian }}</span>
                            <h3 class="ticket-title">{{ $ujian->nama_ujian }}</h3>
                            <div class="ticket-meta">
                                <span><i class="fa-solid fa-book-open"></i> {{ $ujian->nama_mapel }}</span>
                                <span><i class="fa-regular fa-clock"></i> {{ $mulai->format('H:i') }}&ndash;{{ $selesai->format('H:i') }}</span>
                                <span><i class="fa-regular fa-calendar"></i> {{ $ujian->nama_tahun }}</span>
                                <span class="peserta-count"><i class="fa-solid fa-users"></i> {{ $ujian->peserta_count }} peserta</span>
                            </div>
                        </div>
                        <div class="ticket-action">
                            <span class="btn-grade">Kelola Nilai <i class="fa-solid fa-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if($ujians->hasPages())
        <div class="ujian-pagination">
            {{ $ujians->links() }}
        </div>
        @endif
    @endif

</div>
@endsection