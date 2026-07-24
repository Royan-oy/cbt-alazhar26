@extends('layouts.app')

@section('title', 'Peserta Ujian & Nilai - Guru Mapel')

@section('content')
<style>
    .gradebook {
        --ink-900: #0f172a;
        --ink-600: #64748b;
        --paper: #f8fafc;
        --surface: #ffffff;
        --line: #e2e8f0;
        --accent: #1B84FF;
        --accent-dark: #156ce6;
        --gold: #F6C000;
        --gold-bg: #fffbeb;
        --good: #17C653;
        --good-bg: #f0fdf4;
        --warn: #F6C000;
        --warn-bg: #fffbeb;
        --bad: #F8285A;
        --bad-bg: #fef2f2;

        font-family: inherit;
        color: var(--ink-900);
        background: var(--paper);
        padding: 1.5rem;
        border-radius: 1rem;
    }
    .gradebook * { box-sizing: border-box; }

    /* Back link */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--ink-600);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 1.25rem;
        transition: color 0.15s;
    }
    .back-link:hover { color: var(--accent); }

    /* Exam ticket header */
    .exam-ticket {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    .ticket-main {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.5rem 1.75rem;
    }
    .ticket-icon {
        width: 54px; height: 54px;
        flex-shrink: 0;
        background: var(--ink-900);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        border-radius: 0.75rem;
        font-size: 1.4rem;
    }
    .ticket-title {
        font-weight: 700;
        font-size: 1.5rem;
        line-height: 1.2;
        margin: 0 0 0.3rem;
        color: var(--ink-900);
    }
    .ticket-meta {
        margin: 0;
        font-size: 0.85rem;
        color: var(--ink-600);
        display: flex;
        flex-wrap: wrap;
        gap: 0 1rem;
    }
    .ticket-meta span { display: inline-flex; align-items: center; gap: 0.35rem; }

    /* Perforated divider - signature detail */
    .ticket-perf {
        border-top: 1.5px dashed var(--line);
        position: relative;
        margin: 0 1.75rem;
    }
    .ticket-perf::before, .ticket-perf::after {
        content: "";
        position: absolute;
        top: -8px;
        width: 16px; height: 16px;
        background: var(--paper);
        border-radius: 50%;
    }
    .ticket-perf::before { left: -25px; }
    .ticket-perf::after { right: -25px; }

    .ticket-tally {
        display: flex;
        padding: 1.1rem 1.75rem;
        gap: 2.25rem;
        flex-wrap: wrap;
    }
    .tally-item { display: flex; flex-direction: column; }
    .tally-num {
        font-weight: 700;
        font-size: 1.5rem;
        line-height: 1;
        color: var(--ink-900);
    }
    .tally-label {
        font-size: 0.75rem;
        color: var(--ink-600);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 0.35rem;
        font-weight: 600;
    }
    .tally-item.is-alert .tally-num { color: var(--bad); }
    .tally-item.is-good .tally-num { color: var(--good); }

    /* Body layout: class rail + ledger panel */
    .gradebook-body {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 1.5rem;
        align-items: start;
    }
    @media (max-width: 767px) {
        .gradebook-body { grid-template-columns: 1fr; }
    }

    .class-rail {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 1rem;
        padding: 0.75rem;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        position: sticky;
        top: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    @media (max-width: 767px) {
        .class-rail {
            flex-direction: row;
            overflow-x: auto;
            position: static;
        }
    }
    .rail-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--ink-600);
        padding: 0.5rem 0.6rem 0.3rem;
    }
    @media (max-width: 767px) { .rail-title { display: none; } }
    .rail-tab {
        border: none;
        background: transparent;
        text-align: left;
        padding: 0.6rem 0.8rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--ink-900);
        cursor: pointer;
        white-space: nowrap;
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
    }
    .rail-tab:hover { background: var(--paper); color: var(--accent); }
    .rail-tab.active {
        background: var(--accent) !important;
        border-left-color: var(--accent-dark);
        color: #ffffff !important;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(27, 132, 255, 0.25);
    }

    .ledger-panel { min-width: 0; }

    .ledger-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 0.9rem;
    }
    .ledger-toolbar h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--ink-900);
        margin: 0;
    }
    .search-box {
        position: relative;
    }
    .search-box input {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem 0.5rem 2.1rem;
        font-size: 0.85rem;
        width: 260px;
        max-width: 100%;
        color: var(--ink-900);
    }
    .search-box input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(27, 132, 255, 0.15);
    }
    .search-box i {
        position: absolute;
        left: 0.75rem; top: 50%;
        transform: translateY(-50%);
        color: var(--ink-600);
        font-size: 0.85rem;
    }

    .ledger-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    .no-result {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--ink-600);
        font-size: 0.875rem;
    }

    .ledger-table { width: 100%; border-collapse: collapse; }
    .ledger-table thead th {
        background: var(--paper);
        color: var(--ink-600);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid var(--line);
        white-space: nowrap;
        text-align: left;
    }
    .ledger-table thead th.num,
    .ledger-table tbody td.num { text-align: right; }

    .ledger-table tbody td {
        padding: 0.9rem 1.25rem;
        font-size: 0.875rem;
        border-bottom: 1px solid var(--paper);
        vertical-align: middle;
    }
    .ledger-table tbody tr:last-child td { border-bottom: none; }
    .ledger-table tbody tr:nth-child(even) { background: #fafafa; }
    .ledger-table tbody tr:hover { background: #f1f5f9; }

    .idx { font-weight: 600; color: var(--ink-600); font-size: 0.85rem; }

    .stu-row { display: flex; align-items: center; gap: 0.65rem; }
    .stu-stamp {
        width: 34px; height: 34px;
        flex-shrink: 0;
        background: var(--ink-900);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        border-radius: 0.4rem;
    }
    .stu-name { font-weight: 600; font-size: 0.9rem; color: var(--ink-900); }
    .stu-sub {
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 0.75rem; color: var(--ink-600); margin-top: 0.15rem;
    }
    .stu-class {
        background: var(--paper);
        border: 1px solid var(--line);
        padding: 0.1rem 0.4rem;
        border-radius: 0.25rem;
        font-weight: 600;
    }

    .tally-marks {
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.6rem;
        border-radius: 0.4rem;
    }
    .tally-marks.n0 { color: var(--ink-600); background: var(--paper); border: 1px solid var(--line); }
    .tally-marks.n1 { color: #ffffff; background: var(--warn); }
    .tally-marks.n2 { color: #ffffff; background: var(--bad); }

    .score-mono {
        font-weight: 700;
        font-size: 0.875rem;
        padding: 4px 12px;
        border-radius: 6px;
        display: inline-block;
        color: #ffffff;
        min-width: 48px;
        text-align: center;
    }
    .score-mono.pass { background-color: var(--good); }
    .score-mono.fail { background-color: var(--bad); }
    .score-pending {
        display: block;
        margin-top: 0.35rem;
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--warn);
    }
    .score-pending i { margin-right: 0.25rem; }

    .action-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.9rem;
        border-radius: 99px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .action-link.pending {
        background: var(--warn);
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(246, 192, 0, 0.2);
    }
    .action-link.done {
        background: var(--accent);
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(27, 132, 255, 0.2);
    }
    .action-link:hover { opacity: 0.9; transform: translateY(-1px); }
    .action-muted { font-size: 0.8rem; font-weight: 500; color: var(--ink-600); }

    .alert-box {
        border: 1px solid var(--good);
        background: var(--good);
        color: #ffffff;
        border-radius: 0.6rem;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex; align-items: center; gap: 0.5rem;
        margin-bottom: 1.25rem;
    }
</style>

<div class="gradebook">

    <a href="{{ route('dashboard-guru.nilai-siswa.index') }}" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Ujian
    </a>

    {{-- EXAM TICKET --}}
    <div class="exam-ticket">
        <div class="ticket-main">
            <div class="ticket-icon"><i class="fa-solid fa-users-viewfinder"></i></div>
            <div>
                <h1 class="ticket-title">{{ $ujian->nama_ujian }}</h1>
                <p class="ticket-meta">
                    <span><i class="fa-solid fa-book-open"></i> {{ $ujian->nama_mapel }}</span>
                    <span><i class="fa-solid fa-star"></i> KKM {{ $ujian->kkm ?? 75 }}</span>
                    <span><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('d M Y') }}</span>
                </p>
            </div>
        </div>
        <div class="ticket-perf"></div>
        @php
            $totalSelesai = $pesertas->where('status', 'selesai')->count();
            $totalBelumDikoreksi = $pesertas->where('belum_dikoreksi', '>', 0)->count();
        @endphp
        <div class="ticket-tally">
            <div class="tally-item">
                <span class="tally-num">{{ $pesertas->count() }}</span>
                <span class="tally-label">Total Peserta</span>
            </div>
            <div class="tally-item is-good">
                <span class="tally-num">{{ $totalSelesai }}</span>
                <span class="tally-label">Selesai Mengerjakan</span>
            </div>
            <div class="tally-item {{ $totalBelumDikoreksi > 0 ? 'is-alert' : '' }}">
                <span class="tally-num">{{ $totalBelumDikoreksi }}</span>
                <span class="tally-label">Butuh Koreksi Manual</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-box"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
    @endif

    <div class="gradebook-body">

        {{-- CLASS RAIL --}}
        <aside class="class-rail">
            <div class="rail-title">Kelas</div>
            <button type="button" class="rail-tab active" data-kelas-id="all">Semua Kelas</button>
            @foreach($kelasList as $k)
                <button type="button" class="rail-tab" data-kelas-id="{{ $k->id }}">{{ $k->nama_kelas }}</button>
            @endforeach
        </aside>

        {{-- LEDGER --}}
        <div class="ledger-panel">
            <div class="ledger-toolbar">
                <h2>Daftar Peserta</h2>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" id="searchSiswa" placeholder="Cari nama atau NIS siswa...">
                </div>
            </div>

            <div class="ledger-card">
                <div class="no-result" id="noResult" style="display: none;">
                    <i class="fa-solid fa-search fa-2x mb-2 d-block opacity-25"></i>
                    Tidak ada siswa yang cocok dengan pencarian atau filter.
                </div>
                <div class="table-responsive">
                    <table class="ledger-table">
                        <thead>
                            <tr>
                                <th style="width: 44px;">No</th>
                                <th>Nama Siswa</th>
                                <th>Waktu Selesai</th>
                                <th>Pelanggaran</th>
                                <th class="num">Nilai Akhir</th>
                                <th class="num">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesertas as $i => $p)
                            <tr class="peserta-row" data-kelas-id="{{ $p->kelas_id }}">
                                <td class="idx">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="stu-row">
                                        <div class="stu-stamp">{{ strtoupper(substr($p->nama_siswa, 0, 2)) }}</div>
                                        <div>
                                            <div class="stu-name">{{ $p->nama_siswa }}</div>
                                            <div class="stu-sub">
                                                <span>NIS {{ $p->nis ?? '-' }}</span>
                                                <span class="stu-class">{{ $p->nama_kelas ?? 'Tanpa Kelas' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="idx">
                                    {{ $p->waktu_kumpul ? \Carbon\Carbon::parse($p->waktu_kumpul)->format('d M Y, H:i') : '-' }}
                                </td>
                                <td>
                                    @if($p->violation_count == 2)
                                        <span class="tally-marks n2"><i class="fa-solid fa-triangle-exclamation"></i> || {{ $p->violation_count }}x</span>
                                    @elseif($p->violation_count == 1)
                                        <span class="tally-marks n1"><i class="fa-solid fa-triangle-exclamation"></i> | {{ $p->violation_count }}x</span>
                                    @else
                                        <span class="tally-marks n0">— 0x</span>
                                    @endif
                                </td>
                                <td class="num">
                                    @if($p->status === 'selesai')
                                        @php
                                            $nilai = (float) $p->nilai_akhir;
                                            $kkm = (float) ($ujian->kkm ?? 75);
                                        @endphp
                                        <span class="score-mono {{ $nilai >= $kkm ? 'pass' : 'fail' }}">{{ number_format($nilai, 0) }}</span>
                                        @if($p->belum_dikoreksi > 0)
                                            <span class="score-pending" title="{{ $p->belum_dikoreksi }} jawaban essay belum dinilai">
                                                <i class="fa-solid fa-triangle-exclamation"></i>Menunggu Koreksi
                                            </span>
                                        @endif
                                    @else
                                        <span class="idx">—</span>
                                    @endif
                                </td>
                                <td class="num">
                                    @if($p->status === 'selesai')
                                        @if($p->belum_dikoreksi > 0)
                                            <a href="{{ route('dashboard-guru.nilai-siswa.koreksi', ['ujian' => $ujian->id, 'siswa' => $p->siswa_id]) }}"
                                               class="action-link pending">
                                                <i class="fa-solid fa-highlighter"></i> Koreksi
                                            </a>
                                        @else
                                            <a href="{{ route('dashboard-guru.nilai-siswa.koreksi', ['ujian' => $ujian->id, 'siswa' => $p->siswa_id]) }}"
                                               class="action-link done">
                                                <i class="fa-solid fa-eye"></i> Detail
                                            </a>
                                        @endif
                                    @else
                                        <span class="action-muted">Belum Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center py-5" style="color: var(--ink-600);">
                                        <i class="f-solid fa-users-slash fa-2x mb-3 d-block opacity-25"></i>
                                        Belum ada siswa yang terdaftar untuk ujian ini.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const railTabs = document.querySelectorAll('.rail-tab');
    const searchSiswa = document.getElementById('searchSiswa');
    const rows = document.querySelectorAll('.peserta-row');
    const noResult = document.getElementById('noResult');
    const table = document.querySelector('.ledger-table');
    let activeKelas = 'all';

    function filterTable() {
        const searchQuery = searchSiswa ? searchSiswa.value.toLowerCase().trim() : '';
        let visibleCount = 0;

        rows.forEach(row => {
            const rowKelasId = row.getAttribute('data-kelas-id');
            const textContent = row.textContent.toLowerCase();

            const matchKelas = (activeKelas === 'all' || rowKelasId === activeKelas);
            const matchSearch = (searchQuery === '' || textContent.includes(searchQuery));

            if (matchKelas && matchSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (noResult && table) {
            if (rows.length > 0 && visibleCount === 0) {
                noResult.style.display = 'block';
                table.style.display = 'none';
            } else {
                noResult.style.display = 'none';
                table.style.display = '';
            }
        }
    }

    railTabs.forEach(tab => {
        tab.addEventListener('click', function () {
            railTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            activeKelas = this.getAttribute('data-kelas-id');
            filterTable();
        });
    });

    if (searchSiswa) searchSiswa.addEventListener('input', filterTable);
});
</script>
@endsection