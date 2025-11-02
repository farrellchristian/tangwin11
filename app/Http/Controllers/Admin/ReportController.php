<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Pastikan ini ada
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\Employee;
use App\Models\Store;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\TransactionDetail;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan utama.
     */
    public function index(Request $request): View
    {
        // === Ambil Tahun Unik yang Punya Data Transaksi/Expense ===
        $transactionYears = Transaction::selectRaw('YEAR(transaction_date) as year')->distinct()->pluck('year');
        $expenseYears = Expense::selectRaw('YEAR(expense_date) as year')->distinct()->pluck('year');
        $availableYears = $transactionYears->merge($expenseYears)->unique()->sortDesc()->values()->toArray();
        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        }

        // === Ambil Data untuk Filter Dropdown ===
        $stores = Store::all();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        // === Ambil Parameter Filter ===
        $filterType = $request->input('filter_type', 'harian');
        $selectedYear = $request->input('year', $availableYears[0] ?? date('Y'));
        $selectedMonth = $request->input('month', date('m'));
        $selectedDay = $request->input('day', date('d'));
        $selectedWeekValue = $request->input('week', 1);
        $selectedStoreId = $request->input('store_id');
        $selectedPaymentMethodId = $request->input('payment_method_id');

        // === Hitung Daftar Minggu & Rentang Tanggal ===
        $weeksOfMonth = $this->getWeeksOfMonth($selectedYear, $selectedMonth); // Hitung minggu
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();
        $reportTitleDate = $startDate->isoFormat('D MMMM YYYY');

        try {
            switch ($filterType) {
                case 'harian':
                    $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, $selectedDay)->startOfDay();
                    $endDate = $startDate->copy()->endOfDay();
                    $reportTitleDate = $startDate->isoFormat('D MMMM YYYY');
                    break;
                case 'mingguan':
                    $selectedWeekData = collect($weeksOfMonth)->firstWhere('value', $selectedWeekValue);
                    if ($selectedWeekData) {
                        $startDate = $selectedWeekData['start']->startOfDay();
                        $endDate = $selectedWeekData['end']->endOfDay();
                        $reportTitleDate = $selectedWeekData['name'];
                    } else {
                         $startDate = Carbon::today()->startOfWeek(Carbon::MONDAY); $endDate = Carbon::today()->endOfWeek(Carbon::SUNDAY); $reportTitleDate = 'Minggu Ini';
                    }
                    break;
                case 'bulanan':
                    $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
                    $endDate = $startDate->copy()->endOfMonth();
                    $reportTitleDate = $startDate->isoFormat('MMMM YYYY');
                    break;
                case 'tahunan':
                    $startDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfYear();
                    $endDate = $startDate->copy()->endOfYear();
                     $reportTitleDate = $startDate->isoFormat('YYYY');
                    break;
            }
        } catch (\Exception $e) {
             \Log::error('Report filter date error: '.$e->getMessage());
             $filterType = 'harian'; $startDate = Carbon::today()->startOfDay(); $endDate = Carbon::today()->endOfDay(); $reportTitleDate = $startDate->isoFormat('D MMMM YYYY');
        }

        // === Hitung Ringkasan Keuangan ===
        $totalIncome = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->when($selectedStoreId, fn($q) => $q->where('id_store', $selectedStoreId))
            ->when($selectedPaymentMethodId, fn($q) => $q->where('id_payment_method', $selectedPaymentMethodId))
            ->sum('total_amount');

        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->when($selectedStoreId, fn($q) => $q->where('id_store', $selectedStoreId))
            ->sum('amount');
        $totalTips = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->when($selectedStoreId, fn($q) => $q->where('id_store', $selectedStoreId))
            ->sum('tips');
        $totalExpenditure = $totalExpenses + $totalTips;
        $netProfitLoss = $totalIncome - $totalExpenditure;

        // === Ambil Detail Transaksi & Pengeluaran per Karyawan ===
        $involvedEmployeeIds = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
                            ->when($selectedStoreId, fn($q) => $q->where('id_store', $selectedStoreId))
                            ->pluck('id_employee_primary')
                            ->merge( Expense::whereBetween('expense_date', [$startDate, $endDate]) ->when($selectedStoreId, fn($q) => $q->where('id_store', $selectedStoreId)) ->pluck('id_employee') )
                            ->unique()->filter()->sort()->values();

        $employeesDetails = Employee::whereIn('id_employee', $involvedEmployeeIds)
                                   ->orderBy('employee_name')
                                   ->get()
                                   ->mapWithKeys(function ($employee) use ($startDate, $endDate, $selectedStoreId, $selectedPaymentMethodId) {
                                        $transactions = Transaction::with('paymentMethod', 'store')
                                            ->where('id_employee_primary', $employee->id_employee)
                                            ->whereBetween('transaction_date', [$startDate, $endDate])
                                            ->when($selectedStoreId, fn($q) => $q->where('id_store', $selectedStoreId))
                                            ->when($selectedPaymentMethodId, fn($q) => $q->where('id_payment_method', $selectedPaymentMethodId))
                                            ->latest('transaction_date')
                                            ->get();
                                        $expenses = Expense::with('store')
                                            ->where('id_employee', $employee->id_employee)
                                            ->whereBetween('expense_date', [$startDate, $endDate])
                                            ->when($selectedStoreId, fn($q) => $q->where('id_store', $selectedStoreId))
                                            ->latest('expense_date')
                                            ->get();
                                        return [$employee->id_employee => ['employee' => $employee, 'transactions' => $transactions, 'expenses' => $expenses]];
                                   });

        // === Kirim Data ke View ===
        return view('admin.reports.index', [
            'stores' => $stores,
            'paymentMethods' => $paymentMethods,
            'availableYears' => $availableYears,
            'filterType' => $filterType,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'selectedDay' => $selectedDay,
            'selectedWeek' => $selectedWeekValue,
            'selectedStoreId' => $selectedStoreId,
            'selectedPaymentMethodId' => $selectedPaymentMethodId,
            'reportTitleDate' => $reportTitleDate,
            'totalIncome' => $totalIncome,
            'totalExpenditure' => $totalExpenditure,
            'netProfitLoss' => $netProfitLoss,
            'employeesDetails' => $employeesDetails,
            'weeksForDropdown' => $weeksOfMonth, // Kirim hasil perhitungan minggu
        ]);
    }

    // ==========================================================
    // METHOD API UNTUK FILTER DINAMIS (COPY DARI EXPENSECONTROLLER)
    // ==========================================================

    /**
     * API: Get available months based on year with transaction data.
     */
    public function getAvailableMonths(Request $request, $year): JsonResponse
    {
        try {
            if (!ctype_digit($year) || $year < 1900 || $year > date('Y') + 5) { return response()->json(['error' => 'Tahun tidak valid.'], 400); }

            // GANTI KE TRANSACTION
            $monthsData = Transaction::selectRaw('DISTINCT MONTH(transaction_date) as month')
                ->whereYear('transaction_date', $year) // GANTI KE transaction_date
                // ->when($request->query('store_id'), fn($q, $storeId)=>$q->where('id_store', $storeId)) // Opsional filter toko
                ->orderBy('month')
                ->pluck('month')
                ->map(function ($monthNum) {
                    $monthNumPadded = str_pad($monthNum, 2, '0', STR_PAD_LEFT);
                    return ['value' => $monthNumPadded, 'name' => Carbon::create()->month($monthNum)->isoFormat('MMMM')];
                });
            return response()->json($monthsData);
        } catch (\Exception $e) {
            \Log::error("Report API error getting months for year {$year}: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data bulan.'], 500);
        }
    }

    /**
     * API: Get available days based on year/month with transaction data.
     */
    public function getAvailableDays(Request $request, $year, $month): JsonResponse
    {
         try {
            if (!ctype_digit($year) || $year < 1900 || $year > date('Y') + 5 || !ctype_digit($month) || $month < 1 || $month > 12) { return response()->json(['error' => 'Tahun atau Bulan tidak valid.'], 400); }

            // GANTI KE TRANSACTION
            $daysData = Transaction::selectRaw('DISTINCT DAY(transaction_date) as day')
                ->whereYear('transaction_date', $year) // GANTI KE transaction_date
                ->whereMonth('transaction_date', $month) // GANTI KE transaction_date
                // ->when($request->query('store_id'), fn($q, $storeId)=>$q->where('id_store', $storeId)) // Opsional filter toko
                ->orderBy('day')
                ->pluck('day')
                ->map(fn($dayNum) => str_pad($dayNum, 2, '0', STR_PAD_LEFT));
            return response()->json($daysData);
        } catch (\Exception $e) {
            \Log::error("Report API error getting days for {$year}-{$month}: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data tanggal.'], 500);
        }
    }

    /**
     * API: Get available weeks based on year/month that have transaction data.
     */
    public function getAvailableWeeks(Request $request, $year, $month): JsonResponse
    {
         try {
             if (!ctype_digit($year) || $year < 1900 || $year > date('Y') + 5 || !ctype_digit($month) || $month < 1 || $month > 12) { return response()->json(['error' => 'Tahun atau Bulan tidak valid.'], 400); }

            $allWeeksData = $this->getWeeksOfMonth($year, $month); // Panggil helper
            $availableWeeks = [];

            foreach ($allWeeksData as $week) {
                // GANTI KE TRANSACTION
                $hasData = Transaction::whereBetween('transaction_date', [ // GANTI KE transaction_date
                                        $week['start']->copy()->startOfDay(),
                                        $week['end']->copy()->endOfDay()
                                     ])
                                     ->whereYear('transaction_date', $year) // GANTI KE transaction_date
                                     ->whereMonth('transaction_date', $month) // GANTI KE transaction_date
                                     // ->when($request->query('store_id'), fn($q, $storeId)=>$q->where('id_store', $storeId)) // Opsional filter toko
                                     ->exists();
                if ($hasData) {
                    $availableWeeks[] = ['value' => $week['value'], 'name' => $week['name']];
                }
            }
            return response()->json($availableWeeks);
        } catch (\Exception $e) {
            \Log::error("Report API error getting weeks for {$year}-{$month}: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data minggu.'], 500);
        }
    }

    // Helper function to get weeks (copy dari ExpenseController jika belum ada)
    private function getWeeksOfMonth($year, $month): array
    {
       // ... (kode helper getWeeksOfMonth SAMA SEPERTI DI EXPENSE CONTROLLER) ...
        $weeks = [];
        $date = Carbon::createFromDate($year, $month, 1)->startOfWeek(Carbon::MONDAY);
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        $weekNumber = 1;
        while ($date->lte($endDate)) {
            $startOfWeek = $date->copy();
            $endOfWeek = $date->copy()->endOfWeek(Carbon::SUNDAY);
            if ($startOfWeek->month != $month) $startOfWeek->day($startOfWeek->daysInMonth)->startOfDay();
            if ($endOfWeek->month != $month) $endOfWeek = $endDate->copy()->endOfDay();
            if ($startOfWeek->month == $month || $endOfWeek->month == $month) {
                $weekName = sprintf('Minggu %d (%s - %s)', $weekNumber, $startOfWeek->isoFormat('DD MMM'), $endOfWeek->isoFormat('DD MMM'));
                $weeks[] = ['value' => $weekNumber, 'name' => $weekName, 'start' => $startOfWeek, 'end' => $endOfWeek];
                $weekNumber++;
            }
            $date->addWeek()->startOfWeek(Carbon::MONDAY);
        }
        return $weeks;
    }

    /**
     * API: Mengambil rincian detail pemasukan untuk modal.
     */
    public function getIncomeDetails(Request $request): JsonResponse
    {
        // === Ambil Parameter Filter (logika SAMA PERSIS dengan method index) ===
        $filterType = $request->input('filter_type', 'harian');
        $selectedYear = $request->input('year', date('Y'));
        $selectedMonth = $request->input('month', date('m'));
        $selectedDay = $request->input('day', date('d'));
        $selectedWeekValue = $request->input('week', 1);
        $selectedStoreId = $request->input('store_id');
        $selectedPaymentMethodId = $request->input('payment_method_id');

        // === Hitung Rentang Tanggal (logika SAMA PERSIS dengan method index) ===
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();
        $reportTitleDate = $startDate->isoFormat('D MMMM YYYY');

        try {
            switch ($filterType) {
                case 'harian':
                    $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, $selectedDay)->startOfDay();
                    $endDate = $startDate->copy()->endOfDay();
                    $reportTitleDate = $startDate->isoFormat('D MMMM YYYY');
                    break;
                case 'mingguan':
                    $weeksOfMonth = $this->getWeeksOfMonth($selectedYear, $selectedMonth); // Asumsi helper getWeeksOfMonth sudah ada
                    $selectedWeekData = collect($weeksOfMonth)->firstWhere('value', $selectedWeekValue);
                    if ($selectedWeekData) {
                        $startDate = $selectedWeekData['start']->startOfDay();
                        $endDate = $selectedWeekData['end']->endOfDay();
                        $reportTitleDate = $selectedWeekData['name'];
                    } else {
                         $startDate = Carbon::today()->startOfWeek(Carbon::MONDAY); $endDate = Carbon::today()->endOfWeek(Carbon::SUNDAY); $reportTitleDate = 'Minggu Ini';
                    }
                    break;
                case 'bulanan':
                    $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
                    $endDate = $startDate->copy()->endOfMonth();
                    $reportTitleDate = $startDate->isoFormat('MMMM YYYY');
                    break;
                case 'tahunan':
                    $startDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfYear();
                    $endDate = $startDate->copy()->endOfYear();
                     $reportTitleDate = $startDate->isoFormat('YYYY');
                    break;
            }
        } catch (\Exception $e) {
             \Log::error('Report API filter date error: '.$e->getMessage());
             $startDate = Carbon::today()->startOfDay(); $endDate = Carbon::today()->endOfDay(); $reportTitleDate = $startDate->isoFormat('D MMMM YYYY');
        }

        // === Query Rincian Pemasukan (INTI LOGIKA BARU) ===
        
        // Buat query dasar untuk transaction_details dalam rentang waktu
        $detailsQuery = TransactionDetail::with('transaction')
            ->whereHas('transaction', function ($query) use ($startDate, $endDate, $selectedStoreId, $selectedPaymentMethodId) {
                $query->whereBetween('transaction_date', [$startDate, $endDate])
                      ->when($selectedStoreId, fn($q) => $q->where('id_store', $selectedStoreId))
                      ->when($selectedPaymentMethodId, fn($q) => $q->where('id_payment_method', $selectedPaymentMethodId));
            });

        // 1. Ambil Rincian Layanan
        $servicesDetails = (clone $detailsQuery) // Clone query agar filter dasar tetap ada
            ->where('item_type', 'service')
            ->with('service') // Eager load relasi service
            ->select('id_service', DB::raw('COUNT(id_transaction) as transaction_count'), DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(subtotal) as total_amount'))
            ->groupBy('id_service')
            ->get()
            ->map(fn($item) => [
                'name' => $item->service->service_name ?? 'Layanan Dihapus',
                'transactions' => $item->transaction_count,
                'quantity' => $item->total_quantity,
                'total' => (float) $item->total_amount,
            ]);

        // 2. Ambil Rincian Produk
        $productsDetails = (clone $detailsQuery)
            ->where('item_type', 'product')
            ->with('product') // Eager load relasi product
            ->select('id_product', DB::raw('COUNT(id_transaction) as transaction_count'), DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(subtotal) as total_amount'))
            ->groupBy('id_product')
            ->get()
            ->map(fn($item) => [
                'name' => $item->product->product_name ?? 'Produk Dihapus',
                'transactions' => $item->transaction_count,
                'quantity' => $item->total_quantity,
                'total' => (float) $item->total_amount,
            ]);

        // 3. Ambil Rincian Makanan
        $foodsDetails = (clone $detailsQuery)
            ->where('item_type', 'food')
            ->with('food') // Eager load relasi food
            ->select('id_food', DB::raw('COUNT(id_transaction) as transaction_count'), DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(subtotal) as total_amount'))
            ->groupBy('id_food')
            ->get()
            ->map(fn($item) => [
                'name' => $item->food->food_name ?? 'Makanan Dihapus',
                'transactions' => $item->transaction_count,
                'quantity' => $item->total_quantity,
                'total' => (float) $item->total_amount,
            ]);
        
        // 4. Hitung Total (untuk verifikasi)
        $totalIncome = $servicesDetails->sum('total') + $productsDetails->sum('total') + $foodsDetails->sum('total');

        // Kembalikan data sebagai JSON
        return response()->json([
            'success' => true,
            'period' => $reportTitleDate,
            'total_income' => $totalIncome,
            'services' => $servicesDetails,
            'products' => $productsDetails,
            'foods' => $foodsDetails,
        ]);
    }

    /**
    * API: Mengambil rincian detail pengeluaran untuk modal.
    */
    public function getExpenditureDetails(Request $request): JsonResponse
    {
        // === Ambil Parameter Filter (logika SAMA PERSIS dengan method index) ===
        $filterType = $request->input('filter_type', 'harian');
        $selectedYear = $request->input('year', date('Y'));
        $selectedMonth = $request->input('month', date('m'));
        $selectedDay = $request->input('day', '01'); // Default ke 01
        $selectedWeekValue = $request->input('week', 1);
        $selectedStoreId = $request->input('store_id');
        // Filter metode pembayaran TIDAK relevan untuk pengeluaran, jadi kita abaikan

        // === Hitung Rentang Tanggal (logika SAMA PERSIS dengan method index) ===
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();
        $reportTitleDate = $startDate->isoFormat('D MMMM YYYY');

        try {
            switch ($filterType) {
                case 'harian':
                    $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, $selectedDay)->startOfDay();
                    $endDate = $startDate->copy()->endOfDay();
                    $reportTitleDate = $startDate->isoFormat('D MMMM YYYY');
                    break;
                case 'mingguan':
                    $weeksOfMonth = $this->getWeeksOfMonth($selectedYear, $selectedMonth);
                    $selectedWeekData = collect($weeksOfMonth)->firstWhere('value', $selectedWeekValue);
                    if ($selectedWeekData) {
                        $startDate = $selectedWeekData['start']->startOfDay();
                        $endDate = $selectedWeekData['end']->endOfDay();
                        $reportTitleDate = $selectedWeekData['name'];
                    } else {
                        $startDate = Carbon::today()->startOfWeek(Carbon::MONDAY); $endDate = Carbon::today()->endOfWeek(Carbon::SUNDAY); $reportTitleDate = 'Minggu Ini';
                    }
                    break;
                case 'bulanan':
                    $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
                    $endDate = $startDate->copy()->endOfMonth();
                    $reportTitleDate = $startDate->isoFormat('MMMM YYYY');
                    break;
                case 'tahunan':
                    $startDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfYear();
                    $endDate = $startDate->copy()->endOfYear();
                    $reportTitleDate = $startDate->isoFormat('YYYY');
                    break;
            }
        } catch (\Exception $e) {
            \Log::error('Report API filter date error: '.$e->getMessage());
            $startDate = Carbon::today()->startOfDay(); $endDate = Carbon::today()->endOfDay(); $reportTitleDate = $startDate->isoFormat('D MMMM YYYY');
        }

        // === Query Rincian Pengeluaran (INTI LOGIKA BARU) ===

        // 1. Ambil Rincian Pengeluaran (dari tabel expenses)
        $expenseDetails = Expense::with(['employee', 'store', 'user'])
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->when($selectedStoreId, fn($q) => $q->where('id_store', $selectedStoreId))
            ->latest('expense_date')
            ->get()
            ->map(fn($item) => [
                'date' => $item->expense_date->isoFormat('DD MMM YYYY, HH:mm'),
                'description' => $item->description,
                'amount' => (float) $item->amount,
                'store_name' => $item->store->store_name ?? 'N/A',
                'employee_name' => $item->employee->employee_name ?? 'N/A',
                'recorded_by' => $item->user->name ?? 'N/A', // Pencatat
            ]);

        // 2. Ambil Rincian Tips (dari tabel transactions)
        $tipDetails = Transaction::with(['primaryEmployee', 'store', 'user'])
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where('tips', '>', 0) // Hanya ambil transaksi yang ada tips
            ->when($selectedStoreId, fn($q) => $q->where('id_store', $selectedStoreId))
            ->latest('transaction_date')
            ->get()
            ->map(fn($item) => [
                'date' => $item->transaction_date->isoFormat('DD MMM YYYY, HH:mm'),
                'description' => 'Tips untuk Capster: ' . ($item->primaryEmployee->employee_name ?? 'N/A'),
                'amount' => (float) $item->tips,
                'store_name' => $item->store->store_name ?? 'N/A',
                'employee_name' => $item->primaryEmployee->employee_name ?? 'N/A', // Karyawan yg dapat tips
                'recorded_by' => $item->cashierUser->name ?? 'N/A', // Pencatat
            ]);

        // 3. Gabungkan keduanya dan hitung total
        $allExpenditures = $expenseDetails->merge($tipDetails)->sortByDesc('date');
        $totalExpenditure = $allExpenditures->sum('amount');

        // Kembalikan data sebagai JSON
        return response()->json([
            'success' => true,
            'period' => $reportTitleDate,
            'total_expenditure' => $totalExpenditure,
            'expenditures' => $allExpenditures->values(), // values() untuk reset keys array
        ]);
    }

    /**
    * API: Mengambil rincian detail Laba/Rugi untuk modal.
    */
    public function getProfitLossDetails(Request $request): JsonResponse
    {
        // === Ambil Parameter Filter (logika SAMA PERSIS dengan method index) ===
        $filterType = $request->input('filter_type', 'harian');
        $selectedYear = $request->input('year', date('Y'));
        $selectedMonth = $request->input('month', date('m'));
        $selectedDay = $request->input('day', '01');
        $selectedWeekValue = $request->input('week', 1);
        $selectedStoreId = $request->input('store_id');
        $selectedPaymentMethodId = $request->input('payment_method_id'); // Pemasukan difilter oleh ini

        // === Hitung Rentang Tanggal (logika SAMA PERSIS dengan method index) ===
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();
        $reportTitleDate = $startDate->isoFormat('D MMMM YYYY');

        try {
            switch ($filterType) {
                case 'harian':
                    $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, $selectedDay)->startOfDay();
                    $endDate = $startDate->copy()->endOfDay();
                    $reportTitleDate = $startDate->isoFormat('D MMMM YYYY');
                    break;
                case 'mingguan':
                    $weeksOfMonth = $this->getWeeksOfMonth($selectedYear, $selectedMonth);
                    $selectedWeekData = collect($weeksOfMonth)->firstWhere('value', $selectedWeekValue);
                    if ($selectedWeekData) {
                        $startDate = $selectedWeekData['start']->startOfDay();
                        $endDate = $selectedWeekData['end']->endOfDay();
                        $reportTitleDate = $selectedWeekData['name'];
                    } else {
                        $startDate = Carbon::today()->startOfWeek(Carbon::MONDAY); $endDate = Carbon::today()->endOfWeek(Carbon::SUNDAY); $reportTitleDate = 'Minggu Ini';
                    }
                    break;
                case 'bulanan':
                    $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
                    $endDate = $startDate->copy()->endOfMonth();
                    $reportTitleDate = $startDate->isoFormat('MMMM YYYY');
                    break;
                case 'tahunan':
                    $startDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfYear();
                    $endDate = $startDate->copy()->endOfYear();
                    $reportTitleDate = $startDate->isoFormat('YYYY');
                    break;
            }
        } catch (\Exception $e) {
            \Log::error('Report API filter date error: '.$e->getMessage());
            $startDate = Carbon::today()->startOfDay(); $endDate = Carbon::today()->endOfDay(); $reportTitleDate = $startDate->isoFormat('D MMMM YYYY');
        }

        // === Query Ringkasan Laba/Rugi (INTI LOGIKA BARU) ===

        // 1. Total Pemasukan (SAMA seperti di index)
        $totalIncome = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->when($selectedStoreId, fn($q) => $q->where('id_store', $selectedStoreId))
            ->when($selectedPaymentMethodId, fn($q) => $q->where('id_payment_method', $selectedPaymentMethodId))
            ->sum('total_amount');

        // 2. Total Pengeluaran (SAMA seperti di index)
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->when($selectedStoreId, fn($q) => $q->where('id_store', $selectedStoreId))
            ->sum('amount');
        $totalTips = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->when($selectedStoreId, fn($q) => $q->where('id_store', $selectedStoreId))
            ->sum('tips');
        $totalExpenditure = $totalExpenses + $totalTips;

        // 3. Laba/Rugi Bersih
        $netProfitLoss = $totalIncome - $totalExpenditure;

        // Kembalikan data sebagai JSON
        return response()->json([
            'success' => true,
            'period' => $reportTitleDate,
            'total_income' => (float) $totalIncome,
            'total_expenditure' => (float) $totalExpenditure,
            'net_profit_loss' => (float) $netProfitLoss,
            'status' => $netProfitLoss >= 0 ? 'Laba' : 'Rugi',
        ]);
    }
    /**
    * API: Mengambil rincian detail SATU transaksi untuk modal.
    */
    public function getTransactionDetails(Transaction $transaction): JsonResponse
    {
        try {
            // Eager load semua relasi yang kita butuhkan untuk "struk"
            $transaction->load(
                'store', 
                'primaryEmployee', // Capster utama
                'paymentMethod',
                'details.service', // Detail item layanan
                'details.product', // Detail item produk
                'details.food',    // Detail item makanan
                'details.employee' // Capster per item (jika beda)
            );

            // Format data agar mudah dibaca oleh JavaScript
            $formattedDetails = $transaction->details->map(function ($detail) {
                $itemName = 'N/A';
                if ($detail->item_type === 'service' && $detail->service) {
                    $itemName = $detail->service->service_name;
                } elseif ($detail->item_type === 'product' && $detail->product) {
                    $itemName = $detail->product->product_name;
                } elseif ($detail->item_type === 'food' && $detail->food) {
                    $itemName = $detail->food->food_name;
                }

                return [
                    'item_type' => $detail->item_type,
                    'name' => $itemName,
                    'quantity' => $detail->quantity,
                    'price_at_sale' => (float) $detail->price_at_sale,
                    'subtotal' => (float) $detail->subtotal,
                    'employee_name' => $detail->employee->employee_name ?? 'N/A', // Capster per item
                ];
            });

            // Format data transaksi utama
            $formattedTransaction = [
                'id' => $transaction->id_transaction,
                'date' => $transaction->transaction_date->isoFormat('DD MMMM YYYY, HH:mm'),
                'store_name' => $transaction->store->store_name ?? 'N/A',
                'employee_name' => $transaction->primaryEmployee->employee_name ?? 'N/A',
                'payment_method' => $transaction->paymentMethod->method_name ?? 'N/A',
                'status' => 'Lunas', // Asumsi semua lunas
                'total_amount' => (float) $transaction->total_amount,
                'tips' => (float) $transaction->tips,

                // Pisahkan detail berdasarkan tipe
                'services' => $formattedDetails->where('item_type', 'service')->values(),
                'products' => $formattedDetails->where('item_type', 'product')->values(),
                'foods' => $formattedDetails->where('item_type', 'food')->values(),
            ];

            return response()->json([
                'success' => true,
                'transaction' => $formattedTransaction,
            ]);

        } catch (\Exception $e) {
            \Log::error("Report API error getting transaction details: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data transaksi.'], 500);
        }
    }
} // Akhir Class