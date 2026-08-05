<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\Tingkat;
use Illuminate\Database\Eloquent\Factories\Factory;

class KelasFactory extends Factory
{
    protected $model = Kelas::class;

    public function definition()
    {
        return [
            'tingkat_id' => Tingkat::factory(),
            'nama_kelas' => 'X-A',
        ];
    }
}
