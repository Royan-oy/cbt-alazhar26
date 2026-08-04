<?php

namespace Database\Factories;

use App\Models\PilihanJawaban;
use App\Models\Soal;
use Illuminate\Database\Eloquent\Factories\Factory;

class PilihanJawabanFactory extends Factory
{
    protected $model = PilihanJawaban::class;

    public function definition()
    {
        return [
            'soal_id' => Soal::factory(),
            'teks_pilihan' => '4',
            'is_benar' => true,
            'urutan' => 1,
        ];
    }
}
