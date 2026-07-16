<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\WaliKelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WaliKelasImport implements ToCollection, WithHeadingRow
{
    public $success = 0;
    public $failed = [];
    public $skipped = 0;

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {

            foreach ($rows as $index => $row) {

                $baris = $index + 2; // karena baris pertama header

                /*
                |--------------------------------------------------------------------------
                | Baris kosong
                |--------------------------------------------------------------------------
                */

                if (empty($row['nip_guru']) || empty($row['kelas'])) {

                    $this->skipped++;

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Guru
                |--------------------------------------------------------------------------
                */

                $guru = Guru::where('nip', trim($row['nip_guru']))->first();

                if (!$guru) {

                    $this->failed[] = [
                        'baris' => $baris,
                        'pesan' => "Guru dengan NIP {$row['nip_guru']} tidak ditemukan."
                    ];

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Kelas
                |--------------------------------------------------------------------------
                */

                $kelas = Kelas::with('tingkat')
                    ->where('nama_kelas', trim($row['kelas']))
                    ->first();

                if (!$kelas) {

                    $this->failed[] = [
                        'baris' => $baris,
                        'pesan' => "Kelas {$row['kelas']} tidak ditemukan."
                    ];

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Tahun Ajaran
                |--------------------------------------------------------------------------
                */

                $tahunAjaran = TahunAjaran::where('nama_tahun', trim($row['tahun_ajaran']))
                    ->where('semester', trim($row['semester']))
                    ->first();

                if (!$tahunAjaran) {

                    $this->failed[] = [
                        'baris' => $baris,
                        'pesan' => "Tahun Ajaran {$row['tahun_ajaran']} Semester {$row['semester']} tidak ditemukan."
                    ];

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Jenjang Guru = Jenjang Kelas
                |--------------------------------------------------------------------------
                */

                if ($guru->jenjang_id != optional($kelas->tingkat)->jenjang_id) {

                    $this->failed[] = [
                        'baris' => $baris,
                        'pesan' => "Jenjang guru tidak sesuai dengan kelas."
                    ];

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Maksimal 2 wali
                |--------------------------------------------------------------------------
                */

                $jumlah = WaliKelas::where('kelas_id', $kelas->id)
                    ->where('tahun_ajaran_id', $tahunAjaran->id)
                    ->count();

                if ($jumlah >= 2) {

                    $this->failed[] = [
                        'baris' => $baris,
                        'pesan' => "Kelas {$kelas->nama_kelas} sudah memiliki 2 wali kelas."
                    ];

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Guru sudah jadi wali kelas lain
                |--------------------------------------------------------------------------
                */

                $guruSudah = WaliKelas::where('guru_id', $guru->id)
                    ->where('tahun_ajaran_id', $tahunAjaran->id)
                    ->exists();

                if ($guruSudah) {

                    $this->failed[] = [
                        'baris' => $baris,
                        'pesan' => "{$guru->nama} sudah menjadi wali kelas lain."
                    ];

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Sudah ada
                |--------------------------------------------------------------------------
                */

                $sudahAda = WaliKelas::where('guru_id', $guru->id)
                    ->where('kelas_id', $kelas->id)
                    ->where('tahun_ajaran_id', $tahunAjaran->id)
                    ->exists();

                if ($sudahAda) {

                    $this->skipped++;

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Simpan
                |--------------------------------------------------------------------------
                */

                WaliKelas::create([
                    'guru_id' => $guru->id,
                    'kelas_id' => $kelas->id,
                    'tahun_ajaran_id' => $tahunAjaran->id,
                ]);

                $this->success++;
            }

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }
}