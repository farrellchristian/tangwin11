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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id('id_transaction_detail'); // Primary Key

            // Foreign Keys
            $table->foreignId('id_transaction')->constrained('transactions', 'id_transaction')->cascadeOnDelete();
            $table->foreignId('id_employee')->constrained('employees', 'id_employee')->cascadeOnDelete(); // Capster per item

            // Item Type Identification
            $table->string('item_type'); // 'service', 'product', 'food'

            // Foreign Keys for Items (nullable, only one will be filled per row)
            $table->foreignId('id_service')->nullable()->constrained('services', 'id_service')->cascadeOnDelete();
            $table->foreignId('id_product')->nullable()->constrained('products', 'id_product')->cascadeOnDelete();
            $table->foreignId('id_food')->nullable()->constrained('foods', 'id_food')->cascadeOnDelete();

            // Item Details
            $table->integer('quantity'); // Jumlah item
            $table->decimal('price_at_sale', 15, 2); // Harga satuan saat itu
            $table->decimal('subtotal', 15, 2); // quantity * price_at_sale

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};