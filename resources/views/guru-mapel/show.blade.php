@extends('layouts.app')

@section('title', 'Detail Guru Mata Pelajaran')

@section('content')

<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --accent-hover: #0284c7;
        --bg-light: #f8fafc;
        --border-color: #e2e8f0;
        --text-main: #334155;
        --text-muted: #64748b;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --radius-lg: 16px;
        --radius-xl: 24px;
    }

    /* =========================
       GLOBAL & TYPOGRAPHY
    ========================= */
    body {
        color: var(--text-main);
    }

    .text-muted {
        color: var(--text-muted) !important;
    }

    /* =========================
       MAIN CARD
    ========================= */
    .detail-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 32px;
        box-shadow: var(--shadow-sm);
    }

    /* =========================
       HEADER PROFILE
    ========================= */
    .profile-header {
        background: linear-gradient(135deg, var(--primary-dark) 0%, #172554 100%);
        border-radius: var(--radius-xl);
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }

    .profile-header::before {
        content: "";
        position: absolute;
        left: -10%;
        top: -50%;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.15), transparent 70%);
    }

    .profile-header::after {
        content: "";
        position: absolute;
        width: 350px;
        height: 350px;
        right: -10%;
        bottom: -50%;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.25), transparent 70%);
    }

    .profile-content {
        position: relative;
        z-index: 2;
    }

    .avatar-detail {
        width: 90px;
        height: 90px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: 800;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: var(--shadow-sm);
        flex-shrink: 0;
    }

    /* =========================
       STAT CARD
    ========================= */
    .stat-mini {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-mini:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: #cbd5e1;
    }

    .stat-mini i {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(14, 165, 233, 0.1);
        color: var(--accent-blue);
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-mini h5 {
        margin: 0;
        font-weight: 800;
        color: var(--primary-dark);
        font-size: 1.25rem;
    }

    .stat-mini .text-muted {
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 4px;
        display: block;
    }

    /* =========================
       MAPEL CARD
    ========================= */
    .mapel-card {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 24px;
        background: white;
        transition: all 0.3s ease;
    }

    .mapel-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: #bae6fd;
    }

    .mapel-title {
        font-weight: 800;
        font-size: 18px;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
    }

    .year-badge {
        background: var(--bg-light);
        color: var(--text-muted);
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid var(--border-color);
        display: inline-block;
    }

    /* =========================
       KELAS BADGE
    ========================= */
    .badge-kelas {
        background: #eff6ff;
        color: var(--accent-hover);
        border: 1px solid #bfdbfe;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .badge-kelas:hover {
        background: #dbeafe;
    }

    .btn-edit-detail {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    /* =========================
       MEDIA QUERIES (RESPONSIVE)
    ========================= */
    @media (max-width: 768px) {
        .container-fluid.py-4 { padding-left: 12px; padding-right: 12px; }

        /* Header Halaman */
        .page-header-title {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 14px;
        }

        .page-header-title h3 {
            font-size: 1.15rem;
        }

        .page-header-title p {
            font-size: 0.85rem;
        }

        .page-header-title a {
            width: 100%;
            text-align: center;
            font-size: 0.875rem;
        }

        /* Kartu Profil Utama */
        .profile-header {
            padding: 26px 18px;
            border-radius: 20px;
            text-align: center;
        }

        .profile-content {
            flex-direction: column;
            justify-content: center;
            gap: 12px !important;
        }

        .profile-content h2 {
            font-size: 1.35rem;
        }

        .avatar-detail {
            margin: 0 auto;
            width: 68px;
            height: 68px;
            font-size: 26px;
            border-radius: 14px;
        }

        /* Kartu Statistik */
        .row.g-3.mb-4 { row-gap: 12px !important; }

        .stat-mini {
            padding: 14px;
            gap: 10px;
            border-radius: 14px;
        }

        .stat-mini i {
            width: 38px;
            height: 38px;
            font-size: 15px;
            border-radius: 10px;
        }

        .stat-mini h5 {
            font-size: 1.05rem;
        }

        .stat-mini .text-muted {
            font-size: 0.68rem;
        }

        /* Kartu Mata Pelajaran */
        .detail-card {
            padding: 16px;
            border-radius: 20px;
        }

        .mapel-card {
            padding: 14px;
            border-radius: 14px;
        }

        .mapel-card .d-flex.justify-content-between.align-items-start {
            flex-direction: column;
            gap: 12px;
        }

        .mapel-title {
            font-size: 1rem;
        }

        /* Badges / Label */
        .year-badge,
        .badge-kelas {
            font-size: 11px;
            padding: 5px 10px;
        }

        .btn-edit-detail {
            width: 100%;
            height: 40px;
            margin-top: 4px;
            border-radius: 10px;
        }
    }
</style>

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 page-header-title">
        <div>
            <h3 class="fw-bold mb-1 text-dark">
                <i class="fa-solid fa-id-card text-primary me-2"></i>
                Detail Guru Mata Pelajaran
            </h3>
            <p class="text-muted mb-0">
                Informasi lengkap penugasan dan kelas yang diajar.
            </p>
        </div>
        <a href="{{ route('guru-mapel.index') }}" class="btn btn-white border bg-white rounded-pill px-4 shadow-sm text-dark font-weight-bold">
            <i class="fa-solid fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    {{-- Profile --}}
    <div class="profile-header mb-4">
        <div class="d-flex align-items-center gap-4 profile-content">
            <div class="avatar-detail">
                {{ strtoupper(substr($guru->nama, 0, 1)) }}
            </div>
            <div>
                <h2 class="fw-bold mb-2">{{ $guru->nama }}</h2>
                <div class="opacity-75 d-inline-flex align-items-center bg-white bg-opacity-10 px-3 py-1 rounded-pill">
                    <i class="fa-solid fa-school me-2"></i>
                    {{ optional($guru->jenjang)->nama_jenjang ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="stat-mini">
                <i class="fa-solid fa-book"></i>
                <div>
                    <span class="text-muted">Total Mapel</span>
                    <h5>{{ $guruMapels->count() }}</h5>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="stat-mini">
                <i class="fa-solid fa-users"></i>
                <div>
                    <span class="text-muted">Total Kelas</span>
                    <h5>{{ $guruMapels->pluck('kelas')->collapse()->unique('id')->count() }}</h5>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-mini">
                <i class="fa-solid fa-calendar"></i>
                <div>
                    <span class="text-muted">Tahun Ajaran</span>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h5 class="mb-0">{{ optional($guruMapels->first()->tahunAjaran)->nama_tahun ?? '-' }}</h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill border border-primary-subtle">
                            {{ optional($guruMapels->first()->tahunAjaran)->semester_label ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Penugasan --}}
    <div class="detail-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="fa-solid fa-layer-group text-primary me-2"></i>
                Daftar Penugasan
            </h5>
        </div>

        @forelse($guruMapels as $item)
            <div class="mapel-card mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="mapel-title">
                            <i class="fa-solid fa-book-open text-primary me-2"></i>
                            {{ $item->mataPelajaran->nama_mapel }}
                        </div>
                        <div class="mt-2">
                            <span class="year-badge">
                                {{ optional($item->tahunAjaran)->nama_tahun }} - {{ ucfirst(optional($item->tahunAjaran)->semester) }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('guru-mapel.edit', $item->id) }}" class="btn btn-outline-primary btn-edit-detail shadow-sm">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                </div>

                <hr class="text-muted opacity-25 my-3">

                <div>
                    <div class="small fw-bold text-muted mb-3" style="letter-spacing: 0.5px;">
                        KELAS YANG DIAJAR
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($item->kelas as $kelas)
                            <span class="badge-kelas">
                                {{ optional($kelas->tingkat)->nama_tingkat }} - {{ $kelas->nama_kelas }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-folder-open fa-2x text-muted"></i>
                </div>
                <h6 class="fw-bold text-secondary">Belum ada penugasan</h6>
                <p class="text-muted small">Guru ini belum memiliki jadwal mata pelajaran saat ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection