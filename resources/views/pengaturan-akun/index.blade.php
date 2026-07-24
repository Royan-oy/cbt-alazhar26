@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')
<style>
    :root {
        --sb-bg: #0f172a;
        --sb-card: #1e293b;

        --primary: #0ea5e9;
        --primary-dark: #0284c7;
        --primary-light: #eff8ff;

        --teal: #0d9488;
        --teal-light: #f0fdfa;

        --amber: #d97706;
        --amber-light: #fffbeb;

        --violet: #7c3aed;
        --violet-light: #f5f3ff;

        --emerald: #059669;
        --emerald-light: #ecfdf5;

        --ink-900: #0f172a;
        --ink-700: #334155;
        --ink-500: #64748b;
        --border: #e5e9f2;
    }

    /* --- HEADER --- */
    .account-header {
        background: linear-gradient(135deg, var(--sb-bg) 0%, var(--sb-card) 60%, #1e293b 100%);
        border: 1px solid rgba(255,255,255,.05);
        position: relative;
        overflow: hidden;
        border-radius: 18px;
        color: white;
        padding: 30px;
        margin-bottom: 28px;
    }

    .account-header::after {
        content: '';
        position: absolute;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(56,189,248,.16) 0%, transparent 70%);
        top: -100px; right: -50px;
        border-radius: 50%;
        pointer-events: none;
    }

    .account-header::before {
        content: '';
        position: absolute;
        width: 220px; height: 220px;
        background: radial-gradient(circle, rgba(124,58,237,.12) 0%, transparent 70%);
        bottom: -100px; left: -40px;
        border-radius: 50%;
        pointer-events: none;
    }

    .account-header > div { position: relative; }

    /* --- CARD --- */
    .premium-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid var(--border);
        box-shadow: 0 10px 30px -8px rgba(15,23,42,.06);
        transition: box-shadow .3s ease;
        height: fit-content;
    }

    .premium-card:hover {
        box-shadow: 0 16px 38px -8px rgba(15,23,42,.1);
    }

    @media (min-width: 992px) {
        .sticky-profile-col { position: sticky; top: 90px; z-index: 10; }
    }

    /* --- FOTO PROFIL --- */
    .profile-img-container {
        width: 130px; height: 130px;
        border-radius: 50%;
        margin: 0 auto;
        position: relative;
        border: 4px solid #f8fafc;
        box-shadow: 0 10px 26px -6px rgba(15,23,42,.14);
        overflow: hidden;
        background: linear-gradient(135deg, var(--teal-light), var(--primary-light));
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-img-container img { width: 100%; height: 100%; object-fit: cover; }
    .profile-img-container i { font-size: 55px; color: var(--teal); opacity: .5; }

    .btn-edit-photo {
        position: absolute;
        bottom: 0; left: 0; width: 100%;
        background: rgba(15,23,42,.78);
        color: white;
        text-align: center;
        padding: 6px 0;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        opacity: 0;
        transition: opacity .3s ease;
    }

    .profile-img-container:hover .btn-edit-photo { opacity: 1; }

    /* --- FORM --- */
    .form-control-premium {
        border-radius: 11px;
        border: 1px solid var(--border);
        padding: 12px 16px;
        font-size: 14px;
        color: var(--ink-700);
        background-color: #f8fafc;
        transition: all .2s ease;
    }

    .form-control-premium:focus {
        background-color: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(14,165,233,.13);
        outline: none;
    }

    .form-control-premium[readonly] {
        background-color: #f1f5f9;
        color: var(--ink-500);
        cursor: not-allowed;
    }

    .form-label-premium {
        font-weight: 700;
        color: var(--ink-700);
        font-size: 12.5px;
        margin-bottom: 8px;
    }

    .btn-premium {
        background: linear-gradient(135deg, var(--sb-bg), var(--primary-dark));
        border: none;
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        transition: all .2s ease;
        box-shadow: 0 8px 18px -6px rgba(2,132,199,.35);
    }

    .btn-premium:hover {
        background: linear-gradient(135deg, var(--sb-card), var(--primary));
        transform: translateY(-2px);
        box-shadow: 0 12px 24px -6px rgba(2,132,199,.45);
        color: white;
    }

    .section-title {
        font-size: 15.5px;
        font-weight: 800;
        color: var(--ink-900);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        width: 28px; height: 28px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px;
    }

    .section-icon-teal   { background: var(--teal-light);   color: var(--teal); }
    .section-icon-violet { background: var(--violet-light); color: var(--violet); }
    .section-icon-amber  { background: var(--amber-light);  color: var(--amber); }

    /* --- ALERT INFO KUSTOM --- */
    .alert-info-premium {
        background: var(--violet-light) !important;
        color: #5b21b6 !important;
        border-radius: 12px !important;
    }

    /* --- BADGE KELAS --- */
    .badge-kelas-aktif {
        background: var(--emerald-light);
        color: var(--emerald);
        border: 1px solid #a7f3d0;
    }

    .badge-kelas-kosong {
        background: var(--amber-light);
        color: var(--amber);
        border: 1px solid #fde3b8;
    }

    /* --- TABEL RIWAYAT --- */
    .table-riwayat thead { background-color: #f8fafc; }
    .table-riwayat thead th {
        font-size: 11px; color: var(--ink-500);
        text-transform: uppercase; letter-spacing: .5px;
        padding: 13px 14px; font-weight: 700;
    }
    .table-riwayat tbody td {
        padding: 13px 14px; font-size: 13.5px;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-riwayat tbody tr:hover { background-color: #fafbfc; }

    .badge-status-aktif {
        background: var(--emerald-light); color: var(--emerald);
        border: 1px solid #a7f3d0;
    }
    .badge-status-selesai {
        background: #f1f5f9; color: var(--ink-500);
        border: 1px solid var(--border);
    }

    /* =========================================================
       RESPONSIVE — HP
       ========================================================= */
    @media (max-width: 575.98px) {
        .account-header { padding: 22px; border-radius: 16px; margin-bottom: 20px; }
        .account-header h4 { font-size: 17px; }
        .account-header p { font-size: 12.5px; }

        .premium-card { border-radius: 16px; }
        .premium-card.p-4, .premium-card.p-4.p-md-5 { padding: 18px !important; }

        .profile-img-container { width: 100px; height: 100px; }
        .profile-img-container i { font-size: 42px; }

        .section-title { font-size: 14px; margin-bottom: 16px; }
        .form-control-premium { padding: 11px 14px; font-size: 13px; }
        .btn-premium { padding: 11px 18px; font-size: 13px; width: 100%; }

        .table-riwayat { font-size: 12px; }
    }
</style>

<!-- Hidden Upload Form -->
<form action="{{ route('pengaturan-akun.update-foto') }}" method="POST" enctype="multipart/form-data" id="formUploadFoto" class="d-none">
    @csrf
    <input type="file" name="foto" id="fotoInput" accept="image/*" onchange="document.getElementById('formUploadFoto').submit();">
</form>

<div class="container-fluid">
    <!-- Header -->
    <div class="account-header d-flex justify-content-between align-items-center mb-4">
        <div style="z-index: 1;">
            <h4 class="fw-bold mb-1">Profile Saya</h4>
            <p class="mb-0 text-white-50" style="font-size: 14px;">
                @if(Auth::user()->role == 'siswa')
                    Informasi personal, riwayat kelas, dan keamanan akun Anda
                @else
                    Kelola informasi profil dan pengaturan keamanan akun Anda
                @endif
            </p>
        </div>
        <div class="d-none d-md-block" style="z-index: 1;">
            <i class="fa-solid fa-user-gear" style="font-size: 40px; color: rgba(255,255,255,0.2);"></i>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #dcfce7; color: #15803d;" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #fef2f2; color: #b91c1c;" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(Auth::user()->role == 'siswa')
    {{-- LAYOUT KHUSUS SISWA --}}
    <div class="row g-4 align-items-start">
        <!-- Profile Picture & Active Status Sidebar (Sticky) -->
        <div class="col-12 col-lg-4 sticky-profile-col">
            <div class="premium-card p-4 text-center">
                <div class="section-title text-start mb-4">Foto Profil</div>
                
                <div class="profile-img-container mb-3" onclick="document.getElementById('fotoInput').click();" style="cursor: pointer;" title="Klik untuk mengubah foto">
                    @if(isset($siswa) && !empty($siswa->foto) && \Illuminate\Support\Facades\Storage::disk('public')->exists($siswa->foto))
                        <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto Profil">
                    @else
                        <i class="fa-solid fa-user"></i>
                    @endif
                    <div class="btn-edit-photo">
                        <i class="fa-solid fa-camera me-1"></i> Ubah Foto
                    </div>
                </div>

                <h5 class="fw-bold text-dark mb-1">{{ $siswa->nama ?? Auth::user()->nama ?? Auth::user()->name }}</h5>
                <p class="text-muted mb-2" style="font-size: 13px;">NIS: <strong>{{ $siswa->nis ?? '-' }}</strong></p>

                @if(isset($siswa) && $siswa->kelasAktif)
                    <span class="badge badge-kelas-aktif px-3 py-2 rounded-pill fw-semibold" style="font-size: 12px;">
                        <i class="fa-solid fa-graduation-cap me-1"></i> Kelas {{ optional($siswa->kelasAktif->kelas)->nama_kelas }}
                    </span>
                @else
                    <span class="badge badge-kelas-kosong px-3 py-2 rounded-pill fw-semibold" style="font-size: 12px;">
                        Belum Ada Kelas Aktif
                    </span>
                @endif

                <div class="d-grid gap-2 mt-4">
                    <button type="button" class="btn btn-light" onclick="document.getElementById('fotoInput').click();" style="border-radius: 10px; font-size: 13px; font-weight: 600; color: #475569; border: 1px solid #e2e8f0;">
                        <i class="fa-solid fa-upload me-2"></i> Unggah Foto Baru
                    </button>
                    @if(isset($siswa) && !empty($siswa->foto))
                        <form action="{{ route('pengaturan-akun.destroy-foto') }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger text-decoration-none" style="font-size: 13px; font-weight: 500;">
                                <i class="fa-solid fa-trash me-1"></i> Hapus Foto
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Details & Class History -->
        <div class="col-12 col-lg-8">
            <div class="premium-card p-4 p-md-5">
                
                <!-- 1. Informasi Personal (Read Only) -->
                <div class="section-title mb-4">
                    <i class="fa-solid fa-id-card section-icon-teal"></i> Informasi Personal Siswa
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <label for="namaLengkap" class="form-label-premium">Nama Lengkap</label>
                        <input type="text" class="form-control form-control-premium" id="namaLengkap" value="{{ $siswa->nama ?? Auth::user()->nama ?? Auth::user()->name ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="nis" class="form-label-premium">NIS (Nomor Induk Siswa)</label>
                        <input type="text" class="form-control form-control-premium" id="nis" value="{{ $siswa->nis ?? '-' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="nisn" class="form-label-premium">NISN (Nomor Induk Siswa Nasional)</label>
                        <input type="text" class="form-control form-control-premium" id="nisn" value="{{ !empty($siswa->nisn) ? $siswa->nisn : 'Belum diisi' }}" readonly>
                    </div>
                </div>

                <!-- 2. Riwayat Kelas & Akademik -->
                <div class="section-title mt-5 mb-4">
                    <i class="fa-solid fa-clock-rotate-left section-icon-violet"></i> Riwayat Kelas & Akademik
                </div>

                @if(isset($riwayatKelas) && $riwayatKelas->count() > 0)
                    <div class="table-responsive mb-4" style="border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
                        <table class="table align-middle mb-0">
                            <thead style="background-color: #f8fafc;">
                                <tr style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <th class="py-3 px-3">Nama Kelas</th>
                                    <th class="py-3 px-3">Tingkat</th>
                                    <th class="py-3 px-3">Tahun Ajaran</th>
                                    <th class="py-3 px-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatKelas as $sk)
                                    @php
                                        $isAktif = optional($sk->tahunAjaran)->is_aktif;
                                    @endphp
                                    <tr style="border-bottom: 1px solid #f1f5f9; font-size: 14px;">
                                        <td class="py-3 px-3 fw-bold text-dark">
                                            <i class="fa-solid fa-door-open text-primary me-2"></i>
                                            {{ optional($sk->kelas)->nama_kelas ?? '-' }}
                                        </td>
                                        <td class="py-3 px-3 text-muted">
                                            {{ optional(optional($sk->kelas)->tingkat)->nama_tingkat ?? '-' }}
                                        </td>
                                        <td class="py-3 px-3 text-dark">
                                            {{ optional($sk->tahunAjaran)->nama_tahun ?? '-' }} 
                                            @if(optional($sk->tahunAjaran)->semester)
                                                <small class="text-muted">(Semester {{ $sk->tahunAjaran->semester }})</small>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            @if($isAktif)
                                                <span class="badge badge-status-aktif px-3 py-1 rounded-pill" style="font-size: 11px;">
                                                    <i class="fa-solid fa-circle-check me-1"></i> Kelas Aktif
                                                </span>
                                            @else
                                                <span class="badge badge-status-selesai px-3 py-1 rounded-pill" style="font-size: 11px;">
                                                    Selesai
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center rounded-3 mb-4" style="background-color: #f8fafc; border: 1px dashed #cbd5e1;">
                        <i class="fa-regular fa-folder-open mb-2 text-muted" style="font-size: 28px;"></i>
                        <p class="text-muted small mb-0">Belum ada riwayat pendaftaran kelas untuk akun siswa ini.</p>
                    </div>
                @endif

                <!-- 3. Keamanan & Password -->
                <div class="section-title mt-5 mb-4">
                    <i class="fa-solid fa-shield-halved section-icon-amber"></i> Keamanan & Password
                </div>

                <form action="{{ route('pengaturan-akun.update-password') }}" method="POST">
                    @csrf
                    <div class="alert alert-info-premium border-0 d-flex align-items-center mb-4" style="font-size: 13px;">
                        <i class="fa-solid fa-circle-info me-3 fs-5"></i>
                        <div>Biarkan kosong jika Anda tidak ingin mengubah password akun saat ini.</div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password_siswa" class="form-label-premium">Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control form-control-premium" id="password_siswa" placeholder="Minimal 6 karakter" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <button class="btn btn-outline-secondary toggle-password-btn" type="button" data-target="password_siswa" style="border-color: #e2e8f0; background: #f8fafc; color: #64748b; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation_siswa" class="form-label-premium">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" class="form-control form-control-premium" id="password_confirmation_siswa" placeholder="Ulangi password baru" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <button class="btn btn-outline-secondary toggle-password-btn" type="button" data-target="password_confirmation_siswa" style="border-color: #e2e8f0; background: #f8fafc; color: #64748b; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-premium px-4">
                            <i class="fa-solid fa-key me-2"></i> Perbarui Password
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @else
    {{-- LAYOUT GURU / ADMIN / SUPER ADMIN --}}
    <div class="row g-4 align-items-start">
        <!-- Profile Picture Sidebar (Sticky) -->
        <div class="col-12 col-lg-4 sticky-profile-col">
            <div class="premium-card p-4 text-center">
                <div class="section-title text-start mb-4">Foto Profil</div>
                
                <div class="profile-img-container mb-4" onclick="document.getElementById('fotoInput').click();" style="cursor: pointer;" title="Klik untuk mengubah foto">
                    @if(Auth::user()->guru && !empty(Auth::user()->guru->foto) && \Illuminate\Support\Facades\Storage::disk('public')->exists(Auth::user()->guru->foto))
                        <img src="{{ asset('storage/' . Auth::user()->guru->foto) }}" alt="Foto Profil">
                    @else
                        <i class="fa-solid fa-user"></i>
                    @endif
                    <div class="btn-edit-photo">
                        <i class="fa-solid fa-camera me-1"></i> Ubah Foto
                    </div>
                </div>

                <h5 class="fw-bold text-dark mb-1">{{ Auth::user()->nama ?? Auth::user()->name ?? 'Nama Pengguna' }}</h5>
                <p class="text-muted mb-3" style="font-size: 14px;">{{ ucfirst(Auth::user()->role ?? 'Pengguna') }}</p>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-light" onclick="document.getElementById('fotoInput').click();" style="border-radius: 10px; font-size: 14px; font-weight: 600; color: #475569; border: 1px solid #e2e8f0;">
                        <i class="fa-solid fa-upload me-2"></i> Unggah Foto Baru
                    </button>
                    @if(Auth::user()->guru && !empty(Auth::user()->guru->foto))
                        <form action="{{ route('pengaturan-akun.destroy-foto') }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger text-decoration-none" style="font-size: 13px; font-weight: 500;">
                                <i class="fa-solid fa-trash me-1"></i> Hapus Foto
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Details -->
        <div class="col-12 col-lg-8">
            <div class="premium-card p-4 p-md-5">
                <div class="section-title mb-4">Informasi Personal</div>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="namaLengkap" class="form-label-premium">Nama Lengkap</label>
                        <input type="text" class="form-control form-control-premium" id="namaLengkap" placeholder="Masukkan nama lengkap" value="{{ Auth::user()->nama ?? Auth::user()->name ?? '' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="nip" class="form-label-premium">NIP / NUPTK</label>
                        <input type="text" class="form-control form-control-premium" id="nip" placeholder="Masukkan NIP atau NUPTK" value="{{ Auth::user()->guru->nip ?? '' }}" readonly>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label-premium">Alamat Email</label>
                    <input type="email" class="form-control form-control-premium" id="email" placeholder="contoh@sekolah.com" value="{{ Auth::user()->email ?? '' }}" readonly>
                </div>

                <div class="section-title mt-5 mb-4">Keamanan & Password</div>

                <div class="alert alert-info border-0 d-flex align-items-center mb-4" style="background-color: #f0f9ff; color: #0369a1; border-radius: 10px; font-size: 13px;">
                    <i class="fa-solid fa-circle-info me-3 fs-5"></i>
                    <div>Biarkan kosong jika Anda tidak ingin mengubah password saat ini.</div>
                </div>

                <form action="{{ route('pengaturan-akun.update-password') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password_other" class="form-label-premium">Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control form-control-premium" id="password_other" placeholder="Minimal 6 karakter" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <button class="btn btn-outline-secondary toggle-password-btn" type="button" data-target="password_other" style="border-color: #e2e8f0; background: #f8fafc; color: #64748b; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation_other" class="form-label-premium">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" class="form-control form-control-premium" id="password_confirmation_other" placeholder="Ulangi password baru" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <button class="btn btn-outline-secondary toggle-password-btn" type="button" data-target="password_confirmation_other" style="border-color: #e2e8f0; background: #f8fafc; color: #64748b; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                        <button type="submit" class="btn btn-premium px-5">
                            <i class="fa-solid fa-save me-2"></i> Perbarui Password
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.toggle-password-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        });
    });
});
</script>
@endsection
