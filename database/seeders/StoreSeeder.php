<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB facade
use App\Models\Store; // Import model Store

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel stores dulu untuk menghindari duplikat
        DB::table('stores')->truncate();

        // Buat data toko
        Store::create([
            'id_store' => 1,
            'store_name' => 'Syuhada'
        ]);

        Store::create([
            'id_store' => 2,
            'store_name' => 'Sedayu'
        ]);
        
        Store::create([
            'id_store' => 3,
            'store_name' => 'Office'
        ]);
    }
}