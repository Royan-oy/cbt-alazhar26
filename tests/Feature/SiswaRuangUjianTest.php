<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Ujian;
use App\Models\Nilai;
use App\Models\BankSoal;
use App\Models\Soal;
use App\Models\PilihanJawaban;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SiswaRuangUjianTest extends TestCase
{
    use RefreshDatabase;

    protected $siswaUser;
    protected $siswa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->siswaUser = User::factory()->siswa()->create();
        $this->siswa = Siswa::factory()->create(['user_id' => $this->siswaUser->id]);
    }

    /** @test */
    public function siswa_dapat_memvalidasi_token_dan_masuk_ruang_ujian()
    {
        $ujian = Ujian::factory()->create([
            'token' => '654321',
            'waktu_mulai' => now()->subMinutes(10),
            'waktu_selesai' => now()->addMinutes(60),
        ]);

        $response = $this->actingAs($this->siswaUser)->post("/dashboard-siswa/ujian/{$ujian->id}/proses-masuk", [
            'token' => '654321',
        ]);

        $response->assertRedirect(route('dashboard-siswa.ujian.kerja', $ujian->id));
        $this->assertTrue(session('ujian_terverifikasi_' . $ujian->id));
    }

    /** @test */
    public function siswa_dapat_mengirim_autosave_jawaban()
    {
        $bankSoal = BankSoal::factory()->create();
        $soal = Soal::factory()->create(['bank_soal_id' => $bankSoal->id]);
        $pilihan = PilihanJawaban::factory()->create(['soal_id' => $soal->id]);
        $ujian = Ujian::factory()->create(['bank_soal_id' => $bankSoal->id]);
        $nilai = Nilai::factory()->create([
            'ujian_id' => $ujian->id,
            'siswa_id' => $this->siswa->id,
        ]);

        $response = $this->actingAs($this->siswaUser)->postJson('/dashboard-siswa/ujian/autosave', [
            'ujian_id' => $ujian->id,
            'soal_id' => $soal->id,
            'pilihan_jawaban_id' => $pilihan->id,
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseHas('jawaban_siswas', [
            'nilai_id' => $nilai->id,
            'soal_id' => $soal->id,
            'pilihan_jawaban_id' => $pilihan->id,
        ]);
    }

    /** @test */
    public function pencatatan_pelanggaran_menambah_violation_count()
    {
        $ujian = Ujian::factory()->create();
        $nilai = Nilai::factory()->create([
            'ujian_id' => $ujian->id,
            'siswa_id' => $this->siswa->id,
            'violation_count' => 0,
        ]);

        $response = $this->actingAs($this->siswaUser)->postJson('/dashboard-siswa/ujian/violation', [
            'ujian_id' => $ujian->id,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'count' => 1]);

        $this->assertEquals(1, $nilai->fresh()->violation_count);
    }
}
