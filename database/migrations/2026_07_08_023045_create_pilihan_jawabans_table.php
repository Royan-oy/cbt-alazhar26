<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilihanJawabansTable extends Migration
{
    public function up()
    {
        Schema::create('pilihan_jawabans', function (Blueprint $table) {

            $table->id();

            $table->foreignId('soal_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->string('kode')->nullable();

            $table->text('teks_pilihan');

            $table->text('pasangan')->nullable();

            $table->boolean('is_benar')->default(false);

            $table->integer('urutan')->default(1);

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('pilihan_jawabans');
    }
}