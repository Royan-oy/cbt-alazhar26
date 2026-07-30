<table>
    <!-- JUDUL LAPORAN -->
    <tr>
        <td colspan="6" style="font-size: 14pt; font-weight: bold; text-align: center;">LAPORAN HASIL UJIAN SISWA</td>
    </tr>
    <tr>
        <td colspan="6" style="font-size: 11pt; text-align: center;">Sekolah Islam Al-Azhar Pekalongan</td>
    </tr>
    <tr></tr>

    <!-- METADATA UJIAN -->
    <tr>
        <td style="font-weight: bold; background-color: #f1f5f9;">Mata Pelajaran</td>
        <td>{{ $ujian->nama_mapel }}</td>
        <td style="font-weight: bold; background-color: #f1f5f9;">Nama Ujian</td>
        <td colspan="3">{{ $ujian->nama_ujian }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #f1f5f9;">Jenis Ujian</td>
        <td>{{ $ujian->nama_jenis_ujian ?? '-' }}</td>
        <td style="font-weight: bold; background-color: #f1f5f9;">KKM</td>
        <td colspan="3" style="text-align: left;">{{ $ujian->kkm ?? 75 }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; background-color: #f1f5f9;">Tahun Ajaran</td>
        <td>{{ $ujian->nama_tahun ?? '-' }}</td>
        <td style="font-weight: bold; background-color: #f1f5f9;">Waktu Ujian</td>
        <td colspan="3">{{ date('d/m/Y H:i', strtotime($ujian->waktu_mulai)) }}</td>
    </tr>
    <tr></tr>

    @if($kelasFilterName || $searchQuery)
    <tr>
        <td colspan="6" style="font-style: italic; color: #64748b;">
            * Filter Aktif: 
            @if($kelasFilterName) Kelas: {{ $kelasFilterName }} @endif
            @if($kelasFilterName && $searchQuery) | @endif
            @if($searchQuery) Pencarian: "{{ $searchQuery }}" @endif
        </td>
    </tr>
    <tr></tr>
    @endif

    <!-- RINGKASAN STATISTIK -->
    <tr>
        <td style="font-weight: bold; background-color: #e2e8f0; text-align: center;">Total Peserta</td>
        <td style="font-weight: bold; background-color: #e2e8f0; text-align: center;">Rata-Rata Nilai</td>
        <td style="font-weight: bold; background-color: #e2e8f0; text-align: center;">Nilai Tertinggi</td>
        <td style="font-weight: bold; background-color: #e2e8f0; text-align: center;">Nilai Terendah</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td style="text-align: center; font-weight: bold;">{{ count($pesertas) }}</td>
        <td style="text-align: center; font-weight: bold;">{{ number_format($avgScore, 1) }}</td>
        <td style="text-align: center; font-weight: bold; color: #166534;">{{ $maxScore }}</td>
        <td style="text-align: center; font-weight: bold; color: #991b1b;">{{ $minScore }}</td>
        <td colspan="2"></td>
    </tr>
    <tr></tr>

    <!-- TABEL UTAMA -->
    <thead>
        <tr>
            <th style="font-weight: bold; background-color: #0f172a; color: #ffffff; text-align: center; border: 1px solid #000000;">NO</th>
            <th style="font-weight: bold; background-color: #0f172a; color: #ffffff; text-align: center; border: 1px solid #000000;">NIS</th>
            <th style="font-weight: bold; background-color: #0f172a; color: #ffffff; text-align: left; border: 1px solid #000000;">NAMA SISWA</th>
            <th style="font-weight: bold; background-color: #0f172a; color: #ffffff; text-align: center; border: 1px solid #000000;">KELAS</th>
            <th style="font-weight: bold; background-color: #0f172a; color: #ffffff; text-align: center; border: 1px solid #000000;">NILAI AKHIR</th>
            <th style="font-weight: bold; background-color: #0f172a; color: #ffffff; text-align: center; border: 1px solid #000000;">KETERANGAN</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pesertas as $index => $p)
            @php
                $isSelesai = $p->status === 'selesai';
                $isTuntas = $isSelesai && ($p->nilai_akhir >= ($ujian->kkm ?? 75));
            @endphp
            <tr>
                <td style="text-align: center; border: 1px solid #cbd5e1;">{{ $index + 1 }}</td>
                <td style="text-align: center; border: 1px solid #cbd5e1;">'{{ $p->nis ?? '-' }}</td>
                <td style="text-align: left; border: 1px solid #cbd5e1;">{{ $p->nama_siswa }}</td>
                <td style="text-align: center; border: 1px solid #cbd5e1;">{{ $p->nama_kelas ?? '-' }}</td>
                <td style="text-align: center; font-weight: bold; border: 1px solid #cbd5e1;">
                    {{ $isSelesai ? number_format($p->nilai_akhir, 1) : '-' }}
                </td>
                <td style="text-align: center; font-weight: bold; border: 1px solid #cbd5e1; color: {{ $isSelesai ? ($isTuntas ? '#166534' : '#991b1b') : '#64748b' }};">
                    @if($isSelesai)
                        {{ $isTuntas ? 'TUNTAS' : 'REMIDI' }}
                    @else
                        {{ ucfirst($p->status) }}
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center; border: 1px solid #cbd5e1; color: #64748b;">
                    Tidak ada data peserta ujian yang sesuai filter.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
