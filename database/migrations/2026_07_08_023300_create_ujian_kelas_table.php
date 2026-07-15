<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUjianKelasTable extends Migration
{
    public function up()
    {
        Schema::create('ujian_kelas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ujian_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->foreignId('kelas_id')
                    ->constrained('kelas')
                    ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('ujian_kelas');
    }
}