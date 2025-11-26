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
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\PresenceScheduleController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\Admin\PresenceRecapController;
use App\Http\Controllers\ReservationSlotController;

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

    // Rute baru untuk menangani pembuatan pembayaran QRIS
    Route::post('/pos/payment/qris', [PosController::class, 'createQrisPayment'])
         ->name('pos.payment.qris');

    // Rute baru untuk mengecek status pembayaran
    Route::get('/pos/payment/status/{order_id}', [PosController::class, 'getPaymentStatus'])
        ->name('pos.payment.status');
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
| Rute Fitur Presensi (Karyawan)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('presence')->name('presence.')->group(function () {
    // Halaman utama presensi (pilih karyawan)
    Route::get('/', [PresenceController::class, 'index'])->name('index');
    // Aksi untuk check-in
    Route::post('/check-in', [PresenceController::class, 'checkIn'])->name('check-in');
    // Aksi untuk check-out (nanti)
    // Route::post('/check-out', [PresenceController::class, 'checkOut'])->name('check-out');
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
    Route::put('/employees/{employee}/update-limit', [AdminExpenseController::class, 'updateLimit']) ->name('employees.update-limit');
    // Rute CRUD standar untuk mengelola data expense (KECUALI index)
    Route::resource('expenses', AdminExpenseController::class)->except(['index']);
    // Rute CRUD Payment Methods
    Route::resource('payment-methods', PaymentMethodController::class);
    // Rute CRUD User Management
    Route::resource('users', UserController::class);
    // Rute CRUD Store Management
    Route::resource('stores', StoreController::class);
    // Rute untuk Soft Delete Transaksi
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    // Rute untuk CRUD Jadwal Presensi (Setting Presensi)
    Route::resource('presence-schedules', PresenceScheduleController::class);
    // Rute untuk Halaman Rekap Presensi
    Route::get('presence-recap', [PresenceRecapController::class, 'index']) ->name('presence-recap.index');
    // Rute API untuk mengambil karyawan berdasarkan toko
    Route::get('employees/by-store/{store}', [EmployeeController::class, 'getEmployeesByStore'])
         ->name('employees.by-store');

    // --- RUTE API UNTUK FILTER DINAMIS ---
    Route::prefix('expenses/filters')->name('expenses.filters.')->group(function () {
        Route::get('/months/{year}', [AdminExpenseController::class, 'getAvailableMonths'])->name('months');
        Route::get('/days/{year}/{month}', [AdminExpenseController::class, 'getAvailableDays'])->name('days');
        Route::get('/weeks/{year}/{month}', [AdminExpenseController::class, 'getAvailableWeeks'])->name('weeks');
    });
    // Rute untuk Halaman Laporan Utama
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    // --- RUTE API BARU UNTUK FILTER LAPORAN ---
    Route::prefix('reports/filters')->name('reports.filters.')->group(function () {
        Route::get('/months/{year}', [ReportController::class, 'getAvailableMonths'])->name('months');
        Route::get('/days/{year}/{month}', [ReportController::class, 'getAvailableDays'])->name('days');
        Route::get('/weeks/{year}/{month}', [ReportController::class, 'getAvailableWeeks'])->name('weeks');
    });

    // --- RUTE API BARU UNTUK MODAL LAPORAN ---
    Route::prefix('reports/details')->name('reports.details.')->group(function () {
        Route::get('/income', [ReportController::class, 'getIncomeDetails'])->name('income');
        Route::get('/expenditure', [ReportController::class, 'getExpenditureDetails'])->name('expenditure');
        Route::get('/profit-loss', [ReportController::class, 'getProfitLossDetails'])->name('profit-loss');
        Route::get('/transaction/{transaction}', [ReportController::class, 'getTransactionDetails'])->name('transaction');
        Route::get('/expense/{expense}', [AdminExpenseController::class, 'show']) ->name('expense');
    });

    // === MANAJEMEN JADWAL (SLOT) ===
    // Cukup tulis 'reservation' saja, karena sudah ada di dalam grup 'admin'
    Route::prefix('reservation')->name('reservation.')->group(function () {
        // Halaman Kelola Jadwal
        Route::get('/slots', [ReservationSlotController::class, 'index'])->name('slots.index');
        
        // Simpan Jadwal Baru (Generate)
        Route::post('/slots', [ReservationSlotController::class, 'store'])->name('slots.store');

        // --- TAMBAHKAN INI UNTUK UPDATE ---
        Route::put('/slots/{id}', [ReservationSlotController::class, 'update'])->name('slots.update');
        
        // Hapus Semua Jadwal (Reset)
        Route::delete('/slots/reset', [ReservationSlotController::class, 'destroyAll'])->name('slots.destroyAll');
        
        // Hapus Satu Slot
        Route::delete('/slots/{id}', [ReservationSlotController::class, 'destroy'])->name('slots.destroy');
    });
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