@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<style>
    /* --- CUSTOM PREMIUM ACCOUNT SETTINGS CSS --- */
    .account-header {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        border: 1px solid rgba(255, 255, 255, 0.05);
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        color: white;
        padding: 30px;
        margin-bottom: 30px;
    }

    .account-header::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.08) 0%, transparent 70%);
        top: -100px;
        right: -50px;
        border-radius: 50%;
    }

    .premium-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }
    
    .premium-card:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
    }

    .profile-img-container {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        margin: 0 auto;
        position: relative;
        border: 4px solid #f8fafc;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        overflow: hidden;
        background-color: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-img-container i {
        font-size: 60px;
        color: #94a3b8;
    }

    .btn-edit-photo {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(15, 23, 42, 0.7);
        color: white;
        text-align: center;
        padding: 8px 0;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .profile-img-container:hover .btn-edit-photo {
        opacity: 1;
    }

    .form-control-premium {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 12px 16px;
        font-size: 14px;
        color: #334155;
        background-color: #f8fafc;
        transition: all 0.3s ease;
    }

    .form-control-premium:focus {
        background-color: #ffffff;
        border-color: #38bdf8;
        box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
        outline: none;
    }

    .form-label-premium {
        font-weight: 600;
        color: #475569;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .btn-premium {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        border: none;
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.2);
    }

    .btn-premium:hover {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(2, 132, 199, 0.3);
        color: white;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="account-header d-flex justify-content-between align-items-center mb-4">
        <div style="z-index: 1;">
            <h4 class="fw-bold mb-1">Pengaturan Akun</h4>
            <p class="mb-0 text-white-50" style="font-size: 14px;">Kelola informasi profil dan pengaturan keamanan akun Anda</p>
        </div>
        <div class="d-none d-md-block" style="z-index: 1;">
            <i class="fa-solid fa-user-gear" style="font-size: 40px; color: rgba(255,255,255,0.2);"></i>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Picture Sidebar -->
        <div class="col-12 col-lg-4">
            <div class="premium-card p-4 text-center">
                <div class="section-title text-start mb-4">Foto Profil</div>
                
                <div class="profile-img-container mb-4">
                    <!-- Placeholder icon if no image -->
                    <i class="fa-solid fa-user"></i>
                    <!-- <img src="path_to_image" alt="Profile"> -->
                    <div class="btn-edit-photo">
                        <i class="fa-solid fa-camera me-1"></i> Ubah Foto
                    </div>
                </div>

                <h5 class="fw-bold text-dark mb-1">{{ Auth::user()->name ?? 'Nama Pengguna' }}</h5>
                <p class="text-muted mb-3" style="font-size: 14px;">{{ ucfirst(Auth::user()->role ?? 'Guru') }}</p>

                <div class="d-grid gap-2">
                    <button class="btn btn-light" style="border-radius: 10px; font-size: 14px; font-weight: 600; color: #475569; border: 1px solid #e2e8f0;">
                        <i class="fa-solid fa-upload me-2"></i> Unggah Baru
                    </button>
                    <button class="btn btn-link text-danger text-decoration-none" style="font-size: 13px; font-weight: 500;">
                        Hapus Foto
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Details -->
        <div class="col-12 col-lg-8">
            <div class="premium-card p-4 p-md-5">
                <div class="section-title mb-4">Informasi Personal</div>
                
                <form action="#" method="POST">
                    @csrf
                    <!-- We will add method PUT when backend is ready -->

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="namaLengkap" class="form-label-premium">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-premium" id="namaLengkap" placeholder="Masukkan nama lengkap" value="{{ Auth::user()->name ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label for="nip" class="form-label-premium">NIP / NUPTK</label>
                            <!-- Assuming NIP is in guru relationship -->
                            <input type="text" class="form-control form-control-premium" id="nip" placeholder="Masukkan NIP atau NUPTK" value="{{ Auth::user()->guru->nip ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label-premium">Alamat Email</label>
                        <input type="email" class="form-control form-control-premium" id="email" placeholder="contoh@sekolah.com" value="{{ Auth::user()->email ?? '' }}">
                    </div>

                    <div class="section-title mt-5 mb-4">Keamanan & Password</div>

                    <div class="alert alert-info border-0 d-flex align-items-center mb-4" style="background-color: #f0f9ff; color: #0369a1; border-radius: 10px; font-size: 13px;">
                        <i class="fa-solid fa-circle-info me-3 fs-5"></i>
                        <div>Biarkan kosong jika Anda tidak ingin mengubah password saat ini.</div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label-premium">Password Baru</label>
                            <input type="password" class="form-control form-control-premium" id="password" placeholder="Minimal 8 karakter">
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label-premium">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control form-control-premium" id="password_confirmation" placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                        <button type="submit" class="btn btn-premium px-5">
                            <i class="fa-solid fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
