{{-- ========================================================= --}}
{{-- 1. OVERVIEW STAT CARDS (UNIT METRICS) --}}
{{-- ========================================================= --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h2 class="section-heading mb-0">
        <i class="fa-solid fa-graduation-cap"></i>Statistik Unit {{ $jenjang->nama_jenjang ?? 'Jenjang Pendidikan' }}
    </h2>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="chip chip-indigo">
            <i class="fa-solid fa-calendar-day"></i>
            Tahun Ajaran: 
            @if(isset($active_tahun_ajaran))
                {{ $active_tahun_ajaran->nama_tahun }} ({{ ucfirst($active_tahun_ajaran->semester) }})
            @else
                Belum Diatur
            @endif
        </span>
        @if(isset($jenjang))
            <span class="chip chip-emerald">
                <i class="fa-solid fa-school"></i> Unit {{ $jenjang->nama_jenjang }}
            </span>
        @endif
    </div>
</div>

<div class="row g-2 g-sm-3 mb-4">
    {{-- Card 1: Unit Jenjang --}}
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-accent-indigo">
            <div class="stat-icon">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Unit Pengelola</span>
                <h3 class="mb-0 fs-6 fw-bold text-dark text-truncate">
                    {{ $jenjang->nama_jenjang ?? 'Admin Jenjang' }}
                </h3>
                <span class="stat-caption is-accent">
                    <i class="fa-solid fa-circle-check me-1"></i>Unit Terdaftar
                </span>
            </div>
        </div>
    </div>

    {{-- Card 2: Total Kelas --}}
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-accent-emerald">
            <div class="stat-icon">
                <i class="fa-solid fa-landmark"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Total Kelas</span>
                <h3>{{ number_format($total_kelas ?? 0) }} <small>Ruang</small></h3>
                <span class="stat-caption is-accent"><i class="fa-solid fa-door-open me-1"></i>Kelas Aktif Unit</span>
            </div>
        </div>
    </div>

    {{-- Card 3: Total Siswa --}}
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-accent-cyan">
            <div class="stat-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Siswa Terdaftar</span>
                <h3>{{ number_format($total_siswa ?? 0) }} <small>Anak</small></h3>
                <span class="stat-caption is-accent">
                    <i class="fa-solid fa-user-check me-1"></i>Tahun Ajaran Aktif
                </span>
            </div>
        </div>
    </div>

    {{-- Card 4: Guru Mapel --}}
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-card-accent-violet">
            <div class="stat-icon">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Guru Mapel</span>
                <h3>{{ number_format($total_guru_mapel ?? 0) }} <small>Pengajar</small></h3>
                <span class="stat-caption is-accent"><i class="fa-solid fa-book-open me-1"></i>Terplot di Unit</span>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================= --}}
{{-- 2. TABEL RINGKASAN KELAS (KIRI 70%) & AKSI PINTASAN (KANAN 30%) --}}
{{-- ========================================================= --}}
<div class="row g-3 mb-4">
    {{-- Kolom Kiri: Daftar Kelas & Wali Kelas Unit (Lebar 70% / col-lg-7 col-xl-8) --}}
    <div class="col-12 col-lg-7 col-xl-8">
        <div class="content-card h-100">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h2 class="section-heading mb-0">
                    <i class="fa-solid fa-door-open"></i>Daftar Kelas &amp; Wali Kelas Unit
                </h2>
                <a href="{{ route('kelas.index') }}" class="btn btn-brand-primary rounded-pill px-3 py-1.5" style="font-size: 12.5px;">
                    Kelola Kelas <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>

            @if(isset($ringkasan_kelas) && count($ringkasan_kelas) > 0)
                <div class="table-responsive">
                    <table class="table table-academic align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama Kelas</th>
                                <th>Tingkat</th>
                                <th>Wali Kelas Terdaftar</th>
                                <th>Jumlah Siswa</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ringkasan_kelas as $k)
                                <tr>
                                    <td data-label="Nama Kelas">
                                        <div class="fw-bold text-dark" style="font-size: 13.5px;">
                                            <i class="fa-solid fa-door-closed me-2" style="color: var(--indigo-600);"></i>{{ $k->nama_kelas }}
                                        </div>
                                    </td>
                                    <td data-label="Tingkat">
                                        <span class="badge bg-light text-secondary border px-2 py-1 rounded-2" style="font-size: 11px;">
                                            Tingkat {{ $k->nama_tingkat }}
                                        </span>
                                    </td>
                                    <td data-label="Wali Kelas">
                                        @if($k->wali_kelas != 'Belum Diatur')
                                            <span class="fw-semibold text-dark" style="font-size: 12.5px;">
                                                <i class="fa-solid fa-user-tie me-1" style="color: var(--emerald-600);"></i>{{ $k->wali_kelas }}
                                            </span>
                                        @else
                                            <span class="chip chip-amber" style="font-size: 10px; padding: 3px 8px;">
                                                <i class="fa-solid fa-circle-exclamation"></i>Belum Diatur
                                            </span>
                                        @endif
                                    </td>
                                    <td data-label="Jumlah Siswa">
                                        <span class="fw-bold" style="font-size: 12.5px; color: var(--indigo-600);">
                                            <i class="fa-solid fa-user-graduate me-1"></i>{{ $k->total_siswa }} Siswa
                                        </span>
                                    </td>
                                    <td data-label="Aksi" class="text-end">
                                        <a href="{{ route('kelas.index') }}" class="btn btn-brand-light rounded-2 px-3 py-1" style="font-size: 12px;">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>Kelola
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state py-4">
                    <i class="fa-solid fa-landmark text-muted opacity-50 mb-2" style="font-size: 36px;"></i>
                    <p class="mb-1 text-secondary fw-semibold" style="font-size: 13.5px;">Belum Ada Kelas Terdaftar</p>
                    <p class="small text-muted mb-3">Unit jenjang ini belum memiliki data kelas.</p>
                    <a href="{{ route('kelas.create') }}" class="btn btn-brand-primary rounded-pill px-3 py-1.5" style="font-size: 12px;">
                        <i class="fa-solid fa-plus me-1"></i>Tambah Kelas Baru
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Kolom Kanan: Aksi Pintasan Admin Jenjang (Lebar 30% / col-lg-5 col-xl-4) --}}
    <div class="col-12 col-lg-5 col-xl-4">
        <div class="content-card position-sticky" style="top: 20px; z-index: 10;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h2 class="section-heading mb-0">
                    <i class="fa-solid fa-bolt"></i>Aksi Pintasan
                </h2>
               
            </div>

            <div class="quick-action-grid">
                <a href="{{ route('siswa.create') }}" class="quick-action-btn-sm accent-indigo">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-user-plus"></i></div>
                    <span>Tambah Siswa</span>
                </a>
                <a href="{{ route('kelas.index') }}" class="quick-action-btn-sm accent-emerald">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-door-open"></i></div>
                    <span>Kelola Kelas</span>
                </a>
                <a href="{{ route('guru-mapel.index') }}" class="quick-action-btn-sm accent-violet">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-book-open"></i></div>
                    <span>Guru Mapel</span>
                </a>
                <a href="{{ route('wali-kelas.index') }}" class="quick-action-btn-sm accent-amber">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-users-gear"></i></div>
                    <span>Wali Kelas</span>
                </a>
                <a href="{{ route('bank-soal.index') }}" class="quick-action-btn-sm accent-slate">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-folder-open"></i></div>
                    <span>Bank Soal</span>
                </a>
                <a href="{{ route('ujian.index') }}" class="quick-action-btn-sm accent-blue">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-calendar-check"></i></div>
                    <span>Jadwal Ujian</span>
                </a>
            </div>
        </div>
    </div>
</div>
