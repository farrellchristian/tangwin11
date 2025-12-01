<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Tetap pakai SoftDeletes

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_transaction';

    // Kita pertahankan fillable kamu yang lengkap
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
        'status',   // Tambahan: status (paid/pending)
        'order_id'  // Tambahan: order_id (untuk Qris)
    ];

    // Kita pertahankan casts kamu
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
     * PERBAIKAN: Namanya diganti jadi 'employee' agar sesuai Controller
     */
    public function employee()
    {
        // Tetap mengacu ke 'id_employee_primary'
        return $this->belongsTo(Employee::class, 'id_employee_primary', 'id_employee');
    }

    /**
     * Relasi ke User (Kasir yg mencatat)
     * PERBAIKAN: Namanya diganti jadi 'user' agar sesuai Controller
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relasi ke Metode Pembayaran (PaymentMethod)
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'id_payment_method', 'id_payment_method');
    }

    /**
     * Relasi ke Detail Transaksi (TransactionDetail)
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'id_transaction', 'id_transaction');
    }
}