<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $table = 'students';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'user_id', 'program_id', 'student_id', 'status', 'batch_year', 'current_semester',
        'admission_date', 'phone', 'emergency_contact_name', 'emergency_contact_relation',
        'emergency_contact_phone', 'address', 'date_of_birth', 'gender', 'blood_group',
        'nationality', 'religion', 'passport_number', 'id_card_number', 'bank_account_number',
        'bank_name', 'notes', 'status_updated_by', 'status_updated_at'
    ];

    protected $casts = [
        'admission_date' => 'date',
        'date_of_birth' => 'date',
        'status_updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function statusNotes(): HasMany
    {
        return $this->hasMany(StatusNote::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(StudentDocument::class);
    }

    // Course Relationships
    public function courseRegistrations(): HasMany
    {
        return $this->hasMany(CourseRegistration::class, 'student_id', 'user_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_registrations', 'student_id', 'course_id')
                    ->wherePivot('status', 'approved')
                    ->withPivot('status', 'approved_at');
    }

    // Finance Relationships
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Bill::class);
    }

    // Accommodation Relationships
    public function accommodationAssignments(): HasMany
    {
        return $this->hasMany(AccommodationAssignment::class, 'student_id', 'user_id');
    }

    // Results Relationships
    public function marks(): HasMany
    {
        return $this->hasMany(StudentMark::class, 'student_id', 'user_id');
    }

    // Helper Methods
    public function getCurrentBalance()
    {
        return $this->bills()->sum('balance');
    }

    public function getTotalAmountDue()
    {
        return $this->bills()->sum('total_amount');
    }

    public function getTotalAmountPaid()
    {
        return $this->bills()->sum('amount_paid');
    }

    public function calculateCGPA()
    {
        $marks = $this->marks()->whereNotNull('total')->get();
        if ($marks->isEmpty()) {
            return 0;
        }
        return $marks->avg('total') / 20; // Assuming marks are out of 100, convert to 5.0 scale
    }

    public function getCurrentAccommodation()
    {
        return $this->accommodationAssignments()
                    ->whereNull('check_out')
                    ->orWhere('check_out', '>', now())
                    ->with(['room.hostel'])
                    ->first();
    }
}


