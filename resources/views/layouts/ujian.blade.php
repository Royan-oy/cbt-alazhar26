<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Sedang Ujian - CBT Online Sekolah')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:opsz,wght@6..72,500;6..72,600;6..72,700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* =========================================================
        DESIGN TOKENS — Tema Dark Slate & Sky Blue (Dashboard Style)
        ========================================================= */
        :root {
            --primary-dark: #0f172a;
            --secondary-dark: #1e293b;
            --accent-blue: #0ea5e9;
            --accent-blue-light: #38bdf8;
            --accent-blue-dark: #0284c7;
            --surface-white: #ffffff;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --paper-bg: #f8fafc;
            --danger: #ef4444;
            --danger-soft: #fef2f2;
            --success: #10b981;
        }

        html, body {
            background-color: var(--paper-bg);
            overflow-x: hidden;
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #1e293b;
        }

        /* =========================================================
        HEADER DARURAT — Sticky Soft Bluish-White Header
        ========================================================= */
        .exam-emergency-header {
            background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
            border-bottom: 1px solid #bae6fd;
            padding: 11px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1030;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.08);
        }

        .exam-content-wrapper main {
            transition: padding-top 0.15s ease;
        }

        .exam-header-brand {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
            flex: 1 1 auto;
        }

        .exam-header-logos {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            padding-right: 12px;
            border-right: 1px solid #e2e8f0;
        }

        .exam-logo {
            height: 36px;
            width: 36px;
            object-fit: contain;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .exam-header-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
            line-height: 1.35;
        }

        .exam-header-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            max-width: 100%;
            width: fit-content;
            background: #ffffff;
            color: #0284c7;
            border: 1px solid #bae6fd;
            padding: 2px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            box-shadow: 0 2px 6px rgba(14, 165, 233, 0.06);
        }

        .exam-header-badge i { font-size: 10px; flex-shrink: 0; color: #0284c7; }

        .exam-header-badge .badge-meta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #64748b;
            font-weight: 600;
        }

        .exam-header-dot {
            opacity: 0.55;
            font-size: 8px;
        }

        .exam-header-active-tag {
            background: #dcfce7;
            color: #15803d;
            font-size: 8.5px;
            font-weight: 800;
            padding: 1px 6px;
            border-radius: 999px;
            margin-left: 2px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border: 1px solid #86efac;
        }

        .exam-user-name {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .exam-user-role {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .btn-logout-emergency {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .btn-logout-emergency:hover {
            background-color: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }

        /* =========================================================
        RESPONSIVE HEADER — TABLET (<= 767.98px)
        ========================================================= */
        @media (max-width: 767.98px) {
            .exam-emergency-header {
                padding: 8px 14px;
                gap: 10px;
            }

            .exam-logo {
                height: 32px;
                width: 32px;
            }

            .exam-header-badge {
                font-size: 9.5px;
                padding: 2px 8px;
            }

            .exam-header-info {
                max-width: 240px;
            }

            .exam-user-name {
                font-size: 12.5px;
            }

            .exam-user-role {
                font-size: 8.5px;
            }

            .btn-logout-emergency {
                padding: 7px 12px;
                font-size: 11.5px;
            }
        }

        /* =========================================================
        RESPONSIVE HEADER — HP KECIL (<= 480px)
        ========================================================= */
        @media (max-width: 480px) {
            .exam-emergency-header {
                padding: 7px 10px;
                gap: 8px;
            }

            .exam-header-logos {
                gap: 5px;
            }

            .exam-logo {
                height: 26px;
                width: 26px;
                border-radius: 6px;
            }

            .exam-header-badge {
                font-size: 8.5px;
                padding: 1px 7px;
            }

            .exam-header-badge .badge-meta {
                display: none;
            }

            .exam-header-info {
                max-width: 130px;
            }

            .exam-user-name {
                font-size: 11px;
            }

            .exam-user-role {
                font-size: 8px;
            }

            .btn-logout-text {
                display: none;
            }

            .btn-logout-emergency {
                padding: 7px 9px;
            }
        }

        /* =========================================================
        GERBANG FULLSCREEN (tampil sebelum ujian dimulai)
        ========================================================= */
        #fullscreenGate {
            position: fixed;
            inset: 0;
            background:
                radial-gradient(circle at 15% 12%, rgba(14, 165, 233, 0.15), transparent 50%),
                linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 24px;
        }

        #fullscreenGate .gate-kicker {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--accent-blue-light);
            margin-bottom: 18px;
        }

        #fullscreenGate .gate-icon {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            background: rgba(14, 165, 233, 0.12);
            border: 1px solid rgba(56, 189, 248, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: var(--accent-blue-light);
            margin-bottom: 26px;
            box-shadow: 0 0 25px rgba(14, 165, 233, 0.2);
        }

        #fullscreenGate h4 {
            color: #ffffff;
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 12px;
        }

        #fullscreenGate p {
            color: #94a3b8;
            font-size: 14px;
            max-width: 420px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        #btnStartExam {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            color: #ffffff;
            border: none;
            padding: 14px 38px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14.5px;
            letter-spacing: 0.2px;
            box-shadow: 0 10px 25px -5px rgba(14, 165, 233, 0.4);
            transition: filter 0.2s ease, transform 0.2s ease;
        }

        #btnStartExam:hover {
            filter: brightness(1.08);
            transform: translateY(-2px);
            box-shadow: 0 14px 30px -5px rgba(14, 165, 233, 0.5);
        }

        .exam-content-wrapper {
            display: none; /* baru ditampilkan setelah fullscreen aktif */
        }
    </style>
</head>
<body>

    {{-- ================================================= --}}
    {{-- GERBANG: WAJIB FULLSCREEN DULU SEBELUM LIHAT SOAL --}}
    {{-- ================================================= --}}
    <div id="fullscreenGate">
        <span class="gate-kicker">CBT Online Sekolah</span>
        <div class="gate-icon">
            <i class="fa-solid fa-expand"></i>
        </div>
        <h4>Ujian Akan Dimulai dalam Mode Layar Penuh</h4>
        <p>
            Untuk menjaga fokus dan mencegah kecurangan, ujian hanya bisa dikerjakan dalam
            mode layar penuh (fullscreen). Klik tombol di bawah untuk memulai.
        </p>
        <button type="button" id="btnStartExam">
            <i class="fa-solid fa-expand me-2"></i> Mulai Ujian (Fullscreen)
        </button>
    </div>

    {{-- ================================================= --}}
    {{-- HEADER DARURAT: logo sekolah + info ujian + keluar --}}
    {{-- ================================================= --}}
    <div class="exam-content-wrapper" id="examContentWrapper">
        <header class="exam-emergency-header">
            <div class="exam-header-brand">
                <div class="exam-header-logos">
                    <img src="{{ asset('img/alazhar.png') }}" alt="Logo Sekolah" class="exam-logo">
                    <img src="{{ asset('img/sigma.png') }}" alt="Logo Sigma" class="exam-logo">
                </div>

                <div class="exam-header-info">
                    <span class="exam-header-badge" title="{{ $ujian->jenisUjian->nama ?? 'Ujian' }} — TA {{ $ujian->tahunAjaran->nama_tahun ?? '-' }} {{ optional($ujian->tahunAjaran)->semester }}">
                        <i class="fa-solid fa-file-signature"></i>
                        {{ $ujian->jenisUjian->nama ?? 'Ujian' }}
                        <span class="badge-meta">
                            <span class="exam-header-dot">&bull;</span>
                            TA {{ $ujian->tahunAjaran->nama_tahun ?? '-' }}
                            {{ optional($ujian->tahunAjaran)->semester }}
                        </span>
                        @if(optional($ujian->tahunAjaran)->is_aktif)
                            <span class="exam-header-active-tag">Aktif</span>
                        @endif
                    </span>
                    <span class="exam-user-name">{{ Auth::user()->nama }}</span>
                    <span class="exam-user-role">{{ str_replace('_', ' ', Auth::user()->role) }} &mdash; Sedang Ujian</span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="m-0" id="formLogoutDarurat">
                @csrf
                <button type="submit" class="btn-logout-emergency" onclick="return confirmEmergencyLogout(event)">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="btn-logout-text">Keluar Darurat</span>
                </button>
            </form>
        </header>

        <main class="w-100">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const gate = document.getElementById('fullscreenGate');
        const contentWrapper = document.getElementById('examContentWrapper');
        const btnStart = document.getElementById('btnStartExam');
        let examStarted = false;
        let intentionalExit = false; // true saat logout darurat / submit selesai

        /* =========================================================
        MASUK FULLSCREEN SAAT TOMBOL "MULAI UJIAN" DIKLIK
        ========================================================= */
        btnStart.addEventListener('click', function () {
            const el = document.documentElement;
            const request = el.requestFullscreen
                || el.webkitRequestFullscreen
                || el.mozRequestFullScreen
                || el.msRequestFullscreen;

            if (request) {
                request.call(el).then(enterExamMode).catch(function () {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fullscreen Tidak Bisa Diaktifkan',
                        text: 'Browser Anda menolak mode layar penuh. Ujian tetap dilanjutkan, namun pastikan Anda tidak berpindah tab.',
                        confirmButtonColor: '#0ea5e9'
                    }).then(enterExamMode);
                });
            } else {
                enterExamMode();
            }
        });

        function enterExamMode() {
            examStarted = true;
            gate.style.display = 'none';
            contentWrapper.style.display = 'block';
        }

        function isFullscreenActive() {
            return !!(document.fullscreenElement
                || document.webkitFullscreenElement
                || document.mozFullScreenElement
                || document.msFullscreenElement);
        }

        document.addEventListener('fullscreenchange', handleFullscreenExit);
        document.addEventListener('webkitfullscreenchange', handleFullscreenExit);
        document.addEventListener('mozfullscreenchange', handleFullscreenExit);
        document.addEventListener('MSFullscreenChange', handleFullscreenExit);

        /* =========================================================
        LAPISAN CADANGAN: deteksi window kehilangan fokus
        (recent-apps / app-switcher / alih aplikasi lain)
        Sengaja pakai fungsi reportViolation() yang SAMA supaya
        tetap dijaga cooldown-nya — tidak dobel hitung dengan
        visibilitychange yang mungkin terpicu bersamaan.
        ========================================================= */
        window.addEventListener('blur', function () {
            // Beri jeda sepersekian detik sebelum lapor, supaya tidak
            // salah tangkap saat dialog SweetAlert sendiri sempat
            // memindahkan fokus browser secara internal.
            setTimeout(function () {
                if (document.hidden || !document.hasFocus()) {
                    reportViolation();
                }
            }, 150);
        });

        function handleFullscreenExit() {
            if (isFullscreenActive()) return; // masih fullscreen, abaikan
            reportViolation();
        }

        /* =========================================================
        SATU-SATUNYA PINTU PELAPORAN PELANGGARAN
        Dipanggil oleh: fullscreenchange (file ini) DAN
        visibilitychange (kerja.blade.php) — supaya Alt+Tab yang
        memicu keduanya sekaligus tetap hanya dihitung 1x.
        ========================================================= */
        let violationInFlight = false;
        let violationCooldownUntil = 0;

        window.reportViolation = function () {
            if (!examStarted || intentionalExit) return;
            if (typeof isReloading !== 'undefined' && isReloading) return;
            if (typeof isFinishing !== 'undefined' && isFinishing) return;

            const now = Date.now();
            if (violationInFlight || now < violationCooldownUntil) return; // cegah double-count

            violationInFlight = true;
            violationCooldownUntil = now + 2000; // jeda 2 detik

            fetch("{{ route('dashboard-siswa.ujian.violation') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ ujian_id: {{ $ujian->id }} })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                violationInFlight = false;
                if (!data.success) return;

                if (data.submit) {
                    // ================= PELANGGARAN 2/2 =================
                    intentionalExit = true;

                    Swal.fire({
                        icon: 'error',
                        title: 'Pelanggaran ' + data.count + '/2',
                        text: 'Anda keluar dari ujian untuk kedua kalinya. Ujian akan dikumpulkan otomatis.',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#ef4444',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(function () {
                        if (typeof submitExamAutomatically === 'function') {
                            submitExamAutomatically();
                        }
                    });

                } else {
                    // ================= PELANGGARAN 1/2 =================
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pelanggaran ' + data.count + '/2',
                        html: 'Anda keluar dari halaman atau mode layar penuh ujian.<br>' +
                            'Jika terjadi sekali lagi, ujian akan otomatis dikumpulkan.',
                        confirmButtonText: 'Kembali ke Ujian',
                        confirmButtonColor: '#0ea5e9',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(function (result) {
                        if (result.isConfirmed && !isFullscreenActive()) {
                            reEnterFullscreen();
                        }
                    });
                }
            })
            .catch(function () {
                violationInFlight = false;
            });
        };

        function reEnterFullscreen() {
            const el = document.documentElement;
            const request = el.requestFullscreen
                || el.webkitRequestFullscreen
                || el.mozRequestFullScreen
                || el.msRequestFullscreen;

            if (!request) return;

            request.call(el).catch(function () {
                Swal.fire({
                    icon: 'info',
                    title: 'Klik untuk Kembali ke Layar Penuh',
                    text: 'Browser Anda meminta konfirmasi tambahan. Klik tombol di bawah untuk melanjutkan.',
                    confirmButtonText: 'Lanjutkan',
                    confirmButtonColor: '#0ea5e9',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(function () {
                    reEnterFullscreen();
                });
            });
        }

        /* =========================================================
        TOMBOL LOGOUT DARURAT
        ========================================================= */
        function confirmEmergencyLogout(e) {
            e.preventDefault();
            intentionalExit = true;

            Swal.fire({
                icon: 'warning',
                title: 'Keluar dari Ujian?',
                text: 'Anda akan logout dan meninggalkan ujian yang sedang berjalan. Jawaban Anda sejauh ini sudah tersimpan otomatis.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f43f5e',
                cancelButtonColor: '#64748b'
            }).then((result) => {
                if (result.isConfirmed) {
                    intentionalExit = true;
                    document.getElementById('formLogoutDarurat').submit();
                } else {
                    intentionalExit = false;
                }
            });

            return false;
        }

        /* =========================================================
        CEGAH SISWA KEMBALI (TOMBOL BACK)
        ========================================================= */
        (function () {
            history.pushState(null, "", location.href);

            window.addEventListener("popstate", function () {
                history.pushState(null, "", location.href);

                Swal.fire({
                    icon: "warning",
                    title: "Tidak Bisa Kembali",
                    text: "Anda tidak dapat meninggalkan halaman ujian melalui tombol back.",
                    confirmButtonText: "Mengerti",
                    confirmButtonColor: "#0ea5e9"
                });
            });
        })();

        /* =========================================================
        KONFIRMASI SEBELUM MENUTUP / RELOAD TAB
        ========================================================= */
        window.addEventListener("beforeunload", function (e) {
            if (intentionalExit) return;
            e.preventDefault();
            e.returnValue = "";
        });

        /* =========================================================
        DETEKSI BACK-FORWARD CACHE (BFCache / SWIPE BACK HISTORY)
        ========================================================= */
        window.addEventListener("pageshow", function (event) {
            if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>

    <script>
        /* =========================================================
        SINKRONISASI TINGGI HEADER FIXED → padding-top konten
        Dijalankan setiap kali ukuran header berubah (resize,
        rotate, teks wrap beda, dsb) supaya konten TIDAK PERNAH
        ketutupan walau tinggi header tidak bisa ditebak pasti.
        ========================================================= */
        (function () {
            const header = document.querySelector('.exam-emergency-header');
            const main = document.querySelector('.exam-content-wrapper main');

            if (!header || !main) return;

            function syncHeaderHeight() {
                const h = header.offsetHeight;
                main.style.paddingTop = h + 'px';
            }

            // Jalankan saat load & setiap header berubah ukuran
            if (window.ResizeObserver) {
                new ResizeObserver(syncHeaderHeight).observe(header);
            } else {
                // fallback browser lama
                window.addEventListener('resize', syncHeaderHeight);
            }

            window.addEventListener('load', syncHeaderHeight);
            document.addEventListener('DOMContentLoaded', syncHeaderHeight);
            syncHeaderHeight();
        })();
    </script>

    @yield('scripts')
</body>
</html>