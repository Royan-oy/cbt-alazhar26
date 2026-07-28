<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Nilai Siswa - {{ $siswa->nama }}</title>
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
            width: 15%;
        }
        .meta-val {
            color: #0f172a;
            width: 35%;
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
        .mapel-row {
            background-color: #f1f5f9;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .badge-tuntas { color: #166534; font-weight: bold; }
        .badge-kurang { color: #991b1b; font-weight: bold; }
        .badge-menunggu { color: #d97706; font-weight: bold; }
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
                <div class="sub-school">Laporan Hasil Ujian Siswa</div>
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
    <div class="doc-title">Rincian Hasil Ujian Kelas</div>
    <!-- Meta Info -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Nama Siswa</td>
            <td class="meta-val">: {{ $siswa->nama }}</td>
            <td class="meta-label">Kelas</td>
            <td class="meta-val">: {{ $kelas->nama_kelas }} ({{ $kelas->nama_tingkat }})</td>
        </tr>
        <tr>
            <td class="meta-label">NIS</td>
            <td class="meta-val">: {{ $siswa->nis }}</td>
            <td class="meta-label">Tahun Ajaran</td>
            <td class="meta-val">: {{ $activeTahunAjaran->nama_tahun }}</td>
        </tr>
        <tr>
            <td class="meta-label">Rata-Rata Nilai</td>
            <td class="meta-val">: <strong>{{ $rataRataKeseluruhan !== null ? $rataRataKeseluruhan : '—' }}</strong></td>
            <td class="meta-label">Status Ketuntasan</td>
            <td class="meta-val">: 
                @if($statusGlobal === 'tuntas')
                    <span class="badge-tuntas">TUNTAS</span>
                @elseif($statusGlobal === 'kurang')
                    <span class="badge-kurang">BELUM TUNTAS</span>
                @else
                    <span class="badge-belum">BELUM UJIAN</span>
                @endif
            </td>
        </tr>
    </table>
    <!-- Data Ujian Tabel -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 50%;">Mata Pelajaran / Nama Evaluasi</th>
                <th style="width: 15%;">Jenis Ujian</th>
                <th style="width: 10%;">KKM</th>
                <th style="width: 10%;">Nilai</th>
                <th style="width: 17%;">Status Koreksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mapelDetails as $mapelNama => $data)
                <!-- Mapel Header Row -->
                <tr class="mapel-row">
                    <td colspan="3">{{ $mapelNama }}</td>
                    <td class="text-center">{{ $data['avg'] !== null ? $data['avg'] : '—' }}</td>
                    <td class="text-center">
                        @if($data['status'] === 'tuntas')
                            <span class="badge-tuntas">Tuntas</span>
                        @elseif($data['status'] === 'kurang')
                            <span class="badge-kurang">Belum Tuntas</span>
                        @else
                            <span class="badge-belum">Belum Ujian</span>
                        @endif
                    </td>
                </tr>
                <!-- Ujian Records for this Mapel -->
                @foreach($data['details'] as $detail)
                    <tr>
                        <td style="padding-left: 25px; color: #475569;">{{ $detail['nama_ujian'] }}</td>
                        <td class="text-center" style="color: #64748b;">{{ $detail['jenis_ujian'] }}</td>
                        <td class="text-center" style="color: #64748b;">{{ $detail['kkm'] }}</td>
                        <td class="text-center">
                            {{ $detail['nilai'] !== null ? number_format($detail['nilai'], 0) : '—' }}
                        </td>
                        <td class="text-center">
                            @if($detail['nilai'] !== null)
                                @if(($detail['status_penilaian'] ?? 'selesai') === 'selesai')
                                    <span class="badge-tuntas">Sudah Dikoreksi</span>
                                @else
                                    <span class="badge-menunggu">Belum Dikoreksi</span>
                                @endif
                            @else
                                <span class="badge-belum">Belum Diikuti</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data ujian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Signature Area -->
    <table class="signature-table">
        <tr>
            <td style="width: 50%;">
                Mengetahui,<br>
                Orang Tua / Wali Siswa
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