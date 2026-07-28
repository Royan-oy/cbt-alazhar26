<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transkrip Hasil Ujian - {{ $siswa->nama }}</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
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
            width: 65px;
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
            margin-top: 2px;
        }
        .school-address {
            font-size: 8pt;
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
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }
        /* Student & Exam Info Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }
        .info-table td {
            padding: 6px 10px;
            font-size: 9pt;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #475569;
            width: 18%;
        }
        .info-val {
            color: #0f172a;
            width: 32%;
        }
        /* Final Score Highlight Box */
        .score-box {
            background-color: #0f172a;
            color: #ffffff;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            margin-bottom: 15px;
        }
        .score-val {
            font-size: 20pt;
            font-weight: bold;
            color: #38bdf8;
        }
        .score-lbl {
            font-size: 8.5pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
        }
        /* Section Heading */
        .section-head {
            font-size: 10pt;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1.5px solid #cbd5e1;
            padding-bottom: 4px;
            margin-top: 15px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        /* Question Card Table */
        .q-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        .q-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 8.5pt;
            font-weight: bold;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }
        .q-table td {
            font-size: 9pt;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge-correct { color: #166534; font-weight: bold; }
        .badge-wrong { color: #991b1b; font-weight: bold; }

        /* Signature Table */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            font-size: 9pt;
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
                <div class="sub-school">LEMBAR HASIL & DETAIL JAWABAN SISWA</div>
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
    <div class="doc-title">TRANSKRIP UJIAN INDIVIDUAL</div>

    <!-- INFORMASI SISWA & UJIAN -->
    <table class="info-table">
        <tr>
            <td class="info-label">Nama Siswa:</td>
            <td class="info-val"><strong>{{ $siswa->nama }}</strong></td>
            <td class="info-label">Mata Pelajaran:</td>
            <td class="info-val"><strong>{{ $ujian->nama_mapel }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">NIS:</td>
            <td class="info-val">{{ $siswa->nis ?? '-' }}</td>
            <td class="info-label">Nama Ujian:</td>
            <td class="info-val">{{ $ujian->nama_ujian }}</td>
        </tr>
        <tr>
            <td class="info-label">Status Ujian:</td>
            <td class="info-val" style="text-transform: uppercase; font-weight: bold; color: #0284c7;">{{ $nilai->status }}</td>
            <td class="info-label">KKM / Target:</td>
            <td class="info-val"><strong>{{ $ujian->kkm ?? 75 }}</strong></td>
        </tr>
    </table>

    <!-- BANNER NILAI AKHIR -->
    <div class="score-box">
        <div class="score-val">{{ number_format($nilai->nilai_akhir, 1) }}</div>
        <div class="score-lbl">
            NILAI AKHIR &mdash; 
            @if($nilai->nilai_akhir >= ($ujian->kkm ?? 75))
                <span style="color: #4ade80; font-weight: bold;">TUNTAS</span>
            @else
                <span style="color: #f87171; font-weight: bold;">REMIDI</span>
            @endif
        </div>
    </div>

    <!-- RINGKASAN PILIHAN GANDA -->
    <div class="section-head">1. Ringkasan Pilihan Ganda</div>
    <table class="info-table">
        <tr>
            <td style="width: 25%; text-align: center;">Total Soal PG: <strong>{{ $total_soal_pg }}</strong></td>
            <td style="width: 25%; text-align: center;">Jawaban Benar: <strong style="color: #166534;">{{ $benar_pg }}</strong></td>
            <td style="width: 25%; text-align: center;">Jawaban Salah/Kosong: <strong style="color: #991b1b;">{{ $total_soal_pg - $benar_pg }}</strong></td>
            <td style="width: 25%; text-align: center;">Skor Terkumpul: <strong>{{ $skor_pg }}</strong></td>
        </tr>
    </table>

    <!-- RINCIAN JAWABAN ESSAY / ISIAN -->
    @if(count($jawabans) > 0)
    <div class="section-head">2. Detail Jawaban & Koreksi Essay / Isian</div>
    @foreach($jawabans as $index => $j)
    <table class="q-table">
        <thead>
            <tr>
                <th style="width: 70%;">Soal No. {{ $j->urutan ?? ($index + 1) }} ({{ ucfirst($j->jenis_soal) }})</th>
                <th style="width: 30%; text-align: right;">Bobot Maksimal: {{ $j->bobot }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2">
                    <div style="font-weight: bold; margin-bottom: 4px;">Pertanyaan:</div>
                    <div style="color: #334155; margin-bottom: 8px;">{!! strip_tags($j->teks_soal) !!}</div>

                    <div style="font-weight: bold; margin-bottom: 4px;">Jawaban Siswa:</div>
                    <div style="background-color: #f8fafc; padding: 6px; border: 1px dashed #cbd5e1; border-radius: 4px; color: #0f172a; margin-bottom: 6px;">
                        {{ $j->jawaban_text ?? '(Siswa tidak menjawab)' }}
                    </div>

                    <div style="text-align: right; font-size: 8.5pt;">
                        Skor Diberikan Guru: 
                        <strong style="font-size: 10pt; color: #0284c7;">{{ $j->nilai_jawaban ?? 0 }}</strong> / {{ $j->bobot }}
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach
    @endif

    <!-- TANDA TANGAN -->
    <table class="signature-table">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%;">
                Pekalongan, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                Guru Pengampu Mata Pelajaran,
                <div class="sig-space"></div>
                <span class="sig-name">{{ Auth::user()->nama }}</span><br>
                <span style="font-size: 8pt; color: #64748b;">NIP. {{ Auth::user()->guru->nip ?? '-' }}</span>
            </td>
        </tr>
    </table>

</body>
</html>
