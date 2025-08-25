<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'course';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'course_id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'programme_id',
    ];

    /**
     * Get the programme that owns the course.
     */
    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class, 'programme_id', 'programme_id');
    }

    /**
     * Get the course registrations for the course.
     */
    public function registrations()
    {
        return $this->hasMany(CourseRegistration::class, 'course_id', 'course_id');
    }
    
    /**
     * Get the lecturers who teach this course.
     */
    public function lecturers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'lecturer_course', 'course_id', 'user_id')
            ->where('role', 'lecturer');
    }
}
