<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // 1. Ambil semua toko
        $stores = Store::all();

        // 2. Mulai query products + relasi toko
        $productsQuery = Product::with('store');

        // 3. Filter berdasarkan toko jika dipilih
        if ($request->filled('store_id')) {
            $productsQuery->where('id_store', $request->store_id);
        }

        // 4. Ambil data (10 per halaman)
        $products = $productsQuery->latest()->paginate(10); 

        // 5. Kirim data ke view (kita akan buat view ini nanti)
        return view('admin.products.index', [
            'products' => $products,
            'stores' => $stores,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Ambil semua toko untuk dropdown
        $stores = Store::all();

        return view('admin.products.create', [
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
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_available' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'id_store' => 'required|exists:stores,id_store',
        ]);

        // Simpan
        Product::create([
            'product_name' => $request->product_name,
            'price' => $request->price,
            'stock_available' => $request->stock_available,
            'description' => $request->description,
            'id_store' => $request->id_store,
        ]);

        // Redirect
        return redirect()->route('admin.products.index')
                        ->with('success', 'Produk baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        // Ambil semua toko
        $stores = Store::all();

        return view('admin.products.edit', [
            'product' => $product, // Kirim data produk yang mau diedit
            'stores' => $stores
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validasi
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_available' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'id_store' => 'required|exists:stores,id_store',
        ]);

        // Update
        $product->update([
            'product_name' => $request->product_name,
            'price' => $request->price,
            'stock_available' => $request->stock_available,
            'description' => $request->description,
            'id_store' => $request->id_store,
        ]);

        // Redirect
        return redirect()->route('admin.products.index')
                        ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Soft Delete
        $product->delete();

        // Redirect
        return redirect()->route('admin.products.index')
                        ->with('success', 'Produk berhasil dihapus.');
    }
}
