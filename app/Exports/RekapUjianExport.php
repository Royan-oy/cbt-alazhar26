<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class RekapUjianExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $ujian;
    protected $pesertas;
    protected $kelasFilterName;
    protected $searchQuery;
    protected $avgScore;
    protected $maxScore;
    protected $minScore;

    public function __construct($ujian, $pesertas, $kelasFilterName = null, $searchQuery = null, $avgScore = 0, $maxScore = 0, $minScore = 0)
    {
        $this->ujian = $ujian;
        $this->pesertas = $pesertas;
        $this->kelasFilterName = $kelasFilterName;
        $this->searchQuery = $searchQuery;
        $this->avgScore = $avgScore;
        $this->maxScore = $maxScore;
        $this->minScore = $minScore;
    }

    public function view(): View
    {
        return view('excel.rekap-ujian', [
            'ujian'           => $this->ujian,
            'pesertas'        => $this->pesertas,
            'kelasFilterName' => $this->kelasFilterName,
            'searchQuery'     => $this->searchQuery,
            'avgScore'        => $this->avgScore,
            'maxScore'        => $this->maxScore,
            'minScore'        => $this->minScore,
        ]);
    }

    public function title(): string
    {
        return 'Hasil Ujian';
    }
}
