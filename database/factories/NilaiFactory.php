<?php

namespace Database\Factories;

use App\Models\Nilai;
use App\Models\Ujian;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class NilaiFactory extends Factory
{
    protected $model = Nilai::class;

    public function definition()
    {
        return [
            'ujian_id' => Ujian::factory(),
            'siswa_id' => Siswa::factory(),
            'waktu_mulai_kerja' => now(),
            'waktu_kumpul' => null,
            'nilai_pg' => 0,
            'nilai_essay' => 0,
            'nilai_akhir' => 0,
            'status' => 'mengerjakan',
            'status_penilaian' => 'belum',
            'current_question' => 1,
            'last_autosave' => now(),
            'violation_count' => 0,
            'potongan_pelanggaran' => 0,
        ];
    }
}
