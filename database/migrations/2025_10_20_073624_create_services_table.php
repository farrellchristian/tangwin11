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
        Schema::create('services', function (Blueprint $table) {
            $table->id(); 
            $table->string('service_name'); 
            $table->text('description')->nullable(); 
            $table->decimal('price', 10, 2); 
            $table->string('image_path')->nullable(); 
            
            // Kolom wajib untuk relasi ke toko
            $table->unsignedBigInteger('id_store');
            $table->foreign('id_store')->references('id_store')->on('stores')->onDelete('cascade');
            
            // Perbaikan Soft Delete
            $table->softDeletes(); // <-- INI DIA PERBAIKANNYA (Menambah kolom deleted_at)
            
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Perbaikan: Hapus softDeletes dulu sebelum drop
        Schema::table('services', function (Blueprint $table) {
            $table->dropSoftDeletes(); // <-- INI PERBAIKANNYA
        });
        Schema::dropIfExists('services');
    }
};