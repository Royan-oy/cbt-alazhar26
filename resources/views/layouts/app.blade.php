<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CBT Online Sekolah')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --sb-bg: #0f172a;
            --sb-card: #1e293b;
            --sb-text-muted: #64748b;
            --sb-text-active: #38bdf8;
        }

        body {
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        /* --- LOGIKA RESPONSIVE SIDEBAR --- */
        .sidebar {
            min-width: 280px;
            max-width: 280px;
            background-color: var(--sb-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.03);
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
                box-shadow: 15px 0 30px rgba(0,0,0,0.25);
            }
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-color: rgba(15, 23, 42, 0.6);
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
            color: #94a3b8; padding: 12px 16px; border-radius: 12px; margin-bottom: 6px; font-size: 14px; font-weight: 500; display: flex; align-items: center; transition: all 0.2s ease;
        }
        .sidebar .nav-link:hover { background-color: rgba(255, 255, 255, 0.05); color: #f8fafc; transform: translateX(4px); }
        .sidebar .nav-link.active { background: linear-gradient(135deg, #0284c7, #0369a1); color: #ffffff; font-weight: 600; box-shadow: 0 8px 20px rgba(2, 132, 199, 0.25); }
        .sidebar .nav-link.active i { color: var(--sb-text-active); }
        .style-header .shadow-hover-danger:hover { background-color: #fff1f2; border-radius: 10px; }
    </style>
</head>
<body>

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
        function toggleSidebarMobile() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            // Toggle class show untuk memunculkan efek CSS transition slide-in
            sidebar.classList.toggle('mobile-show');
            overlay.classList.toggle('show');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>