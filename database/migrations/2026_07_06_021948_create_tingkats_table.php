<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTingkatsTable extends Migration
{
    public function up()
    {
        Schema::create('tingkats', function (Blueprint $table) {

            $table->id();

            $table->foreignId('jenjang_id')
                ->constrained('jenjangs')
                ->cascadeOnDelete();

            $table->string('nama_tingkat', 50);

            $table->timestamps();

            // Tidak boleh ada tingkat yang sama dalam satu jenjang
            $table->unique(['jenjang_id', 'nama_tingkat']);

        });
    }

    public function down()
    {
        Schema::dropIfExists('tingkats');
    }
}