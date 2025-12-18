<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Transaction;
use App\Models\TransactionDetail; // Tambahkan ini
use App\Models\Employee;
use App\Models\Product;
use App\Models\PresenceLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan Executive Dashboard untuk Admin dengan Chart Data.
     */
    public function __invoke(Request $request): View
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. STATISTIK UTAMA (Cards)
        $stats = [
            'income_today' => Transaction::whereDate('transaction_date', $today)->sum('total_amount'),
            'trx_today'    => Transaction::whereDate('transaction_date', $today)->count(),
            'income_month' => Transaction::whereMonth('transaction_date', $currentMonth)
                                         ->whereYear('transaction_date', $currentYear)
                                         ->sum('total_amount'),
            'active_employees' => PresenceLog::whereDate('created_at', $today)
                                             ->distinct('id_employee')
                                             ->count('id_employee')
        ];

        // 2. CHART DATA: OMZET 7 HARI TERAKHIR (Line Chart)
        $last7Days = collect(range(6, 0))->map(function($daysAgo) {
            return Carbon::now()->subDays($daysAgo)->format('Y-m-d');
        });

        $chartIncome = Transaction::selectRaw('DATE(transaction_date) as date, SUM(total_amount) as total')
            ->where('transaction_date', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date');

        // Format data agar siap dipakai Chart.js (Isi 0 jika hari tsb tidak ada transaksi)
        $chartLabels = $last7Days->map(fn($d) => Carbon::parse($d)->format('d M'));
        $chartValues = $last7Days->map(fn($d) => $chartIncome->get($d) ?? 0);

        // 3. CHART DATA: KOMPOSISI PENJUALAN (Doughnut Chart)
        // Berapa % Service vs Produk vs Makanan
        $composition = TransactionDetail::selectRaw('item_type, COUNT(*) as count')
            ->whereMonth('created_at', $currentMonth) // Data bulan ini saja
            ->groupBy('item_type')
            ->pluck('count', 'item_type');

        $pieLabels = ['Layanan', 'Produk', 'Makanan'];
        $pieValues = [
            $composition->get('service') ?? 0,
            $composition->get('product') ?? 0,
            $composition->get('food') ?? 0
        ];

        // 4. TRANSAKSI TERBARU (Live Feed)
        $recentTransactions = Transaction::with(['store', 'employee'])
            ->latest('transaction_date')
            ->limit(5)
            ->get();

        // 5. TOP PERFORMING EMPLOYEES (Bulan Ini)
        $topEmployees = Employee::withSum(['transactions' => function($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('transaction_date', $currentMonth)
                      ->whereYear('transaction_date', $currentYear);
            }], 'total_amount')
            ->orderByDesc('transactions_sum_total_amount')
            ->take(5)
            ->get();

        // 6. STOK MENIPIS
        $lowStockProducts = Product::with('store')
            ->where('stock_available', '<=', 10)
            ->orderBy('stock_available', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'recentTransactions', 
            'topEmployees', 
            'lowStockProducts',
            'chartLabels', // Kirim ke View
            'chartValues', // Kirim ke View
            'pieLabels',   // Kirim ke View
            'pieValues'    // Kirim ke View
        ));
    }
}