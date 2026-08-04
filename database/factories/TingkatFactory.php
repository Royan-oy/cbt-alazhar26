<?php

namespace Database\Factories;

use App\Models\Tingkat;
use App\Models\Jenjang;
use Illuminate\Database\Eloquent\Factories\Factory;

class TingkatFactory extends Factory
{
    protected $model = Tingkat::class;

    public function definition()
    {
        return [
            'jenjang_id' => Jenjang::factory(),
            'nama_tingkat' => 'Kelas 10',
        ];
    }
}
