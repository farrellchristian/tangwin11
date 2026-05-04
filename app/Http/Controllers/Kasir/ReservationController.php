<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Menampilkan daftar reservasi hari ini & besok khusus toko kasir ini.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // Query dasar: hanya toko kasir ini, hanya hari ini & besok
        $query = Reservation::with(['service', 'employee', 'store'])
            ->where('id_store', $user->id_store)
            ->whereIn('booking_date', [$today->toDateString(), $tomorrow->toDateString()]);

        // Filter opsional: status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'expired');
        }

        // Filter opsional: search nama/HP
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Statistik untuk toko ini, hari ini & besok saja
        $statsQuery = Reservation::where('id_store', $user->id_store)
            ->whereIn('booking_date', [$today->toDateString(), $tomorrow->toDateString()]);

        $statsTotal     = (clone $statsQuery)->count();
        $statsPending   = (clone $statsQuery)->where('status', 'pending')->count();
        $statsApproved  = (clone $statsQuery)->where('status', 'approved')->count();
        $statsCompleted = (clone $statsQuery)->where('status', 'completed')->count();
        $statsRefunded  = (clone $statsQuery)->where('status', 'refunded')->count();

        $reservations = $query
            ->orderBy('booking_date', 'asc')
            ->orderBy('booking_time', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('kasir.reservation.index', compact(
            'reservations',
            'statsTotal',
            'statsPending',
            'statsApproved',
            'statsCompleted',
            'statsRefunded'
        ));
    }

    /**
     * Update status reservasi — kasir tidak boleh mengubah ke/dari 'refunded'.
     */
    public function updateStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        // Pastikan reservasi milik toko kasir ini
        if ($reservation->id_store !== Auth::user()->id_store) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke reservasi ini.');
        }

        // Kasir tidak boleh mengubah status refunded
        if ($reservation->status === 'refunded') {
            return redirect()->back()->with('error', 'Status Refunded tidak dapat diubah oleh kasir.');
        }

        $request->validate([
            'status' => 'required|in:pending,approved,completed,canceled,expired'
        ]);

        $oldStatus = $reservation->status;
        $newStatus = $request->status;

        $reservation->update(['status' => $newStatus]);

        // --- LOGIKA INTEGRASI LAPORAN ---
        // Trigger: masuk laporan saat APPROVED, keluar laporan saat status berubah DARI approved
        if ($newStatus === 'approved' && $oldStatus !== 'approved') {
            // → approved: buat transaksi jika belum ada (cek via id_reservation DAN order_id)
            $orderId    = 'RES-' . $reservation->id_reservation;
            $existingTx = Transaction::where('id_reservation', $reservation->id_reservation)
                ->orWhere('order_id', $orderId)
                ->first();

            if (!$existingTx) {
                $paymentMethod   = PaymentMethod::where('method_name', 'Reservasi Online')->first();
                $paymentMethodId = $paymentMethod ? $paymentMethod->id_payment_method : 1;

                $transactionDate = $reservation->booking_date;
                if ($reservation->booking_time) {
                    $transactionDate .= ' ' . $reservation->booking_time;
                }

                $newTx = Transaction::create([
                    'id_store'            => $reservation->id_store,
                    'id_employee_primary' => $reservation->id_employee,
                    'id_user'             => Auth::id() ?? 1,
                    'id_payment_method'   => $paymentMethodId,
                    'total_amount'        => $reservation->service->price ?? 0,
                    'amount_paid'         => $reservation->service->price ?? 0,
                    'change_amount'       => 0,
                    'tips'                => 0,
                    'transaction_date'    => $transactionDate,
                    'notes'               => 'Pembayaran via Reservasi Online (Booking ID: ' . $reservation->id_reservation . ')',
                    'status'              => 'completed',
                    'order_id'            => 'RES-' . $reservation->id_reservation,
                    'id_reservation'      => $reservation->id_reservation,
                ]);

                TransactionDetail::create([
                    'id_transaction' => $newTx->id_transaction,
                    'item_type'      => 'service',
                    'id_service'     => $reservation->id_service,
                    'quantity'       => 1,
                    'price_at_sale'  => $reservation->service->price ?? 0,
                    'subtotal'       => $reservation->service->price ?? 0,
                    'id_employee'    => $reservation->id_employee,
                ]);
            } // Sudah ada → tidak perlu buat lagi

        } elseif ($oldStatus === 'approved' && $newStatus !== 'approved') {
            // approved → status lain: hapus transaksinya dari laporan
            $existingTx = Transaction::where('id_reservation', $reservation->id_reservation)->first();
            if ($existingTx) {
                TransactionDetail::where('id_transaction', $existingTx->id_transaction)->delete();
                $existingTx->delete();
            }
        }
        // Status 'completed' → tidak ada efek ke laporan (sudah tercatat sejak approved)

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui.');
    }
}
