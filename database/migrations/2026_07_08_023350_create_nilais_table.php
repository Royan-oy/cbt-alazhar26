<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaisTable extends Migration
{
    public function up()
    {
        Schema::create('nilais', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ujian_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->foreignId('siswa_id')
                    ->constrained('siswas')
                    ->cascadeOnDelete();

            $table->dateTime('waktu_mulai_kerja');

            $table->dateTime('waktu_kumpul')->nullable();

            $table->decimal('nilai_pg',5,2)->default(0);

            $table->decimal('nilai_essay',5,2)->default(0);

            $table->decimal('nilai_akhir',5,2)->default(0);

            $table->enum('status',[

                'belum',

                'mengerjakan',

                'selesai'

            ])->default('belum');

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('nilais');
    }
}