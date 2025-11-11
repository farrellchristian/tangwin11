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
        // Tabel ini berisi LOG/CATATAN absen harian
        Schema::create('presence_logs', function (Blueprint $table) {
            $table->id('id_presence_log'); // Ganti dari id_log_presensi

            // Relasi ke Karyawan dan Toko
            $table->foreignId('id_employee')->constrained('employees', 'id_employee')->cascadeOnDelete();
            $table->foreignId('id_store')->constrained('stores', 'id_store')->cascadeOnDelete();
            
            // Relasi ke Jadwal (opsional, bisa null jika absen di luar jadwal)
            $table->foreignId('id_presence_schedule')->nullable()->constrained('presence_schedules', 'id_presence_schedule')->nullOnDelete();

            // Waktu Check-in dan Check-out aktual
            $table->dateTime('check_in_time')->nullable();
            $table->dateTime('check_out_time')->nullable();

            // Status (misal: Tepat Waktu, Terlambat, Pulang Cepat, Izin, Alpha)
            $table->string('status', 50)->default('Pending'); 
            
            // Keterangan/Catatan
            $table->text('notes')->nullable();

            // IP Address (jika validasi IP aktif saat itu)
            $table->ipAddress('ip_address')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presence_logs');
    }
};