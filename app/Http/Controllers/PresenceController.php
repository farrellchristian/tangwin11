<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Models\Employee;
use App\Models\Store;
use App\Models\PresenceSchedule;
use App\Models\PresenceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PresenceController extends Controller
{
    /**
     * Menampilkan halaman utama presensi (pilih karyawan).
     */
    public function index(): View
    {
        $user = Auth::user();
        $storeId = $user->id_store;

        if ($user->role === 'admin') {
            return redirect()->route('admin.presence-schedules.index')
                             ->with('info', 'Halaman presensi adalah untuk karyawan. Admin bisa mengatur jadwal di sini.');
        }

        if (!$storeId) {
             abort(403, 'Akun kasir tidak terhubung ke toko manapun.');
        }
        
        $employees = Employee::where('id_store', $storeId)
                             ->where('is_active', true) 
                             ->orderBy('employee_name')
                             ->get();
        
        $store = Store::find($storeId);

        // Ambil data log hari ini untuk ditampilkan di dropdown
        $todayLogs = PresenceLog::where('id_store', $storeId)
            ->whereDate('check_in_time', Carbon::today())
            ->get()
            ->keyBy('id_employee') 
            ->map(function ($log) {
                return $log->check_in_time->format('H:i'); 
            });

        return view('presence.index', [
            'employees' => $employees,
            'store' => $store,
            'todayLogs' => $todayLogs, 
        ]);
    }

    /**
     * Memproses check-in karyawan.
     */
    public function checkIn(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $storeId = $user->id_store;
        $karyawanIp = $request->ip(); 

        // 1. Validasi Input
        $request->validate([
            'id_employee' => 'required|exists:employees,id_employee',
        ], [
            'id_employee.required' => 'Anda harus memilih nama karyawan.',
        ]);

        $employeeId = $request->input('id_employee');
        $employee = Employee::find($employeeId);

        // 2. Validasi Keamanan: Karyawan harus dari toko si Kasir
        if (!$employee || $employee->id_store !== $storeId) {
            return redirect()->route('presence.index')
                             ->with('error', 'Karyawan tidak valid untuk toko ini.');
        }

        // 3. Validasi IP Address
        $store = Store::find($storeId);
        if ($store->enable_ip_validation && $store->store_ip_address) {
            $allowedIps = array_map('trim', explode(',', $store->store_ip_address));
            if (!in_array($karyawanIp, $allowedIps)) {
                return redirect()->route('presence.index')
                                 ->with('error', "Presensi ditolak. Anda tidak berada di jaringan WiFi toko yang terdaftar. IP Anda: $karyawanIp");
            }
        }

        // ==========================================================
        // --- PERBAIKAN LOGIKA #1 MULAI DI SINI ---
        // ==========================================================
        
        // 4. Validasi Jadwal (Logika Baru: Cari jadwal paling dekat)
        $now = Carbon::now();
        $todayDayOfWeek = $now->dayOfWeek; 
        $currentTime = $now->format('H:i:s'); 

        // Ambil SEMUA jadwal aktif hari ini
        $schedules = PresenceSchedule::where('id_store', $storeId)
                                      ->where('day_of_week', $todayDayOfWeek)
                                      ->where('is_active', true)
                                      ->get();
        
        if ($schedules->isEmpty()) {
            return redirect()->route('presence.index')
                             ->with('error', 'Tidak ada jadwal presensi aktif untuk hari ini.');
        }

        // Cari jadwal yang jam masuknya PALING DEKAT dengan jam sekarang
        $bestSchedule = null;
        $smallestDiff = PHP_INT_MAX; // Angka terbesar

        foreach ($schedules as $sch) {
            $jamMasuk = Carbon::parse($sch->jam_check_in);
            $jamSekarang = Carbon::parse($currentTime);
            
            // Hitung selisih absolut (jarak waktu)
            $absDiff = $jamSekarang->diffInSeconds($jamMasuk, true); // true = selisih absolut

            if ($absDiff < $smallestDiff) {
                $smallestDiff = $absDiff;
                $bestSchedule = $sch;
            }
        }
        
        // Gunakan jadwal terbaik yang ditemukan
        $schedule = $bestSchedule;

        if (!$schedule) {
            // Seharusnya tidak mungkin terjadi jika $schedules->isEmpty() lolos
             return redirect()->route('presence.index')
                             ->with('error', 'Gagal menentukan jadwal presensi yang sesuai.');
        }
        
        // ==========================================================
        // --- PERBAIKAN LOGIKA #1 SELESAI ---
        // ==========================================================


        // 5. Cek apakah sudah Check-in hari ini
        // Kita cek berdasarkan ID Karyawan DAN ID Jadwal (agar bisa absen di 2 shift)
        $existingLog = PresenceLog::where('id_employee', $employeeId)
                                  ->where('id_presence_schedule', $schedule->id_presence_schedule) // Cek spesifik di jadwal ini
                                  ->whereDate('check_in_time', $now->toDateString())
                                  ->first();

        if ($existingLog) {
            return redirect()->route('presence.index')
                             ->with('error', 'Anda sudah melakukan presensi masuk untuk jadwal ini pada jam ' . $existingLog->check_in_time->format('H:i'));
        }

        // 6. Tentukan Status Presensi (Tepat Waktu vs Terlambat)
        $jamMasukJadwal = Carbon::parse($schedule->jam_check_in);
        $jamAbsen = Carbon::parse($currentTime);
        
        // Beri toleransi keterlambatan (misal: 15 menit)
        $batasToleransi = $jamMasukJadwal->copy()->addMinutes(15); 

        $status = 'Tepat Waktu';
        $notes = 'Presensi masuk berhasil.';
        
        $redirectResponse = null; // Variabel untuk menyimpan redirect

        if ($jamAbsen->gt($batasToleransi)) { 
            
            // ==========================================================
            // --- PERBAIKAN LOGIKA #2 MULAI DI SINI (Kalkulasi Menit) ---
            // ==========================================================
            
            // Hitung selisih dalam DETIK (selalu positif)
            $selisihDetik = $jamAbsen->diffInSeconds($jamMasukJadwal);

            // Ubah detik ke menit dan BULATKAN KE ATAS
            $keterlambatan = (int) ceil($selisihDetik / 60);

            $status = 'Terlambat';
            $notes = "Terlambat $keterlambatan menit.";
            
            // ==========================================================
            // --- PERBAIKAN LOGIKA #2 SELESAI ---
            // ==========================================================
            
            // Notifikasi ORANYE untuk terlambat
            $redirectResponse = redirect()->route('presence.index')
                                       ->with('late', "Presensi berhasil! Status: $status. $notes");

        } else { 
            // Notifikasi HIJAU untuk tepat waktu
            $redirectResponse = redirect()->route('presence.index')
                                       ->with('success', "Presensi berhasil! Status: $status. $notes");
        }


        // 7. Simpan Log Presensi
        try {
            PresenceLog::create([
                'id_employee' => $employeeId,
                'id_store' => $storeId,
                'id_presence_schedule' => $schedule->id_presence_schedule, // Simpan ID jadwal yang benar
                'check_in_time' => $now, 
                'check_out_time' => null,
                'status' => $status,
                'notes' => $notes,
                'ip_address' => $karyawanIp,
            ]);

            return $redirectResponse; // Kembalikan notifikasi yang sudah disiapkan

        } catch (\Exception $e) {
             \Log::error('Error saving presence log: ' . $e->getMessage());
            return redirect()->route('presence.index')
                             ->with('error', 'Terjadi kesalahan database saat menyimpan presensi.');
        }
    }
}