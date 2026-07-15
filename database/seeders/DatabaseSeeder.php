<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | Tahun Ajaran
        |--------------------------------------------------------------------------
        */

        $tahunAjaranId = DB::table('tahun_ajarans')->insertGetId([
            'nama_tahun' => '2025/2026',
            'semester'   => 'ganjil',
            'is_aktif'   => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Jenis Ujian
        |--------------------------------------------------------------------------
        */

        DB::table('jenis_ujians')->insert([

            [
                'kode' => 'PH',
                'nama' => 'Penilaian Harian',
                'deskripsi' => 'Penilaian harian untuk mengukur capaian pembelajaran.',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kode' => 'PTS',
                'nama' => 'Penilaian Tengah Semester',
                'deskripsi' => 'Ujian pada pertengahan semester.',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kode' => 'PAS',
                'nama' => 'Penilaian Akhir Semester',
                'deskripsi' => 'Ujian pada akhir semester ganjil.',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kode' => 'PAT',
                'nama' => 'Penilaian Akhir Tahun',
                'deskripsi' => 'Ujian pada akhir tahun pelajaran.',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kode' => 'TO',
                'nama' => 'Try Out',
                'deskripsi' => 'Simulasi atau latihan menghadapi ujian.',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kode' => 'QUIZ',
                'nama' => 'Quiz',
                'deskripsi' => 'Evaluasi singkat setelah materi pembelajaran.',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kode' => 'REM',
                'nama' => 'Remedial',
                'deskripsi' => 'Ujian perbaikan bagi peserta didik.',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kode' => 'US',
                'nama' => 'Ujian Sekolah',
                'deskripsi' => 'Ujian akhir yang diselenggarakan oleh sekolah.',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kode' => 'UP',
                'nama' => 'Ujian Praktik',
                'deskripsi' => 'Ujian untuk mengukur kemampuan praktik peserta didik.',
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Jenjang
        |--------------------------------------------------------------------------
        */

        $sd = DB::table('jenjangs')->insertGetId([
            'nama_jenjang'=>'SD',
            'slug'=>'sd',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        $smp = DB::table('jenjangs')->insertGetId([
            'nama_jenjang'=>'SMP',
            'slug'=>'smp',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        $sma = DB::table('jenjangs')->insertGetId([
            'nama_jenjang'=>'SMA',
            'slug'=>'sma',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        /*
        |--------------------------------------------------------------------------
        | Tingkat
        |--------------------------------------------------------------------------
        */

        $kelas7 = DB::table('tingkats')->insertGetId([
            'jenjang_id'=>$smp,
            'nama_tingkat'=>'Kelas VII',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        /*
        |--------------------------------------------------------------------------
        | Kelas
        |--------------------------------------------------------------------------
        */

        $kelas7A = DB::table('kelas')->insertGetId([
            'tingkat_id'=>$kelas7,
            'nama_kelas'=>'VII A',
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        /*
        |--------------------------------------------------------------------------
        | Mata Pelajaran
        |--------------------------------------------------------------------------
        */

        // Mapel jenjang SD
        DB::table('mata_pelajarans')->insert([
            [
                'jenjang_id'  => $sd,
                'nama_mapel'  => 'Matematika',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $sd,
                'nama_mapel'  => 'Bahasa Indonesia',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $sd,
                'nama_mapel'  => 'IPA',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $sd,
                'nama_mapel'  => 'IPS',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $sd,
                'nama_mapel'  => 'Pendidikan Agama Islam',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
        

        // Mapel jenjang SMP
        DB::table('mata_pelajarans')->insert([
            [
                'jenjang_id'  => $smp,
                'nama_mapel'  => 'Matematika',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $smp,
                'nama_mapel'  => 'Bahasa Indonesia',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $smp,
                'nama_mapel'  => 'Bahasa Inggris',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $smp,
                'nama_mapel'  => 'IPA',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $smp,
                'nama_mapel'  => 'IPS',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $smp,
                'nama_mapel'  => 'Pendidikan Agama Islam',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Ambil ID Mata Pelajaran yang dibutuhkan untuk seeder relasi
        |--------------------------------------------------------------------------
        */

        $matematikaSmp = DB::table('mata_pelajarans')
            ->where('jenjang_id', $smp)
            ->where('nama_mapel', 'Matematika')
            ->value('id');

        // Mapel jenjang SMA
        DB::table('mata_pelajarans')->insert([
            [
                'jenjang_id'  => $sma,
                'nama_mapel'  => 'Matematika',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $sma,
                'nama_mapel'  => 'Bahasa Indonesia',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $sma,
                'nama_mapel'  => 'Bahasa Inggris',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $sma,
                'nama_mapel'  => 'Fisika',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $sma,
                'nama_mapel'  => 'Kimia',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $sma,
                'nama_mapel'  => 'Biologi',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'jenjang_id'  => $sma,
                'nama_mapel'  => 'Pendidikan Agama Islam',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */

        // Super Admin

        $superAdmin = DB::table('users')->insertGetId([

            'email'=>'superadmin@cbt.com',

            'password'=>Hash::make('password123'),

            'role'=>'super_admin',

            'created_at'=>now(),

            'updated_at'=>now()

        ]);

        // Admin Jenjang

        $adminSmp = DB::table('users')->insertGetId([

            'email'=>'adminsmp@cbt.com',

            'password'=>Hash::make('password123'),

            'role'=>'admin_jenjang',

            'created_at'=>now(),

            'updated_at'=>now()

        ]);

        // Guru Mapel

        $guruMapelUser = DB::table('users')->insertGetId([

            'email'=>'gurumtk@cbt.com',

            'password'=>Hash::make('password123'),

            'role'=>'guru',

            'created_at'=>now(),

            'updated_at'=>now()

        ]);

        // Guru Wali Kelas

        $waliUser = DB::table('users')->insertGetId([

            'email'=>'walikelas@cbt.com',

            'password'=>Hash::make('password123'),

            'role'=>'guru',

            'created_at'=>now(),

            'updated_at'=>now()

        ]);

        // Siswa

        $siswaUser = DB::table('users')->insertGetId([

            'nis'=>'212210043',

            'password'=>Hash::make('siswa123'),

            'role'=>'siswa',

            'created_at'=>now(),

            'updated_at'=>now()

        ]);

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        DB::table('admins')->insert([

            'user_id'=>$superAdmin,

            'jenjang_id'=>null,

            'nama'=>'Super Administrator',

            'created_at'=>now(),

            'updated_at'=>now()

        ]);

        DB::table('admins')->insert([

            'user_id'=>$adminSmp,

            'jenjang_id'=>$smp,

            'nama'=>'Admin SMP',

            'created_at'=>now(),

            'updated_at'=>now()

        ]);

        $guru = DB::table('gurus')->insertGetId([
            'user_id'=>$guruMapelUser,
            'nama'=>'Budi Utomo, S.Pd',
            'nip'=>'19881201001',
            'jenjang_id'=>$smp,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | GURU MAPEL
        |--------------------------------------------------------------------------
        */

        // Simpan penugasan guru mapel
        $guruMapel = DB::table('guru_mapels')->insertGetId([

            'guru_id'           => $guru,

            'mata_pelajaran_id' => $matematikaSmp,

            'tahun_ajaran_id'   => $tahunAjaranId,

            'created_at'        => now(),

            'updated_at'        => now(),

        ]);

        // Tentukan kelas-kelas yang diajar
        DB::table('guru_mapel_kelas')->insert([

            [
                'guru_mapel_id' => $guruMapel,
                'kelas_id'      => $kelas7A,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | GURU WALI KELAS
        |--------------------------------------------------------------------------
        */
        DB::table('wali_kelas')->insert([

            'guru_id'          => $guru,
            'kelas_id'         => $kelas7A,
            'tahun_ajaran_id'  => $tahunAjaranId,

            'created_at' => now(),
            'updated_at' => now(),

        ]);

        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        */

        $siswa = DB::table('siswas')->insertGetId([

            'user_id' => $siswaUser,

            'nama' => 'Rian Hidayat',

            'nis' => '212210043',

            'nisn' => '0081234567',

            'created_at' => now(),

            'updated_at' => now(),

        ]);

        /*
        |--------------------------------------------------------------------------
        | SISWA KELAS (Histori)
        |--------------------------------------------------------------------------
        */

        DB::table('siswa_kelas')->insert([

            'siswa_id' => $siswa,

            'kelas_id' => $kelas7A,

            'tahun_ajaran_id' => $tahunAjaranId,

            'created_at' => now(),

            'updated_at' => now(),

        ]);
    }
}