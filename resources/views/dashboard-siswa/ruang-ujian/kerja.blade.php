@extends('layouts.app')

@section('title', 'Lembar Kerja Ujian')

@section('content')
<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --card-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.04), 0 8px 16px -6px rgba(15, 23, 42, 0.04);
    }

    body {
        background-color: #f8fafc;
    }

    /* Mencegah seleksi teks (Anti-Block) di seluruh halaman kerja ujian */
    body {
        -webkit-user-select: none;  /* Safari */
        -ms-user-select: none;      /* IE 10 and Consumer Preview */
        user-select: none;          /* Standard syntax */
    }

    /* Proteksi tambahan untuk elemen visual dan sentuhan di HP */
    img {
        pointer-events: none;       /* Gambar tidak bisa ditekan lama/diunduh di browser HP */
        -webkit-touch-callout: none; /* Menghilangkan menu pop-up saat gambar ditahan di iOS/Android */
    }

    .soal-card {
        /* Mencegah fitur salin bawaan browser modern mobile */
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Khusus jika ada input/textarea yang tetap harus bisa diketik oleh siswa */
    textarea, input[type="text"] {
        -webkit-user-select: text;
        -ms-user-select: text;
        user-select: text;
    }

    /* Card Wrapper */
    .exam-nav-card {
        background: var(--surface-white);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 24px;
        position: sticky;
        top: 24px;
        box-shadow: var(--card-shadow);
    }

    .soal-card {
        background: var(--surface-white);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 35px;
        margin-bottom: 24px;
        box-shadow: var(--card-shadow);
        display: none; /* Disembunyikan secara default, diatur lewat JS */
    }

    .soal-card.active {
        display: block;
        animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Number Navigation */
    .number-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(42px, 1fr));
        gap: 8px;
    }

    .number-box {
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        font-weight: 600;
        color: var(--secondary-dark);
        text-decoration: none;
        transition: all 0.2s ease;
        background: var(--surface-white);
        cursor: pointer;
    }

    .number-box:hover {
        border-color: var(--accent-blue);
        color: var(--accent-blue);
        background: #f0f9ff;
    }

    .number-box.active {
        background: var(--primary-dark);
        color: var(--surface-white) !important;
        border-color: var(--primary-dark);
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
        color: var(--surface-white) !important;
        border-color: #f59e0b !important;
    }

    /* Interactive Options */
    .option-wrapper {
        position: relative;
        margin-bottom: 14px;
    }

    .option-input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .btn-option {
        display: flex;
        align-items: center;
        gap: 16px;
        width: 100%;
        text-align: left;
        background: var(--surface-white);
        border: 1px solid var(--border-color);
        padding: 16px 20px;
        border-radius: 14px;
        transition: all 0.2s ease;
        cursor: pointer;
        user-select: none;
    }

    .option-badge {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-weight: 700;
        background: #f8fafc;
        color: var(--secondary-dark);
        transition: all 0.2s ease;
    }

    /* Checked State styling */
    .option-input:checked + .btn-option {
        border-color: var(--accent-blue);
        background-color: #f0f9ff;
        box-shadow: 0 0 0 1px var(--accent-blue);
    }

    .option-input:checked + .btn-option .option-badge {
        background: var(--accent-blue);
        color: var(--surface-white);
        border-color: var(--accent-blue);
    }
</style>

<div class="container-fluid px-4 py-4">
    
    {{-- TOP BAR INFO --}}
    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-4 border shadow-sm">
        <div>
            <span class="text-muted d-block text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.5px;">Sedang Mengerjakan</span>
            <h5 class="fw-bold mb-0" style="color: var(--primary-dark);">{{ $ujian->nama_ujian }}</h5>
        </div>
        <div class="text-end">
            <span class="text-muted d-block text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.5px;">
                <i class="fa-regular fa-clock me-1 text-danger"></i> Sisa Waktu
            </span>
            <h5 class="fw-bold text-danger mb-0" id="countdownTimer">--:--:--</h5>
        </div>
    </div>

    {{-- CORE AREA --}}
    <div class="row">
        
        {{-- KOLOM SOAL --}}
        <div class="col-lg-8">
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
                        
                        <div class="d-flex justify-content-between mb-4 align-items-center">
                            <span class="badge bg-dark px-3 py-2 rounded-3 text-uppercase fw-bold" style="font-size: 11px;">
                                Soal {{ $index + 1 }} dari {{ $soals->count() }}
                            </span>
                            <span class="text-muted fw-medium" style="font-size: 12px;">
                                <i class="fa-regular fa-star me-1 text-warning"></i> Bobot: {{ $soal->bobot }} Poin
                            </span>
                        </div>

                        {{-- Teks Soal --}}
                        <div class="fs-5 mb-4 text-dark" style="line-height: 1.8; font-weight: 500;">
                            {!! $soal->teks_soal !!}
                        </div>

                        {{-- Gambar Soal (Jika Ada) --}}
                        @if($soal->gambar)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $soal->gambar) }}" class="img-fluid rounded-4 border" style="max-height: 350px;" alt="Gambar Soal">
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

                                        <span class="text-secondary fw-medium">
                                            {!! $pilihan->teks_pilihan !!}
                                        </span>

                                    </label>

                                </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Jika Essay / Isian --}}
                            <div class="form-group">
                                <label class="form-label fw-bold text-muted mb-2">Jawaban Anda:</label>
                                @php
                                    $jawabanSoal = $jawaban[$soal->id] ?? null;
                                @endphp

                                <textarea
                                    name="jawaban[{{ $soal->id }}]"
                                    class="form-control rounded-4 p-3 border-2"
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
            <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-4 border mb-4 shadow-sm">
                <button type="button" class="btn btn-light px-4 py-2.5 rounded-3 text-secondary fw-semibold" id="btnPrev" onclick="navigateQuestion(-1)">
                    <i class="fa-solid fa-arrow-left me-2"></i> Sebelumnya
                </button>
                
                <button type="button" class="btn btn-warning px-4 py-2.5 rounded-3 text-white fw-semibold" id="btnRagu" onclick="toggleRagu()">
                    <i class="fa-regular fa-square-minus me-2"></i> Ragu-Ragu
                </button>
                
                <button type="button" class="btn btn-primary px-4 py-2.5 rounded-3 fw-semibold" id="btnNext" style="background: var(--accent-blue); border:none;" onclick="navigateQuestion(1)">
                    Selanjutnya <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>

        {{-- KOLOM NAVIGASI NOMOR SOAL --}}
        <div class="col-lg-4">
            <div class="exam-nav-card shadow-sm">
                <h6 class="fw-bold mb-3" style="color: var(--primary-dark);">
                    <i class="fa-solid fa-th me-2 text-info"></i> Navigasi Soal
                </h6>
                
                <div class="number-grid mb-4" id="navigationGrid">
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
                
                <hr style="border-color: var(--border-color);">
                
                <button type="button" class="btn btn-danger w-100 py-3 rounded-4 fw-bold shadow-sm" style="letter-spacing: 0.5px;" onclick="confirmFinish()">
                    <i class="fa-solid fa-cloud-arrow-up me-2"></i> Selesaikan Ujian
                </button>
            </div>
        </div>

    </div>
</div>

{{-- INTERACTIVE JAVASCRIPT --}}
<script>
    let isReloading = false;

    window.addEventListener("beforeunload", function () {
        isReloading = true;
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

            // sedang reload -> abaikan
            if (isReloading) return;

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

                document.getElementById("formUjian").submit();

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
            btnNext.style.background = '#10b981'; // Green color for completion
        } else {
            btnNext.innerHTML = 'Selanjutnya <i class="fa-solid fa-arrow-right ms-2"></i>';
            btnNext.style.background = 'var(--accent-blue)';
        }

        // Sinkronisasi status visual Ragu-Ragu pada tombol
        if (raguStates[currentIdx]) {
            btnRagu.classList.remove('btn-warning');
            btnRagu.classList.add('btn-danger');
            btnRagu.innerHTML = '<i class="fa-solid fa-square-check me-2"></i> Batalkan Ragu';
        } else {
            btnRagu.classList.remove('btn-danger');
            btnRagu.classList.add('btn-warning');
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
                document.getElementById("formUjian").submit(); // Kumpulkan otomatis jika habis
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Format angka agar selalu dua digit (00:00:00)
            const format = (num) => String(num).padStart(2, '0');
            document.getElementById("countdownTimer").innerHTML = `${format(hours)}:${format(minutes)}:${format(seconds)}`;
        }, 1000);
    }

    // Alert Konfirmasi Selesai Ujian
    function confirmFinish() {
        if (confirm("Apakah Anda yakin ingin mengakhiri ujian ini? Pastikan semua jawaban telah terisi.")) {
            document.getElementById("formUjian").submit();
        }
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
                // tidak kirim jawaban_text & is_ragu_ragu -> tidak akan tertimpa
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
                // tidak kirim pilihan_jawaban_id & is_ragu_ragu
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
                // tidak kirim pilihan_jawaban_id & jawaban_text -> jawaban lama tetap aman
            })
        });
    }
</script>
@endsection