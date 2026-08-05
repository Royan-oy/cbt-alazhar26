<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Jenjang;
use App\Models\TahunAjaran;
use App\Models\JenisUjian;
use App\Models\Tingkat;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminMasterDataTest extends TestCase
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
    public function admin_dapat_melihat_dan_membuat_jenjang()
    {
        $response = $this->actingAs($this->adminUser)->get('/jenjang');
        $response->assertStatus(200);

        $response = $this->actingAs($this->adminUser)->post('/jenjang', [
            'nama_jenjang' => 'SMA',
        ]);

        $response->assertRedirect('/jenjang');
        $this->assertDatabaseHas('jenjangs', ['nama_jenjang' => 'SMA']);
    }

    /** @test */
    public function admin_dapat_membuat_tahun_ajaran()
    {
        $response = $this->actingAs($this->adminUser)->post('/tahun-ajaran', [
            'nama_tahun' => '2026/2027',
            'semester' => 'ganjil',
            'is_aktif' => 1,
        ]);

        $response->assertRedirect('/tahun-ajaran');
        $this->assertDatabaseHas('tahun_ajarans', ['nama_tahun' => '2026/2027']);
    }

    /** @test */
    public function admin_dapat_membuat_jenis_ujian()
    {
        $response = $this->actingAs($this->adminUser)->post('/jenis-ujian', [
            'kode' => 'PTS',
            'nama' => 'Penilaian Tengah Semester',
            'deskripsi' => 'Ujian Tengah Semester',
            'aktif' => 1,
        ]);

        $response->assertRedirect('/jenis-ujian');
        $this->assertDatabaseHas('jenis_ujians', ['kode' => 'PTS']);
    }

    /** @test */
    public function admin_dapat_membuat_mata_pelajaran()
    {
        $jenjang = Jenjang::factory()->create();

        $response = $this->actingAs($this->adminUser)->post('/mata-pelajaran', [
            'nama_mapel' => 'Fisika',
            'jenjang_id' => $jenjang->id,
        ]);

        $response->assertRedirect('/mata-pelajaran');
        $this->assertDatabaseHas('mata_pelajarans', ['nama_mapel' => 'Fisika']);
    }
}
