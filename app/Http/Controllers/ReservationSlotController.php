<?php

namespace App\Http\Controllers;

use App\Models\ReservationSlot;
use App\Models\Employee; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationSlotController extends Controller
{
    // Menampilkan halaman Kelola Jadwal
    public function index()
    {
        // 1. Ambil data Slot (beserta relasi karyawan & toko)
        $slots = ReservationSlot::with(['employees', 'store'])
                    ->orderBy('day_of_week')
                    ->orderBy('slot_time')
                    ->get();

        // 2. Ambil data Karyawan (Semua)
        $employees = \App\Models\Employee::where('is_active', 1)->get();

        // 3. Ambil data Toko (Semua)
        $stores = \App\Models\Store::where('is_active', 1)->get();

        return view('admin.reservation.slots.index', compact('slots', 'employees', 'stores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Cek tipe input (apakah 'single' atau 'bulk')
        // Kita ambil value input_type, kalau tidak ada kita anggap 'bulk' (bawaan lama)
        $inputType = $request->input('input_type', 'bulk'); 

        if ($inputType === 'single') {
            return $this->storeSingle($request);
        } else {
            return $this->storeBulk($request);
        }
    }

    /**
     * Logika untuk menyimpan Satu Jadwal (Manual)
     * Method baru untuk menangani input satuan
     */
    protected function storeSingle(Request $request)
    {
        $request->validate([
            'id_store'     => 'required|exists:stores,id_store', // <--- VALIDASI BARU
            'day_of_week'  => 'required|string',
            'slot_time'    => 'required',
            'employee_ids' => 'required|array',
            'quota'        => 'nullable|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            // GANTI BAGIAN INI:
            $storeId = $request->id_store; // <--- Ambil dari Input Form

            // Buat Slot
            $slot = ReservationSlot::create([
                'id_store'    => $storeId,
                'day_of_week' => $request->day_of_week,
                'slot_time'   => $request->slot_time,
                'quota'       => $request->quota ?? 1,
                'is_active'   => 1,
            ]);

            // Hubungkan Karyawan
            $slot->employees()->sync($request->employee_ids);
        });

        return redirect()->back()->with('success', 'Jadwal satuan berhasil ditambahkan.');
    }

    /**
     * Logika untuk Generate Jadwal Massal (Logika Lama/Standar)
     * Kita pindahkan logika lama ke method ini agar rapi
     */
    protected function storeBulk(Request $request)
    {
        $request->validate([
            'id_store'     => 'required|exists:stores,id_store',
            'day_of_week'  => 'required|array',
            'employee_ids' => 'required|array',
            'start_time'   => 'required',
            'end_time'     => 'required',
            'interval'     => 'required|integer',
            'quota'        => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $storeId = $request->id_store;
            $days = $request->day_of_week;
            $startTime = Carbon::parse($request->start_time);
            $endTime = Carbon::parse($request->end_time);
            $interval = (int) $request->interval;
            $inputQuota = (int) $request->quota; 

            foreach ($days as $day) {
                $current = $startTime->copy();

                while ($current->format('H:i') < $endTime->format('H:i')) {
                    $timeString = $current->format('H:i:00');

                    $existingSlot = ReservationSlot::where('id_store', $storeId)
                        ->where('day_of_week', $day)
                        ->where('slot_time', $timeString)
                        ->first();

                    if (!$existingSlot) {
                        $slot = ReservationSlot::create([
                            'id_store'    => $storeId,
                            'day_of_week' => $day,
                            'slot_time'   => $timeString,
                            'quota'       => $inputQuota, // <--- PAKAI INPUT USER
                            'is_active'   => 1,
                        ]);
                        $slot->employees()->sync($request->employee_ids);
                    } else {
                        // Jika sudah ada, kita update karyawannya saja atau mau update kuota juga?
                        // Di sini kita update kuota juga biar konsisten
                        $existingSlot->update(['quota' => $inputQuota]); 
                        $existingSlot->employees()->syncWithoutDetaching($request->employee_ids);
                    }

                    $current->addMinutes($interval);
                }
            }
        });

        return redirect()->back()->with('success', 'Generate jadwal massal berhasil!');
    }

    // Update Karyawan pada Slot tertentu
    // Update Jadwal (Jam & Karyawan)
    public function update(Request $request, $id)
    {
        $request->validate([
            'slot_time'      => 'required', 
            'employee_ids'   => 'required|array',
            'employee_ids.*' => 'exists:employees,id_employee',
            'quota'          => 'required|integer|min:1', // <--- TAMBAHAN VALIDASI
        ]);

        $slot = ReservationSlot::findOrFail($id);
        
        // Logika Cek Duplikasi (Sama seperti sebelumnya)
        $newTime = \Carbon\Carbon::parse($request->slot_time)->format('H:i:00');
        $isDuplicate = ReservationSlot::where('id_store', $slot->id_store)
            ->where('day_of_week', $slot->day_of_week)
            ->where('slot_time', $newTime)
            ->where('id_slot', '!=', $id)
            ->exists();

        if ($isDuplicate) {
            return redirect()->back()
                ->withErrors(['slot_time' => "Gagal! Jam {$request->slot_time} sudah ada."])
                ->withInput();
        }

        DB::transaction(function () use ($slot, $request) {
            $slot->employees()->sync($request->employee_ids);

            $slot->update([
                'slot_time' => $request->slot_time,
                'quota'     => $request->quota // <--- PAKAI INPUT FORM, BUKAN COUNT()
            ]);
        });

        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    // Menghapus satu slot
    public function destroy($id)
    {
        // PERBAIKAN: Hapus ->where('id_store', ...) agar Admin bisa hapus punya siapa saja
        $slot = ReservationSlot::findOrFail($id);
        
        $slot->delete();

        return redirect()->back()->with('success', 'Slot jadwal berhasil dihapus.');
    }
    
    // Menghapus SEMUA slot (Reset)
    public function destroyAll()
    {
        ReservationSlot::where('id_store', Auth::user()->id_store)->delete();
        
        return redirect()->back()->with('success', 'Semua jadwal berhasil dikosongkan.');
    }
}