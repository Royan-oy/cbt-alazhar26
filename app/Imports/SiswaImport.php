<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\User;
use App\Models\Jenjang;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class SiswaImport extends DefaultValueBinder implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure, WithCustomValueBinder
{
    use SkipsFailures;

    /**
     * Jumlah siswa BARU yang berhasil dibuat (akun + profil baru).
     */
    public $berhasil = 0;

    /**
     * Jumlah siswa LAMA yang berhasil dipindah/diperbarui kelasnya
     * (akun login tidak disentuh sama sekali).
     */
    public $diperbarui = 0;

    /**
     * Baris yang dilewati (kelas tidak ditemukan, dsb).
     */
    public $gagalLainnya = [];

    /**
     * Paksa semua cell dibaca sebagai string, supaya NIS/NISN yang berupa
     * angka murni tidak otomatis dikonversi jadi int oleh Excel.
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

        $tahunAktif = TahunAjaran::where('is_aktif', true)->first();

        if (!$tahunAktif) {
            $this->gagalLainnya[] = 'Tidak ada tahun ajaran aktif. Aktifkan salah satu tahun ajaran terlebih dahulu.';
            return;
        }

        foreach ($rows as $row) {

            if (empty($row['nama']) && empty($row['nis'])) {
                continue;
            }

            $nama = trim($row['nama']);
            $nis = trim($row['nis']);
            $nisn = trim($row['nisn'] ?? '');
            $password = trim($row['password'] ?? '');
            $namaTingkat = trim($row['tingkat'] ?? '');
            $namaKelas = trim($row['kelas'] ?? '');

            /*
            |--------------------------------------------------------------------------
            | Tentukan Jenjang untuk pencarian kelas
            |--------------------------------------------------------------------------
            */

            if ($isAdminJenjang) {
                $jenjangId = $jenjangAdmin;
            } else {
                $namaJenjang = trim($row['jenjang'] ?? '');
                $jenjang = Jenjang::where('nama_jenjang', $namaJenjang)->first();

                if (!$jenjang) {
                    $this->gagalLainnya[] = $nama . ' (Jenjang "' . $namaJenjang . '" tidak ditemukan)';
                    continue;
                }

                $jenjangId = $jenjang->id;
            }

            /*
            |--------------------------------------------------------------------------
            | Cari Kelas tujuan berdasarkan tingkat + nama kelas + jenjang
            |--------------------------------------------------------------------------
            */

            $kelas = Kelas::whereHas('tingkat', function ($q) use ($namaTingkat, $jenjangId) {
                    $q->where('nama_tingkat', $namaTingkat)->where('jenjang_id', $jenjangId);
                })
                ->where('nama_kelas', $namaKelas)
                ->first();

            if (!$kelas) {
                $this->gagalLainnya[] = $nama . ' (Kelas "' . $namaTingkat . ' - ' . $namaKelas . '" tidak ditemukan)';
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CEK: siswa dengan NIS ini sudah terdaftar sebelumnya?
            |--------------------------------------------------------------------------
            */

            $siswaLama = Siswa::where('nis', $nis)->first();

            if ($siswaLama) {

                // ==========================================================
                // SISWA LAMA -> HANYA PINDAHKAN/PERBARUI KELASNYA.
                // Akun login (users: nis + password) TIDAK disentuh sama sekali.
                // ==========================================================

                SiswaKelas::updateOrCreate(
                    [
                        'siswa_id'        => $siswaLama->id,
                        'tahun_ajaran_id' => $tahunAktif->id,
                    ],
                    [
                        'kelas_id' => $kelas->id,
                    ]
                );

                // Opsional: sinkronkan nama/NISN kalau ada perubahan di excel
                $siswaLama->update([
                    'nama' => $nama != '' ? $nama : $siswaLama->nama,
                    'nisn' => $nisn != '' ? $nisn : $siswaLama->nisn,
                ]);

                $this->diperbarui++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | SISWA BARU -> buat akun (users) + profil (siswas) + siswa_kelas
            |--------------------------------------------------------------------------
            */

            if ($nisn != '' && Siswa::where('nisn', $nisn)->exists()) {
                $this->gagalLainnya[] = $nama . ' (NISN sudah digunakan siswa lain)';
                continue;
            }

            DB::transaction(function () use ($nama, $nis, $nisn, $password, $kelas, $tahunAktif) {

                $user = User::create([
                    'nis'      => $nis,
                    'password' => Hash::make($password != '' ? $password : 'siswa12345'),
                    'role'     => 'siswa',
                ]);

                $siswaBaru = Siswa::create([
                    'user_id' => $user->id,
                    'nama'    => $nama,
                    'nis'     => $nis,
                    'nisn'    => $nisn != '' ? $nisn : null,
                ]);

                SiswaKelas::create([
                    'siswa_id'        => $siswaBaru->id,
                    'kelas_id'        => $kelas->id,
                    'tahun_ajaran_id' => $tahunAktif->id,
                ]);
            });

            $this->berhasil++;
        }
    }

    public function rules(): array
    {
        $rules = [
            'nama'     => 'required|string|max:150',
            'nis'      => 'required|string|max:50',
            'nisn'     => 'nullable|string|max:50',
            'password' => 'nullable|string|max:50',
            'tingkat'  => 'required|string|max:100',
            'kelas'    => 'required|string|max:100',
        ];

        if (Auth::user()->role != 'admin_jenjang') {
            $rules['jenjang'] = 'required|string|max:100';
        }

        return $rules;
    }

    public function customValidationMessages()
    {
        return [
            'nama.required'    => 'Nama wajib diisi.',
            'nis.required'     => 'NIS wajib diisi.',
            'tingkat.required' => 'Tingkat wajib diisi.',
            'kelas.required'   => 'Kelas wajib diisi.',
            'jenjang.required' => 'Jenjang wajib diisi.',
        ];
    }
}