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
use App\Http\Controllers\PengaturanAkunController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\BankSoalController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\GuruBankSoalController;
use App\Http\Controllers\GuruDashboardController;



// Halaman awal langsung menampilkan form login
Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rute Dashboard Utama (Hanya bisa diakses jika sudah login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pengaturan-akun', [PengaturanAkunController::class, 'index'])->name('pengaturan-akun.index');
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

    Route::get(
        'guru-mapel/template',
        [GuruMapelController::class, 'downloadTemplate']
    )->name('guru-mapel.template');


    Route::get(
        'guru-mapel/export',
        [GuruMapelController::class,'export']
    )->name('guru-mapel.export');

    Route::post(
        'guru-mapel/import',
        [GuruMapelController::class,'import']
    )->name('guru-mapel.import');

    Route::resource('guru-mapel', GuruMapelController::class)
    ->parameters(['guru-mapel' => 'guru_mapel'])
    ->except('show');

    Route::resource('wali-kelas', WaliKelasController::class)
    ->parameters(['wali-kelas' => 'wali_kelas'])
    ->except('show');

    Route::get(
        'wali-kelas/template',
        [WaliKelasController::class, 'downloadTemplate']
    )->name('wali-kelas.template');

    Route::get(
        'wali-kelas/export',
        [WaliKelasController::class, 'export']
    )->name('wali-kelas.export');

    Route::post(
        'wali-kelas/import',
        [WaliKelasController::class, 'import']
    )->name('wali-kelas.import');

    Route::get('siswa-export', [SiswaController::class, 'export'])->name('siswa.export');
    Route::get('siswa-template-download', [SiswaController::class, 'downloadTemplate'])->name('siswa.template');
    Route::post('siswa-import', [SiswaController::class, 'import'])->name('siswa.import');

    Route::resource('siswa', SiswaController::class);

    Route::patch('ujian/{ujian}/toggle-token', [UjianController::class, 'toggleToken'])->name('ujian.toggle-token');
    Route::patch('ujian/{ujian}/regenerate-token', [UjianController::class, 'regenerateToken'])->name('ujian.regenerate-token');

    Route::resource('ujian', UjianController::class);
   
    Route::get('bank-soal', [BankSoalController::class, 'index'])->name('bank-soal.index');
    Route::get('bank-soal/{bankSoal}', [BankSoalController::class, 'show'])->name('bank-soal.show');
    Route::patch('bank-soal/{bankSoal}/toggle-publish', [BankSoalController::class, 'togglePublish'])->name('bank-soal.toggle-publish');
    Route::delete('bank-soal/{bankSoal}', [BankSoalController::class, 'destroy'])->name('bank-soal.destroy');


    Route::prefix('dashboard-guru')->name('dashboard-guru.')->group(function () {
        
        // Rute dashboard utama guru
        Route::get('/', [GuruDashboardController::class, 'index'])->name('index');
        
        // Kita gunakan nama rute yang berbeda agar tidak bentrok dengan rute bank-soal milik Admin
        Route::resource('bank-soal', GuruBankSoalController::class);

        // Guru butuh cara sendiri untuk unpublish bank soal miliknya
        Route::patch(
            'bank-soal/{bank_soal}/toggle-publish',
            [GuruBankSoalController::class, 'togglePublish']
        )->name('bank-soal.toggle-publish');

        Route::prefix('bank-soal/{bank_soal}')->name('bank-soal.')->group(function () {
            Route::get('soal', [SoalController::class, 'index'])->name('soal.index');
            Route::get('soal/create', [SoalController::class, 'create'])->name('soal.create');
            Route::post('soal', [SoalController::class, 'store'])->name('soal.store');
            Route::get('soal/{soal}/edit', [SoalController::class, 'edit'])->name('soal.edit');
            Route::put('soal/{soal}', [SoalController::class, 'update'])->name('soal.update');
            Route::delete('soal/{soal}', [SoalController::class, 'destroy'])->name('soal.destroy');
            Route::post('soal/import', [SoalController::class, 'import'])->name('soal.import');
        });
    });

});