<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMataPelajaransTable extends Migration
{
    public function up()
    {
        Schema::create('mata_pelajarans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jenjang_id')
                ->constrained('jenjangs')
                ->cascadeOnDelete();

            $table->string('nama_mapel'); // Matematika, IPA, dll

            $table->timestamps();

            $table->unique(['jenjang_id', 'nama_mapel']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mata_pelajarans');
    }
}