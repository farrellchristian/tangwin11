<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardRedirectController; 
use App\Http\Controllers\Admin\DashboardController as AdminDashboard; 
use App\Http\Controllers\Kasir\DashboardController as KasirDashboard;
use App\Http\Controllers\Admin\InformationController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Kasir\ExpenseController as KasirExpenseController;
use App\Http\Controllers\Admin\ExpenseController as AdminExpenseController;

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
Route::get('/dashboard', DashboardRedirectController::class)
    ->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Rute Fitur Kasir (POS)
|--------------------------------------------------------------------------
| Bisa diakses oleh Admin dan Kasir
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');

    // Rute untuk menampilkan halaman pilih karyawan SETELAH admin pilih toko
    Route::get('/pos/select-employee/{store}', [PosController::class, 'showSelectEmployee'])
         ->name('pos.select-employee');

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/select-employee/{store}', [PosController::class, 'showSelectEmployee'])->name('pos.select-employee');

    // Rute untuk menampilkan halaman transaksi utama
    Route::get('/pos/transaction/{store}/{employee}', [PosController::class, 'showTransactionPage'])
         ->name('pos.transaction'); // <-- TAMBAHKAN INI

    // (Nanti rute simpan transaksi)
    Route::post('/pos/store-transaction', [PosController::class, 'storeTransaction'])
         ->name('pos.store-transaction');
});


/*
|--------------------------------------------------------------------------
| Rute Fitur Input Pengeluaran (Kasir)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('kasir/expenses')->name('kasir.expenses.')->group(function () {
    // Halaman pilih karyawan (jika kasir)
    Route::get('/select-employee', [KasirExpenseController::class, 'showSelectEmployee'])->name('select-employee');
    // Halaman input pengeluaran setelah pilih karyawan
    Route::get('/create/{employee}', [KasirExpenseController::class, 'create'])->name('create');
    // Simpan pengeluaran baru
    Route::post('/', [KasirExpenseController::class, 'store'])->name('store');
});


/*
|--------------------------------------------------------------------------
| Rute Grup Admin
|--------------------------------------------------------------------------
| Semua URL di sini akan dimulai dengan /admin
| dan hanya bisa diakses oleh user yang terotentikasi.
*/
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Rute: /admin/dashboard
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/informasi', InformationController::class)->name('informasi.index');
    
    // Rute CRUD Services
    Route::resource('services', ServiceController::class);
    // Rute CRUD Products
    Route::resource('products', ProductController::class);
    // Rute CRUD Foods
    Route::resource('foods', FoodController::class);
    // Rute CRUD Employees
    Route::resource('employees', EmployeeController::class);
    // Rute untuk Riwayat Pengeluaran & Setting Limit
    Route::get('/expenses', [AdminExpenseController::class, 'index'])->name('expenses.index');
    // Rute untuk menyimpan update limit karyawan (via AJAX/Fetch nanti)
    Route::put('/employees/{employee}/update-limit', [AdminExpenseController::class, 'updateLimit'])
         ->name('employees.update-limit');
    // Rute CRUD standar untuk mengelola data expense (KECUALI index)
    Route::resource('expenses', AdminExpenseController::class)->except(['index']);
    
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