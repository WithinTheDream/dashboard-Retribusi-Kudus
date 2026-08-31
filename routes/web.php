<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\WilayahController;
use App\Models\JenisRetribusi; // pastikan tetap aman
use App\Http\Controllers\Admin\JenisRetribusiController;
use App\Http\Controllers\Admin\TarifController;
use App\Http\Controllers\Admin\WajibRetribusiController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\Admin\TagihanController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PenggunaController;

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::get('/captcha-image', [AuthController::class, 'generateCaptcha'])
    ->name('captcha.image');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // 1. Utama: Dashboard
        Route::get('/dashboard', [
            DashboardController::class,
            'index'
        ])->name('dashboard');

        // 2. Data Master: Wilayah & Jenis Retribusi
        Route::resource(
            'wilayah', WilayahController::class
        );

        Route::resource(
            'jenis-retribusi',
            JenisRetribusiController::class
        );

        Route::resource(
            'tarif',
            TarifController::class
        );

        // 3. Data Operasional: Wajib Retribusi
        Route::resource(
            'wajib-retribusi',
            WajibRetribusiController::class
        );

        Route::get(
            'api/desa-by-kecamatan/{kecamatan}',
            [WajibRetribusiController::class, 'getDesaByKecamatan']
        )->name('api.desa-by-kecamatan');

        // 4. Data Operasional: Pengajuan
        Route::resource(
            'pengajuan',
            PengajuanController::class
        )->except(['show']);

        // 5. Data Operasional: Tagihan
        Route::post('tagihan/generate', [TagihanController::class, 'generate'])->name('tagihan.generate');
        Route::resource(
            'tagihan',
            TagihanController::class
        )->except(['show']);

        // 6. Data Operasional: Pembayaran & Setoran
        Route::resource(
            'pembayaran',
            PembayaranController::class
        )->except(['show']);

        Route::post('setoran/{setoran}/verify', [\App\Http\Controllers\Admin\SetoranController::class, 'verify'])->name('setoran.verify');
        Route::resource(
            'setoran',
            \App\Http\Controllers\Admin\SetoranController::class
        )->only(['index', 'show']);

        // Kelola Petugas Lapangan & Penugasan Wilayah
        Route::resource(
            'petugas',
            \App\Http\Controllers\Admin\PetugasController::class
        )->except(['show']);

        // 7. Laporan: Rekap Tagihan & Pembayaran
        Route::get(
            'laporan',
            [LaporanController::class, 'index']
        )->name('laporan.index');

        // 8. Sistem: Pengguna & Role
        Route::resource(
            'pengguna',
            PenggunaController::class
        )->except(['show']);

        Route::resource(
            'roles',
            \App\Http\Controllers\Admin\RoleController::class
        )->except(['show']);

        // 9. Master Konten: Banner Slideshow
        Route::resource(
            'banners',
            \App\Http\Controllers\Admin\BannerController::class
        )->except(['show']);

});
