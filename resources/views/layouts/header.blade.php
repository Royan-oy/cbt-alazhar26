<style>
    /* --- CORE NAVBAR STYLE (MODERN FLOATING & STICKY) --- */
    .cbt-header-container {
        padding: 15px 24px 5px 24px;
        background-color: #f8fafc; /* Menyamai background main content */
        
        /* Tambahkan 3 baris kode di bawah ini agar sticky saat di-scroll */
        position: sticky;
        top: 0;
        z-index: 1020; /* Berada di atas konten utama, tapi di bawah sidebar mobile */
    }

    .cbt-navbar-modern {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        padding: 10px 20px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.03);
        transition: all 0.3s ease;
    }

    /* --- ACADEMIC BADGE SYSTEM --- */
    .academic-pill {
        background-color: #f1f5f9;
        border-radius: 30px;
        padding: 6px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid #e2e8f0;
    }

    .academic-pill .status-indicator {
        position: relative;
        display: flex;
        height: 10px;
        width: 10px;
    }

    .academic-pill .status-indicator .animate-ping {
        position: absolute;
        display: inline-flex;
        height: 100%;
        width: 100%;
        border-radius: 50%;
        opacity: 0.6;
    }

    .academic-pill .status-indicator .core-dot {
        position: relative;
        display: inline-flex;
        border-radius: 50%;
        height: 10px;
        width: 10px;
    }

    /* Warna Aktif (Emerald) */
    .academic-pill.active-year .animate-ping { background-color: #34d399; }
    .academic-pill.active-year .core-dot { background-color: #10b981; }
    
    /* Warna Inaktif (Rose) */
    .academic-pill.inactive-year .animate-ping { background-color: #f87171; }
    .academic-pill.inactive-year .core-dot { background-color: #ef4444; }

    .semester-badge-premium {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }

    /* --- PROFILE & LOGOUT --- */
    .user-avatar-placeholder {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        color: #334155;
        font-weight: 700;
        font-size: 13px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ffffff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .btn-logout-premium {
        background-color: transparent;
        color: #64748b;
        border: 1px solid transparent;
        padding: 8px 14px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-logout-premium:hover {
        background-color: #fff1f2;
        color: #f43f5e;
        border-color: #ffe4e6;
    }

    /* --- MOBILE TOGGLE BUTTON --- */
    .btn-burger-modern {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        color: #334155;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .btn-burger-modern:hover {
        background-color: #f1f5f9;
        transform: scale(1.03);
    }

    /* ========================================== */
    /* --- RESPONSIVE MEDIA QUERIES (MEDIA HP) --- */
    /* ========================================== */
    @media (max-width: 767.98px) {
        .cbt-header-container {
            padding: 12px 12px 0 12px; /* Margin dipersempit di HP */
        }
        
        .cbt-navbar-modern {
            padding: 8px 12px; /* Padding dalam navbar lebih ringkas */
        }

        .academic-pill {
            padding: 5px 10px;
            font-size: 11px !important;
            gap: 6px;
        }

        .academic-pill .text-label-ay {
            display: none; /* Sembunyikan teks tulisan "Tahun Ajaran:" di HP agar muat */
        }

        .semester-badge-premium {
            padding: 2px 6px;
            font-size: 9px;
        }

        .btn-logout-premium {
            padding: 8px; /* Hanya menyisakan icon di layar sangat kecil jika perlu */
        }
    }
</style>

<div class="cbt-header-container">
    <nav class="navbar navbar-expand cbt-navbar-modern">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0">
            
            <div class="d-flex align-items-center gap-2 gap-md-3">
                <button class="btn btn-burger-modern d-md-none" type="button" id="sidebarToggle" onclick="toggleSidebarMobile()">
                    <i class="fa-solid fa-bars-staggered fs-5"></i>
                </button>

                @if(isset($tahunAktif) && $tahunAktif)
                    <div class="academic-pill active-year">
                        <span class="status-indicator">
                            <span class="animate-ping position-absolute inline-flex h-100 w-100 rounded-circle opacity-75"></span>
                            <span class="core-dot relative inline-flex rounded-circle"></span>
                        </span>
                        <span class="text-secondary small font-medium text-label-ay">Tahun Ajaran:</span>
                        <span class="text-dark fw-bold small">{{ $tahunAktif->nama_tahun }}</span>
                        <span class="semester-badge-premium">
                            {{ $tahunAktif->semester }}
                        </span>
                    </div>
                @else
                    <div class="academic-pill inactive-year">
                        <span class="status-indicator">
                            <span class="animate-ping position-absolute inline-flex h-100 w-100 rounded-circle opacity-75"></span>
                            <span class="core-dot relative inline-flex rounded-circle"></span>
                        </span>
                        <span class="text-danger fw-bold small" style="font-size: 12px;">Tahun Ajaran Off</span>
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="text-end d-none d-sm-block">
                        <p class="mb-0 text-dark fw-bold" style="font-size: 13px; letter-spacing: -0.1px;">{{ Auth::user()->nama }}</p>
                        <span class="text-uppercase fw-bold text-muted" style="font-size: 9px; letter-spacing: 0.5px; opacity: 0.7;">
                            {{ str_replace('_', ' ', Auth::user()->role) }}
                        </span>
                    </div>
                    <div class="user-avatar-placeholder">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 2)) }}
                    </div>
                </div>
                
                <div class="vr bg-secondary opacity-20 d-none d-sm-block" style="height: 24px;"></div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-logout-premium">
                        <i class="fa-solid fa-right-from-bracket fs-6"></i>
                        <span class="d-none d-md-inline">Keluar</span>
                    </button>
                </form>
            </div>

        </div>
    </nav>
</div>

<div class="sidebar-overlay d-md-none" id="sidebarOverlay" onclick="toggleSidebarMobile()"></div>