<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Cek dulu apakah tabel 'tahun_ajarans' sudah dimigrasi agar tidak error saat pertama kali migrate
        if (Schema::hasTable('tahun_ajarans')) {
            $tahunAktif = DB::table('tahun_ajarans')->where('is_aktif', true)->first();
            
            // Bagikan variabel $tahunAktif ke semua halaman view
            View::share('tahunAktif', $tahunAktif);
        }

        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
    }
}