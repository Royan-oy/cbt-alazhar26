<?php

namespace Database\Factories;

use App\Models\GuruMapel;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuruMapelFactory extends Factory
{
    protected $model = GuruMapel::class;

    public function definition()
    {
        return [
            'guru_id' => Guru::factory(),
            'mata_pelajaran_id' => MataPelajaran::factory(),
            'tahun_ajaran_id' => TahunAjaran::factory(),
        ];
    }
}
