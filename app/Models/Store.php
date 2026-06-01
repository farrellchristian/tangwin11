<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Tentukan primary key.
     */
    protected $primaryKey = 'id_store';

    /**
     * Tentukan nama tabel jika tidak sesuai standar.
     */
    protected $table = 'stores';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'store_name',
        'address',
        'phone_number',
        'show_on_reservation',
        'store_ip_address',
        'enable_ip_validation',
    ];

    protected $casts = [
        'show_on_reservation'  => 'boolean',
        'enable_ip_validation' => 'boolean',
    ];
}