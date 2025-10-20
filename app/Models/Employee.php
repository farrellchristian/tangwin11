<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'employee_name',
        'photo_path',
        'position',
        'phone_number',
        'join_date',
        'exit_date',
        'is_active',
        'id_store', // Penting untuk relasi
    ];

    /**
     * Tipe data bawaan untuk kolom tertentu.
     */
    protected $casts = [
        'join_date' => 'date',
        'exit_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Mendefinisikan relasi ke model Store.
     * Satu Employee dimiliki oleh satu Store.
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'id_store', 'id_store');
    }
}