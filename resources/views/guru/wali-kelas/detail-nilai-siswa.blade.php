@extends('layouts.app')

@section('title', 'Detail Nilai Siswa - Wali Kelas')

@section('content')
<style>
    :root {
        --primary-dark: #0f172a;
        --secondary-dark: #1e293b;
        --accent-blue: #0284c7;
        --accent-blue-light: #38bdf8;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    /* Page Header */
    .page-header-card {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        border: none;
        border-radius: 1.25rem;
        overflow: hidden;
        position: relative;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
    }
    .page-header-card::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 350px; height: 350px;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Stat Cards */
    .summary-stat-card {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -2px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
        height: 100%;
    }
    .summary-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
    }
    .stat-icon-shape {
        width: 44px;
        height: 44px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    /* Action Buttons */
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    .btn-pdf {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2);
    }
    .btn-pdf:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(220, 38, 38, 0.3);
    }
    .btn-back {
        background: #ffffff;
        color: #475569;
        border-color: #cbd5e1;
    }
    .btn-back:hover {
        background: #f8fafc;
        color: #1e293b;
        border-color: #94a3b8;
        transform: translateY(-2px);
    }

    /* Mapel Card / Accordion Style */
    .mapel-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        box-shadow: 0 2px 8px -2px rgba(15, 23, 42, 0.04);
        overflow: hidden;
        margin-bottom: 1.25rem;
        transition: all 0.2s ease;
    }
    .mapel-card:hover {
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
    }
    .mapel-header {
        padding: 1.25rem;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .mapel-title-area {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .mapel-icon-shape {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
        color: #0369a1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
    }
    .mapel-stats-area {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Badges */
    .badge-status {
        font-size: 11px;
        font-weight: 700;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        border: 1px solid transparent;
    }
    .badge-tuntas {
        background: #f0fdf4;
        color: #15803d;
        border-color: #bbf7d0;
    }
    .badge-kurang {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fecaca;
    }
    .badge-belum {
        background: #f8fafc;
        color: #64748b;
        border-color: #e2e8f0;
    }

    /* Sub-Table inside Mapel Card */
    .ujian-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }
    .ujian-table th {
        background-color: #f8fafc;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .ujian-table td {
        padding: 0.85rem 1.25rem;
        font-size: 0.85rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .ujian-table tbody tr:last-child td {
        border-bottom: none;
    }

    .nilai-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
    }
    .nilai-high {
        background-color: #dcfce7;
        color: #15803d;
    }
    .nilai-low {
        background-color: #fee2e2;
        color: #b91c1c;
    }

    @media (max-width: 767.98px) {
        .page-header-card {
            padding: 1.25rem !important;
            border-radius: 1rem;
        }
        .page-header-card h1 {
            font-size: 1.35rem !important;
        }
        .action-btn {
            width: 100%;
            justify-content: center;
        }
        .ujian-table th,
        .ujian-table td {
            padding: 0.6rem 0.85rem;
            font-size: 0.8rem;
        }
    }
</style>

<div class="container-fluid px-0 py-2">

    <!-- PAGE HEADER & ACTION BUTTONS -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-card p-4 p-md-5">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill mb-3 d-inline-flex align-items-center gap-2"
                              style="font-size: 0.75rem; font-weight: 600; backdrop-filter: blur(4px);">
                            <i class="fa-solid fa-user-graduate"></i>
                            Rapor Nilai Individual
                        </span>
                        <h1 class="fw-bold text-white mb-2" style="font-size: 1.75rem; letter-spacing: -0.5px;">
                            {{ $siswa->nama }}
                        </h1>
                        <p class="text-white text-opacity-75 mb-0 d-flex align-items-center gap-2 flex-wrap" style="font-size: 0.875rem;">
                            <span>NIS: {{ $siswa->nis }}</span>
                            <span class="text-white text-opacity-50">•</span>
                            <span><i class="fa-solid fa-school me-1"></i> Kelas {{ $kelas->nama_kelas }}</span>
                            <span class="text-white text-opacity-50">•</span>
                            <span><i class="fa-solid fa-calendar-alt me-1"></i> TA {{ $waliKelas->tahunAjaran->nama_tahun }}</span>
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <a href="{{ route('dashboard-guru.wali-kelas.rekap-nilai') }}" class="action-btn btn-back">
                            <i class="fa-solid fa-arrow-left"></i>
                            Kembali
                        </a>
                        <a href="{{ route('dashboard-guru.wali-kelas.rekap-nilai.export-pdf-siswa', $siswa->id) }}" target="_blank" class="action-btn btn-pdf">
                            <i class="fa-solid fa-file-pdf"></i>
                            Cetak Rapor PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STAT CARDS (SUMMARY RINGKASAN SISWA) -->
    <div class="row g-3 g-md-4 mb-4">
        <!-- Rerata Keseluruhan -->
        <div class="col-12 col-md-4">
            <div class="summary-stat-card d-flex align-items-center gap-3">
                <div class="stat-icon-shape bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-calculator"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium d-block mb-0.5" style="font-size: 12px;">Rata-Rata Nilai</span>
                    <h3 class="mb-0 fw-bold text-dark lh-1">
                        {{ $rataRataKeseluruhan !== null ? $rataRataKeseluruhan : '—' }}
                        @if($rataRataKeseluruhan !== null)
                            <span class="fs-6 text-muted fw-normal">/ 100</span>
                        @endif
                    </h3>
                </div>
            </div>
        </div>

        <!-- Ketuntasan -->
        <div class="col-6 col-md-4">
            <div class="summary-stat-card d-flex align-items-center gap-3">
                @if($statusGlobal === 'tuntas')
                    <div class="stat-icon-shape bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium d-block mb-0.5" style="font-size: 12px;">Status Ketuntasan</span>
                        <span class="badge-status badge-tuntas">TUNTAS</span>
                    </div>
                @elseif($statusGlobal === 'kurang')
                    <div class="stat-icon-shape bg-danger bg-opacity-10 text-danger">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium d-block mb-0.5" style="font-size: 12px;">Status Ketuntasan</span>
                        <span class="badge-status badge-kurang">BELUM TUNTAS</span>
                    </div>
                @else
                    <div class="stat-icon-shape bg-secondary bg-opacity-10 text-secondary">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium d-block mb-0.5" style="font-size: 12px;">Status Ketuntasan</span>
                        <span class="badge-status badge-belum">BELUM UJIAN</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Mapel Tuntas / Total -->
        <div class="col-6 col-md-4">
            <div class="summary-stat-card d-flex align-items-center gap-3">
                <div class="stat-icon-shape bg-info bg-opacity-10 text-info">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div>
                    <span class="text-muted small fw-medium d-block mb-0.5" style="font-size: 12px;">Mapel Tuntas</span>
                    <h3 class="mb-0 fw-bold text-dark lh-1">
                        {{ $mapelTuntas }} <span class="fs-6 text-muted fw-normal">dari {{ count($mapelDetails) }} Mapel</span>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- DETAIL NILAI PER MAPEL -->
    <div class="row">
        <div class="col-12">
            <h5 class="fw-bold mb-3" style="color: #0f172a;">
                <i class="fa-solid fa-list-check me-2" style="color: #0284c7;"></i> Rincian Mata Pelajaran
            </h5>

            @forelse($mapelDetails as $mapelNama => $data)
                <div class="mapel-card">
                    <!-- Header Mapel -->
                    <div class="mapel-header">
                        <div class="mapel-title-area">
                            <div class="mapel-icon-shape">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $mapelNama }}</h6>
                                <small class="text-muted" style="font-size: 11px;">KKM Mapel: {{ $data['kkm'] }}</small>
                            </div>
                        </div>

                        <div class="mapel-stats-area">
                            @if($data['avg'] !== null)
                                <div class="text-end me-3 d-none d-sm-block">
                                    <small class="text-muted d-block" style="font-size: 10px; text-transform: uppercase;">Rata-Rata Mapel</small>
                                    <span class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $data['avg'] }}</span>
                                </div>

                                @if($data['status'] === 'tuntas')
                                    <span class="badge-status badge-tuntas">Tuntas ({{ $data['avg'] }})</span>
                                @else
                                    <span class="badge-status badge-kurang">Kurang ({{ $data['avg'] }})</span>
                                @endif
                            @else
                                <span class="badge-status badge-belum">Belum Ujian</span>
                            @endif
                        </div>
                    </div>

                    <!-- Tabel Ujian Mapel -->
                    <div class="table-responsive">
                        <table class="ujian-table">
                            <thead>
                                <tr>
                                    <th>Nama Ujian / Evaluasi</th>
                                    <th style="width: 180px;" class="text-center">Jenis Ujian</th>
                                    <th style="width: 100px;" class="text-center">KKM</th>
                                    <th style="width: 120px;" class="text-center">Nilai Akhir</th>
                                    <th style="width: 170px;" class="text-center">Status Koreksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['details'] as $detail)
                                    <tr>
                                        <td>
                                            <span class="fw-medium text-dark">{{ $detail['nama_ujian'] }}</span>
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ $detail['jenis_ujian'] }}
                                        </td>
                                        <td class="text-center">
                                            {{ $detail['kkm'] }}
                                        </td>
                                        <td class="text-center">
                                            @if($detail['nilai'] !== null)
                                                @if($detail['nilai'] >= $detail['kkm'])
                                                    <span class="nilai-badge nilai-high">{{ number_format($detail['nilai'], 0) }}</span>
                                                @else
                                                    <span class="nilai-badge nilai-low">{{ number_format($detail['nilai'], 0) }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($detail['nilai'] !== null)
                                                @if(($detail['status_penilaian'] ?? 'selesai') === 'selesai')
                                                    <span class="text-success fw-bold" style="font-size: 12px;">
                                                        <i class="fa-solid fa-check-circle me-1"></i> Sudah Dikoreksi
                                                    </span>
                                                @else
                                                    <span class="text-warning fw-bold" style="font-size: 12px; color: #d97706 !important;">
                                                        <i class="fa-solid fa-clock me-1"></i> Belum Dikoreksi
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-muted" style="font-size: 12px;">
                                                    <i class="fa-solid fa-circle-minus me-1"></i> Belum Diikuti
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm p-5 text-center rounded-4">
                    <div class="empty-state-icon mb-3 mx-auto">
                        <i class="fa-solid fa-book-open fa-2x" style="color: #94a3b8;"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="color: #334155;">Belum Ada Mata Pelajaran</h5>
                    <p class="mb-0 text-muted" style="font-size: 0.875rem;">Mata pelajaran atau data ujian belum tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
