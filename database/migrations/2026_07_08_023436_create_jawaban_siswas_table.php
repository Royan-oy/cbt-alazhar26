<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJawabanSiswasTable extends Migration
{
    public function up()
    {
        Schema::create('jawaban_siswas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('nilai_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->foreignId('soal_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->foreignId('pilihan_jawaban_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();

            $table->longText('jawaban_text')->nullable();

            $table->json('jawaban_json')->nullable();

            $table->boolean('is_benar')->nullable();

            $table->boolean('is_ragu_ragu')->default(false);

            $table->decimal('nilai',5,2)->default(0);

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('jawaban_siswas');
    }
}