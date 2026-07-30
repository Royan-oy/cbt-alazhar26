{{-- ========================================================= --}}
{{-- 1. OVERVIEW STAT CARDS (TOP METRICS) --}}
{{-- ========================================================= --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h2 class="section-heading mb-0">
        <i class="fa-solid fa-chart-pie"></i>Ringkasan Eksekutif Sistem
    </h2>
    <span class="chip chip-indigo">
        <i class="fa-solid fa-calendar-day"></i>
        Tahun Ajaran: 
        @if(isset($active_tahun_ajaran))
            {{ $active_tahun_ajaran->nama_tahun }} ({{ ucfirst($active_tahun_ajaran->semester) }})
        @else
            Belum Diatur
        @endif
    </span>
</div>

<div class="row g-2 g-sm-3 mb-4">
    {{-- Card 1: Tahun Ajaran --}}
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-accent-indigo">
            <div class="stat-icon">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Tahun Ajaran</span>
                <h3 class="mb-0 fs-6 fw-bold text-dark text-truncate">
                    {{ isset($active_tahun_ajaran) ? $active_tahun_ajaran->nama_tahun : 'Belum Set' }}
                </h3>
                @if(isset($active_tahun_ajaran))
                    <span class="stat-caption is-accent">
                        <i class="fa-solid fa-circle-check me-1"></i>Semester {{ ucfirst($active_tahun_ajaran->semester) }} (Aktif)
                    </span>
                @else
                    <span class="stat-caption text-danger fw-semibold">
                        <i class="fa-solid fa-circle-exclamation me-1"></i>Belum ada yang aktif
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Card 2: Total Jenjang --}}
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-accent-emerald">
            <div class="stat-icon">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Total Jenjang</span>
                <h3>{{ $total_jenjang ?? 0 }} <small>Unit</small></h3>
                <span class="stat-caption is-accent"><i class="fa-solid fa-graduation-cap me-1"></i>SD, SMP, SMA, dll</span>
            </div>
        </div>
    </div>

    {{-- Card 3: Admin Jenjang --}}
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-accent-violet">
            <div class="stat-icon">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Admin Jenjang</span>
                <h3>{{ number_format($total_admin_jenjang ?? 0) }} <small>Akun</small></h3>
                <span class="stat-caption is-accent"><i class="fa-solid fa-shield-halved me-1"></i>Pengelola Unit</span>
            </div>
        </div>
    </div>

    {{-- Card 4: Total Guru & Siswa --}}
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-accent-cyan">
            <div class="stat-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Guru &amp; Siswa</span>
                <h3 class="fs-6 fw-bold text-dark mb-0">
                    {{ number_format($total_guru ?? 0) }} <small class="text-muted">Guru</small>
                </h3>
                <span class="stat-caption is-accent">
                    <i class="fa-solid fa-user-graduate me-1"></i>{{ number_format($total_siswa ?? 0) }} Siswa Terdaftar
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- 2. TABEL RINGKASAN JENJANG (KIRI 70%) & AKSI PINTASAN STICKY (KANAN 30%) --}}
{{-- ========================================================= --}}
<div class="row g-3 mb-4">
    {{-- Kolom Kiri: Tabel Ringkasan Unit Per Jenjang (Lebar 70% / col-lg-7 col-xl-8) --}}
    <div class="col-12 col-lg-7 col-xl-8">
        <div class="content-card h-100">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h2 class="section-heading mb-0">
                    <i class="fa-solid fa-sitemap"></i>Ringkasan Unit Per Jenjang Pendidikan
                </h2>
                <a href="{{ route('jenjang.index') }}" class="btn btn-brand-primary rounded-pill px-3 py-1.5" style="font-size: 12.5px;">
                    Kelola Jenjang <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>

            @if(isset($jenjang_summary) && count($jenjang_summary) > 0)
                <div class="table-responsive">
                    <table class="table table-academic align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama Jenjang</th>
                                <th>Admin Penanggung Jawab</th>
                                <th class="text-center">Kelas</th>
                                <th class="text-center">Total Siswa</th>
                                <th class="text-center">Bank Soal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jenjang_summary as $item)
                                <tr>
                                    <td data-label="Nama Jenjang">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-2 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                                                 style="width: 32px; height: 32px; background: linear-gradient(135deg, var(--indigo-700), var(--indigo-500)); font-size: 12px;">
                                                <i class="fa-solid fa-graduation-cap"></i>
                                            </div>
                                            <div class="fw-bold text-dark" style="font-size: 13.5px;">
                                                {{ $item->nama_jenjang }}
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Admin">
                                        @if($item->admin_nama != 'Belum Ditentukan')
                                            <span class="fw-semibold text-dark" style="font-size: 12.5px;">
                                                <i class="fa-solid fa-user-shield me-1" style="color: var(--indigo-600);"></i>{{ $item->admin_nama }}
                                            </span>
                                        @else
                                            <span class="chip chip-amber" style="font-size: 10px; padding: 3px 8px;">
                                                <i class="fa-solid fa-triangle-exclamation"></i>Belum Set
                                            </span>
                                        @endif
                                    </td>
                                    <td data-label="Kelas" class="text-center">
                                        <span class="badge bg-light text-dark border px-2 py-1 rounded-2 fw-semibold" style="font-size: 11px;">
                                            {{ $item->total_kelas }} Ruang
                                        </span>
                                    </td>
                                    <td data-label="Total Siswa" class="text-center">
                                        <span class="fw-bold" style="font-size: 12.5px; color: var(--indigo-600);">
                                            <i class="fa-solid fa-user-graduate me-1"></i>{{ number_format($item->total_siswa) }}
                                        </span>
                                    </td>
                                    <td data-label="Bank Soal" class="text-center">
                                        <span class="fw-bold" style="font-size: 12.5px; color: var(--emerald-700);">
                                            <i class="fa-solid fa-folder-open me-1"></i>{{ number_format($item->total_bank_soal) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state py-4">
                    <i class="fa-solid fa-layer-group text-muted opacity-50 mb-2" style="font-size: 36px;"></i>
                    <p class="mb-1 text-secondary fw-semibold" style="font-size: 13.5px;">Belum Ada Jenjang Terdaftar</p>
                    <p class="small text-muted mb-3">Sistem belum memiliki data unit jenjang pendidikan.</p>
                    <a href="{{ route('jenjang.create') }}" class="btn btn-brand-primary rounded-pill px-3 py-1.5" style="font-size: 12px;">
                        <i class="fa-solid fa-plus me-1"></i>Tambah Jenjang Baru
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Kolom Kanan: Aksi Pintasan Super Admin Sticky (Lebar 30% / col-lg-5 col-xl-4) --}}
    <div class="col-12 col-lg-5 col-xl-4">
        <div class="content-card position-sticky" style="top: 20px; z-index: 10;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h2 class="section-heading mb-0">
                    <i class="fa-solid fa-bolt"></i>Aksi Pintasan
                </h2>
            </div>

            <div class="quick-action-grid">
                <a href="{{ route('jenjang.index') }}" class="quick-action-btn-sm accent-indigo">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-layer-group"></i></div>
                    <span>Kelola Jenjang</span>
                </a>
                <a href="{{ route('tahun-ajaran.index') }}" class="quick-action-btn-sm accent-blue">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-calendar-days"></i></div>
                    <span>Tahun Ajaran</span>
                </a>
                <a href="{{ route('admin-jenjang.create') }}" class="quick-action-btn-sm accent-violet">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-user-shield"></i></div>
                    <span>Tambah Admin</span>
                </a>
                <a href="{{ route('guru.index') }}" class="quick-action-btn-sm accent-emerald">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-chalkboard-user"></i></div>
                    <span>Data Guru</span>
                </a>
                <a href="{{ route('siswa.index') }}" class="quick-action-btn-sm accent-cyan">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-user-graduate"></i></div>
                    <span>Data Siswa</span>
                </a>
                <a href="{{ route('ujian.index') }}" class="quick-action-btn-sm accent-amber">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-calendar-check"></i></div>
                    <span>Jadwal Ujian</span>
                </a>
            </div>
        </div>
    </div>
</div>
