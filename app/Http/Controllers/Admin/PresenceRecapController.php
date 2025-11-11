<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PresenceLog;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB; // <-- TAMBAHKAN INI

class PresenceRecapController extends Controller
{
    /**
     * Menampilkan halaman rekap presensi (index).
     */
    public function index(Request $request): View
    {
        // 1. Ambil input filter dari request
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', Carbon::now()->toDateString());
        
        $selectedStoreId = $request->input('store_id');
        $selectedEmployeeId = $request->input('employee_id');

        // 2. Siapkan query dasar
        $logsQuery = PresenceLog::with(['employee', 'store', 'schedule'])
                            ->whereDate('check_in_time', '>=', $dateFrom)
                            ->whereDate('check_in_time', '<=', $dateTo);

        // 3. Terapkan filter jika ada
        if ($selectedStoreId) {
            $logsQuery->where('id_store', $selectedStoreId);
        }
        
        if ($selectedEmployeeId) {
            $logsQuery->where('id_employee', $selectedEmployeeId);
        }

        $statsQuery = clone $logsQuery;

        // Hitung 4 angka statistik berdasarkan filter
        $totalLogs = $statsQuery->count();
        $uniqueEmployees = $statsQuery->distinct('id_employee')->count('id_employee');
        $totalLate = $statsQuery->where('status', 'Terlambat')->count();
        
        // Untuk total menit terlambat, kita perlu mengekstrak angka dari 'notes'
        // Ini sedikit 'tricky' tapi "gacor"
        $totalMinutesLate = (int) $statsQuery
            ->where('status', 'Terlambat')
            ->sum(DB::raw("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(notes, ' ', 2), ' ', -1) AS UNSIGNED)"));
            
        // Kumpulkan statistik dalam satu array
        $summaryStats = [
            'totalLogs' => $totalLogs,
            'uniqueEmployees' => $uniqueEmployees,
            'totalLate' => $totalLate,
            'totalMinutesLate' => $totalMinutesLate,
        ];

        // 5. Ambil data (tabel utama) dengan pagination
        $logs = $logsQuery->orderBy('check_in_time', 'desc') // Urutkan dari terbaru
                         ->paginate(25)
                         ->withQueryString();

        // 6. Ambil data untuk dropdown filter
        $stores = Store::where('is_active', true)->orderBy('store_name')->get();
        
        $employeesQuery = Employee::where('is_active', true)->orderBy('employee_name');
        if ($selectedStoreId) {
            $employeesQuery->where('id_store', $selectedStoreId);
        }
        $employees = $employeesQuery->get();


        // 7. Kirim semua data ke view
        return view('admin.presence-recap.index', [
            'logs' => $logs,
            'stores' => $stores,
            'employees' => $employees,
            'filters' => [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'store_id' => $selectedStoreId,
                'employee_id' => $selectedEmployeeId,
            ],
            'summaryStats' => $summaryStats,
        ]);
    }
}