<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenceLog extends Model
{
    use HasFactory;

    /**
     * Nama primary key tabel ini.
     */
    protected $primaryKey = 'id_presence_log';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'id_employee',
        'id_store',
        'id_presence_schedule',
        'check_in_time',
        'check_out_time',
        'status',
        'notes',
        'ip_address',
    ];

    /**
     * Tipe data bawaan untuk kolom tertentu.
     */
    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime', 
    ];

    /**
     * Relasi ke Karyawan (Employee)
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee', 'id_employee');
    }

    /**
     * Relasi ke Toko (Store)
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'id_store', 'id_store');
    }

    /**
     * Relasi ke Jadwal (PresenceSchedule)
     */
    public function schedule()
    {
        return $this->belongsTo(PresenceSchedule::class, 'id_presence_schedule', 'id_presence_schedule');
    }
}