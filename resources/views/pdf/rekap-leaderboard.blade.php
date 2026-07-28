<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Leaderboard Kelas - {{ $kelas->nama_kelas }}</title>
    <style>
        @page {
            margin: 1.2cm 1.2cm 1.2cm 1.2cm;
            size: portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.4;
        }
        /* Kop Surat Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo {
            width: 60px;
            height: auto;
        }
        .school-info {
            text-align: center;
            padding: 0 5px;
        }
        .school-name {
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .sub-school {
            font-size: 11pt;
            font-weight: bold;
            color: #0284c7;
            margin-top: 1px;
        }
        .school-address {
            font-size: 8pt;
            color: #64748b;
        }
        .header-line {
            border: 0;
            border-top: 2px solid #0f172a;
            border-bottom: 1px solid #0f172a;
            height: 2px;
            margin-bottom: 15px;
        }
        /* Document Title */
        .doc-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }
        /* Meta Info Table */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
        }
        .meta-table td {
            padding: 6px 10px;
            font-size: 8.5pt;
        }
        .meta-label {
            font-weight: bold;
            color: #475569;
            width: 18%;
        }
        .meta-val {
            color: #0f172a;
            width: 32%;
        }
        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            border: 1px solid #0f172a;
            text-align: center;
        }
        .data-table td {
            font-size: 8.5pt;
            padding: 7px 10px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }
        .rank-row-1 { background-color: #fffbeb; }
        .rank-row-2 { background-color: #fafafa; }
        .rank-row-3 { background-color: #fff7ed; }
        
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .badge-tuntas { color: #166534; font-weight: bold; }
        .badge-kurang { color: #991b1b; font-weight: bold; }
        .badge-belum { color: #64748b; font-style: italic; }
        /* Signature Table */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            font-size: 8.5pt;
        }
        .sig-space {
            height: 55px;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
    </style>
</head>
<body>
    <!-- Header Kop Surat -->
    <table class="header-table">
        <tr>
            <td style="width: 65px; text-align: left;">
                @if(file_exists(public_path('img/logo-alazhar.png')))
                    <img src="{{ public_path('img/logo-alazhar.png') }}" class="logo" alt="Logo Al-Azhar">
                @endif
            </td>
            <td class="school-info">
                <div class="school-name">Sekolah Islam Al Azhar Pekalongan</div>
                <div class="sub-school">Laporan Rekapitulasi Leaderboard Kelas</div>
                <div class="school-address">Jl. Pelita II, Kelurahan Banyurip, Kecamatan Pekalongan Selatan, Kota Pekalongan, Jawa Tengah</div>
            </td>
            <td style="width: 65px; text-align: right;">
                @if(file_exists(public_path('img/sigma.png')))
                    <img src="{{ public_path('img/sigma.png') }}" class="logo" alt="Logo Sigma">
                @endif
            </td>
        </tr>
    </table>
    <hr class="header-line">
    <div class="doc-title">REKAP LEADERBOARD NILAI SISWA</div>
    <!-- Meta Info -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Kelas</td>
            <td class="meta-val">: {{ $kelas->nama_kelas }} ({{ $kelas->nama_tingkat }})</td>
            <td class="meta-label">Tahun Ajaran</td>
            <td class="meta-val">: {{ $activeTahunAjaran->nama_tahun }}</td>
        </tr>
        <tr>
            <td class="meta-label">Rata-Rata Kelas</td>
            <td class="meta-val">: {{ number_format($avgScores->avg(), 1) }}</td>
            <td class="meta-label">Total Siswa</td>
            <td class="meta-val">: {{ count($siswas) }} Siswa</td>
        </tr>
    </table>
    <!-- Leaderboard Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 18%;">NIS</th>
                <th style="width: 42%;">Nama Siswa</th>
                <th style="width: 14%;">Rata-Rata</th>
                <th style="width: 18%;">Peringkat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $idx => $siswa)
                @php
                    $avg = $avgScores->get($siswa->id);
                    $rank = $rankMap[$siswa->id] ?? null;
                    $rowClass = '';
                    if ($rank === 1) $rowClass = 'rank-row-1';
                    elseif ($rank === 2) $rowClass = 'rank-row-2';
                    elseif ($rank === 3) $rowClass = 'rank-row-3';
                @endphp
                <tr class="{{ $rowClass }}">
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $siswa->nis }}</td>
                    <td>{{ $siswa->nama }}</td>
                    <td class="text-center" style="font-weight: bold;">{{ $avg !== null ? $avg : '—' }}</td>
                    <td class="text-center">
                        @if($rank === 1)
                            🥇 Ke-1
                        @elseif($rank === 2)
                            🥈 Ke-2
                        @elseif($rank === 3)
                            🥉 Ke-3
                        @elseif($rank)
                            Ke-{{ $rank }}
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Signature Area -->
    <table class="signature-table">
        <tr>
            <td style="width: 50%;">
                Mengetahui,<br>
                Kepala Sekolah
                <div class="sig-space"></div>
                ..........................................
            </td>
            <td style="width: 50%;">
                Sleman, {{ now()->translatedFormat('d F Y') }}<br>
                Wali Kelas
                <div class="sig-space"></div>
                <span class="sig-name">{{ Auth::user()->name }}</span><br>
                NIP. ......................................
            </td>
        </tr>
    </table>
</body>
</html>