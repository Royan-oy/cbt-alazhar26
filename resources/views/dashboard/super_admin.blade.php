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
            </div>
        </div>
    </div>
</div>
