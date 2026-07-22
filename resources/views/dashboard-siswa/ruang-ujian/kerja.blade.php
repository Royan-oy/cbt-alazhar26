@extends('layouts.ujian')

@section('title', 'Lembar Kerja Ujian')

@section('content')
<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --accent-blue-dark: #0284c7;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --warning-bg: #fffbeb;
        --warning-border: #fde68a;
        --warning-text: #b45309;
        --card-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.06), 0 8px 16px -6px rgba(15, 23, 42, 0.04);
        --card-shadow-lg: 0 20px 40px -10px rgba(15, 23, 42, 0.10);
        --radius-lg: 20px;
        --radius-md: 14px;
        --radius-sm: 10px;
    }

    body {
        background: #f1f5f9;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    img {
        pointer-events: none;
        -webkit-touch-callout: none;
    }

    .soal-card {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    textarea, input[type="text"] {
        -webkit-user-select: text;
        -ms-user-select: text;
        user-select: text;
    }

    .exam-wrapper {
        max-width: 1320px;
        margin: 0 auto;
        padding: 20px 24px 40px;
    }

    /* =========================================================
       BANNER PERINGATAN FULLSCREEN (selalu tampil di paling atas)
       ========================================================= */
    .fullscreen-warning-banner {
        display: flex;
        align-items: center;
        gap: 12px;
        background: linear-gradient(135deg, var(--warning-bg), #fff7ed);
        border: 1px solid var(--warning-border);
        color: var(--warning-text);
        padding: 12px 18px;
        border-radius: var(--radius-md);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 18px;
        box-shadow: 0 4px 12px -4px rgba(180, 83, 9, 0.15);
    }

    .fullscreen-warning-banner .icon-wrap {
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 10px;
        background: rgba(180, 83, 9, 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        color: #d97706;
    }

    .fullscreen-warning-banner .banner-sub {
        display: block;
        font-weight: 500;
        font-size: 11.5px;
        color: #a16207;
        margin-top: 1px;
    }

    /* =========================================================
       TOP BAR INFO (nama ujian + timer)
       ========================================================= */
    .exam-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        background: var(--surface-white);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 18px 24px;
        margin-bottom: 22px;
        box-shadow: var(--card-shadow);
    }

    .exam-topbar .exam-label {
        font-size: 10.5px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.6px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 3px;
    }

    .exam-topbar .exam-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary-dark);
        margin: 0;
        line-height: 1.3;
    }

    .exam-timer-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: var(--radius-md);
        padding: 10px 18px;
        min-width: 150px;
        justify-content: center;
    }

    .exam-timer-box i { color: #dc2626; font-size: 16px; }

    .exam-timer-box .timer-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .exam-timer-box .timer-label {
        font-size: 9.5px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #b91c1c;
        opacity: 0.8;
    }

    #countdownTimer {
        font-size: 19px;
        font-weight: 800;
        color: #dc2626;
        font-variant-numeric: tabular-nums;
        margin: 0;
    }

    /* =========================================================
       LAYOUT UTAMA (grid 2 kolom -> jadi 1 kolom di HP/tablet kecil)
       ========================================================= */
    .exam-main-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 24px;
        align-items: start;
    }

    /* =========================================================
       KARTU SOAL
       ========================================================= */
    .exam-nav-card {
        background: var(--surface-white);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 22px;
        position: sticky;
        top: 20px;
        box-shadow: var(--card-shadow);
    }

    .soal-card {
        background: var(--surface-white);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 30px 32px;
        margin-bottom: 20px;
        box-shadow: var(--card-shadow);
        display: none;
    }

    .soal-card.active {
        display: block;
        animation: fadeIn 0.35s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .soal-meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .soal-index-badge {
        background: var(--primary-dark);
        color: #fff;
        padding: 7px 16px;
        border-radius: 10px;
        text-transform: uppercase;
        font-weight: 700;
        font-size: 11px;
        letter-spacing: 0.4px;
    }

    .soal-bobot {
        color: var(--text-muted);
        font-weight: 600;
        font-size: 12.5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .soal-teks {
        font-size: 16px;
        line-height: 1.85;
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 22px;
        word-break: break-word;
    }

    .soal-gambar-wrap img {
        max-height: 340px;
        width: auto;
        max-width: 100%;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
    }

    /* =========================================================
       OPSI JAWABAN PILIHAN GANDA
       ========================================================= */
    .option-wrapper { position: relative; margin-bottom: 12px; }

    .option-input { position: absolute; opacity: 0; width: 0; height: 0; }

    .btn-option {
        display: flex;
        align-items: center;
        gap: 16px;
        width: 100%;
        text-align: left;
        background: #f8fafc;
        border: 1.5px solid var(--border-color);
        padding: 15px 18px;
        border-radius: var(--radius-md);
        transition: all 0.18s ease;
        cursor: pointer;
        user-select: none;
    }

    .btn-option:hover {
        border-color: #bae6fd;
        background: #f0f9ff;
    }

    .option-badge {
        width: 34px;
        height: 34px;
        min-width: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid var(--border-color);
        border-radius: 9px;
        font-weight: 700;
        font-size: 13.5px;
        background: #ffffff;
        color: var(--secondary-dark);
        transition: all 0.18s ease;
    }

    .option-input:checked + .btn-option {
        border-color: var(--accent-blue);
        background-color: #eff8ff;
        box-shadow: 0 0 0 1.5px var(--accent-blue), 0 4px 14px -4px rgba(14,165,233,0.25);
    }

    .option-input:checked + .btn-option .option-badge {
        background: var(--accent-blue);
        color: #fff;
        border-color: var(--accent-blue);
    }

    .option-text { font-size: 14.5px; color: #334155; font-weight: 500; }

    /* Essay */
    .essay-label {
        font-weight: 700;
        color: var(--text-muted);
        font-size: 12.5px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 8px;
        display: block;
    }

    textarea.form-control {
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-md);
        font-size: 14.5px;
        padding: 14px 16px;
        transition: all 0.18s ease;
    }

    textarea.form-control:focus {
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.12);
    }

    /* =========================================================
       NAVIGASI BAWAH
       ========================================================= */
    .exam-bottom-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        background: var(--surface-white);
        padding: 14px;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        margin-bottom: 24px;
        box-shadow: var(--card-shadow);
        flex-wrap: wrap;
    }

    .exam-bottom-nav .btn {
        border-radius: var(--radius-sm);
        font-weight: 600;
        padding: 10px 20px;
        font-size: 13.5px;
        border: none;
        flex: 1 1 auto;
        min-width: 120px;
    }

    #btnPrev { background: #f1f5f9; color: #475569; }
    #btnPrev:disabled { opacity: 0.45; }
    #btnRagu { background: #f59e0b; color: #fff; }
    #btnNext { background: var(--accent-blue); color: #fff; }

    /* =========================================================
       KARTU NAVIGASI NOMOR SOAL (sidebar kanan)
       ========================================================= */
    .exam-nav-card h6 {
        font-weight: 800;
        color: var(--primary-dark);
        font-size: 14px;
        margin-bottom: 16px;
    }

    .number-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
        gap: 8px;
    }

    .number-box {
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid var(--border-color);
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        color: var(--secondary-dark);
        cursor: pointer;
        background: #fff;
        transition: all 0.18s ease;
    }

    .number-box:hover {
        border-color: var(--accent-blue);
        color: var(--accent-blue);
        background: #f0f9ff;
    }

    .number-box.active {
        background: var(--primary-dark);
        color: #fff !important;
        border-color: var(--primary-dark);
        transform: scale(1.05);
    }

    .number-box.answered {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }

    .number-box.ragu {
        background: #fef3c7 !important;
        color: #d97706 !important;
        border-color: #fde68a !important;
    }

    .number-box.ragu.active {
        background: #f59e0b !important;
        color: #fff !important;
        border-color: #f59e0b !important;
    }

    .nav-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 14px;
        font-size: 11px;
        color: var(--text-muted);
    }

    .nav-legend span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .nav-legend i.dot {
        width: 9px; height: 9px; border-radius: 3px; display: inline-block;
    }

    .dot-answered { background: #38bdf8; }
    .dot-ragu { background: #f59e0b; }
    .dot-empty { background: #e2e8f0; }

    .btn-finish-exam {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border: none;
        color: #fff;
        font-weight: 700;
        border-radius: var(--radius-md);
        padding: 14px;
        width: 100%;
        letter-spacing: 0.3px;
        box-shadow: 0 10px 22px -8px rgba(220, 38, 38, 0.45);
        transition: all 0.18s ease;
    }

    .btn-finish-exam:hover { filter: brightness(1.05); }

    /* =========================================================
       RESPONSIVE — TABLET (<= 991.98px)
       ========================================================= */
    @media (max-width: 991.98px) {
        .exam-wrapper { padding: 16px 16px 32px; }

        .exam-main-grid {
            grid-template-columns: 1fr;
        }

        .exam-nav-card {
            position: static;
            order: -1; /* navigasi soal tampil di atas soal di layar sempit */
        }

        .number-grid {
            grid-template-columns: repeat(auto-fill, minmax(38px, 1fr));
        }

        .soal-card { padding: 26px 24px; }
    }

    /* =========================================================
       RESPONSIVE — HP (<= 575.98px)
       ========================================================= */
    @media (max-width: 575.98px) {
        .exam-wrapper { padding: 12px 10px 28px; }

        .exam-topbar {
            flex-direction: column;
            align-items: stretch;
            padding: 14px 16px;
            gap: 12px;
        }

        .exam-topbar .exam-title { font-size: 15.5px; }

        .exam-timer-box { min-width: 0; }

        .fullscreen-warning-banner {
            font-size: 12px;
            padding: 10px 14px;
        }

        .fullscreen-warning-banner .banner-sub { font-size: 10.5px; }

        .soal-card { padding: 20px 16px; border-radius: 16px; }

        .soal-teks { font-size: 14.5px; line-height: 1.7; }

        .soal-meta-row { margin-bottom: 16px; }

        .soal-index-badge { font-size: 10px; padding: 6px 12px; }

        .btn-option { padding: 13px 14px; gap: 12px; }

        .option-badge { width: 30px; height: 30px; min-width: 30px; font-size: 12px; }

        .option-text { font-size: 13.5px; }

        .exam-bottom-nav {
            flex-direction: column;
            padding: 10px;
        }

        .exam-bottom-nav .btn {
            width: 100%;
            padding: 13px 16px;
        }

        .exam-nav-card { padding: 16px; }

        .number-grid { grid-template-columns: repeat(auto-fill, minmax(36px, 1fr)); gap: 6px; }

        .number-box { height: 38px; font-size: 12.5px; border-radius: 9px; }

        .btn-finish-exam { padding: 13px; font-size: 13.5px; }
    }

    .finish-exam-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(3px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
    }

    .finish-exam-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .finish-exam-modal {
        background: var(--surface-white);
        border-radius: var(--radius-lg);
        padding: 32px 28px;
        max-width: 380px;
        width: 90%;
        text-align: center;
        box-shadow: var(--card-shadow-lg);
        transform: scale(0.92);
        transition: transform 0.2s ease;
    }

    .finish-exam-overlay.show .finish-exam-modal {
        transform: scale(1);
    }

    .finish-exam-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 16px;
        border-radius: 50%;
        background: #fef2f2;
        color: #dc2626;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .finish-exam-modal h5 {
        font-weight: 800;
        color: var(--primary-dark);
        margin-bottom: 8px;
    }

    .finish-exam-modal p {
        font-size: 13.5px;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 22px;
    }

    .finish-exam-actions {
        display: flex;
        gap: 10px;
    }

    .finish-exam-actions button {
        flex: 1;
        padding: 12px;
        border-radius: var(--radius-sm);
        font-weight: 700;
        font-size: 13.5px;
        border: none;
        cursor: pointer;
    }

    .btn-batal-finish {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-yakin-finish {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
    }
</style>

<div class="exam-wrapper">

    {{-- BANNER PERINGATAN: JANGAN KELUAR FULLSCREEN --}}
    <div class="fullscreen-warning-banner">
        <span class="icon-wrap"><i class="fa-solid fa-triangle-exclamation"></i></span>
        <div>
            Tetap berada di mode layar penuh (fullscreen) selama ujian berlangsung.
            <span class="banner-sub">Menekan tombol Esc atau keluar dari fullscreen akan tercatat sebagai pelanggaran (maks. 2x sebelum ujian dikumpulkan otomatis).</span>
        </div>
    </div>

    {{-- TOP BAR INFO --}}
    <div class="exam-topbar">
        <div>
            <span class="exam-label">Sedang Mengerjakan</span>
            <h5 class="exam-title">{{ $ujian->nama_ujian }}</h5>
        </div>
        <div class="exam-timer-box">
            <i class="fa-regular fa-clock"></i>
            <div class="timer-text">
                <span class="timer-label">Sisa Waktu</span>
                <p id="countdownTimer">--:--:--</p>
            </div>
        </div>
    </div>

    {{-- CORE AREA --}}
    <div class="exam-main-grid">

        {{-- KOLOM SOAL --}}
        <div>
            <form
                id="formUjian"
                action="{{ route('dashboard-siswa.ujian.submit', $ujian->id) }}"
                method="POST">
                @csrf

                @foreach($soals as $index => $soal)
                    <div
                        class="soal-card {{ $index==$currentQuestion?'active':'' }}"
                        id="card-soal-{{ $index }}"
                        data-soal-index="{{ $index }}"
                        data-soal-id="{{ $soal->id }}">

                        <div class="soal-meta-row">
                            <span class="soal-index-badge">
                                Soal {{ $index + 1 }} dari {{ $soals->count() }}
                            </span>
                            <span class="soal-bobot">
                                <i class="fa-regular fa-star text-warning"></i> Bobot: {{ $soal->bobot }} Poin
                            </span>
                        </div>

                        {{-- Teks Soal --}}
                        <div class="soal-teks">
                            {!! $soal->teks_soal !!}
                        </div>

                        {{-- Gambar Soal (Jika Ada) --}}
                        @if($soal->gambar)
                            <div class="mb-4 soal-gambar-wrap">
                                <img src="{{ asset('storage/' . $soal->gambar) }}" alt="Gambar Soal">
                            </div>
                        @endif

                        {{-- Input Opsi Jawaban --}}
                        @if($soal->jenis_soal == 'pilihan_ganda')
                            <div class="options-container">
                                @php
                                    $jawabanSoal = $jawaban[$soal->id] ?? null;
                                @endphp
                                @foreach($soal->pilihanJawabans as $pilihan)
                                    <div class="option-wrapper">

                                    <input
                                        type="radio"
                                        name="jawaban[{{ $soal->id }}]"
                                        id="opt-{{ $pilihan->id }}"
                                        value="{{ $pilihan->id }}"
                                        class="option-input"

                                        {{ optional($jawabanSoal)->pilihan_jawaban_id == $pilihan->id ? 'checked' : '' }}

                                        onchange="
                                            markAsAnswered({{ $index }});

                                            saveAnswer(
                                                {{ $ujian->id }},
                                                {{ $soal->id }},
                                                {{ $pilihan->id }},
                                                'pilihan_ganda'
                                            );
                                        "
                                    >

                                    <label
                                        for="opt-{{ $pilihan->id }}"
                                        class="btn-option">

                                        <span class="option-badge">
                                            {{ $pilihan->kode }}
                                        </span>

                                        <span class="option-text">
                                            {!! $pilihan->teks_pilihan !!}
                                        </span>

                                    </label>

                                </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Jika Essay / Isian --}}
                            <div class="form-group">
                                <label class="essay-label">Jawaban Anda</label>
                                @php
                                    $jawabanSoal = $jawaban[$soal->id] ?? null;
                                @endphp

                                <textarea
                                    name="jawaban[{{ $soal->id }}]"
                                    class="form-control"
                                    rows="5"
                                    placeholder="Ketik jawaban lengkap Anda di sini..."
                                    oninput="
                                        checkEssayAnswer(this, {{ $index }});
                                        saveEssay(
                                            {{ $ujian->id }},
                                            {{ $soal->id }},
                                            this.value
                                        );
                                ">{{ old('jawaban.'.$soal->id, optional($jawabanSoal)->jawaban_text) }}</textarea>
                            </div>
                        @endif
                    </div>
                @endforeach
            </form>

            {{-- NAVIGASI BUTTONS --}}
            <div class="exam-bottom-nav">
                <button type="button" class="btn" id="btnPrev" onclick="navigateQuestion(-1)">
                    <i class="fa-solid fa-arrow-left me-2"></i> Sebelumnya
                </button>

                <button type="button" class="btn" id="btnRagu" onclick="toggleRagu()">
                    <i class="fa-regular fa-square-minus me-2"></i> Ragu-Ragu
                </button>

                <button type="button" class="btn" id="btnNext" onclick="navigateQuestion(1)">
                    Selanjutnya <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>

        {{-- KOLOM NAVIGASI NOMOR SOAL --}}
        <div>
            <div class="exam-nav-card">
                <h6>
                    <i class="fa-solid fa-th me-2 text-info"></i> Navigasi Soal
                </h6>

                <div class="number-grid" id="navigationGrid">
                    @foreach($soals as $index => $soal)
                        @php
                            $jawabanSoal = $jawaban[$soal->id] ?? null;

                            $answered = false;
                            $ragu = false;

                            if($jawabanSoal){

                                if($jawabanSoal->pilihan_jawaban_id){
                                    $answered = true;
                                }

                                if(!empty($jawabanSoal->jawaban_text)){
                                    $answered = true;
                                }

                                if($jawabanSoal->is_ragu_ragu){
                                    $ragu = true;
                                }

                            }
                        @endphp

                        <div
                            class="number-box
                                {{ $index==$currentQuestion ? 'active' : '' }}
                                {{ $answered ? 'answered' : '' }}
                                {{ $ragu ? 'ragu' : '' }}"
                            id="nav-box-{{ $index }}"
                            onclick="jumpToQuestion({{ $index }})">

                            {{ $index+1 }}

                        </div>
                    @endforeach
                </div>

                <div class="nav-legend">
                    <span><i class="dot dot-answered"></i> Terjawab</span>
                    <span><i class="dot dot-ragu"></i> Ragu-ragu</span>
                    <span><i class="dot dot-empty"></i> Belum diisi</span>
                </div>

                <hr style="border-color: var(--border-color); margin: 18px 0;">

                <button type="button" class="btn-finish-exam" onclick="confirmFinish()">
                    <i class="fa-solid fa-cloud-arrow-up me-2"></i> Selesaikan Ujian
                </button>
            </div>
        </div>

    </div>
</div>

{{-- MODAL KONFIRMASI SELESAI UJIAN (custom, aman untuk fullscreen) --}}
<div class="finish-exam-overlay" id="finishExamOverlay">
    <div class="finish-exam-modal">
        <div class="finish-exam-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h5>Selesaikan Ujian?</h5>
        <p>Pastikan semua jawaban telah terisi dengan benar. Jawaban yang sudah dikumpulkan tidak dapat diubah kembali.</p>
        <div class="finish-exam-actions">
            <button type="button" class="btn-batal-finish" onclick="closeFinishModal()">
                Batal
            </button>
            <button type="button" class="btn-yakin-finish" onclick="submitFinalExam()">
                <i class="fa-solid fa-cloud-arrow-up me-1"></i> Ya, Selesaikan
            </button>
        </div>
    </div>
</div>

{{-- INTERACTIVE JAVASCRIPT --}}
<script>
    let isReloading = false;

    window.addEventListener("beforeunload", function (e) {
        isReloading = true;
        if (isFinishing) {
            // proses submit yang sah (klik "Selesaikan Ujian"), jangan tampilkan warning
            delete e.returnValue;
            return;
        }
    });

    let currentIdx = {{ $currentQuestion ?? 0 }};
    const totalQuestions = {{ $soals->count() }};
    const raguStates = [

    @foreach($soals as $index=>$soal)

    @if(isset($jawaban[$soal->id]) && $jawaban[$soal->id]->is_ragu_ragu)
    true,
    @else
    false,
    @endif

    @endforeach

    ];

    document.addEventListener("DOMContentLoaded", function() {
        updateNavigationButtons();
        startTimer();

        // Aktifkan fitur proteksi keamanan
        enableAntiCheat();
    });

    function enableAntiCheat() {
        // 1. Mencegah Klik Kanan & Long Press di HP (Anti-contexmenu)
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });

        // 2. Mencegah Shortcut Keyboard & Aksi Seleksi
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'u' || e.key === 's' || e.key === 'a')) {
                e.preventDefault();
                return false;
            }
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C'))) {
                e.preventDefault();
                return false;
            }
        });

        // 3. Deteksi Perpindahan Tab / Minimize Aplikasi (Page Visibility API)
        document.addEventListener("visibilitychange", function () {

           if (isReloading || isFinishing) return;

            if (document.hidden) {
                forceSubmitExam();
            }

        });
    }

    let sendingViolation = false;
    function forceSubmitExam()
    {
        if (sendingViolation) return;

        sendingViolation = true;

        fetch("{{ route('dashboard-siswa.ujian.violation') }}", {

            method: "POST",

            headers: {

                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"

            },

            body: JSON.stringify({

                ujian_id: {{ $ujian->id }}

            })

        })
        .then(response => response.json())
        .then(data => {

            sendingViolation = false;

            if (!data.success) return;

            if (data.submit) {

                alert(
                    "Anda telah melakukan pelanggaran sebanyak "
                    + data.count +
                    "/2.\n\nUjian akan dikumpulkan otomatis."
                );

                submitExamAutomatically();

            } else {

                alert(
                    "PERINGATAN!\n\n" +
                    "Anda telah keluar dari halaman ujian.\n\n" +
                    "Pelanggaran : "
                    + data.count +
                    "/2\n\n" +
                    "Jika mengulangi sekali lagi maka ujian akan langsung dikumpulkan."
                );

            }

        })
        .catch(() => {

            sendingViolation = false;

        });
    }

    // Pindah Soal (Selanjutnya/Sebelumnya)
    function navigateQuestion(direction) {
        let targetIdx = currentIdx + direction;
        if (targetIdx >= 0 && targetIdx < totalQuestions) {
            jumpToQuestion(targetIdx);
        }
    }

    function saveCurrentQuestion(index)
    {
        fetch("{{ route('dashboard-siswa.ujian.current-question') }}",{

            method:"POST",

            headers:{
                "Content-Type":"application/json",
                "X-CSRF-TOKEN":"{{ csrf_token() }}"
            },

            body:JSON.stringify({

                ujian_id:{{ $ujian->id }},

                current_question:index

            })

        });

    }

    // Lompat Langsung ke No Soal Tertentu
    function jumpToQuestion(index) {
        // Deaktifkan soal saat ini
        document.getElementById(`card-soal-${currentIdx}`).classList.remove('active');
        document.getElementById(`nav-box-${currentIdx}`).classList.remove('active');

        // Aktifkan soal tujuan
        currentIdx = index;
        saveCurrentQuestion(index);
        document.getElementById(`card-soal-${currentIdx}`).classList.add('active');
        document.getElementById(`nav-box-${currentIdx}`).classList.add('active');

        updateNavigationButtons();
    }

    // Perbarui Teks & Keaktifan Tombol Bawah
    function updateNavigationButtons() {
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        const btnRagu = document.getElementById('btnRagu');

        // Disable "Sebelumnya" jika di nomor 1
        btnPrev.disabled = (currentIdx === 0);

        // Jika nomor terakhir, ubah tombol "Selanjutnya" menjadi "Selesai"
        if (currentIdx === totalQuestions - 1) {
            btnNext.innerHTML = 'Selesai <i class="fa-solid fa-circle-check ms-2"></i>';
            btnNext.style.background = '#10b981';
        } else {
            btnNext.innerHTML = 'Selanjutnya <i class="fa-solid fa-arrow-right ms-2"></i>';
            btnNext.style.background = 'var(--accent-blue)';
        }

        // Sinkronisasi status visual Ragu-Ragu pada tombol
        if (raguStates[currentIdx]) {
            btnRagu.style.background = '#dc2626';
            btnRagu.innerHTML = '<i class="fa-solid fa-square-check me-2"></i> Batalkan Ragu';
        } else {
            btnRagu.style.background = '#f59e0b';
            btnRagu.innerHTML = '<i class="fa-regular fa-square-minus me-2"></i> Ragu-Ragu';
        }
    }

    // Aksi tombol Ragu-Ragu
    function toggleRagu() {

        raguStates[currentIdx] = !raguStates[currentIdx];


        const navBox = document.getElementById(`nav-box-${currentIdx}`);


        if (raguStates[currentIdx]) {

            navBox.classList.add('ragu');

        } else {

            navBox.classList.remove('ragu');

        }


        saveRaguStatus(currentIdx, raguStates[currentIdx]);


        updateNavigationButtons();

    }

    // Tandai nomor soal hijau/biru jika sudah dijawab (Pilihan Ganda)
    function markAsAnswered(index)
    {
        const nav = document.getElementById(`nav-box-${index}`);

        nav.classList.add("answered");

        nav.classList.remove("ragu");
    }

    // Tandai nomor soal jika sudah dijawab (Essay/Isian)
    function checkEssayAnswer(textarea, index) {
        const navBox = document.getElementById(`nav-box-${index}`);
        if (textarea.value.trim().length > 0) {
            navBox.classList.add('answered');
        } else {
            navBox.classList.remove('answered');
        }
    }

    // Hitung Mundur Sisa Waktu Ujian (Simulasi menggunakan waktu selesai)
    function startTimer() {
        const targetTime = new Date("{{ $ujian->waktu_selesai }}").getTime();

        const interval = setInterval(function() {
            const now = new Date().getTime();
            const distance = targetTime - now;

            if (distance < 0) {
                clearInterval(interval);
                document.getElementById("countdownTimer").innerHTML = "WAKTU HABIS";

                intentionalExit = true; // <-- tambahkan ini
                isReloading = true;

                document.getElementById("formUjian").submit();
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const format = (num) => String(num).padStart(2, '0');
            document.getElementById("countdownTimer").innerHTML = `${format(hours)}:${format(minutes)}:${format(seconds)}`;
        }, 1000);
    }

    function confirmFinish() {
            document.getElementById("finishExamOverlay").classList.add("show");
    }

    function closeFinishModal() {
        document.getElementById("finishExamOverlay").classList.remove("show");
    }

    function submitFinalExam() {
        // PENTING: set variabel milik layout, bukan variabel lokal
        intentionalExit = true;
        isReloading = true;

        document.getElementById("formUjian").submit();
    }

    // ===============================
    // AUTO SAVE PILIHAN GANDA
    // ===============================

    function saveAnswer(ujianId, soalId, pilihanJawabanId, jenisSoal) {
        fetch("{{ route('dashboard-siswa.ujian.autosave') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                ujian_id: ujianId,
                soal_id: soalId,
                pilihan_jawaban_id: pilihanJawabanId
            })
        })
        .then(r => r.json())
        .then(data => { if (!data.success) console.log(data.message); })
        .catch(err => console.error("AutoSave Error :", err));
    }

    function saveEssay(ujianId, soalId, jawaban) {
        fetch("{{ route('dashboard-siswa.ujian.autosave') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                ujian_id: ujianId,
                soal_id: soalId,
                jawaban_text: jawaban
            })
        });
    }

    function saveRaguStatus(index, status) {
        let soalId = document
            .getElementById(`card-soal-${index}`)
            .getAttribute('data-soal-id');

        fetch("{{ route('dashboard-siswa.ujian.autosave') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                ujian_id: {{ $ujian->id }},
                soal_id: soalId,
                is_ragu_ragu: status
            })
        });
    }

    // ==========================================================
    // SUBMIT OTOMATIS (dipicu saat pelanggaran ke-2 atau waktu habis)
    // ==========================================================
    async function submitExamAutomatically() {

        intentionalExit = true;
        isReloading = true;

        const soalCards = document.querySelectorAll(".soal-card");

        for (const card of soalCards) {

            const soalId = card.dataset.soalId;

            let payload = {
                ujian_id: {{ $ujian->id }},
                soal_id: soalId
            };

            // ==========================
            // PILIHAN GANDA
            // ==========================

            const checked = card.querySelector("input[type=radio]:checked");

            if (checked) {
                payload.pilihan_jawaban_id = checked.value;
            }

            // ==========================
            // ESSAY
            // ==========================

            const textarea = card.querySelector("textarea");

            if (textarea) {
                payload.jawaban_text = textarea.value;
            }

            try {

                await fetch("{{ route('dashboard-siswa.ujian.autosave') }}", {

                    method: "POST",

                    headers: {
                        "Content-Type":"application/json",
                        "Accept":"application/json",
                        "X-CSRF-TOKEN":"{{ csrf_token() }}"
                    },

                    body: JSON.stringify(payload)

                });

            } catch(e){

                console.log(e);

            }

        }

        const form = document.getElementById("formUjian");

        let flag = document.getElementById("autoSubmitFlag");

        if(!flag){

            flag=document.createElement("input");

            flag.type="hidden";
            flag.name="auto_submit";
            flag.value="1";

            form.appendChild(flag);

        }

        form.submit();

    }
</script>
@endsection