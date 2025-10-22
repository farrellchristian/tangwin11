<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;

use App\Models\Employee;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon; // Untuk cek tanggal
use Illuminate\Support\Facades\DB; // Untuk sum

class ExpenseController extends Controller
{
    /**
     * Menampilkan halaman pilih karyawan untuk input pengeluaran.
     * Hanya bisa diakses oleh role Kasir.
     */
    public function showSelectEmployee(): View
    {
        $user = Auth::user();

        // Pastikan hanya kasir yg bisa akses
        if ($user->role !== 'kasir') {
            abort(403, 'Hanya kasir yang bisa mengakses halaman ini.');
        }

        $storeId = $user->id_store;
        if (!$storeId) {
             abort(403, 'Akun kasir tidak terhubung ke toko manapun.');
        }

        // Ambil karyawan HANYA dari toko kasir tersebut
        $employees = Employee::where('id_store', $storeId)
                             ->where('is_active', true)
                             ->orderBy('employee_name')
                             ->get();

        // Tampilkan view pilih karyawan (akan kita buat)
        return view('kasir.expenses.select-employee', [
            'employees' => $employees,
        ]);
    }

    /**
     * Menampilkan form input pengeluaran untuk karyawan yang dipilih.
     */
    public function create(Employee $employee): View // Gunakan Route Model Binding
    {
        $user = Auth::user();

        // Validasi: Pastikan kasir hanya bisa input untuk karyawan di tokonya
        if ($user->role !== 'kasir' || $user->id_store !== $employee->id_store) {
            abort(403, 'Akses ditolak.');
        }

        // Hitung total pengeluaran karyawan HARI INI
        $todayExpenses = Expense::where('id_employee', $employee->id_employee)
                                ->whereDate('expense_date', Carbon::today())
                                ->sum('amount');

        // Tampilkan view form input (akan kita buat)
        return view('kasir.expenses.create', [
            'employee' => $employee,
            'todayExpenses' => $todayExpenses, // Kirim total hari ini untuk info limit
        ]);
    }

    /**
     * Menyimpan data pengeluaran baru.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Pastikan hanya kasir
        if ($user->role !== 'kasir') {
            abort(403, 'Akses ditolak.');
        }

        // Validasi input dasar
        $validatedData = $request->validate([
            'id_employee' => 'required|exists:employees,id_employee',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0.01', // Minimal pengeluaran 0.01
        ]);

        $employee = Employee::find($validatedData['id_employee']);

        // Validasi tambahan: Karyawan harus dari toko kasir
        if (!$employee || $employee->id_store !== $user->id_store) {
             return back()->withErrors(['id_employee' => 'Karyawan tidak valid.'])->withInput();
        }

        // Cek Limit Pengeluaran Harian Karyawan
        $limit = $employee->daily_expense_limit; // Ambil limit dari model employee
        if ($limit !== null) { // Hanya cek jika limit di-set (tidak null)
            $todayExpenses = Expense::where('id_employee', $employee->id_employee)
                                    ->whereDate('expense_date', Carbon::today())
                                    ->sum('amount');
            $newAmount = $validatedData['amount'];

            if (($todayExpenses + $newAmount) > $limit) {
                // Jika melebihi limit, kembalikan dengan error
                return back()->withErrors([
                    'amount' => 'Jumlah pengeluaran melebihi limit harian karyawan (Rp ' . number_format($limit, 0, ',', '.') . '). Sisa limit hari ini: Rp ' . number_format(max(0, $limit - $todayExpenses), 0, ',', '.')
                ])->withInput();
            }
        }

        // Jika lolos validasi & limit, simpan data
        Expense::create([
            'description' => $validatedData['description'],
            'amount' => $validatedData['amount'],
            'expense_date' => now(), // Waktu saat ini
            'id_employee' => $employee->id_employee,
            'id_store' => $user->id_store, // Ambil dari user kasir yg login
            'id_user' => $user->id, // ID user kasir yg input
        ]);

        // Redirect kembali ke halaman pilih karyawan dengan pesan sukses
        return redirect()->route('kasir.expenses.select-employee')
                         ->with('success', 'Pengeluaran berhasil dicatat.');
    }
}
