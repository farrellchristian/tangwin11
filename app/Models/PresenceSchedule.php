<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenceSchedule extends Model
{
    use HasFactory;

    /**
     * Nama primary key tabel ini.
     */
    protected $primaryKey = 'id_presence_schedule';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'id_store',
        'day_of_week',
        'jam_check_in',
        'jam_check_out',
        'is_active',
    ];

    /**
     * Tipe data bawaan untuk kolom tertentu.
     */
    protected $casts = [
        'is_active' => 'boolean',
        // Kita tidak cast 'jam_check_in' & 'jam_check_out' ke datetime
        // karena itu hanya TIME, bukan TIMESTAMP.
    ];

    /**
     * Relasi ke Toko (Store)
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'id_store', 'id_store');
    }

    /**
     * Relasi ke Log Presensi (Satu jadwal bisa punya banyak log)
     */
    public function logs()
    {
        return $this->hasMany(PresenceLog::class, 'id_presence_schedule', 'id_presence_schedule');
    }
}