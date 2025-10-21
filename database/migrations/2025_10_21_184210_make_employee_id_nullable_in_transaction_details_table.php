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
        Schema::table('transaction_details', function (Blueprint $table) {
            // Hapus foreign key constraint dulu
            $table->dropForeign(['id_employee']);

            // Ubah kolom id_employee menjadi nullable
            $table->unsignedBigInteger('id_employee')->nullable()->change();

            // Tambahkan kembali foreign key constraint dengan onDelete('set null')
            // Jika karyawan dihapus, id_employee di detail jadi null (lebih aman)
            $table->foreign('id_employee')
                  ->references('id_employee')
                  ->on('employees')
                  ->onDelete('set null'); // Ganti dari cascade
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
             // Hapus foreign key constraint dulu
            $table->dropForeign(['id_employee']);

            // Kembalikan kolom id_employee menjadi not nullable
            // PERHATIAN: Ini bisa gagal jika sudah ada data null
            $table->unsignedBigInteger('id_employee')->nullable(false)->change();

             // Tambahkan kembali foreign key constraint lama (cascade)
            $table->foreign('id_employee')
                  ->references('id_employee')
                  ->on('employees')
                  ->onDelete('cascade');
        });
    }
};