<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['hostel_id','room_number','capacity'];

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }
    
    public function assignments(): HasMany
    {
        return $this->hasMany(AccommodationAssignment::class);
    }
    
    public function occupants()
    {
        return $this->hasManyThrough(
            User::class,
            AccommodationAssignment::class,
            'room_id',
            'id',
            'id',
            'student_id'
        )->whereNull('accommodation_assignments.check_out')
         ->orWhere('accommodation_assignments.check_out', '>', now());
    }
}


