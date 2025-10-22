<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    /**
     * Nama primary key tabel ini.
     */
    protected $primaryKey = 'id_expense';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'description',
        'amount',
        'expense_date',
        'id_employee',
        'id_store',
        'id_user', // Akun kasir yg input
    ];

    /**
     * Tipe data bawaan untuk kolom tertentu.
     */
    protected $casts = [
        'expense_date' => 'datetime',
        'amount' => 'decimal:2',
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
     * Relasi ke User (Kasir yg input)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user'); // Asumsi primary key User adalah 'id'
    }
}