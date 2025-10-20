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
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // 'id_karyawan'
            $table->string('employee_name'); // 'nama_karyawan'
            $table->string('photo_path')->nullable(); // 'photo_path'
            $table->string('position'); // 'jabatan'
            $table->string('phone_number')->nullable(); // 'nomor_telepon'
            $table->date('join_date'); // 'tanggal_masuk'
            $table->date('exit_date')->nullable(); // 'tanggal_keluar'
            $table->boolean('is_active')->default(true); // 'aktif'
            
            // Kolom wajib untuk relasi ke toko
            $table->unsignedBigInteger('id_store');
            $table->foreign('id_store')->references('id_store')->on('stores')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};