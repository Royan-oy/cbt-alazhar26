@extends('layouts.app')

@section('title', 'Ujian Hari Ini')

@section('content')
<style>
    :root {
        --sb-bg: #0f172a;
        --sb-card: #1e293b;
        --sb-text-muted: #64748b;
        --sb-text-active: #38bdf8;

        --primary: #0ea5e9;        /* aksen utama: sky blue, senada sidebar */
        --primary-dark: #0284c7;
        --primary-light: #eff8ff;
        --accent-violet: #818cf8;  /* variasi sekunder, dipakai terbatas */
        --accent-violet-light: #eef1ff;

        --ink-900: #0f172a;
        --ink-700: #334155;
        --ink-500: #64748b;
        --ink-400: #94a3b8;
        --surface: #ffffff;
        --border: #e5e9f2;
        --card-shadow: 0 10px 30px -8px rgba(15,23,42,.05), 0 4px 12px -6px rgba(15,23,42,.04);
        --hover-shadow: 0 22px 44px -10px rgba(14,165,233,.16);
    }

    /* =========================================================
       HEADER — samakan dengan gelap sidebar
       ========================================================= */
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

    .cbt-header::before {
        content: '';
        position: absolute;
        bottom: -60%; left: -8%;
        width: 220px; height: 220px;
        background: radial-gradient(circle, rgba(129,140,248,.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .cbt-header h3 { position: relative; font-size: 21px; }
    .cbt-header p { position: relative; font-size: 13.5px; }

    /* =========================================================
       INFO CARD (Data Siswa)
       ========================================================= */
    .info-card {
        background: var(--surface);
        border-radius: 18px;
        border: 1px solid var(--border);
        padding: 22px;
        margin-bottom: 26px;
        box-shadow: var(--card-shadow);
    }

    .info-divider { width: 1px; background-color: var(--border); }

    /* =========================================================
       FILTER PILLS
       ========================================================= */
    .custom-filter-pills {
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding-bottom: 2px;
    }

    .custom-filter-pills::-webkit-scrollbar { display: none; }

    .custom-filter-pills .nav-link {
        color: var(--ink-500);
        background: var(--surface);
        border: 1px solid var(--border);
        transition: all .2s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .custom-filter-pills .nav-link:hover { background: #f1f5f9; }

    .custom-filter-pills .nav-link.active {
        background: linear-gradient(135deg, var(--sb-bg), var(--primary-dark)) !important;
        color: white !important;
        border-color: transparent;
        box-shadow: 0 8px 18px -6px rgba(2,132,199,.4);
    }

    /* =========================================================
       EXAM CARD
       ========================================================= */
    .exam-card {
        background: var(--surface);
        border-radius: 18px;
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all .25s cubic-bezier(.4,0,.2,1);
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: var(--card-shadow);
    }

    .exam-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--hover-shadow);
        border-color: #bae6fd;
    }

    .exam-header {
        padding: 20px 22px;
        border-bottom: 1px solid var(--border);
    }

    .exam-body {
        padding: 22px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    /* =========================================================
       BADGES — status "berlangsung" pakai variasi ungu tipis
       ========================================================= */
    .status-badge {
        font-size: 11px;
        padding: 6px 13px;
        border-radius: 999px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .status-belum { background: var(--primary-light); color: var(--primary-dark); }
    .status-berlangsung { background: var(--accent-violet-light); color: #4f46e5; }
    .status-selesai {
        background: #ecfdf5;
        color: #059669;
    }

    .pulse-dot {
        width: 7px; height: 7px;
        background-color: var(--accent-violet);
        border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(129,140,248,.7);
        animation: pulsing 1.5s infinite;
    }

    @keyframes pulsing {
        0% { transform: scale(.95); box-shadow: 0 0 0 0 rgba(129,140,248,.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(129,140,248,0); }
        100% { transform: scale(.95); box-shadow: 0 0 0 0 rgba(129,140,248,0); }
    }

    /* =========================================================
       DETAIL GRID
       ========================================================= */
    .detail-box {
        background: #f8fafc;
        padding: 13px;
        border-radius: 14px;
        text-align: center;
        border: 1px solid var(--border);
    }

    /* =========================================================
       TOMBOL — aksen biru sidebar
       ========================================================= */
    .btn-masuk {
        background: linear-gradient(135deg, var(--sb-bg), var(--primary-dark));
        color: #fff;
        width: 100%;
        padding: 13px;
        border-radius: 14px;
        font-weight: 700;
        border: none;
        transition: all .2s ease;
        box-shadow: 0 8px 18px -6px rgba(2,132,199,.35);
    }

    .btn-masuk:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 24px -6px rgba(2,132,199,.45);
        color: #fff;
        background: linear-gradient(135deg, var(--sb-card), var(--primary));
    }

    .btn-disabled {
        background: #f1f5f9;
        color: var(--ink-500);
        width: 100%;
        padding: 13px;
        border-radius: 14px;
        font-weight: 700;
        border: none;
        cursor: not-allowed;
    }

    /* =========================================================
       EMPTY STATE
       ========================================================= */
    .empty-box {
        text-align: center;
        padding: 56px 20px;
        border: 2px dashed var(--border);
        border-radius: 22px;
        background: #fafbfc;
    }

    /* =========================================================
       MODAL SUCCESS — icon glow ungu untuk variasi
       ========================================================= */
    .success-icon {
        width: 84px; height: 84px;
        margin: auto;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: var(--accent-violet-light);
        color: #4f46e5;
        font-size: 40px;
        animation: pop .4s ease;
    }

    @keyframes pop {
        from { transform: scale(.5); opacity: 0; }
        to   { transform: scale(1); opacity: 1; }
    }

    .modal-content { border-radius: 22px !important; }

    /* =========================================================
       RESPONSIVE — TABLET
       ========================================================= */
    @media (max-width: 991.98px) {
        .info-card .col-md-4,
        .info-card .col-md-3 { margin-bottom: 14px; }
        .info-divider { display: none !important; }
    }

    /* =========================================================
       RESPONSIVE — HP
       ========================================================= */
    @media (max-width: 575.98px) {
        .container-fluid.px-4 { padding-left: 12px !important; padding-right: 12px !important; }

        .cbt-header { padding: 22px 20px; border-radius: 18px; margin-bottom: 18px;}
        .cbt-header .d-flex { flex-wrap: wrap; }
        .cbt-header h3 { font-size: 17px; }
        .cbt-header p { font-size: 12px; }
        .cbt-header .p-3.rounded-4 { padding: 10px !important; margin-bottom: 10px; }

        .info-card { padding: 16px; border-radius: 16px; margin-bottom: 18px; }
        .info-card h6 { font-size: 13px; }

        #section-title { font-size: 14.5px; }

        .custom-filter-pills .nav-link { font-size: 12px !important; padding: 8px 13px !important; }

        .exam-header { padding: 16px 16px; }
        .exam-header h6 { max-width: 150px !important; font-size: 13.5px; }
        .exam-header small { font-size: 11px; }

        .exam-body { padding: 16px; }
        .exam-body .p-3.rounded-4 { padding: 12px !important; margin-bottom: 16px !important; }

        .detail-box { padding: 10px; border-radius: 12px; }
        .detail-box small { font-size: 10px; }
        .detail-box .fw-bold { font-size: 12.5px; }

        .btn-masuk, .btn-disabled { padding: 12px; font-size: 13px; border-radius: 12px; }

        .empty-box { padding: 40px 16px; border-radius: 18px; }

        .modal-body.text-center.p-5 { padding: 32px 22px !important; }
        .success-icon { width: 68px; height: 68px; font-size: 32px; }
    }
</style>

<div class="container-fluid px-4 py-2">

    {{-- HEADER --}}
    <div class="cbt-header shadow-sm">
        <div class="d-flex align-items-center">
            <div class="me-3 bg-white bg-opacity-10 p-3 rounded-4">
                <i class="fa-solid fa-laptop-code fa-2x text-white"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-1">Ujian Hari Ini</h3>
                <p class="mb-0 text-white-50">Silakan cek jadwal ujian Computer Based Test (CBT) Anda di bawah ini.</p>
            </div>
        </div>
    </div>

    {{-- DATA SISWA --}}
    <div class="info-card">
        <div class="row align-items-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="d-flex align-items-center">
                    <div class="bg-light p-2 rounded-3 me-3" style="color: var(--accent-blue);">
                        <i class="fa-solid fa-user-graduate fa-lg"></i>
                    </div>
                    <div>
                        <small class="text-uppercase fw-bold d-block" style="font-size: 10px; letter-spacing: 0.5px; color: var(--text-muted);">Nama Siswa</small>
                        <h6 class="fw-bold mb-0" style="color: var(--primary-dark);">{{ $siswa->nama }}</h6>
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
                        <small class="text-uppercase fw-bold d-block" style="font-size: 10px; letter-spacing: 0.5px; color: var(--text-muted);">Kelas Aktif</small>
                        <h6 class="fw-bold mb-0" style="color: var(--primary-dark);">{{ optional($kelasAktif)->nama_kelas ?? '-' }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-1 d-none d-md-block text-center">
                <div class="info-divider d-inline-block" style="height: 40px;"></div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div class="bg-light p-2 rounded-3 me-3" style="color: var(--accent-blue);">
                        <i class="fa-solid fa-file-signature fa-lg"></i>
                    </div>
                    <div>
                        <small class="text-uppercase fw-bold d-block" style="font-size: 10px; letter-spacing: 0.5px; color: var(--text-muted);">Total Agenda</small>
                        <h6 class="fw-bold mb-0" style="color: var(--accent-blue);">{{ $ujians->count() }} Ujian Hari Ini</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DAFTAR UJIAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h5 class="fw-bold mb-0" id="section-title" style="color: var(--primary-dark);">
            <i class="fa-solid fa-calendar-week me-2" style="color: var(--accent-blue);"></i> <span>Jadwal Ujian Aktif</span>
        </h5>
        
       <div class="nav nav-pills custom-filter-pills gap-2" role="tablist">
            <button class="nav-link active rounded-pill px-3 py-2 btn-filter-tab" data-filter="hari_ini" style="font-size: 13px; font-weight: 600;">
                <i class="fa-solid fa-calendar-day me-1"></i> Hari Ini
            </button>
            <button class="nav-link rounded-pill px-3 py-2 btn-filter-tab" data-filter="akan_datang" style="font-size: 13px; font-weight: 600;">
                <i class="fa-solid fa-clock me-1"></i> Akan Datang
            </button>
            <button class="nav-link rounded-pill px-3 py-2 btn-filter-tab" data-filter="riwayat" style="font-size: 13px; font-weight: 600;">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Riwayat Ujian
            </button>
        </div>
    </div>

    @if($ujians->count() > 0)
        <div class="row g-4" id="exam-list-container">
            <!-- Empty state (hidden by default) -->
            <div class="col-12" id="empty-state" style="display: none;">
                <div class="text-center py-5">
                    <i class="fa-regular fa-folder-open mb-3" style="font-size: 48px; color: var(--border-color);"></i>
                    <h5 class="fw-bold" style="color: var(--text-muted);">Tidak ada data ujian</h5>
                    <p class="text-muted small">Belum ada jadwal ujian untuk kategori ini.</p>
                </div>
            </div>

            @foreach($ujians as $ujian)
                <div class="col-md-6 col-xl-4 exam-card-wrapper" data-category="{{ $ujian->filter_category }}">
                    <div class="exam-card">
                        
                        <div class="exam-header">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <h6 class="fw-bold mb-1 text-truncate" style="max-width: 180px; color: var(--primary-dark);">
                                        {{ $ujian->nama_ujian }}
                                    </h6>
                                    <small style="color: var(--text-muted);">
                                        <i class="fa-solid fa-tag me-1"></i> {{ optional($ujian->jenisUjian)->nama ?? 'Ujian' }}
                                    </small>
                                </div>

                                @if($ujian->status == 'berlangsung')
                                    <span class="status-badge status-berlangsung" style="color: var(--accent-blue); background: #f0f9ff;">
                                        <span class="pulse-dot"></span> Aktif
                                    </span>
                                @elseif($ujian->status == 'belum')
                                    <span class="status-badge status-belum">
                                        <i class="fa-regular fa-calendar me-1"></i> Belum Mulai
                                    </span>
                                @else
                                    <span class="status-badge status-selesai">
                                        <i class="fa-solid fa-circle-check me-1"></i> Selesai
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="exam-body">
                            <div class="p-3 rounded-4 mb-4" style="background-color: #f8fafc; border: 1px solid var(--border-color);">
                                <small class="text-uppercase fw-bold d-block mb-1" style="font-size: 10px; color: var(--text-muted);">Mata Pelajaran</small>
                                <span class="fw-bold" style="color: var(--primary-dark); font-size: 0.95rem;">
                                    <i class="fa-solid fa-book-bookmark me-2" style="color: var(--accent-blue);"></i>
                                    {{ optional(optional($ujian->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}
                                </span>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="detail-box">
                                        <small class="d-block mb-1" style="font-size: 11px; color: var(--text-muted);">Jam Mulai</small>
                                        <div class="fw-bold" style="color: var(--primary-dark);">
                                            <i class="fa-regular fa-clock me-1" style="color: var(--accent-blue);"></i>
                                            {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('H:i') }} WIB
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="detail-box">
                                        <small class="d-block mb-1" style="font-size: 11px; color: var(--text-muted);">Jam Selesai</small>
                                        <div class="fw-bold" style="color: var(--primary-dark);">
                                            <i class="fa-regular fa-clock text-danger me-1"></i>
                                            {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }} WIB
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-auto">
                                @if($ujian->status_siswa == 'selesai')


                                <button class="btn-disabled d-flex align-items-center justify-content-center gap-2"
                                        style="background:#dcfce7;color:#15803d;">

                                    <i class="fa-solid fa-circle-check"></i>

                                    Sudah Dikerjakan

                                </button>



                                @elseif($ujian->status == 'berlangsung')


                                <a href="{{ route('dashboard-siswa.ujian.mulai',$ujian->id) }}"
                                class="btn-masuk d-block text-center text-decoration-none">

                                    <i class="fa-solid fa-play me-2"></i>

                                    Kerjakan Ujian

                                </a>



                                @elseif($ujian->status == 'belum')


                                <button class="btn-disabled d-flex align-items-center justify-content-center gap-2"
                                        disabled>

                                    <i class="fa-solid fa-lock"></i>

                                    Belum Dibuka

                                </button>



                                @else


                                <button class="btn-disabled d-flex align-items-center justify-content-center gap-2"
                                        disabled>

                                    <i class="fa-solid fa-clock"></i>

                                    Waktu Berakhir

                                </button>


                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-box py-5 shadow-sm">
            <div class="mb-3">
                <i class="fa-solid fa-calendar-xmark fa-4x" style="color: var(--text-muted); opacity: 0.4;"></i>
            </div>
            <h5 class="fw-bold mb-1" style="color: var(--primary-dark);">Belum Ada Jadwal Ujian</h5>
            <p class="mb-0" style="color: var(--text-muted);">Tidak ada agenda ujian CBT yang dijadwalkan untuk kelas Anda hari ini.</p>
        </div>
    @endif

</div>

{{-- MODAL SUCCESS --}}
@if(session('success'))

<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">


            <div class="modal-body text-center p-5">


                <div class="success-icon mb-4">

                    @if(session('auto_submit'))
                        <i class="fa-solid fa-triangle-exclamation text-danger"></i>
                    @else
                        <i class="fa-solid fa-circle-check text-success"></i>
                    @endif

                </div>


                <h4 class="fw-bold mb-2">

                    @if(session('auto_submit'))
                        Ujian Dikumpulkan Otomatis
                    @else
                        Ujian Berhasil Dikumpulkan
                    @endif

                </h4>

                <p class="text-muted mb-4">

                    {{ session('success') }}

                    <br>

                    @if(session('auto_submit'))
                        Jawaban yang belum diisi dianggap salah.
                    @else
                        Jawaban Anda sudah tersimpan dan tidak dapat dikerjakan kembali.
                    @endif

                </p>
                
                <button type="button"
                        class="btn-masuk px-5"
                        data-bs-dismiss="modal">

                    <i class="fa-solid fa-check me-2"></i>

                    Mengerti

                </button>


            </div>


        </div>

    </div>

</div>


@endif

@if(session('success'))

<script>

document.addEventListener("DOMContentLoaded",function(){

    let modal = new bootstrap.Modal(
        document.getElementById('successModal')
    );

    modal.show();

});

</script>

@endif

<script>
document.addEventListener("DOMContentLoaded", function() {
    const filterBtns = document.querySelectorAll('.btn-filter-tab');
    const examCards = document.querySelectorAll('.exam-card-wrapper');
    const emptyState = document.getElementById('empty-state');
    const sectionTitleText = document.querySelector('#section-title span');
    const sectionTitleIcon = document.querySelector('#section-title i');

    const titleMap = {
        'hari_ini': { text: 'Jadwal Ujian Aktif', icon: 'fa-calendar-week' },
        'akan_datang': { text: 'Jadwal Ujian Mendatang', icon: 'fa-clock' },
        'riwayat': { text: 'Riwayat Ujian', icon: 'fa-clock-rotate-left' }
    };

    function applyFilter(filterValue) {
        let visibleCount = 0;

        examCards.forEach(card => {
            if (card.dataset.category === filterValue) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        // Update title
        if (titleMap[filterValue] && sectionTitleText && sectionTitleIcon) {
            sectionTitleText.textContent = titleMap[filterValue].text;
            sectionTitleIcon.className = `fa-solid ${titleMap[filterValue].icon} me-2`;
        }
    }

    // Initialize with default active tab (hari_ini)
    applyFilter('hari_ini');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active state on buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filterValue = this.dataset.filter;
            applyFilter(filterValue);
        });
    });
});
</script>

@endsection