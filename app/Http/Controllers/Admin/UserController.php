<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Model User
use App\Models\Store; // Model Store (untuk dropdown)
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Illuminate\Validation\Rules; // Untuk rule validasi

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua akun kasir.
     */
    public function index(Request $request): View
    {
        $stores = Store::all();
        $selectedStoreId = $request->input('store_id');

        $usersQuery = User::where('role', 'kasir') // HANYA ambil role 'kasir'
                          ->with('store') // Eager load relasi toko
                          ->latest();

        // Filter berdasarkan toko jika dipilih
        if ($selectedStoreId) {
            $usersQuery->where('id_store', $selectedStoreId);
        }

        $users = $usersQuery->paginate(10)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'stores' => $stores,
        ]);
    }

    /**
     * Menampilkan form untuk membuat akun kasir baru.
     */
    public function create(): View
    {
        $stores = Store::where('store_name', '!=', 'Office')->get(); // Ambil semua toko kecuali 'Office'
        return view('admin.users.create', compact('stores'));
    }

    /**
     * Menyimpan akun kasir baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', \Illuminate\Validation\Rule::unique(User::class)->whereNull('deleted_at')],
            'password' => ['required', 'confirmed'],
            'id_store' => ['required', 'exists:stores,id_store'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kasir', // Otomatis set role 'kasir'
            'id_store' => $request->id_store,
            'is_active' => 1, // Tetap default 1 untuk legacy
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Akun kasir baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit akun kasir.
     */
    public function edit(User $user): View
    {
        // Pastikan kita tidak mengedit admin secara tidak sengaja
        if ($user->role === 'admin') {
            abort(403, 'Tidak bisa mengedit akun Admin dari halaman ini.');
        }

        $stores = Store::where('store_name', '!=', 'Office')->get(); // Ambil semua toko kecuali 'Office'
        return view('admin.users.edit', compact('user', 'stores'));
    }

    /**
     * Memperbarui akun kasir di database.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Pastikan kita tidak mengedit admin
        if ($user->role === 'admin') {
            abort(403);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', \Illuminate\Validation\Rule::unique(User::class)->ignore($user->id)->whereNull('deleted_at')],
            'id_store' => ['required', 'exists:stores,id_store'],
        ];

        // Cek jika admin ingin mengganti password
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed'];
        }

        $validatedData = $request->validate($rules);

        // Update data dasar
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->id_store = $validatedData['id_store'];

        // Update password HANYA jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Akun kasir berhasil diperbarui.');
    }

    /**
     * Menghapus akun kasir (Soft Delete).
     */
    public function destroy(User $user): RedirectResponse
    {
        // Pastikan kita tidak menghapus admin
        if ($user->role === 'admin') {
             return redirect()->route('admin.users.index')
                         ->with('error', 'Tidak bisa menghapus akun Admin.');
        }

        // Hapus akun (Soft Delete karena model User pakai trait SoftDeletes)
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Akun kasir berhasil dihapus.');
    }
}