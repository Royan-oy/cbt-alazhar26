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

    /* FILTER PILLS STYLING */
    .custom-filter-pills .btn-view-mode {
        color: #64748b;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        font-size: 13px;
        font-weight: 600;
    }
    .custom-filter-pills .btn-view-mode:hover {
        background-color: #f1f5f9;
        color: #0284c7;
    }
    .custom-filter-pills .btn-view-mode.active {
        background: linear-gradient(135deg, #38bdf8, #0284c7) !important;
        color: #ffffff !important;
        border-color: #0284c7;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    }

    /* Floating Stats Bar & Dropdown Animation */
    .dropdown-menu {
        animation: slideFadeIn 0.25s ease-out forwards;
        margin-top: 0;
    }
    @keyframes slideFadeIn {
        from { opacity: 0; margin-top: -10px; }
        to { opacity: 1; margin-top: 0.25rem; }
    }

    .stats-bar {
        position: sticky;
        top: 1rem;
        z-index: 100;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 1rem;
        padding: 0.75rem 1.5rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .stat-item {
        text-align: center;
        padding: 0 1rem;
        flex: 1;
    }
    .stat-item:not(:last-child) {
        border-right: 1px solid #e2e8f0;
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    .stat-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Grid Layout */
    .monitoring-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.25rem;
    }

    /* Student Card */
    .student-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.25rem;
        position: relative;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .student-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
    }
    .student-card.status-mengerjakan {
        border-top: 4px solid #f59e0b;
    }
    .student-card.status-selesai {
        border-top: 4px solid #10b981;
    }
    .student-card.status-belum {
        border-top: 4px solid #94a3b8;
        opacity: 0.9;
    }
    .student-card.violation-alert {
        border-color: #ef4444;
        box-shadow: 0 0 0 1px #ef4444, 0 4px 6px rgba(239, 68, 68, 0.1);
    }

    .card-header-flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .avatar-student {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .student-info {
        flex-grow: 1;
        margin-left: 0.75rem;
        overflow: hidden;
    }
    .student-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.95rem;
        line-height: 1.2;
        margin-bottom: 0.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .student-nis {
        font-size: 0.75rem;
        color: #64748b;
    }

    /* Pulse animation for 'mengerjakan' */
    @keyframes pulse-ring {
        0% { transform: scale(0.8); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(245, 158, 11, 0); }
        100% { transform: scale(0.8); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }
    .status-dot {
        width: 12px; height: 12px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }
    .dot-mengerjakan {
        background: #f59e0b;
        animation: pulse-ring 2s infinite;
    }
    .dot-selesai { background: #10b981; }
    .dot-belum { background: #94a3b8; }

    /* Progress Section */
    .progress-section {
        background: #f8fafc;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
    }
    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
    }
    .progress-bar-container {
        height: 6px;
        border-radius: 3px;
        background: #e2e8f0;
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.5s ease-in-out;
    }
    .fill-mengerjakan { background: #3b82f6; }
    .fill-selesai { background: #10b981; }

    /* Meta Info (Time & Violations) */
    .meta-info {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: #64748b;
        padding-top: 0.5rem;
        border-top: 1px dashed #e2e8f0;
    }
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    
    .violation-badge {
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .v-0 { background: #f1f5f9; color: #94a3b8; }
    .v-low { background: #fef3c7; color: #d97706; }
    .v-high { background: #fee2e2; color: #dc2626; }

    /* Actions */
    .card-actions {
        display: flex;
        gap: 0.5rem;
        opacity: 0;
        transition: opacity 0.2s;
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        background: rgba(255,255,255,0.9);
        padding-left: 0.5rem;
        border-radius: 0.5rem;
    }
    .student-card:hover .card-actions {
        opacity: 1;
    }
    @media (max-width: 768px) {
        .card-actions { opacity: 1; position: static; justify-content: flex-end; padding-top: 0.5rem; background: transparent; }
        .stats-bar { flex-wrap: wrap; gap: 1rem; justify-content: center; position: static; margin-bottom: 1rem; }
        .stat-item:not(:last-child) { border-right: none; }
        .stat-item { width: 45%; flex: auto; }
        .student-info { max-width: 150px; }
    }

    .btn-action {
        width: 30px; height: 30px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        background: #fff;
        transition: all 0.2s;
        cursor: pointer;
    }
    .btn-action.force-submit:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }
    .btn-action.reset:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }

    /* Loading Overlay */
    .auto-refresh-indicator {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        background: rgba(15, 23, 42, 0.85);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        backdrop-filter: blur(4px);
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: opacity 0.3s;
    }
    .refresh-spinner {
        width: 12px; height: 12px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

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
                    Pantau progres, pelanggaran, dan status pengerjaan siswa kelas Anda secara live tanpa perlu me-refresh halaman.
                </p>
            </div>
        </div>
    </div>

    {{-- SESSION MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-circle-check text-success"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-3 mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-danger"></i> {{ session('error') }}
        </div>
    @endif

    {{-- TIDAK ADA UJIAN AKTIF --}}
    @if($ujians->isEmpty())
        <div class="card border-0 rounded-4 shadow-sm p-5 text-center">
            <i class="fa-regular fa-calendar-xmark fa-3x text-muted mb-3 d-block" style="opacity: 0.3;"></i>
            <h6 class="text-muted fw-semibold">Tidak Ada Ujian Aktif</h6>
            <p class="text-muted mb-0" style="font-size: 13px;">
                Saat ini tidak ada ujian yang sedang berlangsung untuk kelas yang Anda wali.
            </p>
        </div>
    @else

        {{-- FLOATING STATS BAR --}}
        @php
            $cntBelum       = $monitoring->where('status', 'belum')->count();
            $cntMengerjakan = $monitoring->where('status', 'mengerjakan')->count();
            $cntSelesai     = $monitoring->where('status', 'selesai')->count();
            $totalSiswa     = $monitoring->count();
        @endphp
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-value text-dark" id="stat-total">{{ $totalSiswa }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
            <div class="stat-item">
                <div class="stat-value text-secondary" id="stat-belum">{{ $cntBelum }}</div>
                <div class="stat-label">Belum Mulai</div>
            </div>
            <div class="stat-item">
                <div class="stat-value text-warning" id="stat-mengerjakan">{{ $cntMengerjakan }}</div>
                <div class="stat-label">Mengerjakan</div>
            </div>
            <div class="stat-item">
                <div class="stat-value text-success" id="stat-selesai">{{ $cntSelesai }}</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>

        {{-- UJIAN SELECTOR TABS --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <h5 class="fw-bold mb-0" style="color: #0f172a;">
                <i class="fa-solid fa-list-check me-2" style="color: #0284c7;"></i> <span style="font-size: 1.1rem;">Pilih Ujian Aktif</span>
            </h5>
            
            <div class="nav nav-pills custom-filter-pills gap-2 flex-wrap justify-content-md-end">
                @php
                    // Pastikan ujian yang sedang aktif/dipilih selalu menjadi pill yang terlihat
                    $selectedUjian = $ujians->firstWhere('id', $selectedUjianId) ?? $ujians->first();
                    $hiddenUjians = $ujians->where('id', '!=', $selectedUjian->id);
                @endphp

                <!-- Ujian yang sedang dipilih -->
                <a href="{{ route('dashboard-guru.wali-kelas.monitoring-siswa', ['ujian_id' => $selectedUjian->id]) }}"
                   class="nav-link rounded-pill px-3 py-2 btn-view-mode active">
                    <i class="fa-solid fa-file-pen me-1"></i>
                    {{ $selectedUjian->nama_ujian }}
                    <span class="ms-1 opacity-75">({{ $selectedUjian->nama_mapel }})</span>
                </a>

                <!-- Opsi ujian lainnya di dalam dropdown -->
                @if($hiddenUjians->isNotEmpty())
                    <div class="dropdown">
                        <button class="nav-link rounded-pill px-3 py-2 btn-view-mode dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #fff; border: 1px solid #e2e8f0; color: #64748b;">
                             Ujian Lainnya
                        </button>
                        <ul class="dropdown-menu border-0 shadow-sm dropdown-menu-end" style="border-radius: 0.75rem;">
                            @foreach($hiddenUjians as $ujian)
                                <li>
                                    <a class="dropdown-item py-2" 
                                       href="{{ route('dashboard-guru.wali-kelas.monitoring-siswa', ['ujian_id' => $ujian->id]) }}"
                                       style="font-size: 13.5px;">
                                        <i class="fa-solid fa-file-pen me-1 text-muted"></i> {{ $ujian->nama_ujian }}
                                        <span class="ms-1 text-muted">({{ $ujian->nama_mapel }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        {{-- MONITORING GRID --}}
        <div class="monitoring-grid" id="monitoring-container">
            <!-- Isi akan di render melalui JavaScript via AJAX -->
        </div>

        {{-- AUTO REFRESH INDICATOR --}}
        <div class="auto-refresh-indicator" id="sync-indicator">
            <div class="refresh-spinner" id="sync-spinner"></div>
            <span id="sync-text">Live Sync: Menghubungkan...</span>
        </div>

    @endif

</div>

@if($ujians->isNotEmpty())
<script>
    const monitoringUrl = "{{ route('dashboard-guru.wali-kelas.monitoring-siswa', ['ujian_id' => $selectedUjianId]) }}";
    const container = document.getElementById('monitoring-container');
    const syncIndicator = document.getElementById('sync-indicator');
    const syncSpinner = document.getElementById('sync-spinner');
    const syncText = document.getElementById('sync-text');
    
    // Polling every 5 seconds for real-time feel
    const pollInterval = 5000; 
    let initialLoad = true;
    
    function fetchMonitoringData() {
        if(!initialLoad) {
            syncSpinner.style.display = 'block';
            syncText.textContent = 'Syncing...';
        }
        
        fetch(monitoringUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update Stats
            document.getElementById('stat-total').textContent = data.totalSiswa;
            document.getElementById('stat-belum').textContent = data.cntBelum;
            document.getElementById('stat-mengerjakan').textContent = data.cntMengerjakan;
            document.getElementById('stat-selesai').textContent = data.cntSelesai;
            
            // Update Cards
            const students = data.monitoring;
            const totalSoal = data.totalSoal;
            
            if(students.length > 0) {
                let html = '';
                students.forEach(row => {
                    html += buildCardHtml(row, totalSoal);
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = `
                <div class="col-12 text-center py-5 text-muted" style="grid-column: 1 / -1;">
                    <i class="fa-solid fa-users-slash fa-2x mb-3 d-block opacity-25"></i>
                    Tidak ada data siswa untuk ujian ini.
                </div>`;
            }
            
            syncSpinner.style.display = 'none';
            syncText.textContent = 'Live Sync: Active';
            initialLoad = false;
        })
        .catch(error => {
            console.error("Error fetching monitoring data:", error);
            syncSpinner.style.display = 'none';
            syncText.textContent = 'Sync Failed. Retrying...';
        });
    }
    
    function buildCardHtml(row, totalSoal) {
        let statusClass = 'status-belum';
        let dotClass = 'dot-belum';
        let statusText = 'Belum Mulai';
        if (row.status === 'mengerjakan') { statusClass = 'status-mengerjakan'; dotClass = 'dot-mengerjakan'; statusText = 'Mengerjakan'; }
        else if (row.status === 'selesai') { statusClass = 'status-selesai'; dotClass = 'dot-selesai'; statusText = 'Selesai'; }
        
        let vc = parseInt(row.violation_count || 0);
        let vClass = 'v-0';
        let vIcon = 'fa-shield-check';
        let alertClass = '';
        if (vc > 0 && vc <= 2) { vClass = 'v-low'; vIcon = 'fa-triangle-exclamation'; }
        else if (vc > 2) { vClass = 'v-high'; vIcon = 'fa-skull-crossbones'; alertClass = 'violation-alert'; }
        
        let progressPercent = row.progress;
        let progressFillClass = row.status === 'selesai' ? 'fill-selesai' : 'fill-mengerjakan';
        
        let waktuMulai = row.waktu_mulai_kerja ? row.waktu_mulai_kerja.substring(11, 16) : '—';
        let lastAuto = row.last_autosave ? row.last_autosave.substring(11, 16) : '—';
        
        // Initial Avatar letters
        let initials = row.nama.substring(0, 2).toUpperCase();
        
        // CSRF Token
        let csrf = "{{ csrf_token() }}";
        
        let formForceSubmit = '';
        if (row.nilai_id && row.status !== 'selesai') {
            formForceSubmit = `
                <form method="POST" action="/dashboard-guru/wali-kelas/monitoring/${row.nilai_id}/force-submit" class="d-inline" onsubmit="return confirm('Yakin ingin force submit ujian ${row.nama}?')">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="ujian_id" value="{{ $selectedUjianId }}">
                    <button type="submit" class="btn-action force-submit" title="Force Submit">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            `;
        }
        
        let formReset = '';
        if (row.nilai_id) {
            formReset = `
                <form method="POST" action="/dashboard-guru/wali-kelas/monitoring/${row.nilai_id}/reset" class="d-inline" onsubmit="return confirm('PERHATIAN: Semua jawaban ${row.nama} akan dihapus. Lanjutkan?')">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="ujian_id" value="{{ $selectedUjianId }}">
                    <button type="submit" class="btn-action reset" title="Reset Ujian">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>
                </form>
            `;
        }
        
        let actionsHtml = '';
        if (row.nilai_id) {
            actionsHtml = `
                <div class="card-actions">
                    ${formForceSubmit}
                    ${formReset}
                </div>
            `;
        }

        return `
            <div class="student-card ${statusClass} ${alertClass}">
                <div class="card-header-flex">
                    <div class="avatar-student">${initials}</div>
                    <div class="student-info">
                        <div class="student-name" title="${row.nama}">${row.nama}</div>
                        <div class="student-nis">${row.nis}</div>
                    </div>
                    <div class="status-dot ${dotClass}" title="${statusText}"></div>
                </div>
                
                <div class="progress-section">
                    <div class="progress-header">
                        <span>Progres</span>
                        <span>${row.current_question || 0} / ${totalSoal} Soal</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill ${progressFillClass}" style="width: ${progressPercent}%"></div>
                    </div>
                </div>
                
                <div class="meta-info">
                    <div class="meta-item" title="Mulai Kerja">
                        <i class="fa-regular fa-clock"></i> ${waktuMulai}
                    </div>
                    <div class="meta-item" title="Autosave Terakhir">
                        <i class="fa-solid fa-floppy-disk"></i> ${lastAuto}
                    </div>
                    <div class="meta-item violation-badge ${vClass}" title="Pelanggaran: ${vc}">
                        <i class="fa-solid ${vIcon}"></i> ${vc}
                    </div>
                </div>
                
                ${actionsHtml}
            </div>
        `;
    }
    
    // Initial fetch
    fetchMonitoringData();
    // Start Polling
    setInterval(fetchMonitoringData, pollInterval);
</script>
@endif

@endsection
