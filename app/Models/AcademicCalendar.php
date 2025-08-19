<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AcademicCalendar extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'type',
        'color'
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at'
    ];

    /**
     * Scope a query to only include upcoming events.
     */
    public function scopeUpcoming($query, $days = 7)
    {
        return $query->where('start_date', '>=', now())
                    ->where('start_date', '<=', now()->addDays($days))
                    ->orderBy('start_date');
    }

    /**
     * Get the event type in a human-readable format.
     */
    public function getEventTypeAttribute()
    {
        return ucfirst($this->type);
    }
}
