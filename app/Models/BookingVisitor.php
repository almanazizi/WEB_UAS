<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingVisitor extends Model
{
    protected $fillable = [
        'booking_id',
        'nim',
        'nama',
    ];

    /**
     * Relationship to Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
