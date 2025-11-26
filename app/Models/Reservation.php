<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_reservation';
    protected $guarded = ['id_reservation'];

    // Relasi ke Toko
    public function store()
    {
        return $this->belongsTo(Store::class, 'id_store');
    }

    // Relasi ke Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    // Relasi ke Employee (Yang dipilih customer)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }
}