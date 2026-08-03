<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>404 - Halaman Tidak Ditemukan | CBT Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-dark: #0f172a;
            --secondary-dark: #1e293b;
            --accent-blue: #0ea5e9;
            --accent-light: #38bdf8;
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

        /* Ambient Glow Background */
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
            opacity: 0.15;
            animation: pulse-glow 8s infinite alternate ease-in-out;
        }

        .ambient-glow::before {
            top: 10%;
            left: 20%;
            width: 400px;
            height: 400px;
            background: var(--accent-blue);
        }

        .ambient-glow::after {
            bottom: 10%;
            right: 20%;
            width: 500px;
            height: 500px;
            background: #8b5cf6;
            animation-delay: -4s;
        }

        @keyframes pulse-glow {
            0% { transform: scale(0.8) translate(0, 0); opacity: 0.1; }
            100% { transform: scale(1.1) translate(20px, -20px); opacity: 0.2; }
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
            width: 240px;
            height: 240px;
            margin: 0 auto 1.5rem;
            position: relative;
        }

        .svg-paper {
            animation: float 4s ease-in-out infinite;
            transform-origin: center;
        }

        .svg-shadow {
            animation: shadowPulse 4s ease-in-out infinite;
            transform-origin: center;
            opacity: 0.2;
        }

        .svg-magnifier {
            animation: searchFloat 5s ease-in-out infinite reverse;
            transform-origin: 60% 60%;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(-2deg); }
            50% { transform: translateY(-15px) rotate(2deg); }
        }

        @keyframes searchFloat {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(-10px, -15px) rotate(-15deg); }
            66% { transform: translate(15px, -5px) rotate(10deg); }
        }

        @keyframes shadowPulse {
            0%, 100% { transform: scale(1); opacity: 0.2; }
            50% { transform: scale(0.8); opacity: 0.1; }
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

        .btn-home {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, var(--accent-blue), #0284c7);
            border-radius: 12px;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(56, 189, 248, 0.2);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.4);
            filter: brightness(1.1);
            color: #ffffff;
        }

        .btn-home:active {
            transform: translateY(0);
        }

        /* Floating elements in background */
        .particles {
            position: absolute;
            inset: 0;
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            background-color: var(--accent-light);
            border-radius: 50%;
            opacity: 0.2;
            animation: drift linear infinite;
        }

        @keyframes drift {
            from { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.4; }
            90% { opacity: 0.4; }
            to { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        @media (max-width: 480px) {
            .illustration { width: 180px; height: 180px; }
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
        <!-- SVG Illustration -->
        <div class="illustration">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <!-- Shadow -->
                <ellipse class="svg-shadow" cx="100" cy="180" rx="60" ry="10" fill="#000000" />
                
                <!-- Floating Paper -->
                <g class="svg-paper">
                    <!-- Paper base -->
                    <rect x="50" y="40" width="90" height="110" rx="6" fill="#f8fafc" stroke="#cbd5e1" stroke-width="2" />
                    <!-- Folded corner -->
                    <polygon points="110,40 140,40 140,70" fill="#e2e8f0" stroke="#cbd5e1" stroke-width="2" stroke-linejoin="round" />
                    <polyline points="110,40 110,70 140,70" fill="none" stroke="#cbd5e1" stroke-width="2" stroke-linejoin="round" />
                    
                    <!-- Exam Lines & Checkmarks -->
                    <line x1="65" y1="65" x2="100" y2="65" stroke="#94a3b8" stroke-width="3" stroke-linecap="round" />
                    <line x1="65" y1="85" x2="120" y2="85" stroke="#e2e8f0" stroke-width="3" stroke-linecap="round" />
                    <line x1="65" y1="105" x2="110" y2="105" stroke="#e2e8f0" stroke-width="3" stroke-linecap="round" />
                    <line x1="65" y1="125" x2="125" y2="125" stroke="#e2e8f0" stroke-width="3" stroke-linecap="round" />
                    
                    <circle cx="115" cy="65" r="5" fill="#ef4444" />
                    <path d="M112,62 L118,68 M118,62 L112,68" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" />
                    
                    <path d="M75,95 L95,80 M75,80 L95,95" stroke="#cbd5e1" stroke-width="15" stroke-linecap="round" stroke-dasharray="1,15" opacity="0.3"/>
                </g>

                <!-- Floating Magnifying Glass -->
                <g class="svg-magnifier">
                    <circle cx="135" cy="120" r="22" fill="rgba(14, 165, 233, 0.15)" stroke="#38bdf8" stroke-width="4" />
                    <line x1="120" y1="135" x2="100" y2="155" stroke="#0284c7" stroke-width="6" stroke-linecap="round" />
                    <path d="M130,110 A10,10 0 0,1 145,115" stroke="#ffffff" stroke-width="2" stroke-linecap="round" fill="none" opacity="0.6"/>
                </g>
                
                <!-- Floating Question Marks -->
                <g class="svg-paper" style="animation-delay: -1s;">
                    <text x="30" y="60" font-family="Inter" font-weight="bold" font-size="24" fill="#64748b" opacity="0.6" transform="rotate(-15, 30, 60)">?</text>
                    <text x="155" y="50" font-family="Inter" font-weight="bold" font-size="18" fill="#38bdf8" opacity="0.8" transform="rotate(20, 155, 50)">?</text>
                    <text x="145" y="165" font-family="Inter" font-weight="bold" font-size="20" fill="#8b5cf6" opacity="0.5" transform="rotate(-10, 145, 165)">?</text>
                </g>
            </svg>
        </div>

        <!-- Text Content -->
        <h1 class="error-code">404</h1>
        <h2 class="error-title">Halaman Tidak Ditemukan</h2>
        <p class="error-message">
            Ups! Kertas ujian atau halaman yang Anda cari sepertinya tidak ada di sistem kami. 
            Mungkin URL-nya salah atau halamannya sudah dihapus.
        </p>
        
        <!-- Call to Action -->
        <a href="{{ url('/') }}" class="btn-home">
            <i class="fa-solid fa-house"></i> Kembali ke Halaman Utama
        </a>
    </div>

    <!-- Script for floating particles -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('particles');
            const particleCount = 15;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Random properties
                const size = Math.random() * 4 + 2;
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
