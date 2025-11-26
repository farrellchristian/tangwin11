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
        Schema::create('reservation_slot_employee', function (Blueprint $table) {
            $table->id();

            // Hubungkan ke tabel Slot Jadwal
            $table->foreignId('id_slot')
                  ->constrained('reservation_slots', 'id_slot')
                  ->onDelete('cascade');

            // Hubungkan ke tabel Karyawan
            $table->foreignId('id_employee')
                  ->constrained('employees', 'id_employee')
                  ->onDelete('cascade');

            // Mencegah duplikasi (Satu karyawan tidak bisa dimasukkan 2x di slot yang sama)
            $table->unique(['id_slot', 'id_employee']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_slot_employee');
    }
};
