<style>
    /* --- SIDEBAR BASE STYLE --- */
    .cbt-sidebar {
        width: 280px;
        min-width: 280px;
        max-width: 280px;
        background-color: #0b0f19; /* Warna Gelap Premium Modern */
        height: 100vh; /* Mengunci tinggi tepat sesuai layar browser */
        position: sticky;
        top: 0;
        display: flex;
        flex-direction: column;
        border-right: 1px solid rgba(255, 255, 255, 0.04);
        z-index: 1040;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* --- BRAND SECTION --- */
    .sidebar-brand-section {
        padding: 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        background-color: #070a12;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        flex-shrink: 0; /* Mencegah logo gepeng/menciut saat menu di-scroll */
    }

    .brand-logo-img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        margin-bottom: 16px;
    }

    /* --- AREA MENU UTAMA (BISA DI-SCROLL) --- */
    .sidebar-menu-scroll {
        flex-grow: 1;
        padding: 24px 16px;
        overflow-y: auto; /* Otomatis memunculkan scroll jika menu penuh */
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch; /* Efek scroll halus/bouncy khusus di HP iOS/Safari */
    }

    /* Desain Scrollbar Minimalis agar Tetap Estetik */
    .sidebar-menu-scroll::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar-menu-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-menu-scroll::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }
    
    .sidebar-menu-scroll:hover::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2); /* Scollbar sedikit lebih jelas saat di-hover */
    }

    /* --- GROUP LABEL --- */
    .menu-group-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #475569;
        padding-left: 12px;
        margin-top: 20px;
        margin-bottom: 8px;
        display: block;
    }

    .menu-group-label:first-of-type {
        margin-top: 0;
    }

    /* --- NAV LINK STYLING --- */
    .cbt-sidebar .nav-link {
        color: #94a3b8;
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 4px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .cbt-sidebar .nav-link i {
        font-size: 16px;
        width: 24px;
        transition: transform 0.2s ease;
    }

    .cbt-sidebar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.04);
        color: #f8fafc;
    }

    .cbt-sidebar .nav-link:hover i {
        transform: scale(1.1);
        color: #38bdf8;
    }

    .cbt-sidebar .nav-link.active {
        background: linear-gradient(135deg, rgba(2, 132, 199, 0.15), rgba(2, 132, 199, 0.05));
        color: #38bdf8;
        font-weight: 600;
        border: 1px solid rgba(56, 189, 248, 0.2);
    }

    .cbt-sidebar .nav-link.active i {
        color: #38bdf8;
    }

    /* --- FLOATING PROFILE AT BOTTOM --- */
    .sidebar-footer-profile {
        padding: 16px;
        background-color: #070a12;
        border-top: 1px solid rgba(255, 255, 255, 0.04);
        flex-shrink: 0; /* Mencegah profil terpotong atau mengecil */
    }

    .profile-floating-box {
        background-color: #111827;
        border: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 14px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .avatar-circle-premium {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, #1e293b, #334155);
        color: #e2e8f0;
        font-weight: 700;
        font-size: 13px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .role-badge-glow {
        background-color: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
        margin-top: 4px;
    }

    /* ========================================== */
    /* --- RESPONSIVE MEDIA QUERIES (MEDIA HP) --- */
    /* ========================================== */
    @media (max-width: 767.98px) {
        .cbt-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            transform: translateX(-100%);
            height: 100vh; /* Memastikan tinggi full layar HP */
        }
        
        .cbt-sidebar.mobile-show {
            transform: translateX(0);
            box-shadow: 20px 0 40px rgba(0, 0, 0, 0.5);
        }
    }
</style>

<aside class="cbt-sidebar sidebar text-white">
    <div class="sidebar-brand-section">
        <img src="{{ asset('img/alazhar-putih.png') }}" alt="Logo Al Azhar" class="brand-logo-img" onerror="this.style.display='none'; document.getElementById('fallback-logo').classList.remove('d-none')">

        <div id="fallback-logo" class="brand-logo-box-secondary d-none d-flex align-items-center justify-content-center text-white font-black rounded-3 mb-3" 
                 style="width: 60px; height: 60px; background: linear-gradient(135deg, #38bdf8, #0284c7);">
                <i class="fa-solid fa-mosque fs-4"></i>
        </div>

        <div>
            <h6 class="fw-bold tracking-wide text-white mb-0" style="font-size: 14px; letter-spacing: 0.3px;">CBT SMART ONLINE</h6>
            <span class="text-uppercase fw-bold d-block mt-1" style="font-size: 10px; color: #38bdf8; letter-spacing: 0.5px;">Sekolah Islam Al Azhar</span>
            <span class="text-muted font-semibold d-block mt-0.5" style="font-size: 10px; color: #64748b !important;">Pekalongan</span>
        </div>
    </div>

    <div class="sidebar-menu-scroll">
        
        <span class="menu-group-label">Utama</span>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high me-3"></i>
                    <span>Dashboard Center</span>
                </a>
            </li>
        </ul>

        {{-- ========================================================= --}}
        {{-- SUPER ADMIN --}}
        {{-- ========================================================= --}}

        @if(Auth::user()->role == 'super_admin')

        <span class="menu-group-label">Master Data</span>

        <ul class="nav flex-column mb-2">

            <li>
                <a href="{{ route('jenjang.index') }}"
                    class="nav-link {{ request()->routeIs('jenjang.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group me-3"></i>
                    Jenjang
                </a>
            </li>

            <li>
                <a href="{{ route('tahun-ajaran.index') }}"
                    class="nav-link {{ request()->routeIs('tahun-ajaran.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days me-3"></i>
                    Tahun Ajaran
                </a>
            </li>

            <li>
                <a href="{{ route('jenis-ujian.index') }}"
                class="nav-link {{ request()->routeIs('jenis-ujian.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check me-3"></i>
                    Jenis Ujian
                </a>
            </li>

        </ul>

        @endif

        {{-- ========================================================= --}}
        {{-- SUPER ADMIN & ADMIN JENJANG --}}
        {{-- ========================================================= --}}

        @if(in_array(Auth::user()->role, ['super_admin', 'admin_jenjang']))

            <span class="menu-group-label">Akademik</span>

            <ul class="nav flex-column mb-2">

                <li>
                    <a href="{{ route('tingkat.index') }}"
                    class="nav-link {{ request()->routeIs('tingkat.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-school me-3"></i>
                        Tingkat
                    </a>
                </li>

                <li>
                    <a href="{{ route('kelas.index') }}"
                        class="nav-link {{ request()->routeIs('kelas.*') ? 'active' : '' }}">

                        <i class="fa-solid fa-door-open me-3"></i>

                        Kelas

                    </a>
                </li>

                <li>
                    <a href="{{ route('mata-pelajaran.index') }}"
                        class="nav-link {{ request()->routeIs('mata-pelajaran.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-book-open me-3"></i>
                        Mata Pelajaran
                    </a>
                </li>

            </ul>

        @endif

        @if(in_array(Auth::user()->role, ['super_admin', 'admin_jenjang']))

            <span class="menu-group-label">Pengguna</span>

            <ul class="nav flex-column mb-2">

                @if(Auth::user()->role == 'super_admin')
                <li>
                    <a href="{{ route('admin-jenjang.index') }}"
                        class="nav-link {{ request()->routeIs('admin-jenjang.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-shield me-3"></i>
                        Admin Jenjang
                    </a>
                </li>
                @endif

                <li>
                    <a href="{{ route('guru.index') }}"
                        class="nav-link {{ request()->routeIs('guru.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-chalkboard-user me-3"></i>
                        Data Guru
                    </a>
                </li>

                <li>
                    <a href="{{ route('guru-mapel.index') }}"
                        class="nav-link {{ request()->routeIs('guru-mapel.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-book-open me-3"></i>
                        Guru Mapel
                    </a>
                </li>

                <li>
                    <a href="{{ route('wali-kelas.index') }}"
                        class="nav-link {{ request()->routeIs('wali-kelas.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users-gear me-3"></i>
                        Wali Kelas
                    </a>
                </li>

                <li>
                    <a href="{{ route('siswa.index') }}"
                        class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-graduate me-3"></i>
                        Siswa
                    </a>
                </li>

            </ul>

        @endif

        @if(in_array(Auth::user()->role, ['super_admin', 'admin_jenjang']))

        <span class="menu-group-label">Ujian</span>

        <ul class="nav flex-column mb-2">

            <li>
                <a href="{{ route('bank-soal.index') }}"
                    class="nav-link {{ request()->routeIs('bank-soal.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder-open me-3"></i>
                    Bank Soal
                </a>
            </li>

            <li>
                <a href="{{ route('ujian.index') }}"
                    class="nav-link {{ request()->routeIs('ujian.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check me-3"></i>
                    Jadwal Ujian
                </a>
            </li>

        </ul>

        @endif


        @if(Auth::user()->role == 'guru')

        <span class="menu-group-label">Manajemen Soal</span>
        <ul class="nav flex-column mb-2">
            <li>
                <a href="{{ route('dashboard-guru.bank-soal.index') }}" class="nav-link {{ request()->is('dashboard-guru/bank-soal*') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open me-3"></i> Bank Soal Anda
                </a>
            </li>
        </ul>

        <span class="menu-group-label">Ujian & Nilai</span>
        <ul class="nav flex-column mb-2">
            <li>
                <a href="{{ route('dashboard-guru.jadwal-ujian.index') }}" 
                   class="nav-link {{ request()->routeIs('dashboard-guru.jadwal-ujian.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check me-3"></i>
                    Jadwal Ujian
                </a>
            </li>
            <li><a href="#" class="nav-link">
                <i class="fa-solid fa-square-poll-vertical me-3"></i>
                Nilai Siswa
            </a></li>
        </ul>

        @if(Auth::user()->guru && Auth::user()->guru->waliKelas)

        <span class="menu-group-label">Wali Kelas</span>

        <ul class="nav flex-column mb-2">

            <li>
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-users-gear me-3"></i>
                    Data Kelas
                </a>
            </li>

            <li>
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-chart-line me-3"></i>
                    Monitoring Siswa
                </a>
            </li>

            <li>
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-clipboard-check me-3"></i>
                    Rekap Nilai
                </a>
            </li>

        </ul>

        @endif
        
        <span class="menu-group-label">Pengaturan</span>
        <ul class="nav flex-column mb-2">
            <li>
                <a href="{{ route('pengaturan-akun.index') }}" class="nav-link {{ request()->routeIs('pengaturan-akun.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear me-3"></i>
                    Pengaturan Akun
                </a>
            </li>
        </ul>

        @endif

         

        @if(Auth::user()->role == 'siswa')

            <span class="menu-group-label">Ujian</span>

            <ul class="nav flex-column mb-2">

                <li>
                    <a href="#" class="nav-link">
                        <i class="fa-solid fa-pen-to-square me-3"></i>
                        Ujian Berjalan
                    </a>
                </li>

                <li>
                    <a href="#" class="nav-link">
                        <i class="fa-solid fa-clock-rotate-left me-3"></i>
                        Riwayat Ujian
                    </a>
                </li>

                <!-- <li>
                    <a href="#" class="nav-link">
                        <i class="fa-solid fa-square-poll-vertical me-3"></i>
                        Nilai Saya
                    </a>
                </li> -->

            </ul>

            @endif
    </div>


    <div class="sidebar-footer-profile">
        <div class="profile-floating-box">
            @php
                $nama = Auth::user()->nama;
            @endphp

            <div class="avatar-circle-premium">
                {{ strtoupper(substr($nama,0,2)) }}
            </div>

            <div class="overflow-hidden flex-grow-1">
                <p class="mb-0 text-white fw-bold text-truncate">
                    {{ $nama }}
                </p>

                <span class="role-badge-glow">

                    @switch(Auth::user()->role)

                        @case('super_admin')
                            Super Admin
                        @break

                        @case('admin_jenjang')
                            Admin Jenjang
                        @break

                        @case('guru')
                            Guru
                        @break

                        @case('siswa')
                            Siswa
                        @break

                    @endswitch

                </span>

            </div>
        </div>
    </div>
</aside>