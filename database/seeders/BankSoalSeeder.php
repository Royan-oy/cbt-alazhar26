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
                'bobot' => 20,
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
                'bobot' => 20,
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
                'bobot' => 40,
                'jawaban' => 'Bilangan prima adalah bilangan yang hanya memiliki dua faktor yaitu 1 dan dirinya sendiri.'
            ],

            [
                'jenis' => 'isian',
                'soal'  => '100 : 4 = ....',
                'bobot' => 20,
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

        $bankSoal2 = DB::table('bank_soals')->insertGetId([
            'guru_mapel_id'     => $guruMapel->id,
            'mata_pelajaran_id' => $guruMapel->mata_pelajaran_id,
            'jenjang_id'        => DB::table('gurus')
                                        ->where('id',$guruMapel->guru_id)
                                        ->value('jenjang_id'),
            'nama_bank_soal'    => 'Bank Soal Matematika Bab Aljabar',
            'deskripsi'         => 'Contoh soal materi Aljabar',
            'is_publish'        => true,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $dataSoal2 = [

            [
                'jenis'=>'pilihan_ganda',
                'soal'=>'Hasil dari x + x + x adalah...',
                'bobot'=>20,
                'jawaban'=>[
                    ['A','2x',false],
                    ['B','3x',true],
                    ['C','4x',false],
                    ['D','x²',false],
                    ['E','6x',false],
                ]
            ],

            [
                'jenis'=>'pilihan_ganda',
                'soal'=>'Nilai 5x jika x = 6 adalah...',
                'bobot'=>20,
                'jawaban'=>[
                    ['A','25',false],
                    ['B','30',true],
                    ['C','36',false],
                    ['D','40',false],
                    ['E','56',false],
                ]
            ],

            [
                'jenis'=>'essay',
                'soal'=>'Jelaskan pengertian variabel pada bentuk aljabar.',
                'bobot'=>40,
                'jawaban'=>'Variabel adalah lambang yang mewakili suatu nilai yang belum diketahui.'
            ],

            [
                'jenis'=>'isian',
                'soal'=>'2x + 3x = ....',
                'bobot'=>20,
                'jawaban'=>'5x'
            ],

        ];

        $urutan = 1;

        foreach($dataSoal2 as $item){

            $soalId = DB::table('soals')->insertGetId([
                'bank_soal_id'=>$bankSoal2,
                'jenis_soal'=>$item['jenis'],
                'teks_soal'=>$item['soal'],
                'bobot'=>$item['bobot'],
                'urutan'=>$urutan++,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);

            if($item['jenis']=='pilihan_ganda'){

                $pilihan=[];

                foreach($item['jawaban'] as $index=>$opsi){

                    $pilihan[]=[
                        'soal_id'=>$soalId,
                        'kode'=>$opsi[0],
                        'teks_pilihan'=>$opsi[1],
                        'is_benar'=>$opsi[2],
                        'urutan'=>$index+1,
                        'created_at'=>now(),
                        'updated_at'=>now(),
                    ];

                }

                DB::table('pilihan_jawabans')->insert($pilihan);

            }else{

                DB::table('pilihan_jawabans')->insert([
                    'soal_id'=>$soalId,
                    'kode'=>null,
                    'teks_pilihan'=>$item['jawaban'],
                    'is_benar'=>true,
                    'urutan'=>1,
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ]);

            }

        }

        $bankSoal3 = DB::table('bank_soals')->insertGetId([
            'guru_mapel_id'     => $guruMapel->id,
            'mata_pelajaran_id' => $guruMapel->mata_pelajaran_id,
            'jenjang_id'        => DB::table('gurus')
                                        ->where('id',$guruMapel->guru_id)
                                        ->value('jenjang_id'),
            'nama_bank_soal'    => 'Bank Soal Matematika Bab Geometri',
            'deskripsi'         => 'Contoh soal materi Geometri',
            'is_publish'        => true,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $dataSoal3 = [

            [
                'jenis'=>'pilihan_ganda',
                'soal'=>'Bangun datar yang memiliki 4 sisi sama panjang adalah...',
                'bobot'=>20,
                'jawaban'=>[
                    ['A','Persegi',true],
                    ['B','Segitiga',false],
                    ['C','Lingkaran',false],
                    ['D','Trapesium',false],
                    ['E','Jajar Genjang',false],
                ]
            ],

            [
                'jenis'=>'pilihan_ganda',
                'soal'=>'Jumlah sudut dalam segitiga adalah...',
                'bobot'=>20,
                'jawaban'=>[
                    ['A','90°',false],
                    ['B','180°',true],
                    ['C','270°',false],
                    ['D','360°',false],
                    ['E','540°',false],
                ]
            ],

            [
                'jenis'=>'essay',
                'soal'=>'Jelaskan perbedaan persegi dan persegi panjang.',
                'bobot'=>40,
                'jawaban'=>'Persegi memiliki empat sisi sama panjang, sedangkan persegi panjang hanya sisi yang berhadapan saja yang sama panjang.'
            ],

            [
                'jenis'=>'isian',
                'soal'=>'Rumus luas persegi adalah ....',
                'bobot'=>20,
                'jawaban'=>'s × s'
            ],

        ];

        $urutan = 1;

        foreach($dataSoal3 as $item){

            $soalId = DB::table('soals')->insertGetId([
                'bank_soal_id'=>$bankSoal3,
                'jenis_soal'=>$item['jenis'],
                'teks_soal'=>$item['soal'],
                'bobot'=>$item['bobot'],
                'urutan'=>$urutan++,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);

            if($item['jenis']=='pilihan_ganda'){

                $pilihan=[];

                foreach($item['jawaban'] as $index=>$opsi){

                    $pilihan[]=[
                        'soal_id'=>$soalId,
                        'kode'=>$opsi[0],
                        'teks_pilihan'=>$opsi[1],
                        'is_benar'=>$opsi[2],
                        'urutan'=>$index+1,
                        'created_at'=>now(),
                        'updated_at'=>now(),
                    ];

                }

                DB::table('pilihan_jawabans')->insert($pilihan);

            }else{

                DB::table('pilihan_jawabans')->insert([
                    'soal_id'=>$soalId,
                    'kode'=>null,
                    'teks_pilihan'=>$item['jawaban'],
                    'is_benar'=>true,
                    'urutan'=>1,
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ]);

            }

        }
    }
}