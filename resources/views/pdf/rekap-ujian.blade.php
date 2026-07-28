<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Ujian - {{ $ujian->nama_ujian }}</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11pt;
            color: #1e293b;
            line-height: 1.4;
        }
        /* Kop Surat Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo {
            width: 70px;
            height: auto;
        }
        .school-info {
            text-align: center;
            padding: 0 5px;
        }
        .school-name {
            font-size: 15pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .sub-school {
            font-size: 12pt;
            font-weight: bold;
            color: #0284c7;
            margin-top: 2px;
        }
        .school-address {
            font-size: 8.5pt;
            color: #64748b;
            margin-top: 3px;
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
            font-size: 13pt;
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
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 12px;
        }
        .meta-table td {
            padding: 4px 6px;
            font-size: 9.5pt;
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
        /* Summary Cards Table */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .summary-box {
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            text-align: center;
            padding: 8px;
            border-radius: 4px;
        }
        .summary-num {
            font-size: 13pt;
            font-weight: bold;
            color: #0f172a;
        }
        .summary-lbl {
            font-size: 8pt;
            color: #64748b;
            text-transform: uppercase;
            margin-top: 2px;
        }
        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 6px;
            border: 1px solid #0f172a;
            text-align: center;
        }
        .data-table td {
            font-size: 9pt;
            padding: 6px 6px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .badge-tuntas {
            color: #166534;
            font-weight: bold;
        }
        .badge-remidi {
            color: #991b1b;
            font-weight: bold;
        }
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
            font-size: 9.5pt;
        }
        .sig-space {
            height: 60px;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }
        .filter-info {
            font-size: 8.5pt;
            font-style: italic;
            color: #64748b;
            margin-bottom: 8px;
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
                <div class="sub-school">CBT SMART ONLINE - LAPORAN HASIL UJIAN</div>
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
    <div class="doc-title">DAFTAR NILAI DAN HASIL UJIAN SISWA</div>

    <!-- METADATA UJIAN -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Mata Pelajaran:</td>
            <td class="meta-val"><strong>{{ $ujian->nama_mapel }}</strong></td>
            <td class="meta-label">Nama Ujian:</td>
            <td class="meta-val">{{ $ujian->nama_ujian }}</td>
        </tr>
        <tr>
            <td class="meta-label">Jenis Ujian:</td>
            <td class="meta-val">{{ $ujian->nama_jenis_ujian ?? '-' }}</td>
            <td class="meta-label">KKM:</td>
            <td class="meta-val"><strong>{{ $ujian->kkm ?? 75 }}</strong></td>
        </tr>
        <tr>
            <td class="meta-label">Tahun Ajaran:</td>
            <td class="meta-val">{{ $ujian->nama_tahun ?? '-' }}</td>
            <td class="meta-label">Waktu Ujian:</td>
            <td class="meta-val">{{ date('d/m/Y H:i', strtotime($ujian->waktu_mulai)) }}</td>
        </tr>
    </table>

    @if($kelasFilterName || $searchQuery)
    <div class="filter-info">
        * Filter Aktif &mdash; 
        @if($kelasFilterName) Kelas: <strong>{{ $kelasFilterName }}</strong> @endif
        @if($kelasFilterName && $searchQuery) | @endif
        @if($searchQuery) Pencarian: <strong>"{{ $searchQuery }}"</strong> @endif
    </div>
    @endif

    <!-- RINGKASAN STATISTIK -->
    <table class="summary-table">
        <tr>
            <td style="width: 25%; padding-right: 5px;">
                <div class="summary-box">
                    <div class="summary-num">{{ count($pesertas) }}</div>
                    <div class="summary-lbl">Total Peserta</div>
                </div>
            </td>
            <td style="width: 25%; padding-left: 5px; padding-right: 5px;">
                <div class="summary-box">
                    <div class="summary-num">{{ number_format($avgScore, 1) }}</div>
                    <div class="summary-lbl">Rata-Rata Nilai</div>
                </div>
            </td>
            <td style="width: 25%; padding-left: 5px; padding-right: 5px;">
                <div class="summary-box">
                    <div class="summary-num" style="color: #166534;">{{ $maxScore }}</div>
                    <div class="summary-lbl">Nilai Tertinggi</div>
                </div>
            </td>
            <td style="width: 25%; padding-left: 5px;">
                <div class="summary-box">
                    <div class="summary-num" style="color: #991b1b;">{{ $minScore }}</div>
                    <div class="summary-lbl">Nilai Terendah</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- TABEL HASIL SISWA -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">NO</th>
                <th style="width: 15%;">NIS</th>
                <th style="width: 35%;">NAMA SISWA</th>
                <th style="width: 15%;">KELAS</th>
                <th style="width: 15%;">NILAI AKHIR</th>
                <th style="width: 15%;">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesertas as $index => $p)
            @php
                $isTuntas = $p->nilai_akhir >= ($ujian->kkm ?? 75);
            @endphp
            <tr style="background-color: {{ $index % 2 == 1 ? '#f8fafc' : '#ffffff' }};">
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $p->nis ?? '-' }}</td>
                <td class="text-left"><strong>{{ $p->nama_siswa }}</strong></td>
                <td class="text-center">{{ $p->nama_kelas ?? '-' }}</td>
                <td class="text-center"><strong>{{ number_format($p->nilai_akhir, 1) }}</strong></td>
                <td class="text-center">
                    @if($p->status === 'selesai')
                        <span class="{{ $isTuntas ? 'badge-tuntas' : 'badge-remidi' }}">
                            {{ $isTuntas ? 'TUNTAS' : 'REMIDI' }}
                        </span>
                    @else
                        <span style="color: #64748b; font-style: italic;">{{ ucfirst($p->status) }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px; color: #64748b;">
                    Tidak ada data peserta ujian yang sesuai filter.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <table class="signature-table">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%;">
                Pekalongan, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                Guru Pengampu Mata Pelajaran,
                <div class="sig-space"></div>
                <span class="sig-name">{{ Auth::user()->nama }}</span><br>
                <span style="font-size: 8.5pt; color: #64748b;">NIP. {{ Auth::user()->guru->nip ?? '-' }}</span>
            </td>
        </tr>
    </table>

</body>
</html>
