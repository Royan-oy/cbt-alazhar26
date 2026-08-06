<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishNilaiToUjiansTable extends Migration
{
    public function up()
    {
        Schema::table('ujians', function (Blueprint $table) {
            $table->boolean('publish_nilai')->default(false)->after('acak_jawaban');
            $table->timestamp('published_at')->nullable()->after('publish_nilai');
            $table->foreignId('published_by')->nullable()->after('published_at')
                  ->constrained('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('ujians', function (Blueprint $table) {
            $table->dropForeign(['published_by']);
            $table->dropColumn(['publish_nilai', 'published_at', 'published_by']);
        });
    }
}
