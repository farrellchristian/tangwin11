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
        Schema::create('reservations', function (Blueprint $table) {
            // Primary Key
            $table->id('id_reservation');

            // Toko (Wajib)
            $table->foreignId('id_store')
                  ->constrained('stores', 'id_store')
                  ->onDelete('cascade');

            // Data Customer
            $table->string('customer_name');
            $table->string('customer_phone');
            
            // Waktu Reservasi
            // Kita pisah Date dan Time agar query pencarian slot lebih mudah
            $table->date('booking_date'); // Contoh: 2025-11-25
            $table->time('booking_time'); // Contoh: 16:00:00

            // Service (Opsional)
            $table->foreignId('id_service')
                  ->nullable()
                  ->constrained('services', 'id_service')
                  ->onDelete('set null');

            // Employee / Capster yang DIPILIH customer
            $table->foreignId('id_employee')
                  ->nullable()
                  ->constrained('employees', 'id_employee')
                  ->onDelete('set null');

            // Status (pending, confirmed, cancelled, completed)
            $table->string('status')->default('pending');
            
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
