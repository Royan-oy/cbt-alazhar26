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
            color: #0f172a;
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
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
        }

        .card-header {
            background: #0f172a;
            color: #ffffff;
            padding: 7px 10px;
            text-align: center;
            border-bottom: 2px solid #0ea5e9;
        }

        .card-header .school-name {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #38bdf8;
            text-transform: uppercase;
            margin: 0;
        }

        .card-header .card-title {
            font-size: 10px;
            font-weight: 800;
            margin: 2px 0 0 0;
            letter-spacing: 0.3px;
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
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
            background: #f8fafc;
            text-align: center;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            font-size: 7px;
            color: #94a3b8;
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
            color: #64748b;
            width: 62px;
            font-weight: 600;
        }

        .info-sep {
            width: 8px;
            color: #94a3b8;
        }

        .info-val {
            font-weight: 700;
            color: #0f172a;
        }

        .cred-box {
            background: #f0f9ff;
            border: 1px dashed #bae6fd;
            border-radius: 6px;
            padding: 4px 6px;
            margin-top: 4px;
        }

        .cred-table {
            width: 100%;
            font-size: 9.5px;
        }

        .cred-label {
            color: #0369a1;
            font-weight: 700;
            width: 62px;
        }

        .cred-val {
            font-weight: 800;
            color: #0284c7;
            font-family: monospace;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .card-footer {
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            padding: 4px 8px;
            font-size: 7.5px;
            color: #64748b;
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
                    <p class="school-name">SEKOLAH ISLAM AL AZHAR PEKALONGAN</p>
                    <h4 class="card-title">KARTU PESERTA UJIAN CBT</h4>
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
                                <td style="width: 8px; color: #0284c7;">:</td>
                                <td class="cred-val">{{ $siswa->nis }}</td>
                            </tr>
                            <tr>
                                <td class="cred-label">Password</td>
                                <td style="width: 8px; color: #0284c7;">:</td>
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
