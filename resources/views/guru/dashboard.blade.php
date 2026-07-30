@extends('layouts.app')

@section('title', 'Dashboard Guru')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')

@php
    // --- Sapaan waktu ---
    $jamSekarang = now()->format('H');
    $sapaanWaktu = $jamSekarang < 11 ? 'Selamat Pagi' : ($jamSekarang < 15 ? 'Selamat Siang' : ($jamSekarang < 18 ? 'Selamat Sore' : 'Selamat Malam'));

    // --- Inisial avatar guru ---
    $namaGuru = Auth::user()->nama ?? 'Guru';
    $kataNama = preg_split('/\s+/', trim($namaGuru));
    $inisialGuru = strtoupper(substr($kataNama[0] ?? 'G', 0, 1) . substr($kataNama[1] ?? '', 0, 1));

    // --- Progres semester (opsional, hanya tampil jika tanggal tersedia dari controller) ---
    $persenSemester = null;
    if (isset($active_tahun_ajaran) && !empty($active_tahun_ajaran->tanggal_mulai) && !empty($active_tahun_ajaran->tanggal_selesai)) {
        try {
            $mulaiTa   = \Carbon\Carbon::parse($active_tahun_ajaran->tanggal_mulai);
            $selesaiTa = \Carbon\Carbon::parse($active_tahun_ajaran->tanggal_selesai);
            $totalHariTa = max($mulaiTa->diffInDays($selesaiTa), 1);
            $hariBerjalan = min(max($mulaiTa->diffInDays(now(), false), 0), $totalHariTa);
            $persenSemester = (int) round(($hariBerjalan / $totalHariTa) * 100);
        } catch (\Throwable $e) {
            $persenSemester = null;
        }
    }

    // --- Progres penyelesaian ujian kelas binaan (dihitung dari data yang sudah ada) ---
    $totalSiswaMonitor = ($siswa_ujian ?? 0) + ($siswa_selesai ?? 0) + ($siswa_belum ?? 0);
    $persenSelesaiUjian = $totalSiswaMonitor > 0 ? (int) round((($siswa_selesai ?? 0) / $totalSiswaMonitor) * 100) : 0;
@endphp

<div class="container-fluid px-0 py-2">

    {{-- ========================================================= --}}
    {{-- HEADER SAMBUTAN --}}
    {{-- ========================================================= --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="page-header-top">
                    <div>
                        <h1>{{ $sapaanWaktu }}, {{ $namaGuru }} 👋</h1>

                        <p class="page-header-sub">
                            Anda login sebagai
                            <strong class="hl-gold">
                                @if($isWaliKelas && isset($wali_kelas_info))
                                    Guru &amp; Wali Kelas {{ $wali_kelas_info->nama_kelas }}
                                @else
                                    Guru Mata Pelajaran
                                @endif
                            </strong>
                            di <strong class="hl-white">CBT Smart Online</strong> Sekolah Islam Al Azhar Pekalongan.
                        </p>

                        <div class="page-header-meta">
                            <i class="fa-regular fa-calendar"></i>
                            {{ now()->translatedFormat('l, d F Y') }}
                        </div>

                        @if(!is_null($persenSemester))
                            <div class="semester-progress">
                                <div class="semester-progress-label">
                                    <span>Progres Tahun Ajaran {{ $active_tahun_ajaran->nama_tahun ?? '' }}</span>
                                    <b>{{ $persenSemester }}%</b>
                                </div>
                                <div class="semester-progress-track">
                                    <div class="semester-progress-fill" style="width: {{ $persenSemester }}%;"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- 1. OVERVIEW STAT CARDS --}}
    {{-- ========================================================= --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h2 class="section-heading mb-0">
            <i class="fa-solid fa-chart-pie"></i>Ringkasan Akademik Guru
        </h2>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="chip chip-indigo">
                <i class="fa-solid fa-calendar-day"></i>
                @if(isset($active_tahun_ajaran))
                    {{ $active_tahun_ajaran->nama_tahun }} ({{ ucfirst($active_tahun_ajaran->semester) }})
                @else
                    Tahun Ajaran Belum Diatur
                @endif
            </span>
            @if($isWaliKelas && isset($wali_kelas_info))
                <span class="chip chip-emerald">
                    <i class="fa-solid fa-user-shield"></i> Wali Kelas {{ $wali_kelas_info->nama_kelas }}
                </span>
            @endif
        </div>
    </div>

    <div class="row g-2 g-sm-3 mb-4">
        {{-- Card 1: Mapel Diampu --}}
        <div class="col-6 {{ $isWaliKelas ? 'col-xl-3' : 'col-xl-4' }}">
            <div class="stat-card stat-card-accent-indigo">
                <div class="stat-icon"><i class="fa-solid fa-book-open"></i></div>
                <div class="stat-body">
                    <span class="stat-label">Mapel Diampu</span>
                    <h3>{{ number_format($total_mapel ?? 0) }} <small>Mapel</small></h3>
                    <span class="stat-caption is-accent"><i class="fa-solid fa-graduation-cap me-1"></i>Semester Aktif</span>
                </div>
            </div>
        </div>

        {{-- Card 2: Bank Soal Anda --}}
        <div class="col-6 {{ $isWaliKelas ? 'col-xl-3' : 'col-xl-4' }}">
            <div class="stat-card stat-card-accent-violet">
                <div class="stat-icon"><i class="fa-solid fa-folder-open"></i></div>
                <div class="stat-body">
                    <span class="stat-label">Bank Soal Anda</span>
                    <h3>{{ number_format($total_bank_soal ?? 0) }} <small>Paket</small></h3>
                    <span class="stat-caption is-accent"><i class="fa-solid fa-check me-1"></i>Dibuat Guru</span>
                </div>
            </div>
        </div>

        {{-- Card 3: Jadwal Ujian --}}
        <div class="col-6 {{ $isWaliKelas ? 'col-xl-3' : 'col-xl-4' }}">
            <div class="stat-card stat-card-accent-amber">
                <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div class="stat-body">
                    <span class="stat-label">Jadwal Ujian</span>
                    <h3>{{ number_format($total_ujian_mapel ?? 0) }} <small>Jadwal</small></h3>
                    <span class="stat-caption is-accent"><i class="fa-solid fa-bolt me-1"></i>Ujian Mapel</span>
                </div>
            </div>
        </div>

        {{-- Card 4 (Khusus Wali Kelas): Kelas Binaan --}}
        @if($isWaliKelas && isset($wali_kelas_info))
        <div class="col-6 col-xl-3">
            <div class="stat-card stat-card-accent-cyan">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <div class="stat-body">
                    <span class="stat-label">Kelas Binaan</span>
                    <h3 style="font-size:16px;">Kelas {{ $wali_kelas_info->nama_kelas }}</h3>
                    <span class="stat-caption is-accent">{{ number_format($wali_kelas_info->total_siswa) }} Siswa Terdaftar</span>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ========================================================= --}}
    {{-- 2. TAB: MAPEL DIAMPU vs MONITORING WALI KELAS --}}
    {{-- ========================================================= --}}
    <div class="row g-3 mb-4">
        {{-- Kolom Kiri --}}
        <div class="col-12 col-lg-7 col-xl-8">
            <div class="content-card h-100">
                <ul class="nav nav-tabs tabs-clean mb-3" id="guruDashboardTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="mapel-tab" data-bs-toggle="tab" data-bs-target="#mapel-pane" type="button" role="tab">
                            <i class="fa-solid fa-book-open me-1 me-sm-2"></i><span class="d-none d-sm-inline">Mapel &amp; Kelas Diampu</span><span class="d-inline d-sm-none">Mapel &amp; Kelas</span>
                        </button>
                    </li>
                    @if($isWaliKelas && isset($wali_kelas_info))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="wali-tab" data-bs-toggle="tab" data-bs-target="#wali-pane" type="button" role="tab">
                            <i class="fa-solid fa-chart-line me-1 me-sm-2"></i><span class="d-none d-sm-inline">Pemantauan Wali Kelas ({{ $wali_kelas_info->nama_kelas }})</span><span class="d-inline d-sm-none">Wali Kelas ({{ $wali_kelas_info->nama_kelas }})</span>
                        </button>
                    </li>
                    @endif
                </ul>

                <div class="tab-content" id="guruDashboardTabContent">
                    {{-- Tab 1: Mapel & Kelas Diampu --}}
                    <div class="tab-pane fade show active" id="mapel-pane" role="tabpanel" tabindex="0">
                        <div class="d-flex align-items-start align-items-sm-center justify-content-between flex-column flex-sm-row gap-sm-3 mb-3">
                            <span class="small text-muted">Daftar mata pelajaran yang Anda ajar pada tahun ajaran ini.</span>
                            <a href="{{ route('dashboard-guru.bank-soal.create') }}" class="btn btn-brand-primary rounded-pill px-4 py-2 text-nowrap w-50 w-sm-100" style="font-size: 13px; font-weight: 700; align-self:end">
                                <i class="fa-solid fa-plus me-1"></i>Buat Bank Soal
                            </a>
                        </div>

                        @if(isset($mapel_diampu) && count($mapel_diampu) > 0)
                            <div class="table-responsive">
                                <table class="table table-academic align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Mata Pelajaran</th>
                                            <th>Kelas Target</th>
                                            <th class="text-center">Bank Soal Dibuat</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mapel_diampu as $m)
                                            <tr>
                                                <td data-label="Mata Pelajaran">
                                                    <div class="fw-bold text-dark" style="font-size: 13.5px;">
                                                        <i class="fa-solid fa-book me-2" style="color: var(--indigo-600);"></i>{{ $m->nama_mapel }}
                                                    </div>
                                                </td>
                                                <td data-label="Kelas Target">
                                                    <div class="small text-secondary text-truncate" style="max-width: 220px;">
                                                        @if($m->kelas_list)
                                                            <span class="badge bg-light text-dark border px-2 py-1 rounded-2">{{ $m->kelas_list }}</span>
                                                        @else
                                                            <span class="fst-italic text-muted">Belum ada kelas</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td data-label="Bank Soal" class="text-center">
                                                    <span class="fw-bold" style="font-size: 12.5px; color: var(--violet-700);">
                                                        <i class="fa-solid fa-folder-open me-1"></i>{{ $m->total_bank_soal }} Paket
                                                    </span>
                                                </td>
                                                <td data-label="Aksi" class="text-end">
                                                    <a href="{{ route('dashboard-guru.bank-soal.index') }}" class="btn btn-brand-light rounded-2 px-3 py-1.5" style="font-size: 12.5px; font-weight: 700;">
                                                        <i class="fa-solid fa-folder me-1"></i>Kelola Soal
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state py-4">
                                <i class="fa-solid fa-book-bookmark text-muted opacity-50 mb-2" style="font-size: 36px;"></i>
                                <p class="mb-1 text-secondary fw-semibold" style="font-size: 13.5px;">Belum Ada Mapel Diampu</p>
                                <p class="small text-muted mb-3">Anda belum diplot untuk mengajar mata pelajaran pada semester ini.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Tab 2: Monitoring Wali Kelas --}}
                    @if($isWaliKelas && isset($wali_kelas_info))
                    <div class="tab-pane fade" id="wali-pane" role="tabpanel" tabindex="0">
                        <div class="d-flex align-items-start align-items-sm-center justify-content-between flex-column flex-sm-row gap-sm-3 mb-3">
                            <span class="small text-muted">Ringkasan pengerjaan ujian siswa kelas binaan <strong>{{ $wali_kelas_info->nama_kelas }}</strong>.</span>
                            <a href="{{ route('dashboard-guru.wali-kelas.monitoring-siswa') }}" class="btn btn-brand-outline rounded-pill px-4 py-2 text-nowrap w-50 w-sm-auto text-center" style="font-size: 13px; font-weight: 700; align-self:end">
                                Monitoring Ujian <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>

                        <div class="row g-2 g-sm-3 text-center mb-3">
                            <div class="col-4">
                                <div class="mini-stat mini-stat-amber">
                                    <span class="mini-stat-label">Sedang Mengerjakan</span>
                                    <h3>{{ number_format($siswa_ujian ?? 0) }}</h3>
                                    <small class="text-muted d-block" style="font-size: 10px;">Siswa Ujian</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mini-stat mini-stat-emerald">
                                    <span class="mini-stat-label">Sudah Selesai</span>
                                    <h3>{{ number_format($siswa_selesai ?? 0) }}</h3>
                                    <small class="text-muted d-block" style="font-size: 10px;">Siswa Selesai</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mini-stat mini-stat-slate">
                                    <span class="mini-stat-label">Belum Ujian</span>
                                    <h3>{{ number_format($siswa_belum ?? 0) }}</h3>
                                    <small class="text-muted d-block" style="font-size: 10px;">Siswa Belum</small>
                                </div>
                            </div>
                        </div>

                        @if($totalSiswaMonitor > 0)
                            <div class="completion-bar-wrap mb-3">
                                <div class="completion-bar-label">
                                    <span>Progres Penyelesaian Ujian Kelas</span>
                                    <b>{{ $persenSelesaiUjian }}% selesai</b>
                                </div>
                                <div class="completion-bar-track">
                                    <div class="completion-bar-fill" style="width: {{ $persenSelesaiUjian }}%;"></div>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end flex-column flex-sm-row gap-2 pt-2 border-top">
                            <a href="{{ route('dashboard-guru.wali-kelas.data-kelas') }}" class="btn btn-brand-light rounded-2 px-3 py-2 text-center" style="font-size: 13px; font-weight: 700;">
                                <i class="fa-solid fa-users me-1"></i>Data Kelas Binaan
                            </a>
                            <a href="{{ route('dashboard-guru.wali-kelas.rekap-nilai') }}" class="btn btn-brand-primary rounded-2 px-3 py-2 text-center" style="font-size: 13px; font-weight: 700;">
                                <i class="fa-solid fa-clipboard-check me-1"></i>Rekap Nilai Kelas
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Aksi Cepat Guru --}}
        <div class="col-12 col-lg-5 col-xl-4">
            <div class="content-card position-sticky" style="top: 20px; z-index: 10;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <h2 class="section-heading mb-0"><i class="fa-solid fa-bolt"></i>Aksi Cepat Guru</h2>
                </div>

                <div class="quick-action-grid">
                    <a href="{{ route('dashboard-guru.bank-soal.create') }}" class="quick-action-btn-sm accent-indigo">
                        <div class="quick-icon-box-sm"><i class="fa-solid fa-circle-plus"></i></div>
                        <span>Buat Bank Soal</span>
                    </a>
                    <a href="{{ route('dashboard-guru.jadwal-ujian.index') }}" class="quick-action-btn-sm accent-emerald">
                        <div class="quick-icon-box-sm"><i class="fa-solid fa-calendar-check"></i></div>
                        <span>Jadwal Ujian</span>
                    </a>
                    <a href="{{ route('dashboard-guru.nilai-siswa.index') }}" class="quick-action-btn-sm accent-violet">
                        <div class="quick-icon-box-sm"><i class="fa-solid fa-square-poll-vertical"></i></div>
                        <span>Nilai &amp; Koreksi</span>
                    </a>
                    @if($isWaliKelas)
                    <a href="{{ route('dashboard-guru.wali-kelas.data-kelas') }}" class="quick-action-btn-sm accent-amber">
                        <div class="quick-icon-box-sm"><i class="fa-solid fa-users"></i></div>
                        <span>Kelas Binaan</span>
                    </a>
                    <a href="{{ route('dashboard-guru.wali-kelas.monitoring-siswa') }}" class="quick-action-btn-sm accent-slate">
                        <div class="quick-icon-box-sm"><i class="fa-solid fa-chart-line"></i></div>
                        <span>Monitoring Siswa</span>
                    </a>
                    <a href="{{ route('dashboard-guru.wali-kelas.rekap-nilai') }}" class="quick-action-btn-sm accent-indigo">
                        <div class="quick-icon-box-sm"><i class="fa-solid fa-clipboard-check"></i></div>
                        <span>Rekap Nilai</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection