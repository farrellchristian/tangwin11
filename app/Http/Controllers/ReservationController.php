<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data Toko untuk dropdown filter
        $stores = Store::all();

        // 2. Tentukan Rentang Tanggal Default (Bulan Ini) jika tidak ada input
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // 3. Query Dasar
        $query = Reservation::with(['service', 'employee', 'store'])
            ->whereDate('booking_date', '>=', $startDate)
            ->whereDate('booking_date', '<=', $endDate);

        // 4. Terapkan Filter Tambahan
        if ($request->filled('store_id')) {
            $query->where('id_store', $request->store_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('id_reservation', 'like', "%{$search}%");
            });
        }

        // 5. Hitung Statistik (Berdasarkan Filter di atas, KECUALI filter status agar stats tetap relevan per kategori)
        // Kita buat query terpisah untuk statistik agar tidak terpengaruh filter 'status' itu sendiri (opsional, tapi biasanya user ingin lihat total per status dalam rentang tanggal)
        $statsQuery = Reservation::whereDate('booking_date', '>=', $startDate)
            ->whereDate('booking_date', '<=', $endDate);

        if ($request->filled('store_id')) {
            $statsQuery->where('id_store', $request->store_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $statsQuery->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Clone query untuk menghitung per status dengan efisien
        $statsTotal = (clone $statsQuery)->count();
        $statsPending = (clone $statsQuery)->where('status', 'pending')->count();
        $statsApproved = (clone $statsQuery)->where('status', 'approved')->count();
        $statsCompleted = (clone $statsQuery)->where('status', 'completed')->count();
        $statsExpired = (clone $statsQuery)->where('status', 'expired')->count();
        $statsRefunded = (clone $statsQuery)->where('status', 'refunded')->count();


        // 6. Urutkan dan Paginate
        $reservations = $query->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'asc')
            ->paginate(15) // Kita naikkan jadi 15 per halaman
            ->withQueryString(); // Agar parameter filter tetap ada saat ganti halaman

        return view('admin.reservation.index', compact(
            'reservations',
            'stores',
            'startDate',
            'endDate',
            'statsTotal',
            'statsPending',
            'statsApproved',
            'statsCompleted',
            'statsExpired',
            'statsRefunded'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,completed,canceled,expired,refunded'
        ]);

        $reservation->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status reservasi diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->delete(); // Soft delete

            return redirect()->back()->with('success', 'Reservasi berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Error deleting reservation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus reservasi.');
        }
    }
}
