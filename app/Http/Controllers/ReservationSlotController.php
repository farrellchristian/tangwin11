<?php

namespace App\Http\Controllers;

use App\Models\ReservationSlot;
use App\Models\Employee; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationSlotController extends Controller
{
    // Menampilkan halaman Kelola Jadwal
    public function index()
    {
        $user = Auth::user();
        
        // Ambil slot jadwal (Tetap difilter per toko admin biar jadwalnya gak nyampur)
        $slots = ReservationSlot::with('employees')
                    ->where('id_store', $user->id_store)
                    ->orderBy('day_of_week', 'asc')
                    ->orderBy('slot_time', 'asc')
                    ->get();

        // --- PERUBAHAN DI SINI ---
        // Ambil SEMUA karyawan yang aktif (TIDAK difilter id_store lagi)
        // Pokoknya yang ada di tabel employees diambil semua.
        $employees = Employee::where('is_active', 1)->get();

        return view('admin.reservation.slots.index', compact('slots', 'employees'));
    }

    // Menyimpan jadwal baru & Assign Karyawan
    public function store(Request $request)
    {
        $request->validate([
            'day_of_week'   => 'required', 
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i|after:start_time',
            'interval'      => 'required|integer|min:15',
            'employee_ids'  => 'required|array', 
            'employee_ids.*'=> 'exists:employees,id_employee',
        ]);

        $user = Auth::user();
        $days = (array) $request->day_of_week; 
        
        $countCreated = 0;

        foreach ($days as $day) {
            $current = Carbon::createFromFormat('H:i', $request->start_time);
            $end     = Carbon::createFromFormat('H:i', $request->end_time);

            while ($current->lt($end)) {
                $timeString = $current->format('H:i:00');

                // Cek apakah slot sudah ada?
                $slot = ReservationSlot::where('id_store', $user->id_store)
                            ->where('day_of_week', $day)
                            ->where('slot_time', $timeString)
                            ->first();

                // Jika belum ada, buat baru
                if (!$slot) {
                    $slot = ReservationSlot::create([
                        'id_store'    => $user->id_store,
                        'day_of_week' => $day,
                        'slot_time'   => $timeString,
                        'quota'       => 1, 
                        'is_active'   => 1
                    ]);
                    $countCreated++;
                }

                // Masukkan karyawan ke slot ini
                $slot->employees()->syncWithoutDetaching($request->employee_ids);
                
                // Update kuota
                $slot->update(['quota' => $slot->employees()->count()]);

                $current->addMinutes((int) $request->interval);
            }
        }

        return redirect()->back()->with('success', "Jadwal berhasil diperbarui! ($countCreated slot baru dibuat).");
    }

    // Update Karyawan pada Slot tertentu
    // Update Jadwal (Jam & Karyawan)
    public function update(Request $request, $id)
    {
        $request->validate([
            'slot_time'      => 'required|date_format:H:i', // Validasi format jam
            'employee_ids'   => 'required|array', 
            'employee_ids.*' => 'exists:employees,id_employee',
        ]);

        $user = Auth::user();
        
        $slot = ReservationSlot::where('id_slot', $id)
                    ->where('id_store', $user->id_store)
                    ->firstOrFail();

        // Cek Duplikasi: Apakah jam baru yang diminta SUDAH ADA di hari yang sama?
        // (Kecuali punya slot ini sendiri)
        $exists = ReservationSlot::where('id_store', $user->id_store)
                    ->where('day_of_week', $slot->day_of_week)
                    ->where('slot_time', $request->slot_time . ':00') // Tambah :00 untuk format DB
                    ->where('id_slot', '!=', $id) // Abaikan ID sendiri
                    ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['slot_time' => "Jam {$request->slot_time} sudah ada untuk hari {$slot->day_of_week}!"]);
        }

        // 1. Update Karyawan
        $slot->employees()->sync($request->employee_ids);

        // 2. Update Data Slot (Jam & Kuota)
        $slot->update([
            'slot_time' => $request->slot_time,
            'quota'     => $slot->employees()->count()
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    // Menghapus satu slot
    public function destroy($id)
    {
        $slot = ReservationSlot::where('id_slot', $id)
                    ->where('id_store', Auth::user()->id_store)
                    ->firstOrFail();
        
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