<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_employee';

    protected $fillable = [
        'employee_name',
        'photo_path',
        'position',
        'phone_number',
        'join_date',
        'exit_date',
        'is_active',
        'id_store',
        'daily_expense_limit', // <-- TAMBAHKAN INI
    ];

    protected $casts = [
        'join_date' => 'date',
        'exit_date' => 'date',
        'is_active' => 'boolean',
        'daily_expense_limit' => 'decimal:2', // <-- TAMBAHKAN INI
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'id_store', 'id_store');
    }

    // (Opsional) Relasi ke Expenses jika diperlukan
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'id_employee', 'id_employee');
    }

    // Tambahkan ini di dalam class Employee
    public function slots()
    {
        return $this->belongsToMany(ReservationSlot::class, 'reservation_slot_employee', 'id_employee', 'id_slot');
    }

    /**
     * Relasi: Satu karyawan memiliki banyak transaksi (sebagai Capster Utama)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_employee_primary', 'id_employee');
    }
}