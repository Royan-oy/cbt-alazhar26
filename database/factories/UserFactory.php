<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'nis' => $this->faker->unique()->numerify('1000####'),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'password_plain' => 'password',
            'role' => 'siswa',
            'remember_token' => Str::random(10),
        ];
    }

    public function superAdmin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'super_admin',
            ];
        });
    }

    public function adminJenjang()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'admin_jenjang',
            ];
        });
    }

    public function guru()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'guru',
            ];
        });
    }

    public function siswa()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'siswa',
            ];
        });
    }
}
