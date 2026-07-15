<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswaKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa_kelas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('siswa_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->cascadeOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'siswa_id',
                'tahun_ajaran_id'
            ]);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siswa_kelas');
    }
}
