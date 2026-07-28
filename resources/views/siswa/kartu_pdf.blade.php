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
            width: 48.5%;
            float: left;
            margin-right: 3%;
            margin-bottom: 12px;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .card-wrapper:nth-child(2n) {
            margin-right: 0;
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
            padding: 6px 8px;
            border-bottom: 2px solid #b08d57;
        }

        .card-header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .card-header-table td {
            vertical-align: middle;
        }

        .card-header-logo {
            width: 22px;
            height: auto;
        }

        .card-header .school-name {
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #c9ad7f;
            text-transform: uppercase;
            margin: 0;
            text-align: center;
        }

        .card-header .card-title {
            font-size: 9.5px;
            font-weight: 800;
            margin: 1px 0 0 0;
            letter-spacing: 0.3px;
            text-align: center;
        }

        .card-body {
            padding: 8px 10px;
        }

        .card-table {
            width: 100%;
            border-collapse: collapse;
        }

        .card-table td {
            vertical-align: top;
            padding: 2px 0;
        }

        .photo-cell {
            width: 50px;
            padding-right: 8px !important;
        }

        .photo-box {
            width: 48px;
            height: 58px;
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

        .photo-placeholder {
            font-size: 7px;
            color: #a8a29e;
            margin-top: 22px;
            font-weight: 700;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
        }

        .info-table td {
            padding: 1.5px 0;
        }

        .info-label {
            color: #78716c;
            width: 62px;
            font-weight: 600;
        }

        .info-sep {
            width: 8px;
            color: #a8a29e;
        }

        .info-val {
            font-weight: 700;
            color: #1f2430;
        }

        .cred-box {
            background: #faf8f4;
            border: 1px solid #e2d9c3;
            border-radius: 5px;
            padding: 4px 6px;
            margin-top: 4px;
        }

        .cred-table {
            width: 100%;
            font-size: 9.5px;
        }

        .cred-label {
            color: #8a6d3b;
            font-weight: 700;
            width: 62px;
        }

        .cred-val {
            font-weight: 800;
            color: #1f2430;
            font-family: monospace;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .card-footer {
            background: #faf9f7;
            border-top: 1px solid #ece9e4;
            padding: 4px 8px;
            font-size: 7.5px;
            color: #78716c;
            text-align: center;
        }

        .clear {
            clear: both;
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
                            <td style="width: 26px; text-align: left;">
                                @if(file_exists(public_path('img/logo-alazhar.png')))
                                    <img src="{{ public_path('img/logo-alazhar.png') }}" class="card-header-logo" alt="Logo">
                                @endif
                            </td>
                            <td>
                                <p class="school-name">SEKOLAH ISLAM AL AZHAR PEKALONGAN</p>
                                <h4 class="card-title">KARTU PESERTA UJIAN CBT</h4>
                            </td>
                            <td style="width: 26px; text-align: right;">
                                @if(file_exists(public_path('img/sigma.png')))
                                    <img src="{{ public_path('img/sigma.png') }}" class="card-header-logo" alt="Sigma">
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="card-body">
                    <table class="card-table">
                        <tr>
                            <td class="photo-cell">
                                <div class="photo-box">
                                    @if($siswa->foto && file_exists(public_path('storage/' . $siswa->foto)))
                                        <img src="{{ public_path('storage/' . $siswa->foto) }}" alt="Foto">
                                    @else
                                        <div class="photo-placeholder">FOTO</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <table class="info-table">
                                    <tr>
                                        <td class="info-label">Nama Siswa</td>
                                        <td class="info-sep">:</td>
                                        <td class="info-val">{{ Str::limit($siswa->nama, 22) }}</td>
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
                        </tr>
                    </table>

                    <div class="cred-box">
                        <table class="cred-table">
                            <tr>
                                <td class="cred-label">Username</td>
                                <td style="width: 8px; color: #8a6d3b;">:</td>
                                <td class="cred-val">{{ $siswa->nis }}</td>
                            </tr>
                            <tr>
                                <td class="cred-label">Password</td>
                                <td style="width: 8px; color: #8a6d3b;">:</td>
                                <td class="cred-val">{{ optional($siswa->user)->password_plain ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    Simpan kartu ini dengan baik. Rahasiakan username & password Anda.
                </div>
            </div>
        </div>

        @if(($loop->iteration % 2) == 0)
            <div class="clear"></div>
        @endif

        @endforeach
    </div>

</body>
</html>