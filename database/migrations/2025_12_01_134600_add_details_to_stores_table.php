<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            // Menambahkan kolom alamat dan no telp setelah nama toko
            $table->text('address')->nullable()->after('store_name');
            $table->string('phone_number', 20)->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['address', 'phone_number']);
        });
    }
};
