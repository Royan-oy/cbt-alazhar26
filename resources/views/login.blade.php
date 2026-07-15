<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login CBT Online - Modern Split</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --dark-slate: #0f172a;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
        }

        .login-wrapper {
            min-height: 100vh;
        }

        /* Sisi Kiri: Gambar Latar Belakang Gedung */
        .bg-brand-side {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 27, 75, 0.8) 100%), 
                        url('/img/gedung.jpeg');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* Sisi Kanan: Form Area yang Lebih Lebar */
        .form-side {
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Pengaturan Lebar Card Form Baru (Lebih Proposional & Lebar) */
        .login-card {
            width: 100%;
            max-width: 520px; /* Diubah menjadi lebih lebar agar tidak lonjong ke bawah */
            padding: 2.5rem !important;
        }

        /* Form Controls Styling */
        .form-control, .form-select {
            border: 1.5px solid #e2e8f0;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-color: #f8fafc;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
            background-color: #fff;
        }

        .input-group-text {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            color: #64748b;
        }

        /* Tombol Modern */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #6366f1);
            border: none;
            padding: 0.8rem;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-hover), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
        }

        /* Branding Logo Box */
        .logo-box {
            background: linear-gradient(135deg, var(--primary-color), #818cf8);
            width: 55px;
            height: 55px;
            font-size: 20px;
            letter-spacing: 1px;
        }

        .form-label {
            font-size: 0.825rem;
            letter-spacing: 0.5px;
            color: #475569 !important;
        }

        /* Media Query untuk Tampilan HP dan Tablet (Layar < 992px) */
        @media (max-width: 991.98px) {
            .login-wrapper {
                min-height: auto;
            }

            /* Mengubah sisi kiri (gambar gedung) menjadi banner atas di HP */
            .bg-brand-side {
                display: flex !important; /* Memunculkan kembali gambar di HP */
                min-height: 220px;
                padding: 2rem !important;
                text-align: center;
                align-items: center;
                justify-content: center;
            }

            .bg-brand-side .position-relative {
                max-width: 100% !important;
            }

            .bg-brand-side h1 {
                font-size: 1.75rem !important; /* Mengecilkan ukuran teks judul di HP */
                margin-bottom: 0.5rem !important;
            }

            .bg-brand-side p {
                font-size: 0.9rem !important; /* Mengecilkan teks deskripsi */
                margin-bottom: 0 !important;
            }

            /* Mengatur area form di HP */
            .form-side {
                padding: 1.5rem !important;
                background-color: #f8fafc; /* Menyamakan background dengan body */
            }

            /* Membuat card form melengkung manis di HP */
            .login-card {
                max-width: 100%;
                padding: 2rem 1.5rem !important;
                border-radius: 20px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
                border: 1px solid #e2e8f0;
            }

            /* Menyembunyikan logo box ganda di form karena sudah ada banner di atas */
            .form-side .logo-box {
                display: none !important;
            }
            
            /* Menyelaraskan teks form ke tengah agar rapi di HP */
            .form-side .text-lg-start {
                text-align: center !important;
            }
        }

        /* Media Query Khusus Optimasi Font & Komponen di Layar HP (< 576px) */
        @media (max-width: 575.98px) {
            /* Ukuran teks pada banner gedung (bagian atas) */
            .bg-brand-side h1 {
                font-size: 1.5rem !important;
                font-weight: 700 !important;
            }
            
            .bg-brand-side p {
                font-size: 0.85rem !important;
            }

            /* Ukuran teks judul form login */
            .form-side h3 {
                font-size: 1.35rem !important;
                font-weight: 700 !important;
            }

            .form-side p.text-muted {
                font-size: 0.8rem !important;
                margin-bottom: 1.5rem !important;
            }

            /* Menyesuaikan ukuran label input (NIS, Password, dll) */
            .form-label {
                font-size: 0.75rem !important;
                margin-bottom: 0.4rem !important;
            }

            /* Mengecilkan teks di dalam kotak input & dropdown select agar pas */
            .form-control, .form-select, .input-group-text {
                font-size: 0.875rem !important;
                padding: 0.65rem 0.85rem !important;
                border-radius: 10px !important; /* Sedikit lebih kotak agar rapi di layar kecil */
            }

            /* Menyesuaikan ukuran tombol masuk */
            .btn-primary {
                font-size: 0.9rem !important;
                padding: 0.7rem !important;
                border-radius: 10px !important;
            }

            /* Mengurangi jarak antar komponen biar tidak terlalu panjang ke bawah */
            .mb-3 {
                margin-bottom: 0.85rem !important;
            }
            .mb-4 {
                margin-bottom: 1.25rem !important;
            }
        }

        /* Taruh di bagian CSS utama (di luar media query) */
        .logo-responsive {
            height: 50px; /* Tinggi logo standar di layar laptop/komputer */
            width: auto;
            object-fit: contain;
        }

        /* Taruh di dalam @media (max-width: 575.98px) yang sudah kita buat sebelumnya */
        @media (max-width: 575.98px) {
            .logo-responsive {
                height: 40px; /* Otomatis mengecil jadi 40px saat dibuka di HP */
            }
            .gap-3 {
                gap: 0.5rem !important; /* Jarak antar logo sedikit merapat di HP */
            }
        }
        
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0 login-wrapper">
            
            <div class="col-lg-6 d-none d-lg-flex bg-brand-side align-items-center justify-content-center p-5 text-white">
                <div class="position-relative z-1 text-center text-lg-start" style="max-width: 500px;">
                    <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 fw-semibold tracking-wider">Sistem CBT Modern</span>
                    <h1 class="display-5 fw-bold mb-3">Selamat Datang Kembali</h1>
                    <p class="lead text-white-50">Silakan masuk untuk mengakses ujian, memantau hasil, dan mengelola aktivitas akademik Anda secara real-time.</p>
                </div>
            </div>

            <div class="col-lg-6 form-side p-4 p-sm-5">
                <div class="login-card">
                    
                    <div class="text-center text-lg-start mb-4">
                        <div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-3 mb-3">
                            <img src="{{ asset('img/alazhar.png') }}" alt="Logo Sekolah" class="img-fluid logo-responsive">
                            
                            <div class="vr bg-secondary opacity-25 d-none d-sm-block" style="height: 40px; width: 1.5px;"></div>
                            
                            <img src="{{ asset('img/sigma.png') }}" alt="Logo CBT" class="img-fluid logo-responsive">
                        </div>
                        <h3 class="fw-bold text-dark mb-1">Portal Ujian Sekolah</h3>
                        <p class="text-muted small">Silakan masuk menggunakan akun resmi Anda</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 small py-2.5 rounded-3 d-flex align-items-center gap-2 mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>{{ $errors->first() }}</div>
                        </div>
                    @endif

                    <form action="{{ url('/login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="role" class="form-label fw-bold text-uppercase">Masuk Sebagai</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0"><i class="bi bi-person-badge"></i></span>
                                <select id="role" name="role" onchange="switchInputLabel()" class="form-select border-start-0 text-dark fw-medium" required>
                                    <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa (Gunakan NIS)</option>
                                    <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                                    <option value="admin_jenjang" {{ old('role') == 'admin_jenjang' ? 'selected' : '' }}>Admin Per Jenjang</option>
                                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label id="identity_label" for="login_identity" class="form-label fw-bold text-uppercase">Nomor Induk Siswa (NIS)</label>
                            <div class="input-group">
                                <span id="identity_icon" class="input-group-text border-end-0"><i class="bi bi-card-text"></i></span>
                                <input type="text" id="login_identity" name="login_identity" value="{{ old('login_identity') }}" class="form-control border-start-0 text-dark" placeholder="Contoh: 212210043" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label Republic for="password" class="form-label fw-bold text-uppercase">Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0"><i class="bi bi-lock"></i></span>
                                <input type="password" id="password" name="password" class="form-control border-start-0 border-end-0 text-dark" placeholder="••••••••" required>
                                <button class="input-group-text bg-transparent border-start-0 text-muted" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm py-2.5">
                            <span>Masuk Sekarang</span>
                            <i class="bi bi-arrow-right-short fs-5"></i>
                        </button>
                    </form>

                    <div class="text-center text-lg-start mt-5 pt-3 border-top small text-muted" style="font-size: 11px; border-color: rgba(0,0,0,0.06) !important;">
                        &copy; 2026 CBT Hub Multi-Jenjang Sekolah
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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
                icon.innerHTML = '<i class="bi bi-card-text"></i>';
            } else {
                label.innerText = "Alamat Email Resmi";
                input.placeholder = "nama@sekolah.sch.id";
                input.type = "email";
                icon.innerHTML = '<i class="bi bi-envelope"></i>';
            }
        }
        
        // Fitur: Show/Hide Password
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });

        // Jalankan fungsi saat halaman di-load
        window.onload = switchInputLabel;
    </script>
</body>
</html>