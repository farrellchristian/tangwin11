<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PresenceLog;
use App\Models\Store;
use App\Models\PresenceSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

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
        
        // Untuk total menit terlambat, gunakan kolom late_minutes
        $totalMinutesLate = (int) $statsQuery
            ->where('status', 'Terlambat')
            ->sum('late_minutes');
            
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

    /**
     * Menampilkan form edit presensi.
     */
    public function edit(string $id): View|RedirectResponse
    {
        $log = PresenceLog::with(['employee', 'store', 'schedule'])->findOrFail($id);

        // Ambil data pendukung untuk dropdown
        $employees = Employee::where('id_store', $log->id_store)->where('is_active', true)->get();
        // Jadwal yang tersedia untuk toko ini pada hari tersebut
        $dayOfWeek = Carbon::parse($log->check_in_time)->dayOfWeek;
        $schedules = PresenceSchedule::where('id_store', $log->id_store)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->get();

        return view('admin.presence-recap.edit', compact('log', 'employees', 'schedules'));
    }

    /**
     * Memperbarui data presensi.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'id_employee' => 'required|exists:employees,id_employee',
            'check_in_time' => 'required|date',
            'id_presence_schedule' => 'nullable|exists:presence_schedules,id_presence_schedule',
            'notes' => 'nullable|string',
        ]);

        $log = PresenceLog::findOrFail($id);
        
        $employeeId = $request->input('id_employee');
        $checkInTimeInput = $request->input('check_in_time');
        $scheduleId = $request->input('id_presence_schedule');
        
        $log->id_employee = $employeeId;
        $log->check_in_time = $checkInTimeInput;
        $log->id_presence_schedule = $scheduleId;
        $log->notes = $request->input('notes');

        // Kalkulasi ulang status dan menit terlambat jika ada jadwal
        if ($scheduleId) {
            $schedule = PresenceSchedule::find($scheduleId);
            $jamAbsen = Carbon::parse($checkInTimeInput);
            $jamMasukJadwal = Carbon::parse($schedule->jam_check_in);
            
            $thresholdMenit = (int) $schedule->late_threshold;
            $batasToleransi = $jamMasukJadwal->copy()->addMinutes($thresholdMenit);

            if ($thresholdMenit > 0 && $jamAbsen->gt($batasToleransi)) {
                $selisihDetik = $jamMasukJadwal->diffInSeconds($jamAbsen);
                $lateMinutes = (int) ceil(abs($selisihDetik) / 60);
                $log->status = 'Terlambat';
                $log->late_minutes = $lateMinutes;
                // Update notes jika perlu, atau biarkan catatan admin
                if (!$log->notes) {
                    $log->notes = "Terlambat $lateMinutes menit (Toleransi: $thresholdMenit menit).";
                }
            } else {
                $log->status = 'Tepat Waktu';
                $log->late_minutes = 0;
            }
        } else {
            // Jika tidak ada jadwal, default ke Tepat Waktu atau biarkan status sebelumnya
            $log->status = 'Tepat Waktu';
            $log->late_minutes = 0;
        }

        $log->save();

        return redirect()->route('admin.presence-recap.index')
            ->with('success', 'Data presensi berhasil diperbarui dan status telah dihitung ulang.');
    }

    /**
     * Menghapus data presensi (Soft Delete).
     */
    public function destroy(string $id): RedirectResponse
    {
        $log = PresenceLog::findOrFail($id);
        $log->delete();

        return redirect()->route('admin.presence-recap.index')
            ->with('success', 'Data presensi berhasil dihapus (Soft Delete).');
    }
}