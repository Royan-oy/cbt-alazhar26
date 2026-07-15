<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use App\Models\Jenjang;
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

class GuruImport extends DefaultValueBinder implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure, WithCustomValueBinder
{
    use SkipsFailures;

    /**
     * Jumlah data berhasil.
     */
    public $berhasil = 0;

    /**
     * Data yang dilewati (duplikat / jenjang tidak ditemukan).
     */
    public $gagalDuplikat = [];

    /**
     * PENTING: paksa SEMUA cell dibaca sebagai string.
     *
     * Tanpa ini, kolom seperti NIP atau No. HP yang isinya angka murni
     * (tanpa huruf) otomatis dibaca sebagai tipe int/float oleh Excel,
     * bukan string. Akibatnya rule validasi 'string' pada rules() di bawah
     * gagal untuk baris tersebut, dan karena SkipsOnFailure baris itu
     * langsung di-skip TANPA notifikasi yang jelas ke user — persis
     * kasus "guru ke-2 dan ke-3 tidak masuk" yang Anda alami.
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

            // Lewati jika satu baris kosong
            if (
                empty($row['nama']) &&
                empty($row['nip']) &&
                empty($row['email'])
            ) {
                continue;
            }

            $nama = trim($row['nama']);
            $nip = trim($row['nip']);
            $email = strtolower(trim($row['email']));
            $noHp = trim($row['no_hp'] ?? '');
            $password = trim($row['password'] ?? '');

            /*
            |--------------------------------------------------------------------------
            | Tentukan Jenjang
            |--------------------------------------------------------------------------
            */

            if ($isAdminJenjang) {

                $jenjangId = $jenjangAdmin;

            } else {

                $jenjang = Jenjang::where(
                    'nama_jenjang',
                    trim($row['jenjang'] ?? '')
                )->first();

                if (!$jenjang) {

                    $this->gagalDuplikat[] =
                        $nama . ' (Jenjang tidak ditemukan)';

                    continue;
                }

                $jenjangId = $jenjang->id;
            }

            /*
            |--------------------------------------------------------------------------
            | Cek Duplicate
            |--------------------------------------------------------------------------
            */

            if (User::where('email', $email)->exists()) {

                $this->gagalDuplikat[] =
                    $nama . ' (Email sudah digunakan)';

                continue;
            }

            if (Guru::where('nip', $nip)->exists()) {

                $this->gagalDuplikat[] =
                    $nama . ' (NIP sudah digunakan)';

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan Data
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use (
                $nama,
                $nip,
                $email,
                $noHp,
                $password,
                $jenjangId
            ) {

                $user = User::create([
                    'email'    => $email,
                    'password' => Hash::make(
                        $password != ''
                            ? $password
                            : 'guru12345'
                    ),
                    'role' => 'guru',
                ]);

                Guru::create([
                    'user_id'    => $user->id,
                    'jenjang_id' => $jenjangId,
                    'nama'       => $nama,
                    'nip'        => $nip,
                    'no_hp'      => $noHp != '' ? $noHp : null,
                ]);
            });

            $this->berhasil++;
        }
    }

    /**
     * Validasi setiap baris.
     */
    public function rules(): array
    {
        $rules = [
            'nama'     => 'required|string|max:150',
            'nip'      => 'required|string|max:50',
            'email'    => 'required|email|max:150',
            'no_hp'    => 'nullable|string|max:20',
            'password' => 'nullable|string|max:50',
        ];

        if (Auth::user()->role != 'admin_jenjang') {
            $rules['jenjang'] = 'required|string|max:100';
        }

        return $rules;
    }

    /**
     * Pesan validasi.
     */
    public function customValidationMessages()
    {
        return [
            'nama.required'     => 'Nama wajib diisi.',
            'nip.required'      => 'NIP wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'jenjang.required'  => 'Jenjang wajib diisi.',
        ];
    }
}