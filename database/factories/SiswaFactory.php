<?php

namespace Database\Factories;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->siswa(),
            'nama' => $this->faker->name,
            'nis' => $this->faker->numerify('1000####'),
            'nisn' => $this->faker->numerify('0050######'),
        ];
    }
}
