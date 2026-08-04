<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Ujian;
use App\Models\Nilai;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WaliKelasDanKoreksiTest extends TestCase
{
    use RefreshDatabase;

    protected $guruUser;
    protected $guru;
    protected $tahunAjaran;
    protected $kelas;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guruUser = User::factory()->guru()->create();
        $this->guru = Guru::factory()->create(['user_id' => $this->guruUser->id]);
        $this->tahunAjaran = TahunAjaran::factory()->create(['is_aktif' => true]);
        $this->kelas = Kelas::factory()->create();

        DB::table('wali_kelas')->insert([
            'guru_id' => $this->guru->id,
            'kelas_id' => $this->kelas->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
        ]);
    }

    /** @test */
    public function wali_kelas_dapat_melakukan_force_submit_ujian_siswa()
    {
        $siswa = Siswa::factory()->create();
        DB::table('siswa_kelas')->insert([
            'siswa_id' => $siswa->id,
            'kelas_id' => $this->kelas->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
        ]);

        $ujian = Ujian::factory()->create();
        $nilai = Nilai::factory()->create([
            'ujian_id' => $ujian->id,
            'siswa_id' => $siswa->id,
            'status' => 'mengerjakan',
        ]);

        $response = $this->actingAs($this->guruUser)->post("/dashboard-guru/wali-kelas/monitoring-siswa/{$nilai->id}/force-submit");

        $response->assertRedirect();
        $this->assertEquals('selesai', $nilai->fresh()->status);
    }

    /** @test */
    public function wali_kelas_dapat_mereset_ujian_siswa()
    {
        $siswa = Siswa::factory()->create();
        DB::table('siswa_kelas')->insert([
            'siswa_id' => $siswa->id,
            'kelas_id' => $this->kelas->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
        ]);

        $ujian = Ujian::factory()->create();
        $nilai = Nilai::factory()->create([
            'ujian_id' => $ujian->id,
            'siswa_id' => $siswa->id,
            'status' => 'selesai',
        ]);

        $response = $this->actingAs($this->guruUser)->post("/dashboard-guru/wali-kelas/monitoring-siswa/{$nilai->id}/reset");

        $response->assertRedirect();
        $this->assertEquals('belum', $nilai->fresh()->status);
    }
}
