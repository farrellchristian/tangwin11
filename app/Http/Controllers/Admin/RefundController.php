<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\Reservation;
use App\Mail\RefundProcessedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RefundController extends Controller
{
    /**
     * Tampilkan daftar permintaan refund Admin.
     */
    public function index()
    {
        // Get all refunds with related reservation (including soft-deleted ones)
        $refunds = Refund::with(['reservation' => function($query) {
            $query->withTrashed();
        }])->latest()->paginate(10);

        return view('admin.refunds.index', compact('refunds'));
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(Refund $refund)
    {
        try {
            $refund->delete(); // Soft delete the refund
            return redirect()->back()->with('success', 'Data permintaan refund berhasil dihapus (soft delete).');
        } catch (\Exception $e) {
            \Log::error('Error soft deleting refund: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data refund.');
        }
    }

    /**
     * Proses/Selesaikan pengajuan refund.
     */
    public function process(Request $request, Refund $refund)
    {
        // Validasi input: wajib upload bukti transfer untuk proses
        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            // Simpan gambar ke storage/app/public/refunds
            $proofPath = $request->file('proof_image')->store('refunds', 'public');
        }

        // Update status refund beserta filenya
        $refund->update([
            'status' => 'processed',
            'proof_path' => $proofPath
        ]);

        // Update status reservasi terkait menjadi refunded
        $refund->reservation()->update([
            'status' => 'refunded'
        ]);

        // Kirim email notifikasi ke pelanggan dengan melampirkan path
        if ($refund->reservation && $refund->reservation->customer_email) {
            Mail::to($refund->reservation->customer_email)->send(new RefundProcessedMail($refund, $proofPath));
        }

        return redirect()->route('admin.refunds.index')->with('success', 'Refund berhasil diproses dan email notifikasi telah dikirim beserta bukti transfer.');
    }

    /**
     * Tolak pengajuan refund.
     */
    public function reject(Request $request, Refund $refund)
    {
        // Update status refund
        $refund->update([
            'status' => 'rejected'
        ]);

        // Kembalikan status reservasi ke approved karena refund ditolak
        $refund->reservation()->update([
            'status' => 'approved'
        ]);

        return redirect()->route('admin.refunds.index')->with('success', 'Refund berhasil ditolak. Status reservasi dikembalikan ke approved.');
    }

}
