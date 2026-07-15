<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoalsTable extends Migration
{
    public function up()
    {
        Schema::create('soals', function (Blueprint $table) {

            $table->id();

            $table->foreignId('bank_soal_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->enum('jenis_soal',[

                'pilihan_ganda',

                'pilihan_ganda_kompleks',

                'benar_salah',

                'essay',

                'isian',

                'menjodohkan',

                'mengurutkan'

            ]);

            $table->longText('teks_soal');

            $table->string('gambar')->nullable();

            $table->integer('bobot')->default(1);

            $table->integer('urutan')->default(1);

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('soals');
    }
}