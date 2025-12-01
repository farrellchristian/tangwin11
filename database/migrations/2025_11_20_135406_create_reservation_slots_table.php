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
        Schema::create('reservation_slots', function (Blueprint $table) {
            $table->id('id_slot');
            
            // Relasi ke Toko
            $table->foreignId('id_store')
                  ->constrained('stores', 'id_store')
                  ->onDelete('cascade');

            // Hari (Senin, Selasa, dst)
            // Kita simpan sebagai integer: 0=Minggu, 1=Senin, ... 6=Sabtu
            // Atau string: 'Senin', 'Selasa' (Tergantung preferensi, tapi integer lebih mudah di-coding)
            $table->string('day_of_week'); 
            
            // Jam Slot (Misal: 16:00:00)
            $table->time('slot_time');

            // Kuota (Untuk persiapan masa depan jika 1 jam bisa 3 orang)
            // Default 1 sesuai request kamu sekarang.
            $table->integer('quota')->default(1);

            // Status aktif/tidak (biar bisa dimatikan tanpa dihapus)
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_slots');
    }
};
