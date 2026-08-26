<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MasterDataController;

Route::prefix('mobile')->group(function () {
    // Public routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\Api\Auth\RegisterController::class, 'register']);

    // Master Data (Wilayah & Jenis Retribusi untuk Mobile)
    Route::prefix('master')->group(function () {
        Route::get('/kecamatan', [MasterDataController::class, 'kecamatan']);
        Route::get('/desa/{kecamatan_id}', [MasterDataController::class, 'desaByKecamatan']);
        Route::get('/jenis-retribusi', [MasterDataController::class, 'jenisRetribusi']);
    });

    // Protected routes (Sanctum Token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
        // Warga Endpoints
        Route::prefix('warga')->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\Api\Warga\WargaDashboardController::class, 'index']);
            Route::post('/pengajuan', [\App\Http\Controllers\Api\Warga\PengajuanController::class, 'store']);
            Route::get('/pengajuan/status', [\App\Http\Controllers\Api\Warga\PengajuanController::class, 'status']);
        });
        
        // Petugas Endpoints
        Route::prefix('petugas')->group(function () {
            Route::get('/tagihan', [\App\Http\Controllers\Api\Petugas\TagihanController::class, 'index']);
            Route::post('/pembayaran/tunai', [\App\Http\Controllers\Api\Petugas\PembayaranController::class, 'tunai']);
            Route::post('/kunjungan', [\App\Http\Controllers\Api\Petugas\KunjunganController::class, 'store']);
            Route::get('/setoran/rekap', [\App\Http\Controllers\Api\Petugas\SetoranController::class, 'rekap']);
            Route::post('/setoran/submit', [\App\Http\Controllers\Api\Petugas\SetoranController::class, 'submit']);
        });
    });
});
