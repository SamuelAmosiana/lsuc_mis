<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccommodationAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['student_id','room_id','term','year','check_in','check_out'];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}


