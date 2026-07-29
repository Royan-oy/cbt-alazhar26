@extends('layouts.ujian')

@section('title', 'Lembar Kerja Ujian')

@section('content')
<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --accent-blue-light: #38bdf8;
        --accent-blue-dark: #0284c7;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --paper-bg: #f8fafc;
        --danger: #ef4444;
        --danger-soft: #fef2f2;
        --success: #10b981;
        --warning: #f59e0b;
        --warning-soft: #fffbeb;
        --card-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05), 0 4px 12px -5px rgba(15, 23, 42, 0.03);
        --card-shadow-lg: 0 20px 40px -10px rgba(15, 23, 42, 0.15);
        --radius-lg: 20px;
        --radius-md: 14px;
        --radius-sm: 10px;
        --bottom-bar-h: 74px;
    }

    body {
        background: var(--paper-bg);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        color: #1e293b;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    img {
        pointer-events: none;
        -webkit-touch-callout: none;
    }

    .soal-card {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    textarea, input[type="text"] {
        -webkit-user-select: text;
        -ms-user-select: text;
        user-select: text;
    }

    /* =========================================================
       WRAPPER — kolom tunggal terfokus
       ========================================================= */
    .exam-wrapper {
        max-width: 820px;
        margin: 0 auto;
        padding: 20px 20px calc(var(--bottom-bar-h) + 36px);
    }

    /* =========================================================
       BANNER PERINGATAN FULLSCREEN — Dark Amber Style
       ========================================================= */
    .fullscreen-warning-banner {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
        padding: 12px 18px;
        border-radius: var(--radius-md);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.08);
    }

    .fullscreen-warning-banner .icon-wrap {
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 10px;
        background: rgba(245, 158, 11, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        color: #d97706;
    }

    .fullscreen-warning-banner .banner-sub {
        display: block;
        font-weight: 500;
        font-size: 11.5px;
        color: #b45309;
        margin-top: 1px;
    }

    /* =========================================================
       TOPBAR — Soft Bluish-White Header dengan Pill Badges
       ========================================================= */
    .exam-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
        border: 1px solid #bae6fd;
        border-radius: var(--radius-lg);
        padding: 20px 24px;
        margin-bottom: 22px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(14, 165, 233, 0.08);
    }

    .exam-topbar::after {
        content: '';
        position: absolute;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        right: -40px;
        top: -60px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.12) 0%, transparent 70%);
        pointer-events: none;
    }

    .exam-topbar .exam-label {
        font-size: 10.5px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1.2px;
        color: #0284c7;
        display: block;
        margin-bottom: 4px;
    }

    .exam-topbar .exam-title {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
    }

    .exam-time-pills {
        display: flex;
        gap: 10px;
        position: relative;
        z-index: 2;
    }

    .time-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        border-radius: 999px;
        padding: 8px 16px;
        background: #ffffff;
        border: 1px solid #bae6fd;
        box-shadow: 0 2px 8px rgba(14, 165, 233, 0.06);
    }

    .time-pill.is-timer {
        background: #fef2f2;
        border-color: #fecaca;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.06);
    }

    .time-pill i { font-size: 13px; }
    .time-pill.is-clock i { color: #0284c7; }
    .time-pill.is-timer i { color: #dc2626; }

    .time-pill .pill-text {
        display: flex;
        flex-direction: column;
        line-height: 1.15;
    }

    .time-pill .pill-label {
        font-size: 8.5px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #64748b;
    }

    .time-pill.is-timer .pill-label { color: #b91c1c; }

    #currentClock {
        font-size: 15px;
        font-weight: 800;
        color: #0284c7;
        font-variant-numeric: tabular-nums;
        margin: 0;
    }

    #countdownTimer {
        font-size: 15px;
        font-weight: 800;
        color: #dc2626;
        font-variant-numeric: tabular-nums;
        margin: 0;
    }

    /* =========================================================
    /* =========================================================
       KARTU SOAL
       ========================================================= */
    .soal-card {
        position: relative;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: var(--radius-lg);
        padding: 28px 26px;
        margin-bottom: 18px;
        box-shadow: var(--card-shadow);
        display: none;
        overflow: hidden;
    }

    .soal-card.active {
        display: block;
        animation: fadeIn 0.35s ease-in-out;
    }

    /* Watermark Icon (Menggantikan Angka Raksasa) */
    .soal-card::before {
        content: '\f15c';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        top: -10px;
        right: 15px;
        font-size: 110px;
        color: #0ea5e9;
        opacity: 0.035;
        line-height: 1;
        pointer-events: none;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .soal-meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 8px;
        position: relative;
    }

    .soal-index-badge {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        color: #ffffff;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.3px;
        padding: 6px 14px;
        border-radius: 999px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
    }

    .soal-bobot {
        font-size: 12px;
        font-weight: 700;
        color: #0284c7;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        padding: 5px 12px;
        border-radius: 999px;
    }

    .soal-bobot i { color: #0284c7; }

    .soal-teks {
        font-size: 15.5px;
        line-height: 1.75;
        color: #1e293b;
        margin-bottom: 22px;
        position: relative;
    }

    /* Thumbnail Gambar Soal (Kecil & Terukur: ~280px) */
    .soal-gambar-container {
        margin-bottom: 22px;
    }

    .soal-gambar-wrap {
        display: inline-block;
        position: relative;
        cursor: pointer;
        border-radius: var(--radius-md);
        overflow: hidden;
    }

    .soal-gambar-wrap img {
        max-width: 280px;
        max-height: 220px;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: var(--radius-md);
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        transition: all 0.2s ease;
        display: block;
    }

    .soal-gambar-wrap:hover img {
        border-color: #0ea5e9;
        transform: scale(1.02);
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.18);
    }

    .soal-gambar-badge {
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: rgba(15, 23, 42, 0.75);
        color: #ffffff;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        gap: 5px;
        pointer-events: none;
        transition: background 0.2s ease;
    }

    .soal-gambar-wrap:hover .soal-gambar-badge {
        background: rgba(14, 165, 233, 0.9);
    }

    /* Gambar di dalam teks soal / pilihan editor */
    .soal-teks img, .option-text img {
        max-width: 280px;
        max-height: 220px;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: var(--radius-md);
        border: 1.5px solid #e2e8f0;
        cursor: pointer;
        transition: transform 0.2s ease;
        margin: 8px 0;
    }

    .soal-teks img:hover, .option-text img:hover {
        border-color: #0ea5e9;
        transform: scale(1.02);
    }

    /* =========================================================
       MODAL PREVIEW GAMBAR (LIGHTBOX FULL-SIZE)
       ========================================================= */
    .image-preview-modal {
        background: #ffffff;
        border-radius: var(--radius-lg);
        padding: 20px;
        max-width: 92vw;
        max-height: 92vh;
        width: fit-content;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.3);
        display: flex;
        flex-direction: column;
        transform: scale(0.92);
        transition: transform 0.2s ease;
    }

    .finish-exam-overlay.show .image-preview-modal {
        transform: scale(1);
    }

    .image-preview-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .image-preview-body {
        overflow: auto;
        max-height: calc(85vh - 50px);
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f8fafc;
        border-radius: var(--radius-md);
        padding: 12px;
    }

    .image-preview-body img {
        max-width: 100%;
        max-height: 75vh;
        width: auto;
        height: auto;
        border-radius: 8px;
        object-fit: contain;
    }

    /* =========================================================
       OPSI JAWABAN
       ========================================================= */
    .options-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        position: relative;
    }

    .option-wrapper { position: relative; }

    .option-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .btn-option {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: var(--radius-md);
        padding: 14px 18px;
        cursor: pointer;
        background: #ffffff;
        transition: border-color 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
    }

    .btn-option:hover {
        border-color: #7dd3fc;
        background: #f0f9ff;
    }

    .option-badge {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 12.5px;
        color: #334155;
        background: #f8fafc;
        transition: all 0.15s ease;
    }

    .option-text {
        font-size: 14.5px;
        line-height: 1.6;
        color: #1e293b;
        padding-top: 3px;
    }

    .option-input:checked + .btn-option {
        border-color: #0ea5e9;
        background: #f0f9ff;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.12);
    }

    .option-input:checked + .btn-option .option-badge {
        background: #0ea5e9;
        border-color: #0ea5e9;
        color: #ffffff;
    }

    .option-input:focus-visible + .btn-option {
        outline: 2px solid #0ea5e9;
        outline-offset: 2px;
    }

    /* =========================================================
       ESSAY
       ========================================================= */
    .essay-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 10px;
    }

    textarea.form-control {
        border: 1.5px solid #e2e8f0;
        border-radius: var(--radius-md);
        font-size: 14.5px;
        padding: 14px 16px;
        resize: vertical;
        box-shadow: none;
        color: #1e293b;
    }

    textarea.form-control:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
    }

    /* =========================================================
       BOTTOM ACTION BAR — Fixed Soft Bluish-White Bar
       ========================================================= */
    .exam-bottom-nav {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1025;
        background: linear-gradient(180deg, #ffffff 0%, #f0f9ff 100%);
        border-top: 1px solid #bae6fd;
        box-shadow: 0 -8px 24px rgba(14, 165, 233, 0.08);
        padding: 12px 16px;
        margin: 0;
    }

    .exam-bottom-nav-inner {
        max-width: 780px;
        margin: 0 auto;
        display: flex;
        gap: 10px;
        justify-content: center;
        align-items: center;
    }

    .exam-bottom-nav .btn {
        border-radius: var(--radius-sm);
        padding: 12px 14px;
        font-weight: 700;
        font-size: 13.5px;
        transition: all 0.15s ease;
    }

    .exam-bottom-nav .btn:disabled {
        background: #f1f5f9 !important;
        color: #94a3b8 !important;
        border-color: #e2e8f0 !important;
        cursor: not-allowed;
    }

    #btnPrev, #btnNext {
        background: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1 !important;
        flex: 0 0 auto;
        min-width: 130px;
    }

    #btnPrev:hover:not(:disabled), #btnNext:hover:not(:disabled) {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8 !important;
    }

    #btnRagu {
        flex: 0 0 auto;
        min-width: 130px;
        background: var(--warning);
        color: #ffffff;
    }

    #btnRagu:hover:not(:disabled) {
        filter: brightness(1.05);
    }

    #btnFinishBottom {
        display: none;
        flex: 0 0 auto;
        min-width: 150px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #ffffff;
        border: none !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }

    #btnFinishBottom:hover {
        background: linear-gradient(135deg, #059669, #047857);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
    }

    /* =========================================================
       TOMBOL NAVIGASI SOAL — melayang, membuka drawer (FAB)
       ========================================================= */
    .fab-navigator {
        position: fixed;
        right: 22px;
        bottom: calc(var(--bottom-bar-h) + 18px);
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        border: 2px solid var(--accent-blue-light);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.35);
        cursor: pointer;
        z-index: 1026;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .fab-navigator:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(14, 165, 233, 0.4);
    }

    /* =========================================================
       DRAWER NAVIGASI SOAL (off-canvas)
       ========================================================= */
    .drawer-toggle-input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    .drawer-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(3px);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
        z-index: 2040;
        cursor: pointer;
    }

    #navDrawerToggle:checked ~ .drawer-backdrop {
        opacity: 1;
        visibility: visible;
    }

    .nav-drawer {
        position: fixed;
        top: 0;
        right: 0;
        height: 100vh;
        width: min(340px, 86vw);
        background: var(--surface-white);
        border-left: 1px solid var(--border-color);
        box-shadow: var(--card-shadow-lg);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        z-index: 2050;
        padding: 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    #navDrawerToggle:checked ~ .nav-drawer {
        transform: translateX(0);
    }

    .nav-drawer-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--border-color);
    }

    .nav-drawer-header h6 {
        font-weight: 800;
        font-size: 16px;
        color: var(--primary-dark);
        margin: 0;
    }

    .drawer-close {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--paper-bg);
        color: var(--text-muted);
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.15s ease;
    }

    .drawer-close:hover {
        background: #e2e8f0;
        color: var(--primary-dark);
    }

    .number-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    .number-box {
        aspect-ratio: 1 / 1;
        border-radius: 50%;
        border: 1.5px solid var(--border-color);
        background: var(--surface-white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12.5px;
        font-weight: 700;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .number-box:hover {
        border-color: var(--accent-blue);
        color: var(--accent-blue);
        transform: translateY(-1px);
    }

    .number-box.answered {
        background: var(--accent-blue);
        border-color: var(--accent-blue);
        color: #ffffff;
    }

    .number-box.ragu {
        background: #fffbeb;
        border: 1.5px dashed var(--warning);
        color: #b45309;
    }

    .number-box.active {
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.3);
        border-color: var(--accent-blue);
    }

    .nav-legend {
        display: flex;
        flex-direction: column;
        gap: 8px;
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .nav-legend span {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .dot-answered { background: var(--accent-blue); }
    .dot-ragu { background: #fffbeb; border: 1.5px dashed var(--warning); }
    .dot-empty { background: var(--surface-white); border: 1.5px solid var(--border-color); }

    .nav-drawer-footer {
        margin-top: auto;
        padding-top: 18px;
    }

    .btn-finish-exam {
        width: 100%;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        border: none;
        border-radius: var(--radius-sm);
        padding: 13px;
        font-weight: 700;
        font-size: 13.5px;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
        transition: all 0.15s ease;
    }

    .btn-finish-exam:hover:not(:disabled) {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35);
    }

    .btn-finish-exam:disabled {
        background: var(--border-color);
        color: #94a3b8;
        box-shadow: none;
        cursor: not-allowed;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */
    @media (max-width: 575.98px) {
        .exam-wrapper { padding: 14px 12px calc(var(--bottom-bar-h) + 30px); }
        .soal-card { padding: 22px 18px; }
        .exam-topbar { align-items: flex-start; }
        .exam-time-pills { width: 100%; }
        .time-pill { flex: 1; justify-content: center; }
        #btnPrev, #btnRagu, #btnNext, #btnFinishBottom { min-width: 0; flex: 1; }
        .exam-bottom-nav .btn span.btn-label-full { display: none; }
    }

    /* =========================================================
       MODAL KONFIRMASI SELESAI UJIAN
       ========================================================= */
    .finish-exam-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2100;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
    }

    .finish-exam-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .finish-exam-modal {
        background: var(--surface-white);
        border-radius: var(--radius-lg);
        border-top: 4px solid var(--accent-blue);
        padding: 32px 28px;
        max-width: 380px;
        width: 90%;
        text-align: center;
        box-shadow: var(--card-shadow-lg);
        transform: scale(0.92);
        transition: transform 0.2s ease;
    }

    .finish-exam-overlay.show .finish-exam-modal {
        transform: scale(1);
    }

    .finish-exam-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 16px;
        border-radius: 50%;
        background: var(--danger-soft);
        color: var(--danger);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .finish-exam-modal h5 {
        font-weight: 800;
        font-size: 19px;
        color: var(--primary-dark);
        margin-bottom: 8px;
    }

    .finish-exam-modal p {
        font-size: 13.5px;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 22px;
    }

    .finish-exam-actions {
        display: flex;
        gap: 10px;
    }

    .finish-exam-actions button {
        flex: 1;
        padding: 12px;
        border-radius: var(--radius-sm);
        font-weight: 700;
        font-size: 13.5px;
        border: none;
        cursor: pointer;
    }

    .btn-batal-finish {
        background: var(--paper-bg);
        color: #475569;
        border: 1px solid var(--border-color) !important;
    }

    .btn-yakin-finish {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
    }
</style>

<div class="exam-wrapper">

    {{-- BANNER PERINGATAN: JANGAN KELUAR FULLSCREEN --}}
    <div class="fullscreen-warning-banner">
        <span class="icon-wrap"><i class="fa-solid fa-triangle-exclamation"></i></span>
        <div>
            Tetap di mode layar penuh selama ujian berlangsung.
            <span class="banner-sub">Keluar fullscreen tercatat sebagai pelanggaran (maks. 2x sebelum ujian dikumpulkan otomatis).</span>
        </div>
    </div>

    {{-- TOPBAR: judul ujian + jam/timer --}}
    <div class="exam-topbar">
        <div>
            <span class="exam-label">Sedang Mengerjakan</span>
            <h5 class="exam-title">{{ $ujian->nama_ujian }}</h5>
        </div>
        <div class="exam-time-pills">
            <div class="time-pill is-clock">
                <i class="fa-regular fa-clock"></i>
                <div class="pill-text">
                    <span class="pill-label">Jam</span>
                    <p id="currentClock">--:--:--</p>
                </div>
            </div>

            <div class="time-pill is-timer">
                <i class="fa-solid fa-hourglass-half"></i>
                <div class="pill-text">
                    <span class="pill-label">Sisa Waktu</span>
                    <p id="countdownTimer">--:--:--</p>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM SOAL (kolom tunggal, terfokus) --}}
    <form
        id="formUjian"
        action="{{ route('dashboard-siswa.ujian.submit', $ujian->id) }}"
        method="POST">
        @csrf

        @foreach($soals as $index => $soal)
            <div
                class="soal-card {{ $index==$currentQuestion?'active':'' }}"
                id="card-soal-{{ $index }}"
                data-soal-index="{{ $index }}"
                data-soal-id="{{ $soal->id }}">

                <div class="soal-meta-row">
                    <span class="soal-index-badge">
                        Soal {{ $index + 1 }} dari {{ $soals->count() }}
                    </span>
                    <span class="soal-bobot">
                        <i class="fa-regular fa-star"></i> Bobot: {{ $soal->bobot }} Poin
                    </span>
                </div>

                {{-- Teks Soal --}}
                <div class="soal-teks">
                    {!! $soal->teks_soal !!}
                </div>

                {{-- Gambar Soal (Jika Ada) --}}
                @if($soal->gambar)
                    <div class="soal-gambar-container">
                        <div class="soal-gambar-wrap" onclick="openImageModal('{{ asset('storage/' . $soal->gambar) }}', 'Soal {{ $index + 1 }}')">
                            <img src="{{ asset('storage/' . $soal->gambar) }}" alt="Gambar Soal {{ $index + 1 }}">
                            <span class="soal-gambar-badge">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> Perbesar
                            </span>
                        </div>
                    </div>
                @endif

                {{-- Input Opsi Jawaban --}}
                @if($soal->jenis_soal == 'pilihan_ganda')
                    <div class="options-container">
                        @php
                            $jawabanSoal = $jawaban[$soal->id] ?? null;
                        @endphp
                        @foreach($soal->pilihanJawabans as $pilihan)
                            <div class="option-wrapper">

                            <input
                                type="radio"
                                name="jawaban[{{ $soal->id }}]"
                                id="opt-{{ $pilihan->id }}"
                                value="{{ $pilihan->id }}"
                                class="option-input"

                                {{ optional($jawabanSoal)->pilihan_jawaban_id == $pilihan->id ? 'checked' : '' }}

                                onchange="
                                    markAsAnswered({{ $index }});

                                    saveAnswer(
                                        {{ $ujian->id }},
                                        {{ $soal->id }},
                                        {{ $pilihan->id }},
                                        'pilihan_ganda'
                                    );
                                "
                            >

                            <label
                                for="opt-{{ $pilihan->id }}"
                                class="btn-option">

                                <span class="option-badge">
                                    {{ chr(65 + $loop->index) }}
                                </span>

                                <span class="option-text">
                                    {!! $pilihan->teks_pilihan !!}
                                </span>

                            </label>

                        </div>
                        @endforeach
                    </div>
                @else
                    {{-- Jika Essay / Isian --}}
                    <div class="form-group">
                        <label class="essay-label">Jawaban Anda</label>
                        @php
                            $jawabanSoal = $jawaban[$soal->id] ?? null;
                        @endphp

                        <textarea
                            name="jawaban[{{ $soal->id }}]"
                            class="form-control"
                            rows="5"
                            placeholder="Ketik jawaban lengkap Anda di sini..."
                            oninput="
                                checkEssayAnswer(this, {{ $index }});
                                saveEssay(
                                    {{ $ujian->id }},
                                    {{ $soal->id }},
                                    this.value
                                );
                        ">{{ old('jawaban.'.$soal->id, optional($jawabanSoal)->jawaban_text) }}</textarea>
                    </div>
                @endif
            </div>
        @endforeach
    </form>
</div>

{{-- BOTTOM ACTION BAR — fixed, selalu terjangkau tanpa scroll --}}
<div class="exam-bottom-nav">
    <div class="exam-bottom-nav-inner">
        <button type="button" class="btn" id="btnPrev" onclick="navigateQuestion(-1)">
            <i class="fa-solid fa-arrow-left me-2"></i><span class="btn-label-full">Sebelumnya</span>
        </button>

        <button type="button" class="btn" id="btnRagu" onclick="toggleRagu()">
            <i class="fa-regular fa-square-minus me-2"></i><span class="btn-label-full">Ragu-Ragu</span>
        </button>

        <button type="button" class="btn" id="btnNext" onclick="navigateQuestion(1)">
            <span class="btn-label-full">Selanjutnya</span> <i class="fa-solid fa-arrow-right ms-2"></i>
        </button>

        <button type="button" class="btn" id="btnFinishBottom" onclick="confirmFinish()" style="display: none;">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i><span class="btn-label-full">Selesaikan Ujian</span>
        </button>
    </div>
</div>

{{-- TOMBOL MELAYANG: buka drawer navigasi soal --}}
<label for="navDrawerToggle" class="fab-navigator" aria-label="Buka navigasi soal">
    <i class="fa-solid fa-table-cells"></i>
</label>

{{-- DRAWER NAVIGASI SOAL — off-canvas, murni CSS (checkbox hack), tanpa JS baru --}}
<input type="checkbox" id="navDrawerToggle" class="drawer-toggle-input">
<label for="navDrawerToggle" class="drawer-backdrop" aria-label="Tutup navigasi soal"></label>

<aside class="nav-drawer">
    <div class="nav-drawer-header">
        <h6><i class="fa-solid fa-th me-2" style="color: var(--gold);"></i>Navigasi Soal</h6>
        <label for="navDrawerToggle" class="drawer-close"><i class="fa-solid fa-xmark"></i></label>
    </div>

    <div class="number-grid" id="navigationGrid">
        @foreach($soals as $index => $soal)
            @php
                $jawabanSoal = $jawaban[$soal->id] ?? null;

                $answered = false;
                $ragu = false;

                if($jawabanSoal){

                    if($jawabanSoal->pilihan_jawaban_id){
                        $answered = true;
                    }

                    if(!empty($jawabanSoal->jawaban_text)){
                        $answered = true;
                    }

                    if($jawabanSoal->is_ragu_ragu){
                        $ragu = true;
                    }

                }
            @endphp

            <label
                for="navDrawerToggle"
                class="number-box
                    {{ $index==$currentQuestion ? 'active' : '' }}
                    {{ $answered ? 'answered' : '' }}
                    {{ $ragu ? 'ragu' : '' }}"
                id="nav-box-{{ $index }}"
                onclick="jumpToQuestion({{ $index }})">

                {{ $index+1 }}

            </label>
        @endforeach
    </div>

    <div class="nav-legend">
        <span><i class="dot dot-answered"></i> Terjawab</span>
        <span><i class="dot dot-ragu"></i> Ragu-ragu</span>
        <span><i class="dot dot-empty"></i> Belum diisi</span>
    </div>

    <div class="nav-drawer-footer">
        <button type="button" class="btn-finish-exam" id="btnFinishExam" onclick="confirmFinish()" disabled>
            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Selesaikan Ujian
        </button>
        <p id="finishExamNotice" style="font-size:11px;color:var(--ink-300);text-align:center;margin-top:8px;margin-bottom:0;"></p>
    </div>
</aside>

{{-- MODAL KONFIRMASI SELESAI UJIAN (custom, aman untuk fullscreen) --}}
<div class="finish-exam-overlay" id="finishExamOverlay">
    <div class="finish-exam-modal">
        <div class="finish-exam-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h5>Selesaikan Ujian?</h5>
        <p>Pastikan semua jawaban telah terisi dengan benar. Jawaban yang sudah dikumpulkan tidak dapat diubah kembali.</p>
        <div class="finish-exam-actions">
            <button type="button" class="btn-batal-finish" onclick="closeFinishModal()">
                Batal
            </button>
            <button type="button" class="btn-yakin-finish" onclick="submitFinalExam()">
                <i class="fa-solid fa-cloud-arrow-up me-1"></i> Ya, Selesaikan
            </button>
        </div>
    </div>
</div>

{{-- MODAL NOTIFIKASI WAKTU MINIMAL SELESAI UJIAN --}}
<div class="finish-exam-overlay" id="timeNoticeOverlay">
    <div class="finish-exam-modal">
        <div class="finish-exam-icon" style="background: #fffbeb; color: #d97706; border: 1px solid #fde68a;">
            <i class="fa-solid fa-clock"></i>
        </div>
        <h5>Belum Bisa Selesaikan Ujian</h5>
        <p id="timeNoticeMessage" style="font-size: 13.5px; color: #475569; line-height: 1.6;"></p>
        <div class="finish-exam-actions" style="justify-content: center;">
            <button type="button" class="btn-yakin-finish" style="background: #0284c7; width: 100%; justify-content: center;" onclick="closeTimeNoticeModal()">
                <i class="fa-solid fa-check me-1"></i> Saya Mengerti
            </button>
        </div>
    </div>
</div>

{{-- MODAL PREVIEW GAMBAR (Zoom Lightbox) --}}
<div class="finish-exam-overlay" id="imagePreviewOverlay" onclick="closeImageModal()">
    <div class="image-preview-modal" onclick="event.stopPropagation()">
        <div class="image-preview-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-image text-primary"></i>
                <span id="imagePreviewTitle" class="fw-bold text-dark" style="font-size:15px;">Detail Gambar Soal</span>
            </div>
            <button type="button" class="btn-close" onclick="closeImageModal()" aria-label="Close"></button>
        </div>
        <div class="image-preview-body">
            <img id="imagePreviewSrc" src="" alt="Preview Gambar Soal">
        </div>
    </div>
</div>

{{-- INTERACTIVE JAVASCRIPT --}}
<script>
    // ==========================================================
    // PREVIEW GAMBAR MODAL (LIGHTBOX ZOOM)
    // ==========================================================
    function openImageModal(src, title) {
        const modalSrc = document.getElementById('imagePreviewSrc');
        const modalTitle = document.getElementById('imagePreviewTitle');
        const overlay = document.getElementById('imagePreviewOverlay');

        if (modalSrc && overlay) {
            modalSrc.src = src;
            if (modalTitle) {
                modalTitle.textContent = title ? `Detail Gambar (${title})` : 'Detail Gambar Soal';
            }
            overlay.classList.add('show');
        }
    }

    function closeImageModal() {
        const overlay = document.getElementById('imagePreviewOverlay');
        if (overlay) {
            overlay.classList.remove('show');
        }
    }

    // Mengaktifkan click-to-zoom pada semua gambar di dalam teks soal / pilihan editor
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.soal-teks img, .option-text img').forEach(img => {
            img.addEventListener('click', function() {
                openImageModal(this.src, 'Soal');
            });
        });
    });

    let isReloading = false;
    let isFinishing = false;

    window.addEventListener("beforeunload", function (e) {
        isReloading = true;
        if (isFinishing) {
            delete e.returnValue;
            return;
        }
    });

    let currentIdx = {{ $currentQuestion ?? 0 }};
    const totalQuestions = {{ $soals->count() }};
    const raguStates = [
    @foreach($soals as $index=>$soal)
        @if(isset($jawaban[$soal->id]) && $jawaban[$soal->id]->is_ragu_ragu)
            true,
        @else
            false,
        @endif
    @endforeach
    ];

    // Batas waktu paling cepat boleh menyelesaikan ujian
    const minSelesaiTime = new Date("{{ $minSelesai->toIso8601String() }}").getTime();

    function checkAllAnswered() {
        const boxes = document.querySelectorAll('.number-box');
        for (const box of boxes) {
            if (!box.classList.contains('answered')) return false;
        }
        return true;
    }

    function formatJam(timestamp) {
        const d = new Date(timestamp);
        const jam = String(d.getHours()).padStart(2, '0');
        const menit = String(d.getMinutes()).padStart(2, '0');
        return `${jam}:${menit}`;
    }

    function updateFinishButtonState() {
        const btnDrawer = document.getElementById('btnFinishExam');
        const btnBottom = document.getElementById('btnFinishBottom');
        const notice = document.getElementById('finishExamNotice');

        const allAnswered = checkAllAnswered();

        if (allAnswered) {
            if (btnDrawer) btnDrawer.style.display = 'block';
            if (btnBottom) btnBottom.style.display = 'inline-flex';
        } else {
            if (btnDrawer) btnDrawer.style.display = 'none';
            if (btnBottom) btnBottom.style.display = 'none';
        }

        if (notice) notice.textContent = '';
    }

    document.addEventListener("DOMContentLoaded", function() {
        updateNavigationButtons();
        updateFinishButtonState();
        startTimer();
        startClock();
        enableAntiCheat();
        setInterval(updateFinishButtonState, 1000);
    });

    function enableAntiCheat() {
        // 1. Mencegah Klik Kanan & Long Press
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });

        // 2. Mencegah Shortcut Keyboard
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'u' || e.key === 's' || e.key === 'a')) {
                e.preventDefault();
                return false;
            }
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C'))) {
                e.preventDefault();
                return false;
            }
        });

        // 3. Deteksi Perpindahan Tab / Minimize (Alt+Tab dsb)
        // Panggil fungsi TERPUSAT dari layout — bukan fungsi lokal lagi.
        document.addEventListener("visibilitychange", function () {
            if (isReloading || isFinishing) return;

            if (document.hidden) {
                reportViolation();
            }
        });
    }

    // Pindah Soal (Selanjutnya/Sebelumnya)
    function navigateQuestion(direction) {
        let targetIdx = currentIdx + direction;
        if (targetIdx >= 0 && targetIdx < totalQuestions) {
            jumpToQuestion(targetIdx);
        }
    }

    function saveCurrentQuestion(index) {
        fetch("{{ route('dashboard-siswa.ujian.current-question') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                ujian_id: {{ $ujian->id }},
                current_question: index
            })
        });
    }

    function jumpToQuestion(index) {
        document.getElementById(`card-soal-${currentIdx}`).classList.remove('active');
        document.getElementById(`nav-box-${currentIdx}`).classList.remove('active');

        currentIdx = index;
        saveCurrentQuestion(index);
        document.getElementById(`card-soal-${currentIdx}`).classList.add('active');
        document.getElementById(`nav-box-${currentIdx}`).classList.add('active');

        updateNavigationButtons();
    }

    function updateNavigationButtons() {
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        const btnRagu = document.getElementById('btnRagu');

        btnPrev.disabled = (currentIdx === 0);
        btnNext.disabled = (currentIdx === totalQuestions - 1);

        btnNext.innerHTML = '<span class="btn-label-full">Selanjutnya</span> <i class="fa-solid fa-arrow-right ms-2"></i>';

        if (raguStates[currentIdx]) {
            btnRagu.style.background = '#dc2626';
            btnRagu.innerHTML = '<i class="fa-solid fa-square-check me-2"></i><span class="btn-label-full">Batalkan Ragu</span>';
        } else {
            btnRagu.style.background = '#f59e0b';
            btnRagu.innerHTML = '<i class="fa-regular fa-square-minus me-2"></i><span class="btn-label-full">Ragu-Ragu</span>';
        }
    }

    function toggleRagu() {
        raguStates[currentIdx] = !raguStates[currentIdx];
        const navBox = document.getElementById(`nav-box-${currentIdx}`);

        if (raguStates[currentIdx]) {
            navBox.classList.add('ragu');
        } else {
            navBox.classList.remove('ragu');
        }

        saveRaguStatus(currentIdx, raguStates[currentIdx]);
        updateNavigationButtons();
    }

    function markAsAnswered(index) {
        const nav = document.getElementById(`nav-box-${index}`);
        nav.classList.add("answered");
        nav.classList.remove("ragu");
        updateFinishButtonState();
    }

    function checkEssayAnswer(textarea, index) {
        const navBox = document.getElementById(`nav-box-${index}`);
        if (textarea.value.trim().length > 0) {
            navBox.classList.add('answered');
        } else {
            navBox.classList.remove('answered');
        }
        updateFinishButtonState();
    }

    function startTimer() {
        const targetTime = new Date("{{ $ujian->waktu_selesai }}").getTime();

        const interval = setInterval(function() {
            const now = new Date().getTime();
            const distance = targetTime - now;

            if (distance < 0) {
                clearInterval(interval);
                document.getElementById("countdownTimer").innerHTML = "WAKTU HABIS";

                intentionalExit = true;
                isFinishing = true;
                isReloading = true;

                document.getElementById("formUjian").submit();
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const format = (num) => String(num).padStart(2, '0');
            document.getElementById("countdownTimer").innerHTML = `${format(hours)}:${format(minutes)}:${format(seconds)}`;
        }, 1000);
    }

    function startClock() {
        function updateClock() {
            const now = new Date();
            const format = (num) => String(num).padStart(2, '0');
            const jam = format(now.getHours());
            const menit = format(now.getMinutes());
            const detik = format(now.getSeconds());
            document.getElementById("currentClock").innerHTML = `${jam}:${menit}:${detik}`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    }

    function confirmFinish() {
        const allAnswered = checkAllAnswered();
        if (!allAnswered) return;

        const now = new Date().getTime();
        const timeRequirementMet = now >= minSelesaiTime;

        if (!timeRequirementMet) {
            const jamStr = formatJam(minSelesaiTime);
            document.getElementById('timeNoticeMessage').innerHTML =
                `Semua soal telah terisi. Namun, ujian baru dapat diselesaikan pada pukul <strong>${jamStr} WIB</strong>.<br><br>Silakan periksa kembali jawaban Anda.`;
            document.getElementById('timeNoticeOverlay').classList.add('show');
            return;
        }

        document.getElementById("finishExamOverlay").classList.add("show");
    }

    function closeTimeNoticeModal() {
        document.getElementById('timeNoticeOverlay').classList.remove('show');
    }

    function closeFinishModal() {
        document.getElementById("finishExamOverlay").classList.remove("show");
    }

    function submitFinalExam() {
        intentionalExit = true;
        isFinishing = true;
        isReloading = true;

        document.getElementById("formUjian").submit();
    }

    // ===============================
    // AUTO SAVE
    // ===============================

    function saveAnswer(ujianId, soalId, pilihanJawabanId, jenisSoal) {
        fetch("{{ route('dashboard-siswa.ujian.autosave') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                ujian_id: ujianId,
                soal_id: soalId,
                pilihan_jawaban_id: pilihanJawabanId
            })
        })
        .then(r => r.json())
        .then(data => { if (!data.success) console.log(data.message); })
        .catch(err => console.error("AutoSave Error :", err));
    }

    function saveEssay(ujianId, soalId, jawaban) {
        fetch("{{ route('dashboard-siswa.ujian.autosave') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                ujian_id: ujianId,
                soal_id: soalId,
                jawaban_text: jawaban
            })
        });
    }

    function saveRaguStatus(index, status) {
        let soalId = document
            .getElementById(`card-soal-${index}`)
            .getAttribute('data-soal-id');

        fetch("{{ route('dashboard-siswa.ujian.autosave') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                ujian_id: {{ $ujian->id }},
                soal_id: soalId,
                is_ragu_ragu: status
            })
        });
    }

    // ==========================================================
    // SUBMIT OTOMATIS
    // ==========================================================
    async function submitExamAutomatically() {
        intentionalExit = true;
        isFinishing = true;
        isReloading = true;

        const soalCards = document.querySelectorAll(".soal-card");

        for (const card of soalCards) {
            const soalId = card.dataset.soalId;

            let payload = {
                ujian_id: {{ $ujian->id }},
                soal_id: soalId
            };

            const checked = card.querySelector("input[type=radio]:checked");
            if (checked) payload.pilihan_jawaban_id = checked.value;

            const textarea = card.querySelector("textarea");
            if (textarea) payload.jawaban_text = textarea.value;

            try {
                await fetch("{{ route('dashboard-siswa.ujian.autosave') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(payload)
                });
            } catch(e) {
                console.log(e);
            }
        }

        const form = document.getElementById("formUjian");
        let flag = document.getElementById("autoSubmitFlag");

        if (!flag) {
            flag = document.createElement("input");
            flag.type = "hidden";
            flag.name = "auto_submit";
            flag.value = "1";
            form.appendChild(flag);
        }

        form.submit();
    }
</script>
@endsection