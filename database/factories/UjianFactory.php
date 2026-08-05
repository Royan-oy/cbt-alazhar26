<?php

namespace Database\Factories;

use App\Models\Ujian;
use App\Models\BankSoal;
use App\Models\TahunAjaran;
use App\Models\JenisUjian;
use Illuminate\Database\Eloquent\Factories\Factory;

class UjianFactory extends Factory
{
    protected $model = Ujian::class;

    public function definition()
    {
        return [
            'bank_soal_id' => BankSoal::factory(),
            'tahun_ajaran_id' => TahunAjaran::factory(),
            'jenis_ujian_id' => JenisUjian::factory(),
            'nama_ujian' => 'Ujian Akhir Semester Matematika',
            'waktu_mulai' => now()->subHour(),
            'waktu_selesai' => now()->addHours(2),
            'durasi_minimal' => 30,
            'token' => Ujian::generateToken(),
            'acak_soal' => false,
            'acak_jawaban' => false,
        ];
    }
}
