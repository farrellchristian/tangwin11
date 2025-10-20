<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // 1. Ambil semua toko
        $stores = Store::all();

        // 2. Mulai query foods + relasi toko
        $foodsQuery = Food::with('store');

        // 3. Filter berdasarkan toko jika dipilih
        if ($request->filled('store_id')) {
            $foodsQuery->where('id_store', $request->store_id);
        }

        // 4. Ambil data (10 per halaman)
        $foods = $foodsQuery->latest()->paginate(10); 

        // 5. Kirim data ke view (kita akan buat view ini nanti)
        return view('admin.foods.index', [
            'foods' => $foods,
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

        return view('admin.foods.create', [
            'stores' => $stores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'food_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_available' => 'required|integer|min:0',
            'id_store' => 'required|exists:stores,id_store',
        ]);

        // Simpan
        Food::create([
            'food_name' => $request->food_name,
            'price' => $request->price,
            'stock_available' => $request->stock_available,
            'id_store' => $request->id_store,
        ]);

        // Redirect
        return redirect()->route('admin.foods.index')
                        ->with('success', 'Makanan/Minuman baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Food $food)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Food $food): View
    {
        // Ambil semua toko
        $stores = Store::all();

        return view('admin.foods.edit', [
            'food' => $food, // Kirim data makanan yg mau diedit
            'stores' => $stores
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Food $food)
    {
        // Validasi
        $request->validate([
            'food_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_available' => 'required|integer|min:0',
            'id_store' => 'required|exists:stores,id_store',
        ]);

        // Update
        $food->update([
            'food_name' => $request->food_name,
            'price' => $request->price,
            'stock_available' => $request->stock_available,
            'id_store' => $request->id_store,
        ]);

        // Redirect
        return redirect()->route('admin.foods.index')
                        ->with('success', 'Makanan/Minuman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food)
    {
        // Soft Delete
        $food->delete();

        // Redirect
        return redirect()->route('admin.foods.index')
                        ->with('success', 'Makanan/Minuman berhasil dihapus.');
    }
}
