<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\PresenceLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Khusus Kasir dengan Data Statistik
     */
    public function __invoke(Request $request): View
    {
        $user = Auth::user();
        $today = Carbon::today();

        // 1. Ambil Statistik Transaksi Harian (Khusus User ini)
        // Kita hitung berapa transaksi yg dia input hari ini
        $dailyTransactions = Transaction::where('id_user', $user->id) // Asumsi 'id_user' menyimpan siapa kasirnya
            ->whereDate('transaction_date', $today)
            ->count();

        // 2. Ambil Total Omzet Harian (Khusus User ini)
        // Berapa uang yang dia terima hari ini
        $dailyRevenue = Transaction::where('id_user', $user->id)
            ->whereDate('transaction_date', $today)
            ->sum('total_amount');

        // 3. Cek Status Presensi Hari Ini
        // Apakah dia sudah absen masuk?
        $presence = PresenceLog::where('id_employee', $user->id_employee ?? 0) // Asumsi user terhubung ke employee
             ->whereDate('created_at', $today)
             ->first();
        
        $hasClockedIn = $presence && $presence->check_in_time;

        // 4. Ambil 5 Transaksi Terakhir
        $recentTransactions = Transaction::with('store')
            ->where('id_user', $user->id)
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();

        return view('kasir.dashboard', compact(
            'dailyTransactions', 
            'dailyRevenue', 
            'hasClockedIn', 
            'recentTransactions'
        ));
    }
}