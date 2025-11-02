<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction; // <-- Import Model Transaction
use App\Models\Product; // <-- Import Model Product
use App\Models\Food; // <-- Import Model Food
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(Transaction $transaction)
    {
        // Pastikan hanya Admin yang bisa
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        try {
            DB::beginTransaction();

            $transaction->load('details.product', 'details.food'); 

            foreach ($transaction->details as $detail) {
                if ($detail->item_type === 'product' && $detail->product) {
                    // Gunakan increment untuk mengembalikan stok
                    $detail->product->increment('stock_available', $detail->quantity);
                } elseif ($detail->item_type === 'food' && $detail->food) {
                    // Gunakan increment untuk mengembalikan stok
                    $detail->food->increment('stock_available', $detail->quantity);
                }
            }
            // 2. Soft delete semua detail transaksi terkait
            $transaction->details()->delete(); // Soft delete semua 'transaction_details'

            // 3. Soft delete transaksi utamanya
            $transaction->delete(); // Soft delete 'transactions'

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi #' . $transaction->id_transaction . ' berhasil dihapus (soft delete).');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error soft deleting transaction: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus transaksi. Error: ' . $e->getMessage());
        }
    }
}