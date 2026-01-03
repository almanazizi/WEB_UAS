<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'location',
    ];

    /**
     * Relationships
     */
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
