<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\IsAdmin;


// 🔐 AUTH (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::get('/register', fn() => view('auth.register'))->name('register');
});

// 🔓 LOGOUT
Route::get('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// 👤 USER AREA

// USER
Route::get('/', fn() => view('user.home'))->name('user.home');

// ADMIN
Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
Route::get('/admin/kapal', fn() => view('admin.kapal.index'))->name('admin.kapal.index');
Route::get('/admin/jadwal', fn() => view('admin.jadwal.index'))->name('admin.jadwal.index');
