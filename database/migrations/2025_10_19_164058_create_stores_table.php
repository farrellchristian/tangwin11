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
        // Ganti ini
        Schema::create('stores', function (Blueprint $table) {
            $table->id('id_store'); // Kolom ID primary key, bernama 'id_store'
            $table->string('store_name'); // Kolom nama tokonya
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ganti ini
        Schema::dropIfExists('stores');
    }
};
