<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Food;
use App\Models\Store;
use App\Models\Employee;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        // Pastikan hanya Admin yang bisa
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $transaction->load(['employee', 'paymentMethod', 'store', 'details']);

        $stores = Store::all();
        $employees = Employee::where('id_store', $transaction->id_store)
            ->where('is_active', true)
            ->orderBy('employee_name')
            ->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('admin.transactions.edit', compact('transaction', 'stores', 'employees', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Pastikan hanya Admin yang bisa
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $validatedData = $request->validate([
            'id_employee_primary' => 'required|exists:employees,id_employee',
            'id_payment_method' => 'required|exists:payment_methods,id_payment_method',
            'tips' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:Paid,Unpaid,Pending,Cancelled',
        ]);

        try {
            DB::beginTransaction();

            // Hitung ulang total_amount jika tips berubah
            // Total transaksi biasanya = (Sum of details) + Tips
            $detailsSubtotal = $transaction->details()->sum('subtotal');
            $newTotalAmount = $detailsSubtotal + $validatedData['tips'];

            $transaction->update(array_merge($validatedData, [
                'total_amount' => $newTotalAmount,
            ]));

            DB::commit();

            return redirect()->route('admin.reports.index')->with('success', 'Transaksi #' . $transaction->id_transaction . ' berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating transaction: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui transaksi. Error: ' . $e->getMessage())->withInput();
        }
    }

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