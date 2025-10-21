<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    /**
     * Nama primary key tabel ini.
     */
    protected $primaryKey = 'id_payment_method';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'method_name',
        'is_active',
    ];
}