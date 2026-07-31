@extends('layouts.app')

@section('content')
<style>
    /* ============================================
       DESIGN TOKENS
    ============================================ */
    :root {
        --brand-50:  #f0f9ff;
        --brand-100: #e0f2fe;
        --brand-400: #38bdf8;
        --brand-500: #0ea5e9;
        --brand-600: #0284c7;
        --brand-700: #0369a1;
        --ink-900: #0f172a;
        --ink-700: #334155;
        --ink-500: #64748b;
        --ink-300: #cbd5e1;
        --ink-200: #e2e8f0;
        --ink-100: #f1f5f9;
        --ink-50:  #f8fafc;
        --radius-lg: 20px;
        --radius-md: 14px;
        --radius-sm: 10px;
        --shadow-soft: 0 2px 10px rgba(15, 23, 42, 0.04);
        --shadow-mid: 0 10px 30px rgba(15, 23, 42, 0.08);
        --shadow-hover: 0 18px 40px rgba(2, 132, 199, 0.14);
    }

    .je-wrap { max-width: 1400px; margin-inline: auto; }

    /* ============================================
       HEADER
    ============================================ */
    .je-header {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 1.75rem;
    }
    .je-header h3 {
        font-weight: 800;
        color: var(--ink-900);
        letter-spacing: -0.02em;
        margin-bottom: 0.35rem;
    }
    .je-header p {
        color: var(--ink-500);
        font-size: 14px;
        margin: 0;
    }
    .je-header-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--brand-400), var(--brand-600));
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        box-shadow: 0 8px 20px rgba(2, 132, 199, 0.28);
        flex-shrink: 0;
    }

    /* ============================================
       FILTER / SEARCH CARD
    ============================================ */
    .je-filter-card {
        border: 1px solid var(--ink-100);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-soft);
        background: #fff;
        margin-bottom: 1.75rem;
    }
    .je-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        color: var(--ink-500);
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        display: block;
    }
    .je-input-group {
        border-radius: var(--radius-sm);
        overflow: hidden;
        border: 1.5px solid var(--ink-200);
        transition: border-color .2s ease, box-shadow .2s ease;
        background: var(--ink-50);
    }
    .je-input-group:focus-within {
        border-color: var(--brand-400);
        box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
        background: #fff;
    }
    .je-input-group .input-group-text {
        background: transparent;
        border: none;
        color: var(--ink-500);
        padding-left: 1rem;
    }
    .je-input-group .form-control {
        border: none;
        background: transparent;
        padding: 0.7rem 0.9rem 0.7rem 0.4rem;
        font-size: 14px;
    }
    .je-input-group .form-control:focus {
        box-shadow: none;
        outline: none;
    }
    .je-date-input {
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--ink-200);
        background: var(--ink-50);
        padding: 0.7rem 0.9rem;
        font-size: 14px;
        width: 100%;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .je-date-input:focus {
        border-color: var(--brand-400);
        box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
        background: #fff;
        outline: none;
    }
    .je-btn-apply {
        background: linear-gradient(135deg, var(--brand-400), var(--brand-600));
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: var(--radius-sm);
        padding: 0.7rem 1.1rem;
        font-size: 14px;
        transition: transform .15s ease, box-shadow .2s ease, filter .2s ease;
        box-shadow: 0 6px 16px rgba(2, 132, 199, 0.25);
    }
    .je-btn-apply:hover {
        color: #fff;
        filter: brightness(1.05);
        box-shadow: 0 8px 20px rgba(2, 132, 199, 0.35);
        transform: translateY(-1px);
    }
    .je-btn-reset {
        background: var(--ink-50);
        border: 1.5px solid var(--ink-200);
        color: var(--ink-500);
        font-weight: 600;
        border-radius: var(--radius-sm);
        padding: 0.7rem 1.1rem;
        font-size: 14px;
        transition: all .2s ease;
    }
    .je-btn-reset:hover {
        background: var(--ink-200);
        color: var(--ink-700);
    }

    /* ============================================
       SECTION TITLE + FILTER PILLS (scrollable on mobile)
    ============================================ */
    .je-section-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.9rem;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .je-section-title {
        font-weight: 800;
        color: var(--ink-900);
        font-size: 17px;
        margin: 0;
        display: flex;
        align-items: center;
        white-space: nowrap;
    }
    .je-section-title i { color: var(--brand-600); margin-right: 0.5rem; }

    .je-pills-scroll {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 4px;
        -ms-overflow-style: none;
        scrollbar-width: none;
        flex: 1 1 auto;
        min-width: 0;
        justify-content: flex-end;
    }
    .je-pills-scroll::-webkit-scrollbar { display: none; }

    .btn-filter-tab {
        color: var(--ink-500);
        background: var(--ink-50);
        border: 1.5px solid var(--ink-200);
        font-size: 13px;
        font-weight: 600;
        border-radius: 999px;
        padding: 0.55rem 1rem;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        flex-shrink: 0;
        transition: all .2s ease;
    }
    .btn-filter-tab:hover {
        background: var(--ink-200);
        color: var(--ink-700);
    }
    .btn-filter-tab.active {
        background: linear-gradient(135deg, var(--brand-400), var(--brand-600)) !important;
        color: #fff !important;
        border-color: transparent;
        box-shadow: 0 6px 16px rgba(2, 132, 199, 0.28);
    }
    .btn-filter-tab .badge {
        font-size: 10.5px;
        font-weight: 700;
    }

    /* ============================================
       EXAM CARDS
    ============================================ */
    .exam-card {
        border: 1px solid var(--ink-100);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-soft);
        overflow: hidden;
        background: #fff;
        transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s cubic-bezier(.4,0,.2,1);
        height: 100%;
    }
    .exam-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-hover);
    }
    .exam-card-accent {
        height: 5px;
        width: 100%;
        background: linear-gradient(90deg, var(--brand-400), var(--brand-600));
    }
    .exam-card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .je-time-badge {
        background: var(--brand-50);
        color: var(--brand-600);
        font-size: 12px;
        font-weight: 700;
        padding: 0.45rem 0.85rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .je-status-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 0.45rem 0.8rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        border: 1px solid transparent;
    }
    .je-status-ongoing { background: rgba(34,197,94,.1); color: #16a34a; border-color: rgba(34,197,94,.25); }
    .je-status-pending { background: rgba(245,158,11,.1); color: #d97706; border-color: rgba(245,158,11,.25); }
    .je-status-done     { background: rgba(100,116,139,.1); color: #475569; border-color: rgba(100,116,139,.25); }

    .exam-card h5.exam-title {
        font-weight: 800;
        color: var(--ink-900);
        font-size: 17px;
        margin: 1rem 0 0.35rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .exam-subject {
        color: var(--ink-500);
        font-weight: 500;
        font-size: 14px;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .exam-subject i { color: var(--brand-600); opacity: .85; }

    .je-date-box {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        background: var(--ink-50);
        border: 1px solid var(--ink-100);
        border-radius: var(--radius-md);
        padding: 0.85rem;
        margin-bottom: 1rem;
    }
    .je-date-chip {
        width: 56px;
        height: 56px;
        flex-shrink: 0;
        border-radius: 14px;
        background: #fff;
        box-shadow: var(--shadow-soft);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .je-date-chip .num {
        color: var(--brand-600);
        font-weight: 800;
        font-size: 19px;
        line-height: 1.1;
    }
    .je-date-chip .mon {
        color: var(--ink-500);
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .je-date-info .label {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--ink-900);
        display: block;
    }
    .je-date-info .value {
        font-size: 12.5px;
        color: var(--ink-500);
        font-weight: 500;
    }

    .je-btn-detail {
        background: var(--brand-50);
        border: none;
        color: var(--brand-700);
        font-weight: 700;
        border-radius: var(--radius-sm);
        padding: 0.7rem;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all .2s ease;
        margin-top: auto;
    }
    .je-btn-detail:hover {
        background: linear-gradient(135deg, var(--brand-400), var(--brand-600));
        color: #fff;
        box-shadow: 0 8px 18px rgba(2, 132, 199, 0.3);
    }

    /* ============================================
       EMPTY STATE
    ============================================ */
    .je-empty {
        border: 1.5px dashed var(--ink-200);
        border-radius: var(--radius-lg);
        background: var(--ink-50);
        text-align: center;
        padding: 3.5rem 1.5rem;
    }
    .je-empty-icon {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        background: #fff;
        box-shadow: var(--shadow-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        font-size: 34px;
        color: var(--ink-300);
    }
    .je-empty h5 { font-weight: 800; color: var(--ink-900); }
    .je-empty p { color: var(--ink-500); font-size: 14px; max-width: 400px; margin: 0 auto; }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 767.98px) {
        .je-header { flex-direction: column; align-items: flex-start; }
        .je-section-bar { flex-direction: column; align-items: flex-start; }
        .je-pills-scroll { justify-content: flex-start; width: 100%; }
        .je-filter-card .row > div { margin-bottom: 0; }
        .exam-card-body { padding: 1.25rem; }
    }
</style>

<div class="container-fluid py-4 px-3 px-md-4 je-wrap">

    <!-- HEADER SECTION -->
    <div class="je-header">
        <div class="d-flex align-items-center gap-3">
            <div class="je-header-icon"><i class="fa-solid fa-calendar-week"></i></div>
            <div>
                <h3 class="mb-0">Jadwal Ujian</h3>
                <p>Pantau dan kelola jadwal ujian siswa dengan mudah dan cepat.</p>
            </div>
        </div>
    </div>

    <!-- FITUR PENCARIAN & FILTER TANGGAL -->
    <div class="card je-filter-card">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('dashboard-guru.jadwal-ujian.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-5">
                        <label for="search" class="je-label">Cari Nama Ujian</label>
                        <div class="je-input-group input-group">
                            <span class="input-group-text">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="search" class="form-control" id="search" name="search" placeholder="Contoh: PTS Ganjil..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="tanggal" class="je-label">Filter Tanggal</label>
                        <input type="date" class="je-date-input" id="tanggal" name="tanggal" value="{{ request('tanggal') }}">
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="submit" class="je-btn-apply flex-fill">
                                <i class="fa-solid fa-filter me-1"></i> Terapkan
                            </button>
                            @if(request('search') || request('tanggal'))
                                <a href="{{ route('dashboard-guru.jadwal-ujian.index') }}" class="je-btn-reset flex-fill text-center text-decoration-none">
                                    <i class="fa-solid fa-rotate-right me-1"></i> Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- BAR TITLE & TAB FILTER PILLS -->
    <div class="je-section-bar">
        <h5 class="je-section-title" id="section-title">
            <i class="fa-solid fa-calendar-week"></i> <span>Jadwal Ujian Aktif</span>
        </h5>

        <div class="je-pills-scroll" role="tablist">
            <button type="button" class="btn-filter-tab" data-filter="semua">
                <i class="fa-solid fa-list-check me-1"></i> Semua Ujian
                <span class="badge rounded-pill ms-1 bg-secondary bg-opacity-25 text-dark">{{ $counts['semua'] ?? 0 }}</span>
            </button>
            <button type="button" class="btn-filter-tab active" data-filter="hari_ini">
                <i class="fa-solid fa-calendar-day me-1"></i> Hari Ini
                <span class="badge rounded-pill ms-1 bg-white text-dark">{{ $counts['hari_ini'] ?? 0 }}</span>
            </button>
            <button type="button" class="btn-filter-tab" data-filter="akan_datang">
                <i class="fa-solid fa-clock me-1"></i> Akan Datang
                <span class="badge rounded-pill ms-1 bg-secondary bg-opacity-25 text-dark">{{ $counts['akan_datang'] ?? 0 }}</span>
            </button>
            <button type="button" class="btn-filter-tab" data-filter="riwayat">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Riwayat Ujian
                <span class="badge rounded-pill ms-1 bg-secondary bg-opacity-25 text-dark">{{ $counts['riwayat'] ?? 0 }}</span>
            </button>
        </div>
    </div>

    <!-- DAFTAR JADWAL UJIAN (GRID CARD) -->
    @if(count($ujians) > 0)
        <div class="row g-4" id="exam-list-container">
            <div class="col-12" id="empty-state" style="display: none;">
                <div class="je-empty">
                    <div class="je-empty-icon"><i class="fa-regular fa-folder-open"></i></div>
                    <h5 class="mb-2">Tidak Ada Data Ujian</h5>
                    <p class="mb-0">Belum ada jadwal ujian untuk kategori ini.</p>
                </div>
            </div>

            @foreach($ujians as $ujian)
                <div class="col-12 col-md-6 col-xl-4 exam-card-wrapper" data-category="{{ $ujian->filter_category }}">
                    <div class="exam-card">
                        <div class="exam-card-accent"></div>
                        <div class="exam-card-body">

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <span class="je-time-badge">
                                    <i class="fa-regular fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }} WIB
                                </span>

                                @if($ujian->status_waktu == 'berlangsung')
                                    <span class="je-status-badge je-status-ongoing">
                                        <i class="fa-solid fa-circle-play"></i> Berlangsung
                                    </span>
                                @elseif($ujian->status_waktu == 'belum')
                                    <span class="je-status-badge je-status-pending">
                                        <i class="fa-regular fa-clock"></i> Belum Mulai
                                    </span>
                                @else
                                    <span class="je-status-badge je-status-done">
                                        <i class="fa-solid fa-circle-check"></i> Selesai
                                    </span>
                                @endif
                            </div>

                            <h5 class="exam-title" title="{{ $ujian->nama_ujian }}">{{ $ujian->nama_ujian }}</h5>
                            <p class="exam-subject">
                                <i class="fa-solid fa-book-open"></i>
                                {{ optional(optional($ujian->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}
                            </p>

                            <div class="mt-auto">
                                <div class="je-date-box">
                                    <div class="je-date-chip">
                                        <span class="num">{{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d') }}</span>
                                        <span class="mon">{{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('M') }}</span>
                                    </div>
                                    <div class="je-date-info">
                                        <span class="label">Pelaksanaan</span>
                                        <span class="value">{{ \Carbon\Carbon::parse($ujian->waktu_mulai)->translatedFormat('l, d F Y') }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('dashboard-guru.jadwal-ujian.show', $ujian->id) }}" class="je-btn-detail text-decoration-none">
                                    Lihat Detail <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="je-empty">
            <div class="je-empty-icon"><i class="fa-regular fa-calendar-xmark"></i></div>
            <h5 class="mb-2">Belum Ada Jadwal Ujian</h5>
            <p class="mb-0">Saat ini tidak ada jadwal ujian yang tersedia atau tidak ada data yang cocok dengan pencarian Anda.</p>
        </div>
    @endif

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterBtns = document.querySelectorAll('.btn-filter-tab');
    const examCards = document.querySelectorAll('.exam-card-wrapper');
    const emptyState = document.getElementById('empty-state');
    const sectionTitleText = document.querySelector('#section-title span');
    const sectionTitleIcon = document.querySelector('#section-title i');

    const titleMap = {
        'semua': { text: 'Semua Jadwal Ujian', icon: 'fa-list-check' },
        'hari_ini': { text: 'Jadwal Ujian Aktif', icon: 'fa-calendar-week' },
        'akan_datang': { text: 'Jadwal Ujian Mendatang', icon: 'fa-clock' },
        'riwayat': { text: 'Riwayat Ujian', icon: 'fa-clock-rotate-left' }
    };

    function applyFilter(filterValue) {
        let visibleCount = 0;

        examCards.forEach(card => {
            if (filterValue === 'semua' || card.dataset.category === filterValue) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        if (titleMap[filterValue] && sectionTitleText && sectionTitleIcon) {
            sectionTitleText.textContent = titleMap[filterValue].text;
            sectionTitleIcon.className = `fa-solid ${titleMap[filterValue].icon}`;
        }
    }

    const hasHariIni = document.querySelectorAll('.exam-card-wrapper[data-category="hari_ini"]').length > 0;
    const initialTab = hasHariIni ? 'hari_ini' : 'semua';

    filterBtns.forEach(btn => {
        const badge = btn.querySelector('.badge');
        if (btn.dataset.filter === initialTab) {
            btn.classList.add('active');
            if (badge) badge.className = 'badge rounded-pill ms-1 bg-white text-dark';
        } else {
            btn.classList.remove('active');
            if (badge) badge.className = 'badge rounded-pill ms-1 bg-secondary bg-opacity-25 text-dark';
        }
    });

    applyFilter(initialTab);

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => {
                b.classList.remove('active');
                const badge = b.querySelector('.badge');
                if (badge) badge.className = 'badge rounded-pill ms-1 bg-secondary bg-opacity-25 text-dark';
            });

            this.classList.add('active');
            const activeBadge = this.querySelector('.badge');
            if (activeBadge) activeBadge.className = 'badge rounded-pill ms-1 bg-white text-dark';

            applyFilter(this.dataset.filter);
        });
    });
});
</script>
@endsection