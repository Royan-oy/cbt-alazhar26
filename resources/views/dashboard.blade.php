@extends('layouts.app')

@section('title', 'Dashboard Utama CBT')

@section('content')

<style>
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --primary-light: #eef2ff;
        --ink-900: #0f172a;
        --ink-700: #334155;
        --ink-500: #64748b;
        --ink-400: #94a3b8;
        --surface: #ffffff;
        --canvas: #f6f7fb;
        --border: #e7eaf3;
        --danger: #ef4444;
        --warning: #d97706;
        --success: #059669;
        --info: #0ea5e9;
        --shadow-sm: 0 1px 2px rgba(15,23,42,.04), 0 1px 3px rgba(15,23,42,.06);
        --shadow-md: 0 8px 24px -8px rgba(15,23,42,.10);
        --shadow-lg: 0 20px 45px -12px rgba(15,23,42,.14);
        --radius-lg: 22px;
        --radius-md: 16px;
        --radius-sm: 11px;
    }

    body { background: var(--canvas); }

    /* =========================================================
       HEADER SAMBUTAN
       ========================================================= */
    .page-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e2a4a 55%, #1e293b 100%);
        border-radius: var(--radius-lg);
        padding: 34px 36px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .page-header::after {
        content: '';
        position: absolute;
        width: 340px; height: 340px;
        border-radius: 50%;
        right: -70px; top: -100px;
        background: radial-gradient(circle, rgba(79,70,229,.28) 0%, rgba(79,70,229,0) 70%);
        pointer-events: none;
    }

    .page-header::before {
        content: '';
        position: absolute;
        width: 220px; height: 220px;
        border-radius: 50%;
        left: -60px; bottom: -90px;
        background: radial-gradient(circle, rgba(14,165,233,.18) 0%, rgba(14,165,233,0) 70%);
        pointer-events: none;
    }

    .page-header-badge {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .7px;
        text-transform: uppercase;
        background: rgba(79,70,229,.2);
        color: #a5b4fc;
        border: 1px solid rgba(165,180,252,.25);
        padding: 7px 14px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 14px;
    }

    .page-header h1 { font-size: 27px; position: relative; z-index: 1; }
    .page-header p { position: relative; z-index: 1; }

    .page-header-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #94a3b8;
        position: relative;
        z-index: 1;
    }

    /* =========================================================
       STAT CARD
       ========================================================= */
    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 20px;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 15px;
        height: 100%;
        transition: all .2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: #d7dcea;
    }

    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }

    .stat-card .stat-label {
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--ink-500);
        font-weight: 700;
        display: block;
        margin-bottom: 2px;
    }

    .stat-card h3 {
        color: var(--ink-900);
        font-weight: 800;
        margin: 0;
    }

    /* =========================================================
       CONTENT CARD
       ========================================================= */
    .content-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        padding: 24px;
    }

    .section-title {
        font-size: 14.5px;
        font-weight: 800;
        color: var(--ink-900);
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .section-title i {
        width: 30px; height: 30px;
        border-radius: 9px;
        background: var(--primary-light);
        color: var(--primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
    }

    h5.fw-bold, h6.fw-bold { color: var(--ink-900) !important; }

    /* =========================================================
       TABEL
       ========================================================= */
    .table-responsive { border-radius: var(--radius-md); overflow: hidden; }

    .table thead th {
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: var(--ink-500);
        background-color: var(--canvas);
        padding: 13px 16px;
        border-bottom: 1px solid var(--border);
        font-weight: 700;
    }

    .table tbody td {
        padding: 15px 16px;
        vertical-align: middle;
        border-color: #f1f3f9;
        font-size: 13.5px;
    }

    .table tbody tr { transition: background .15s ease; }
    .table tbody tr:hover { background-color: var(--primary-light); }

    /* =========================================================
       BADGE STATUS
       ========================================================= */
    .exam-status-badge {
        font-size: 10.5px;
        font-weight: 700;
        padding: 6px 13px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-belum   { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .status-berjalan{ background: #fff7ed; color: var(--warning); border: 1px solid #fde3b8; }
    .status-selesai { background: #ecfdf5; color: var(--success); border: 1px solid #a7f3d0; }

    /* =========================================================
       TOMBOL AKSI
       ========================================================= */
    .btn-exam-action {
        border-radius: 10px;
        padding: 9px 20px;
        font-weight: 700;
        font-size: 12px;
        border: none;
        transition: all .15s ease;
    }

    .btn-exam-action:hover:not(:disabled) { transform: translateY(-1px); }

    /* =========================================================
       QUICK ACTIONS
       ========================================================= */
    .quick-action-btn {
        border-radius: var(--radius-sm);
        padding: 15px 16px;
        font-weight: 700;
        font-size: 12.5px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 11px;
        transition: all .2s ease;
        border: 1px solid var(--border);
        color: var(--ink-700);
        background: var(--canvas);
        height: 100%;
    }

    .quick-action-btn:hover {
        background: var(--ink-900);
        color: #fff;
        border-color: var(--ink-900);
        transform: translateY(-2px);
        text-decoration: none;
        box-shadow: var(--shadow-md);
    }

    .quick-action-btn.primary {
        background: linear-gradient(135deg, var(--primary), #6366f1);
        color: #fff;
        border: none;
        box-shadow: 0 10px 22px -8px rgba(79,70,229,.4);
    }

    .quick-action-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px -8px rgba(79,70,229,.5);
        color: #fff;
    }

    .quick-icon-box {
        width: 38px; height: 38px;
        border-radius: 11px;
        background: rgba(255,255,255,.7);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: 14px;
    }

    .quick-action-btn.primary .quick-icon-box { background: rgba(255,255,255,.22); }

    /* =========================================================
       EMPTY STATE
       ========================================================= */
    .empty-state { text-align: center; padding: 46px 20px; }

    .empty-state i {
        font-size: 42px;
        color: #d1d7e6;
        margin-bottom: 14px;
        display: block;
    }

    .empty-state p:first-of-type { color: var(--ink-700); font-weight: 600; }

    /* =========================================================
       BADGE KEAMANAN
       ========================================================= */
    .secure-badge {
        background: #ecfdf5;
        color: var(--success);
        border: 1px solid #a7f3d0;
        font-size: 11.5px;
        font-weight: 700;
        padding: 9px 15px;
        border-radius: var(--radius-sm);
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    /* =========================================================
       URGENT CARD (Ujian sedang berlangsung)
       ========================================================= */
    .urgent-card {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: var(--radius-lg);
        padding: 24px 28px;
        color: #fff;
        box-shadow: 0 16px 32px -10px rgba(217,119,6,.4);
        position: relative;
        overflow: hidden;
    }

    .urgent-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(255,255,255,.15), transparent 60%);
    }

    .urgent-label {
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: .6px;
        font-weight: 800;
        color: rgba(255,255,255,.9);
        margin-bottom: 3px;
        position: relative;
    }

    .urgent-title { font-size: 18px; font-weight: 800; position: relative; }

    .urgent-pulse {
        width: 48px; height: 48px;
        border-radius: 50%;
        background: rgba(255,255,255,.22);
        display: flex; align-items: center; justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
        position: relative;
    }

    .urgent-pulse::after {
        content: '';
        position: absolute; inset: 0;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,.65);
        animation: pulseRing 1.6s infinite;
    }

    @keyframes pulseRing {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(1.65); opacity: 0; }
    }

    .pulse-badge { animation: pulseBadge 1.6s infinite; }

    @keyframes pulseBadge {
        0%, 100% { opacity: 1; }
        50% { opacity: .55; }
    }

    .legend-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
        box-shadow: 0 0 0 3px currentColor22;
    }

    /* =========================================================
       RESPONSIVE — TABLET & HP
       ========================================================= */
    @media (max-width: 991.98px) {
        .content-card { padding: 20px; }
    }

    @media (max-width: 767.98px) {
        .page-header { padding: 24px 22px; border-radius: var(--radius-md); text-align: left; }
        .page-header h1 { font-size: 20px !important; line-height: 1.35; }
        .page-header p { font-size: 12.5px !important; }
        .page-header-badge { font-size: 9.5px; padding: 6px 12px; }

        .stat-card { padding: 16px; gap: 12px; border-radius: var(--radius-sm); }
        .stat-icon { width: 42px; height: 42px; font-size: 16px; border-radius: 11px; }
        .stat-card h3 { font-size: 17px; }

        .content-card { padding: 16px; border-radius: var(--radius-md); }
        .section-title { font-size: 13.5px; }

        .urgent-card { padding: 18px 18px; border-radius: var(--radius-md); }
        .urgent-card .d-flex.justify-content-between { flex-direction: column; align-items: stretch !important; }
        .urgent-card a.btn-light { width: 100%; text-align: center; margin-top: 12px; }

        /* Tabel -> card di mobile */
        .table-responsive { overflow: visible; max-height: none !important; }
        .table-responsive table, .table-responsive thead, .table-responsive tbody,
        .table-responsive tr, .table-responsive th, .table-responsive td { display: block; }
        .table-responsive thead { display: none; }

        .table-responsive tr {
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            margin-bottom: 12px;
            padding: 4px 16px;
            background: var(--surface);
            box-shadow: var(--shadow-sm);
        }

        .table-responsive td {
            border: none !important;
            padding: 13px 0 !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right !important;
        }

        .table-responsive td:not(:last-child) { border-bottom: 1px dashed var(--border) !important; }

        .table-responsive td::before {
            content: attr(data-label);
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: .4px;
            font-weight: 700;
            color: var(--ink-400);
            text-align: left;
            margin-right: 15px;
        }

        /* Cell tanggal/waktu -> pindah ke baris baru di bawah label,
           supaya teks panjang tidak berdesakan sejajar dengan label kiri */
        .table-responsive td[data-label="Batas Waktu"] {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 4px;
        }

        .table-responsive td[data-label="Batas Waktu"]::before {
            margin-right: 0;
        }

        .cell-datetime {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            color: var(--ink-500);
            text-align: left;
            line-height: 1.5;
        }

        .cell-datetime i {
            color: var(--ink-400);
            font-size: 11px;
            flex-shrink: 0;
        }

        .quick-action-btn { padding: 13px 14px; font-size: 12px; }
        .quick-icon-box { width: 34px; height: 34px; font-size: 13px; }

        .secure-badge { width: 100%; justify-content: center; margin-top: 4px; }
    }

    @media (max-width: 480px) {
        .page-header h1 { font-size: 18px !important; }
        .stat-card { flex-direction: column; align-items: flex-start; text-align: left; }
    }

    /* =========================================================
       GRID AKSI PINTASAN
       ========================================================= */
    .quick-action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
    }

    .quick-action-btn {
        border-radius: var(--radius-sm);
        padding: 18px 16px;
        font-weight: 700;
        font-size: 12.5px;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
        transition: all .2s ease;
        border: 1px solid var(--border);
        color: var(--ink-700);
        background: var(--surface);
    }

    .quick-action-btn:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        text-decoration: none;
        border-color: transparent;
    }

    .quick-action-btn span {
        line-height: 1.35;
    }

    .quick-icon-box {
        width: 40px; height: 40px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    /* Varian warna per kategori aksi */
    .quick-action-btn.accent-blue .quick-icon-box    { background: #eff6ff; color: #2563eb; }
    .quick-action-btn.accent-emerald .quick-icon-box { background: #ecfdf5; color: var(--success); }
    .quick-action-btn.accent-amber .quick-icon-box   { background: #fff7ed; color: var(--warning); }
    .quick-action-btn.accent-violet .quick-icon-box  { background: #f5f3ff; color: #7c3aed; }
    .quick-action-btn.accent-slate .quick-icon-box   { background: #f1f5f9; color: var(--ink-500); }

    .quick-action-btn.accent-blue:hover    { background: #eff6ff; border-color: #bfdbfe; }
    .quick-action-btn.accent-emerald:hover { background: #ecfdf5; border-color: #a7f3d0; }
    .quick-action-btn.accent-amber:hover   { background: #fff7ed; border-color: #fde3b8; }
    .quick-action-btn.accent-violet:hover  { background: #f5f3ff; border-color: #ddd6fe; }
    .quick-action-btn.accent-slate:hover   { background: #f1f5f9; border-color: #cbd5e1; }

    .quick-action-btn.primary {
        background: linear-gradient(135deg, var(--primary), #6366f1);
        color: #fff;
        border: none;
        box-shadow: 0 10px 22px -8px rgba(79,70,229,.4);
    }

    .quick-action-btn.primary .quick-icon-box {
        background: rgba(255,255,255,.22);
        color: #fff;
    }

    .quick-action-btn.primary:hover {
        box-shadow: 0 14px 28px -8px rgba(79,70,229,.5);
        color: #fff;
    }

    /* =========================================================
       RESPONSIVE — quick action grid di HP
       ========================================================= */
    @media (max-width: 767.98px) {
        .quick-action-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .quick-action-btn {
            flex-direction: row;
            align-items: center;
            padding: 13px 14px;
            gap: 13px;
            font-size: 13px;
        }

        .quick-action-btn span {
            flex-grow: 1;
        }

        .quick-action-btn::after {
            content: '\f105'; /* chevron-right FontAwesome */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 12px;
            color: var(--ink-400);
            flex-shrink: 0;
        }

        .quick-action-btn.primary::after {
            color: rgba(255,255,255,.7);
        }

        .quick-icon-box {
            width: 38px; height: 38px;
            font-size: 14px;
            border-radius: 11px;
            flex-shrink: 0;
        }
    }

    @media (max-width: 400px) {
        .quick-action-btn { padding: 12px 13px; font-size: 12.5px; }
        .quick-icon-box { width: 34px; height: 34px; font-size: 13px; }
    }

    /* =========================================================
       STAT CARD — versi diperkaya (ikon besar + caption)
       ========================================================= */
    .stat-card {
        position: relative;
        overflow: hidden;
        gap: 16px;
    }

    .stat-card .stat-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        font-size: 20px;
    }

    .stat-card .stat-body {
        min-width: 0;
        flex: 1;
    }

    .stat-card .stat-label {
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--ink-500);
        font-weight: 700;
        display: block;
        margin-bottom: 3px;
    }

    .stat-card h3 {
        color: var(--ink-900);
        font-weight: 800;
        margin: 0;
        font-size: 22px;
        line-height: 1.2;
    }

    .stat-card h3 small {
        font-size: 12px;
        font-weight: 600;
        color: var(--ink-400);
        margin-left: 2px;
    }

    .stat-card .stat-caption {
        display: block;
        font-size: 10.5px;
        color: var(--ink-400);
        margin-top: 3px;
        font-weight: 500;
    }

    /* Aksen dekoratif lingkaran di pojok kanan */
    .stat-card::after {
        content: '';
        position: absolute;
        width: 90px; height: 90px;
        border-radius: 50%;
        right: -30px; top: -30px;
        opacity: .06;
        pointer-events: none;
    }

    .stat-card-accent-blue .stat-icon { background: #eff6ff; color: #2563eb; }
    .stat-card-accent-blue::after { background: #2563eb; }

    .stat-card-accent-emerald .stat-icon { background: #ecfdf5; color: var(--success); }
    .stat-card-accent-emerald::after { background: var(--success); }

    /* =========================================================
       RESPONSIVE — HP: ikon & teks dikecilkan proporsional
       ========================================================= */
    @media (max-width: 767.98px) {
        .stat-card { padding: 14px; gap: 11px; }
        .stat-card .stat-icon { width: 42px; height: 42px; font-size: 16px; border-radius: 12px; }
        .stat-card h3 { font-size: 18px; }
        .stat-card .stat-label { font-size: 9.5px; }
        .stat-card .stat-caption { font-size: 9.5px; }
    }

    @media (max-width: 400px) {
        .stat-card { flex-direction: column; align-items: flex-start; text-align: left; }
        .stat-card .stat-icon { margin-bottom: 4px; }
    }
</style>

<div class="container-fluid px-0">

    {{-- Header Sambutan --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <span class="page-header-badge">
                    <i class="fa-solid fa-circle-check"></i>
                    Portal Akademik Aktif
                </span>

                <h1 class="fw-bold text-white mb-2" style="font-size: 26px;">
                    {{ $sapaanWaktu = now()->format('H') < 11 ? 'Selamat Pagi' : (now()->format('H') < 15 ? 'Selamat Siang' : (now()->format('H') < 18 ? 'Selamat Sore' : 'Selamat Malam')) }},
                    {{ Auth::user()->nama }} 👋
                </h1>

                <p class="mb-2" style="font-size:14px;color:#cbd5e1;line-height:1.8;">
                    Anda login sebagai
                    <strong class="text-info">
                        @switch(Auth::user()->role)
                            @case('super_admin') Super Administrator @break
                            @case('admin_jenjang') Administrator Jenjang @break
                            @case('guru') Guru @break
                            @case('siswa') Siswa @break
                            @default Pengguna
                        @endswitch
                    </strong>
                    di <strong class="text-white">CBT Smart Online</strong> Sekolah Islam Al Azhar Pekalongan.
                </p>

                <div class="page-header-meta">
                    <i class="fa-regular fa-calendar"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>  
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SUPER ADMIN --}}
    {{-- ============================================================ --}}
    @if(Auth::user()->role == 'super_admin')
    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Overview Sistem Master</h5>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-server"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Total Jenjang</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ $total_jenjang ?? 0 }} <span class="fs-6 text-muted fw-normal">Unit</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fa-solid fa-users"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Total Pengguna</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ number_format($total_users ?? 0) }} <span class="fs-6 text-muted fw-normal">Akun</span></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- ADMIN JENJANG --}}
    {{-- ============================================================ --}}
    @if(Auth::user()->role == 'admin_jenjang')
    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Statistik Data Pendidikan</h5>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-landmark"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Total Kelas</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ $total_kelas ?? 0 }} <span class="fs-6 text-muted fw-normal">Ruang</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fa-solid fa-graduation-cap"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Siswa Terdaftar</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ $total_siswa ?? 0 }} <span class="fs-6 text-muted fw-normal">Anak</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fa-solid fa-book"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Mata Pelajaran</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ $total_mapel ?? 0 }} <span class="fs-6 text-muted fw-normal">Mapel</span></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(Auth::user()->role == 'guru')
    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Ringkasan Tugas Mengajar</h5>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-6">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-folder-open"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Bank Soal Anda</span>
                    <h3 class="mb-0 fw-bold text-dark">{{ $total_bank_soal ?? 0 }} <span class="fs-6 text-muted fw-normal">Paket</span></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isWaliKelas ?? false)
    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Pemantauan Ruang Kelas Anda</h5>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-6">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-satellite-dish"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Siswa Sedang Ujian</span>
                    <h3 class="mb-0 fw-bold text-warning">{{ $siswa_ujian ?? 0 }} <span class="fs-6 text-muted fw-normal">Siswa</span></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-6">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fa-solid fa-clipboard-user"></i></div>
                <div>
                    <span class="text-muted small fw-semibold d-block text-uppercase" style="font-size: 10px;">Siswa Sudah Selesai</span>
                    <h3 class="mb-0 fw-bold text-success">{{ $siswa_selesai ?? 0 }} <span class="fs-6 text-muted fw-normal">Siswa</span></h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- SISWA -- REDESIGN --}}
    {{-- ============================================================ --}}
    @if(Auth::user()->role == 'siswa')

    @php
        $ujianBerjalan = collect($ujian_hari_ini ?? [])->firstWhere('status_siswa', 'Sedang Mengerjakan');
        $jumlahBelum = collect($ujian_hari_ini ?? [])->where('status_siswa', 'Belum Dikerjakan')->count();
        $jumlahBerjalan = collect($ujian_hari_ini ?? [])->where('status_siswa', 'Sedang Mengerjakan')->count();
        $jumlahSelesai = collect($ujian_hari_ini ?? [])->where('status_siswa', 'Selesai')->count();
    @endphp

    @if($ujianBerjalan)
    <div class="row mb-4">
        <div class="col-12">
            <div class="urgent-card">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="urgent-pulse">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <div>
                            <div class="urgent-label">Sedang Berlangsung</div>
                            <div class="urgent-title">{{ $ujianBerjalan->nama_ujian }}</div>
                            <small class="text-white-50">Batas waktu {{ \Carbon\Carbon::parse($ujianBerjalan->waktu_selesai)->format('H:i') }} WIB </small>
                        </div>
                    </div>
                    <a href="{{ route('dashboard-siswa.ujian.mulai',$ujianBerjalan->id) }}"
                    class="btn btn-light fw-bold px-4 py-2 rounded-3 position-relative"
                    style="z-index:999; pointer-events:auto;">
                        <i class="fa-solid fa-arrow-right me-2"></i>
                        Lanjutkan Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <h5 class="fw-bold text-dark mb-3" style="font-size: 14px; letter-spacing: 0.3px;">Ringkasan Evaluasi Anda</h5>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="stat-card stat-card-accent-blue">
                <div class="stat-icon"><i class="fa-solid fa-pen-to-square"></i></div>
                <div class="stat-body">
                    <span class="stat-label">Ujian Tersedia</span>
                    <h3>{{ count($ujian_hari_ini ?? []) }} <small>Hari Ini</small></h3>
                    <span class="stat-caption">Jadwal aktif untuk Anda</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card stat-card-accent-emerald">
                <div class="stat-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <div class="stat-body">
                    <span class="stat-label">Ujian Diselesaikan</span>
                    <h3>{{ $riwayat_ujian ?? 0 }} <small>Riwayat</small></h3>
                    <span class="stat-caption">Total sepanjang waktu</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-8">
            <div class="content-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div class="section-title">
                        <i class="fa-solid fa-calendar-day text-primary"></i>
                        Jadwal Ujian
                    </div>
                    <a href="{{ route('dashboard-siswa.ujian-hari-ini') }}" class="text-primary text-decoration-none small fw-semibold">
                        Lihat semua <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>

                @if(isset($ujian_hari_ini) && count($ujian_hari_ini) > 0)
                    <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Ujian</th>
                                    <th>Batas Waktu</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ujian_hari_ini as $ujian)
                                    <tr>
                                        <td data-label="Nama Ujian">
                                            <div class="text-end">
                                                <div class="fw-bold text-dark">{{ $ujian->nama_ujian }}</div>
                                                <small class="text-muted">Durasi: {{ $ujian->durasi_menit }} Menit</small>
                                            </div>
                                        </td>
                                        <td class="text-muted text-end" data-label="Batas Waktu">
                                            <span class="cell-datetime">
                                                <i class="fa-regular fa-clock"></i>
                                                {{ $ujian->display_tanggal }}
                                            </span>
                                        </td>
                                        <td class="text-center" data-label="Status">
                                            @if($ujian->status_waktu == 'akan_datang')
                                                <span class="exam-status-badge status-belum"><i class="fa-regular fa-calendar-days"></i> Akan Datang</span>
                                            @elseif($ujian->status_siswa == 'Belum Dikerjakan')
                                                <span class="exam-status-badge status-berjalan"><i class="fa-solid fa-hourglass-start"></i> Belum Dikerjakan</span>
                                            @elseif($ujian->status_siswa == 'Sedang Mengerjakan')
                                                <span class="exam-status-badge status-berjalan pulse-badge"><i class="fa-solid fa-spinner"></i> Sedang Mengerjakan</span>
                                            @else
                                                <span class="exam-status-badge status-selesai"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                                            @endif
                                        </td>
                                        <td class="text-end" data-label="Aksi">

                                            @if($ujian->status_waktu == 'akan_datang')

                                                <button class="btn btn-secondary text-white btn-exam-action" disabled>
                                                    <i class="fa-regular fa-clock me-1"></i>
                                                    Belum Mulai
                                                </button>

                                            @elseif($ujian->status_waktu == 'selesai')

                                                <button class="btn btn-danger text-white btn-exam-action" disabled>
                                                    <i class="fa-solid fa-hourglass-end me-1"></i>
                                                    Berakhir
                                                </button>

                                            @else

                                                @if($ujian->status_siswa == 'Belum Dikerjakan')

                                                    <a href="{{ route('dashboard-siswa.ujian.mulai', $ujian->id) }}"
                                                    class="btn btn-primary btn-exam-action position-relative"
                                                    style="z-index:10;">
                                                        <i class="fa-solid fa-play me-1"></i>
                                                        Mulai
                                                    </a>

                                                @elseif($ujian->status_siswa == 'Sedang Mengerjakan')

                                                    <a href="{{ route('dashboard-siswa.ujian.mulai', $ujian->id) }}"
                                                    class="btn btn-warning text-dark btn-exam-action position-relative"
                                                    style="z-index:10;">
                                                        <i class="fa-solid fa-arrow-rotate-right me-1"></i>
                                                        Lanjutkan
                                                    </a>

                                                @else

                                                    <button class="btn btn-light border text-muted btn-exam-action" disabled>
                                                        <i class="fa-solid fa-circle-check text-success me-1"></i>
                                                        Selesai
                                                    </button>

                                                @endif

                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fa-regular fa-calendar-check"></i>
                        <p class="text-secondary small mb-0 fw-semibold">Alhamdulillah, tidak ada jadwal ujian aktif untuk Anda saat ini.</p>
                        <p class="text-muted small mb-0">Jadwal ujian baru akan muncul otomatis di sini saat sudah waktunya.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="content-card h-100">
                <div class="section-title mb-3">
                    <i class="fa-solid fa-chart-pie text-primary"></i>
                    Status Hari Ini
                </div>

                @if(count($ujian_hari_ini ?? []) > 0)
                    <div style="position: relative; height: 180px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="d-flex flex-column gap-2 mt-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="legend-dot" style="background:#2563eb; color:#2563eb;"></span>
                            <span class="flex-grow-1 small ms-2">Belum Dikerjakan</span>
                            <strong class="small">{{ $jumlahBelum }}</strong>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="legend-dot" style="background:#d97706;"></span>
                            <span class="flex-grow-1 small ms-2">Sedang Berjalan</span>
                            <strong class="small">{{ $jumlahBerjalan }}</strong>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="legend-dot" style="background:#059669;"></span>
                            <span class="flex-grow-1 small ms-2">Selesai</span>
                            <strong class="small">{{ $jumlahSelesai }}</strong>
                        </div>
                    </div>
                @else
                    <div class="empty-state py-4">
                        <i class="fa-regular fa-face-smile"></i>
                        <p class="text-muted small mb-0">Belum ada data untuk ditampilkan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @endif

    {{-- ============================================================ --}}
    {{-- AKSI PINTASAN --}}
    {{-- ============================================================ --}}
    <div class="row">
        <div class="col-12">
            <div class="content-card">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div class="section-title">
                        <i class="fa-solid fa-bolt"></i>
                        Aksi Pintasan
                    </div>
                    <span class="secure-badge">
                        <i class="fa-solid fa-shield-halved"></i> Sesi Enkripsi Terlindungi
                    </span>
                </div>

                <div class="quick-action-grid">

                    {{-- ================= SUPER ADMIN ================= --}}
                    @if(Auth::user()->role == 'super_admin')
                        <a href="{{ route('jenjang.index') }}" class="quick-action-btn accent-slate">
                            <div class="quick-icon-box"><i class="fa-solid fa-layer-group"></i></div>
                            <span>Kelola Jenjang</span>
                        </a>
                        <a href="{{ route('tahun-ajaran.index') }}" class="quick-action-btn accent-blue">
                            <div class="quick-icon-box"><i class="fa-solid fa-calendar-days"></i></div>
                            <span>Tahun Ajaran</span>
                        </a>
                        <a href="{{ route('admin-jenjang.create') }}" class="quick-action-btn accent-violet">
                            <div class="quick-icon-box"><i class="fa-solid fa-user-shield"></i></div>
                            <span>Tambah Admin Jenjang</span>
                        </a>
                        <a href="{{ route('ujian.index') }}" class="quick-action-btn accent-amber">
                            <div class="quick-icon-box"><i class="fa-solid fa-calendar-check"></i></div>
                            <span>Jadwal Ujian</span>
                        </a>
                    @endif

                    {{-- ================= ADMIN JENJANG ================= --}}
                    @if(Auth::user()->role == 'admin_jenjang')
                        <a href="{{ route('siswa.create') }}" class="quick-action-btn primary">
                            <div class="quick-icon-box"><i class="fa-solid fa-user-plus"></i></div>
                            <span>Tambah Siswa Baru</span>
                        </a>
                        <a href="{{ route('kelas.index') }}" class="quick-action-btn accent-blue">
                            <div class="quick-icon-box"><i class="fa-solid fa-door-open"></i></div>
                            <span>Kelola Kelas</span>
                        </a>
                        <a href="{{ route('bank-soal.index') }}" class="quick-action-btn accent-emerald">
                            <div class="quick-icon-box"><i class="fa-solid fa-folder-open"></i></div>
                            <span>Bank Soal</span>
                        </a>
                        <a href="{{ route('ujian.index') }}" class="quick-action-btn accent-amber">
                            <div class="quick-icon-box"><i class="fa-solid fa-calendar-check"></i></div>
                            <span>Jadwal Ujian</span>
                        </a>
                    @endif

                    {{-- ================= GURU ================= --}}
                    @if(Auth::user()->role == 'guru')
                        <a href="{{ route('dashboard-guru.bank-soal.index') }}" class="quick-action-btn accent-emerald">
                            <div class="quick-icon-box"><i class="fa-solid fa-book-open"></i></div>
                            <span>Bank Soal Anda</span>
                        </a>
                        <a href="{{ route('dashboard-guru.jadwal-ujian.index') }}" class="quick-action-btn accent-amber">
                            <div class="quick-icon-box"><i class="fa-solid fa-calendar-check"></i></div>
                            <span>Jadwal Ujian</span>
                        </a>
                        <a href="{{ route('dashboard-guru.nilai-siswa.index') }}" class="quick-action-btn accent-blue">
                            <div class="quick-icon-box"><i class="fa-solid fa-square-poll-vertical"></i></div>
                            <span>Nilai Siswa</span>
                        </a>
                    @endif

                    {{-- ================= WALI KELAS (tambahan untuk guru) ================= --}}
                    @if($isWaliKelas ?? false)
                        <a href="{{ route('dashboard-guru.wali-kelas.monitoring-siswa') }}" class="quick-action-btn accent-violet">
                            <div class="quick-icon-box"><i class="fa-solid fa-chart-line"></i></div>
                            <span>Monitoring Siswa</span>
                        </a>
                        <a href="{{ route('dashboard-guru.wali-kelas.rekap-nilai') }}" class="quick-action-btn accent-violet">
                            <div class="quick-icon-box"><i class="fa-solid fa-file-export"></i></div>
                            <span>Rekap Nilai Kelas</span>
                        </a>
                    @endif

                    {{-- ================= SISWA ================= --}}
                    @if(Auth::user()->role == 'siswa')
                        <a href="{{ route('dashboard-siswa.scan-token.index') }}" class="quick-action-btn primary">
                            <div class="quick-icon-box"><i class="fa-solid fa-qrcode"></i></div>
                            <span>Scan Token Ujian</span>
                        </a>
                        <a href="{{ route('dashboard-siswa.ujian-hari-ini') }}" class="quick-action-btn accent-blue">
                            <div class="quick-icon-box"><i class="fa-solid fa-calendar-day"></i></div>
                            <span>Jadwal Ujian</span>
                        </a>
                        <a href="{{ route('pengaturan-akun.index') }}" class="quick-action-btn accent-slate">
                            <div class="quick-icon-box"><i class="fa-solid fa-user"></i></div>
                            <span>Profil Saya</span>
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>

@if(Auth::user()->role == 'siswa' && count($ujian_hari_ini ?? []) > 0)
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('statusChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Belum Dikerjakan', 'Sedang Berjalan', 'Selesai'],
            datasets: [{
                data: [{{ $jumlahBelum }}, {{ $jumlahBerjalan }}, {{ $jumlahSelesai }}],
                backgroundColor: ['#2563eb', '#d97706', '#059669'],
                borderWidth: 0,
                cutout: '72%',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            }
        }
    });
});
</script>
@endif

@endsection