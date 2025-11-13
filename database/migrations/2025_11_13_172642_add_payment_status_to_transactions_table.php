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
        Schema::table('transactions', function (Blueprint $table) {
            // Menambah kolom status
            // 'paid' = Lunas (untuk cash atau yg sudah dikonfirmasi)
            // 'pending' = Menunggu pembayaran
            // 'failed' / 'expired' = Gagal bayar
            $table->string('status')->default('paid')->after('id_payment_method');
            
            // Menambah kolom untuk order_id dari Midtrans (TANGWIN-xxx)
            // nullable() = Boleh kosong (untuk transaksi cash)
            // unique() = Tidak boleh ada order_id yang sama
            $table->string('order_id')->nullable()->unique()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            // (Penting: Hapus dalam urutan terbalik dari pembuatannya)
            $table->dropColumn('order_id');
            $table->dropColumn('status');
        });
    }
};