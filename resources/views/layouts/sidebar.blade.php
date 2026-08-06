<style>
    /* --- SIDEBAR BASE STYLE: Claymorphism biru --- */
    .cbt-sidebar {
        width: 280px;
        min-width: 280px;
        max-width: 280px;
        background: linear-gradient(165deg, #f5f8ff 0%, #eef3fd 55%, #e1eaf9 100%);
        height: 100vh;
        position: sticky;
        top: 0;
        display: flex;
        flex-direction: column;
        border-right: none;
        box-shadow: 14px 0 34px rgba(79, 125, 243, 0.14);
        z-index: 1040;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* --- BRAND SECTION: lockup pill timbul --- */
    .sidebar-brand-section {
        padding: 20px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        background: transparent;
        flex-shrink: 0;
    }

    .sidebar-brand-section > .brand-logo-img,
    .sidebar-brand-section > #fallback-logo {
        box-shadow:
            8px 8px 18px rgba(79, 125, 243, 0.2),
            -6px -6px 14px rgba(255, 255, 255, 0.9);
        border-radius: 22px;
        background: linear-gradient(160deg, #ffffff, #eef3fd);
        padding: 10px;
    }

    .brand-logo-img {
        width: 64px;
        height: 64px;
        object-fit: contain;
        margin-bottom: 16px;
    }

    .sidebar-brand-section h6 {
        color: #1f2c4f !important;
    }

    /* --- AREA MENU UTAMA (BISA DI-SCROLL) --- */
    .sidebar-menu-scroll {
        flex-grow: 1;
        padding: 20px 16px;
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
    }

    .sidebar-menu-scroll::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar-menu-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-menu-scroll::-webkit-scrollbar-thumb {
        background: rgba(79, 125, 243, 0.35);
        border-radius: 10px;
    }

    .sidebar-menu-scroll:hover::-webkit-scrollbar-thumb {
        background: rgba(79, 125, 243, 0.55);
    }

    /* --- GROUP LABEL --- */
    .menu-group-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #6d7aa6;
        padding-left: 12px;
        margin-top: 20px;
        margin-bottom: 8px;
        display: block;
    }

    .menu-group-label:first-of-type {
        margin-top: 0;
    }

    /* --- NAV LINK STYLING: chip clay --- */
    .cbt-sidebar .nav-link {
        color: #5c6a97;
        padding: 12px 16px;
        border-radius: 16px;
        margin-bottom: 6px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        background: transparent;
        box-shadow: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .cbt-sidebar .nav-link i {
        font-size: 16px;
        width: 24px;
        color: #8b96c4;
        transition: transform 0.2s ease, color 0.2s ease;
    }

    .cbt-sidebar .nav-link:hover {
        background: #eef3fd;
        color: #1f2c4f;
        box-shadow:
            5px 5px 10px rgba(79, 125, 243, 0.14),
            -4px -4px 9px rgba(255, 255, 255, 0.9);
        transform: translateX(2px);
    }

    .cbt-sidebar .nav-link:hover i {
        transform: scale(1.1);
        color: #4f7df3;
    }

    .cbt-sidebar .nav-link.active {
        background: linear-gradient(135deg, #4f7df3, #6a9bf7);
        color: #ffffff;
        font-weight: 600;
        border: none;
        box-shadow:
            inset 3px 3px 7px rgba(0, 0, 0, 0.15),
            inset -2px -2px 5px rgba(255, 255, 255, 0.25);
    }

    .cbt-sidebar .nav-link.active i {
        color: #ffffff;
    }

    /* --- FLOATING PROFILE AT BOTTOM: clay card --- */
    .sidebar-footer-profile {
        padding: 16px;
        background: transparent;
        border-top: none;
        flex-shrink: 0;
    }

    .profile-floating-box {
        background: linear-gradient(160deg, #ffffff, #eef3fd);
        border: none;
        border-radius: 18px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow:
            6px 6px 14px rgba(79, 125, 243, 0.16),
            -5px -5px 12px rgba(255, 255, 255, 0.9);
    }

    .avatar-circle-premium {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, #4f7df3, #2f56d1);
        color: #ffffff;
        font-weight: 700;
        font-size: 13px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 3px 4px 8px rgba(47, 86, 209, 0.35);
    }

    .role-badge-glow {
        background: linear-gradient(135deg, #4f7df3, #7aa4fb);
        color: #ffffff;
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
    /* --- SIDEBAR MOBILE STATE --- */
    /* ========================================== */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(3px);
        z-index: 1050; /* Di bawah sidebar, di atas konten */
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .sidebar-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    @media (max-width: 767.98px) {
        .cbt-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1055; /* Di atas overlay */
            transform: translateX(-100%);
            box-shadow: none;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cbt-sidebar.mobile-show {
            transform: translateX(0);
            box-shadow: 14px 0 34px rgba(79, 125, 243, 0.25);
        }
    }

    /* ========================================== */
    /* --- SIDEBAR MINI STATE (DESKTOP) --- */
    /* ========================================== */
    @media (min-width: 768px) {
        .cbt-sidebar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cbt-sidebar.sidebar-mini {
            width: 80px;
            min-width: 80px;
            max-width: 80px;
        }

        .cbt-sidebar.sidebar-mini .brand-logo-img {
            width: 50px;
            height: 50px;
            margin-bottom: 0;
        }

        .cbt-sidebar.sidebar-mini #fallback-logo {
            width: 40px !important;
            height: 40px !important;
            margin-bottom: 0 !important;
        }
        
        .cbt-sidebar.sidebar-mini #fallback-logo i {
            font-size: 18px !important;
        }

        .cbt-sidebar.sidebar-mini .brand-text-wrapper,
        .cbt-sidebar.sidebar-mini .menu-group-label,
        .cbt-sidebar.sidebar-mini .nav-link span,
        .cbt-sidebar.sidebar-mini .profile-info-wrapper,
        .cbt-sidebar.sidebar-mini .sidebar-footer-profile {
            display: none !important;
        }

        .cbt-sidebar.sidebar-mini .sidebar-brand-section {
            padding: 20px 10px;
        }

        .cbt-sidebar.sidebar-mini .nav-link {
            padding: 12px;
            justify-content: center;
        }

        .cbt-sidebar.sidebar-mini .nav-link i {
            margin-right: 0 !important;
            font-size: 18px;
            width: auto;
        }

    }
    
    /* TOOLTIP CLAYMORPHISM GLOBAL (DIKONTROL VIA JS) */
    .clay-tooltip {
        position: fixed;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #4f7df3, #6a9bf7);
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        z-index: 9999; /* Selalu di atas segalanya */
        box-shadow: 4px 4px 10px rgba(79, 125, 243, 0.2), -2px -2px 6px rgba(255, 255, 255, 0.8);
        pointer-events: none;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease, visibility 0.2s ease, transform 0.2s ease;
    }
    
    .clay-tooltip.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(-50%) translateX(5px);
    }
</style>

<aside class="cbt-sidebar sidebar">
    <div class="sidebar-brand-section">
        <img src="{{ asset('img/logo-alazhar.png') }}" alt="Logo Al Azhar" class="brand-logo-img" onerror="this.style.display='none'; document.getElementById('fallback-logo').classList.remove('d-none')">

        <div id="fallback-logo" class="brand-logo-box-secondary d-none d-flex align-items-center justify-content-center text-white font-black rounded-3 mb-3" 
                 style="width: 60px; height: 60px; background: linear-gradient(135deg, #4f7df3, #2f56d1); transition: all 0.3s ease;">
                <i class="fa-solid fa-mosque fs-4"></i>
        </div>

        <div class="brand-text-wrapper text-center">
            <h6 class="fw-bold tracking-wide mb-0" style="font-size: 14px; letter-spacing: 0.3px; color: #1f2c4f;">CBT SMART ONLINE</h6>
            <span class="text-uppercase fw-bold d-block mt-1" style="font-size: 10px; color: #4f7df3; letter-spacing: 0.5px;">Sekolah Islam Al Azhar</span>
            <span class="font-semibold d-block mt-0.5" style="font-size: 10px; color: #8b96c4;">Pekalongan</span>
        </div>
    </div>

    <div class="sidebar-menu-scroll">
        
        <span class="menu-group-label">Utama</span>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-title="Dashboard Center">
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

            <li class="nav-column-item">
                <a href="{{ route('jenjang.index') }}"
                    class="nav-link {{ request()->routeIs('jenjang.*') ? 'active' : '' }}" data-title="Jenjang">
                    <i class="fa-solid fa-layer-group me-3"></i>
                    <span>Jenjang</span>
                </a>
            </li>

            <li class="nav-column-item">
                <a href="{{ route('tahun-ajaran.index') }}"
                    class="nav-link {{ request()->routeIs('tahun-ajaran.*') ? 'active' : '' }}" data-title="Tahun Ajaran">
                    <i class="fa-solid fa-calendar-days me-3"></i>
                    <span>Tahun Ajaran</span>
                </a>
            </li>

            <li class="nav-column-item">
                <a href="{{ route('jenis-ujian.index') }}"
                class="nav-link {{ request()->routeIs('jenis-ujian.*') ? 'active' : '' }}" data-title="Jenis Ujian">
                    <i class="fa-solid fa-list-check me-3"></i>
                    <span>Jenis Ujian</span>
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

                <li class="nav-column-item">
                    <a href="{{ route('tingkat.index') }}"
                    class="nav-link {{ request()->routeIs('tingkat.*') ? 'active' : '' }}" data-title="Tingkat">
                        <i class="fa-solid fa-school me-3"></i>
                        <span>Tingkat</span>
                    </a>
                </li>

                <li class="nav-column-item">
                    <a href="{{ route('kelas.index') }}"
                        class="nav-link {{ request()->routeIs('kelas.*') ? 'active' : '' }}" data-title="Kelas">

                        <i class="fa-solid fa-door-open me-3"></i>

                        <span>Kelas</span>

                    </a>
                </li>

                <li class="nav-column-item">
                    <a href="{{ route('mata-pelajaran.index') }}"
                        class="nav-link {{ request()->routeIs('mata-pelajaran.*') ? 'active' : '' }}" data-title="Mata Pelajaran">
                        <i class="fa-solid fa-book-open me-3"></i>
                        <span>Mata Pelajaran</span>
                    </a>
                </li>

            </ul>

        @endif

        @if(in_array(Auth::user()->role, ['super_admin', 'admin_jenjang']))

            <span class="menu-group-label">Pengguna</span>

            <ul class="nav flex-column mb-2">

                @if(Auth::user()->role == 'super_admin')
                <li class="nav-column-item">
                    <a href="{{ route('admin-jenjang.index') }}"
                        class="nav-link {{ request()->routeIs('admin-jenjang.*') ? 'active' : '' }}" data-title="Admin Jenjang">
                        <i class="fa-solid fa-user-shield me-3"></i>
                        <span>Admin Jenjang</span>
                    </a>
                </li>
                @endif

                <li class="nav-column-item">
                    <a href="{{ route('guru.index') }}"
                        class="nav-link {{ request()->routeIs('guru.*') ? 'active' : '' }}" data-title="Data Guru">
                        <i class="fa-solid fa-chalkboard-user me-3"></i>
                        <span>Data Guru</span>
                    </a>
                </li>

                <li class="nav-column-item">
                    <a href="{{ route('guru-mapel.index') }}"
                        class="nav-link {{ request()->routeIs('guru-mapel.*') ? 'active' : '' }}" data-title="Guru Mapel">
                        <i class="fa-solid fa-book-open me-3"></i>
                        <span>Guru Mapel</span>
                    </a>
                </li>

                <li class="nav-column-item">
                    <a href="{{ route('wali-kelas.index') }}"
                        class="nav-link {{ request()->routeIs('wali-kelas.*') ? 'active' : '' }}" data-title="Wali Kelas">
                        <i class="fa-solid fa-users-gear me-3"></i>
                        <span>Wali Kelas</span>
                    </a>
                </li>

                <li class="nav-column-item">
                    <a href="{{ route('siswa.index') }}"
                        class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}" data-title="Siswa">
                        <i class="fa-solid fa-user-graduate me-3"></i>
                        <span>Siswa</span>
                    </a>
                </li>

            </ul>

        @endif

        @if(in_array(Auth::user()->role, ['super_admin', 'admin_jenjang']))

        <span class="menu-group-label">Ujian</span>

        <ul class="nav flex-column mb-2">

            <li class="nav-column-item">
                <a href="{{ route('bank-soal.index') }}"
                    class="nav-link {{ request()->routeIs('bank-soal.*') ? 'active' : '' }}" data-title="Bank Soal">
                    <i class="fa-solid fa-folder-open me-3"></i>
                    <span>Bank Soal</span>
                </a>
            </li>

            <li class="nav-column-item">
                <a href="{{ route('ujian.index') }}"
                    class="nav-link {{ request()->routeIs('ujian.*') ? 'active' : '' }}" data-title="Jadwal Ujian">
                    <i class="fa-solid fa-calendar-check me-3"></i>
                    <span>Jadwal Ujian</span>
                </a>
            </li>

        </ul>

        @endif


        @if(Auth::user()->role == 'guru')

        <span class="menu-group-label">Manajemen Soal</span>
        <ul class="nav flex-column mb-2">
            <li class="nav-column-item">
                <a href="{{ route('dashboard-guru.bank-soal.index') }}" class="nav-link {{ request()->is('dashboard-guru/bank-soal*') ? 'active' : '' }}" data-title="Bank Soal Anda">
                    <i class="fa-solid fa-book-open me-3"></i> <span>Bank Soal Anda</span>
                </a>
            </li>
        </ul>

        <span class="menu-group-label">Ujian & Nilai</span>
        <ul class="nav flex-column mb-2">
            <li class="nav-column-item">
                <a href="{{ route('dashboard-guru.jadwal-ujian.index') }}" 
                   class="nav-link {{ request()->routeIs('dashboard-guru.jadwal-ujian.index') ? 'active' : '' }}" data-title="Jadwal Ujian">
                    <i class="fa-solid fa-calendar-check me-3"></i>
                    <span>Jadwal Ujian</span>
                </a>
            </li>
            <li class="nav-column-item">
                <a href="{{ route('dashboard-guru.nilai-siswa.index') }}" 
                   class="nav-link {{ request()->routeIs('dashboard-guru.nilai-siswa.*') ? 'active' : '' }}" data-title="Nilai Siswa">
                    <i class="fa-solid fa-square-poll-vertical me-3"></i>
                    <span>Nilai Siswa</span>
                </a>
            </li>
        </ul>

        @if(Auth::user()->guru && Auth::user()->guru->waliKelas->isNotEmpty())

        <span class="menu-group-label">Wali Kelas</span>

        <ul class="nav flex-column mb-2">

            <li class="nav-column-item">
                <a href="{{ route('dashboard-guru.wali-kelas.data-kelas') }}"
                   class="nav-link {{ request()->routeIs('dashboard-guru.wali-kelas.data-kelas') ? 'active' : '' }}" data-title="Data Kelas">
                    <i class="fa-solid fa-users me-3"></i>
                    <span>Data Kelas</span>
                </a>
            </li>

            <li class="nav-column-item">
                <a href="{{ route('dashboard-guru.wali-kelas.monitoring-siswa') }}"
                   class="nav-link {{ request()->routeIs('dashboard-guru.wali-kelas.monitoring-siswa') ? 'active' : '' }}" data-title="Monitoring Siswa">
                    <i class="fa-solid fa-chart-line me-3"></i>
                    <span>Monitoring Siswa</span>
                </a>
            </li>

            <li class="nav-column-item">
                <a href="{{ route('dashboard-guru.wali-kelas.rekap-nilai') }}"
                   class="nav-link {{ request()->routeIs('dashboard-guru.wali-kelas.rekap-nilai') ? 'active' : '' }}" data-title="Rekap Nilai">
                    <i class="fa-solid fa-clipboard-check me-3"></i>
                    <span>Rekap Nilai</span>
                </a>
            </li>

        </ul>

        @endif
        
        <span class="menu-group-label">Pengaturan</span>
        <ul class="nav flex-column mb-2">
            <li class="nav-column-item">
                <a href="{{ route('pengaturan-akun.index') }}" class="nav-link {{ request()->routeIs('pengaturan-akun.*') ? 'active' : '' }}" data-title="Pengaturan Akun">
                    <i class="fa-solid fa-user-gear me-3"></i>
                    <span>Pengaturan Akun</span>
                </a>
            </li>
        </ul>

        @endif

         

        @if(Auth::user()->role == 'siswa')

            <span class="menu-group-label">Menu Ujian</span>

            <ul class="nav flex-column mb-3">
                <li class="nav-column-item">
                    <a href="{{ route('dashboard-siswa.scan-token.index') }}" 
                       class="nav-link {{ request()->routeIs('dashboard-siswa.scan-token.*') ? 'active' : '' }}" data-title="Scan Token">
                        <i class="fa-solid fa-qrcode me-3"></i>
                        <span>Scan Token</span>
                    </a>
                </li>

                <li class="nav-column-item">
                    <a href="{{ route('dashboard-siswa.ujian-hari-ini') }}"
                    class="nav-link {{ request()->routeIs('dashboard-siswa.ujian-hari-ini') ? 'active' : '' }}" data-title="Jadwal Ujian">
                        <i class="fa-solid fa-calendar-day me-3"></i>
                        <span>Jadwal Ujian</span>
                    </a>
                </li>

                <li class="nav-column-item">
                    <a href="{{ route('dashboard-siswa.hasil-nilai.index') }}"
                    class="nav-link {{ request()->routeIs('dashboard-siswa.hasil-nilai.*') ? 'active' : '' }}" data-title="Hasil Nilai">
                        <i class="fa-solid fa-square-poll-vertical me-3"></i>
                        <span>Hasil Nilai</span>
                    </a>
                </li>
            </ul>

            <span class="menu-group-label">Pengaturan</span>

            <ul class="nav flex-column">
                <li class="nav-column-item">
                    <a href="{{ route('pengaturan-akun.index') }}" 
                       class="nav-link {{ request()->routeIs('pengaturan-akun.*') ? 'active' : '' }}" data-title="Profile Saya">
                        <i class="fa-solid fa-user me-3"></i>
                        <span>Profile Saya</span>
                    </a>
                </li>
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

            <div class="profile-info-wrapper overflow-hidden flex-grow-1">
                <p class="mb-0 fw-bold text-truncate" style="color: #1f2c4f;">
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

<script>
document.addEventListener('DOMContentLoaded', function () {

    const sidebarScroll = document.querySelector('.sidebar-menu-scroll');
    const activeMenu = document.querySelector('.sidebar-menu-scroll .nav-link.active');

    if (sidebarScroll && activeMenu) {

        const scrollTop =
            activeMenu.offsetTop -
            sidebarScroll.offsetTop -
            (sidebarScroll.clientHeight / 2) +
            (activeMenu.clientHeight / 2);

        sidebarScroll.scrollTo({
            top: scrollTop,
            behavior: 'smooth'
        });
    }

    // --- LOGIKA HOVER TOOLTIP CLAYMORPHISM ---
    const links = document.querySelectorAll('.cbt-sidebar .nav-link');
    const tooltip = document.createElement('div');
    tooltip.className = 'clay-tooltip';
    document.body.appendChild(tooltip);

    links.forEach(link => {
        link.addEventListener('mouseenter', (e) => {
            const sidebar = document.querySelector('.cbt-sidebar');
            // Hanya muncul saat di mode mini (desktop)
            if (sidebar && sidebar.classList.contains('sidebar-mini') && window.innerWidth >= 768) {
                const title = link.getAttribute('data-title');
                if (title) {
                    tooltip.textContent = title;
                    const rect = link.getBoundingClientRect();
                    // Posisikan tooltip tepat di sebelah kanan item menu yang dihover
                    tooltip.style.top = (rect.top + (rect.height / 2)) + 'px';
                    tooltip.style.left = rect.right + 'px'; 
                    tooltip.classList.add('show');
                }
            }
        });
        link.addEventListener('mouseleave', () => {
            tooltip.classList.remove('show');
        });
    });

    // Hilangkan tooltip seketika saat di-scroll untuk menghindari bug posisi
    if (sidebarScroll) {
        sidebarScroll.addEventListener('scroll', () => {
            tooltip.classList.remove('show');
        });
    }

});
</script>