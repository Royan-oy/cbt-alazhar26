<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Guru;
use App\Models\Jenjang;
use App\Models\MataPelajaran;
use App\Models\GuruMapel;
use App\Models\BankSoal;
use App\Models\Soal;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuruBankSoalTest extends TestCase
{
    use RefreshDatabase;

    protected $guruUser;
    protected $guru;
    protected $jenjang;
    protected $mapel;
    protected $guruMapel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guruUser = User::factory()->guru()->create();
        $this->jenjang = Jenjang::factory()->create();
        $this->guru = Guru::factory()->create([
            'user_id' => $this->guruUser->id,
            'jenjang_id' => $this->jenjang->id,
        ]);
        $this->mapel = MataPelajaran::factory()->create(['jenjang_id' => $this->jenjang->id]);
        $this->guruMapel = GuruMapel::factory()->create([
            'guru_id' => $this->guru->id,
            'mata_pelajaran_id' => $this->mapel->id,
        ]);
    }

    /** @test */
    public function guru_dapat_membuat_bank_soal()
    {
        $response = $this->actingAs($this->guruUser)->post('/dashboard-guru/bank-soal', [
            'nama_bank_soal' => 'Bank Soal Biologi',
            'guru_mapel_id' => $this->guruMapel->id,
            'kkm' => 75,
            'kategori' => 'personal',
            'deskripsi' => 'Biologi Kelas X',
        ]);

        $response->assertRedirect('/dashboard-guru/bank-soal');
        $this->assertDatabaseHas('bank_soals', [
            'nama_bank_soal' => 'Bank Soal Biologi',
        ]);
    }

    /** @test */
    public function guru_dapat_toggle_publish_bank_soal()
    {
        $bankSoal = BankSoal::factory()->create([
            'guru_mapel_id' => $this->guruMapel->id,
            'mata_pelajaran_id' => $this->mapel->id,
            'jenjang_id' => $this->jenjang->id,
            'is_publish' => true,
        ]);

        $response = $this->actingAs($this->guruUser)->patch("/dashboard-guru/bank-soal/{$bankSoal->id}/toggle-publish");

        $response->assertRedirect();
        $this->assertFalse((bool)$bankSoal->fresh()->is_publish);
    }

    /** @test */
    public function guru_dapat_menduplikasi_bank_soal()
    {
        $bankSoal = BankSoal::factory()->create([
            'guru_mapel_id' => $this->guruMapel->id,
            'mata_pelajaran_id' => $this->mapel->id,
            'jenjang_id' => $this->jenjang->id,
            'nama_bank_soal' => 'Bank Soal Asli',
        ]);

        $response = $this->actingAs($this->guruUser)->post("/dashboard-guru/bank-soal/{$bankSoal->id}/duplicate");

        $response->assertRedirect();
        $this->assertDatabaseHas('bank_soals', [
            'nama_bank_soal' => 'Salinan - Bank Soal Asli',
        ]);
    }
}
