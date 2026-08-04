{{-- ============================================================
     LOADING SCREEN COMPONENTS
     1. Splash Screen — sekali per sesi (logo + progress bar)
     2. Page Transition — setiap navigasi (top bar + overlay)
     ============================================================ --}}

{{-- ======================== CSS ======================== --}}
<style>
    /* ==========================================================
       SPLASH SCREEN
       ========================================================== */
    #splashScreen {
        position: fixed;
        inset: 0;
        z-index: 99999;
        background:
            radial-gradient(ellipse at 20% 20%, rgba(56, 189, 248, 0.12), transparent 55%),
            radial-gradient(ellipse at 80% 80%, rgba(14, 165, 233, 0.08), transparent 50%),
            linear-gradient(160deg, #0f172a 0%, #1e293b 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 1;
        transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #splashScreen.splash-fade-out {
        opacity: 0;
        pointer-events: none;
    }

    /* Logo */
    .splash-logo {
        width: 90px;
        height: 90px;
        object-fit: contain;
        margin-bottom: 24px;
        animation: splashPulse 2s ease-in-out infinite;
        filter: drop-shadow(0 0 25px rgba(56, 189, 248, 0.3));
    }

    @keyframes splashPulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50%      { transform: scale(1.06); opacity: 0.85; }
    }

    /* Title */
    .splash-title {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: #f1f5f9;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        text-align: center;
    }

    .splash-subtitle {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 36px;
    }

    /* Progress bar container */
    .splash-progress-track {
        width: 220px;
        height: 4px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 999px;
        overflow: hidden;
        position: relative;
    }

    .splash-progress-bar {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 0%;
        border-radius: 999px;
        background: linear-gradient(90deg, #38bdf8, #0ea5e9, #38bdf8);
        background-size: 200% 100%;
        animation: splashBarFill 2.6s cubic-bezier(0.4, 0, 0.2, 1) forwards, splashBarShimmer 1.5s linear infinite;
    }

    @keyframes splashBarFill {
        0%   { width: 0%; }
        25%  { width: 35%; }
        50%  { width: 65%; }
        75%  { width: 88%; }
        100% { width: 100%; }
    }

    @keyframes splashBarShimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* ==========================================================
       PAGE TRANSITION LOADER (KHUSUS BERPINDAH HALAMAN & REFRESH)
       ========================================================== */
    #pageTransitionLoader {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 99998;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    #pageTransitionLoader.active {
        display: block;
        opacity: 1;
    }

    /* Overlay semi-transparan */
    .transition-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.25);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
    }

    /* Top progress bar */
    .transition-progress {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        z-index: 1;
        overflow: hidden;
        background: rgba(56, 189, 248, 0.1);
    }

    .transition-progress-bar {
        height: 100%;
        width: 0%;
        border-radius: 0 999px 999px 0;
        background: linear-gradient(90deg, #38bdf8, #0ea5e9, #7dd3fc);
        animation: transitionBarGrow 8s cubic-bezier(0.2, 0.6, 0.4, 1) forwards;
        box-shadow: 0 0 12px rgba(56, 189, 248, 0.5), 0 0 4px rgba(56, 189, 248, 0.3);
    }

    @keyframes transitionBarGrow {
        0%   { width: 0%; }
        10%  { width: 25%; }
        30%  { width: 50%; }
        50%  { width: 70%; }
        70%  { width: 82%; }
        90%  { width: 90%; }
        100% { width: 95%; }
    }

    /* Spinner circle running di tengah overlay (khusus perpindahan halaman & refresh) */
    .transition-spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 42px;
        height: 42px;
        border: 3.5px solid rgba(56, 189, 248, 0.15);
        border-top-color: #38bdf8;
        border-radius: 50%;
        animation: transitionSpin 0.7s linear infinite;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.2);
    }

    @keyframes transitionSpin {
        to { transform: translate(-50%, -50%) rotate(360deg); }
    }
</style>

{{-- ======================== HTML ======================== --}}

{{-- Splash Screen (Tampil pertama kali membuka website) --}}
<div id="splashScreen" aria-label="Loading">
    <img src="{{ asset('img/alazhar-putih.png') }}" alt="Logo Al-Azhar 26" class="splash-logo">
    <div class="splash-title">CBT Online Al-Azhar 26</div>
    <div class="splash-subtitle">Computer Based Testing</div>
    <div class="splash-progress-track">
        <div class="splash-progress-bar"></div>
    </div>
</div>

{{-- Page Transition Loader (Circle Running - Khusus Berpindah Halaman & Refresh) --}}
<div id="pageTransitionLoader" aria-label="Memuat halaman">
    <div class="transition-overlay"></div>
    <div class="transition-progress">
        <div class="transition-progress-bar"></div>
    </div>
    <div class="transition-spinner"></div>
</div>

{{-- ======================== JavaScript ======================== --}}
<script>
(function () {
    'use strict';

    var splash = document.getElementById('splashScreen');
    var loader = document.getElementById('pageTransitionLoader');
    var SPLASH_KEY = 'splashShown';
    var isSplashActive = false;

    /* ----------------------------------------------------------
       A. SPLASH SCREEN — hanya muncul saat pertama kali membuka website
       ---------------------------------------------------------- */
    function handleSplash() {
        if (!splash) return;

        // Jika splash sudah pernah tampil di sesi ini, langsung sembunyikan
        if (sessionStorage.getItem(SPLASH_KEY)) {
            splash.style.display = 'none';
            isSplashActive = false;
            return;
        }

        isSplashActive = true;

        // Tampilkan splash dengan durasi ~2.8 detik
        setTimeout(function () {
            splash.classList.add('splash-fade-out');

            setTimeout(function () {
                splash.style.display = 'none';
                isSplashActive = false;
            }, 500);

            sessionStorage.setItem(SPLASH_KEY, 'true');
        }, 2800);
    }

    /* ----------------------------------------------------------
       B. PAGE TRANSITION LOADER (Circle running — Perpindahan Halaman & Refresh)
       ---------------------------------------------------------- */
    function showLoader() {
        if (!loader || isSplashActive) return;

        var bar = loader.querySelector('.transition-progress-bar');
        if (bar) {
            bar.style.animation = 'none';
            void bar.offsetHeight; // Force reflow
            bar.style.animation = '';
        }
        loader.classList.add('active');
    }

    function hideLoader() {
        if (!loader) return;
        loader.classList.remove('active');
    }

    // Pemicu 1: Sebelum halaman di-refresh (F5 / Ctrl+R / Tombol reload) atau ditinggalkan
    window.addEventListener('beforeunload', function () {
        if (!isSplashActive) {
            showLoader();
        }
    });

    // Pemicu 2: Intercept klik pada link <a>
    document.addEventListener('click', function (e) {
        var anchor = e.target.closest('a');
        if (!anchor) return;

        var href = anchor.getAttribute('href');

        if (!href) return;
        if (href === '#' || href.startsWith('#') || href.startsWith('javascript:')) return;
        if (anchor.getAttribute('target') === '_blank') return;
        if (anchor.getAttribute('download') !== null) return;
        if (anchor.classList.contains('no-loader')) return;
        if (e.ctrlKey || e.metaKey || e.shiftKey) return;
        
        // Pengecualian otomatis untuk rute pengunduhan file
        var hrefLower = href.toLowerCase();
        if (hrefLower.includes('template') || hrefLower.includes('export') || hrefLower.includes('download')) {
            return;
        }

        // Cek apakah aksi klik diinterupsi oleh script lain (misal: SweetAlert)
        setTimeout(function() {
            if (!e.defaultPrevented) {
                showLoader();
            }
        }, 10);
    });

    // Override form.submit() agar memicu loader (saat SweetAlert mengeksekusi form.submit())
    var originalSubmit = HTMLFormElement.prototype.submit;
    HTMLFormElement.prototype.submit = function() {
        if (!this.classList.contains('no-loader') && this.getAttribute('target') !== '_blank') {
            showLoader();
        }
        originalSubmit.apply(this, arguments);
    };

    // Pemicu 3: Intercept form submit biasa
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || form.classList.contains('no-loader')) return;
        if (form.getAttribute('target') === '_blank') return;

        // Cek apakah submit diinterupsi oleh script lain (misal: SweetAlert e.preventDefault())
        setTimeout(function() {
            if (!e.defaultPrevented) {
                showLoader();
            }
        }, 10);
    });

    // Sembunyikan loader saat halaman selesai dimuat penuh
    window.addEventListener('load', function () {
        setTimeout(hideLoader, 200);
    });

    window.addEventListener('pageshow', function (e) {
        setTimeout(hideLoader, 200);
        if (e.persisted) {
            if (splash) splash.style.display = 'none';
            isSplashActive = false;
        }
    });

    /* ----------------------------------------------------------
       INISIALISASI
       ---------------------------------------------------------- */
    // Cek jika ini adalah refresh atau navigasi sekunder (splash sudah tampil)
    if (sessionStorage.getItem(SPLASH_KEY)) {
        showLoader();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', handleSplash);
    } else {
        handleSplash();
    }
})();
</script>
