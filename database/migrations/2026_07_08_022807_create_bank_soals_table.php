<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankSoalsTable extends Migration
{
    public function up()
    {
        Schema::create('bank_soals', function (Blueprint $table) {

            $table->id();

            $table->foreignId('guru_mapel_id')
                    ->constrained('guru_mapels')
                    ->cascadeOnDelete();

            $table->foreignId('mata_pelajaran_id')
                    ->constrained('mata_pelajarans')
                    ->cascadeOnDelete();

            $table->foreignId('jenjang_id')
                    ->constrained('jenjangs')
                    ->cascadeOnDelete();

            $table->string('nama_bank_soal');

            $table->text('deskripsi')->nullable();

            $table->boolean('is_publish')->default(false);

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('bank_soals');
    }
}