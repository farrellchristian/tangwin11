<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Employee;
use App\Models\Service; 
use App\Models\Product;
use App\Models\Food;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\JsonResponse;
use Midtrans\Config;
use Midtrans\CoreApi;

class PosController extends Controller
{
    /**
    * Menampilkan halaman awal POS (Pilih Toko atau Pilih Karyawan).
    */
    public function index(): View
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Jika Admin: Ambil semua toko untuk modal pilihan
            $stores = Store::all();

            // Tampilkan view index POS, kirim data toko
            // View ini nanti akan punya logika untuk menampilkan modal
            return view('pos.index', [
                'stores' => $stores,
                'isAdmin' => true // Flag untuk view
            ]);

        } elseif ($user->role === 'kasir') {
            // Jika Kasir: Langsung ambil ID tokonya
            $storeId = $user->id_store;

            // Cek jika kasir punya id_store (seharusnya selalu punya)
            if (!$storeId) {
                 // Tindakan jika kasir tidak punya id_store (error)
                 abort(403, 'Akun kasir tidak terhubung ke toko manapun.');
            }

            // Ambil karyawan HANYA dari toko kasir tersebut
            $employees = Employee::where('id_store', $storeId)
                                 ->where('is_active', true) // Hanya yang aktif
                                 ->orderBy('employee_name')
                                 ->get();

            // Tampilkan view untuk memilih karyawan utama
            return view('pos.select-employee', [
                'employees' => $employees,
                'storeId' => $storeId, // Kirim ID toko untuk langkah selanjutnya
                'isAdmin' => false // Flag untuk view
            ]);
        } else {
            // Jika role tidak dikenal
            abort(403, 'Akses ditolak.');
        }
    }

    /**
    * Menampilkan halaman pilih karyawan utama (setelah admin memilih toko).
    */
    public function showSelectEmployee(Store $store): View // Gunakan Route Model Binding
    {
        // Pastikan hanya Admin yang bisa akses halaman ini secara langsung
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        // Ambil karyawan HANYA dari toko yang dipilih Admin
        $employees = Employee::where('id_store', $store->id_store)
                            ->where('is_active', true)
                            ->orderBy('employee_name')
                            ->get();

        // Tampilkan view yang SAMA seperti kasir, tapi kirim data Admin
        return view('pos.select-employee', [
            'employees' => $employees,
            'storeId' => $store->id_store, // Kirim ID toko untuk langkah selanjutnya
            'isAdmin' => true // Flag untuk view (jika perlu perbedaan tampilan nanti)
        ]);
    }

    /**
    * Menampilkan halaman transaksi utama POS.
    */
    // Gunakan Route Model Binding untuk Store dan Employee
    public function showTransactionPage(Store $store, Employee $employee): View
    {
        // Validasi tambahan (misal: pastikan employee ada di store yg benar)
        if ($employee->id_store !== $store->id_store) {
            abort(404, 'Karyawan tidak ditemukan di toko ini.');
        }
        // Jika user adalah Kasir, pastikan dia hanya bisa akses tokonya sendiri
        if (Auth::user()->role === 'kasir' && Auth::user()->id_store !== $store->id_store) {
            abort(403, 'Anda tidak bisa mengakses toko ini.');
        }

        // Ambil data yang dibutuhkan untuk halaman transaksi dari toko yang dipilih
        $availableServices = Service::where('id_store', $store->id_store)->get();
        $availableProducts = Product::where('id_store', $store->id_store)->where('stock_available', '>', 0)->get(); // Hanya yg ada stok
        $availableFoods = Food::where('id_store', $store->id_store)->where('stock_available', '>', 0)->get(); // Hanya yg ada stok
        $availableEmployees = Employee::where('id_store', $store->id_store)
                                    ->where('is_active', true)
                                    ->where('id_employee', '!=', $employee->id_employee) // Kecuali capster utama
                                    ->orderBy('employee_name')
                                    ->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        // Tampilkan view halaman transaksi (akan kita buat)
        return view('pos.transaction', [
            'store' => $store,
            'primaryEmployee' => $employee,
            'availableServices' => $availableServices,
            'availableProducts' => $availableProducts,
            'availableFoods' => $availableFoods,
            'availableEmployees' => $availableEmployees, // Untuk capster tambahan
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
    * Menyimpan data transaksi baru ke database.
    */
    public function storeTransaction(Request $request): JsonResponse
    {
        // 1. Validasi data dasar yang masuk (minimal ada)
        $validatedData = $request->validate([
            'id_store' => 'required|exists:stores,id_store',
            'id_employee_primary' => 'required|exists:employees,id_employee',
            'id_payment_method' => 'required|exists:payment_methods,id_payment_method',
            'total_amount' => 'required|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'change_amount' => 'nullable|numeric|min:0',
            'tips' => 'nullable|numeric|min:0',
            'cart' => 'required|array|min:1', // Pastikan cart tidak kosong
            'cart.*.id_item' => 'required|integer',
            'cart.*.item_type' => 'required|in:service,product,food',
            'cart.*.id_employee' => 'nullable|exists:employees,id_employee', // Validasi employee per item
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price_at_sale' => 'required|numeric|min:0',
        ]);

        // Gunakan Database Transaction untuk memastikan konsistensi data
        try {
            DB::beginTransaction();

            // 2. Simpan data transaksi utama
            $transaction = Transaction::create([
                'id_store' => $validatedData['id_store'],
                'id_employee_primary' => $validatedData['id_employee_primary'],
                'id_user' => Auth::id(), // Ambil ID user yang login
                'id_payment_method' => $validatedData['id_payment_method'],
                'total_amount' => $validatedData['total_amount'],
                'amount_paid' => $validatedData['amount_paid'],
                'change_amount' => $validatedData['change_amount'],
                'tips' => $validatedData['tips'],
                'transaction_date' => now(), // Tanggal saat ini
                'notes' => $request->input('notes'), // Ambil notes jika ada
            ]);

            // 3. Simpan detail transaksi dan kurangi stok (jika perlu)
            foreach ($validatedData['cart'] as $item) {
                // Tentukan ID item berdasarkan tipe
                $itemIdService = $item['item_type'] === 'service' ? $item['id_item'] : null;
                $itemIdProduct = $item['item_type'] === 'product' ? $item['id_item'] : null;
                $itemIdFood = $item['item_type'] === 'food' ? $item['id_item'] : null;

                // Hitung subtotal
                $subtotal = $item['quantity'] * $item['price_at_sale'];

                TransactionDetail::create([
                    'id_transaction' => $transaction->id_transaction,
                    'id_employee' => $item['id_employee'], // Employee per item (penting untuk service)
                    'item_type' => $item['item_type'],
                    'id_service' => $itemIdService,
                    'id_product' => $itemIdProduct,
                    'id_food' => $itemIdFood,
                    'quantity' => $item['quantity'],
                    'price_at_sale' => $item['price_at_sale'],
                    'subtotal' => $subtotal,
                ]);

                // 4. Kurangi stok jika produk atau makanan
                if ($item['item_type'] === 'product') {
                    $product = Product::find($itemIdProduct);
                    if ($product) {
                        // Gunakan decrement untuk mengurangi race condition
                        $product->decrement('stock_available', $item['quantity']);
                    }
                } elseif ($item['item_type'] === 'food') {
                    $food = Food::find($itemIdFood);
                    if ($food) {
                        $food->decrement('stock_available', $item['quantity']);
                    }
                }
            }

            // Jika semua berhasil, commit transaksi database
            DB::commit();

            // Kirim response sukses (JSON) kembali ke frontend
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil disimpan.']);

        } catch (\Exception $e) {
            // Jika terjadi error, batalkan semua perubahan di database
            DB::rollBack();

            // Kirim response error (JSON)
            // Log errornya untuk debugging
            \Log::error('Error saving transaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan transaksi.'], 500);
        }
    }

    /**
     * Membuat tagihan QRIS baru di Midtrans.
     */
    public function createQrisPayment(Request $request)
    {
        // 1. Validasi data yang masuk
        // Kita tambahkan validasi untuk 'name' di dalam 'cart'
        $validated = $request->validate([
            'total_amount' => 'required|numeric|min:1',
            'cart' => 'required|array',
            'cart.*.name' => 'required|string', // Pastikan nama item ada
            'cart.*.id_item' => 'required',
            'cart.*.price_at_sale' => 'required|numeric',
            'cart.*.quantity' => 'required|integer',
            'id_store' => 'required|exists:stores,id_store',
            'tips' => 'nullable|numeric|min:0',
        ]);

        // 2. Set Konfigurasi Midtrans dari file config/midtrans.php
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // 3. Buat ID Transaksi Unik untuk Midtrans
        // Kita akan gunakan ini nanti untuk mengecek status
        $orderId = 'TANGWIN-' . $request->id_store . '-' . time();

        // 4. Siapkan parameter untuk Midtrans
        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (float) $validated['total_amount'], // total_amount sudah termasuk tips
            ],
            'item_details' => [],
        ];

        // 5. Isi item_details dari cart
        foreach ($validated['cart'] as $item) {
            $params['item_details'][] = [
                'id' => $item['id_item'],
                'price' => (float) $item['price_at_sale'],
                'quantity' => (int) $item['quantity'],
                'name' => substr($item['name'], 0, 50), // Midtrans punya batas 50 char
            ];
        }

        // 6. (PENTING) Saat ini kita belum menyimpan ke DB
        // Kita akan lakukan ini NANTI setelah pembayaran lunas.
        // Untuk sekarang, kita hanya buat QR code.

        try {
            // 7. Panggil CoreApi::charge untuk mendapatkan data QRIS
            $charge = CoreApi::charge($params);

            if (!$charge) {
                return response()->json(['message' => 'Gagal membuat charge Midtrans'], 500);
            }

            // 8. Berhasil! Kembalikan data yang dibutuhkan frontend
            return response()->json([
                'success' => true,
                'order_id' => $orderId, // ID untuk pengecekan status nanti
                'qr_code_url' => $charge->actions[0]->url,
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}