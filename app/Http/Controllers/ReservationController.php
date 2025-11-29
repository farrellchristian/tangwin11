<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['service', 'employee', 'store']); 

        // Urutkan: Terbaru di atas
        $reservations = $query->orderBy('booking_date', 'desc')
                              ->orderBy('booking_time', 'asc')
                              ->paginate(10); 

        return view('admin.reservation.index', compact('reservations'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,completed,canceled'
        ]);

        $reservation->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status reservasi diperbarui.');
    }
}