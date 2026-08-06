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
use App\Http\Controllers\GuruJadwalUjianController;
use App\Http\Controllers\GuruNilaiSiswaController;
use App\Http\Controllers\GuruWaliKelasController;


use App\Http\Controllers\Siswa\UjianHariIniController;
use App\Http\Controllers\Siswa\RuangUjianController;


// Halaman awal langsung menampilkan form login
Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rute Dashboard Utama (Hanya bisa diakses jika sudah login)
Route::middleware(['auth'])->group(function () {
    
    // =========================================================================
    // 1. RUTE UMUM (BISA DIAKSES OLEH SEMUA ROLE)
    // =========================================================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pengaturan-akun', [PengaturanAkunController::class, 'index'])->name('pengaturan-akun.index');
    Route::post('/pengaturan-akun/password', [PengaturanAkunController::class, 'updatePassword'])->name('pengaturan-akun.update-password');
    Route::post('/pengaturan-akun/foto', [PengaturanAkunController::class, 'updateFoto'])->name('pengaturan-akun.update-foto');
    Route::delete('/pengaturan-akun/foto', [PengaturanAkunController::class, 'destroyFoto'])->name('pengaturan-akun.destroy-foto');

    // =========================================================================
    // 2. RUTE KHUSUS ADMIN (SUPER ADMIN & ADMIN JENJANG)
    // =========================================================================
    Route::middleware(['role:super_admin,admin_jenjang'])->group(function () {
        Route::resource('jenjang', JenjangController::class);
        
        Route::resource('tahun-ajaran', TahunAjaranController::class);
        Route::patch('tahun-ajaran/{tahunAjaran}/aktifkan', [TahunAjaranController::class, 'aktifkan'])->name('tahun-ajaran.aktifkan');
        Route::patch('tahun-ajaran/{tahunAjaran}/nonaktifkan', [TahunAjaranController::class, 'nonaktifkan'])->name('tahun-ajaran.nonaktifkan');
        
        Route::resource('jenis-ujian', JenisUjianController::class);
        Route::patch('jenis-ujian/{jenisUjian}/aktifkan', [JenisUjianController::class, 'aktifkan'])->name('jenis-ujian.aktifkan');
        Route::patch('jenis-ujian/{jenisUjian}/nonaktifkan', [JenisUjianController::class, 'nonaktifkan'])->name('jenis-ujian.nonaktifkan');
        
        Route::resource('tingkat', TingkatController::class);
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
        Route::resource('mata-pelajaran', MataPelajaranController::class)->except('show');
        
        // Hanya Super Admin yang bisa mengelola Admin Jenjang
        Route::middleware(['role:super_admin'])->group(function() {
            Route::resource('admin-jenjang', AdminJenjangController::class)
                ->parameters(['admin-jenjang' => 'admin'])
                ->except('show');
        });

        Route::get('guru-template-download', [GuruController::class, 'downloadTemplate'])->name('guru.template');
        Route::post('guru-import', [GuruController::class, 'import'])->name('guru.import');
        Route::resource('guru', GuruController::class);
        
        Route::get('guru-mapel/template', [GuruMapelController::class, 'downloadTemplate'])->name('guru-mapel.template');
        Route::get('guru-mapel/export', [GuruMapelController::class,'export'])->name('guru-mapel.export');
        Route::post('guru-mapel/import', [GuruMapelController::class,'import'])->name('guru-mapel.import');
        Route::resource('guru-mapel', GuruMapelController::class)->parameters(['guru-mapel' => 'guru_mapel']);
        
        Route::get('wali-kelas/template', [WaliKelasController::class, 'downloadTemplate'])->name('wali-kelas.template');
        Route::get('wali-kelas/export', [WaliKelasController::class, 'export'])->name('wali-kelas.export');
        Route::post('wali-kelas/import', [WaliKelasController::class, 'import'])->name('wali-kelas.import');
        Route::resource('wali-kelas', WaliKelasController::class)->parameters(['wali-kelas' => 'wali_kelas'])->except('show');
        
        Route::get('siswa-export', [SiswaController::class, 'export'])->name('siswa.export');
        Route::get('siswa-template-download', [SiswaController::class, 'downloadTemplate'])->name('siswa.template');
        Route::post('siswa-import', [SiswaController::class, 'import'])->name('siswa.import');
        Route::get('siswa-export-kartu-pdf', [SiswaController::class, 'exportKartuPdf'])->name('siswa.export-kartu-pdf');
        Route::get('siswa/{siswa}/kartu-pdf', [SiswaController::class, 'cetakKartuSinglePdf'])->name('siswa.kartu-pdf');
        Route::patch('siswa/{siswa}/reset-password', [SiswaController::class, 'resetPassword'])->name('siswa.reset-password');
        Route::resource('siswa', SiswaController::class);
        
        Route::patch('ujian/{ujian}/regenerate-token', [UjianController::class, 'regenerateToken'])->name('ujian.regenerate-token');
        Route::patch('ujian/{ujian}/publish-nilai', [UjianController::class, 'publishNilai'])->name('ujian.publish-nilai');
        Route::patch('ujian/{ujian}/unpublish-nilai', [UjianController::class, 'unpublishNilai'])->name('ujian.unpublish-nilai');
        Route::post('ujian/batch-publish-nilai', [UjianController::class, 'batchPublishNilai'])->name('ujian.batch-publish-nilai');
        Route::resource('ujian', UjianController::class);

        Route::get('bank-soal', [BankSoalController::class, 'index'])->name('bank-soal.index');
        Route::get('bank-soal/{bankSoal}', [BankSoalController::class, 'show'])->name('bank-soal.show');
        Route::patch('bank-soal/{bankSoal}/toggle-publish', [BankSoalController::class, 'togglePublish'])->name('bank-soal.toggle-publish');
        Route::delete('bank-soal/{bankSoal}', [BankSoalController::class, 'destroy'])->name('bank-soal.destroy');
    });

    // =========================================================================
    // 3. RUTE KHUSUS GURU
    // =========================================================================
    Route::middleware(['role:guru'])->prefix('dashboard-guru')->name('dashboard-guru.')->group(function () {
        Route::get('/', [GuruDashboardController::class, 'index'])->name('index');
        Route::resource('bank-soal', GuruBankSoalController::class);

        Route::get('jadwal-ujian', [GuruJadwalUjianController::class, 'index'])->name('jadwal-ujian.index');
        Route::get('jadwal-ujian/{id}', [GuruJadwalUjianController::class, 'show'])->name('jadwal-ujian.show');

        Route::patch('bank-soal/{bank_soal}/toggle-publish', [GuruBankSoalController::class, 'togglePublish'])->name('bank-soal.toggle-publish');
        Route::post('bank-soal/{bank_soal}/duplicate', [GuruBankSoalController::class, 'duplicate'])->name('bank-soal.duplicate');

        Route::prefix('bank-soal/{bank_soal}')->name('bank-soal.')->group(function () {
            Route::get('soal', [SoalController::class, 'index'])->name('soal.index');
            Route::get('soal/create', [SoalController::class, 'create'])->name('soal.create');
            Route::post('soal', [SoalController::class, 'store'])->name('soal.store');
            Route::get('soal/{soal}/edit', [SoalController::class, 'edit'])->name('soal.edit');
            Route::put('soal/{soal}', [SoalController::class, 'update'])->name('soal.update');
            Route::delete('soal/{soal}', [SoalController::class, 'destroy'])->name('soal.destroy');
            Route::post('soal/import', [SoalController::class, 'import'])->name('soal.import');
            Route::get('soal/template', [SoalController::class, 'downloadTemplate'])->name('soal.template');
        });

        Route::prefix('wali-kelas')->name('wali-kelas.')->group(function () {
            Route::get('data-kelas', [GuruWaliKelasController::class, 'dataKelas'])->name('data-kelas');
            Route::get('data-kelas/{id}', [GuruWaliKelasController::class, 'showSiswa'])->name('data-kelas.show-siswa');
            Route::get('monitoring-siswa', [GuruWaliKelasController::class, 'monitoringSiswa'])->name('monitoring-siswa');
            Route::post('monitoring-siswa/{nilai}/force-submit', [GuruWaliKelasController::class, 'forceSubmit'])->name('monitoring-siswa.force-submit');
            Route::post('monitoring-siswa/{nilai}/reset', [GuruWaliKelasController::class, 'resetUjian'])->name('monitoring-siswa.reset');
            Route::get('rekap-nilai/export', [GuruWaliKelasController::class, 'exportRekap'])->name('rekap-nilai.export');
            Route::get('rekap-nilai/export-pdf', [GuruWaliKelasController::class, 'exportPdf'])->name('rekap-nilai.export-pdf');
            Route::get('rekap-nilai/siswa/{siswa}', [GuruWaliKelasController::class, 'detailNilaiSiswa'])->name('rekap-nilai.detail-siswa');
            Route::get('rekap-nilai/siswa/{siswa}/export-pdf', [GuruWaliKelasController::class, 'exportPdfSiswa'])->name('rekap-nilai.export-pdf-siswa');
            Route::get('rekap-nilai', [GuruWaliKelasController::class, 'rekapNilai'])->name('rekap-nilai');
        });

        Route::prefix('nilai-siswa')->name('nilai-siswa.')->group(function () {
            Route::get('/', [GuruNilaiSiswaController::class, 'index'])->name('index');
            Route::get('/{ujian}', [GuruNilaiSiswaController::class, 'show'])->name('show');
            Route::get('/{ujian}/export-pdf', [GuruNilaiSiswaController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/{ujian}/export-excel', [GuruNilaiSiswaController::class, 'exportExcel'])->name('export-excel');
            Route::get('/{ujian}/koreksi/{siswa}', [GuruNilaiSiswaController::class, 'koreksi'])->name('koreksi');
            Route::get('/{ujian}/koreksi/{siswa}/export-pdf', [GuruNilaiSiswaController::class, 'exportSiswaPdf'])->name('koreksi.export-pdf');
            Route::post('/{ujian}/koreksi/{siswa}', [GuruNilaiSiswaController::class, 'storeKoreksi'])->name('store-koreksi');
        });
    });

    // =========================================================================
    // 4. RUTE KHUSUS SISWA
    // =========================================================================
    Route::middleware(['role:siswa'])->prefix('dashboard-siswa')->name('dashboard-siswa.')->group(function () {
        Route::get('scan-token', [\App\Http\Controllers\Siswa\ScanTokenController::class, 'index'])->name('scan-token.index');
        Route::post('scan-token', [\App\Http\Controllers\Siswa\ScanTokenController::class, 'cariUjian'])->name('scan-token.proses');
        Route::post('scan-token/{ujian}/konfirmasi', [\App\Http\Controllers\Siswa\ScanTokenController::class, 'konfirmasi'])->name('scan-token.konfirmasi');

        Route::get('ujian-hari-ini', [UjianHariIniController::class, 'index'])->name('ujian-hari-ini');
        Route::get('hasil-nilai', [\App\Http\Controllers\Siswa\HasilNilaiController::class, 'index'])->name('hasil-nilai.index');

        Route::get('ujian/{ujian}/mulai', [RuangUjianController::class, 'mulai'])->name('ujian.mulai');
        Route::post('ujian/{ujian}/proses-masuk', [RuangUjianController::class, 'prosesMasuk'])->name('ujian.proses-masuk');
        Route::get('ujian/{ujian}/kerja', [RuangUjianController::class, 'kerja'])->name('ujian.kerja');
        Route::post('ujian/{ujian}/submit', [RuangUjianController::class, 'submit'])->name('ujian.submit');
        Route::post('ujian/autosave', [RuangUjianController::class, 'autoSave'])->name('ujian.autosave');
        Route::post('ujian/current-question', [RuangUjianController::class, 'saveCurrentQuestion'])->name('ujian.current-question');
        Route::post('ujian/violation', [RuangUjianController::class, 'violation'])->name('ujian.violation');
    });
});