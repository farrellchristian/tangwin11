<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // <-- Pastikan ini ada
use App\Models\PresenceSchedule;
use App\Models\Store; // <-- Kita perlu Store untuk filter & form
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; // <-- Pastikan ini ada
use Carbon\Carbon; // <-- Kita perlu Carbon untuk cek hari ini
use Illuminate\Validation\Rule; // <-- Kita perlu Rule untuk validasi

class PresenceScheduleController extends Controller
{
    // Rute kita sudah dilindungi middleware role:admin, jadi __construct() tidak perlu.

    /**
     * Menampilkan daftar semua jadwal presensi (Jadwal Presensi).
     */
    public function index(Request $request): View
    {
        // Ambil toko aktif (bukan Office) untuk filter
        $stores = Store::where('is_active', true)->where('store_name', '!=', 'Office')->get();
        
        // Tentukan toko yang dipilih. Default ke toko pertama jika ada.
        $selectedStoreId = $request->input('store_id', $stores->first()->id_store ?? null); 

        // Ambil jadwal berdasarkan filter toko
        $schedulesQuery = PresenceSchedule::with('store')
                            ->orderBy('day_of_week') // Urutkan berdasarkan hari
                            ->orderBy('jam_check_in');
        
        if ($selectedStoreId) {
             $schedulesQuery->where('id_store', $selectedStoreId);
        }

        $schedules = $schedulesQuery->paginate(15)->withQueryString();

        // Hitung "Jadwal Presensi Aktif" (sesuai image_aa62c6.png)
        $todayDayOfWeek = Carbon::now()->dayOfWeek; // 0=Minggu, 1=Senin, ..., 6=Sabtu
        
        $activeTodayCountQuery = PresenceSchedule::where('day_of_week', $todayDayOfWeek)
                                        ->where('is_active', true);
        
        if ($selectedStoreId) {
            $activeTodayCount = (clone $activeTodayCountQuery)->where('id_store', $selectedStoreId)->count();
        } else {
            // Jika filter "Semua Toko" (selectedStoreId = null)
            $activeTodayCount = $activeTodayCountQuery->count();
        }
        
        return view('admin.presence-schedules.index', [
            'schedules' => $schedules,
            'stores' => $stores,
            'selectedStoreId' => $selectedStoreId,
            'activeTodayCount' => $activeTodayCount,
        ]);
    }

    /**
     * Menampilkan form untuk membuat jadwal presensi baru.
     */
    public function create(): View
    {
        $stores = Store::where('is_active', true)->where('store_name', '!=', 'Office')->get();
        $daysOfWeek = [
            '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', 
            '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '0' => 'Minggu'
        ];
        
        // INI AKAN MEMANGGIL VIEW ANDA YANG SUDAH BENAR
        return view('admin.presence-schedules.create', compact('stores', 'daysOfWeek'));
    }

    /**
     * Menyimpan jadwal presensi baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_store' => 'required|exists:stores,id_store',
            'day_of_week' => [
                'required',
                'integer',
                'between:0,6',
            ],
            'jam_check_in' => [ // Validasi custom untuk overlap
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    $newStartTime = $value;
                    $newEndTime = $request->input('jam_check_out');

                    // Cek jika ada jadwal lain yang tumpang tindih
                    $existingOverlap = PresenceSchedule::where('id_store', $request->input('id_store'))
                        ->where('day_of_week', $request->input('day_of_week'))
                        ->where(function ($query) use ($newStartTime, $newEndTime) {
                            $query->where('jam_check_in', '<', $newEndTime) // old_start < new_end
                                  ->where('jam_check_out', '>', $newStartTime); // old_end > new_start
                        })
                        ->exists(); 

                    if ($existingOverlap) {
                        $fail('Jam jadwal tumpang tindih dengan jadwal lain yang sudah ada di hari ini.');
                    }
                }
            ],
            'jam_check_out' => 'required|date_format:H:i|after:jam_check_in',
            'is_active' => 'required|boolean',
        ], [
            'jam_check_out.after' => 'Jam pulang harus setelah jam masuk.',
        ]);

        PresenceSchedule::create($validated);

        return redirect()->route('admin.presence-schedules.index')
                         ->with('success', 'Jadwal presensi baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource. (Tidak kita pakai)
     */
    public function show(PresenceSchedule $presenceSchedule)
    {
        return redirect()->route('admin.presence-schedules.edit', $presenceSchedule->id_presence_schedule);
    }

    /**
     * Menampilkan form untuk mengedit jadwal presensi.
     */
    public function edit(PresenceSchedule $presenceSchedule): View
    {
        $stores = Store::where('is_active', true)->where('store_name', '!=', 'Office')->get();
        $daysOfWeek = [
            '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', 
            '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '0' => 'Minggu'
        ];

        return view('admin.presence-schedules.edit', [
            'schedule' => $presenceSchedule,
            'stores' => $stores,
            'daysOfWeek' => $daysOfWeek
        ]);
    }

    /**
     * Memperbarui jadwal presensi di database.
     */
    public function update(Request $request, PresenceSchedule $presenceSchedule): RedirectResponse
    {
        $validated = $request->validate([
            'id_store' => 'required|exists:stores,id_store',
            'day_of_week' => [
                'required',
                'integer',
                'between:0,6',
            ],
            'jam_check_in' => [ // Validasi custom untuk overlap
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request, $presenceSchedule) {
                    $newStartTime = $value;
                    $newEndTime = $request->input('jam_check_out');
                    $currentScheduleId = $presenceSchedule->id_presence_schedule; 

                    $existingOverlap = PresenceSchedule::where('id_store', $request->input('id_store'))
                        ->where('day_of_week', $request->input('day_of_week'))
                        ->where('id_presence_schedule', '!=', $currentScheduleId) // Abaikan diri sendiri
                        ->where(function ($query) use ($newStartTime, $newEndTime) {
                            $query->where('jam_check_in', '<', $newEndTime) 
                                  ->where('jam_check_out', '>', $newStartTime);
                        })
                        ->exists();

                    if ($existingOverlap) {
                        $fail('Jam jadwal tumpang tindih dengan jadwal lain yang sudah ada di hari ini.');
                    }
                }
            ],
            'jam_check_out' => 'required|date_format:H:i|after:jam_check_in',
            'is_active' => 'required|boolean',
        ], [
            'jam_check_out.after' => 'Jam pulang harus setelah jam masuk.'
        ]);

        $presenceSchedule->update($validated);

        return redirect()->route('admin.presence-schedules.index')
                         ->with('success', 'Jadwal presensi berhasil diperbarui.');
    }

    /**
     * Menghapus jadwal presensi.
     */
    public function destroy(PresenceSchedule $presenceSchedule): RedirectResponse
    {
        try {
            $presenceSchedule->delete();
            return redirect()->route('admin.presence-schedules.index')
                         ->with('success', 'Jadwal presensi berhasil dihapus.');
        } catch (\Exception $e) {
            // Jika jadwal terhubung ke log, mungkin gagal
            return redirect()->route('admin.presence-schedules.index')
                         ->with('error', 'Gagal menghapus jadwal. Pastikan tidak ada data log yang terhubung.');
        }
    }
}