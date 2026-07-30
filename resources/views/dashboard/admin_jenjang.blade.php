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
            </div>
        </div>
    </div>
</div>
