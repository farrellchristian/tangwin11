<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Tambah kolom booking_number setelah id_reservation
            // Format: TWC-YYYYMM-NNN (contoh: TWC-202606-001)
            $table->string('booking_number', 20)->nullable()->unique()->after('id_reservation');
        });

        // Isi booking_number untuk data yang sudah ada
        $reservations = DB::table('reservations')->orderBy('id_reservation')->get();
        foreach ($reservations as $res) {
            $yearMonth = date('Ym', strtotime($res->created_at));

            // Hitung urutan dalam bulan yang sama
            $sequence = DB::table('reservations')
                ->whereRaw("DATE_FORMAT(created_at, '%Y%m') = ?", [$yearMonth])
                ->where('id_reservation', '<=', $res->id_reservation)
                ->count();

            $bookingNumber = 'TWC-' . $yearMonth . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);

            DB::table('reservations')
                ->where('id_reservation', $res->id_reservation)
                ->update(['booking_number' => $bookingNumber]);
        }
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('booking_number');
        });
    }
};
