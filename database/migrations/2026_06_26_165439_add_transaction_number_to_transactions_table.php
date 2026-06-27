<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Format: TRX-YYYYMM-NNN (contoh: TRX-202606-001)
            $table->string('transaction_number', 20)->nullable()->unique()->after('id_transaction');
        });

        // Isi transaction_number untuk semua data yang sudah ada
        $transactions = DB::table('transactions')->orderBy('id_transaction')->get();
        foreach ($transactions as $tx) {
            $yearMonth = date('Ym', strtotime($tx->created_at));

            // Hitung urutan dalam bulan yang sama
            $sequence = DB::table('transactions')
                ->whereRaw("DATE_FORMAT(created_at, '%Y%m') = ?", [$yearMonth])
                ->where('id_transaction', '<=', $tx->id_transaction)
                ->count();

            $txNumber = 'TRX-' . $yearMonth . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);

            DB::table('transactions')
                ->where('id_transaction', $tx->id_transaction)
                ->update(['transaction_number' => $txNumber]);
        }
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('transaction_number');
        });
    }
};
