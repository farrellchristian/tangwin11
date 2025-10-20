<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;   
use Illuminate\Support\Facades\Hash; 
use App\Models\User;                  
use Illuminate\Support\Facades\Schema; // <-- TAMBAHKAN INI

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan cek foreign key
        Schema::disableForeignKeyConstraints();
        
        // Kosongkan tabel users dulu
        DB::table('users')->truncate();

        // Aktifkan kembali cek foreign key
        Schema::enableForeignKeyConstraints();

        // Buat User Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin1'), // Passwordnya 'admin1'
            'role' => 'admin',
            'id_store' => 1 // Admin terikat ke toko 'Office'
        ]);

        // Buat User Kasir 1 (Toko Syuhada)
        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir1@gmail.com',
            'password' => Hash::make('kasir1'), // Passwordnya 'kasir1'
            'role' => 'kasir',
            'id_store' => 2 // ID Toko Syuhada
        ]);

        // Buat User Kasir 2 (Toko Sedayu)
        User::create([
            'name' => 'Kasir 2',
            'email' => 'kasir2@gmail.com',
            'password' => Hash::make('kasir2'), // Passwordnya 'kasir2'
            'role' => 'kasir',
            'id_store' => 3 // ID Toko Sedayu
        ]);
    }
}