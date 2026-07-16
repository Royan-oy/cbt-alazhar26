<?php

namespace App\Exports;

use App\Models\Guru;
use App\Models\Jenjang;
use App\Models\TahunAjaran;
use App\Models\WaliKelas;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WaliKelasExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
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

        $query = WaliKelas::with([
            'guru.jenjang',
            'kelas.tingkat',
            'tahunAjaran'
        ]);

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        if ($isAdminJenjang) {

            $query->whereHas('guru', function ($q) use ($jenjangAdmin) {

                $q->where('jenjang_id', $jenjangAdmin);

            });

        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['search'])) {

            $search = $this->filters['search'];

            $query->where(function ($q) use ($search) {

                $q->whereHas('guru', function ($guru) use ($search) {

                    $guru->where('nama', 'like', "%{$search}%");

                })

                ->orWhereHas('kelas', function ($kelas) use ($search) {

                    $kelas->where('nama_kelas', 'like', "%{$search}%");

                });

            });

        }

        /*
        |--------------------------------------------------------------------------
        | FILTER JENJANG
        |--------------------------------------------------------------------------
        */

        if (!$isAdminJenjang && !empty($this->filters['jenjang'])) {

            $query->whereHas('guru', function ($q) {

                $q->where('jenjang_id', $this->filters['jenjang']);

            });

        }

        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['tahun_ajaran'])) {

            $query->where(
                'tahun_ajaran_id',
                $this->filters['tahun_ajaran']
            );

        } else {

            $tahunAktif = TahunAjaran::where('is_aktif', 1)->first();

            if ($tahunAktif) {

                $query->where(
                    'tahun_ajaran_id',
                    $tahunAktif->id
                );

            }

        }

        return $query
            ->orderByDesc('tahun_ajaran_id')
            ->orderBy('kelas_id')
            ->get();
    }

    /**
     * Header Excel
     */

    public function headings(): array
    {
        return [
            'nip_guru',
            'nama_guru',
            'jenjang',
            'tingkat',
            'kelas',
            'tahun_ajaran',
            'semester'
        ];
    }

    /**
     * Isi Baris
     */

    public function map($item): array
    {
        return [

            optional($item->guru)->nip,

            optional($item->guru)->nama,

            optional(optional($item->guru)->jenjang)->nama_jenjang,

            optional(optional($item->kelas)->tingkat)->nama_tingkat,

            optional($item->kelas)->nama_kelas,

            optional($item->tahunAjaran)->nama_tahun,

            optional($item->tahunAjaran)->semester,

        ];
    }

    /**
     * Styling
     */

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        $sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('FFFFFF');

        $sheet->getStyle('A1:G1')->getFill()

            ->setFillType(Fill::FILL_SOLID)

            ->getStartColor()

            ->setRGB('0F172A');

        return [];
    }
}