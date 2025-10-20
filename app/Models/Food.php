<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Food extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel yang terhubung ke model ini.
     */
    protected $table = 'foods'; // <-- TAMBAHKAN BARIS INI

    protected $fillable = [
        'food_name',
        'price',
        'stock_available',
        'id_store',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'id_store', 'id_store');
    }
}