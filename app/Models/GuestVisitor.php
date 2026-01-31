<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestVisitor extends Model
{
    protected $fillable = [
        'nim',
        'nama',
        'lab_id',
        'purpose',
        'check_in_time',
    ];

    /**
     * Cast attributes to native types
     */
    protected function casts(): array
    {
        return [
            'check_in_time' => 'datetime',
        ];
    }

    /**
     * Relationship to Lab
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    /**
     * Scope: Get today's visitors
     */
    public function scopeToday($query)
    {
        return $query->whereDate('check_in_time', today());
    }

    /**
     * Scope: Filter by lab
     */
    public function scopeByLab($query, $labId)
    {
        return $query->where('lab_id', $labId);
    }

    /**
     * Scope: Filter by date
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('check_in_time', $date);
    }
}
