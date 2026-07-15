<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenjangController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\JenisUjianController;
use App\Http\Controllers\TingkatController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\AdminJenjangController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\GuruMapelController;
use App\Http\Controllers\WaliKelasController;


// Halaman awal langsung menampilkan form login
Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rute Dashboard Utama (Hanya bisa diakses jika sudah login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('jenjang', JenjangController::class);
    Route::resource('tahun-ajaran', TahunAjaranController::class);

    Route::patch(
        'tahun-ajaran/{tahunAjaran}/aktifkan',
        [TahunAjaranController::class, 'aktifkan']
    )->name('tahun-ajaran.aktifkan');

    Route::patch(
        'tahun-ajaran/{tahunAjaran}/nonaktifkan',
        [TahunAjaranController::class, 'nonaktifkan']
    )->name('tahun-ajaran.nonaktifkan');

    Route::resource('jenis-ujian', JenisUjianController::class);

    Route::patch(
        'jenis-ujian/{jenisUjian}/aktifkan',
        [JenisUjianController::class, 'aktifkan']
    )->name('jenis-ujian.aktifkan');

    Route::patch(
        'jenis-ujian/{jenisUjian}/nonaktifkan',
        [JenisUjianController::class, 'nonaktifkan']
    )->name('jenis-ujian.nonaktifkan');

    Route::resource('tingkat', TingkatController::class);

    Route::resource('kelas', KelasController::class)
    ->parameters([
        'kelas' => 'kelas',
    ]);

    Route::resource('mata-pelajaran', MataPelajaranController::class)
    ->except('show');

    Route::resource('admin-jenjang', AdminJenjangController::class)
    ->parameters(['admin-jenjang' => 'admin'])
    ->except('show');

    Route::get('guru-template-download', [GuruController::class, 'downloadTemplate'])->name('guru.template');
    Route::post('guru-import', [GuruController::class, 'import'])->name('guru.import');


    Route::resource('guru', GuruController::class);

    Route::resource('guru-mapel', GuruMapelController::class)
    ->parameters(['guru-mapel' => 'guru_mapel'])
    ->except('show');

    Route::resource('wali-kelas', WaliKelasController::class)
    ->parameters(['wali-kelas' => 'wali_kelas'])
    ->except('show');
    
});