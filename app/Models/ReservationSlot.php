<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationSlot extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_slot';
    
    protected $guarded = ['id_slot'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'id_store');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'reservation_slot_employee', 'id_slot', 'id_employee');
    }
}