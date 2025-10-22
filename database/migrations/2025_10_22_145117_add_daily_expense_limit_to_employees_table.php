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
        Schema::table('employees', function (Blueprint $table) {
            // Tambahkan kolom baru setelah 'is_active'
            $table->decimal('daily_expense_limit', 15, 2)->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn('daily_expense_limit');
        });
    }
};