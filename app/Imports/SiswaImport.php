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
     * Jumlah data berhasil.
     */
    public $berhasil = 0;

    /**
     * Baris yang dilewati (duplikat NIS/NISN, atau kelas tidak ditemukan).
     */
    public $gagalLainnya = [];

    /**
     * Paksa semua cell dibaca sebagai string, supaya NIS/NISN yang berupa
     * angka murni tidak otomatis dikonversi jadi int oleh Excel
     * (pelajaran dari kasus import Guru sebelumnya).
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
            | Cari Kelas berdasarkan tingkat + nama kelas + jenjang
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
            | Cek Duplikat
            |--------------------------------------------------------------------------
            */

            if (User::where('nis', $nis)->exists() || Siswa::where('nis', $nis)->exists()) {
                $this->gagalLainnya[] = $nama . ' (NIS sudah digunakan)';
                continue;
            }

            if ($nisn != '' && Siswa::where('nisn', $nisn)->exists()) {
                $this->gagalLainnya[] = $nama . ' (NISN sudah digunakan)';
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan Data
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use ($nama, $nis, $nisn, $password, $kelas, $tahunAktif) {

                $user = User::create([
                    'nis'      => $nis,
                    'password' => Hash::make($password != '' ? $password : 'siswa12345'),
                    'role'     => 'siswa',
                ]);

                $siswa = Siswa::create([
                    'user_id' => $user->id,
                    'nama'    => $nama,
                    'nis'     => $nis,
                    'nisn'    => $nisn != '' ? $nisn : null,
                ]);

                SiswaKelas::create([
                    'siswa_id'        => $siswa->id,
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