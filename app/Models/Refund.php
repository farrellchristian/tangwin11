<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_reservation',
        'bank_name',
        'account_number',
        'account_name',
        'cancel_reason',
        'amount',
        'status',
        'proof_path',
    ];

    /**
     * Get the reservation associated with the refund.
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'id_reservation', 'id_reservation');
    }
}
