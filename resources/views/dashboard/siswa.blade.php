@php
$jumlahBelum = collect($ujian_hari_ini ?? [])
    ->where('status_siswa','belum')
    ->count();

$jumlahBerjalan = collect($ujian_hari_ini ?? [])
    ->where('status_siswa','mengerjakan')
    ->count();

$jumlahSelesai = collect($ujian_hari_ini ?? [])
    ->where('status_siswa','selesai')
    ->count();

$ujianBerjalan = collect($ujian_hari_ini ?? [])
    ->firstWhere('status_siswa','mengerjakan');
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

                <a href="{{ route('dashboard-siswa.ujian.kerja', ['ujian' => $ujianBerjalan->id]) }}"
                   class="btn btn-light fw-bold px-4 py-2 rounded-3" style="font-size: 13px;">
                    <i class="fa-solid fa-arrow-right me-2"></i>Lanjutkan Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h2 class="section-heading mb-0">
        <i class="fa-solid fa-chart-pie"></i>Ringkasan Evaluasi Anda
    </h2>
    <span class="chip chip-indigo">
        <i class="fa-solid fa-user-graduate"></i> Mode Siswa
    </span>
</div>

<div class="row g-2 g-sm-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="stat-card stat-card-accent-indigo">
            <div class="stat-icon"><i class="fa-solid fa-pen-to-square"></i></div>
            <div class="stat-body">
                <span class="stat-label">Ujian Tersedia</span>
                <h3>{{ count($ujian_hari_ini ?? []) }} <small>Hari Ini</small></h3>
                <span class="stat-caption is-accent"><i class="fa-solid fa-calendar-day me-1"></i>Jadwal aktif untuk Anda</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card stat-card-accent-emerald">
            <div class="stat-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
            <div class="stat-body">
                <span class="stat-label">Ujian Diselesaikan</span>
                <h3>{{ $riwayat_ujian ?? 0 }} <small>Riwayat</small></h3>
                <span class="stat-caption is-accent"><i class="fa-solid fa-circle-check me-1"></i>Total sepanjang waktu</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-lg-8">
        <div class="content-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <h2 class="section-heading mb-0">
                    <i class="fa-solid fa-calendar-day"></i>Jadwal Ujian Hari Ini
                </h2>
                <a href="{{ route('dashboard-siswa.ujian-hari-ini') }}" class="btn btn-brand-primary rounded-pill px-3 py-1.5" style="font-size: 12px;">
                    Lihat semua <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>

            @if(isset($ujian_hari_ini) && count($ujian_hari_ini) > 0)
                <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                    <table class="table table-academic align-middle mb-0">
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
                                        <div class="exam-info">
                                            <div class="exam-icon">
                                                <i class="fa-solid fa-file-lines"></i>
                                            </div>

                                            <div class="exam-content">

                                                <h6 class="exam-title mb-1">
                                                    {{ $ujian->nama_ujian }}
                                                </h6>

                                                <div class="exam-meta">

                                                    <span class="exam-badge duration">
                                                        <i class="fa-regular fa-clock"></i>
                                                        {{ $ujian->durasi_menit }} Menit
                                                    </span>

                                                </div>

                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Batas Waktu">
                                        <div class="exam-time-info">
                                            <div class="exam-time-start">
                                                <i class="fa-regular fa-calendar-check"></i>
                                                <span>{{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d M Y') }}</span>
                                            </div>

                                            <div class="exam-time-hour">
                                                <i class="fa-regular fa-clock"></i>
                                                <span>
                                                    {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('H:i') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }}
                                                    WIB
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center" data-label="Status">
                                        @if($ujian->status_waktu == 'belum_mulai')
                                            <span class="exam-status-badge status-belum">
                                                <i class="fa-regular fa-calendar-days"></i>
                                                Belum Mulai
                                            </span>
                                        @elseif($ujian->status_waktu == 'berakhir')
                                            <span class="exam-status-badge status-selesai">
                                                <i class="fa-solid fa-hourglass-end"></i>
                                                Berakhir
                                            </span>
                                        @elseif($ujian->status_siswa == 'belum')
                                            <span class="exam-status-badge status-belum">
                                                <i class="fa-solid fa-pen"></i>
                                                Belum Dikerjakan
                                            </span>
                                        @elseif($ujian->status_siswa == 'mengerjakan')
                                            <span class="exam-status-badge status-berjalan">
                                                <i class="fa-solid fa-spinner fa-spin"></i>
                                                Sedang Mengerjakan
                                            </span>
                                        @else
                                            <span class="exam-status-badge status-selesai">
                                                <i class="fa-solid fa-circle-check"></i>
                                                Sudah Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end" data-label="Aksi">

                                        @php
                                            $belumMulai = now()->lt($ujian->waktu_mulai);
                                            $sudahBerakhir = now()->gt($ujian->waktu_selesai);
                                        @endphp

                                        @if($belumMulai)

                                            <button class="btn-exam-modern btn-exam-wait" disabled>
                                                <i class="fa-regular fa-clock"></i>
                                                Belum Mulai
                                            </button>

                                        @elseif($sudahBerakhir)

                                            <button class="btn-exam-modern btn-exam-end" disabled>
                                                <i class="fa-solid fa-hourglass-end"></i>
                                                Berakhir
                                            </button>

                                        @else

                                            @if($ujian->status_siswa == 'belum')

                                                <a href="{{ route('dashboard-siswa.ujian.mulai',$ujian->id) }}"
                                                class="btn-exam-modern btn-exam-start">
                                                    <i class="fa-solid fa-play"></i>
                                                    Mulai
                                                </a>

                                            @elseif($ujian->status_siswa == 'mengerjakan')

                                                <a href="{{ route('dashboard-siswa.ujian.mulai',$ujian->id) }}"
                                                class="btn-exam-modern btn-exam-continue">
                                                    <i class="fa-solid fa-arrow-rotate-right"></i>
                                                    Lanjutkan
                                                </a>

                                            @else

                                                <button class="btn-exam-modern btn-exam-done" disabled>
                                                    <i class="fa-solid fa-circle-check"></i>
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
                <div class="empty-state py-4">
                    <i class="fa-regular fa-calendar-check text-muted opacity-50 mb-2" style="font-size: 36px;"></i>
                    <p class="text-secondary small mb-1 fw-semibold">Alhamdulillah, tidak ada jadwal ujian aktif untuk Anda saat ini.</p>
                    <p class="text-muted small mb-0">Jadwal ujian baru akan muncul otomatis di sini saat sudah waktunya.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="content-card h-100">
            <h2 class="section-heading mb-3">
                <i class="fa-solid fa-chart-pie"></i>Status Hari Ini
            </h2>

            @if(count($ujian_hari_ini ?? []) > 0)
                <div style="position: relative; height: 180px;">
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="d-flex flex-column gap-2 mt-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="legend-dot" style="background:#2563eb;"></span>
                        <span class="flex-grow-1 small ms-2 fw-semibold text-secondary">Belum Dikerjakan</span>
                        <strong class="small fw-bold text-dark">{{ $jumlahBelum }}</strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="legend-dot" style="background:#d97706;"></span>
                        <span class="flex-grow-1 small ms-2 fw-semibold text-secondary">Sedang Berjalan</span>
                        <strong class="small fw-bold text-dark">{{ $jumlahBerjalan }}</strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="legend-dot" style="background:#059669;"></span>
                        <span class="flex-grow-1 small ms-2 fw-semibold text-secondary">Selesai</span>
                        <strong class="small fw-bold text-dark">{{ $jumlahSelesai }}</strong>
                    </div>
                </div>
            @else
                <div class="empty-state py-4">
                    <i class="fa-regular fa-face-smile text-muted opacity-50 mb-2" style="font-size: 36px;"></i>
                    <p class="text-muted small mb-0">Belum ada data untuk ditampilkan.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="content-card">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h2 class="section-heading mb-0">
                    <i class="fa-solid fa-bolt"></i>Aksi Pintasan Siswa
                </h2>
                <span class="chip chip-emerald">
                    <i class="fa-solid fa-shield-halved"></i> Sesi Enkripsi Terlindungi
                </span>
            </div>

            <div class="quick-action-grid">
                <a href="{{ route('dashboard-siswa.scan-token.index') }}" class="quick-action-btn-sm accent-indigo">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-qrcode"></i></div>
                    <span>Scan Token Ujian</span>
                </a>
                <a href="{{ route('dashboard-siswa.ujian-hari-ini') }}" class="quick-action-btn-sm accent-blue">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-calendar-day"></i></div>
                    <span>Jadwal Ujian</span>
                </a>
                <a href="{{ route('pengaturan-akun.index') }}" class="quick-action-btn-sm accent-slate">
                    <div class="quick-icon-box-sm"><i class="fa-solid fa-user"></i></div>
                    <span>Profil Saya</span>
                </a>
            </div>
        </div>
    </div>
</div>

@if(count($ujian_hari_ini ?? []) > 0)
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
(function() {
    function initChart() {
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
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChart);
    } else {
        initChart();
    }
})();
</script>
@endpush
@endif
