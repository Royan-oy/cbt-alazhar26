<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CBT Online Sekolah')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo-alazhar.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --sb-bg: #eef3fd;
            --sb-card: #ffffff;
            --sb-text-muted: #6d7aa6;
            --sb-text-active: #4f7df3;
        }

        body {
            background-color: #eef2fb;
            overflow-x: hidden;
        }

        /* --- LOGIKA RESPONSIVE SIDEBAR --- */
        /* Catatan: warna & shadow detail sidebar kini didefinisikan lebih spesifik
           lewat .cbt-sidebar di layouts/sidebar.blade.php (tema claymorphism biru).
           Aturan di bawah ini hanya menjaga logika show/hide di mobile. */
        .sidebar {
            min-width: 280px;
            max-width: 280px;
            background-color: var(--sb-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            border-right: none;
            z-index: 1040;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Saat Layar HP/Tablet (< 768px) */
        @media (max-width: 767.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                transform: translateX(-100%); /* Sembunyikan ke kiri luar layar */
            }
            .sidebar.mobile-show {
                transform: translateX(0); /* Muncul geser ke kanan */
                box-shadow: 24px 0 48px rgba(79, 125, 243, 0.28);
            }
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-color: rgba(31, 44, 79, 0.45);
                backdrop-filter: blur(4px);
                z-index: 1030;
                display: none;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .sidebar-overlay.show {
                display: block;
                opacity: 1;
            }
        }

        /* Nav Link Styling & Hover CSS Premium */
        .sidebar-menu-wrapper { padding: 20px 16px; }
        .sidebar .nav-link {
            color: #5c6a97; padding: 12px 16px; border-radius: 16px; margin-bottom: 6px; font-size: 14px; font-weight: 500; display: flex; align-items: center; transition: all 0.2s ease;
        }
        .sidebar .nav-link:hover { background-color: #eef3fd; color: #1f2c4f; transform: translateX(2px); }
        .sidebar .nav-link.active { background: linear-gradient(135deg, #4f7df3, #6a9bf7); color: #ffffff; font-weight: 600; box-shadow: 0 8px 20px rgba(79, 125, 243, 0.3); }
        .sidebar .nav-link.active i { color: var(--sb-text-active); }
        .style-header .shadow-hover-danger:hover { background-color: #eef3fd; border-radius: 10px; }
    </style>
    <script>
        // Konfigurasi delimiter: guru WAJIB pakai $...$ / \(...\) untuk inline,
        // $$...$$ / \[...\] untuk block. Ini satu-satunya sumber kebenaran
        // "apa yang dianggap rumus" -> tidak ada lagi deteksi manual via regex.
        window.MathJax = {
            tex: {
                inlineMath: [['\\(', '\\)'], ['$', '$']],
                displayMath: [['\\[', '\\]'], ['$$', '$$']]
            },
            options: {
                // skip elemen yang memang bukan tempat konten soal
                // (mis. textarea editor TinyMCE, script, dsb)
                skipHtmlTags: ['script', 'noscript', 'style', 'textarea', 'pre', 'code'],
                ignoreHtmlClass: 'tox-tinymce|no-mathjax'
            }
        };

        /**
         * Render ulang MathJax pada sebuah container (dipanggil setelah
         * konten baru di-inject via AJAX/preview, dsb).
         * TIDAK melakukan manipulasi teks manual apapun -> aman dari XSS,
         * tidak akan "menelan" teks biasa, dan otomatis dukung nested {}.
         */
        function renderMathInContainer(container) {
            if (!container) container = document.body;
            if (window.MathJax && window.MathJax.typesetPromise) {
                // Bersihkan cache typeset lama di container ini dulu supaya
                // tidak error saat di-render ulang berkali-kali (mis. live preview)
                if (window.MathJax.typesetClear) {
                    window.MathJax.typesetClear([container]);
                }
                window.MathJax.typesetPromise([container])
                    .catch(err => console.error('MathJax render error:', err));
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            renderMathInContainer(document.body);
        });
    </script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>    
    @stack('css')
</head>
<body>

    @include('layouts.loading')

    <div class="d-flex">
        @include('layouts.sidebar')

        <div class="w-100 d-flex flex-column min-w-0">
            @include('layouts.header')

            <main class="p-4 flex-grow-1">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check localStorage for sidebar state
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth >= 768 && localStorage.getItem('sidebar_mini') === 'true') {
                sidebar.classList.add('sidebar-mini');
            }
        });

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth >= 768) {
                // Desktop mode: toggle mini sidebar
                sidebar.classList.toggle('sidebar-mini');
                localStorage.setItem('sidebar_mini', sidebar.classList.contains('sidebar-mini'));
            } else {
                // Mobile mode: off-canvas drawer
                sidebar.classList.toggle('mobile-show');
                overlay.classList.toggle('show');
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('js')
</body>
</html>