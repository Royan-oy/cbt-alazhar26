<?php

namespace App\Imports;

use App\Models\GuruMapel;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class GuruMapelImport extends DefaultValueBinder implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure, WithCustomValueBinder
{
    use SkipsFailures;

    /**
     * Jumlah penugasan BARU yang dibuat.
     */
    public $berhasilBaru = 0;

    /**
     * Jumlah penugasan LAMA yang diperbarui (kelasnya di-sync ulang).
     */
    public $berhasilUpdate = 0;

    /**
     * Catatan baris yang dilewati / sebagian gagal (guru/mapel/kelas tidak ditemukan).
     */
    public $gagalLainnya = [];

    /**
     * Paksa semua cell dibaca sebagai string (NIP guru sering angka murni).
     */
    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function collection(Collection $rows)
    {
        $user = Auth::user();
        $isAdminJenjang = $user->role == 'admin_jenjang';
        $jenjangAdmin = optional($user->admin)->jenjang_id;

        foreach ($rows as $row) {

            $nip = trim($row['nip_guru'] ?? '');
            $namaMapel = trim($row['mata_pelajaran'] ?? '');
            $kelasRaw = trim($row['kelas'] ?? '');
            $namaTahun = trim($row['tahun_ajaran'] ?? '');
            $semester = strtolower(trim($row['semester'] ?? ''));

            if ($nip == '' && $namaMapel == '') {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Cari Guru berdasarkan NIP
            |--------------------------------------------------------------------------
            */

            $guru = Guru::where('nip', $nip)->first();

            if (!$guru) {
                $this->gagalLainnya[] = "NIP {$nip} (Guru tidak ditemukan)";
                continue;
            }

            if ($isAdminJenjang && $guru->jenjang_id != $jenjangAdmin) {
                $this->gagalLainnya[] = "{$guru->nama} (Di luar jenjang Anda, dilewati)";
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Cari Mata Pelajaran (harus satu jenjang dengan guru)
            |--------------------------------------------------------------------------
            */

            $mapel = MataPelajaran::where('nama_mapel', $namaMapel)
                ->where('jenjang_id', $guru->jenjang_id)
                ->first();

            if (!$mapel) {
                $this->gagalLainnya[] = "{$guru->nama} (Mapel \"{$namaMapel}\" tidak ditemukan di jenjangnya)";
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Cari Tahun Ajaran (nama_tahun + semester)
            |--------------------------------------------------------------------------
            */

            $tahunAjaran = TahunAjaran::where('nama_tahun', $namaTahun)
                ->where('semester', $semester)
                ->first();

            if (!$tahunAjaran) {
                $this->gagalLainnya[] = "{$guru->nama} (Tahun ajaran \"{$namaTahun} - {$semester}\" tidak ditemukan)";
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Parse kolom kelas: "Kelas VII - VII A, Kelas VII - VII B"
            |--------------------------------------------------------------------------
            */

            $kelasIds = [];
            $kelasGagal = [];

            if ($kelasRaw != '') {
                $potongan = array_map('trim', explode(',', $kelasRaw));

                foreach ($potongan as $bagian) {

                    if ($bagian == '') {
                        continue;
                    }

                    $parts = array_map('trim', explode('-', $bagian, 2));

                    if (count($parts) < 2) {
                        $kelasGagal[] = $bagian;
                        continue;
                    }

                    list($namaTingkat, $namaKelas) = $parts;

                    $kelas = Kelas::whereHas('tingkat', function ($q) use ($namaTingkat, $guru) {
                            $q->where('nama_tingkat', $namaTingkat)->where('jenjang_id', $guru->jenjang_id);
                        })
                        ->where('nama_kelas', $namaKelas)
                        ->first();

                    if ($kelas) {
                        $kelasIds[] = $kelas->id;
                    } else {
                        $kelasGagal[] = $bagian;
                    }
                }
            }

            if (count($kelasGagal) > 0) {
                $this->gagalLainnya[] = "{$guru->nama} - {$mapel->nama_mapel} (Kelas tidak ditemukan: " . implode('; ', $kelasGagal) . ")";
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan: kalau kombinasi guru+mapel+tahun_ajaran sudah ada -> UPDATE
            | (sync ulang daftar kelasnya). Kalau belum ada -> BUAT BARU.
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use ($guru, $mapel, $tahunAjaran, $kelasIds) {

                $existing = GuruMapel::where('guru_id', $guru->id)
                    ->where('mata_pelajaran_id', $mapel->id)
                    ->where('tahun_ajaran_id', $tahunAjaran->id)
                    ->first();

                if ($existing) {
                    $existing->kelas()->sync($kelasIds);
                    $this->berhasilUpdate++;
                } else {
                    $guruMapel = GuruMapel::create([
                        'guru_id'           => $guru->id,
                        'mata_pelajaran_id' => $mapel->id,
                        'tahun_ajaran_id'   => $tahunAjaran->id,
                    ]);

                    $guruMapel->kelas()->sync($kelasIds);
                    $this->berhasilBaru++;
                }
            });
        }
    }

    public function rules(): array
    {
        return [
            'nip_guru'       => 'required|string|max:50',
            'mata_pelajaran' => 'required|string|max:150',
            'tahun_ajaran'   => 'required|string|max:50',
            'semester'       => 'required|string|max:20',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nip_guru.required'       => 'NIP guru wajib diisi.',
            'mata_pelajaran.required' => 'Mata pelajaran wajib diisi.',
            'tahun_ajaran.required'   => 'Tahun ajaran wajib diisi.',
            'semester.required'       => 'Semester wajib diisi.',
        ];
    }
}