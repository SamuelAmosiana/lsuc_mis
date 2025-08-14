<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['hostel_id','room_number','capacity'];

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }
}


