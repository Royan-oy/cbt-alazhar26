<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKategoriToBankSoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_soals', function (Blueprint $table) {
            $table->enum('kategori', ['personal', 'bersama'])->default('personal')->after('is_publish');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_soals', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
}
