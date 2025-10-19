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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom 'role' setelah kolom 'password'
            // Defaultnya 'kasir' (atau 'employee', terserah Anda)
            $table->string('role')->after('password')->default('kasir'); 
            
            // Tambahkan kolom 'id_store' (bisa null untuk admin)
            $table->unsignedBigInteger('id_store')->after('role')->nullable();

            // Jadikan 'id_store' sebagai foreign key ke tabel 'stores'
            // Jika toko dihapus, 'id_store' user ini akan jadi NULL
            $table->foreign('id_store')->references('id_store')->on('stores')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ganti ini
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key dulu
            $table->dropForeign(['id_store']);
            
            // Hapus kedua kolom
            $table->dropColumn(['role', 'id_store']);
        });
    }
};
