<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>403 - Akses Ditolak | CBT Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-dark: #0f172a;
            --secondary-dark: #1e293b;
            --accent-red: #ef4444;
            --accent-light: #f87171;
            --accent-blue: #0ea5e9;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Ambient Glow Background - Reddish tone for Forbidden */
        .ambient-glow {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }

        .ambient-glow::before,
        .ambient-glow::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.12;
            animation: pulse-glow 8s infinite alternate ease-in-out;
        }

        .ambient-glow::before {
            top: 20%;
            left: 20%;
            width: 450px;
            height: 450px;
            background: var(--accent-red);
        }

        .ambient-glow::after {
            bottom: 10%;
            right: 20%;
            width: 500px;
            height: 500px;
            background: #4c1d95; /* Deep purple/blue */
            animation-delay: -4s;
        }

        @keyframes pulse-glow {
            0% { transform: scale(0.8) translate(0, 0); opacity: 0.08; }
            100% { transform: scale(1.1) translate(20px, -20px); opacity: 0.15; }
        }

        .container {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 2rem;
            max-width: 600px;
            width: 100%;
        }

        /* Animated SVG Illustration */
        .illustration {
            width: 260px;
            height: 260px;
            margin: 0 auto 1.5rem;
            position: relative;
        }

        .svg-teacher {
            transform-origin: bottom center;
            animation: breathe 4s ease-in-out infinite;
        }

        .svg-shadow {
            animation: shadowPulse 4s ease-in-out infinite;
            transform-origin: center;
            opacity: 0.3;
        }

        .svg-warning {
            animation: floatWarning 3s ease-in-out infinite alternate;
            transform-origin: center;
        }

        @keyframes breathe {
            0%, 100% { transform: scaleY(1); }
            50% { transform: scaleY(1.02); }
        }

        @keyframes floatWarning {
            0% { transform: translateY(0) scale(1); filter: drop-shadow(0 0 10px rgba(239, 68, 68, 0.4)); }
            100% { transform: translateY(-10px) scale(1.05); filter: drop-shadow(0 0 20px rgba(239, 68, 68, 0.8)); }
        }

        @keyframes shadowPulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(0.85); opacity: 0.15; }
        }

        .error-code {
            font-size: 5rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #ffffff, var(--accent-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -2px;
        }

        .error-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #ffffff;
        }

        .error-message {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--text-muted);
            margin-bottom: 2.5rem;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, var(--accent-red), #b91c1c);
            border-radius: 12px;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(248, 113, 113, 0.2);
            cursor: pointer;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
            filter: brightness(1.1);
            color: #ffffff;
        }

        .btn-back:active {
            transform: translateY(0);
        }

        /* Floating particles */
        .particles {
            position: absolute;
            inset: 0;
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            background-color: var(--accent-red);
            border-radius: 50%;
            opacity: 0.15;
            animation: drift linear infinite;
        }

        @keyframes drift {
            from { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.3; }
            90% { opacity: 0.3; }
            to { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        @media (max-width: 480px) {
            .illustration { width: 200px; height: 200px; }
            .error-code { font-size: 4rem; }
            .error-title { font-size: 1.5rem; }
            .error-message { font-size: 0.9rem; }
            .ambient-glow::before, .ambient-glow::after { filter: blur(60px); }
        }
    </style>
</head>
<body>

    <div class="ambient-glow"></div>
    
    <div class="particles" id="particles">
        <!-- Particles generated via JS -->
    </div>

    <div class="container">
        <!-- SVG Illustration: Strict Teacher Silhouette -->
        <div class="illustration">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <!-- Floor Shadow -->
                <ellipse class="svg-shadow" cx="100" cy="180" rx="55" ry="8" fill="#000000" />
                
                <!-- Teacher Silhouette -->
                <g class="svg-teacher">
                    <!-- Shoulders/Torso -->
                    <path d="M50,175 C50,110 70,95 100,95 C130,95 150,110 150,175 Z" fill="#1e293b" stroke="#334155" stroke-width="3" stroke-linejoin="round" />
                    <!-- Collar/Neck -->
                    <rect x="90" y="85" width="20" height="15" fill="#334155" />
                    <!-- Head -->
                    <circle cx="100" cy="65" r="25" fill="#1e293b" stroke="#334155" stroke-width="3" />
                    <!-- Glasses (Strict vibe) -->
                    <rect x="83" y="60" width="14" height="6" rx="2" fill="#0ea5e9" opacity="0.8" />
                    <rect x="103" y="60" width="14" height="6" rx="2" fill="#0ea5e9" opacity="0.8" />
                    <line x1="97" y1="63" x2="103" y2="63" stroke="#0ea5e9" stroke-width="2" />
                    
                    <!-- Crossed Arms -->
                    <!-- Left arm folding to right -->
                    <path d="M55,130 C70,145 90,155 125,145" fill="none" stroke="#334155" stroke-width="12" stroke-linecap="round" />
                    <path d="M55,130 C70,145 90,155 125,145" fill="none" stroke="#475569" stroke-width="8" stroke-linecap="round" />
                    <!-- Right arm folding over left -->
                    <path d="M145,130 C130,145 110,155 75,145" fill="none" stroke="#334155" stroke-width="12" stroke-linecap="round" />
                    <path d="M145,130 C130,145 110,155 75,145" fill="none" stroke="#64748b" stroke-width="8" stroke-linecap="round" />
                </g>

                <!-- Floating Warning Sign -->
                <g class="svg-warning">
                    <polygon points="160,50 140,85 180,85" fill="#ef4444" stroke="#f87171" stroke-width="2" stroke-linejoin="round" />
                    <line x1="160" y1="60" x2="160" y2="72" stroke="#ffffff" stroke-width="3" stroke-linecap="round" />
                    <circle cx="160" cy="78" r="1.5" fill="#ffffff" />
                </g>
                
                <!-- Floating X Signs -->
                <g style="animation: float 5s ease-in-out infinite;">
                    <path d="M35,60 L45,70 M45,60 L35,70" stroke="#f87171" stroke-width="3" stroke-linecap="round" opacity="0.6" />
                    <path d="M150,150 L160,160 M160,150 L150,160" stroke="#f87171" stroke-width="2" stroke-linecap="round" opacity="0.4" />
                </g>
            </svg>
        </div>

        <!-- Text Content -->
        <h1 class="error-code">403</h1>
        <h2 class="error-title">Akses Ditolak</h2>
        <p class="error-message">
            Maaf, Anda tidak memiliki izin atau hak akses untuk melihat halaman ini. 
            Silakan kembali ke halaman sebelumnya atau hubungi administrator.
        </p>
        
        <!-- Call to Action -->
        <button onclick="goBackOrHome()" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Halaman Sebelumnya
        </button>
    </div>

    <!-- Scripts -->
    <script>
        // Fallback Navigation Logic
        function goBackOrHome() {
            // Jika ada referer dan referer tidak sama dengan halaman saat ini, bisa kembali
            if (document.referrer && document.referrer !== window.location.href) {
                window.history.back();
            } 
            // Jika history browser lebih dari 1 (kadang referrer kosong tapi history ada)
            else if (window.history.length > 1) {
                window.history.back();
            } 
            // Fallback jika dibuka dari tab baru atau link langsung
            else {
                window.location.href = "{{ url('/') }}";
            }
        }

        // Particle generator
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('particles');
            const particleCount = 12;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Random properties
                const size = Math.random() * 5 + 2;
                const left = Math.random() * 100;
                const duration = Math.random() * 15 + 10;
                const delay = Math.random() * 10;
                
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = left + '%';
                particle.style.animationDuration = duration + 's';
                particle.style.animationDelay = '-' + delay + 's';
                
                container.appendChild(particle);
            }
        });
    </script>
</body>
</html>
