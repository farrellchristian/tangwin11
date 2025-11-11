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
        Schema::table('stores', function (Blueprint $table) {
            // Tambahkan kolom 'is_active' setelah 'store_name'
            // Kita set default 'true' (1) agar semua toko lama otomatis aktif
            $table->boolean('is_active')->after('store_name')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn('is_active');
        });
    }
};