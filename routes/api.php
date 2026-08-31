<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PengajuanController;
use App\Http\Controllers\Api\MasterDataController;

Route::prefix('mobile')->group(function () {
    // Public Master Data routes
    Route::prefix('master')->group(function () {
        Route::get('/jenis-retribusi', [MasterDataController::class, 'jenisRetribusi']);
        Route::get('/kecamatan', [MasterDataController::class, 'kecamatan']);
        Route::get('/desa/{kecamatanId}', [MasterDataController::class, 'desa']);
    });

    // Public Auth routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\Api\Auth\RegisterController::class, 'register']);
    Route::get('/banners', [\App\Http\Controllers\Api\BannerApiController::class, 'index']);

    // Protected routes (Butuh Token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
        // Warga Endpoints
        Route::prefix('warga')->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\Api\Warga\WargaDashboardController::class, 'index']);
            Route::post('/pengajuan', [PengajuanController::class, 'store']);
            Route::get('/pengajuan/status', [PengajuanController::class, 'status']);
        });
        
        // Petugas Endpoints
        Route::prefix('petugas')->group(function () {
            Route::get('/tagihan', [\App\Http\Controllers\Api\Petugas\TagihanController::class, 'index']);
            Route::post('/pembayaran/tunai', [\App\Http\Controllers\Api\Petugas\PembayaranController::class, 'tunai']);
            Route::get('/setoran/rekap', [\App\Http\Controllers\Api\Petugas\SetoranController::class, 'rekap']);
            Route::post('/setoran/submit', [\App\Http\Controllers\Api\Petugas\SetoranController::class, 'submit']);
        });
    });
});
