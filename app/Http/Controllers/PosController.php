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
            // Jika Admin: Ambil semua toko yang belum dihapus (SoftDeletes otomatis memfilter)
            $stores = Store::get();

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
        if ($employee->id_store != $store->id_store) {
            abort(404, 'Karyawan tidak ditemukan di toko ini.');
        }
        // Jika user adalah Kasir, pastikan dia hanya bisa akses tokonya sendiri
        if (Auth::user()->role === 'kasir' && Auth::user()->id_store != $store->id_store) {
            abort(403, 'Anda tidak bisa mengakses toko ini.');
        }
        
        // --- VALIDASI PRESENSI ---
        // Jika user bukan Admin, dia tidak bisa melakukan transaksi jika karyawan belum absen
        if (Auth::user()->role !== 'admin' && !$employee->hasCheckedInToday()) {
            return redirect()->route('pos.index')->with('error', 'Karyawan ini ' . $employee->employee_name . ' belum melakukan presensi masuk hari ini!');
        }

        // Ambil data yang dibutuhkan untuk halaman transaksi dari toko yang dipilih
        $availableServices = Service::where('id_store', $store->id_store)->get();
        $availableProducts = Product::where('id_store', $store->id_store)->where('stock_available', '>', 0)->get(); // Hanya yg ada stok
        $availableFoods = Food::where('id_store', $store->id_store)->where('stock_available', '>', 0)->get(); // Hanya yg ada stok
        $availableEmployeesQuery = Employee::where('id_store', $store->id_store)
                                    ->where('id_employee', '!=', $employee->id_employee);

        // Filter: Hanya yang sudah absen hari ini (Kecuali Admin)
        if (Auth::user()->role !== 'admin') {
            $availableEmployeesQuery->whereHas('presenceLogs', function($q) {
                $q->whereDate('check_in_time', \Carbon\Carbon::today());
            });
        }

        $availableEmployees = $availableEmployeesQuery->orderBy('employee_name')->get();
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

        // Tambahan Validasi Presensi (Server-side)
        $employeePrimary = \App\Models\Employee::find($validatedData['id_employee_primary']);
        if (Auth::user()->role !== 'admin' && (!$employeePrimary || !$employeePrimary->hasCheckedInToday())) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi ditolak. Capster utama (' . ($employeePrimary->employee_name ?? 'Unknown') . ') belum melakukan presensi hari ini.'
            ], 403);
        }

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
        // Sertakan id_employee di key agar item capster berbeda tidak di-merge
        $groupedDetails = collect();

        foreach ($transaction->details as $item) {
            $key = $item->item_type . '-' . 
                   ($item->id_service ?? $item->id_product ?? $item->id_food) . '-' . 
                   $item->price_at_sale . '-' .
                   ($item->id_employee ?? 0);

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
        $query = \App\Models\Transaction::with(['employee', 'paymentMethod', 'details'])
                    ->where('id_store', $user->id_store)
                    ->whereDate('transaction_date', $date);
        
        // 2. Hitung Summary Hari Ini
        $allToday = (clone $query)->get();

        // Hitung total pengeluaran seluruh toko hari ini
        $expensesToday = \App\Models\Expense::where('id_store', $user->id_store)
            ->whereDate('expense_date', $date)
            ->get();
        $totalExpensesToday = $expensesToday->sum('amount');
        $countExpensesToday = $expensesToday->count();

        // Hitung total tips hari ini (tips = pengeluaran kas, hak kasir/capster)
        $totalTipsToday = $allToday->sum('tips');

        // Total pengeluaran = bon/beli barang + tips
        $totalPengeluaran = $totalExpensesToday + $totalTipsToday;

        // Hitung total penjualan produk (item_type = 'product') hari ini
        $allProductDetails  = $allToday->flatMap(fn($t) => $t->details)->where('item_type', 'product');
        $totalProductSales  = $allProductDetails->sum('subtotal');
        $countProductSales  = (int) $allProductDetails->sum('quantity');

        $cashTrx     = $allToday->filter(fn($t) => $t->paymentMethod && $t->paymentMethod->method_name === 'Cash');
        $qrisTrx     = $allToday->filter(fn($t) => $t->paymentMethod && $t->paymentMethod->method_name === 'Qris');
        $transferTrx = $allToday->filter(fn($t) => $t->paymentMethod && $t->paymentMethod->method_name === 'Transfer');
        $totalCash     = $cashTrx->sum('total_amount');
        $totalQris     = $qrisTrx->sum('total_amount');
        $totalTransfer = $transferTrx->sum('total_amount');
        $totalIncome   = $totalCash + $totalQris + $totalTransfer;

        $summary = [
            'total_trx'                 => $allToday->count(),
            'total_cash'                => $totalCash,
            'total_cash_count'          => $cashTrx->count(),
            'total_digital'             => $totalQris,
            'total_qris_count'          => $qrisTrx->count(),
            'total_transfer'            => $totalTransfer,
            'total_transfer_count'      => $transferTrx->count(),
            'total_income'              => $totalIncome,
            'total_expenses'            => $totalPengeluaran,   // bon + tips
            'total_expenses_bon'        => $totalExpensesToday, // hanya bon (untuk info)
            'total_tips'                => $totalTipsToday,     // tips saja (untuk info)
            'total_expenses_count'      => $countExpensesToday,
            'total_product_sales'       => $totalProductSales,
            'total_product_sales_count' => $countProductSales,
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

        // 3. Ambil semua transaksi lalu group per capster
        $allTransactions = $query->with(['details.employee'])->orderBy('transaction_date', 'desc')->get();

        // Ambil semua pengeluaran hari ini per store
        $allExpenses = \App\Models\Expense::with('employee')
            ->where('id_store', $user->id_store)
            ->whereDate('expense_date', $date)
            ->orderBy('expense_date', 'desc')
            ->get();

        $capsterMap = []; // [ id_employee => [ 'employee'=>..., 'entries'=>[] ] ]

        foreach ($allTransactions as $trx) {
            // Kelompokkan detail berdasarkan id_employee di tiap item
            $detailsByEmployee = $trx->details->groupBy(function ($detail) use ($trx) {
                // Gunakan id_employee dari detail; fallback ke primary capster
                return $detail->id_employee ?? $trx->id_employee_primary;
            });

            foreach ($detailsByEmployee as $empId => $details) {
                if (!isset($capsterMap[$empId])) {
                    // Ambil objek employee: coba dari detail, fallback ke trx->employee
                    $empObj = $details->first()->employee ?? $trx->employee;
                    $capsterMap[$empId] = [
                        'employee' => $empObj,
                        'entries'  => [], // [ ['trx'=>..., 'amount'=>..., 'tips'=>...] ]
                    ];
                }

                // Hitung subtotal porsi capster ini dalam transaksi ini
                $portionAmount = $details->sum('subtotal');

                // Hitung apakah transaksi ini murni 1 capster atau split
                $totalDetailAmount = $trx->details->sum('subtotal');
                $tipsPortion = $totalDetailAmount > 0
                    ? round(($portionAmount / $totalDetailAmount) * ($trx->tips ?? 0))
                    : 0;

                $capsterMap[$empId]['entries'][] = [
                    'trx'    => $trx,
                    'amount' => $portionAmount,
                    'tips'   => $tipsPortion,
                ];
            }
        }

        // Bangun $groupedByCapster dari capsterMap
        $groupedByCapster = collect($capsterMap)->map(function ($capsterData, $employeeId) use ($allExpenses, $allTransactions) {
            $entries  = collect($capsterData['entries']);
            $expenses = $allExpenses->where('id_employee', $employeeId)->values();

            // Kumpulkan transaksi unik (untuk ditampilkan di tabel)
            // Tambahkan metadata porsi ke setiap transaksi untuk tampilan
            $transactions = $entries->map(function ($entry) {
                // Clone transaksi dan override total_amount & tips dengan porsi capster ini
                $trxClone = clone $entry['trx'];
                $trxClone->display_amount = $entry['amount'];
                $trxClone->display_tips   = $entry['tips'];
                return $trxClone;
            });

            $totalAmount   = $entries->sum('amount');
            $totalTips     = $entries->sum('tips');
            $totalTrx      = $entries->count();

            // Hitung cash_count dan qris_count dari transaksi yang masuk ke capster ini
            $cashCount = $entries->filter(fn($e) =>
                $e['trx']->paymentMethod && $e['trx']->paymentMethod->method_name === 'Cash'
            )->count();
            $qrisCount = $entries->filter(fn($e) =>
                $e['trx']->paymentMethod && $e['trx']->paymentMethod->method_name === 'Qris'
            )->count();

            // Hitung qty produk & makanan milik capster ini
            $allMyDetails = $entries->flatMap(fn($e) => $e['trx']->details->filter(
                fn($d) => ($d->id_employee ?? $e['trx']->id_employee_primary) == $employeeId
            ));

            return [
                'employee'          => $capsterData['employee'],
                'transactions'      => $transactions,
                'expenses'          => $expenses,
                'total_trx'         => $totalTrx,
                'total_amount'      => $totalAmount,
                'total_tips'        => $totalTips,
                'total_expenses'    => $expenses->sum('amount'),
                'cash_count'        => $cashCount,
                'qris_count'        => $qrisCount,
                'total_product_qty' => (int) $allMyDetails->where('item_type', 'product')->sum('quantity'),
                'total_food_qty'    => (int) $allMyDetails->where('item_type', 'food')->sum('quantity'),
            ];
        })->sortByDesc('total_trx');

        return view('pos.history', compact('groupedByCapster', 'summary', 'date'));
    }

    public function transactionDetail($id)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $transaction = \App\Models\Transaction::with([
            'store',
            'employee',
            'user',
            'paymentMethod',
            'details.service',
            'details.product',
            'details.food',
            'details.employee',
        ])->where('id_store', $user->id_store)->findOrFail($id);

        // Grouping detail item — sertakan id_employee di key agar item capster berbeda tidak di-merge
        $groupedDetails = collect();
        foreach ($transaction->details as $item) {
            $key = $item->item_type . '-' .
                   ($item->id_service ?? $item->id_product ?? $item->id_food) . '-' .
                   $item->price_at_sale . '-' .
                   ($item->id_employee ?? 0);
            if ($groupedDetails->has($key)) {
                $existing = $groupedDetails->get($key);
                $existing->quantity  += $item->quantity;
                $existing->subtotal  += $item->subtotal;
            } else {
                $groupedDetails->put($key, clone $item);
            }
        }

        return response()->json([
            'id'             => $transaction->id_transaction,
            'date'           => \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y, H:i'),
            'kasir'          => $transaction->user->name ?? '-',
            'capster'        => $transaction->employee->employee_name ?? '-',
            'payment_method' => $transaction->paymentMethod->method_name ?? '-',
            'tips'           => $transaction->tips ?? 0,
            'total_amount'   => $transaction->total_amount,
            'items'          => $groupedDetails->values()->map(fn($d) => [
                'type'     => $d->item_type,
                'name'     => match($d->item_type) {
                    'service' => $d->service->service_name ?? 'Layanan',
                    'product' => $d->product->product_name ?? 'Produk',
                    'food'    => $d->food->food_name ?? 'Makanan',
                    default   => 'Item',
                },
                'capster'  => $d->employee->employee_name ?? null,
                'qty'      => $d->quantity,
                'price'    => $d->price_at_sale,
                'subtotal' => $d->subtotal,
            ]),
        ]);
    }
}