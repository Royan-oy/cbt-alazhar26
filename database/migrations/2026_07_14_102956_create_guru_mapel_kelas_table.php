<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuruMapelKelasTable extends Migration
{
    public function up()
    {
        Schema::create('guru_mapel_kelas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('guru_mapel_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'guru_mapel_id',
                'kelas_id'
            ]);
        });
    }

    public function down()
    {
        Schema::dropIfExists('guru_mapel_kelas');
    }
}