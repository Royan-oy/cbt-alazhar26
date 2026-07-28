<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Nilai Kelas - {{ $kelas->nama_kelas }}</title>
    <style>
        @page {
            margin: 1.2cm 1.2cm 1.2cm 1.2cm;
            size: landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9pt;
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
            padding: 6px 10px;
        }
        .meta-table td {
            padding: 3px 6px;
            font-size: 8.5pt;
        }
        .meta-label {
            font-weight: bold;
            color: #475569;
            width: 12%;
        }
        .meta-val {
            color: #0f172a;
            width: 38%;
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
            font-size: 8pt;
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
            <td style="width: 65px; text-align: left;">
                @if(file_exists(public_path('img/logo-alazhar.png')))
                    <img src="{{ public_path('img/logo-alazhar.png') }}" class="logo" alt="Logo Al-Azhar">
                @endif
            </td>
            <td class="school-info">
                <div class="school-name">Sekolah Islam Al Azhar Pekalongan</div>
                <div class="sub-school">REKAPITULASI MATRIKS NILAI KELAS - WALI KELAS</div>
                <div class="school-address">Jl. Pelita II, Kelurahan Banyurip, Kecamatan Pekalongan Selatan, Kota Pekalongan, Jawa Tengah</div>
            </td>
            <td style="width: 65px; text-align: right;">
                @if(file_exists(public_path('img/sigma.png')))
                    <img src="{{ public_path('img/sigma.png') }}" class="logo" alt="Logo Sigma">
                @endif
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
            <td class="meta-val"><strong>{{ $kelas->nama_kelas }}</strong></td>
            <td class="meta-label">Wali Kelas:</td>
            <td class="meta-val"><strong>{{ Auth::user()->nama }}</strong></td>
        </tr>
        <tr>
            <td class="meta-label">Tahun Ajaran:</td>
            <td class="meta-val">{{ $activeTahunAjaran->nama_tahun ?? '-' }}</td>
            <td class="meta-label">Jumlah Siswa:</td>
            <td class="meta-val">{{ count($siswas) }} Siswa Terdaftar</td>
        </tr>
    </table>

    @if($jenisFilter)
    <div class="filter-info">
        * Jenis Ujian: <strong>{{ $jenisFilter }}</strong> &mdash; Dokumen resmi berisi seluruh siswa terdaftar di kelas.
    </div>
    @endif

    <!-- TABEL MATRIKS NILAI -->
    <table class="matrix-table">
        <thead>
            <tr>
                <th style="width: 3%;">NO</th>
                <th style="width: 8%;">NIS</th>
                <th style="width: 22%;">NAMA SISWA</th>
                @foreach($mapels as $mapel)
                    <th>{{ strtoupper($mapel) }}</th>
                @endforeach
                <th style="width: 7%;">RATA2</th>
                <th style="width: 8%;">STATUS</th>
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
                    Tidak ada siswa yang sesuai kriteria filter.
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
                Kepala Sekolah Islam Al Azhar Pekalongan
                <div class="sig-space"></div>
                <span class="sig-name">( ________________________ )</span>
            </td>
            <td style="width: 50%;">
                Pekalongan, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                Wali Kelas {{ $kelas->nama_kelas }},
                <div class="sig-space"></div>
                <span class="sig-name">{{ Auth::user()->nama }}</span><br>
                <span style="font-size: 8pt; color: #64748b;">NIP. {{ Auth::user()->guru->nip ?? '-' }}</span>
            </td>
        </tr>
    </table>

</body>
</html>
