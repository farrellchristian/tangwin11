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
            // Tambahkan kolom baru setelah 'is_active'
            // 'store_ip_address' bisa diisi IP, dipisah koma jika lebih dari satu
            $table->string('store_ip_address')->nullable()->after('is_active');
            
            // 'enable_ip_validation' default false (0)
            $table->boolean('enable_ip_validation')->default(false)->after('store_ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn(['store_ip_address', 'enable_ip_validation']);
        });
    }
};