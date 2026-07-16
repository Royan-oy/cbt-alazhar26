<?php

namespace App\Exports;

use App\Models\GuruMapel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class GuruMapelExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
     * Filter dari halaman index (search, jenjang, tahun_ajaran).
     */
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $user = Auth::user();
        $isAdminJenjang = $user->role == 'admin_jenjang';
        $jenjangAdmin = optional($user->admin)->jenjang_id;

        return GuruMapel::with(['guru.jenjang', 'mataPelajaran', 'kelas.tingkat', 'tahunAjaran'])
            ->when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->whereHas('guru', function ($q) use ($jenjangAdmin) {
                    $q->where('jenjang_id', $jenjangAdmin);
                });
            })
            ->when(!empty($this->filters['search']), function ($query) {
                $search = $this->filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->whereHas('guru', function ($guru) use ($search) {
                        $guru->where('nama', 'like', '%' . $search . '%');
                    })->orWhereHas('mataPelajaran', function ($mapel) use ($search) {
                        $mapel->where('nama_mapel', 'like', '%' . $search . '%');
                    });
                });
            })
            ->when(!empty($this->filters['jenjang']) && !$isAdminJenjang, function ($query) {
                $query->whereHas('guru', function ($q) {
                    $q->where('jenjang_id', $this->filters['jenjang']);
                });
            })
            ->when(!empty($this->filters['tahun_ajaran']), function ($query) {
                $query->where('tahun_ajaran_id', $this->filters['tahun_ajaran']);
            })
            ->orderByDesc('tahun_ajaran_id')
            ->orderBy('guru_id')
            ->get();
    }

    /**
     * Header PERSIS sama dengan yang dibaca GuruMapelImport,
     * supaya hasil export bisa langsung diimport ulang.
     */
    public function headings(): array
    {
        return ['nip_guru', 'nama_guru', 'jenjang', 'mata_pelajaran', 'kelas', 'tahun_ajaran', 'semester'];
    }

    public function map($item): array
    {
        // Gabungkan semua kelas jadi satu string dipisah koma:
        // "Kelas VII - VII A, Kelas VII - VII B"
        $kelasText = $item->kelas->map(function ($kelas) {
            return optional($kelas->tingkat)->nama_tingkat . ' - ' . $kelas->nama_kelas;
        })->implode(', ');

        return [
            optional($item->guru)->nip,
            optional($item->guru)->nama,
            optional(optional($item->guru)->jenjang)->nama_jenjang,
            optional($item->mataPelajaran)->nama_mapel,
            $kelasText,
            optional($item->tahunAjaran)->nama_tahun,
            optional($item->tahunAjaran)->semester,
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