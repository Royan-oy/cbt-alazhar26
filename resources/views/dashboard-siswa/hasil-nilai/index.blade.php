@extends('layouts.app')

@section('title', 'Hasil Nilai Ujian')

@section('content')
<style>
    :root {
        --sb-bg: #0f172a;
        --sb-card: #1e293b;
        --sb-text-muted: #64748b;
        --sb-text-active: #38bdf8;

        --primary: #0ea5e9;
        --primary-dark: #0284c7;
        --primary-light: #eff8ff;

        --ink-900: #0f172a;
        --ink-700: #334155;
        --ink-500: #64748b;
        --ink-400: #94a3b8;
        --surface: #ffffff;
        --border: #e5e9f2;
        --card-shadow: 0 10px 30px -8px rgba(15,23,42,.05), 0 4px 12px -6px rgba(15,23,42,.04);
        --hover-shadow: 0 22px 44px -10px rgba(14,165,233,.16);
    }

    .cbt-header {
        background: linear-gradient(135deg, var(--sb-bg) 0%, var(--sb-card) 60%, #1e293b 100%);
        padding: 32px;
        border-radius: 22px;
        color: #fff;
        margin-bottom: 26px;
        position: relative;
        overflow: hidden;
    }

    .cbt-header::after {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(56,189,248,.22) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .info-card {
        background: var(--surface);
        border-radius: 18px;
        border: 1px solid var(--border);
        padding: 22px;
        margin-bottom: 26px;
        box-shadow: var(--card-shadow);
    }

    .info-divider { width: 1px; background-color: var(--border); }

    .stat-card-v2 {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 20px;
        box-shadow: var(--card-shadow);
        height: 100%;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card-v2:hover {
        transform: translateY(-2px);
        box-shadow: var(--hover-shadow);
    }

    .stat-icon-box {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .nilai-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid var(--border);
        box-shadow: var(--card-shadow);
        transition: all 0.25s ease;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .nilai-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--hover-shadow);
        border-color: rgba(14, 165, 233, 0.3);
    }

    .nilai-header {
        padding: 20px 24px 16px 24px;
        border-bottom: 1px dashed var(--border);
    }

    .nilai-body {
        padding: 20px 24px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .nilai-big-badge {
        font-size: 2.2rem;
        font-weight: 800;
        letter-spacing: -1px;
        line-height: 1;
    }

    .badge-tuntas {
        background-color: #dcfce7;
        color: #15803d;
        border: 1px solid rgba(22, 163, 74, 0.2);
    }

    .badge-belum-tuntas {
        background-color: #ffe4e6;
        color: #be123c;
        border: 1px solid rgba(225, 29, 72, 0.2);
    }

    .badge-menunggu {
        background-color: #fef3c7;
        color: #b45309;
        border: 1px solid rgba(217, 119, 6, 0.2);
    }

    .filter-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid var(--border);
        padding: 16px 20px;
        margin-bottom: 24px;
        box-shadow: var(--card-shadow);
    }
</style>

<div class="container-fluid px-0 py-2">

    {{-- HEADER --}}
    <div class="cbt-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 position-relative" style="z-index: 1;">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-square-poll-vertical me-1"></i> HASIL PENILAIAN
                </span>
                <h3 class="fw-bold mb-1">Hasil Nilai Ujian Siswa</h3>
                <p class="mb-0 text-white-50">Daftar nilai ujian resmi yang telah dipublikasikan oleh pihak sekolah.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-white bg-opacity-10 text-white px-3 py-2 rounded-3 fs-7 fw-normal border border-white border-opacity-10">
                    <i class="fa-regular fa-calendar me-1"></i> {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- INFO SISWA --}}
    <div class="info-card">
        <div class="row align-items-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="d-flex align-items-center">
                    <div class="bg-light p-2 rounded-3 me-3" style="color: var(--primary-dark);">
                        <i class="fa-solid fa-user-graduate fa-lg"></i>
                    </div>
                    <div>
                        <small class="text-uppercase fw-bold d-block" style="font-size: 10px; letter-spacing: 0.5px; color: var(--ink-500);">Nama Siswa</small>
                        <h6 class="fw-bold mb-0" style="color: var(--ink-900);">{{ $siswa->nama }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-1 d-none d-md-block text-center">
                <div class="info-divider d-inline-block" style="height: 40px;"></div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="d-flex align-items-center">
                    <div class="bg-light p-2 rounded-3 me-3 text-success">
                        <i class="fa-solid fa-school fa-lg"></i>
                    </div>
                    <div>
                        <small class="text-uppercase fw-bold d-block" style="font-size: 10px; letter-spacing: 0.5px; color: var(--ink-500);">Kelas Aktif</small>
                        <h6 class="fw-bold mb-0" style="color: var(--ink-900);">{{ optional($kelasAktif)->nama_kelas ?? '-' }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-1 d-none d-md-block text-center">
                <div class="info-divider d-inline-block" style="height: 40px;"></div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div class="bg-light p-2 rounded-3 me-3 text-primary">
                        <i class="fa-solid fa-clipboard-check fa-lg"></i>
                    </div>
                    <div>
                        <small class="text-uppercase fw-bold d-block" style="font-size: 10px; letter-spacing: 0.5px; color: var(--ink-500);">Total Dipublish</small>
                        <h6 class="fw-bold mb-0" style="color: var(--primary-dark);">{{ $stats->total }} Ujian</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- WIDGET STATISTIK --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card-v2">
                <div class="stat-icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-calculator"></i>
                </div>
                <div>
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Rata-rata Nilai</small>
                    <h4 class="fw-bold mb-0" style="color: var(--ink-900);">{{ $stats->rata_rata }}</h4>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card-v2">
                <div class="stat-icon-box bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Tuntas (>= KKM)</small>
                    <h4 class="fw-bold mb-0 text-success">{{ $stats->tuntas }}</h4>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card-v2">
                <div class="stat-icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <div>
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Belum Tuntas</small>
                    <h4 class="fw-bold mb-0 text-danger">{{ $stats->belum_tuntas }}</h4>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card-v2">
                <div class="stat-icon-box bg-info bg-opacity-10 text-info">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div>
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Ujian Dinilai</small>
                    <h4 class="fw-bold mb-0" style="color: var(--ink-900);">{{ $stats->tuntas + $stats->belum_tuntas }} / {{ $stats->total }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <div class="filter-card">
        <form action="{{ route('dashboard-siswa.hasil-nilai.index') }}" method="GET">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted rounded-start-3">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 rounded-end-3" 
                               placeholder="Cari nama ujian atau mata pelajaran..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="jenis_ujian" class="form-select rounded-3">
                        <option value="">-- Semua Jenis Ujian --</option>
                        @foreach($jenisUjians as $jenis)
                            <option value="{{ $jenis->id }}" {{ request('jenis_ujian') == $jenis->id ? 'selected' : '' }}>
                                {{ $jenis->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="tahun_ajaran" class="form-select rounded-3">
                        <option value="">-- Semua Tahun Ajaran --</option>
                        @foreach($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" {{ request('tahun_ajaran') == $ta->id ? 'selected' : '' }}>
                                {{ $ta->nama_tahun }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3" title="Filter Data">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    @if(request()->filled('search') || request()->filled('jenis_ujian') || request()->filled('tahun_ajaran'))
                        <a href="{{ route('dashboard-siswa.hasil-nilai.index') }}" class="btn btn-light border rounded-3" title="Reset Filter">
                            <i class="fa-solid fa-rotate"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- LIST NILAI UJIAN --}}
    @if($ujians->count() > 0)
        <div class="row g-4">
            @foreach($ujians as $ujian)
                <div class="col-md-6 col-xl-4">
                    <div class="nilai-card">
                        
                        <div class="nilai-header">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <h6 class="fw-bold mb-0 text-truncate" style="max-width: 220px; color: var(--ink-900);" title="{{ $ujian->nama_ujian }}">
                                    {{ $ujian->nama_ujian }}
                                </h6>
                                <span class="badge bg-light text-secondary border px-2 py-1 rounded-2" style="font-size: 11px;">
                                    {{ optional($ujian->jenisUjian)->nama ?? 'Ujian' }}
                                </span>
                            </div>
                            <div class="text-muted small">
                                <i class="fa-solid fa-book-bookmark me-1" style="color: var(--primary);"></i>
                                {{ optional(optional($ujian->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}
                            </div>
                        </div>

                        <div class="nilai-body">

                            {{-- DISPLAY NILAI ATAU STATUS KOREKSI --}}
                            <div class="p-3 rounded-4 mb-3 text-center" style="background-color: #f8fafc; border: 1px solid var(--border);">
                                @if(!$ujian->has_submitted)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3 py-2 rounded-pill fs-7 fw-semibold">
                                        <i class="fa-solid fa-circle-minus me-1"></i> Tidak Mengikuti / Belum Submit
                                    </span>
                                    <div class="text-muted small mt-2">Anda tidak terdata mengumpulkan ujian ini.</div>

                                @elseif($ujian->status_penilaian === 'menunggu')
                                    <span class="badge badge-menunggu px-3 py-2 rounded-pill fs-7 fw-semibold mb-2">
                                        <i class="fa-solid fa-hourglass-half me-1"></i> Menunggu Koreksi Guru
                                    </span>
                                    <div class="text-muted small">
                                        Jawaban essay/isian Anda sedang dikoreksi oleh guru. Nilai akhir akan otomatis tampil setelah koreksi selesai.
                                    </div>

                                @elseif(!is_null($ujian->nilai_akhir))
                                    <small class="text-uppercase fw-bold d-block text-muted mb-1" style="font-size: 10px; letter-spacing: 1px;">Nilai Akhir Anda</small>
                                    
                                    <div class="nilai-big-badge my-1 {{ $ujian->is_tuntas ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($ujian->nilai_akhir, 1) }}
                                    </div>

                                    <div class="d-inline-flex align-items-center gap-1 mt-1">
                                        @if($ujian->is_tuntas)
                                            <span class="badge badge-tuntas rounded-pill px-3 py-1 fs-7">
                                                <i class="fa-solid fa-circle-check me-1"></i> Tuntas (>= KKM {{ $ujian->kkm }})
                                            </span>
                                        @else
                                            <span class="badge badge-belum-tuntas rounded-pill px-3 py-1 fs-7">
                                                <i class="fa-solid fa-triangle-exclamation me-1"></i> Belum Tuntas (< KKM {{ $ujian->kkm }})
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- DETAIL INFORMASI UJIAN --}}
                            <div class="mt-auto pt-2 border-top">
                                <div class="d-flex justify-content-between align-items-center text-muted small">
                                    <span><i class="fa-regular fa-calendar-check me-1"></i> Waktu Pelaksanaan:</span>
                                    <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d M Y') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center text-muted small mt-1">
                                    <span><i class="fa-solid fa-bullseye me-1"></i> KKM Mata Pelajaran:</span>
                                    <span class="fw-bold text-dark">{{ $ujian->kkm }}</span>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 rounded-4 shadow-sm p-5 text-center" style="background-color: #fff;">
            <div class="mb-3">
                <i class="fa-solid fa-square-poll-vertical fa-4x text-muted opacity-50"></i>
            </div>
            <h5 class="fw-bold mb-1" style="color: var(--ink-900);">Belum Ada Nilai Dipublish</h5>
            <p class="text-muted mb-0">Belum ada hasil nilai ujian yang dipublish oleh pihak sekolah untuk kelas Anda.</p>
        </div>
    @endif

</div>
@endsection
