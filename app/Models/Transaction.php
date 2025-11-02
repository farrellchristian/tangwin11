<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama primary key tabel ini.
     */
    protected $primaryKey = 'id_transaction';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'id_store',
        'id_employee_primary',
        'id_user',
        'id_payment_method',
        'total_amount',
        'amount_paid',
        'change_amount',
        'tips',
        'transaction_date',
        'notes',
    ];

    /**
     * Tipe data bawaan untuk kolom tertentu.
     */
    protected $casts = [
        'transaction_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'tips' => 'decimal:2',
    ];

    /**
     * Relasi ke Toko (Store)
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'id_store', 'id_store');
    }

    /**
     * Relasi ke Karyawan Utama (Employee)
     */
    public function primaryEmployee()
    {
        return $this->belongsTo(Employee::class, 'id_employee_primary', 'id_employee');
    }

    /**
     * Relasi ke User (Kasir yg mencatat)
     */
    public function cashierUser()
    {
        return $this->belongsTo(User::class, 'id_user'); // Asumsi primary key User adalah 'id'
    }

    /**
     * Relasi ke Metode Pembayaran (PaymentMethod)
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'id_payment_method', 'id_payment_method');
    }

    /**
     * Relasi ke Detail Transaksi (TransactionDetail) - Satu transaksi punya banyak detail
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'id_transaction', 'id_transaction');
    }
}