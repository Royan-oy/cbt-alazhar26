<?php

namespace Database\Factories;

use App\Models\Jenjang;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JenjangFactory extends Factory
{
    protected $model = Jenjang::class;

    public function definition()
    {
        $name = $this->faker->unique()->word;
        return [
            'nama_jenjang' => strtoupper($name),
            'slug' => Str::slug($name),
        ];
    }
}
