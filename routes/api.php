
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KapalController;
use App\Http\Controllers\Api\PemesananController;
use App\Http\Controllers\Api\PembayaranController;
use App\Models\Pembayaran;
use App\Http\Controllers\Api\Admin\PembayaranController as AdminPembayaran;
use App\Http\Controllers\Api\JadwalKeberangkatanController;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


// Kapal
Route::middleware('auth:api')->group(function () {
    Route::get('/kapals', [KapalController::class, 'index']);
    Route::post('/kapals', [KapalController::class, 'store']);
    Route::get('/kapals/{id}', [KapalController::class, 'show']);
    Route::put('/kapals/{id}', [KapalController::class, 'update']);
    Route::delete('/kapals/{id}', [KapalController::class, 'destroy']);
});


// 🔐 USER - hanya boleh buat pesanan (POST)
Route::middleware('auth:api')->group(function () {
    Route::get('/user/pemesanans', [PemesananController::class, 'getUser']);
    Route::post('/user/pemesanans', [PemesananController::class, 'store']);
});

// 🔐 ADMIN - full akses ke semua pemesanan
Route::middleware(['auth:api', \App\Http\Middleware\IsAdmin::class])->group(function () {
    Route::apiResource('pemesanans', PemesananController::class)->except(['store']);
});

//🔐 USER
Route::middleware('auth:api')->group(function () {
    Route::get('/user/pembayarans', [PembayaranController::class, 'getUser']);
    Route::post('/user/pembayarans', [PembayaranController::class, 'store']);
});


// 🔐 Admin - hanya boleh buat pembayaran
Route::middleware(['auth:api', \App\Http\Middleware\IsAdmin::class])->group(function () {
    Route::apiResource('pembayarans', PembayaranController::class)->except(['store', 'getUser']);
});


// 🔐 USER - jadwal_keberangakatan
Route::get('/user/jadwal-keberangkatan', [JadwalKeberangkatanController::class, 'getUser']);
Route::get('/user/jadwal-keberangkatan/{id}', [JadwalKeberangkatanController::class, 'showJadwal']);

// 🔐 Admin jadwal_keberangakatan
Route::middleware(['auth:api', \App\Http\Middleware\IsAdmin::class])->group(function () {
    Route::apiResource('jadwal-keberangkatan', JadwalKeberangkatanController::class)->except(['getUser', 'showJadwal']);
});
