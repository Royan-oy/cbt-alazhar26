<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\User;
use App\Models\Jenjang;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->superAdmin(),
            'jenjang_id' => Jenjang::factory(),
            'nama' => 'Administrator',
        ];
    }
}
