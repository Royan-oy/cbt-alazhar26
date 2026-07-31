@extends('layouts.app')

@section('title', 'Detail & Kontrol Ujian')

@section('content')

<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        border-radius: 24px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .page-header-content { position: relative; z-index: 1; }

    .btn-back {
        background: rgba(255,255,255,0.1);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 14px;
        padding: 10px 18px;
        font-weight: 600;
        white-space: nowrap;
    }

    .btn-back:hover { background: rgba(255,255,255,0.2); color: #fff; }

    .content-card {
        background: var(--surface-white);
        border-radius: 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.03);
        padding: 24px;
    }

    .info-sidebar { position: sticky; top: 20px; }

    .token-display {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        border-radius: 20px;
        padding: 32px 24px;
        text-align: center;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .token-value {
        font-family: 'Courier New', monospace;
        font-size: 42px;
        font-weight: 800;
        letter-spacing: 10px;
        margin: 12px 0;
        word-break: break-all;
    }

    .token-status-on {
        background: rgba(5, 150, 105, 0.15);
        color: #34d399;
        border: 1px solid rgba(52, 211, 153, 0.3);
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .token-status-off {
        background: rgba(217, 119, 6, 0.15);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.3);
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-copy-token {
        background: rgba(255,255,255,0.12);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 12px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 13px;
        transition: background-color .2s, color .2s;
    }

    .btn-copy-token:hover { background: rgba(255,255,255,0.2); color: #fff; }

    .btn-copy-token.is-copied {
        background: #059669;
        color: #fff;
        border-color: #059669;
    }

    .btn-copy-token:disabled {
        opacity: .4;
        cursor: not-allowed;
    }
    .btn-copy-token:disabled:hover { background: rgba(255,255,255,0.12); }

    .btn-toggle-token {
        border-radius: 14px;
        padding: 14px 20px;
        font-weight: 700;
        border: none;
        width: 100%;
    }

    .countdown-box {
        background: rgba(255,255,255,0.06);
        border-radius: 16px;
        padding: 16px;
        text-align: center;
        margin-top: 16px;
    }

    .countdown-value {
        font-size: 24px;
        font-weight: 800;
        color: #fff;
        font-family: 'Courier New', monospace;
    }

    .info-item {
        padding: 14px 0;
        border-bottom: 1px dashed var(--border-color);
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .info-item:last-child { border-bottom: none; }

    .info-item-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #f8fafc;
        color: var(--accent-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 14.5px;
        font-weight: 600;
        color: var(--secondary-dark);
        line-height: 1.4;
        word-break: break-word;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--secondary-dark);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kelas-badge {
        background: #f0f9ff;
        color: #0284c7;
        border: 1px solid rgba(2, 132, 199, 0.15);
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 10px;
        display: inline-block;
    }

    .setting-badge {
        background: #f8fafc;
        border-radius: 12px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
        height: 100%;
    }

    /* --- Guru Pengampu card --- */
    .guru-card {
        display: flex;
        align-items: center;
        gap: 16px;
        background: linear-gradient(135deg, #f0f9ff, #f8fafc);
        border: 1px solid rgba(14, 165, 233, 0.15);
        border-radius: 18px;
        padding: 18px;
    }

    .guru-avatar {
        width: 56px;
        height: 56px;
        min-width: 56px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--accent-blue), #0284c7);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 20px;
        letter-spacing: 0.5px;
        overflow: hidden;
    }

    .guru-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .guru-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--secondary-dark);
        margin-bottom: 2px;
    }

    .guru-meta {
        font-size: 12.5px;
        color: var(--text-muted);
        display: flex;
        flex-wrap: wrap;
        gap: 4px 10px;
    }

    .guru-meta span i { width: 14px; text-align: center; }

    .mapel-pill {
        background: #fff;
        border: 1px solid var(--border-color);
        color: var(--secondary-dark);
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        margin-top: 8px;
        display: inline-block;
    }

    /* ============================================
       RESPONSIVE: TABLET & MOBILE (<= 768px)
       ============================================ */
    @media (max-width: 768px) {
        .container-fluid.py-2 { padding-left: 12px; padding-right: 12px; }

        /* Header */
        .page-header { padding: 22px 18px; border-radius: 20px; }
        .page-header-content.d-flex.justify-content-between {
            flex-direction: column;
            align-items: stretch !important;
            gap: 16px;
        }
        .page-header h3 { font-size: 18px; margin-bottom: 4px; }
        .page-header p.small { font-size: 12.5px; }

        .page-header-content .d-flex.gap-2 {
            flex-direction: column;
            width: 100%;
        }
        .page-header-content .d-flex.gap-2 > * {
            width: 100%;
            justify-content: center;
            display: flex;
            align-items: center;
        }

        .row.g-4 { row-gap: 16px !important; }

        .content-card { padding: 16px; border-radius: 20px; }

        .info-sidebar { position: static; }

        .token-display { padding: 26px 18px; border-radius: 18px; }
        .token-value { font-size: 28px; letter-spacing: 5px; }

        .info-item { padding: 12px 0; gap: 10px; }
        .info-item-icon { width: 30px; height: 30px; font-size: 12px; border-radius: 9px; }
        .info-label { font-size: 10px; }
        .info-value { font-size: 13.5px; }

        .section-title { font-size: 14px; margin-bottom: 12px; }

        .guru-card { flex-direction: column; text-align: center; padding: 16px; }
        .guru-meta { justify-content: center; }

        .setting-badge { font-size: 12.5px; padding: 10px 12px; }
    }
</style>

<div class="container-fluid py-2">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div class="page-header-content d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge bg-info bg-opacity-25 text-info px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">
                    DETAIL & KONTROL UJIAN
                </span>
                <h3 class="fw-bold mb-1">{{ $ujian->nama_ujian }}</h3>
                <p class="text-light opacity-75 mb-0 small">
                    {{ optional($ujian->jenisUjian)->nama ?? '-' }}
                    &middot; {{ optional(optional($ujian->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}
                </p>
            </div>

            <div class="d-flex gap-2">
                @if(!$ujian->token_aktif)

                <a href="{{ route('ujian.edit',$ujian->id) }}"
                class="btn-back d-inline-flex align-items-center">

                    <i class="fa-solid fa-pen me-2"></i>
                    Edit

                </a>

                @else

                <button class="btn-back"
                        disabled>

                    <i class="fa-solid fa-lock me-2"></i>

                    Sedang Berlangsung

                </button>

                @endif
                <a href="{{ route('ujian.index') }}" class="btn-back d-inline-flex align-items-center">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success rounded-4 border-0 shadow-sm d-flex align-items-center p-3 mb-4">
        <i class="fa-solid fa-circle-check fs-5 me-2"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger rounded-4 border-0 shadow-sm d-flex align-items-center p-3 mb-4">
        <i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    <div class="row g-4">

        {{-- Kolom Kiri: Token & Kontrol --}}
        <div class="col-lg-4">
        <div class="info-sidebar">

            <div class="token-display mb-3">
                <div class="mb-2">
                    @if(now()->lt($ujian->waktu_mulai))

                        <span class="token-status-off">
                            <i class="fa-solid fa-clock"></i>
                            Menunggu Jadwal
                        </span>

                    @elseif($ujian->token_aktif)

                        <span class="token-status-on">
                            <i class="fa-solid fa-circle-play"></i>
                            Token Aktif
                        </span>

                    @else

                        <span class="token-status-off">
                            <i class="fa-solid fa-circle-stop"></i>
                            Ujian Berakhir
                        </span>

                    @endif
                </div>

                <div class="token-value" id="tokenValue">{{ $ujian->token ?? '------' }}</div>

                <button type="button" class="btn-copy-token" id="btnCopyToken" data-token="{{ $ujian->token }}" {{ $ujian->token ? '' : 'disabled' }}>
                    <i class="fa-solid fa-copy me-1"></i> Salin Token
                </button>

                <div class="countdown-box">
                    <div class="info-label mb-1" style="color: rgba(255,255,255,0.6);">
                        @if(now()->lt($ujian->waktu_mulai))
                            Ujian Dimulai Dalam
                        @elseif(now()->lte($ujian->waktu_selesai))
                            Sisa Waktu Jendela Ujian
                        @else
                            Ujian Telah Berakhir
                        @endif
                    </div>
                    <div class="countdown-value" id="countdownValue">--:--:--</div>
                </div>
            </div>

            <div class="content-card mb-3">

                <div class="section-title">
                    <i class="fa-solid fa-circle-info text-primary"></i>
                    Status Ujian
                </div>

                @if(now()->lt($ujian->waktu_mulai))

                    <div class="alert alert-warning border-0 rounded-4 mb-3">
                        <i class="fa-solid fa-clock me-2"></i>
                        Token akan otomatis aktif saat waktu ujian dimulai.
                    </div>

                @elseif(now()->between($ujian->waktu_mulai, $ujian->waktu_selesai))

                    <div class="alert alert-success border-0 rounded-4 mb-3">
                        <i class="fa-solid fa-circle-check me-2"></i>
                        Token sedang aktif dan dapat digunakan siswa.
                    </div>

                @else

                    <div class="alert alert-secondary border-0 rounded-4 mb-3">
                        <i class="fa-solid fa-circle-stop me-2"></i>
                        Ujian telah selesai. Token otomatis tidak berlaku.
                    </div>

                @endif

                <form action="{{ route('ujian.regenerate-token', $ujian->id) }}"
                    method="POST">

                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-light border btn-toggle-token"
                            onclick="return confirm('Yakin ingin membuat token baru? Token lama langsung tidak berlaku dan siswa harus menggunakan token yang baru.')">

                        <i class="fa-solid fa-rotate me-2"></i>

                        Buat Token Baru

                    </button>

                </form>

            </div>

            <div class="content-card">
                <div class="info-item">
                    <div class="info-item-icon"><i class="fa-solid fa-calendar-day"></i></div>
                    <div>
                        <div class="info-label">Waktu Mulai</div>
                        <div class="info-value">{{ $ujian->waktu_mulai->translatedFormat('l, d F Y - H:i') }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-item-icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <div>
                        <div class="info-label">Waktu Selesai</div>
                        <div class="info-value">{{ $ujian->waktu_selesai->translatedFormat('l, d F Y - H:i') }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-item-icon"><i class="fa-solid fa-hourglass-half"></i></div>
                    <div>
                        <div class="info-label">Durasi Minimal Pengerjaan</div>
                        <div class="info-value">{{ $ujian->durasi_minimal }} menit</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-item-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                    <div>
                        <div class="info-label">Tahun Ajaran</div>
                        <div class="info-value">{{ optional($ujian->tahunAjaran)->nama_tahun ?? '-' }}</div>
                    </div>
                </div>
            </div>

        </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="col-lg-8">

            @php
                $guruMapel = optional($ujian->bankSoal)->guruMapel;
                $guru = optional($guruMapel)->guru;

                if ($guru) {
                    $inisial = '';

                    foreach (explode(' ', $guru->nama) as $index => $kata) {
                        if ($index >= 2) {
                            break;
                        }

                        $inisial .= mb_substr($kata, 0, 1);
                    }

                    $inisial = strtoupper($inisial);
                } else {
                    $inisial = '?';
                }
            @endphp

            <div class="content-card mb-4">
                <div class="section-title">
                    <i class="fa-solid fa-chalkboard-user text-primary"></i>
                    Guru Pengampu
                </div>

                @if($guru)
                    <div class="guru-card">
                        <div class="guru-avatar">
                            @if(!empty($guru->foto))
                                <img src="{{ asset('storage/' . $guru->foto) }}" alt="{{ $guru->nama }}">
                            @else
                                {{ strtoupper($inisial) }}
                            @endif
                        </div>

                        <div>
                            <div class="guru-name">{{ $guru->nama }}</div>
                            <div class="guru-meta">
                                @if(!empty($guru->nip))
                                    <span><i class="fa-solid fa-id-card"></i> NIP {{ $guru->nip }}</span>
                                @endif
                                @if(!empty($guru->no_hp))
                                    <span><i class="fa-solid fa-phone"></i> {{ $guru->no_hp }}</span>
                                @endif
                            </div>
                            <span class="mapel-pill">
                                <i class="fa-solid fa-book me-1"></i>
                                {{ optional(optional($ujian->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="alert alert-secondary border-0 rounded-4 mb-0">
                        <i class="fa-solid fa-circle-info me-2"></i>
                        Data guru pengampu bank soal ini tidak ditemukan.
                    </div>
                @endif
            </div>

            <div class="content-card mb-4">
                <div class="section-title">
                    <i class="fa-solid fa-users text-primary"></i>
                    Kelas Peserta ({{ $ujian->kelas->count() }})
                </div>

                <div class="d-flex flex-wrap gap-2">
                    @forelse($ujian->kelas as $kelas)
                        <span class="kelas-badge">
                            {{ optional($kelas->tingkat)->nama_tingkat }} - {{ $kelas->nama_kelas }}
                        </span>
                    @empty
                        <span class="text-muted small">Belum ada kelas peserta.</span>
                    @endforelse
                </div>
            </div>

            <div class="content-card mb-4">
                <div class="section-title">
                    <i class="fa-solid fa-sliders text-primary"></i>
                    Pengaturan Ujian
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="setting-badge">
                            <i class="fa-solid {{ $ujian->acak_soal ? 'fa-circle-check text-success' : 'fa-circle-xmark text-muted' }}"></i>
                            Acak Urutan Soal: {{ $ujian->acak_soal ? 'Ya' : 'Tidak' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="setting-badge">
                            <i class="fa-solid {{ $ujian->acak_jawaban ? 'fa-circle-check text-success' : 'fa-circle-xmark text-muted' }}"></i>
                            Acak Urutan Jawaban: {{ $ujian->acak_jawaban ? 'Ya' : 'Tidak' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="section-title">
                    <i class="fa-solid fa-folder-open text-primary"></i>
                    Sumber Bank Soal
                </div>

                <div class="info-item">
                    <div class="info-item-icon"><i class="fa-solid fa-book-open"></i></div>
                    <div>
                        <div class="info-label">Nama Bank Soal</div>
                        <div class="info-value">{{ optional($ujian->bankSoal)->nama_bank_soal ?? '-' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-item-icon"><i class="fa-solid fa-book"></i></div>
                    <div>
                        <div class="info-label">Mata Pelajaran</div>
                        <div class="info-value">{{ optional(optional($ujian->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-item-icon"><i class="fa-solid fa-user-tie"></i></div>
                    <div>
                        <div class="info-label">Guru Pembuat</div>
                        <div class="info-value">{{ $guru->nama ?? '-' }}</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
(function () {
    const btn = document.getElementById('btnCopyToken');
    if (!btn) return;

    function showCopied() {
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check me-1"></i> Tersalin!';
        btn.classList.add('is-copied');
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.remove('is-copied');
        }, 1500);
    }

    function showFailed() {
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i> Gagal, salin manual';
        setTimeout(() => {
            btn.innerHTML = original;
        }, 2000);
    }

    // Fallback untuk browser lama / koneksi non-HTTPS yang tidak mendukung navigator.clipboard
    function fallbackCopy(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.setAttribute('readonly', '');
        textarea.style.position = 'fixed';
        textarea.style.top = '0';
        textarea.style.left = '0';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);

        textarea.focus();
        textarea.select();
        textarea.setSelectionRange(0, textarea.value.length);

        let success = false;
        try {
            success = document.execCommand('copy');
        } catch (err) {
            success = false;
        }

        document.body.removeChild(textarea);

        if (success) {
            showCopied();
        } else {
            showFailed();
        }
    }

    btn.addEventListener('click', function () {
        const token = btn.getAttribute('data-token');

        if (!token) return;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(token).then(showCopied).catch(function () {
                fallbackCopy(token);
            });
        } else {
            fallbackCopy(token);
        }
    });
})();

// Countdown timer
(function () {
    const waktuMulai = new Date('{{ $ujian->waktu_mulai->format('Y-m-d\TH:i:s') }}').getTime();
    const waktuSelesai = new Date('{{ $ujian->waktu_selesai->format('Y-m-d\TH:i:s') }}').getTime();
    const el = document.getElementById('countdownValue');

    function updateCountdown() {
        const now = new Date().getTime();
        let target;

        if (now < waktuMulai) {
            target = waktuMulai;
        } else if (now <= waktuSelesai) {
            target = waktuSelesai;
        } else {
            el.textContent = '00:00:00';
            return;
        }

        const diff = target - now;
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        el.textContent =
            String(hours).padStart(2, '0') + ':' +
            String(minutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0');
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
})();
</script>

@endsection