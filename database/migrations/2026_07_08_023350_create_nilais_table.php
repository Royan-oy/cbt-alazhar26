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

            $table->enum('status_penilaian', [
                'belum',
                'menunggu',
                'selesai'
            ])->default('belum');

            $table->timestamps();

            // soal terakhir yang sedang dibuka
            $table->unsignedInteger('current_question')->default(0);

            // waktu terakhir autosave
            $table->timestamp('last_autosave')->nullable();

            // jumlah pelanggaran anti cheat
            $table->unsignedTinyInteger('violation_count')->default(0);

        });
    }

    public function down()
    {
        Schema::dropIfExists('nilais');
    }
}