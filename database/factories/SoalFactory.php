<?php

namespace Database\Factories;

use App\Models\Soal;
use App\Models\BankSoal;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoalFactory extends Factory
{
    protected $model = Soal::class;

    public function definition()
    {
        return [
            'bank_soal_id' => BankSoal::factory(),
            'jenis_soal' => 'pilihan_ganda',
            'teks_soal' => '<p>Berapakah hasil dari 2 + 2?</p>',
            'bobot' => 10,
            'urutan' => 1,
        ];
    }
}
