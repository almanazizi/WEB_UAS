<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_id',
        'code',
        'name',
        'spec',
        'condition',
    ];

    /**
     * Relationships
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
