<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{

    /**
     * Menampilkan daftar semua toko.
     */
    public function index(): View
    {
        $stores = Store::latest()->paginate(10);
        return view('admin.stores.index', compact('stores'));
    }

    /**
     * Menampilkan form untuk membuat toko baru.
     */
    public function create(): View
    {
        return view('admin.stores.create');
    }

    /**
     * Menyimpan toko baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'store_name'           => 'required|string|max:255|unique:stores,store_name',
            'store_ip_address'     => 'nullable|string|max:255',
            'enable_ip_validation' => 'required|boolean',
        ]);

        Store::create($validated);

        return redirect()->route('admin.stores.index')
                         ->with('success', 'Toko baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit toko.
     */
    public function edit(Store $store): View
    {
        return view('admin.stores.edit', compact('store'));
    }

    /**
     * Memperbarui toko di database.
     */
    public function update(Request $request, Store $store): RedirectResponse
    {
        $validated = $request->validate([
            'store_name'           => 'required|string|max:255|unique:stores,store_name,' . $store->id_store . ',id_store',
            'store_ip_address'     => 'nullable|string|max:255',
            'enable_ip_validation' => 'required|boolean',
        ]);

        $store->update($validated);

        return redirect()->route('admin.stores.index')
                         ->with('success', 'Data toko berhasil diperbarui.');
    }

    /**
     * Menghapus toko (Soft Delete via deleted_at).
     */
    public function destroy(Store $store): RedirectResponse
    {
        if (strtolower($store->store_name) === 'office') {
            return redirect()->route('admin.stores.index')
                         ->with('error', 'Toko "Office" tidak bisa dihapus.');
        }

        try {
            $store->delete(); // Soft delete: mengisi kolom deleted_at
            return redirect()->route('admin.stores.index')
                         ->with('success', 'Toko berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.stores.index')
                         ->with('error', 'Gagal menghapus toko.');
        }
    }
}