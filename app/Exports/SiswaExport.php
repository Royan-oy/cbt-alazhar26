<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * Filter yang dibawa dari halaman index (search, jenjang, kelas).
     */
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $isAdminJenjang = Auth::user()->role == 'admin_jenjang';
        $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;

        return Siswa::with(['kelasAktif.kelas.tingkat.jenjang'])
            ->when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->whereHas('kelasAktif.kelas.tingkat', function ($q) use ($jenjangAdmin) {
                    $q->where('jenjang_id', $jenjangAdmin);
                });
            })
            ->when(!empty($this->filters['jenjang']) && !$isAdminJenjang, function ($query) {
                $query->whereHas('kelasAktif.kelas.tingkat', function ($q) {
                    $q->where('jenjang_id', $this->filters['jenjang']);
                });
            })
            ->when(!empty($this->filters['kelas']), function ($query) {
                $query->whereHas('kelasAktif', function ($q) {
                    $q->where('kelas_id', $this->filters['kelas']);
                });
            })
            ->when(!empty($this->filters['search']), function ($query) {
                $search = $this->filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('nis', 'like', '%' . $search . '%')
                      ->orWhere('nisn', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('nama', 'asc')
            ->get();
    }

    /**
     * Header kolom PERSIS sama dengan yang dipakai SiswaImport,
     * supaya file hasil export ini bisa langsung diimport ulang
     * (misal setelah kolom 'kelas' diubah untuk kenaikan kelas).
     */
    public function headings(): array
    {
        return ['nama', 'nis', 'nisn', 'password', 'jenjang', 'tingkat', 'kelas'];
    }

    public function map($siswa): array
    {
        $kelas = optional($siswa->kelasAktif)->kelas;
        $tingkat = optional($kelas)->tingkat;

        return [
            $siswa->nama,
            $siswa->nis,
            $siswa->nisn,
            '', // password sengaja dikosongkan (tersimpan ter-hash, tidak bisa & tidak perlu diekspor)
            optional(optional($tingkat)->jenjang)->nama_jenjang ?? '',
            optional($tingkat)->nama_tingkat ?? '',
            optional($kelas)->nama_kelas ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:G1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('0F172A');

        return [];
    }
}