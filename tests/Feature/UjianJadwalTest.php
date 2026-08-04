<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\BankSoal;
use App\Models\TahunAjaran;
use App\Models\JenisUjian;
use App\Models\Tingkat;
use App\Models\Kelas;
use App\Models\Ujian;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UjianJadwalTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->superAdmin()->create();
        \App\Models\Admin::factory()->create(['user_id' => $this->adminUser->id]);
    }

    /** @test */
    public function admin_dapat_membuat_jadwal_ujian()
    {
        $bankSoal = BankSoal::factory()->create([
            'is_publish' => true,
            'kategori' => 'bersama',
        ]);
        $tahunAjaran = TahunAjaran::factory()->create();
        $jenisUjian = JenisUjian::factory()->create();

        $tingkat = Tingkat::factory()->create(['jenjang_id' => $bankSoal->jenjang_id]);
        $kelas = Kelas::factory()->create(['tingkat_id' => $tingkat->id]);

        $response = $this->actingAs($this->adminUser)->post('/ujian', [
            'nama_ujian' => 'Ujian Akhir Semester Fisika',
            'bank_soal_id' => $bankSoal->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jenis_ujian_id' => $jenisUjian->id,
            'waktu_mulai' => now()->format('Y-m-d\TH:i'),
            'waktu_selesai' => now()->addHours(2)->format('Y-m-d\TH:i'),
            'durasi_minimal' => 30,
            'kelas_id' => [$kelas->id],
        ]);

        $response->assertRedirect('/ujian');
        $this->assertDatabaseHas('ujians', [
            'nama_ujian' => 'Ujian Akhir Semester Fisika',
        ]);
    }

    /** @test */
    public function admin_dapat_regenerate_token_ujian()
    {
        $ujian = Ujian::factory()->create([
            'token' => '123456',
        ]);

        $response = $this->actingAs($this->adminUser)->patch("/ujian/{$ujian->id}/regenerate-token");

        $response->assertRedirect();
        $this->assertNotEquals('123456', $ujian->fresh()->token);
        $this->assertEquals(6, strlen($ujian->fresh()->token));
    }
}
