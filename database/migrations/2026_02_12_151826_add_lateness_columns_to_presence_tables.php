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
        if (!Schema::hasColumn('presence_schedules', 'late_threshold')) {
            Schema::table('presence_schedules', function (Blueprint $table) {
                $table->integer('late_threshold')->default(0)->after('jam_check_out')->comment('Batas toleransi keterlambatan dalam menit');
            });
        }

        if (!Schema::hasColumn('presence_logs', 'late_minutes')) {
            Schema::table('presence_logs', function (Blueprint $table) {
                $table->integer('late_minutes')->default(0)->after('ip_address')->comment('Jumlah menit keterlambatan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presence_schedules', function (Blueprint $table) {
            $table->dropColumn('late_threshold');
        });

        Schema::table('presence_logs', function (Blueprint $table) {
            $table->dropColumn('late_minutes');
        });
    }
};
