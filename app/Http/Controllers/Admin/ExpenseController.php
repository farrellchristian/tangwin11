<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Store;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Perlu untuk update limit
use Illuminate\Http\JsonResponse; // Perlu untuk response update limit

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource (Expense History & Limit Settings).
     */
    public function index(Request $request): View
    {
        // === Ambil Tahun Unik yang Punya Data Expense ===
        $availableYears = Expense::selectRaw('YEAR(expense_date) as year')
                                ->distinct()
                                ->orderBy('year', 'desc')
                                ->pluck('year')
                                ->toArray();

        // Jika tidak ada data sama sekali, gunakan tahun ini sebagai default
        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        }

        // === Ambil Data untuk Filter Dropdown ===
        $stores = Store::all();
        // $paymentMethods = PaymentMethod::where('is_active', true)->get();

        // === Ambil Data Karyawan untuk Setting Limit ===
        $employees = Employee::with('store')
                             ->where('is_active', true)
                             ->orderBy('id_store')
                             ->orderBy('employee_name')
                             ->get();

        // === Ambil Parameter Filter dari Request (dengan default yang lebih baik) ===
        $filterType = $request->input('filter_type', 'harian');
        // Gunakan tahun dari request, atau tahun pertama dari daftar, atau tahun ini
        $selectedYear = $request->input('year', $availableYears[0] ?? date('Y'));
        $selectedMonth = $request->input('month', date('m'));
        $selectedDay = $request->input('day', date('d'));
        $selectedWeek = $request->input('week', 1); // Perlu logika lebih baik nanti
        $selectedStoreId = $request->input('store_id');
        
        $selectedWeekValue = $request->input('week', 1);
        $weeksForDropdown = $this->getWeeksOfMonth($selectedYear, $selectedMonth);
        if ($selectedWeekValue > count($weeksForDropdown)) 
            {
                $selectedWeekValue = 1;
            }

        // === Bangun Query Riwayat Pengeluaran ===
        $expensesQuery = Expense::with(['employee', 'store', 'user'])
                                ->latest('expense_date');

        // Filter Toko
        if ($selectedStoreId) {
            $expensesQuery->where('id_store', $selectedStoreId);
        }

        // Filter Waktu (Logika Baru)
        $validSelectedDateString = Carbon::today()->toDateString(); // Default
        try {
            switch ($filterType) {
                case 'harian':
                    $dateForQuery = Carbon::createFromDate($selectedYear, $selectedMonth, $selectedDay);
                    $expensesQuery->whereDate('expense_date', $dateForQuery);
                    $validSelectedDateString = $dateForQuery->toDateString();
                    break;
                case 'mingguan':
                    // Temukan data minggu yang dipilih dari $weeksForDropdown
                    $selectedWeekData = collect($weeksForDropdown)->firstWhere('value', $selectedWeekValue);

                    if ($selectedWeekData) {
                        // Gunakan rentang tanggal dari data minggu yang dipilih
                        $expensesQuery->whereBetween('expense_date', [
                            $selectedWeekData['start']->startOfDay(), // Mulai dari awal hari
                            $selectedWeekData['end']->endOfDay()       // Sampai akhir hari
                        ]);
                        $validSelectedDateString = $selectedWeekData['start']->toDateString(); // Simpan tanggal awal
                    } else {
                        // Fallback jika minggu tidak ditemukan (seharusnya tidak terjadi)
                        $expensesQuery->whereDate('expense_date', Carbon::today());
                        $validSelectedDateString = Carbon::today()->toDateString();
                    }
                    break;
                case 'bulanan':
                    // ... (Logika bulanan tidak berubah) ...
                    $dateForQuery = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
                    $expensesQuery->whereYear('expense_date', $selectedYear)
                                ->whereMonth('expense_date', $selectedMonth);
                    $validSelectedDateString = $dateForQuery->toDateString();
                    break;
                case 'tahunan':
                    // ... (Logika tahunan tidak berubah) ...
                    $dateForQuery = Carbon::createFromDate($selectedYear, 1, 1);
                    $expensesQuery->whereYear('expense_date', $selectedYear);
                    $validSelectedDateString = $dateForQuery->toDateString();
                    break;
            }
            // Simpan tanggal valid untuk dikirim ke view
            $validSelectedDateString = $dateForQuery->toDateString();

        } catch (\Exception $e) {
             \Log::error('Expense filter date error: '.$e->getMessage());
             $filterType = 'harian';
             $selectedYear = date('Y');
             $selectedMonth = date('m');
             $selectedDay = date('d');
             $expensesQuery->whereDate('expense_date', Carbon::today());
             $validSelectedDateString = Carbon::today()->toDateString(); // Fallback date string
        }


        // Ambil data pengeluaran
        $expenses = $expensesQuery->paginate(15)->withQueryString();

        // TODO: Hitung daftar minggu untuk dropdown filter
        $weeksForDropdown = []; // Nanti diisi

        // === Kirim Semua Data ke View ===
        return view('admin.expenses.index', [
        'stores' => $stores,
        'employees' => $employees,
        'expenses' => $expenses,
        'filterType' => $filterType,
        'selectedYear' => $selectedYear,
        'selectedMonth' => $selectedMonth,
        'selectedDay' => $selectedDay,
        'selectedWeek' => $selectedWeekValue,
        'selectedDate' => $validSelectedDateString,
        'availableYears' => $availableYears,
        'weeksForDropdown' => $weeksForDropdown,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * (Mungkin tidak dipakai Admin)
     */
    public function create()
    {
        abort(404); // Sementara nonaktifkan
    }

    /**
     * Store a newly created resource in storage.
      * (Mungkin tidak dipakai Admin)
     */
    public function store(Request $request)
    {
        abort(404); // Sementara nonaktifkan
    }

    /**
    * Display the specified resource (di-override untuk JSON response modal).
    */
    public function show(Expense $expense): JsonResponse // Ubah return type ke JsonResponse
    {
        // Pastikan hanya admin yg bisa akses
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        try {
            // Eager load relasi yang kita butuhkan untuk "struk" pengeluaran
            $expense->load('store', 'employee', 'user');

            // Format data untuk modal
            $formattedExpense = [
                'id' => $expense->id_expense,
                'date' => $expense->expense_date->isoFormat('DD MMMM YYYY, HH:mm'),
                'store_name' => $expense->store->store_name ?? 'N/A',
                'employee_name' => $expense->employee->employee_name ?? 'N/A',
                'recorded_by' => $expense->user->name ?? 'N/A', // User yg input
                'description' => $expense->description,
                'amount' => (float) $expense->amount,
            ];

            return response()->json([
                'success' => true,
                'expense' => $formattedExpense,
            ]);

        } catch (\Exception $e) {
            \Log::error("Report API error getting expense details: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data pengeluaran.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * (Diaktifkan jika Route::resource diaktifkan)
     */
    public function edit(Expense $expense): View
    {
        // Pastikan hanya admin yg bisa edit
        if (Auth::user()->role !== 'admin') abort(403);

        $stores = Store::all();
        $employees = Employee::where('id_store', $expense->id_store) // Karyawan dari toko expense saja
                             ->where('is_active', true)
                             ->orderBy('employee_name')
                             ->get();
        $users = User::where('role', 'kasir') // User kasir yg relevan
                     ->where('id_store', $expense->id_store)
                     ->orWhere('role','admin') // Atau admin
                     ->orderBy('name')
                     ->get();

        return view('admin.expenses.edit', compact('expense', 'stores', 'employees', 'users'));
    }

    /**
     * Update the specified resource in storage.
     * (Diaktifkan jika Route::resource diaktifkan)
     */
    public function update(Request $request, Expense $expense)
    {
         // Pastikan hanya admin yg bisa update
        if (Auth::user()->role !== 'admin') abort(403);

        $validatedData = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date', // Admin bisa edit tanggal
            'id_employee' => 'required|exists:employees,id_employee',
            'id_store' => 'required|exists:stores,id_store',
            'id_user' => 'required|exists:users,id', // Ganti 'id' jika primary key users beda
        ]);

        // Validasi tambahan: Pastikan employee dan user ada di store yg dipilih
        $employee = Employee::find($validatedData['id_employee']);
        $user = User::find($validatedData['id_user']);
        if (!$employee || $employee->id_store != $validatedData['id_store'] || !$user || ($user->role === 'kasir' && $user->id_store != $validatedData['id_store'])) {
            return back()->withErrors(['id_store' => 'Kombinasi Toko, Karyawan, dan User tidak valid.'])->withInput();
        }

        $expense->update($validatedData);

        return redirect()->route('admin.expenses.index')
                         ->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     * (Diaktifkan jika Route::resource diaktifkan - Soft Delete)
     */
    public function destroy(Expense $expense)
    {
         // Pastikan hanya admin yg bisa delete
        if (Auth::user()->role !== 'admin') abort(403);

        $expense->delete(); // Soft delete

        return redirect()->route('admin.expenses.index')
                         ->with('success', 'Data pengeluaran berhasil dihapus (soft delete).');
    }

     /**
     * Method baru untuk update limit karyawan via AJAX/Fetch.
     */
    public function updateLimit(Request $request, Employee $employee): JsonResponse
    {
         // Pastikan hanya admin yg bisa update limit
        if (Auth::user()->role !== 'admin') {
             return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $validated = $request->validate([
            // Izinkan null, minimal 0 jika diisi
            'daily_expense_limit' => 'nullable|numeric|min:0',
        ]);

        try {
            $employee->update([
                'daily_expense_limit' => $validated['daily_expense_limit']
            ]);
            return response()->json(['success' => true, 'message' => 'Limit berhasil diperbarui.']);
        } catch (\Exception $e) {
            \Log::error('Error updating limit for employee '.$employee->id_employee.': '.$e->getMessage());
             return response()->json(['success' => false, 'message' => 'Gagal memperbarui limit.'], 500);
        }
    }

    /**
    * API: Get available months based on year with expense data.
    */
    public function getAvailableMonths(Request $request, $year): JsonResponse
    {
        try {
            $monthsData = Expense::selectRaw('DISTINCT MONTH(expense_date) as month')
                ->whereYear('expense_date', $year)
                ->orderBy('month')
                ->pluck('month')
                ->map(function ($monthNum) {
                    // Ubah nomor bulan (1-12) menjadi format '01'-'12' dan nama bulan
                    $monthNumPadded = str_pad($monthNum, 2, '0', STR_PAD_LEFT);
                    return [
                        'value' => $monthNumPadded,
                        'name' => Carbon::create()->month($monthNum)->isoFormat('MMMM') // Nama bulan (Januari, etc.)
                    ];
                });

            return response()->json($monthsData);

        } catch (\Exception $e) {
            \Log::error("Error getting available months for year {$year}: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data bulan.'], 500);
        }
    }

    /**
     * API: Get available days based on year/month with expense data.
     */
    public function getAvailableDays(Request $request, $year, $month): JsonResponse
    {
        try {
            $daysData = Expense::selectRaw('DISTINCT DAY(expense_date) as day')
                ->whereYear('expense_date', $year)
                ->whereMonth('expense_date', $month)
                ->orderBy('day')
                ->pluck('day')
                ->map(function ($dayNum) {
                    // Ubah nomor hari menjadi format '01'-'31'
                    return str_pad($dayNum, 2, '0', STR_PAD_LEFT);
                });

            return response()->json($daysData);

        } catch (\Exception $e) {
            \Log::error("Error getting available days for {$year}-{$month}: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data tanggal.'], 500);
        }
    }

    /**
     * API: Get available weeks based on year/month.
     * (Menggunakan helper getWeeksOfMonth yang sudah ada)
     */
    public function getAvailableWeeks(Request $request, $year, $month): JsonResponse
    {
        try {
            // Panggil helper yang sudah kita buat di method index
            $weeksData = $this->getWeeksOfMonth($year, $month);

            // Kita hanya butuh 'value' dan 'name' untuk dropdown
            $weeksForDropdown = collect($weeksData)->map(function ($week) {
                return ['value' => $week['value'], 'name' => $week['name']];
            })->values(); // values() untuk reset keys array

            return response()->json($weeksForDropdown);

        } catch (\Exception $e) {
            \Log::error("Error getting available weeks for {$year}-{$month}: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data minggu.'], 500);
        }
    }

    private function getWeeksOfMonth($year, $month): array
    {
        $weeks = [];
        $date = Carbon::createFromDate($year, $month, 1)->startOfWeek(Carbon::MONDAY);
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        $weekNumber = 1;

        while ($date->lte($endDate)) {
            $startOfWeek = $date->copy();
            $endOfWeek = $date->copy()->endOfWeek(Carbon::SUNDAY);

            // Pastikan minggu tidak keluar bulan (penting untuk minggu awal/akhir)
            if ($startOfWeek->month != $month) $startOfWeek->day($startOfWeek->daysInMonth)->startOfDay();
            if ($endOfWeek->month != $month) $endOfWeek = $endDate->copy()->endOfDay();

            // Filter out weeks that completely fall outside the month if startOfWeek logic is adjusted
            if ($startOfWeek->month == $month || $endOfWeek->month == $month) {
                $weekName = sprintf(
                    'Minggu %d (%s - %s)',
                    $weekNumber,
                    $startOfWeek->isoFormat('DD MMM'),
                    $endOfWeek->isoFormat('DD MMM')
                );

                $weeks[] = [
                    'value' => $weekNumber,
                    'name' => $weekName,
                    'start' => $startOfWeek,
                    'end' => $endOfWeek,
                ];
                $weekNumber++;
            }

            $date->addWeek()->startOfWeek(Carbon::MONDAY);
        }

        return $weeks;
    }
}