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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id('id_expense'); // Primary key
            $table->text('description'); // Keterangan pengeluaran
            $table->decimal('amount', 15, 2); // Jumlah pengeluaran
            $table->timestamp('expense_date')->useCurrent(); // Tanggal pengeluaran

            // Foreign Keys
            $table->foreignId('id_employee')->constrained('employees', 'id_employee')->cascadeOnDelete(); // Karyawan yg melakukan
            $table->foreignId('id_store')->constrained('stores', 'id_store')->cascadeOnDelete(); // Toko tempat pengeluaran
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete(); // Akun kasir yg menginput

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};