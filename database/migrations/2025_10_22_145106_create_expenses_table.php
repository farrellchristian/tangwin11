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
            $table->id('id_expense');
            $table->text('description');
            $table->decimal('amount', 15, 2);
            $table->timestamp('expense_date')->useCurrent();

            $table->foreignId('id_employee')->constrained('employees', 'id_employee')->cascadeOnDelete();
            $table->foreignId('id_store')->constrained('stores', 'id_store')->cascadeOnDelete();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();

            $table->softDeletes(); // <-- TAMBAHKAN INI
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropSoftDeletes(); // <-- TAMBAHKAN INI
        });
        Schema::dropIfExists('expenses');
    }
};