<?php

namespace Database\Factories;

use App\Models\JenisUjian;
use Illuminate\Database\Eloquent\Factories\Factory;

class JenisUjianFactory extends Factory
{
    protected $model = JenisUjian::class;

    public function definition()
    {
        return [
            'kode' => 'PAS',
            'nama' => 'Penilaian Akhir Semester',
            'deskripsi' => 'Ujian Akhir Semester',
            'aktif' => true,
        ];
    }
}
