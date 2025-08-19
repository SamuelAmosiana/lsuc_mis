<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    // For backward compatibility
    public function programme(): BelongsTo
    {
        return $this->program();
    }
}


