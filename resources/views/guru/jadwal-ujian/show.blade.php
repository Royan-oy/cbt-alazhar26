@extends('layouts.app')

@section('content')
<style>
    /* ============================================
       DESIGN TOKENS (konsisten dengan halaman index)
    ============================================ */
    :root {
        --brand-50:  #f0f9ff;
        --brand-100: #e0f2fe;
        --brand-400: #38bdf8;
        --brand-500: #0ea5e9;
        --brand-600: #0284c7;
        --brand-700: #0369a1;
        --ink-900: #0f172a;
        --ink-700: #334155;
        --ink-500: #64748b;
        --ink-300: #cbd5e1;
        --ink-200: #e2e8f0;
        --ink-100: #f1f5f9;
        --ink-50:  #f8fafc;
        --radius-lg: 20px;
        --radius-md: 14px;
        --radius-sm: 10px;
        --shadow-soft: 0 2px 10px rgba(15, 23, 42, 0.04);
        --shadow-mid: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .je-wrap { max-width: 1400px; margin-inline: auto; }

    /* ============================================
       HEADER
    ============================================ */
    .je-back-btn {
        background: #fff;
        border: 1.5px solid var(--ink-200);
        color: var(--ink-500);
        font-weight: 600;
        font-size: 13px;
        border-radius: 999px;
        padding: 0.55rem 1.1rem;
        display: inline-flex;
        align-items: center;
        transition: all .2s ease;
        text-decoration: none;
    }
    .je-back-btn:hover {
        background: var(--ink-100);
        color: var(--ink-900);
        transform: translateX(-2px);
    }

    .je-header {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.75rem;
    }
    .je-header h3 {
        font-weight: 800;
        color: var(--ink-900);
        letter-spacing: -0.02em;
        margin: 0.75rem 0 0.35rem;
    }
    .je-header p {
        color: var(--ink-500);
        font-size: 14px;
        margin: 0;
    }

    .je-status-pill {
        background: rgba(34,197,94,.1);
        color: #16a34a;
        border: 1.5px solid rgba(34,197,94,.25);
        padding: 0.65rem 1.25rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 13.5px;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }

    /* ============================================
       INFO CARDS
    ============================================ */
    .je-card {
        border: 1px solid var(--ink-100);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-soft);
        background: #fff;
        height: 100%;
    }
    .je-card-body { padding: 1.75rem; }

    .je-card-title {
        font-weight: 800;
        color: var(--ink-900);
        font-size: 16px;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--ink-100);
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .je-card-title i { color: var(--brand-600); }

    .je-info-row {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .je-info-row:last-child { margin-bottom: 0; }

    .je-icon-box {
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 19px;
    }
    .je-icon-primary { background: var(--brand-50); color: var(--brand-600); }
    .je-icon-warning { background: #fffbeb; color: #d97706; }

    .je-info-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--ink-300);
        margin-bottom: 4px;
    }
    .je-info-value {
        font-size: 15px;
        font-weight: 700;
        color: var(--ink-900);
        line-height: 1.4;
        word-break: break-word;
    }
    .je-info-value.lg { font-size: 17px; }

    .je-token {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        background: var(--ink-50);
        border: 1.5px dashed var(--ink-200);
        color: #dc2626;
        padding: 0.4rem 0.85rem;
        border-radius: var(--radius-sm);
        font-weight: 700;
        letter-spacing: 0.08em;
        display: inline-block;
    }
    .je-token-empty {
        color: var(--ink-500);
        font-style: italic;
        font-weight: 500;
        font-size: 14px;
    }

    /* ============================================
       WAKTU CARD
    ============================================ */
    .je-time-box {
        border-radius: var(--radius-md);
        padding: 1.1rem;
        border: 1px solid var(--ink-100);
        background: var(--ink-50);
        height: 100%;
    }
    .je-time-box .je-info-value {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 20px;
        margin-top: 2px;
    }
    .je-time-start .je-info-value { color: var(--brand-600); }
    .je-time-end .je-info-value { color: #dc2626; }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 767.98px) {
        .je-header { flex-direction: column; align-items: flex-start; }
        .je-card-body { padding: 1.35rem; }
        .je-info-row { gap: 0.8rem; }
        .je-icon-box { width: 42px; height: 42px; font-size: 16px; }
    }
</style>

<div class="container-fluid py-4 px-3 px-md-4 je-wrap">

    <!-- HEADER PAGE & BACK BUTTON -->
    <div class="je-header">
        <div>
            <a href="{{ route('dashboard-guru.jadwal-ujian.index') }}" class="je-back-btn">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Daftar Ujian
            </a>
            <h3>Detail Jadwal Ujian</h3>
            <p>Informasi lengkap terkait pelaksanaan ujian.</p>
        </div>

        <span class="je-status-pill">
            <i class="fa-solid fa-circle-check me-2"></i> Siap Dilaksanakan
        </span>
    </div>

    <div class="row g-4">
        <!-- CARD INFO UTAMA -->
        <div class="col-12 col-lg-7">
            <div class="je-card">
                <div class="je-card-body">
                    <h5 class="je-card-title"><i class="fa-solid fa-circle-info"></i> Informasi Akademik</h5>

                    <div class="je-info-row">
                        <div class="je-icon-box je-icon-primary">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>
                        <div>
                            <div class="je-info-label">Nama Ujian</div>
                            <div class="je-info-value lg">{{ $ujian->nama_ujian }}</div>
                        </div>
                    </div>

                    <div class="je-info-row">
                        <div class="je-icon-box je-icon-primary">
                            <i class="fa-solid fa-book-open-reader"></i>
                        </div>
                        <div>
                            <div class="je-info-label">Mata Pelajaran</div>
                            <div class="je-info-value">{{ $ujian->bankSoal->mataPelajaran->nama_mapel }}</div>
                        </div>
                    </div>

                    <div class="je-info-row">
                        <div class="je-icon-box je-icon-primary">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div>
                            <div class="je-info-label">Guru Mapel</div>
                            <div class="je-info-value">{{ optional(optional(optional($ujian->bankSoal)->guruMapel)->guru)->nama ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="je-info-row">
                        <div class="je-icon-box je-icon-warning">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div>
                            <div class="je-info-label">Token Ujian</div>
                            <div class="je-info-value">
                                @if($ujian->token)
                                    <span class="je-token">{{ $ujian->token }}</span>
                                @else
                                    <span class="je-token-empty">Tidak menggunakan token</span>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- CARD INFO WAKTU PELAKSANAAN -->
        <div class="col-12 col-lg-5">
            <div class="je-card">
                <div class="je-card-body">
                    <h5 class="je-card-title"><i class="fa-regular fa-clock"></i> Waktu Pelaksanaan</h5>

                    <div class="je-info-row">
                        <div class="je-icon-box je-icon-primary">
                            <i class="fa-regular fa-calendar-check"></i>
                        </div>
                        <div>
                            <div class="je-info-label">Tanggal Ujian</div>
                            <div class="je-info-value">{{ \Carbon\Carbon::parse($ujian->tanggal)->translatedFormat('l, d F Y') }}</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="je-time-box je-time-start">
                                <div class="je-info-label">Waktu Mulai</div>
                                <div class="je-info-value">
                                    <i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="je-time-box je-time-end">
                                <div class="je-info-label">Waktu Selesai</div>
                                <div class="je-info-value">
                                    <i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection