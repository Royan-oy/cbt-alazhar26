<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            if (!Schema::hasColumn('nilais', 'potongan_pelanggaran')) {
                $table->decimal('potongan_pelanggaran', 8, 2)->default(0.00)->after('violation_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            if (Schema::hasColumn('nilais', 'potongan_pelanggaran')) {
                $table->dropColumn('potongan_pelanggaran');
            }
        });
    }
};
