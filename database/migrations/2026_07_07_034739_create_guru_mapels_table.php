<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuruMapelsTable extends Migration
{
    public function up()
    {
        Schema::create('guru_mapels', function (Blueprint $table) {

            $table->id();

            $table->foreignId('guru_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('mata_pelajaran_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'guru_id',
                'mata_pelajaran_id',
                'tahun_ajaran_id'
            ], 'guru_mapel_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('guru_mapels');
    }
}