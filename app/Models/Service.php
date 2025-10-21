<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes; 

    protected $primaryKey = 'id_service';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'service_name',
        'description',
        'price',
        'image_path',
        'id_store', 
    ];

    /**
     * Mendefinisikan relasi ke model Store.
     * Satu Service dimiliki oleh satu Store.
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'id_store', 'id_store');
    }
}