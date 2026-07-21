<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Sedang Ujian - CBT Online Sekolah')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        html, body {
            background-color: #f8fafc;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* =========================================================
           HEADER DARURAT (minimalis, hanya nama + tombol keluar)
           ========================================================= */
        .exam-emergency-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .exam-emergency-header .exam-user-name {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
        }

        .exam-emergency-header .exam-user-role {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
        }

        .btn-logout-emergency {
            background-color: transparent;
            color: #64748b;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-logout-emergency:hover {
            background-color: #fff1f2;
            color: #f43f5e;
            border-color: #ffe4e6;
        }

        /* =========================================================
           GERBANG FULLSCREEN (tampil sebelum ujian dimulai)
           ========================================================= */
        #fullscreenGate {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 24px;
        }

        #fullscreenGate .gate-icon {
            width: 84px;
            height: 84px;
            border-radius: 20px;
            background: rgba(56, 189, 248, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            color: #38bdf8;
            margin-bottom: 24px;
        }

        #fullscreenGate h4 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 10px;
        }

        #fullscreenGate p {
            color: #94a3b8;
            font-size: 14px;
            max-width: 420px;
            margin-bottom: 28px;
        }

        #btnStartExam {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            color: #ffffff;
            border: none;
            padding: 14px 36px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 10px 25px -5px rgba(14, 165, 233, 0.4);
        }

        #btnStartExam:hover { filter: brightness(1.08); }

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
    {{-- HEADER DARURAT: hanya nama siswa + tombol keluar   --}}
    {{-- ================================================= --}}
    <div class="exam-content-wrapper" id="examContentWrapper">
        <header class="exam-emergency-header">
            <div>
                <span class="exam-user-name d-block">{{ Auth::user()->nama }}</span>
                <span class="exam-user-role">{{ str_replace('_', ' ', Auth::user()->role) }} &mdash; Sedang Ujian</span>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="m-0" id="formLogoutDarurat">
                @csrf
                <button type="submit" class="btn-logout-emergency" onclick="return confirmEmergencyLogout(event)">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar Darurat</span>
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
           (harus dari user gesture, tidak bisa otomatis saat load)
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

        /* =========================================================
           DETEKSI KELUAR DARI FULLSCREEN (tekan Esc, dsb)
           -> pelanggaran 1/2: notif + otomatis coba masuk fullscreen lagi
           -> pelanggaran 2/2: langsung submit ujian otomatis
           ========================================================= */
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

        function handleFullscreenExit() {
            if (!examStarted || intentionalExit) return;
            if (isFullscreenActive()) return; // masih fullscreen, abaikan

            reportFullscreenViolation();
        }

        let fsViolationSending = false;

        function reportFullscreenViolation() {
            if (fsViolationSending) return;
            fsViolationSending = true;

            fetch("{{ route('dashboard-siswa.ujian.violation') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    ujian_id: {{ $ujian->id }},
                    reason: 'keluar_fullscreen'
                })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                fsViolationSending = false;
                if (!data.success) return;

                if (data.submit) {
                    // ================= PELANGGARAN 2/2 =================
                    intentionalExit = true;

                    Swal.fire({
                        icon: 'error',
                        title: 'Pelanggaran ' + data.count + '/2',
                        text: 'Anda keluar dari mode layar penuh untuk kedua kalinya. Ujian akan dikumpulkan otomatis.',
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
                        html: 'Anda keluar dari mode layar penuh.<br>' +
                              'Jika terjadi sekali lagi, ujian akan otomatis dikumpulkan.',
                        confirmButtonText: 'Kembali ke Layar Penuh',
                        confirmButtonColor: '#0ea5e9',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            // Diklik dalam jeda singkat setelah klik ini,
                            // browser masih menganggap ini "user gesture"
                            // sehingga fullscreen bisa diminta ulang.
                            reEnterFullscreen();
                        }
                    });
                }
            })
            .catch(function () {
                fsViolationSending = false;
            });
        }

        function reEnterFullscreen() {
            const el = document.documentElement;
            const request = el.requestFullscreen
                || el.webkitRequestFullscreen
                || el.mozRequestFullScreen
                || el.msRequestFullscreen;

            if (!request) return;

            request.call(el).catch(function () {
                // Jika browser tetap menolak (mis. Safari iOS),
                // tampilkan gerbang awal lagi sebagai fallback manual
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
    </script>

    @yield('scripts')
</body>
</html>