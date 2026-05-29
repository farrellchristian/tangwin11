<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom show_on_reservation ke 4 tabel:
     * stores, services, employees, payment_methods.
     *
     * Kolom ini TERPISAH dari is_active:
     * - is_active           => status operasional (tracking laporan, dll)
     * - show_on_reservation => kontrol tampil/tidak di web reservasi pelanggan
     */
    public function up(): void
    {
        // 1. Stores
        Schema::table('stores', function (Blueprint $table) {
            $table->boolean('show_on_reservation')->default(true)->after('is_active')
                  ->comment('Tampilkan cabang ini di web reservasi pelanggan?');
        });

        // 2. Services
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('show_on_reservation')->default(true)->after('image_path')
                  ->comment('Tampilkan layanan ini di web reservasi pelanggan?');
        });

        // 3. Employees (Stylist / Capster)
        Schema::table('employees', function (Blueprint $table) {
            $table->boolean('show_on_reservation')->default(true)->after('is_active')
                  ->comment('Tampilkan stylist ini di web reservasi pelanggan?');
        });

        // 4. Payment Methods
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->boolean('show_on_reservation')->default(true)->after('is_active')
                  ->comment('Tampilkan metode pembayaran ini di web reservasi pelanggan?');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('show_on_reservation');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('show_on_reservation');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('show_on_reservation');
        });

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('show_on_reservation');
        });
    }
};
