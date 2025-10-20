<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use App\Models\Store; 
use Illuminate\Support\Facades\Schema; // <-- TAMBAHKAN INI

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan cek foreign key
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel stores dulu
        DB::table('stores')->truncate();

        // Aktifkan kembali cek foreign key
        Schema::enableForeignKeyConstraints();

        // Buat data toko
        Store::create([
            'id_store' => 1,
            'store_name' => 'Office'
        ]);

        Store::create([
            'id_store' => 2,
            'store_name' => 'Syuhada'
        ]);

        Store::create([
            'id_store' => 3,
            'store_name' => 'Sedayu'
        ]);
        
    }
}