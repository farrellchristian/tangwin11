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
            'cart' => 'required|array|min:1',
            'cart.*.id_item' => 'required|integer',
            'cart.*.item_type' => 'required|in:service,product,food',
            'cart.*.id_employee' => 'nullable|exists:employees,id_employee',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price_at_sale' => 'required|numeric|min:0',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price_at_sale' => 'required|numeric|min:0',
            'status' => 'required|string|in:paid,pending,failed',
            'order_id' => 'nullable|string|max:255'
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
                'transaction_date' => now(),
                'notes' => $request->input('notes'), 
                'tips' => $validatedData['tips'],
                'transaction_date' => now(), 
                'notes' => $request->input('notes'),
                'status' => $validatedData['status'],
                'order_id' => $validatedData['order_id']
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
            return response()->json([
                'success' => true, 
                'message' => 'Transaksi berhasil disimpan.',
                'transaction_id' => $transaction->id_transaction
            ]);

        } catch (\Exception $e) {
            // Jika terjadi error, batalkan semua perubahan di database
            DB::rollBack();

            // Kirim response error (JSON)
            // Log errornya untuk debugging
            \Log::error('Error saving transaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan transaksi.'], 500);
        }
    }



    public function printStruk($id)
    {
        $transaction = \App\Models\Transaction::with([
            'store',
            'employee',         // Capster Utama (yang dipilih di awal)
            'user',             // Kasir
            'paymentMethod',
            'details.service',
            'details.product',
            'details.food',
            'details.employee'  // Capster per item (PENTING!)
        ])->findOrFail($id);

        // --- LOGIKA GROUPING ITEM ---
        $groupedDetails = collect();

        foreach ($transaction->details as $item) {
            $key = $item->item_type . '-' . 
                   ($item->id_service ?? $item->id_product ?? $item->id_food) . '-' . 
                   $item->price_at_sale;

            if ($groupedDetails->has($key)) {
                // Jika sudah ada, tambahkan quantity dan subtotal
                $existingItem = $groupedDetails->get($key);
                $existingItem->quantity += $item->quantity;
                $existingItem->subtotal += $item->subtotal;
            } else {
                // Jika belum, buat baru
                $newItem = clone $item;
                $groupedDetails->put($key, $newItem);
            }
        }

        // --- LOGIKA KUMPULKAN SEMUA CAPSTER ---
        // 1. Masukkan Capster Utama dulu
        $capsters = collect([$transaction->employee->employee_name]);

        // 2. Loop semua detail, ambil nama karyawan di setiap item (jika ada)
        foreach ($transaction->details as $item) {
            if ($item->employee) {
                $capsters->push($item->employee->employee_name);
            }
        }

        // 3. Hapus duplikat dan gabungkan jadi string
        $capsterString = $capsters->unique()->implode(', ');

        // Kirim data yang sudah diolah ke View
        return view('pos.struk', [
            'transaction' => $transaction,
            'groupedDetails' => $groupedDetails,
            'capsterString' => $capsterString
        ]);
    }

    public function history(\Illuminate\Http\Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $date = $request->input('date', date('Y-m-d'));
        $query = \App\Models\Transaction::with(['employee', 'paymentMethod'])
                    ->where('id_store', $user->id_store)
                    ->whereDate('transaction_date', $date);
        
        // 2. Hitung Summary Hari Ini
        $allToday = (clone $query)->get();

        $summary = [
            'total_trx'     => $allToday->count(),
            'total_cash'    => $allToday->filter(fn($t) => $t->paymentMethod && $t->paymentMethod->method_name === 'Cash')->sum('total_amount'),
            'total_digital' => $allToday->filter(fn($t) => $t->paymentMethod && $t->paymentMethod->method_name !== 'Cash')->sum('total_amount'),
        ];

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id_transaction', 'like', "%$search%")
                  ->orWhereHas('employee', function($subQ) use ($search) {
                      $subQ->where('employee_name', 'like', "%$search%");
                  });
            });
        }

        // 3. Ambil Data Tabel (Paginate biar rapi)
        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(10);

        return view('pos.history', compact('transactions', 'summary', 'date'));
    }
}