<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

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
    ];
}