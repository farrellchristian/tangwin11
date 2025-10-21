<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // 1. Ambil semua toko
        $stores = Store::all();

        // 2. Mulai query employees + relasi toko
        $employeesQuery = Employee::with('store');

        // 3. Filter berdasarkan toko jika dipilih
        if ($request->filled('store_id')) {
            $employeesQuery->where('id_store', $request->store_id);
        }

        // 4. Filter berdasarkan status aktif (defaultnya hanya tampilkan yg aktif)
        if ($request->filled('status') && $request->status == 'nonaktif') {
            // Jika filter 'nonaktif' dipilih, tampilkan HANYA yang non-aktif
            $employeesQuery->where('is_active', false);
        } else {
            // Selain itu (termasuk filter 'semua' atau tanpa filter), tampilkan HANYA yang aktif
            $employeesQuery->where('is_active', true);
        }


        // 5. Ambil data (10 per halaman)
        $employees = $employeesQuery->latest('join_date')->paginate(10); // Urutkan berdasarkan tanggal masuk terbaru

        // 6. Kirim data ke view (kita akan buat view ini nanti)
        return view('admin.employees.index', [
            'employees' => $employees,
            'stores' => $stores,
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Ambil semua toko
        $stores = Store::all();

        return view('admin.employees.create', [
            'stores' => $stores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi (tambahkan validasi lain jika perlu)
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'join_date' => 'required|date',
            'id_store' => 'required|exists:stores,id_store',
            'phone_number' => 'nullable|string|max:20', // Contoh validasi nomor HP
            // 'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Jika pakai upload foto
        ]);

        // Simpan
        Employee::create([
            'employee_name' => $request->employee_name,
            'position' => $request->position,
            'join_date' => $request->join_date,
            'id_store' => $request->id_store,
            'phone_number' => $request->phone_number,
            'is_active' => true, // Otomatis aktif saat dibuat
            // 'photo_path' => $path ?? null, // Jika pakai upload foto
        ]);

        // Redirect
        return redirect()->route('admin.employees.index')
                        ->with('success', 'Karyawan baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee): View
    {
        // Ambil semua toko
        $stores = Store::all();

        return view('admin.employees.edit', [
            'employee' => $employee, // Kirim data karyawan yg mau diedit
            'stores' => $stores
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        // Validasi
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'join_date' => 'required|date',
            'exit_date' => 'nullable|date|after_or_equal:join_date', // Tgl keluar hrs setelah/sama dgn tgl masuk
            'id_store' => 'required|exists:stores,id_store',
            'phone_number' => 'nullable|string|max:20',
            'is_active' => 'required|boolean', // Tambah validasi status
            // 'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update
        $employee->update([
            'employee_name' => $request->employee_name,
            'position' => $request->position,
            'join_date' => $request->join_date,
            'exit_date' => $request->exit_date,
            'id_store' => $request->id_store,
            'phone_number' => $request->phone_number,
            'is_active' => $request->is_active,
            // 'photo_path' => $path ?? $employee->photo_path, // Update foto jika ada yg baru
        ]);

        // Redirect
        return redirect()->route('admin.employees.index')
                        ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     * Kita modifikasi ini menjadi 'Nonaktifkan'
     */
    public function destroy(Employee $employee)
    {
        // Update status is_active menjadi false
        $employee->update(['is_active' => false]);

        // Redirect
        return redirect()->route('admin.employees.index')
                        ->with('success', 'Karyawan berhasil dinonaktifkan.');
    }
}
