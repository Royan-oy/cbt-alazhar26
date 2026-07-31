<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateJenisSoalColumnInSoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ubah kolom jenis_soal menjadi VARCHAR/STRING agar mendukung 6 jenis soal secara dinamis tanpa hambatan enum MySQL
        DB::statement("ALTER TABLE `soals` MODIFY `jenis_soal` VARCHAR(50) NOT NULL DEFAULT 'pilihan_ganda'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `soals` MODIFY `jenis_soal` ENUM('pilihan_ganda', 'essay', 'isian') NOT NULL DEFAULT 'pilihan_ganda'");
    }
}
