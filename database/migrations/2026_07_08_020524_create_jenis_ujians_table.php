<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisUjiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_ujians', function (Blueprint $table) {

            $table->id();

            $table->string('kode',20)->unique();

            $table->string('nama',100)->unique();

            $table->text('deskripsi')->nullable();

            $table->boolean('aktif')->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenis_ujians');
    }
}
