<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel ini berisi JADWAL (master data), bukan log absen
        Schema::create('presence_schedules', function (Blueprint $table) {
            $table->id('id_presence_schedule'); // Ganti dari id_presensi

            // Relasi ke Toko (Jadwal ini milik toko mana)
            $table->foreignId('id_store')->constrained('stores', 'id_store')->cascadeOnDelete();

            // Hari dalam seminggu (0 = Minggu, 1 = Senin, 2 = Selasa, dst.)
            // 'day_of_week' dan 'id_store' harus unik bersama, 
            // agar 1 toko tidak punya 2 jadwal di hari Senin
            $table->tinyInteger('day_of_week'); 
            
            $table->time('jam_check_in');  // Jam seharusnya masuk
            $table->time('jam_check_out'); // Jam seharusnya pulang
            
            $table->boolean('is_active')->default(true); // Status jadwal (aktif/tidak)

            // Kita HAPUS kolom 'token' karena tidak dipakai lagi
            
            $table->timestamps();

            // Tambahkan unique constraint
            $table->unique(['id_store', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presence_schedules');
    }
};