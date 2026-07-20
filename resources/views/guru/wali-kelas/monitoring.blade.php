@extends('layouts.app')

@section('title', 'Monitoring Siswa - Wali Kelas')

@section('content')
<style>
    .page-header-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border: none;
        border-radius: 1.25rem;
        overflow: hidden;
        position: relative;
    }
    .page-header-card::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 280px; height: 280px;
        background: radial-gradient(circle, rgba(245,158,11,0.1) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .ujian-tab {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }
    .ujian-tab-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.625rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        font-size: 0.8125rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }
    .ujian-tab-btn:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        background: #eff6ff;
    }
    .ujian-tab-btn.active {
        background: #3b82f6;
        color: #fff;
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59,130,246,0.25);
    }

    .monitor-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        overflow: hidden;
    }
    .monitor-card table thead th {
        background-color: #f8fafc;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .monitor-card table tbody tr {
        transition: background-color 0.15s;
    }
    .monitor-card table tbody tr:hover {
        background-color: #f8fafc;
    }
    .monitor-card table tbody td {
        padding: 0.9rem 1rem;
        font-size: 0.875rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .avatar-student {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Progress Bar */
    .progress-thin {
        height: 6px;
        border-radius: 3px;
        background: #f1f5f9;
        overflow: hidden;
        min-width: 80px;
    }
    .progress-thin .progress-bar {
        border-radius: 3px;
        transition: width 0.4s ease;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-belum   { background: #f1f5f9; color: #64748b; }
    .status-mengerjakan { background: #fef9c3; color: #854d0e; }
    .status-selesai { background: #dcfce7; color: #166534; }

    /* Violation Badge */
    .violation-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }
    .violation-0 { background: #f0fdf4; color: #16a34a; }
    .violation-low { background: #fef9c3; color: #ca8a04; }
    .violation-high { background: #fee2e2; color: #dc2626; }

    /* Action Buttons */
    .action-mini-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 7px;
        border: 1px solid;
        font-size: 11.5px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        background: transparent;
    }
    .btn-force { border-color: #ef4444; color: #ef4444; }
    .btn-force:hover { background: #ef4444; color: #fff; }
    .btn-reset { border-color: #f59e0b; color: #b45309; }
    .btn-reset:hover { background: #f59e0b; color: #fff; }

    .auto-refresh-bar {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 0.75rem;
        padding: 0.6rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 12.5px;
        color: #1d4ed8;
    }

    @media (max-width: 767.98px) {
        .page-header-card { padding: 1.5rem !important; }
        .ujian-tab-btn { font-size: 0.75rem; padding: 0.4rem 0.75rem; }
    }
</style>

<div class="container-fluid px-0 py-2">

    {{-- PAGE HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-card p-4 p-md-5">
                <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill mb-3 d-inline-flex align-items-center gap-1"
                      style="font-size: 11px; font-weight: 600;">
                    <i class="fa-solid fa-chart-line me-1"></i>
                    Monitoring Siswa — Wali Kelas
                </span>
                <h1 class="fw-bold text-white mb-1" style="font-size: 1.75rem; letter-spacing: -0.5px;">
                    Pemantauan Ujian Real-time
                </h1>
                <p class="text-white text-opacity-60 mb-0" style="font-size: 13px;">
                    Pantau progres, pelanggaran, dan status pengerjaan siswa kelas Anda secara langsung.
                </p>
            </div>
        </div>
    </div>

    {{-- AUTO REFRESH INFO --}}
    <div class="auto-refresh-bar mb-3" id="refreshStatus">
        <i class="fa-solid fa-rotate fa-spin" id="refreshIcon"></i>
        <span id="refreshText">Halaman akan diperbarui otomatis setiap <strong>30 detik</strong>.</span>
        <span class="ms-auto text-muted" id="refreshCountdown" style="font-size: 11px;"></span>
    </div>

    {{-- SESSION MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 mb-3 d-flex align-items-center gap-2" style="font-size: 14px;">
            <i class="fa-solid fa-circle-check text-success"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-3 mb-3 d-flex align-items-center gap-2" style="font-size: 14px;">
            <i class="fa-solid fa-circle-exclamation text-danger"></i> {{ session('error') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning border-0 rounded-3 mb-3 d-flex align-items-center gap-2" style="font-size: 14px;">
            <i class="fa-solid fa-triangle-exclamation text-warning"></i> {{ session('warning') }}
        </div>
    @endif

    {{-- TIDAK ADA UJIAN AKTIF --}}
    @if($ujians->isEmpty())
        <div class="monitor-card p-5 text-center">
            <i class="fa-regular fa-calendar-xmark fa-3x text-muted mb-3 d-block" style="opacity: 0.3;"></i>
            <h6 class="text-muted fw-semibold">Tidak Ada Ujian Aktif</h6>
            <p class="text-muted mb-0" style="font-size: 13px;">
                Saat ini tidak ada ujian yang sedang berlangsung untuk kelas yang Anda wali.
            </p>
        </div>
    @else

        {{-- UJIAN SELECTOR TABS --}}
        <div class="ujian-tab">
            @foreach($ujians as $ujian)
                <a href="{{ route('dashboard-guru.wali-kelas.monitoring-siswa', ['ujian_id' => $ujian->id]) }}"
                   class="ujian-tab-btn {{ $selectedUjianId == $ujian->id ? 'active' : '' }}">
                    <i class="fa-solid fa-file-pen me-1"></i>
                    {{ $ujian->nama_ujian }}
                    <span class="ms-1 opacity-75">({{ $ujian->nama_mapel }})</span>
                </a>
            @endforeach
        </div>

        {{-- STATS ROW --}}
        @php
            $cntBelum       = $monitoring->where('status', 'belum')->count();
            $cntMengerjakan = $monitoring->where('status', 'mengerjakan')->count();
            $cntSelesai     = $monitoring->where('status', 'selesai')->count();
            $totalSiswa     = $monitoring->count();
        @endphp
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="bg-white border rounded-3 p-3 text-center" style="border-color: #e2e8f0 !important;">
                    <div class="fw-bold text-dark fs-4">{{ $totalSiswa }}</div>
                    <div class="text-muted" style="font-size: 12px;">Total Siswa</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bg-white border rounded-3 p-3 text-center" style="border-color: #e2e8f0 !important;">
                    <div class="fw-bold text-secondary fs-4">{{ $cntBelum }}</div>
                    <div class="text-muted" style="font-size: 12px;">Belum Mulai</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bg-white border rounded-3 p-3 text-center" style="border-color: #e2e8f0 !important;">
                    <div class="fw-bold text-warning fs-4">{{ $cntMengerjakan }}</div>
                    <div class="text-muted" style="font-size: 12px;">Mengerjakan</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bg-white border rounded-3 p-3 text-center" style="border-color: #e2e8f0 !important;">
                    <div class="fw-bold text-success fs-4">{{ $cntSelesai }}</div>
                    <div class="text-muted" style="font-size: 12px;">Selesai</div>
                </div>
            </div>
        </div>

        {{-- MONITORING TABLE --}}
        <div class="monitor-card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 48px;">No</th>
                            <th>Nama Siswa</th>
                            <th>Status</th>
                            <th>Progres Soal</th>
                            <th>Pelanggaran</th>
                            <th>Mulai Kerja</th>
                            <th>Autosave Terakhir</th>
                            <th style="min-width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monitoring as $i => $row)
                        <tr>
                            <td>
                                <span class="text-muted" style="font-size: 13px;">{{ $i + 1 }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-student">
                                        {{ strtoupper(substr($row->nama, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ $row->nama }}</div>
                                        <div class="text-muted" style="font-size: 11px;">{{ $row->nis }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($row->status === 'mengerjakan')
                                    <span class="status-badge status-mengerjakan">
                                        <i class="fa-solid fa-circle-dot fa-beat" style="font-size: 8px;"></i> Mengerjakan
                                    </span>
                                @elseif($row->status === 'selesai')
                                    <span class="status-badge status-selesai">
                                        <i class="fa-solid fa-check"></i> Selesai
                                    </span>
                                @else
                                    <span class="status-badge status-belum">
                                        <i class="fa-regular fa-clock"></i> Belum Mulai
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($row->status !== 'belum' && $totalSoal > 0)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress-thin flex-grow-1">
                                            <div class="progress-bar {{ $row->status === 'selesai' ? 'bg-success' : 'bg-warning' }}"
                                                 style="width: {{ $row->progress }}%"></div>
                                        </div>
                                        <small class="text-muted fw-medium" style="font-size: 11px; white-space: nowrap;">
                                            {{ $row->current_question }}/{{ $totalSoal }}
                                        </small>
                                    </div>
                                @else
                                    <span class="text-muted" style="font-size: 12px;">—</span>
                                @endif
                            </td>
                            <td>
                                @php $vc = (int) ($row->violation_count ?? 0); @endphp
                                @if($vc === 0)
                                    <span class="violation-badge violation-0"><i class="fa-solid fa-shield-check"></i> {{ $vc }}</span>
                                @elseif($vc <= 2)
                                    <span class="violation-badge violation-low"><i class="fa-solid fa-triangle-exclamation"></i> {{ $vc }}</span>
                                @else
                                    <span class="violation-badge violation-high"><i class="fa-solid fa-skull-crossbones"></i> {{ $vc }}</span>
                                @endif
                            </td>
                            <td class="text-muted" style="font-size: 12px;">
                                {{ $row->waktu_mulai_kerja ? \Carbon\Carbon::parse($row->waktu_mulai_kerja)->format('H:i:s') : '—' }}
                            </td>
                            <td class="text-muted" style="font-size: 12px;">
                                {{ $row->last_autosave ? \Carbon\Carbon::parse($row->last_autosave)->format('H:i:s') : '—' }}
                            </td>
                            <td>
                                @if($row->nilai_id)
                                    @if($row->status !== 'selesai')
                                        {{-- FORCE SUBMIT --}}
                                        <form method="POST"
                                              action="{{ route('dashboard-guru.wali-kelas.monitoring-siswa.force-submit', $row->nilai_id) }}"
                                              class="d-inline-block"
                                              onsubmit="return confirm('Yakin ingin force submit ujian {{ addslashes($row->nama) }}?')">
                                            @csrf
                                            @if($selectedUjianId)
                                            <input type="hidden" name="ujian_id" value="{{ $selectedUjianId }}">
                                            @endif
                                            <button type="submit" class="action-mini-btn btn-force">
                                                <i class="fa-solid fa-paper-plane"></i> Force Submit
                                            </button>
                                        </form>
                                    @endif

                                    {{-- RESET --}}
                                    <form method="POST"
                                          action="{{ route('dashboard-guru.wali-kelas.monitoring-siswa.reset', $row->nilai_id) }}"
                                          class="d-inline-block ms-1"
                                          onsubmit="return confirm('PERHATIAN: Semua jawaban {{ addslashes($row->nama) }} akan dihapus dan sesi direset. Lanjutkan?')">
                                        @csrf
                                        @if($selectedUjianId)
                                        <input type="hidden" name="ujian_id" value="{{ $selectedUjianId }}">
                                        @endif
                                        <button type="submit" class="action-mini-btn btn-reset">
                                            <i class="fa-solid fa-rotate-left"></i> Reset
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted" style="font-size: 12px;">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-users-slash fa-2x mb-3 d-block opacity-25"></i>
                                    Tidak ada data siswa untuk ujian ini.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @endif

</div>

<script>
    // Auto-refresh setiap 30 detik
    let countdown = 30;
    const countdownEl = document.getElementById('refreshCountdown');
    const iconEl = document.getElementById('refreshIcon');

    const timer = setInterval(function () {
        countdown--;
        if (countdownEl) countdownEl.textContent = 'Refresh dalam ' + countdown + 's';

        if (countdown <= 0) {
            clearInterval(timer);
            if (iconEl) iconEl.classList.add('fa-spin');
            window.location.reload();
        }
    }, 1000);

    // Stop spinning icon once page is loaded
    if (iconEl) iconEl.classList.remove('fa-spin');
</script>

@endsection
