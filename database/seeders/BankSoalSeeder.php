<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSoalSeeder extends Seeder
{
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil Relasi
        |--------------------------------------------------------------------------
        */

        $guruMapel = DB::table('guru_mapels')->first();

        if (!$guruMapel) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Bank Soal
        |--------------------------------------------------------------------------
        */

        $bankSoal = DB::table('bank_soals')->insertGetId([

            'guru_mapel_id'     => $guruMapel->id,

            'mata_pelajaran_id' => $guruMapel->mata_pelajaran_id,

            'jenjang_id'        => DB::table('gurus')
                                        ->where('id',$guruMapel->guru_id)
                                        ->value('jenjang_id'),

            'nama_bank_soal'    => 'Bank Soal Matematika Bab Bilangan',

            'deskripsi'         => 'Contoh bank soal Matematika kelas VII',

            'is_publish'        => true,

            'created_at'        => now(),

            'updated_at'        => now(),

        ]);

        /*
        |--------------------------------------------------------------------------
        | Data Soal
        |--------------------------------------------------------------------------
        */

        $dataSoal = [

            [
                'jenis' => 'pilihan_ganda',
                'soal'  => 'Berapakah hasil dari 5 + 7 ?',
                'bobot' => 1,
                'jawaban' => [
                    ['A','10',false],
                    ['B','11',false],
                    ['C','12',true],
                    ['D','13',false],
                    ['E','14',false],
                ]
            ],

            [
                'jenis' => 'pilihan_ganda',
                'soal'  => 'Berapakah hasil dari 8 x 6 ?',
                'bobot' => 1,
                'jawaban' => [
                    ['A','42',false],
                    ['B','46',false],
                    ['C','48',true],
                    ['D','52',false],
                    ['E','56',false],
                ]
            ],

            [
                'jenis' => 'essay',
                'soal'  => 'Jelaskan pengertian bilangan prima.',
                'bobot' => 5,
                'jawaban' => 'Bilangan prima adalah bilangan yang hanya memiliki dua faktor yaitu 1 dan dirinya sendiri.'
            ],

            [
                'jenis' => 'isian',
                'soal'  => '100 : 4 = ....',
                'bobot' => 2,
                'jawaban' => '25'
            ],

        ];
        
        $urutan = 1;

        foreach ($dataSoal as $item) {

            $soalId = DB::table('soals')->insertGetId([

                'bank_soal_id' => $bankSoal,

                'jenis_soal'   => $item['jenis'],

                'teks_soal'    => $item['soal'],

                'bobot'        => $item['bobot'],

                'urutan'       => $urutan++,

                'created_at'   => now(),

                'updated_at'   => now(),

            ]);

            /*
            |--------------------------------------------------------------------------
            | Pilihan Ganda
            |--------------------------------------------------------------------------
            */

            if ($item['jenis'] == 'pilihan_ganda') {

                $pilihan = [];

                foreach ($item['jawaban'] as $index => $opsi) {

                    $pilihan[] = [

                        'soal_id'       => $soalId,

                        'kode'          => $opsi[0],

                        'teks_pilihan'  => $opsi[1],

                        'is_benar'      => $opsi[2],

                        'urutan'        => $index + 1,

                        'created_at'    => now(),

                        'updated_at'    => now(),

                    ];

                }

                DB::table('pilihan_jawabans')->insert($pilihan);

            }

            /*
            |--------------------------------------------------------------------------
            | Essay & Isian
            |--------------------------------------------------------------------------
            */

            else {

                DB::table('pilihan_jawabans')->insert([

                    'soal_id'       => $soalId,

                    'kode'          => null,

                    'teks_pilihan'  => $item['jawaban'],

                    'is_benar'      => true,

                    'urutan'        => 1,

                    'created_at'    => now(),

                    'updated_at'    => now(),

                ]);

            }

        }

    }
}