<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    /**
     * Nama primary key tabel ini.
     */
    protected $primaryKey = 'id_transaction_detail';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'id_transaction',
        'id_employee', // Capster per item
        'item_type',   // 'service', 'product', 'food'
        'id_service',
        'id_product',
        'id_food',
        'quantity',
        'price_at_sale',
        'subtotal',
    ];

    /**
     * Tipe data bawaan untuk kolom tertentu.
     */
    protected $casts = [
        'quantity' => 'integer',
        'price_at_sale' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relasi ke Transaksi Utama (Transaction)
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaction', 'id_transaction');
    }

    /**
     * Relasi ke Karyawan (Employee) yang mengerjakan item ini
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee', 'id_employee');
    }

    /**
     * Relasi ke Layanan (Service) - Opsional
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id_service');
    }

    /**
     * Relasi ke Produk (Product) - Opsional
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    /**
     * Relasi ke Makanan (Food) - Opsional
     */
    public function food()
    {
        return $this->belongsTo(Food::class, 'id_food', 'id_food');
    }

    /**
     * (Opsional) Accessor untuk mendapatkan item terkait secara dinamis
     * Contoh penggunaan: $detail->item->service_name atau $detail->item->product_name
     */
    public function getItemAttribute()
    {
        switch ($this->item_type) {
            case 'service':
                return $this->service;
            case 'product':
                return $this->product;
            case 'food':
                return $this->food;
            default:
                return null;
        }
    }
}