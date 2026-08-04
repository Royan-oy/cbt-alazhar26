<?php

namespace Database\Factories;

use App\Models\MataPelajaran;
use App\Models\Jenjang;
use Illuminate\Database\Eloquent\Factories\Factory;

class MataPelajaranFactory extends Factory
{
    protected $model = MataPelajaran::class;

    public function definition()
    {
        return [
            'nama_mapel' => 'Matematika',
            'jenjang_id' => Jenjang::factory(),
        ];
    }
}
