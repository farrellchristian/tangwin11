<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
// Kita tidak butuh Auth di sini karena rute sudah dilindungi

class PaymentMethodController extends Controller
{
    // Method __construct() yang error SUDAH DIHAPUS

    /**
     * Menampilkan daftar semua metode pembayaran.
     */
    public function index(): View
    {
        $paymentMethods = PaymentMethod::latest()->paginate(10);
        
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Menampilkan form untuk membuat metode pembayaran baru.
     */
    public function create(): View
    {
        return view('admin.payment-methods.create');
    }

    /**
     * Menyimpan metode pembayaran baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'method_name' => 'required|string|max:50|unique:payment_methods,method_name',
            'is_active' => 'required|boolean',
        ]);

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')
                         ->with('success', 'Metode pembayaran baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit metode pembayaran.
     */
    public function edit(PaymentMethod $paymentMethod): View
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Memperbarui metode pembayaran di database.
     */
    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
         $validated = $request->validate([
            'method_name' => 'required|string|max:50|unique:payment_methods,method_name,' . $paymentMethod->id_payment_method . ',id_payment_method',
            'is_active' => 'required|boolean',
        ]);

        $paymentMethod->update($validated);

         return redirect()->route('admin.payment-methods.index')
                         ->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    /**
     * Menghapus metode pembayaran.
     */
    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        try {
            $paymentMethod->delete();

            return redirect()->route('admin.payment-methods.index')
                         ->with('success', 'Metode pembayaran berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangani jika error foreign key (metode sudah dipakai di transaksi)
            return redirect()->route('admin.payment-methods.index')
                         ->with('error', 'Gagal menghapus! Metode pembayaran ini mungkin sudah digunakan dalam transaksi.');
        }
    }
}