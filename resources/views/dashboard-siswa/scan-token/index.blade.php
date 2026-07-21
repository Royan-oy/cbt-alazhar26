@extends('layouts.app')

@section('title', 'Scan Token Ujian')

@section('content')
<style>
    :root {
        --primary-dark: #0f172a;
        --accent-blue: #0ea5e9;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .token-container {
        min-height: 75vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .token-card {
        background: var(--surface-white);
        border-radius: 24px;
        box-shadow: 0 20px 40px -5px rgba(15, 23, 42, 0.08);
        border: 1px solid var(--border-color);
        max-width: 500px;
        width: 100%;
        overflow: hidden;
    }

    .token-header {
        background: linear-gradient(135deg, var(--primary-dark), #1e293b);
        padding: 40px 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .token-header::after {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        top: -100px;
        right: -50px;
        pointer-events: none;
    }

    .token-icon-wrap {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .token-icon-wrap i { font-size: 30px; color: #fff; }

    .token-body { padding: 40px 30px; }

    .token-input {
        font-size: 32px !important;
        font-weight: 800 !important;
        text-align: center;
        letter-spacing: 8px;
        text-transform: uppercase;
        border: 2px solid var(--border-color) !important;
        border-radius: 16px !important;
        padding: 20px !important;
        color: var(--primary-dark) !important;
        background: #f8fafc !important;
        transition: all 0.3s ease;
    }

    .token-input:focus {
        border-color: var(--accent-blue) !important;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15) !important;
        background: #fff !important;
    }

    .token-input::placeholder {
        color: #cbd5e1;
        font-weight: 600;
        letter-spacing: 2px;
        font-size: 18px;
    }

    .btn-submit-token {
        background: linear-gradient(135deg, var(--accent-blue), #0284c7);
        color: white;
        border: none;
        padding: 16px;
        border-radius: 16px;
        font-weight: 700;
        font-size: 16px;
        width: 100%;
        margin-top: 24px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(14, 165, 233, 0.25);
    }

    .btn-submit-token:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(14, 165, 233, 0.35);
        color: white;
    }

    /* === MODAL STYLES === */
    .confirm-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        backdrop-filter: blur(6px);
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeInOverlay 0.25s ease;
    }

    @keyframes fadeInOverlay {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    .confirm-modal-box {
        background: #fff;
        border-radius: 20px;
        max-width: 380px;
        width: 100%;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.2);
        overflow: hidden;
        animation: slideUpModal 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes slideUpModal {
        from { opacity: 0; transform: translateY(40px) scale(0.96); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-top-bar {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        padding: 16px 20px;
        text-align: center;
        position: relative;
    }

    .modal-check-icon {
        width: 40px;
        height: 40px;
        background: rgba(14, 165, 233, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        border: 2px solid rgba(14, 165, 233, 0.4);
    }

    .modal-body-content { padding: 16px 20px; }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-row:last-of-type { border-bottom: none; }

    .info-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
    }

    .info-label {
        font-size: 10px;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1px;
    }

    .info-value {
        font-size: 13px;
        font-weight: 700;
        color: var(--primary-dark);
    }

    .btn-yakin {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: white;
        border: none;
        height: 40px;
        padding: 7px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);
        white-space: nowrap;
    }

    .btn-yakin:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(14, 165, 233, 0.35);
    }

    .btn-batal {
        background: #f1f5f9;
        color: var(--text-muted);
        border: 1px solid var(--border-color);
        padding: 7px 16px;
        height: 40px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-batal:hover {
        background: #e2e8f0;
        color: var(--primary-dark);
    }

    .ujian-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: rgba(14, 165, 233, 0.1);
        color: #0284c7;
        border: 1px solid rgba(14, 165, 233, 0.2);
    }
</style>

<div class="container-fluid">
    <div class="token-container">
        <div class="token-card">

            <div class="token-header">
                <div class="token-icon-wrap">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <h3 class="text-white fw-bold mb-1">Masuk Ruang Ujian</h3>
                <p class="text-white-50 mb-0" style="font-size: 14px;">Masukkan token untuk membuka akses soal ujian Anda.</p>
            </div>

            <div class="token-body">

                @if(session('error'))
                    <div class="alert d-flex align-items-center rounded-3 mb-4 border-0 gap-3" style="background: #fef2f2; color: #991b1b;">
                        <i class="fa-solid fa-circle-exclamation fa-lg flex-shrink-0"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert d-flex align-items-center rounded-3 mb-4 border-0 gap-3" style="background: #f0fdf4; color: #166534;">
                        <i class="fa-solid fa-circle-check fa-lg flex-shrink-0"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <form action="{{ route('dashboard-siswa.scan-token.proses') }}" method="POST">
                    @csrf
                    <div class="form-group mb-0">
                        <input type="text" name="token" id="token"
                               class="form-control token-input @error('token') is-invalid @enderror"
                               placeholder="TOKEN" maxlength="6" autocomplete="off"
                               autofocus required
                               value="{{ old('token', $token ?? '') }}">
                        @error('token')
                            <div class="invalid-feedback text-center fw-bold mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit-token d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i> Cari Ujian
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL KONFIRMASI (muncul otomatis jika ada $ujian) --}}
{{-- ======================================================== --}}
@if(isset($ujian))
<div class="confirm-modal-overlay" id="confirmModal">
    <div class="confirm-modal-box">

        {{-- Header Modal --}}
        <div class="modal-top-bar">
            <div class="modal-check-icon">
                <i class="fa-solid fa-circle-check fa-lg" style="color: #38bdf8;"></i>
            </div>
            <h5 class="text-white fw-bold mb-1">Token Ditemukan!</h5>
            <p class="text-white-50 mb-0" style="font-size: 13px;">Pastikan ini adalah ujian yang ingin Anda kerjakan.</p>
        </div>

        {{-- Body Modal --}}
        <div class="modal-body-content">

            <div class="mb-3">
                <span class="ujian-type-badge">
                    <i class="fa-solid fa-tag"></i>
                    {{ optional($ujian->jenisUjian)->nama ?? 'Ujian' }}
                </span>
            </div>

            {{-- Nama Ujian --}}
            <div class="info-row">
                <div class="info-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-file-alt"></i>
                </div>
                <div>
                    <div class="info-label">Nama Ujian</div>
                    <div class="info-value">{{ $ujian->nama_ujian }}</div>
                </div>
            </div>

            {{-- Mata Pelajaran --}}
            <div class="info-row">
                <div class="info-icon bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div>
                    <div class="info-label">Mata Pelajaran</div>
                    <div class="info-value">
                        {{ optional(optional($ujian->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}
                    </div>
                </div>
            </div>

            {{-- Guru Pengajar --}}
            <div class="info-row">
                <div class="info-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <div>
                    <div class="info-label">Guru Pengajar</div>
                    <div class="info-value">
                        {{ optional(optional(optional($ujian->bankSoal)->guruMapel)->guru)->nama ?? '-' }}
                    </div>
                </div>
            </div>

            {{-- Waktu Ujian --}}
            <div class="info-row">
                <div class="info-icon bg-info bg-opacity-10 text-info">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div>
                    <div class="info-label">Waktu Pelaksanaan</div>
                    <div class="info-value" style="font-size: 13px;">
                        {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->translatedFormat('d M Y, H:i') }}
                        <span class="text-muted fw-normal">s/d</span>
                        {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->translatedFormat('H:i') }} WIB
                    </div>
                </div>
            </div>

            {{-- Durasi --}}
            <div class="info-row">
                <div class="info-icon" style="background:#f0fdf4; color:#16a34a;">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <div class="info-label">Durasi</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->diffInMinutes(\Carbon\Carbon::parse($ujian->waktu_selesai)) }} Menit
                    </div>
                </div>
            </div>

            {{-- Pertanyaan Konfirmasi --}}
            <div class="mt-2 p-2 rounded-3 text-center" style="background: #fffbeb; border: 1px solid #fde68a;">
                <i class="fa-solid fa-circle-question text-warning me-1"></i>
                <span class="fw-semibold" style="color: #92400e; font-size: 14px;">Apakah ini ujian yang kamu cari?</span>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-2 pt-2" style="border-top: 1px solid #f1f5f9;">
                <form class="d-flex gap-2 justify-content-center" action="{{ route('dashboard-siswa.scan-token.konfirmasi', $ujian->id) }}" method="POST">
                    <button type="button" class="btn-batal w-50" onclick="tutupModal()">
                        <i class="fa-solid fa-xmark me-1"></i> Batal
                    </button>
                    @csrf
                    <button type="submit" class="btn-yakin w-50">
                        <i class="fa-solid fa-right-to-bracket me-1"></i> Ya, Masuk Sekarang!
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endif

<script>
    // Konversi input token ke huruf kapital otomatis
    document.getElementById('token').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });

    // Tutup modal saat tombol Batal ditekan
    function tutupModal() {
        const modal = document.getElementById('confirmModal');
        if (modal) {
            modal.style.animation = 'fadeInOverlay 0.2s ease reverse';
            setTimeout(() => modal.remove(), 180);
        }
    }

    // Tutup modal jika klik di luar area box
    const overlay = document.getElementById('confirmModal');
    if (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) tutupModal();
        });
    }
</script>
@endsection