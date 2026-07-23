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

        {{-- TOOLBAR (SEARCH & FILTER) --}}
        <div class="d-flex flex-column flex-md-row justify-content-start align-items-md-center gap-3 my-3">
            <div class="d-flex align-items-center gap-2">
                <label for="status-filter" class="text-muted fw-semibold mb-0" style="font-size: 0.85rem; white-space: nowrap;">Filter:</label>
                <select id="status-filter" class="form-select" style="min-width: 160px; border-radius: 0.75rem; border-color: #e2e8f0; font-size: 0.875rem;">
                    <option value="semua">Semua Status</option>
                    <option value="belum">Belum Mulai</option>
                    <option value="mengerjakan">Mengerjakan</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <div class="input-group" style="max-width: 350px;">
                <span class="input-group-text bg-white border-end-0" style="border-radius: 0.75rem 0 0 0.75rem; border-color: #e2e8f0;">
                    <i class="fa-solid fa-search text-muted"></i>
                </span>
                <input type="text" id="search-input" class="form-control border-start-0 ps-0" placeholder="Cari nama siswa..." style="border-radius: 0 0.75rem 0.75rem 0; border-color: #e2e8f0; box-shadow: none;">
            </div>
            
        </div>

        {{-- MONITORING TABLE --}}
        <div class="table-responsive bg-white rounded-4 shadow-sm border border-slate-200" style="overflow: hidden;">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                <thead style="background-color: #f8fafc; color: #475569; border-bottom: 2px solid #e2e8f0; font-weight: 600;">
                    <tr>
                        <th class="py-3 px-4" width="5%">No</th>
                        <th class="py-3 px-4" width="30%">Nama Siswa</th>
                        <th class="py-3 px-4 text-center" width="15%">Status</th>
                        <th class="py-3 px-4 text-center" width="15%">Pelanggaran</th>
                        <th class="py-3 px-4 text-center" width="15%">Mulai</th>
                        <th class="py-3 px-4 text-center" width="15%">Selesai</th>
                        <th class="py-3 px-4 text-center" width="5%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="monitoring-container" style="border-top: none;">
                    <!-- Isi akan di render melalui JavaScript via AJAX -->
                </tbody>
            </table>
        </div>

    @endif

</div>

@if($ujians->isNotEmpty())
<script>
    const monitoringUrl = "{{ route('dashboard-guru.wali-kelas.monitoring-siswa', ['ujian_id' => $selectedUjianId]) }}";
    const container = document.getElementById('monitoring-container');
    
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    
    let initialLoad = true;
    
    function fetchMonitoringData() {
        let queryParams = new URLSearchParams({
            search: searchInput.value,
            status: statusFilter.value
        }).toString();

        fetch(monitoringUrl + '&' + queryParams, {
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
                students.forEach((row, index) => {
                    html += buildRowHtml(row, index + 1);
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="fa-solid fa-users-slash fa-2x mb-3 d-block opacity-25"></i>
                        Tidak ada data siswa untuk ujian ini.
                    </td>
                </tr>`;
            }
        })
        .catch(error => {
            console.error("Error fetching monitoring data:", error);
        });
    }
    
    function buildRowHtml(row, index) {
        let badgeClass = 'bg-secondary';
        let statusText = 'Belum Mulai';
        if (row.status === 'mengerjakan') { badgeClass = 'bg-warning text-dark'; statusText = 'Mengerjakan'; }
        else if (row.status === 'selesai') { badgeClass = 'bg-success'; statusText = 'Selesai'; }
        
        let vc = parseInt(row.violation_count || 0);
        let vClass = 'text-muted';
        let vIcon = 'fa-shield-check';
        if (vc > 0 && vc <= 2) { vClass = 'text-warning'; vIcon = 'fa-triangle-exclamation'; }
        else if (vc > 2) { vClass = 'text-danger'; vIcon = 'fa-skull-crossbones'; }
        
        let waktuMulai = row.waktu_mulai_kerja ? row.waktu_mulai_kerja.substring(11, 16) : '—';
        let waktuKumpul = row.waktu_kumpul ? row.waktu_kumpul.substring(11, 16) : '—';
        
        // CSRF Token
        let csrf = "{{ csrf_token() }}";
        
        let formForceSubmit = '';
        if (row.nilai_id && row.status !== 'selesai') {
            formForceSubmit = `
                <form method="POST" action="/dashboard-guru/wali-kelas/monitoring/${row.nilai_id}/force-submit" class="d-inline" onsubmit="return confirm('Yakin ingin force submit ujian ${row.nama}?')">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="ujian_id" value="{{ $selectedUjianId }}">
                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-circle" title="Force Submit">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            `;
        }

        return `
            <tr>
                <td class="text-center font-weight-bold">${index}</td>
                <td>
                    <div class="fw-bold" style="color: #1e293b;">${row.nama}</div>
                    <div style="font-size: 0.75rem; color: #64748b;">${row.nis}</div>
                </td>
                <td class="text-center">
                    <span class="badge rounded-pill ${badgeClass} px-3 py-2">${statusText}</span>
                </td>
                <td class="text-center">
                    <span class="${vClass} fw-bold" style="font-size: 0.9rem;">
                        <i class="fa-solid ${vIcon} me-1"></i> ${vc}
                    </span>
                </td>
                <td class="text-center" style="font-size: 0.85rem; color: #475569;">
                    ${waktuMulai !== '—' ? `<i class="fa-regular fa-clock me-1 text-muted"></i> ${waktuMulai}` : '—'}
                </td>
                <td class="text-center" style="font-size: 0.85rem; color: #475569;">
                    ${waktuKumpul !== '—' ? `<i class="fa-solid fa-flag-checkered me-1 text-muted"></i> ${waktuKumpul}` : '—'}
                </td>
                <td class="text-center">
                    ${formForceSubmit}
                </td>
            </tr>
        `;
    }
    // Event Listeners for Search and Filter
    searchInput.addEventListener('keyup', function() {
        fetchMonitoringData();
    });
    
    statusFilter.addEventListener('change', function() {
        fetchMonitoringData();
    });

    // Initial fetch
    fetchMonitoringData();
</script>
@endif

@endsection
