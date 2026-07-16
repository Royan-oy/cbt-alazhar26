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

    .btn-back {
        background: rgba(255,255,255,0.1);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 14px;
        padding: 10px 18px;
        font-weight: 600;
    }

    .btn-back:hover { background: rgba(255,255,255,0.2); color: #fff; }

    .content-card {
        background: var(--surface-white);
        border-radius: 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.03);
        padding: 24px;
    }

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
    }

    .btn-copy-token:hover { background: rgba(255,255,255,0.2); color: #fff; }

    .btn-toggle-token {
        border-radius: 14px;
        padding: 14px 20px;
        font-weight: 700;
        border: none;
        width: 100%;
    }

    .countdown-box {
        background: #f8fafc;
        border-radius: 16px;
        padding: 16px;
        text-align: center;
        margin-top: 16px;
    }

    .countdown-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--secondary-dark);
        font-family: 'Courier New', monospace;
    }

    .info-item {
        padding: 14px 0;
        border-bottom: 1px dashed var(--border-color);
    }

    .info-item:last-child { border-bottom: none; }

    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--secondary-dark);
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
    }

    @media (max-width: 768px) {
        .page-header { padding: 24px; border-radius: 18px; text-align: center; }
        .page-header .d-flex { flex-direction: column; gap: 16px; }
        .content-card { padding: 16px; }
        .token-value { font-size: 30px; letter-spacing: 6px; }
    }
</style>

<div class="container-fluid py-2">

    {{-- Header --}}
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
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
                <a href="{{ route('ujian.edit', $ujian->id) }}" class="btn-back d-inline-flex align-items-center">
                    <i class="fa-solid fa-pen me-2"></i>
                    Edit
                </a>
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

            <div class="token-display mb-3">
                <div class="mb-2">
                    @if($ujian->token_aktif)
                        <span class="token-status-on"><i class="fa-solid fa-circle-play"></i> Token Aktif</span>
                    @else
                        <span class="token-status-off"><i class="fa-solid fa-lock"></i> Token Nonaktif</span>
                    @endif
                </div>

                <div class="token-value" id="tokenValue">{{ $ujian->token ?? '------' }}</div>

                <button type="button" class="btn-copy-token" id="btnCopyToken" data-token="{{ $ujian->token }}">
                    <i class="fa-solid fa-copy me-1"></i> Salin Token
                </button>

                <div class="countdown-box">
                    <div class="info-label mb-1">
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
                <form action="{{ route('ujian.toggle-token', $ujian->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn {{ $ujian->token_aktif ? 'btn-warning' : 'btn-success' }} text-white btn-toggle-token">
                        <i class="fa-solid {{ $ujian->token_aktif ? 'fa-lock' : 'fa-unlock' }} me-2"></i>
                        {{ $ujian->token_aktif ? 'Nonaktifkan Token' : 'Aktifkan Token' }}
                    </button>
                </form>

                @if(!$ujian->token_aktif)
                <form action="{{ route('ujian.regenerate-token', $ujian->id) }}" method="POST" class="mt-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-light border btn-toggle-token" onclick="return confirm('Buat token baru? Token lama tidak akan berlaku lagi.')">
                        <i class="fa-solid fa-rotate me-2"></i>
                        Buat Token Baru
                    </button>
                </form>
                @endif
            </div>

            <div class="content-card">
                <div class="info-item">
                    <div class="info-label">Waktu Mulai</div>
                    <div class="info-value">{{ $ujian->waktu_mulai->translatedFormat('l, d F Y - H:i') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Waktu Selesai</div>
                    <div class="info-value">{{ $ujian->waktu_selesai->translatedFormat('l, d F Y - H:i') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Durasi Minimal Pengerjaan</div>
                    <div class="info-value">{{ $ujian->durasi_minimal }} menit</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tahun Ajaran</div>
                    <div class="info-value">{{ optional($ujian->tahunAjaran)->nama_tahun ?? '-' }}</div>
                </div>
            </div>

        </div>

        {{-- Kolom Kanan --}}
        <div class="col-lg-8">

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
                    <div class="col-md-6">
                        <div class="setting-badge">
                            <i class="fa-solid {{ $ujian->tampilkan_nilai ? 'fa-circle-check text-success' : 'fa-circle-xmark text-muted' }}"></i>
                            Tampilkan Nilai Langsung: {{ $ujian->tampilkan_nilai ? 'Ya' : 'Tidak' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="setting-badge">
                            <i class="fa-solid {{ $ujian->tampilkan_pembahasan ? 'fa-circle-check text-success' : 'fa-circle-xmark text-muted' }}"></i>
                            Tampilkan Pembahasan: {{ $ujian->tampilkan_pembahasan ? 'Ya' : 'Tidak' }}
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
                    <div class="info-label">Nama Bank Soal</div>
                    <div class="info-value">{{ optional($ujian->bankSoal)->nama_bank_soal ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Mata Pelajaran</div>
                    <div class="info-value">{{ optional(optional($ujian->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}</div>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
document.getElementById('btnCopyToken').addEventListener('click', function () {
    const token = this.getAttribute('data-token');

    if (!token) return;

    navigator.clipboard.writeText(token).then(() => {
        const original = this.innerHTML;
        this.innerHTML = '<i class="fa-solid fa-check me-1"></i> Tersalin!';
        setTimeout(() => {
            this.innerHTML = original;
        }, 1500);
    });
});

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