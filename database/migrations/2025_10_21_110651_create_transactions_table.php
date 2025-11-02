<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('id_transaction');

            $table->foreignId('id_store')->constrained('stores', 'id_store')->cascadeOnDelete();
            $table->foreignId('id_employee_primary')->constrained('employees', 'id_employee')->cascadeOnDelete();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->foreignId('id_payment_method')->constrained('payment_methods', 'id_payment_method');

            $table->decimal('total_amount', 15, 2);
            $table->decimal('amount_paid', 15, 2)->nullable();
            $table->decimal('change_amount', 15, 2)->nullable();
            $table->decimal('tips', 15, 2)->nullable();
            $table->timestamp('transaction_date')->useCurrent();
            $table->text('notes')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('transactions');
    }
};