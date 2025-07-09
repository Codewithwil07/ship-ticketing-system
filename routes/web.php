<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// 🔓 Halaman depan
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 🔐 Dashboard umum (user & admin) — non protected sementara
Route::view('/dashboard', 'dashboard');

// ⚙️ Group route admin (hanya untuk role 'admin') — sementara lepas auth
Route::middleware([IsAdmin::class])
    ->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });

// 🚧 Optional: dummy route login biar gak error
Route::get('/login', function () {
    return response()->json(['message' => 'Unauthorized'], 401);
})->name('login');
