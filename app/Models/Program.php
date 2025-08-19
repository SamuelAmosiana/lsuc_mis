<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'name', 'description', 'duration_years', 'total_semesters',
        'total_credits', 'fee_per_semester', 'degree_awarded', 'department',
        'is_active', 'created_by'
    ];

    protected $casts = [
        'duration_years' => 'integer',
        'total_semesters' => 'integer',
        'total_credits' => 'decimal:2',
        'fee_per_semester' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // For backward compatibility
    public function programmes(): HasMany
    {
        return $this->students();
    }
}
