<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUjiansTable extends Migration
{
    public function up()
    {
        Schema::create('ujians', function (Blueprint $table) {

            $table->id();

            $table->foreignId('bank_soal_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->foreignId('tahun_ajaran_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->foreignId('jenis_ujian_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->string('nama_ujian');

            $table->dateTime('waktu_mulai');

            $table->dateTime('waktu_selesai');

            $table->integer('durasi_minimal');

            $table->string('token', 6)->nullable()->unique();

            $table->boolean('acak_soal')->default(true);

            $table->boolean('acak_jawaban')->default(true);

            $table->boolean('tampilkan_nilai')->default(false);

            $table->boolean('tampilkan_pembahasan')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ujians');
    }
}