<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login CBT Online - Modern Minimalist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --ink: #14142b;
            --muted: #6b7280;
            --line: #e7e5ef;
            --surface: #ffffff;
            --canvas: #f6f5fb;
            --brand: #4f46e5;
            --brand-2: #8b5cf6;
            --brand-soft: #eef0ff;
            --danger: #dc2626;
            --radius: 18px;
        }

        * { box-sizing: border-box; }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--canvas);
            color: var(--ink);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ---------- Ambient backdrop: two soft blurred fields, not a stock gradient card ---------- */
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
            filter: blur(90px);
            opacity: 0.35;
        }

        .ambient .b1 {
            width: 480px; height: 480px;
            background: var(--brand);
            top: -160px; left: -140px;
        }

        .ambient .b2 {
            width: 420px; height: 420px;
            background: var(--brand-2);
            bottom: -180px; right: -120px;
            opacity: 0.25;
        }

        .ambient .grid-overlay {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(to right, rgba(20,20,43,0.035) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(20,20,43,0.035) 1px, transparent 1px);
            background-size: 42px 42px;
            mask-image: radial-gradient(circle at 50% 30%, black 0%, transparent 70%);
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

        /* ---------- Lockup above the card ---------- */
        .lockup {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.85rem;
            margin-bottom: 1.75rem;
        }

        .lockup img {
            height: 34px;
            width: auto;
            object-fit: contain;
        }

        .lockup .divider {
            width: 1px;
            height: 24px;
            background: var(--line);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--brand);
            background: var(--brand-soft);
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
            margin-bottom: 0.9rem;
        }

        .eyebrow i { font-size: 0.85rem; }

        /* ---------- Card ---------- */
        .card-auth {
            width: 100%;
            max-width: 420px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 2.25rem 2rem;
            box-shadow: 0 1px 2px rgba(20,20,43,0.04), 0 20px 45px -20px rgba(20,20,43,0.18);
        }

        .card-auth h1 {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            margin-bottom: 0.35rem;
        }

        .card-auth .subtitle {
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 1.75rem;
        }

        /* ---------- Role segmented control (replaces plain <select> look) ---------- */
        .role-field { margin-bottom: 1.15rem; }

        .field-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.5rem;
        }

        .role-select-wrap {
            position: relative;
        }

        .role-select-wrap select {
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
            border: 1.5px solid var(--line);
            border-radius: 12px;
            background: var(--canvas);
            color: var(--ink);
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.8rem 2.6rem 0.8rem 2.75rem;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        }

        .role-select-wrap select:focus {
            outline: none;
            border-color: var(--brand);
            background: var(--surface);
            box-shadow: 0 0 0 4px var(--brand-soft);
        }

        .role-select-wrap i.role-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--brand);
            font-size: 1rem;
            pointer-events: none;
        }

        .role-select-wrap i.chevron {
            position: absolute;
            right: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 0.85rem;
            pointer-events: none;
        }

        /* ---------- Inputs ---------- */
        .field { margin-bottom: 1.15rem; }

        .input-shell {
            position: relative;
            display: flex;
            align-items: center;
            border: 1.5px solid var(--line);
            border-radius: 12px;
            background: var(--canvas);
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        }

        .input-shell:focus-within {
            border-color: var(--brand);
            background: var(--surface);
            box-shadow: 0 0 0 4px var(--brand-soft);
        }

        .input-shell i.leading-icon {
            padding-left: 0.9rem;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .input-shell input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.8rem 0.85rem;
            font-size: 0.92rem;
            color: var(--ink);
            min-width: 0;
        }

        .input-shell input:focus {
            outline: none;
            box-shadow: none;
        }

        .input-shell .toggle-btn {
            border: none;
            background: transparent;
            color: var(--muted);
            padding: 0 0.9rem;
            display: flex;
            align-items: center;
        }

        .input-shell .toggle-btn:hover { color: var(--brand); }

        /* ---------- Button ---------- */
        .btn-submit {
            width: 100%;
            border: none;
            border-radius: 12px;
            background: var(--brand);
            color: #fff;
            font-weight: 700;
            font-size: 0.92rem;
            letter-spacing: 0.01em;
            padding: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.4rem;
            transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
        }

        .btn-submit:hover {
            background: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 10px 24px -8px rgba(79,70,229,0.55);
        }

        .btn-submit:active { transform: translateY(0); }

        /* ---------- Alert ---------- */
        .alert-modern {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: var(--danger);
            border-radius: 12px;
            font-size: 0.85rem;
            padding: 0.75rem 0.9rem;
            margin-bottom: 1.25rem;
        }

        .alert-modern i { margin-top: 0.15rem; }

        /* ---------- Footer note ---------- */
        .footnote {
            text-align: center;
            font-size: 0.72rem;
            color: #9ca3af;
            margin-top: 1.75rem;
        }

        /* ---------- Mobile refinements ---------- */
        @media (max-width: 400px) {
            .card-auth { padding: 1.85rem 1.35rem; }
            .lockup img { height: 28px; }
            .card-auth h1 { font-size: 1.3rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            .btn-submit, .input-shell, .role-select-wrap select { transition: none; }
        }
    </style>
</head>
<body>

    <div class="ambient">
        <span class="b1"></span>
        <span class="b2"></span>
        <div class="grid-overlay"></div>
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
                    <label for="role" class="field-label">Masuk Sebagai</label>
                    <div class="role-select-wrap">
                        <i class="bi bi-person-badge role-icon"></i>
                        <select id="role" name="role" onchange="switchInputLabel()" required>
                            <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa (Gunakan NIS)</option>
                            <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="admin_jenjang" {{ old('role') == 'admin_jenjang' ? 'selected' : '' }}>Admin Per Jenjang</option>
                            <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        <i class="bi bi-chevron-down chevron"></i>
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
            const roleSelect = document.getElementById('role');
            const label = document.getElementById('identity_label');
            const input = document.getElementById('login_identity');
            const icon = document.getElementById('identity_icon');

            if (roleSelect.value === 'siswa') {
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