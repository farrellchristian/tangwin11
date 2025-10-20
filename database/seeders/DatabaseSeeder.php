<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder yang kita buat, StoreSeeder DULUAN
        $this->call([
            StoreSeeder::class,
            UserSeeder::class,
        ]);
    }
}