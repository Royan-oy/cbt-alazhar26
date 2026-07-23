@extends('layouts.app')

@section('content')
<style>
    /* --- CUSTOM STYLES FOR MODERN UI --- */
    .bg-soft-primary {
        background-color: #f0f9ff;
    }
    .text-primary-custom {
        color: #0284c7;
    }
    .btn-primary-custom {
        background: linear-gradient(135deg, #38bdf8, #0284c7);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: white;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
    }
    .btn-light-custom {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        transition: all 0.3s ease;
    }
    .btn-light-custom:hover {
        background-color: #e2e8f0;
        color: #334155;
    }
    .modern-card {
        border: 1px solid rgba(0,0,0,0.03);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }
    .card-top-accent {
        height: 4px;
        width: 100%;
        background: linear-gradient(90deg, #38bdf8, #0284c7);
    }
    .date-box {
        width: 60px;
        height: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-radius: 12px;
    }
    .form-control-modern {
        border-radius: 10px;
        padding: 0.6rem 1rem;
        border: 1px solid #cbd5e1;
        background-color: #f8fafc;
    }
    .form-control-modern:focus {
        background-color: #ffffff;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
    }
    .input-group-text-modern {
        background-color: #f8fafc;
        border: 1px solid #cbd5e1;
        border-right: none;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }
    
    /* FILTER PILLS STYLING (SAMAKAN DENGAN DISPLAY SISWA) */
    .custom-filter-pills .btn-filter-tab {
        color: #64748b;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
    }
    .custom-filter-pills .btn-filter-tab:hover {
        background-color: #e2e8f0;
        color: #334155;
    }
    .custom-filter-pills .btn-filter-tab.active {
        background: #0284c7 !important;
        color: #ffffff !important;
        border-color: #0284c7;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    }
</style>

<div class="container-fluid py-4 px-md-4">
    
    <!-- HEADER SECTION -->
    <div class="mb-4">
        <h3 class="fw-bolder text-dark mb-1">Jadwal Ujian</h3>
        <p class="text-muted fw-medium mb-0" style="font-size: 14px;">Pantau dan kelola jadwal ujian siswa dengan mudah dan cepat.</p>
    </div>

    <!-- FITUR PENCARIAN & FILTER TANGGAL -->
    <div class="card border-0 shadow-sm mb-4 rounded-4" style="background-color: #ffffff;">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('dashboard-guru.jadwal-ujian.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <!-- Cari Nama -->
                    <div class="col-12 col-md-5">
                        <label for="search" class="form-label text-muted fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.8px;">CARI NAMA UJIAN</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-modern">
                                <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            </span>
                            <input type="search" class="form-control form-control-modern border-start-0 ps-0" id="search" name="search" placeholder="Contoh: PTS Ganjil..." value="{{ request('search') }}">
                        </div>
                    </div>
                    
                    <!-- Filter Tanggal -->
                    <div class="col-12 col-md-4">
                        <label for="tanggal" class="form-label text-muted fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.8px;">FILTER TANGGAL</label>
                        <input type="date" class="form-control form-control-modern" id="tanggal" name="tanggal" value="{{ request('tanggal') }}">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="col-12 col-md-3">
                        <div class="d-grid gap-2 d-flex flex-column flex-sm-row">
                            <button type="submit" class="btn btn-primary-custom w-100 rounded-3 py-2 fw-semibold">
                                <i class="fa-solid fa-filter me-1"></i> Terapkan
                            </button>
                            @if(request('search') || request('tanggal'))
                                <a href="{{ route('dashboard-guru.jadwal-ujian.index') }}" class="btn btn-light-custom w-100 rounded-3 py-2 fw-semibold">
                                    <i class="fa-solid fa-rotate-right me-1"></i> Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- BAR TITLE & TAB FILTER PILLS (POSISI SAMA DENGAN TABEL SISWA) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h5 class="fw-bold mb-0" id="section-title" style="color: #0f172a;">
            <i class="fa-solid fa-calendar-week me-2" style="color: #0284c7;"></i> <span>Jadwal Ujian Aktif</span>
        </h5>
        
        <div class="nav nav-pills custom-filter-pills gap-2 flex-wrap" role="tablist">
            <button type="button" class="nav-link rounded-pill px-3 py-2 btn-filter-tab" data-filter="semua" style="font-size: 13px; font-weight: 600;">
                <i class="fa-solid fa-list-check me-1"></i> Semua Ujian
                <span class="badge rounded-pill ms-1 bg-secondary bg-opacity-25 text-dark">{{ $counts['semua'] ?? 0 }}</span>
            </button>
            <button type="button" class="nav-link active rounded-pill px-3 py-2 btn-filter-tab" data-filter="hari_ini" style="font-size: 13px; font-weight: 600;">
                <i class="fa-solid fa-calendar-day me-1"></i> Hari Ini
                <span class="badge rounded-pill ms-1 bg-white text-dark">{{ $counts['hari_ini'] ?? 0 }}</span>
            </button>
            <button type="button" class="nav-link rounded-pill px-3 py-2 btn-filter-tab" data-filter="akan_datang" style="font-size: 13px; font-weight: 600;">
                <i class="fa-solid fa-clock me-1"></i> Akan Datang
                <span class="badge rounded-pill ms-1 bg-secondary bg-opacity-25 text-dark">{{ $counts['akan_datang'] ?? 0 }}</span>
            </button>
            <button type="button" class="nav-link rounded-pill px-3 py-2 btn-filter-tab" data-filter="riwayat" style="font-size: 13px; font-weight: 600;">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Riwayat Ujian
                <span class="badge rounded-pill ms-1 bg-secondary bg-opacity-25 text-dark">{{ $counts['riwayat'] ?? 0 }}</span>
            </button>
        </div>
    </div>

    <!-- DAFTAR JADWAL UJIAN (GRID CARD) -->
    @if(count($ujians) > 0)
        <div class="row g-4" id="exam-list-container">
            <!-- Empty state (ditampilkan JS jika filter tab tidak memiliki data) -->
            <div class="col-12" id="empty-state" style="display: none;">
                <div class="card border-0 shadow-sm rounded-4 text-center p-5" style="background-color: #f8fafc;">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fa-regular fa-folder-open text-muted" style="font-size: 32px;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bolder text-dark mb-2">Tidak Ada Data Ujian</h5>
                    <p class="text-muted mb-0 mx-auto" style="max-width: 400px; font-size: 14px;">Belum ada jadwal ujian untuk kategori ini.</p>
                </div>
            </div>

            @foreach($ujians as $ujian)
                <div class="col-12 col-md-6 col-xl-4 exam-card-wrapper" data-category="{{ $ujian->filter_category }}">
                    <div class="card h-100 modern-card bg-white">
                        <div class="card-top-accent"></div>
                        <div class="card-body p-4 d-flex flex-column">
                            
                            <!-- Header Card: Badge Waktu & Realtime Status Badge -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-soft-primary text-primary-custom px-3 py-2 rounded-pill fw-semibold" style="font-size: 12px;">
                                    <i class="fa-regular fa-clock me-1"></i> 
                                    {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }} WIB
                                </span>

                                @if($ujian->status_waktu == 'berlangsung')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-semibold" style="font-size: 11px;">
                                        <i class="fa-solid fa-circle-play me-1"></i> Berlangsung
                                    </span>
                                @elseif($ujian->status_waktu == 'belum')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill fw-semibold" style="font-size: 11px;">
                                        <i class="fa-regular fa-clock me-1"></i> Belum Mulai
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill fw-semibold" style="font-size: 11px;">
                                        <i class="fa-solid fa-circle-check me-1"></i> Selesai
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Info Utama -->
                            <h5 class="fw-bolder text-dark mb-2 text-truncate" title="{{ $ujian->nama_ujian }}">{{ $ujian->nama_ujian }}</h5>
                            <p class="text-muted fw-medium mb-4 fs-6">
                                <i class="fa-solid fa-book-open text-primary-custom me-2 opacity-75"></i> 
                                {{ optional(optional($ujian->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}
                            </p>

                            <div class="mt-auto">
                                <!-- Box Tanggal Modern -->
                                <div class="d-flex align-items-center bg-light rounded-4 p-3 mb-3 border" style="border-color: #f1f5f9 !important;">
                                    <div class="date-box bg-white shadow-sm me-3">
                                        <span class="d-block text-primary-custom fw-black fs-4 lh-1">{{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d') }}</span>
                                        <span class="d-block text-muted mt-1" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">{{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('M') }}</span>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark mb-1" style="font-size: 13px;">Pelaksanaan</span>
                                        <span class="text-muted fw-medium" style="font-size: 13px;">{{ \Carbon\Carbon::parse($ujian->waktu_mulai)->translatedFormat('l, d F Y') }}</span>
                                    </div>
                                </div>

                                <!-- Tombol Aksi -->
                                <a href="{{ route('dashboard-guru.jadwal-ujian.show', $ujian->id) }}" class="btn btn-light-custom w-100 rounded-3 py-2 fw-bold text-primary-custom">
                                    Lihat Detail <i class="fa-solid fa-arrow-right ms-2 fs-7"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 text-center p-5" style="background-color: #f8fafc;">
            <div class="d-flex justify-content-center mb-3">
                <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fa-regular fa-calendar-xmark text-muted" style="font-size: 32px;"></i>
                </div>
            </div>
            <h5 class="fw-bolder text-dark mb-2">Belum Ada Jadwal Ujian</h5>
            <p class="text-muted mb-0 mx-auto" style="max-width: 400px; font-size: 14px;">Saat ini tidak ada jadwal ujian yang tersedia atau tidak ada data yang cocok dengan pencarian Anda.</p>
        </div>
    @endif

</div>

<!-- SCRIPT FILTER CLIENT-SIDE INSTAN (SAMAKAN LOGIKA DENGAN SISWA) -->
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

        // Update title & icon
        if (titleMap[filterValue] && sectionTitleText && sectionTitleIcon) {
            sectionTitleText.textContent = titleMap[filterValue].text;
            sectionTitleIcon.className = `fa-solid ${titleMap[filterValue].icon} me-2`;
        }
    }

    // Tentukan tab awal yang aktif: jika ada ujian hari ini -> 'hari_ini', jika tidak ada -> 'semua'
    const hasHariIni = document.querySelectorAll('.exam-card-wrapper[data-category="hari_ini"]').length > 0;
    const initialTab = hasHariIni ? 'hari_ini' : 'semua';

    // Set styling tombol tab awal
    filterBtns.forEach(btn => {
        const badge = btn.querySelector('.badge');
        if (btn.dataset.filter === initialTab) {
            btn.classList.add('active');
            if (badge) {
                badge.className = 'badge rounded-pill ms-1 bg-white text-dark';
            }
        } else {
            btn.classList.remove('active');
            if (badge) {
                badge.className = 'badge rounded-pill ms-1 bg-secondary bg-opacity-25 text-dark';
            }
        }
    });

    // Jalankan filter pertama kali
    applyFilter(initialTab);

    // Event listener perpindahan tab
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => {
                b.classList.remove('active');
                const badge = b.querySelector('.badge');
                if (badge) {
                    badge.className = 'badge rounded-pill ms-1 bg-secondary bg-opacity-25 text-dark';
                }
            });
            
            this.classList.add('active');
            const activeBadge = this.querySelector('.badge');
            if (activeBadge) {
                activeBadge.className = 'badge rounded-pill ms-1 bg-white text-dark';
            }
            
            const filterValue = this.dataset.filter;
            applyFilter(filterValue);
        });
    });
});
</script>
@endsection