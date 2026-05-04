<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('id_reservation')->nullable()->after('order_id');
            // Menambahkan foreign key constraint
            // Jika ada aturan penghapusan khusus, kita bisa tambahkan onDelete
            $table->foreign('id_reservation')
                  ->references('id_reservation')
                  ->on('reservations')
                  ->onDelete('set null');
        });

        // Insert otomatis Payment Method untuk Midtrans/Reservasi jika belum ada
        $exists = DB::table('payment_methods')->where('method_name', 'Reservasi Online')->exists();
        if (!$exists) {
            DB::table('payment_methods')->insert([
                'method_name' => 'Reservasi Online',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['id_reservation']);
            $table->dropColumn('id_reservation');
        });
        
        DB::table('payment_methods')->where('method_name', 'Reservasi Online')->delete();
    }
};
