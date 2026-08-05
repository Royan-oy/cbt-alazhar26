<?php

namespace Database\Factories;

use App\Models\Guru;
use App\Models\User;
use App\Models\Jenjang;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuruFactory extends Factory
{
    protected $model = Guru::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->guru(),
            'jenjang_id' => Jenjang::factory(),
            'nama' => $this->faker->name,
            'nip' => $this->faker->numerify('1990########'),
            'no_hp' => '081298765432',
        ];
    }
}
