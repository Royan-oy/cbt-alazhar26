<?php

namespace Database\Factories;

use App\Models\BankSoal;
use App\Models\MataPelajaran;
use App\Models\Jenjang;
use App\Models\GuruMapel;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankSoalFactory extends Factory
{
    protected $model = BankSoal::class;

    public function definition()
    {
        return [
            'guru_mapel_id' => GuruMapel::factory(),
            'mata_pelajaran_id' => MataPelajaran::factory(),
            'jenjang_id' => Jenjang::factory(),
            'nama_bank_soal' => 'Bank Soal Matematika XI',
            'deskripsi' => 'Deskripsi Bank Soal',
            'kkm' => 75,
            'is_publish' => true,
            'kategori' => 'personal',
        ];
    }
}
