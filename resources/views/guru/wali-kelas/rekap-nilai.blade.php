@extends('layouts.app')

@section('title', 'Rekap Nilai - Wali Kelas')

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
        background: radial-gradient(circle, rgba(16,185,129,0.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .rekap-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        overflow: hidden;
    }

    /* Sticky table with horizontal scroll */
    .rekap-table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .rekap-table-wrapper::-webkit-scrollbar { height: 6px; }
    .rekap-table-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .rekap-table {
        border-collapse: separate;
        border-spacing: 0;
        min-width: 600px;
        width: 100%;
    }

    /* Header rows */
    .rekap-table thead tr:first-child th {
        background: #0f172a;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.875rem 1rem;
        border-right: 1px solid rgba(255,255,255,0.08);
        white-space: nowrap;
        text-align: center;
    }
    .rekap-table thead tr:first-child th:first-child,
    .rekap-table thead tr:first-child th:nth-child(2),
    .rekap-table thead tr:first-child th:nth-child(3) {
        text-align: left;
    }

    .rekap-table tbody tr {
        transition: background-color 0.15s;
    }
    .rekap-table tbody tr:hover td { background-color: #f8fafc; }
    .rekap-table tbody td {
        padding: 0.8rem 1rem;
        font-size: 0.8125rem;
        border-bottom: 1px solid #f1f5f9;
        border-right: 1px solid #f1f5f9;
        vertical-align: middle;
        text-align: center;
    }
    .rekap-table tbody td:first-child,
    .rekap-table tbody td:nth-child(2),
    .rekap-table tbody td:nth-child(3) {
        text-align: left;
    }

    /* Sticky first 3 columns on wide scroll */
    .sticky-col {
        position: sticky;
        background: #fff;
        z-index: 1;
    }
    .col-no   { left: 0;    min-width: 48px; }
    .col-nama { left: 48px; min-width: 180px; }
    .col-nis  { left: 228px; min-width: 100px; }

    .rekap-table thead tr:first-child th.sticky-col { background: #0f172a; }

    .score-chip {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        min-width: 48px;
        text-align: center;
    }
    .score-high   { background: #dcfce7; color: #15803d; }
    .score-mid    { background: #fef9c3; color: #a16207; }
    .score-low    { background: #fee2e2; color: #b91c1c; }
    .score-none   { background: #f1f5f9; color: #94a3b8; }

    .avg-chip {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        background: #eff6ff;
        color: #1d4ed8;
        min-width: 48px;
        text-align: center;
    }

    .avatar-student {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .filter-select {
        border: 1px solid #e2e8f0;
        border-radius: 0.625rem;
        padding: 0.5rem 0.875rem;
        font-size: 0.875rem;
        color: #334155;
        background: #fff;
        transition: border-color 0.2s;
    }
    .filter-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    .export-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #16a34a;
        color: #fff;
        padding: 0.55rem 1.1rem;
        border-radius: 0.625rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        transition: background 0.2s, transform 0.15s;
    }
    .export-btn:hover {
        background: #15803d;
        color: #fff;
        transform: translateY(-1px);
    }

    .mapel-sub {
        display: block;
        font-size: 10px;
        font-weight: 400;
        color: rgba(255,255,255,0.5);
        margin-top: 2px;
    }

    @media (max-width: 767.98px) {
        .page-header-card { padding: 1.5rem !important; }
        .col-nama { left: 48px; min-width: 150px; }
        .col-nis { left: 198px; }
    }
</style>

<div class="container-fluid px-0 py-2">

    {{-- PAGE HEADER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header-card p-4 p-md-5">
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                    <div class="flex-grow-1">
                        <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill mb-3 d-inline-flex align-items-center gap-1"
                              style="font-size: 11px; font-weight: 600;">
                            <i class="fa-solid fa-clipboard-check me-1"></i>
                            Rekap Nilai — Wali Kelas
                        </span>
                        <h1 class="fw-bold text-white mb-1" style="font-size: 1.75rem; letter-spacing: -0.5px;">
                            Rekap Nilai Kelas {{ optional($kelas)->nama_kelas ?? '-' }}
                        </h1>
                        <p class="text-white text-opacity-60 mb-0" style="font-size: 13px;">
                            {{ optional($kelas)->nama_tingkat ?? '' }}
                            &bull; Tahun Ajaran {{ optional($waliKelas->tahunAjaran)->nama_tahun_ajaran ?? '-' }}
                        </p>
                    </div>
                    <a href="{{ route('dashboard-guru.wali-kelas.rekap-nilai.export') }}" class="export-btn">
                        <i class="fa-solid fa-file-excel"></i>
                        Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    @if($allJenisUjian->isNotEmpty())
    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
        <label class="text-muted fw-medium" style="font-size: 13px;">Filter Jenis Ujian:</label>
        <form method="GET" action="{{ route('dashboard-guru.wali-kelas.rekap-nilai') }}" class="d-flex align-items-center gap-2">
            <select name="jenis_ujian" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                @foreach($allJenisUjian as $jenis)
                    <option value="{{ $jenis }}" {{ $jenisFilter === $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="rekap-card">
        @if($ujians->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-table-cells fa-2x mb-3 d-block opacity-25"></i>
                <p class="mb-0">Belum ada ujian yang tersedia untuk kelas ini.</p>
            </div>
        @else
        <div class="rekap-table-wrapper">
            <table class="rekap-table">
                <thead>
                    <tr>
                        <th class="sticky-col col-no">No</th>
                        <th class="sticky-col col-nama">Nama Siswa</th>
                        <th class="sticky-col col-nis">NIS</th>
                        @foreach($ujians as $ujian)
                        <th>
                            {{ $ujian->nama_ujian }}
                            <span class="mapel-sub">{{ $ujian->nama_mapel }}</span>
                        </th>
                        @endforeach
                        <th>Rata-rata</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $idx => $siswa)
                    @php
                        $nilaiSiswa = $nilaiData->get($siswa->id, collect())->keyBy('ujian_id');
                        $totalNilai = 0;
                        $countNilai = 0;
                    @endphp
                    <tr>
                        <td class="sticky-col col-no text-muted">{{ $idx + 1 }}</td>
                        <td class="sticky-col col-nama">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-student">{{ strtoupper(substr($siswa->nama, 0, 2)) }}</div>
                                <span class="fw-semibold text-dark" style="font-size: 13px;">{{ $siswa->nama }}</span>
                            </div>
                        </td>
                        <td class="sticky-col col-nis text-muted" style="font-size: 12px;">{{ $siswa->nis }}</td>

                        @foreach($ujians as $ujian)
                        @php
                            $record = $nilaiSiswa->get($ujian->id);
                            $nilai  = $record ? (float) $record->nilai_akhir : null;
                            if ($nilai !== null) {
                                $totalNilai += $nilai;
                                $countNilai++;
                            }
                        @endphp
                        <td>
                            @if($nilai !== null)
                                @if($nilai >= 80)
                                    <span class="score-chip score-high">{{ number_format($nilai, 0) }}</span>
                                @elseif($nilai >= 60)
                                    <span class="score-chip score-mid">{{ number_format($nilai, 0) }}</span>
                                @else
                                    <span class="score-chip score-low">{{ number_format($nilai, 0) }}</span>
                                @endif
                            @else
                                <span class="score-chip score-none">—</span>
                            @endif
                        </td>
                        @endforeach

                        {{-- Rata-rata --}}
                        <td>
                            @if($countNilai > 0)
                                @php $avg = round($totalNilai / $countNilai, 1); @endphp
                                <span class="avg-chip">{{ $avg }}</span>
                            @else
                                <span class="score-chip score-none">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 4 + $ujians->count() }}" class="text-center py-5 text-muted">
                            Tidak ada data siswa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- LEGEND --}}
        <div class="px-4 py-3 border-top d-flex align-items-center gap-3 flex-wrap" style="border-color: #f1f5f9 !important; font-size: 12px; color: #64748b;">
            <span><span class="score-chip score-high me-1">—</span> ≥ 80 (Baik)</span>
            <span><span class="score-chip score-mid me-1">—</span> 60–79 (Cukup)</span>
            <span><span class="score-chip score-low me-1">—</span> &lt; 60 (Kurang)</span>
            <span><span class="score-chip score-none me-1">—</span> Belum mengikuti</span>
        </div>
        @endif
    </div>

</div>
@endsection
