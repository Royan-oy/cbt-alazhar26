<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ujian;
use Carbon\Carbon;

class UpdateUjianToken extends Command
{
    protected $signature = 'ujian:update-token';
    protected $description = 'Update status token ujian otomatis';

    public function handle()
    {
        $now = Carbon::now();

        // Aktifkan token
        Ujian::where('token_aktif', false)
            ->where('waktu_mulai', '<=', $now)
            ->where('waktu_selesai', '>=', $now)
            ->update([
                'token_aktif' => true
            ]);

        // Nonaktifkan token
        Ujian::where('token_aktif', true)
            ->where('waktu_selesai', '<', $now)
            ->update([
                'token_aktif' => false
            ]);

        $this->info('Token ujian berhasil diperbarui.');
    }
}