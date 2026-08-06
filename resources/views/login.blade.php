<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login CBT Online - Claymorphism</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo-alazhar.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --ink: #1f2c4f;
            --muted: #6d7aa6;
            --clay-bg: #dbe6fb;
            --clay-surface: #eef3fd;
            --clay-surface-2: #e1eaf9;
            --brand: #4f7df3;
            --brand-2: #7aa4fb;
            --brand-deep: #2f56d1;
            --brand-navy: #1c3a8a;
            --shadow-dark: rgba(79, 125, 243, 0.24);
            --shadow-dark-2: rgba(79, 125, 243, 0.14);
            --shadow-light: rgba(255, 255, 255, 0.9);
            --danger: #e0566a;
            --danger-bg: #fbe7ea;
            --radius: 32px;
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--clay-bg);
            color: var(--ink);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ---------- Ambient backdrop: soft floating clay bubbles ---------- */
        .ambient {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .ambient span {
            position: absolute;
            border-radius: 50%;
        }

        .ambient .b1 {
            width: 320px; height: 320px;
            background: radial-gradient(circle at 32% 28%, #ffffff 0%, #c9d3fb 55%, #b6c2f7 100%);
            box-shadow: 24px 34px 60px rgba(79,125,243,0.18);
            top: -90px; left: -80px;
            opacity: 0.9;
        }

        .ambient .b2 {
            width: 260px; height: 260px;
            background: radial-gradient(circle at 30% 25%, #ffffff 0%, #bcd2fb 55%, #93b3f5 100%);
            box-shadow: 20px 28px 50px rgba(79,125,243,0.22);
            bottom: -70px; right: -60px;
            opacity: 0.85;
        }

        .ambient .b3 {
            width: 90px; height: 90px;
            background: radial-gradient(circle at 30% 25%, #ffffff 0%, #a9c1fb 100%);
            box-shadow: 10px 14px 26px rgba(79,125,243,0.25);
            top: 18%; right: 12%;
            opacity: 0.8;
        }

        .ambient .b4 {
            width: 46px; height: 46px;
            background: radial-gradient(circle at 30% 25%, #ffffff 0%, #a9c4fb 100%);
            box-shadow: 8px 10px 18px rgba(79,125,243,0.25);
            bottom: 20%; left: 9%;
            opacity: 0.8;
        }

        /* ---------- Page shell ---------- */
        .shell {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.25rem;
        }

        /* ---------- Lockup: embossed clay coin holding both logos ---------- */
        .lockup {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.7rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(150deg, #f4f6fe, var(--clay-surface-2));
            border-radius: 999px;
            padding: 0.65rem 1.4rem;
            box-shadow:
                8px 8px 18px var(--shadow-dark-2),
                -6px -6px 14px var(--shadow-light);
        }

        .lockup img {
            height: 30px;
            width: auto;
            object-fit: contain;
        }

        .lockup .divider {
            width: 2px;
            height: 22px;
            border-radius: 2px;
            background: linear-gradient(180deg, var(--brand), var(--brand-2));
            opacity: 0.5;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #fff;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            padding: 0.42rem 0.9rem;
            border-radius: 999px;
            margin-bottom: 1rem;
            box-shadow: 4px 6px 12px var(--shadow-dark-2);
        }

        .eyebrow i { font-size: 0.85rem; }

        /* ---------- Card: raised clay slab ---------- */
        .card-auth {
            width: 100%;
            max-width: 440px;
            background: linear-gradient(160deg, #f5f7ff 0%, var(--clay-surface) 55%, var(--clay-surface-2) 100%);
            border-radius: var(--radius);
            padding: 2.35rem 2.1rem;
            box-shadow:
                16px 16px 32px var(--shadow-dark),
                -12px -12px 26px var(--shadow-light),
                inset 1px 1px 1px rgba(255,255,255,0.7);
        }

        .card-auth h1 {
            font-family: 'Baloo 2', sans-serif;
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            margin-bottom: 0.35rem;
            color: var(--brand-navy);
        }

        .card-auth .subtitle {
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 1.75rem;
        }

        /* ---------- Role field: sculpted clay chip group (replaces <select>) ---------- */
        .role-field { margin-bottom: 1.3rem; }

        .field-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.65rem;
        }

        .role-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.65rem;
        }

        .role-chip { position: relative; }

        .role-chip input {
            position: absolute;
            opacity: 0;
            inset: 0;
            margin: 0;
            cursor: pointer;
        }

        .role-chip label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 0.75rem;
            border-radius: 18px;
            background: var(--clay-surface);
            color: var(--muted);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow:
                6px 6px 12px var(--shadow-dark-2),
                -5px -5px 10px var(--shadow-light);
            transition: box-shadow 0.15s ease, color 0.15s ease, background 0.15s ease, transform 0.15s ease;
        }

        .role-chip label i {
            font-size: 1rem;
            width: 1.2rem;
            text-align: center;
        }

        .role-chip label:hover { transform: translateY(-1px); }

        .role-chip input:checked + label {
            color: #fff;
            box-shadow:
                inset 4px 4px 8px rgba(0,0,0,0.18),
                inset -3px -3px 6px rgba(255,255,255,0.25);
            transform: translateY(0);
        }

        .role-chip:nth-child(1) input:checked + label { background: linear-gradient(135deg, var(--brand), var(--brand-2)); }
        .role-chip:nth-child(2) input:checked + label { background: linear-gradient(135deg, var(--brand-deep), #5f8ff5); }
        .role-chip:nth-child(3) input:checked + label { background: linear-gradient(135deg, #4f7df3, #6a9bf7); }
        .role-chip:nth-child(4) input:checked + label { background: linear-gradient(135deg, var(--brand-navy), var(--brand-deep)); }

        .role-chip input:focus-visible + label {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
        }

        /* ---------- Inputs: pressed clay wells ---------- */
        .field { margin-bottom: 1.15rem; }

        .input-shell {
            position: relative;
            display: flex;
            align-items: center;
            border-radius: 18px;
            background: var(--clay-bg);
            box-shadow:
                inset 5px 5px 10px var(--shadow-dark-2),
                inset -4px -4px 8px rgba(255,255,255,0.85);
            transition: box-shadow 0.15s ease;
        }

        .input-shell:focus-within {
            box-shadow:
                inset 5px 5px 10px rgba(79,125,243,0.28),
                inset -4px -4px 8px rgba(255,255,255,0.9),
                0 0 0 3px rgba(79,125,243,0.15);
        }

        .input-shell i.leading-icon {
            padding-left: 1rem;
            color: var(--brand);
            font-size: 0.95rem;
        }

        .input-shell input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.85rem 0.85rem;
            font-size: 0.92rem;
            color: var(--ink);
            min-width: 0;
        }

        .input-shell input:focus { outline: none; box-shadow: none; }

        .input-shell .toggle-btn {
            border: none;
            background: transparent;
            color: var(--muted);
            padding: 0 0.95rem;
            display: flex;
            align-items: center;
        }

        .input-shell .toggle-btn:hover { color: var(--brand); }

        /* ---------- Button: raised clay pill ---------- */
        .btn-submit {
            width: 100%;
            border: none;
            border-radius: 20px;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            color: #fff;
            font-weight: 700;
            font-size: 0.92rem;
            letter-spacing: 0.01em;
            padding: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            box-shadow:
                8px 10px 18px rgba(79,125,243,0.4),
                -4px -4px 10px rgba(255,255,255,0.5);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow:
                10px 14px 22px rgba(79,125,243,0.45),
                -4px -4px 10px rgba(255,255,255,0.55);
        }

        .btn-submit:active {
            transform: translateY(1px);
            box-shadow:
                inset 4px 4px 10px rgba(0,0,0,0.25),
                inset -3px -3px 6px rgba(255,255,255,0.2);
        }

        /* ---------- Alert: soft clay warning ---------- */
        .alert-modern {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            background: var(--danger-bg);
            color: var(--danger);
            border: none;
            border-radius: 16px;
            font-size: 0.85rem;
            padding: 0.8rem 1rem;
            margin-bottom: 1.25rem;
            box-shadow:
                inset 3px 3px 6px rgba(224,86,106,0.12),
                -3px -3px 8px rgba(255,255,255,0.7);
        }

        .alert-modern i { margin-top: 0.15rem; }

        /* ---------- Footer note ---------- */
        .footnote {
            text-align: center;
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: 1.75rem;
            opacity: 0.8;
        }

        /* ---------- Mobile refinements ---------- */
        @media (max-width: 400px) {
            .card-auth { padding: 1.9rem 1.4rem; }
            .lockup img { height: 26px; }
            .card-auth h1 { font-size: 1.4rem; }
            .role-grid { grid-template-columns: 1fr 1fr; gap: 0.5rem; }
            .role-chip label { font-size: 0.76rem; padding: 0.65rem 0.55rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            .btn-submit, .input-shell, .role-chip label { transition: none; }
        }
    </style>
</head>
<body>

    @include('layouts.loading')

    <div class="ambient">
        <span class="b1"></span>
        <span class="b2"></span>
        <span class="b3"></span>
        <span class="b4"></span>
    </div>

    <div class="shell">

        <div class="lockup">
            <img src="{{ asset('img/alazhar.png') }}" alt="Logo Sekolah">
            <div class="divider"></div>
            <img src="{{ asset('img/sigma.png') }}" alt="Logo CBT">
        </div>

        <div class="card-auth">

            <span class="eyebrow"><i class="bi bi-shield-check"></i> Sistem CBT Modern</span>
            <h1>Portal Ujian Sekolah</h1>
            <p class="subtitle">Masuk menggunakan akun resmi Anda untuk melanjutkan.</p>

            @if ($errors->any())
                <div class="alert-modern" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf

                <div class="role-field">
                    <label class="field-label">Masuk Sebagai</label>
                    <div class="role-grid">
                        <div class="role-chip">
                            <input type="radio" id="role_siswa" name="role" value="siswa" onchange="switchInputLabel()" {{ old('role', 'siswa') == 'siswa' ? 'checked' : '' }} required>
                            <label for="role_siswa"><i class="bi bi-pencil-fill"></i> Siswa</label>
                        </div>
                        <div class="role-chip">
                            <input type="radio" id="role_guru" name="role" value="guru" onchange="switchInputLabel()" {{ old('role') == 'guru' ? 'checked' : '' }}>
                            <label for="role_guru"><i class="bi bi-easel-fill"></i> Guru</label>
                        </div>
                        <div class="role-chip">
                            <input type="radio" id="role_admin_jenjang" name="role" value="admin_jenjang" onchange="switchInputLabel()" {{ old('role') == 'admin_jenjang' ? 'checked' : '' }}>
                            <label for="role_admin_jenjang"><i class="bi bi-diagram-3-fill"></i> Admin Jenjang</label>
                        </div>
                        <div class="role-chip">
                            <input type="radio" id="role_super_admin" name="role" value="super_admin" onchange="switchInputLabel()" {{ old('role') == 'super_admin' ? 'checked' : '' }}>
                            <label for="role_super_admin"><i class="bi bi-shield-lock-fill"></i> Super Admin</label>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label id="identity_label" for="login_identity" class="field-label">Nomor Induk Siswa (NIS)</label>
                    <div class="input-shell">
                        <i id="identity_icon" class="bi bi-card-text leading-icon"></i>
                        <input type="text" id="login_identity" name="login_identity" value="{{ old('login_identity') }}" placeholder="Contoh: 212210043" required autofocus>
                    </div>
                </div>

                <div class="field">
                    <label for="password" class="field-label">Kata Sandi</label>
                    <div class="input-shell">
                        <i class="bi bi-lock leading-icon"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <button class="toggle-btn" type="button" id="togglePassword">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <span>Masuk Sekarang</span>
                    <i class="bi bi-arrow-right-short fs-5"></i>
                </button>
            </form>

            <div class="footnote">&copy; 2026 CBT Hub Multi-Jenjang Sekolah</div>

        </div>

    </div>

    <script>
        function switchInputLabel() {
            const checked = document.querySelector('input[name="role"]:checked');
            if (!checked) return;
            const label = document.getElementById('identity_label');
            const input = document.getElementById('login_identity');
            const icon = document.getElementById('identity_icon');

            if (checked.value === 'siswa') {
                label.innerText = "Nomor Induk Siswa (NIS)";
                input.placeholder = "Contoh: 212210043";
                input.type = "text";
                icon.className = "bi bi-card-text leading-icon";
            } else {
                label.innerText = "Alamat Email Resmi";
                input.placeholder = "nama@sekolah.sch.id";
                input.type = "email";
                icon.className = "bi bi-envelope leading-icon";
            }
        }

        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });

        window.onload = switchInputLabel;
    </script>
</body>
</html>