<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Peserta Ujian CBT</title>
    <style>
        @page {
            margin: 12mm 10mm 12mm 10mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1f2430;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        .grid-container {
            width: 100%;
        }

        .card-wrapper {
            width: 100%;
            margin-bottom: 24px;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .card-box {
            border: 1px solid #d6d3d1;
            border-radius: 6px;
            overflow: hidden;
            background: #ffffff;
        }

        .card-header {
            background: #1f2430;
            color: #ffffff;
            padding: 8px 12px;
            border-bottom: 3px solid #0284c7;
            border-image: linear-gradient(135deg, #0284c7, #0369a1) 1;
        }

        .card-header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .card-header-table td {
            vertical-align: middle;
        }

        .card-header-logo {
            width: 32px;
            height: auto;
        }

        .card-header .school-name {
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #38bdf8;
            text-transform: uppercase;
            margin: 0 0 2px 0;
            text-align: center;
        }

        .card-header .card-title {
            font-size: 11px;
            font-weight: 800;
            margin: 0;
            letter-spacing: 0.5px;
            text-align: center;
        }

        .card-body {
            padding: 12px 14px;
        }

        .top-section-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .top-section-table td {
            vertical-align: top;
        }

        .photo-cell {
            width: 70px;
            padding-right: 12px !important;
        }

        .photo-box {
            width: 65px;
            height: 80px;
            border: 1px solid #e0ded9;
            border-radius: 5px;
            overflow: hidden;
            background: #faf9f7;
            text-align: center;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }

        .info-table td {
            padding: 2.5px 0;
        }

        .info-label {
            color: #78716c;
            width: 75px;
            font-weight: 600;
        }

        .info-sep {
            width: 10px;
            color: #a8a29e;
        }

        .info-val {
            font-weight: 700;
            color: #1f2430;
        }

        .cred-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 5px;
            padding: 6px 10px;
            width: 200px;
        }

        .cred-table {
            width: 100%;
            font-size: 10.5px;
        }

        .cred-label {
            color: #0369a1;
            font-weight: 700;
            width: 70px;
        }

        .cred-val {
            font-weight: 800;
            color: #1f2430;
            font-family: monospace;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        /* Schedule Table */
        .schedule-title {
            margin: 0 0 6px 0;
            font-size: 10.5px;
            font-weight: 800;
            color: #1f2430;
            border-bottom: 1px solid #e0ded9;
            padding-bottom: 4px;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
            margin-bottom: 4px;
        }

        .schedule-table th, .schedule-table td {
            border: 1px solid #d6d3d1;
            padding: 5px 6px;
            text-align: left;
            vertical-align: middle;
        }

        .schedule-table th {
            background-color: #f8f9fa;
            color: #334155;
            font-weight: 700;
        }

        .text-center {
            text-align: center !important;
        }

        .card-footer {
            background: #faf9f7;
            border-top: 1px solid #ece9e4;
            padding: 6px 10px;
            font-size: 8.5px;
            color: #78716c;
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <div class="grid-container">
        @foreach($siswas as $siswa)
        <div class="card-wrapper">
            <div class="card-box">
                <div class="card-header">
                    <table class="card-header-table">
                        <tr>
                            <td style="width: 40px; text-align: left;">
                                @if(file_exists(public_path('img/logo-alazhar.png')))
                                    <img src="{{ public_path('img/logo-alazhar.png') }}" class="card-header-logo" alt="Logo">
                                @endif
                            </td>
                            <td>
                                <p class="school-name">SEKOLAH ISLAM AL AZHAR PEKALONGAN</p>
                                <h4 class="card-title">KARTU PESERTA UJIAN CBT</h4>
                            </td>
                            <td style="width: 40px; text-align: right;">
                                @if(file_exists(public_path('img/sigma.png')))
                                    <img src="{{ public_path('img/sigma.png') }}" class="card-header-logo" alt="Sigma">
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="card-body">
                    <!-- Bagian Atas: Foto & Info -->
                    <table class="top-section-table">
                        <tr>
                            <td class="photo-cell">
                                <div class="photo-box">
                                    @if($siswa->foto && file_exists(public_path('storage/' . $siswa->foto)))
                                        <img src="{{ public_path('storage/' . $siswa->foto) }}" alt="Foto">
                                    @else
                                        <img src="data:image/svg+xml;base64,{{ base64_encode('<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 125\'><rect width=\'100\' height=\'125\' fill=\'#94a3b8\'/><circle cx=\'50\' cy=\'44\' r=\'26\' fill=\'#ffffff\'/><path d=\'M0,125 C0,72 100,72 100,125 Z\' fill=\'#ffffff\'/></svg>') }}" alt="Foto" style="width:100%;height:100%;object-fit:cover;">
                                    @endif
                                </div>
                            </td>
                            <td>
                                <table class="info-table">
                                    <tr>
                                        <td class="info-label">Nama Siswa</td>
                                        <td class="info-sep">:</td>
                                        <td class="info-val">{{ $siswa->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">NIS / NISN</td>
                                        <td class="info-sep">:</td>
                                        <td class="info-val">{{ $siswa->nis }} {{ $siswa->nisn ? '/ ' . $siswa->nisn : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Kelas</td>
                                        <td class="info-sep">:</td>
                                        <td class="info-val">
                                            @if($siswa->kelasAktif && $siswa->kelasAktif->kelas)
                                                {{ optional($siswa->kelasAktif->kelas->tingkat)->nama_tingkat }} - {{ $siswa->kelasAktif->kelas->nama_kelas }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="text-align: right; vertical-align: top;">
                                <div class="cred-box" style="display: inline-block; text-align: left; margin-top: 0;">
                                    <table class="cred-table">
                                        <tr>
                                            <td class="cred-label">Username</td>
                                            <td style="width: 8px; color: #0369a1;">:</td>
                                            <td class="cred-val">{{ $siswa->nis }}</td>
                                        </tr>
                                        <tr>
                                            <td class="cred-label">Password</td>
                                            <td style="width: 8px; color: #0369a1;">:</td>
                                            <td class="cred-val">{{ optional($siswa->user)->password_plain ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <!-- Bagian Bawah: Jadwal Ujian -->
                    <h5 class="schedule-title">Jadwal Ujian</h5>
                    <table class="schedule-table">
                        <thead>
                            <tr>
                                <th style="width: 25%">Hari, Tanggal</th>
                                <th style="width: 20%">Waktu</th>
                                <th style="width: 40%">Mata Pelajaran</th>
                                <th style="width: 15%" class="text-center">Paraf Pengawas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($siswa->kelasAktif && $siswa->kelasAktif->kelas && $siswa->kelasAktif->kelas->ujians->count() > 0)
                                @foreach($siswa->kelasAktif->kelas->ujians as $ujian)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($ujian->waktu_mulai)->translatedFormat('l, d F Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($ujian->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }}</td>
                                        <td>{{ optional(optional($ujian->bankSoal)->mataPelajaran)->nama_mapel ?? '-' }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center" style="color: #78716c; font-style: italic;">Belum ada jadwal ujian yang ditugaskan untuk kelas ini.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                </div>

                <div class="card-footer">
                    Simpan kartu ini dengan baik. Rahasiakan username & password Anda selama ujian berlangsung.
                </div>
            </div>
        </div>

        @if(($loop->iteration % 2) == 0 && !$loop->last)
            <div class="page-break"></div>
        @endif

        @endforeach
    </div>

</body>
</html>