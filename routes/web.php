<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardRedirectController; // <-- 1. TAMBAHKAN INI
use App\Http\Controllers\Admin\DashboardController as AdminDashboard; // <-- 2. TAMBAHKAN INI
use App\Http\Controllers\Kasir\DashboardController as KasirDashboard; // <-- 3. TAMBAHKAN INI

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rute Pengalihan Dashboard Utama
|--------------------------------------------------------------------------
| Ini "membajak" rute /dashboard bawaan Breeze.
| Sekarang ia akan memanggil DashboardRedirectController kita.
*/
Route::get('/dashboard', DashboardRedirectController::class) // <-- 4. UBAH INI
    ->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Rute Grup Admin
|--------------------------------------------------------------------------
| Semua URL di sini akan dimulai dengan /admin
| dan hanya bisa diakses oleh user yang terotentikasi.
*/
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Rute: /admin/dashboard
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard'); // <-- 5. BUAT INI
    
    // (Nanti rute admin lain seperti /admin/laporan bisa ditambah di sini)
});

/*
|--------------------------------------------------------------------------
| Rute Grup Kasir
|--------------------------------------------------------------------------
| Semua URL di sini akan dimulai dengan /kasir
| dan hanya bisa diakses oleh user yang terotentikasi.
*/
Route::middleware(['auth', 'verified'])->prefix('kasir')->name('kasir.')->group(function () {
    
    // Rute: /kasir/dashboard
    Route::get('/dashboard', KasirDashboard::class)->name('dashboard'); // <-- 6. BUAT INI

    // (Nanti rute kasir lain seperti /kasir/transaksi bisa ditambah di sini)
});

/*
|--------------------------------------------------------------------------
| Rute Profil Bawaan Breeze
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';