<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\View\View;
    
class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // 1. Ambil semua toko untuk dropdown filter
        $stores = Store::all();

        // 2. Mulai query untuk services, dan panggil relasi 'store'
        //    (agar kita bisa tahu nama tokonya)
        $servicesQuery = Service::with('store');

        // 3. Cek apakah ada filter 'store_id' dari URL (misal: ?store_id=1)
        if ($request->filled('store_id')) {
            $servicesQuery->where('id_store', $request->store_id);
        }

        // 4. Ambil datanya setelah difilter (atau semua jika tidak ada filter)
        $services = $servicesQuery->latest()->paginate(10); // Ambil 10 per halaman

        // 5. Kirim data services dan stores ke view
        return view('admin.services.index', [
            'services' => $services,
            'stores' => $stores,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Kita perlu mengambil semua toko agar Admin bisa memilih
        // layanan ini akan dimasukkan ke toko mana.
        $stores = Store::all();

        return view('admin.services.create', [
            'stores' => $stores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Hapus type-hint 'StoreServiceRequest' jika ada
    {
        // 1. Validasi data yang masuk
        $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'id_store' => 'required|exists:stores,id_store', // Pastikan id_store ada di tabel stores
        ]);

        // 2. Buat data baru di database
        Service::create([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'description' => $request->description,
            'id_store' => $request->id_store,
        ]);

        // 3. Alihkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.services.index') ->with('success', 'Layanan baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // Laravel otomatis mencari Service berdasarkan {service} di URL
    public function edit(Service $service): View 
    {
        // Ambil semua toko untuk dropdown (jika admin ingin pindah toko)
        $stores = Store::all();

        return view('admin.services.edit', [
            'service' => $service, // Kirim data layanan yang mau diedit
            'stores' => $stores
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service) 
    {
        // 1. Validasi data yang masuk (sama seperti 'store')
        $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'id_store' => 'required|exists:stores,id_store',
        ]);

        // 2. Update data di database
        $service->update([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'description' => $request->description,
            'id_store' => $request->id_store,
        ]);

        // 3. Alihkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.services.index') ->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service) 
    {
        // 1. Hapus (atau soft delete) data
        $service->delete();

        // 2. Alihkan kembali ke HALAMAN INDEX (tabel) dengan pesan sukses
        return redirect()->route('admin.services.index') ->with('success', 'Layanan berhasil dihapus.');
    }
}
