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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('id_transaction'); // Primary Key

            // Foreign Keys
            $table->foreignId('id_store')->constrained('stores', 'id_store')->cascadeOnDelete();
            $table->foreignId('id_employee_primary')->constrained('employees', 'id_employee')->cascadeOnDelete();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete(); // Akun kasir yg mencatat
            $table->foreignId('id_payment_method')->constrained('payment_methods', 'id_payment_method');

            // Data Transaksi
            $table->decimal('total_amount', 15, 2); // Total akhir
            $table->decimal('amount_paid', 15, 2)->nullable(); // Jumlah dibayar (utk cash)
            $table->decimal('change_amount', 15, 2)->nullable(); // Kembalian (utk cash)
            $table->decimal('tips', 15, 2)->nullable(); // Tips
            $table->timestamp('transaction_date')->useCurrent(); // Tanggal Transaksi
            $table->text('notes')->nullable(); // Catatan

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};