<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Nilai Ujian Kelas - {{ $kelas->nama_kelas }}</title>
    <style>
        @page {
            margin: 1.2cm 1.2cm 1.2cm 1.2cm;
            size: landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            color: #1e293b;
            line-height: 1.3;
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
        .school-info {
            text-align: center;
            padding-right: 60px;
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
            margin-bottom: 10px;
        }
        /* Document Title */
        .doc-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        /* Meta Info Table */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
        }
        .meta-table td {
            padding: 5px 10px;
            font-size: 8pt;
        }
        .meta-label {
            font-weight: bold;
            color: #475569;
            width: 15%;
        }
        .meta-val {
            color: #0f172a;
            width: 35%;
        }
        /* Matrix Data Table */
        .matrix-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .matrix-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 4px;
            border: 1px solid #0f172a;
            text-align: center;
        }
        .matrix-table td {
            font-size: 8pt;
            padding: 5px 4px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .badge-tuntas { color: #166534; font-weight: bold; }
        .badge-kurang { color: #991b1b; font-weight: bold; }
        .filter-info {
            font-size: 8pt;
            font-style: italic;
            color: #64748b;
            margin-bottom: 6px;
        }
        /* Signature Table */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            font-size: 8.5pt;
        }
        .sig-space {
            height: 50px;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <table class="header-table">
        <tr>
            <td style="width: 70px;">
                <span style="font-weight: bold; color: #0284c7; font-size: 18pt;">CBT</span>
            </td>
            <td class="school-info">
                <div class="school-name">SMP AL AZHAR 26</div>
                <div class="sub-school">REKAPITULASI MATRIKS NILAI KELAS - WALI KELAS</div>
                <div class="school-address">Jl. Lingkar Utara, Sendangadi, Mlati, Sleman, Yogyakarta</div>
            </td>
        </tr>
    </table>
    <div class="header-line"></div>

    <!-- JUDUL DOKUMEN -->
    <div class="doc-title">REKAPITULASI NILAI MATA PELAJARAN</div>

    <!-- METADATA KELAS & WALI KELAS -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Kelas:</td>
            <td class="meta-val"><strong>{{ $kelas->nama_kelas }} ({{ $kelas->nama_tingkat }})</strong></td>
            <td class="meta-label">Wali Kelas:</td>
            <td class="meta-val"><strong>{{ Auth::user()->name }}</strong></td>
        </tr>
        <tr>
            <td class="meta-label">Tahun Ajaran:</td>
            <td class="meta-val">{{ $activeTahunAjaran->nama_tahun ?? '-' }}</td>
            <td class="meta-label">Jenis Ujian:</td>
            <td class="meta-val"><strong>{{ $jenisFilter }}</strong></td>
        </tr>
    </table>

    <div class="filter-info">
        * Dokumen resmi berisi daftar seluruh siswa terdaftar di kelas.
    </div>

    <!-- TABEL MATRIKS NILAI -->
    <table class="matrix-table">
        <thead>
            <tr>
                <th style="width: 4%;">NO</th>
                <th style="width: 10%;">NIS</th>
                <th style="width: 25%;">NAMA SISWA</th>
                @foreach($mapels as $mapel)
                    <th>{{ strtoupper($mapel) }}</th>
                @endforeach
                <th style="width: 8%;">RATA-RATA</th>
                <th style="width: 10%;">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswas as $index => $siswa)
            @php
                $mapelScores = $matrixData->get($siswa->id, collect());
                $overallAvg  = $avgScores->get($siswa->id);
                $isTuntas    = $overallAvg !== null && $overallAvg >= 75;
            @endphp
            <tr style="background-color: {{ $index % 2 == 1 ? '#f8fafc' : '#ffffff' }};">
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $siswa->nis ?? '-' }}</td>
                <td class="text-left"><strong>{{ $siswa->nama }}</strong></td>
                
                @foreach($mapels as $mapel)
                @php
                    $mapelData = $mapelScores->get($mapel);
                    $mapelAvg  = $mapelData['avg'] ?? null;
                    $mapelKkm  = $mapelData['kkm'] ?? 75;
                @endphp
                <td class="text-center">
                    @if($mapelAvg !== null)
                        <span style="color: {{ $mapelAvg >= $mapelKkm ? '#166534' : '#991b1b' }}; font-weight: bold;">
                            {{ number_format($mapelAvg, 0) }}
                        </span>
                    @else
                        <span style="color: #94a3b8;">&mdash;</span>
                    @endif
                </td>
                @endforeach

                <td class="text-center" style="background-color: #f1f5f9;">
                    <strong>{{ $overallAvg !== null ? number_format($overallAvg, 1) : '-' }}</strong>
                </td>
                <td class="text-center">
                    @if($overallAvg !== null)
                        <span class="{{ $isTuntas ? 'badge-tuntas' : 'badge-kurang' }}">
                            {{ $isTuntas ? 'TUNTAS' : 'BELUM' }}
                        </span>
                    @else
                        <span style="color: #94a3b8;">&mdash;</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ count($mapels) + 5 }}" class="text-center" style="padding: 15px; color: #64748b;">
                    Tidak ada siswa terdaftar.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <table class="signature-table">
        <tr>
            <td style="width: 50%;">
                Mengetahui,<br>
                Kepala Sekolah
                <div class="sig-space"></div>
                <span class="sig-name">( ________________________ )</span>
            </td>
            <td style="width: 50%;">
                Sleman, {{ now()->translatedFormat('d F Y') }}<br>
                Wali Kelas {{ $kelas->nama_kelas }},
                <div class="sig-space"></div>
                <span class="sig-name">{{ Auth::user()->name }}</span><br>
                <span style="font-size: 8pt; color: #64748b;">NIP. ......................................</span>
            </td>
        </tr>
    </table>

</body>
</html>
