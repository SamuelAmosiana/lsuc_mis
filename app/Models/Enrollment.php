<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'program_id',
        'enrollment_type',
        'status',
        'notes',
        'needs_accommodation',
        'accommodation_status',
        'interview_date',
        'interview_time',
        'interview_notes',
    ];

    protected $casts = [
        'interview_date' => 'date',
        'interview_time' => 'datetime',
        'needs_accommodation' => 'boolean',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeNeedsInterview($query)
    {
        return $query->whereNull('interview_date')
                    ->orWhereNull('interview_time');
    }

    public function scopeNeedsAccommodation($query)
    {
        return $query->where('needs_accommodation', true)
                    ->where('accommodation_status', '!=', 'assigned');
    }
}
