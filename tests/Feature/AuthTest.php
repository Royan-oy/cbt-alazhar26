<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function halaman_login_dapat_diakses()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function siswa_dapat_login_dan_melihat_dashboard()
    {
        $user = User::factory()->siswa()->create([
            'nis' => '10009999',
            'password' => Hash::make('password123'),
        ]);
        Siswa::factory()->create(['user_id' => $user->id, 'nis' => '10009999']);

        $response = $this->post('/login', [
            'role' => 'siswa',
            'login_identity' => '10009999',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function guru_dapat_login()
    {
        $user = User::factory()->guru()->create([
            'email' => 'guru@alazhar.sch.id',
            'password' => Hash::make('password123'),
        ]);
        Guru::factory()->create(['user_id' => $user->id]);

        $response = $this->post('/login', [
            'role' => 'guru',
            'login_identity' => 'guru@alazhar.sch.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_dapat_logout()
    {
        $user = User::factory()->siswa()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function user_dapat_mengubah_password()
    {
        $user = User::factory()->siswa()->create([
            'password' => Hash::make('password_lama'),
        ]);

        $response = $this->actingAs($user)->post('/pengaturan-akun/password', [
            'current_password' => 'password_lama',
            'password' => 'password_baru123',
            'password_confirmation' => 'password_baru123',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('password_baru123', $user->fresh()->password));
    }
}
