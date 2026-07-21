@extends('layouts.app')

@section('title', 'Ujian Hari Ini')

@section('content')
<style>
    /* Custom Modern Color Palette */
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0ea5e9;
        --surface-white: #ffffff;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        
        /* Soft shadow matching the dark slate tone */
        --card-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.04), 0 4px 12px -5px rgba(15, 23, 42, 0.04);
        --hover-shadow: 0 20px 40px -5px rgba(14, 165, 233, 0.12);
    }

    /* Header Section (Dark Theme) */
    .cbt-header {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-dark));
        padding: 35px;
        border-radius: 24px;
        color: var(--surface-white);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    .cbt-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(14, 165, 233, 0.08); /* Glow using accent blue */
        border-radius: 50%;
    }

    /* Profile / Info Card */
    .info-card {
        background: var(--surface-white);
        border-radius: 20px;
        border: 1px solid var(--border-color);
        padding: 24px;
        margin-bottom: 30px;
        box-shadow: var(--card-shadow);
    }
    .info-divider {
        width: 1px;
        background-color: var(--border-color);
    }

    /* Exam Card Styles */
    .exam-card {
        background: var(--surface-white);
        border-radius: 20px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: var(--card-shadow);
    }
    .exam-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--hover-shadow);
        border-color: var(--accent-blue);
    }
    .exam-header {
        padding: 24px;
        border-bottom: 1px solid var(--border-color);
        flex-grow: 0;
    }
    .exam-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    /* Modern Badges & Status */
    .status-badge {
        font-size: 11.5px;
        padding: 6px 14px;
        border-radius: 30px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-belum {
        background: #f0fdf4;
        color: #16a34a;
    }
    .status-berlangsung {
        background: #fff7ed;
        color: #ea580c;
    }
    .status-selesai {
        background: #f1f5f9;
        color: var(--text-muted);
    }

    /* Pulsing Dot Effect for Active Exam (Accent Blue) */
    .pulse-dot {
        width: 8px;
        height: 8px;
        background-color: var(--accent-blue);
        border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.7);
        animation: pulsing 1.5s infinite;
    }
    @keyframes pulsing {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(14, 165, 233, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(14, 165, 233, 0); }
    }

    /* Info Grid */
    .detail-box {
        background: #f8fafc;
        padding: 14px;
        border-radius: 16px;
        text-align: center;
        border: 1px solid var(--border-color);
    }

    /* Modern Buttons */
    .btn-masuk {
        background: var(--accent-blue);
        color: var(--surface-white);
        width: 100%;
        padding: 14px;
        border-radius: 16px;
        font-weight: 600;
        border: none;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
    }
    .btn-masuk:hover {
        background: #0284c7; /* Darker accent */
        color: var(--surface-white);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(14, 165, 233, 0.3);
    }
    .btn-disabled {
        background: #f1f5f9;
        color: var(--text-muted);
        width: 100%;
        padding: 14px;
        border-radius: 16px;
        font-weight: 600;
        border: none;
        cursor: not-allowed;
    }

    /* Empty State */
    .empty-box {
        text-align: center;
        padding: 60px 20px;
        border: 2px dashed var(--border-color);
        border-radius: 24px;
        background: #fafafa;
    }

    /* SUCCESS MODAL */

    .success-icon{

        width:90px;
        height:90px;

        margin:auto;

        border-radius:50%;

        display:flex;
        align-items:center;
        justify-content:center;

        background:#dcfce7;

        color:#16a34a;

        font-size:45px;

        animation:pop .4s ease;

    }


    @keyframes pop{

        from{

            transform:scale(.5);

            opacity:0;

        }

        to{

            transform:scale(1);

            opacity:1;

        }

    }


    .modal-content{

        border-radius:24px!important;

    }
</style>

<div class="container-fluid px-4 py-2">

    {{-- HEADER --}}
    <div class="cbt-header shadow-sm">
        <div class="d-flex align-items-center">
            <div class="me-3 bg-white bg-opacity-10 p-3 rounded-4">
                <i class="fa-solid fa-laptop-code fa-2x text-white"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-1">Ujian Hari Ini</h3>
                <p class="mb-0 text-white-50">Silakan cek jadwal ujian Computer Based Test (CBT) Anda di bawah ini.</p>
            </div>
        </div>
    </div>

    {{-- DATA SISWA --}}
    <div class="info-card">
        <div class="row align-items-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="d-flex align-items-center">
                    <div class="bg-light p-2 rounded-3 me-3" style="color: var(--accent-blue);">
                        <i class="fa-solid fa-user-graduate fa-lg"></i>
                    </div>
                    <div>
                        <small class="text-uppercase fw-bold d-block" style="font-size: 10px; letter-spacing: 0.5px; color: var(--text-muted);">Nama Siswa</small>
                        <h6 class="fw-bold mb-0" style="color: var(--primary-dark);">{{ $siswa->nama }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-1 d-none d-md-block text-center">
                <div class="info-divider d-inline-block" style="height: 40px;"></div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="d-flex align-items-center">
                    <div class="bg-light p-2 rounded-3 me-3 text-success">
                        <i class="fa-solid fa-school fa-lg"></i>
                    </div>
                    <div>
                        <small class="text-uppercase fw-bold d-block" style="font-size: 10px; letter-spacing: 0.5px; color: var(--text-muted);">Kelas Aktif</small>
                        <h6 class="fw-bold mb-0" style="color: var(--primary-dark);">{{ optional($kelasAktif)->nama_kelas ?? '-' }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-1 d-none d-md-block text-center">
                <div class="info-divider d-inline-block" style="height: 40px;"></div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <div class="bg-light p-2 rounded-3 me-3" style="color: var(--accent-blue);">
                        <i class="fa-solid fa-file-signature fa-lg"></i>
                    </div>
                    <div>
                        <small class="text-uppercase fw-bold d-block" style="font-size: 10px; letter-spacing: 0.5px; color: var(--text-muted);">Total Agenda</small>
                        <h6 class="fw-bold mb-0" style="color: var(--accent-blue);">{{ $ujians->count() }} Ujian Hari Ini</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DAFTAR UJIAN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0" style="color: var(--primary-dark);">
            <i class="fa-solid fa-calendar-week me-2" style="color: var(--accent-blue);"></i> Jadwal Ujian Aktif
        </h5>
    </div>

    @if($ujians->count() > 0)
        <div class="row g-4">
            @foreach($ujians as $ujian)
                <div class="col-md-6 col-xl-4">
                    <div class="exam-card">
                        
                        <div class="exam-header">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <h6 class="fw-bold mb-1 text-truncate" style="max-width: 180px; color: var(--primary-dark);">
                                        {{ $ujian->nama_ujian }}
                                    </h6>
                                    <small style="color: var(--text-muted);">
                                        <i class="fa-solid fa-tag me-1"></i> {{ optional($ujian->jenisUjian)->nama ?? 'Ujian' }}
                                    </small>
                                </div>

                                @if($ujian->status == 'berlangsung')
                                    <span class="status-badge status-berlangsung" style="color: var(--accent-blue); background: #f0f9ff;">
                                        <span class="pulse-dot"></span> Aktif
                                    </span>
                                @elseif($ujian->status == 'belum')
                                    <span class="status-badge status-belum">
                                        <i class="fa-regular fa-calendar me-1"></i> Belum Mulai
                                    </span>
                                @else
                                    <span class="status-badge status-selesai">
                                        <i class="fa-solid fa-circle-check me-1"></i> Selesai
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="exam-body">
                            <div class="p-3 rounded-4 mb-4" style="background-color: #f8fafc; border: 1px solid var(--border-color);">
                                <small class="text-uppercase fw-bold d-block mb-1" style="font-size: 10px; color: var(--text-muted);">Mata Pelajaran</small>
                                <span class="fw-bold" style="color: var(--primary-dark); font-size: 0.95rem;">
                                    <i class="fa-solid fa-book-bookmark me-2" style="color: var(--accent-blue);"></i>
                                    {{ optional(optional($ujian->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}
                                </span>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="detail-box">
                                        <small class="d-block mb-1" style="font-size: 11px; color: var(--text-muted);">Jam Mulai</small>
                                        <div class="fw-bold" style="color: var(--primary-dark);">
                                            <i class="fa-regular fa-clock me-1" style="color: var(--accent-blue);"></i>
                                            {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('H:i') }} WIB
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="detail-box">
                                        <small class="d-block mb-1" style="font-size: 11px; color: var(--text-muted);">Jam Selesai</small>
                                        <div class="fw-bold" style="color: var(--primary-dark);">
                                            <i class="fa-regular fa-clock text-danger me-1"></i>
                                            {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }} WIB
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-auto">
                                @if($ujian->status_siswa == 'selesai')


                                <button class="btn-disabled d-flex align-items-center justify-content-center gap-2"
                                        style="background:#dcfce7;color:#15803d;">

                                    <i class="fa-solid fa-circle-check"></i>

                                    Sudah Dikerjakan

                                </button>



                                @elseif($ujian->status == 'berlangsung')


                                <a href="{{ route('dashboard-siswa.ujian.mulai',$ujian->id) }}"
                                class="btn-masuk d-block text-center text-decoration-none">

                                    <i class="fa-solid fa-play me-2"></i>

                                    Kerjakan Ujian

                                </a>



                                @elseif($ujian->status == 'belum')


                                <button class="btn-disabled d-flex align-items-center justify-content-center gap-2"
                                        disabled>

                                    <i class="fa-solid fa-lock"></i>

                                    Belum Dibuka

                                </button>



                                @else


                                <button class="btn-disabled d-flex align-items-center justify-content-center gap-2"
                                        disabled>

                                    <i class="fa-solid fa-clock"></i>

                                    Waktu Berakhir

                                </button>


                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-box py-5 shadow-sm">
            <div class="mb-3">
                <i class="fa-solid fa-calendar-xmark fa-4x" style="color: var(--text-muted); opacity: 0.4;"></i>
            </div>
            <h5 class="fw-bold mb-1" style="color: var(--primary-dark);">Belum Ada Jadwal Ujian</h5>
            <p class="mb-0" style="color: var(--text-muted);">Tidak ada agenda ujian CBT yang dijadwalkan untuk kelas Anda hari ini.</p>
        </div>
    @endif

</div>

{{-- MODAL SUCCESS --}}
@if(session('success'))

<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">


            <div class="modal-body text-center p-5">


                <div class="success-icon mb-4">

                    @if(session('auto_submit'))
                        <i class="fa-solid fa-triangle-exclamation text-danger"></i>
                    @else
                        <i class="fa-solid fa-circle-check text-success"></i>
                    @endif

                </div>


                <h4 class="fw-bold mb-2">

                    @if(session('auto_submit'))
                        Ujian Dikumpulkan Otomatis
                    @else
                        Ujian Berhasil Dikumpulkan
                    @endif

                </h4>

                <p class="text-muted mb-4">

                    {{ session('success') }}

                    <br>

                    @if(session('auto_submit'))
                        Jawaban yang belum diisi dianggap salah.
                    @else
                        Jawaban Anda sudah tersimpan dan tidak dapat dikerjakan kembali.
                    @endif

                </p>
                
                <button type="button"
                        class="btn-masuk px-5"
                        data-bs-dismiss="modal">

                    <i class="fa-solid fa-check me-2"></i>

                    Mengerti

                </button>


            </div>


        </div>

    </div>

</div>


@endif

@if(session('success'))

<script>

document.addEventListener("DOMContentLoaded",function(){

    let modal = new bootstrap.Modal(
        document.getElementById('successModal')
    );

    modal.show();

});

</script>

@endif
@endsection