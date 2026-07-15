<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTahunAjaransTable extends Migration
{
    public function up()
    {
        Schema::create('tahun_ajarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tahun'); // Contoh: "2025/2026"
            $table->enum('semester', ['ganjil', 'genap']);
            $table->boolean('is_aktif')->default(false); // Untuk menentukan tahun ajaran yang sedang berjalan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tahun_ajarans');
    }
}